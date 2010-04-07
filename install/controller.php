<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define('base_path', dirname(__FILE__).'/');
$protocol = explode('/', strtolower($_SERVER['SERVER_PROTOCOL']));
class controller {
    var $config_sample = '../application/config/config_sample.php';
    var $database_sample = '../application/config/database_sample.php';
    var $link="";
    var $banco = '';
    var $servidor ='';
    var $usuario ='';
    var $senha ='';
    var $protocol = '';

    private static $instance = null;

    function __construct() {
        $this->protocol = $this->__protocol();
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

    function __mostraErro($error) {
        $dados = array(
            '{DBNAME}'  => $this->banco,
            '{DBUSER}'  => $this->usuario,
            '{DBSERVER}'=> $this->servidor,
            '{SUPORTE}' => "<a href='http://visie.com.br'>e-Downloads</a>"
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

    function hasConfigFile() {
        $sys_config = '../application/config/config.php';
        return (bool)(file_exists($sys_config));
    }

    /**
     * Inclue na página, o template html passado em $_request_template.
     * @param string $_request_template Nome do arquivo .html que será requisitado
     * @param array $data Array associativo cujas chaves serão passadas como variável
     * para o templete requisitado.
     */
    function loadTemplate($_request_template, $data=array()) {
        if (file_exists(base_path.$_request_template.'.php')) {
            extract($data);
            include($_request_template.'.php');
            die();
        }
    }

    /**
     * Cria o config.php com base_url definida pelo usuário
     * @param <type> $dados Array associativo cujas chaves correspondem as partes
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
     * @param string $uri Url da aplicação, definida pelo usuário
     * @return misc Retorna a url base da aplicação ou false
     */
    function define_base_url($uri) {

        $uri = substr($uri, -1)=='/' ? $uri : $uri . '/';
        $uri = substr($uri, 0, 1)=='/' ? $uri : '/' . $uri;
        $uri_verification  = $uri.'install/index.php';

        $cod = rand(0,100);
        $verify_code = $this->doPost($uri.'install/verify.php','cod='.$cod,$_SERVER['HTTP_HOST']);

        if(trim($verify_code) != md5($cod)) {
            return false;
        }

        return strtolower($this->protocol) . $_SERVER['HTTP_HOST'] . $uri;
    }

    function instalar() {
        $tpedidos = <<<eof
        DROP TABLE IF EXISTS `pedidos`;
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
    }

    function __protocol() {
        $protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
        return $protocol[0] . '://';
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
            $response=split("\r\n\r\n",$response);
            $header=$response[0];
            $responsecontent=$response[1];
            if(!(strpos($header,"Transfer-Encoding: chunked")===false)) {
                $aux=split("\r\n",$responsecontent);
                for($i=0;$i<count($aux);$i++)
                    if($i==0 || ($i%2==0))
                        $aux[$i]="";
                $responsecontent=implode("",$aux);
            }//if
            return chop($responsecontent);
    }//else
}//function-doPost


}
