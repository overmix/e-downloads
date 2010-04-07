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
        $data = array('title'=>'Iniciando a instalação');
        $ins->loadTemplate('conf_db', $data);
        break;    
    case '2':
        if($_SERVER['REQUEST_METHOD']=='POST'){
            // Define a url base que será usada pelo instalador e incluída no config.php
            $url = $ins->define_base_url($_POST['appurl']);
            if (!$url) {
                $data = array('title'=>'e-Downloads', 'mensagem'=> "A URI <b>{$_POST['appurl']}</b> não é válida ou não per
                    tence ao diretório da aplicação.");
                $ins->loadTemplate('erro', $data);
            }
            $ins->servidor  = $_POST['dbserver'];
            $ins->usuario   = $_POST['dbuser'];
            $ins->senha     = $_POST['dbpass'];
            $ins->banco     = $_POST['dbname'];
        }
        
        if (!$ins->link) $ins->conectar();
        
        if ($ins->link) {
            $dados = array('{APPURL}'  =>  $url);

            // tenta gravar no arquivo de configuração
            if ($ins->makeConfigFile($dados)) {
                // mostra o mensagem do início da instalação, com o botão para instalar
                $data = array('title'=>'Finalizar instalação');
                $ins->loadTemplate('conf_finalizar', $data);
            }else{
                // carrega o config_sample numa textarea e pedo para o próprio usuário gravar as informações
                $data = array('title'=>'e-Downloads', 
                    'title_msg'=>'Não foi possível gravar o arquivo config.php',
                    'content_config'=> htmlentities($ins->getFile($ins->config_sample, $dados)));
                $ins->loadTemplate('conf_manual', $data);
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

