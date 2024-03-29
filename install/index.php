<?php
include 'controller.php';
$ins = controller::getInstance();
$not_writable = $ins->verify_requeriments();

$root_path = dirname(dirname(__FILE__)).'/';

if (!isset($_GET['passo'])) {
    $data = array('title'=>'Iniciando a instalação', 'mensagem'=>$not_writable);
    $ins->loadTemplate('conf_home', $data);
    die();
}

switch ($_GET['passo']) {
    case '1':
        $url = $ins->define_base_url();
        if($not_writable){
            $data = array(
                'title'     => "e-Downloads", 
                'mensagem'  => $not_writable
                );
            $ins->loadTemplate('conf_home', $data);        
        }
        
        if (!$url) {
            $data = array('title'=>'e-Downloads', 'mensagem'=> "A URI <b>{$_POST['appurl']}</b> não é válida ou não per
                tence ao diretório da aplicação.");
            $ins->loadTemplate('erro', $data);
        }else{
            $dados = array(
                '{APPURL}'  =>  $url
            );

            // tenta gravar no arquivo de configuração
            if ($ins->hasConfigFile('config.php') OR $ins->makeConfigFile($dados)) {
                // mostra o mensagem do início da instalação, com o botão para instalar
                $data = array('title'=>'e-Downloads');
                $ins->loadTemplate('conf_db', $data);
            }else{
                // carrega o config_sample numa textarea e pedo para o próprio usuário gravar as informações
                $data = array(
                    'title'         =>'e-Downloads',
                    'title_msg'     =>"Desculpe, não foi possível gravar o arquivo config.php por falta de permissão. 
                    Mas não se preocupe, é muito simples resolver isso.",
                    'content_config'=> html_entity_decode($ins->getFile($ins->config_sample, $dados)),
                    'config_msg'    =>"<ul>
                        <li>Crie o arquivo config.php manualmente dentro da pasta '<em>raiz_da_aplicacao</em>/application/config/', e cole nele o código abaixo:</li>
                        </ul>",
                    'redirect'      =>"index.php?passo=1",
                );
                $ins->loadTemplate('conf_manual', $data);
            }
            $data = array('title'=>'Iniciando a instalação');
            $ins->loadTemplate('conf_home', $data);
        }
        break;    
    case '2':
        if($_SERVER['REQUEST_METHOD']=='POST'){            
            $connection_data =  array(
                'dbserver'  =>  $_POST['dbserver'],
                'dbuser'    =>  $_POST['dbuser'],
                'dbpass'    =>  $_POST['dbpass'],
                'dbname'    =>  $_POST['dbname'],
                'useremail' =>  $_POST['useremail'],
            );
            $ins->set_session($connection_data);            
        }        
        
        $ins->servidor  = $ins->get_session('dbserver');
        $ins->usuario   = $ins->get_session('dbuser');
        $ins->senha     = $ins->get_session('dbpass');
        $ins->banco     = $ins->get_session('dbname');
        $ins->email     = $ins->get_session('useremail');
        
        if (!$ins->valid_email($ins->email)) {
            $data = array('title'=>'e-Downloads', 'mensagem'=> 'O email digitado não é válido!');
            $ins->loadTemplate('erro', $data);
        }

        // Tenta conectar com o banco criado
        $ins->conectar();

        // se conectou, vai para a aplicação
        if ($ins->link) {
            $senha = $ins->instalar();
            if($senha){
                $dados = array(
                    '{DBHOST}'  =>  $ins->servidor,
                    '{DBUSER}'  =>  $ins->usuario,
                    '{DBPASS}'  =>  $ins->senha,
                    '{DBNAME}'  =>  $ins->banco,

                );
                // tenta gravar no arquivo de configuração
                if ($ins->hasConfigFile('database.php') OR $ins->makeDatabaseFile($dados)) {
                    // mostra o mensagem do início da instalação, com o botão para instalar
                    $data = array(
                        'title'     => 'e-Downloads',
                        'redirect'  => $ins->base_url.'index.php/inicio',
                        'email'     => $ins->email,
                        'senha'     => $senha,
                    );
                    chmod($root_path . "application/config/", 0755);
                    
                    $ins->loadTemplate('conf_finalizar', $data);
                }else{
                    // carrega o config_sample numa textarea e pedo para o próprio usuário gravar as informações
                    $data = array(
                        'title'         =>'e-Downloads',
                        'title_msg'     =>'Estamos quase lá! porém ainda temos
uma pendência. Apesar de conseguirmos conectar ao banco de dados com sucesso, não foi possível gravar as informações
necessárias no arquivo database.php, por falta de permissão.<br />Siga a informação abaixo e seja feliz novamente:',
                        'content_config'=> html_entity_decode($ins->getFile($ins->database_sample, $dados)),
                        'config_msg'    =>"<ul>
                            <li>Crie o arquivo database.php manualmente dentro da pasta '<em>raiz_da_aplicacao</em>/application/config/', e cole nele o código abaixo:</li>
                            </ul>",
                        'redirect'      =>"index.php?passo=2",
                    );
                    $ins->loadTemplate('conf_manual', $data);
                }
            }else{
                $data = array(
                    'title'     =>'e-Downloads',
                    'mensagem'  =>'Desculpe mas não foi possível criar as tabelas no banco de dados.<br />Tente novamente mais tarde.'
                );
                $ins->loadTemplate('erro', $data);
            }
        }
        break;
   
    default:
        $data = array('title'=>'Iniciando a instalação');
        $ins->loadTemplate('conf_home', $data);
        break;
}

