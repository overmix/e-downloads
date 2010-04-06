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
        $ins->servidor  = $_POST['dbserver'];
        $ins->usuario   = $_POST['dbuser'];
        $ins->senha     = $_POST['dbpass'];
        $ins->banco     = $_POST['dbname'];

        if (!$ins->link) $ins->connect();
        
        if ($ins->link) {
            $dados = array('{APPURL}'=>$_POST['appurl']);

            // tenta gravar no arquivo de configuração
            if ($ins->install($dados)) {
                // mostra o mensagem do início da instalação, com o botão para instalar
                $data = array('title'=>'Finalizar instalação');
                $ins->loadTemplate('conf_finalizar', $data);
            }else{
                // carrega o config_sample numa textarea e pedo para o próprio usuário gravar as informações
                $data = array('title'=>'e-Downloads', 'content_config'=> htmlentities($ins->getSample($dados)));
                $ins->loadTemplate('conf_manual', $data);
            }

        }
        break;
    
    case '3':
        ;
        break;
    
    default:
        $data = array('title'=>'Iniciando a instalação');
        $ins->loadTemplate('conf_home', $data);
        break;
}

