<?php
include 'controller.php';
$ins = controller::getInstance();

if (!isset($_GET['passo'])) {
    $data = array('title'=>'Iniciando a instalação');
    $ins->loadTemplate('conf_home', $data);
    die();
}

switch ($_GET['passo']) {
    case '1':
        $url = $ins->define_base_url();
        if (!$url) {
            $data = array('title'=>'e-Downloads', 'mensagem'=> "A URI <b>{$_POST['appurl']}</b> não é válida ou não per
                tence ao diretório da aplicação.");
            $ins->loadTemplate('erro', $data);
        }else{
            $dados = array(
                '{APPURL}'  =>  $url
            );

            // tenta gravar no arquivo de configuração
            if ($ins->makeConfigFile($dados)) {
                // mostra o mensagem do início da instalação, com o botão para instalar
                $data = array('title'=>'e-Downloads');
                $ins->loadTemplate('conf_db', $data);
            }else{
                // carrega o config_sample numa textarea e pedo para o próprio usuário gravar as informações
                $data = array(
                    'title'         =>'e-Downloads',
                    'title_msg'     =>'Não foi possível gravar o arquivo database.php',
                    'content_config'=> htmlentities($ins->getFile($ins->config_sample, $dados)),
                    'config_msg'    =>"Você pode criar o config.php manualmente e colar o seguinte texto nele.<br />
                        O arquivo config.php deve ficar dentro da pasta 'application/config/' da sua aplicação.",
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
            $ins->servidor  = $_POST['dbserver'];
            $ins->usuario   = $_POST['dbuser'];
            $ins->senha     = $_POST['dbpass'];
            $ins->banco     = $_POST['dbname'];
            $ins->email     = $_POST['useremail'];

            if (!$ins->valid_email($ins->email)) {
                $data = array('title'=>'e-Downloads', 'mensagem'=> 'O email digitado não é válido!');
                $ins->loadTemplate('erro', $data);
            }
        }

        // Tenta conectar com o banco criado
        $ins->conectar();

        // se conectou, vai para a aplicação
        if ($ins->link) {
            if($ins->instalar()){
                $dados = array(
                    '{DBHOST}'  =>  $ins->servidor,
                    '{DBUSER}'  =>  $ins->usuario,
                    '{DBPASS}'  =>  $ins->senha,
                    '{DBNAME}'  =>  $ins->banco,

                );
                // tenta gravar no arquivo de configuração
                if ($ins->makeDatabaseFile($dados)) {
                    // mostra o mensagem do início da instalação, com o botão para instalar
                    $data = array(
                        'title'     =>'e-Downloads',
                        'redirect'  =>$ins->base_url,
                    );
                    $ins->loadTemplate('conf_finalizar', $data);
                }else{
                    // carrega o config_sample numa textarea e pedo para o próprio usuário gravar as informações
                    $data = array(
                        'title'         =>'e-Downloads',
                        'title_msg'     =>'Não foi possível gravar o arquivo database.php',
                        'content_config'=> htmlentities($ins->getFile($ins->database_sample, $dados)),
                        'config_msg'    =>"Você pode criar o database.php manualmente e colar o seguinte texto nele.<br />
                            O arquivo database.php deve ficar dentro da pasta 'application/config/' da sua aplicação.",
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
    case '3':
        $ins->instalar();
        if (!$ins->hasConfigFile()) {
            $data = array('title'=>'e-Downloads', 
                'title'=>'e-Downloads',
                'mensagem'=> 'Não foi possível localizar o arquivo config.php');
            $ins->loadTemplate('erro', $data);
        }

        $ins->instalar();
        break;
    
    default:
        $data = array('title'=>'Iniciando a instalação');
        $ins->loadTemplate('conf_home', $data);
        break;
}

