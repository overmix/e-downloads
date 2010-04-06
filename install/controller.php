<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define('base_path', dirname(__FILE__).'/');
$protocol = explode('/', strtolower($_SERVER['SERVER_PROTOCOL']));
define('base_url', $protocol[0].'://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
class controller {
    var $link="";
    var $banco = '';
    var $servidor ='';
    var $usuario ='';
    var $senha ='';
    private static $instance = null;

    function __construct() {
        $sys_config = '../application/config/config.php';
        if(!file_exists($sys_config)) {
            $data = array(
                'title' => 'Configurando a instalação'
            );
            $this->loadTemplate('install', $data);
        }


    }

    function connect() {
        $this->link = @mysql_connect($this->servidor, $this->usuario, $this->senha);
        $error = array();
        if (!$this->link) {
            $error[]="Não foi possível estabelecer conexão com o servidor.";
        } elseif (!mysql_select_db($this->banco, $this->link)) {
            $error[]= "Não foi possível selecionar base de dados.";
        }
        $string = '';
        if (count($error)) {
            foreach ($error as $item) {
                $string .= $item . '<br />';
            }
            $data = array('mensagem'=>$string);
            $this->loadTemplate('erro', $data);
            die();
        }
        //mysql_close($this->link);
        return true;
    }

    /**
     *
     * Cria uma nova instancia desta classe, e não necessita de um destrutor, pois quando o usuário
     * muda de página, esta instancia é destruída automaticamente.
     * @exemplo $objInstancia = GeralClass::getInstance()->metodoQualquer()
     */
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Evita clone da classe
     */
    public function __clone() {
        trigger_error('Clone não é permitido.', E_USER_ERROR);
    }

    /**
     * Evita deserializing da classe
     */
    public function __wakeup() {
        trigger_error('Deserializing não é permitido.', E_USER_ERROR);
    }

    function loadTemplate($_request_template, $data=array()) {
        if (file_exists(base_path.$_request_template.'.php')) {
            extract($data);
            include($_request_template.'.php');            
            die();
        }
    }

    function install($dados){
        $templ = $this->getSample($dados);
        return (bool)@file_put_contents('../application/config/config.php', $templ);
    }

    function getSample($dados){
        // Lê o conteúdo do sample
        $file = @file_get_contents('../application/config/config_sample.php');

        // Substitue a configuração pela informação do usuário
        $templ = strtr($file, $dados);
        
        return $templ;
    }
}
