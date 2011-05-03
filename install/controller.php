<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(0);
$protocol = explode('/', strtolower($_SERVER['SERVER_PROTOCOL']));
class controller {
    var $config_sample   = '../application/config/config_sample.php';
    var $database_sample = '../application/config/database_sample.php';
    var $link="";
    var $banco = '';
    var $servidor ='';
    var $usuario ='';
    var $senha ='';
    var $protocol = '';
    var $email = '';
    var $sessID = '';
    var $base_path = "";
    
    private static $instance = null;

    function __construct() {
        session_start();
        $this->protocol  = $this->__protocol();
        $this->base_url  = $this->define_base_url();
        $this->base_path = dirname(__FILE__).'/';    
    }

    /**
     * Testa conexão com a base de dados.
     * @return misc True caso a conexão tenha ocorrido com sucesso, ou mata o
     * processamento e redireciona para a página de erro com uma mensagem.
     */
    function conectar() {
        $this->link = @mysql_connect($this->servidor, $this->usuario, $this->senha);
        $error = '';
        if (!$this->link) {
            $error = "Não foi possível estabelecer conexão com o servidor.";
        } elseif (!mysql_select_db($this->banco, $this->link)) {
            $error = "Não foi possível selecionar base de dados.";
        }

        if ($error) {
            $this->__mostraErro($error);
        }
        //mysql_close($this->link);
        return true;
    }

    /**
     * Mostra uma mensagem gernérica de erro para erro de conexão
     * @param sring $error Título da mensagem de erro
     */
    function __mostraErro($error) {
        $dados = array(
            '{DBNAME}'  => $this->banco,
            '{DBUSER}'  => $this->usuario,
            '{DBHOST}'  => $this->servidor,
            '{SUPORTE}' => "<a href='http://github.com/pagseguro/e-downloads'>e-Downloads</a>"
        );
        $template = $this->getFile('error_content.txt', $dados);

        $data = array('title'=>$error, 'mensagem'=>$template);
        $this->loadTemplate('erro', $data);
        die();
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

    /**
     * Verifica se o arquivo de configuração existe
     * @return bool True caso o arquivo de configuração exista, ou False
     */
    function hasConfigFile($filename) {
        $sys_config = '../application/config/'.$filename;
        return (bool)(file_exists($sys_config));
    }

    /**
     * Inclue na página, o template html passado em $_request_template.
     * @param string $_request_template Nome do arquivo .html que será requisitado
     * @param array $data Array associativo cujas chaves serão passadas como variável
     * para o templete requisitado.
     */
    function loadTemplate($_request_template, $data=array()) {
        if (file_exists($this->base_path . $_request_template.'.php')) {
            extract($data);
            include($_request_template.'.php');
            die();
        }
    }

    /**
     * Cria o config.php com base_url definida pelo usuário
     * @param array $dados Array associativo cujas chaves correspondem as partes
     * da string que serão substituídas no config_sample.php pelos valores deste
     * array
     * @return bool Retorna true caso a substituição seja feita com sucesso, ou fase.
     */
    function makeConfigFile($dados) {
        $templ = $this->getFile($this->config_sample, $dados);
        return (bool)@file_put_contents('../application/config/config.php', $templ);
    }

    function makeDatabaseFile($dados) {
        $templ = $this->getFile($this->database_sample, $dados);
        return (bool)@file_put_contents('../application/config/database.php', $templ);
    }

    /**
     * Substitue partes do texto no config_sample.php pelas informações passadas
     * em $dados.
     * @param array $replaced Array associativo cujas chaves correspondem as partes
     * da string que serão substituídas no config_sample.php pelos valores deste
     * array
     * @return string conteúdo do config_sample.php com as devidas substituições
     */
    function getFile($filepath, $replaced=array()) {
    // Lê o conteúdo do sample
        $file = @file_get_contents($filepath);

        // Substitue a configuração pela informação do usuário
        $templ = strtr($file, $replaced);

        return $templ;
    }

    /**
     * Define a url base que será utilizada pelo sistema
     * @return misc Retorna a url base da aplicação ou false
     */
    function define_base_url() {
        $uri = explode('/', $_SERVER['PHP_SELF']);
        if (substr($_SERVER['PHP_SELF'],0,1)=='/') unset($uri[0]);
        if (count($uri)) unset($uri[count($uri)]);
        if (count($uri)) unset($uri[count($uri)]);
        else return false;

        $uri = '/'.implode('/', $uri).'/';
        return strtolower($this->protocol) . $_SERVER['HTTP_HOST'] . $uri;
    }

    /**
     * Email Validation
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    function valid_email($address)
    {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
    }

    function instalar() {
        $tpedidos = <<<eof
        CREATE TABLE `pedidos` (
          `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
          `id_produto` int(11) NOT NULL,
          `id_usuario` int(11) NOT NULL,
          `pedido_em` datetime NOT NULL,
          `liberado_em` datetime DEFAULT NULL,
          `downloads` int(11) DEFAULT '0',
          `usar_ate` date DEFAULT NULL,
          `limite` int(11) NOT NULL DEFAULT '0',
          `status` enum('Ativo','Bloqueado') NOT NULL DEFAULT 'Bloqueado',
          `form_pgs` text NOT NULL,
          PRIMARY KEY (`id_pedido`) USING BTREE
        ) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
eof;
        $query = mysql_query("DROP TABLE IF EXISTS `pedidos`", $this->link);
        if(!$query) return false;
        $query = mysql_query($tpedidos, $this->link);
        if(!$query) return false;

        $tprodutos = <<<eof
        CREATE TABLE `produtos` (
          `id_produto` int(11) NOT NULL AUTO_INCREMENT,
          `nome` varchar(128) NOT NULL,
          `arquivo` varchar(128) NOT NULL,
          `preco` float NOT NULL,
          `image` varchar(128) NOT NULL,
          `atualizado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `status` tinyint(1) NOT NULL DEFAULT '1',
          `descricao` text NOT NULL,
          PRIMARY KEY (`id_produto`) USING BTREE
        ) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
eof;
        $query = mysql_query("DROP TABLE IF EXISTS `produtos`", $this->link);
        if(!$query) return false;
        $query = mysql_query($tprodutos, $this->link);
        if(!$query) return false;

        $tusuarios = <<<eof
        CREATE TABLE `usuarios` (
          `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
          `nome` varchar(128) NOT NULL,
          `email` varchar(128) NOT NULL,
          `senha` varchar(128) NOT NULL,
          `telefone` varchar(128) NOT NULL,
          `cadastrado_em` datetime NOT NULL,
          `group` int(11) NOT NULL DEFAULT '0',
          `controle` varchar(255) NOT NULL,
          `status` enum('Ativo','Bloqueado') NOT NULL,
          PRIMARY KEY (`id_usuario`)
        ) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
eof;
        $query = mysql_query("DROP TABLE IF EXISTS `usuarios`", $this->link);
        if(!$query) return false;
        $query = mysql_query($tusuarios, $this->link);
        if(!$query) return false;

        $query = mysql_query("LOCK TABLES `usuarios` WRITE", $this->link);
        if(!$query) return false;
        $senha = $this->geraSenha(6);
        $query = mysql_query("INSERT INTO `usuarios` VALUES (1,'Administrador','{$this->email}','".md5($senha)."','(xx)0000-00000','0000-00-00 00:00:00',1,'first_login','Ativo')", $this->link);
        if(!$query) return false;
        $query = mysql_query("UNLOCK TABLES", $this->link);
        if(!$query) return false;
        return $senha;
    }

    function __protocol() {
        $protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
        return $protocol[0] . '://';
    }

    /**
     * geraSenha() Gera uma senha aleatória com 6 digitos.
     * @param int $digit número de digitos pegos do rash
     * @return string Retorna uma senha aleatória gerada a partir de um número randômico
     */
    function geraSenha($digit = 6) {
        $controle = rand(0,1000000000);
        return substr(md5($controle), 0, $digit);
    }

    function geraToken($digit = 15){
	    return substr(hash('sha512', uniqid()), 0 , $digit);
    }

    function doPost($uri,$postdata,$host) {
        $da = fsockopen($host, 80, $errno, $errstr);
        $response = '';
        if (!$da) {
            echo "$errstr ($errno)<br/>\n";
            echo $da;
        }
        else {
            $salida ="POST $uri  HTTP/1.1\r\n";
            $salida.="Host: $host\r\n";
            $salida.="User-Agent: PHP Script\r\n";
            $salida.="Content-Type: application/x-www-form-urlencoded\r\n";
            $salida.="Content-Length: ".strlen($postdata)."\r\n";
            $salida.="Connection: close\r\n\r\n";
            $salida.=$postdata;
            fwrite($da, $salida);
            while (!feof($da))
                $response.=fgets($da, 128);
            $response=explode("\r\n\r\n",$response);
            $header=$response[0];
            $responsecontent=$response[1];
            if(!(strpos($header,"Transfer-Encoding: chunked")===false)) {
                $aux=explode("\r\n",$responsecontent);
                for($i=0;$i<count($aux);$i++)
                    if($i==0 || ($i%2==0))
                        $aux[$i]="";
                $responsecontent=implode("",$aux);
            }//if
            return chop($responsecontent);
        }//else
    }//function-doPost


    /*
     * set_session() adiciona valores a sessão passada como array
     * 
     *
     */
    function set_session($sess_data){
        //if(empty($this->sessID)) session_start() or exit(basename(__FILE__).'(): Could not start session');     
        foreach ($sess_data as $k=>$v):
            $_SESSION[$k] = $v;
        endforeach;
    }
    /*
     * sessdata() retorna um valor contido na sessão $item
     */
    function get_session($item){
        //if(empty($this->sessID)) session_start() or exit(basename(__FILE__).'(): Could not start session');     
        if(isset($_SESSION[$item])){
            return $_SESSION[$item];
        }        
    }
    
    function destroy_session(){
        return session_destroy();
    }
    
    function clear_session(){
        $_SESSION = array();
    }

    function verify_requeriments(){
        $file_path  = dirname($this->base_path) . "/uploads/arquivo";
        $image_path = dirname($this->base_path) . "/uploads/image";
        $lista      = array();
        if (!is_writable($file_path)){
            array_push($lista,"<li>Dê permissão de escrita na pasta <em>raiz_da_aplicacao</em>/uploads/arquivo</li>");
        }
        if (!is_writable($image_path)){
            array_push($lista,"<li>Dê permissão de escrita na pasta <em>raiz_da_aplicacao</em>/uploads/image</li>");
        }
        
        if (!function_exists("gd_info")) {        
            array_push($lista,"<li><span class='alert'>Instale a biblioteca GD para php.</span></li>");
        }

        if (count($lista)){
            return "Você precisas dar permissão de escrita nos diretórios listados abaixo:<br /><ul>". implode("",$lista) ."</ul>";
        }    
    }
}

/*
function exception_handler($exception) {
    echo "Uncaught exception: " , $exception->getMessage(), "\n";
}
set_exception_handler('exception_handler');    
*/
