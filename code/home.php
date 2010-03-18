<?php
global $autoload;
global $msgObject;

include 'ice/app.php';
include 'ice/model.php';
include 'app/config.php';

# O Path Info nunca pode ficar vazio
if(!array_key_exists('PATH_INFO', $_SERVER))
	$_SERVER['PATH_INFO'] = '/';

#Diz ao sistema para não guarda cache
noCache();

#Inicia a Sessão
session_start();

#Carrega helpers e librarys
ice_autoload_componnets($autoload);

/**
 * Carregando a biblioteca de mensagens na inicialização que estará disponível para
 * todas as classes subsequentes.
 * @var $msgObject - Variável que será usada dentro das classes que se utilizarão da biblioteca
 * @example /template/form-usuario.php - line 33/55
 * global $msgObject;
 * $msgObject->add('mensagem', 'tipo');
 * @author ldmotta
 */
$msgObject = new Messages();

include 'app/model.php';

$routes = array(
    // Cadastro do usuario
    '^/cadastro/?$'     => 'Cadastro',
    '^/downloads?/?$'   => 'Download',

    // Deve estar sempre por último
    '^/?$'              => 'Home',
);

#Carrega as Rotas do sitema
app($routes, $_SERVER['PATH_INFO']);

#Carre o controle do sistema corrente
function __autoload($class){
	global $routes;
	ice_autoload($class, $routes);
}

/**
 * Faz a segurança das URLs, basta extender
 */
class Secure {
    public function __construct() {
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        $uri = array_map('strtolower', $uri);

        // Se tiver admin na uri e não for admin, cai fora
        $lock_admin = (bool)(in_array('admin', $uri) AND !isAdmin());

        // Se não tiver admin na uri e for o admin, ou não estiver logado, não entra
        $lock_comun = (bool)(!in_array('admin', $uri) AND isAdmin());

        $login_page = $lock_admin?'admin':'';

        if ($lock_comun OR $lock_admin  OR !isLogged()) {
            $_SESSION['last_url'] = ''.$_SERVER['PATH_INFO'];
            header('Location: ' . BASE_URL . $login_page);
        }
    }
}