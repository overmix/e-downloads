<?php
/**
 * retorna o caminho absoluto da pasta de uploads
 * @return string
 */
function uploadPath() {
    $ci =& get_instance();
    return FCPATH . $ci->config->item('upload_path');
}

/**
 * retorna o caminho absoluto da pasta de uploads
 * @return string
 */
function uploadUrl() {
    $ci =& get_instance();
    return base_url() . $ci->config->item('upload_path');
}

/**
 *
 * Retorna true caso exista algum valor na sessão email e $dados seja = FALSE, ou
 * retorna a sesão completa do usuário logado quando $dados = TRUE
 * @param boolean $dados Define se o retorno será booleano ou um objeto com os dados
 * da sesão do usuário logado
 * @return misc
 */
function logged ($dados=FALSE) {
    $ci =& get_instance();

    if ($dados) {
        if( (bool)(isset($ci->session->userdata['email']) AND $ci->session->userdata['email']) )
            return (object)$ci->session->userdata;
    }else{
        return (bool)(isset($ci->session->userdata['email']) AND $ci->session->userdata['email']);
    }
}

/**
 *
 * @param String $media = nome do arquivo dentro da url uploads
 * @return String
 */
function getProductUrlById($id) {
    $ci =& get_instance();
    if(!$id) return;
    $product = getProductById($id);
    return base_url().$ci->config->item('upload_path') . 'image/' . $product['image'];
}

/**
 * Seta a sessão last_uri com o valor passado em $uri
 * @param string $uri Uri de redirecionamento
 */
function setLastUri($uri='')
{
    $ci =& get_instance();
    $ci->session->set_userdata(array('last_uri'=>$uri));
}

function getLastUri($default='home')
{
    $ci =& get_instance();
    $last_uri = $ci->session->userdata('last_uri');
    $segment = $last_uri ? $last_uri : $default;
    setLastUri('');
    return $segment;
}

/**
 * retorna a url absoluta da miniatura de uma imagem ou vídeo gravado pelo usuário
 * @param int $id
 * @return string
 */
function getThumbUrlById($id) {
    $ci =& get_instance();
    if(!$id) return;
    $product = getProductById($id);
    $thumb = imgToThumb($product['image']);
    return uploadUrl() . "image/" . $thumb;
}

/**
 * retorna o caminho absoluto da miniatura de uma imagem gravada pelo usuário
 * @param int $id
 * @return string
 */
function getThumbPathById($id) {
    if(!$id) return;
    $media = getProductById($id);
    $thumb = imgToThumb($media['media_url']);
    return uploadPath() . "image/" . $thumb;
}


/**
* seleciona o nome da imagem/url do vídeo no banco de dados
* @param int $id
* @return array ( tipo(imagem ou vídeo), nome da imagem/url do vídeo )
*/
function getProductById($id) {
    if(!$id) return;
    $ci =& get_instance();
    return $ci->db->getwhere('produtos', array('id_produto' => $id))->row_array();
}

/**
 * converte a nomenclatura da imagem para nomenclatura dos thumbs "imagem_thumb.ext"
 * @param string $filename
 * @return string
 */
function imgToThumb($filename) {
    $ci =& get_instance();
    $arr_file = explode('.', $filename);
    $ext = array_pop($arr_file);
    return implode('.',$arr_file) . $ci->config->item('prefix_thumb') . '.' . $ext;
    /*
    $ext = getExtension($filename);
    return str_replace($ext, '', $filename) . "_thumb" . $ext;
    */
}

/**
 * retorna a extensão do arquivo passado em $filename
 * @param string $filename
 * @return string
 */
function getExtension($filename, $doted='.') {
    $x = explode('.', $filename);
    return trim($doted.end($x));
}

/**
 * getDescription Retorna a descrição do produto cortada ou inteira, dependendo do valor de $length
 * @param int $id Id do produto
 * @param int $length Tamanho da string de retorno
 * @return string Descrição do produto cortada ou inteira
 */
function getDescription($id, $length=0)
{
    $ci =& get_instance();
    if(!$id) return;
    $product = getProductById($id);
    $description = $length ? trim(substr($product['descricao'], 0, $length)) .'...' : $product['descricao'];
    return $description;
}

/**
 * make_path() Recebe um endereço de um diretório e cria caso não exista
 * @param string $folder Caminho do diretório a ser criado
 * @return string $folder Retorna o endereço do diretório criado ou false caso não consiga criar
 * @author ldmotta
 */
function make_path($folder) {
    $pasta='';
    $f = explode('/', $folder);
    foreach ($f as $p) {
        $pasta .= $p . '/';
        if (!is_dir($pasta)) {
            try {
                mkdir($pasta, 0777);
            }catch (Exception $e) {return false;}
        }
    }
    return $folder;
}

/**
 * isAdmin Verifica se o usuário logado é administrador
 * @return boolean Retorna True caso o usuário logado seja administrador
 */
function isAdmin(){
    $ci =& get_instance();
    $query = $ci->db->get_where( 'usuarios', array('id_usuario'=>$ci->user->userID(), 'group'=>1));
    return (bool)$query->num_rows();
}

function pedidoLiberado($userID, $pedidoID)
{
    $ci =& get_instance();
    $where = array(
        'id_usuario' =>  $userID,
        'id_pedido' =>  $pedidoID,
        'DATEDIFF(liberado_em, pedido_em) >= ' => 0,    // que tenha sido liberado
    );
    $ci->db->select('pedidos.liberado_em');
    $return = $ci->db->getwhere('pedidos', $where)->row_array();
    if (!count($return)) {
        return array();
    }
    return $return;
}

function is_date($date)
{
	if (!$date) return FALSE;
	$Stamp = strtotime( $date );
	if(!$Stamp) return FALSE;
	$Month = date( 'm', $Stamp );
	$Day   = date( 'd', $Stamp );
	$Year  = date( 'Y', $Stamp );
	return checkdate($Month,$Day,$Year);
}

function deleteImage($filename)
{
    $image_path = uploadPath() . 'image/';
    $file = $image_path . $filename;
    if (file_exists($file)) {
        unlink($file);
    }
    $file = $image_path . imgToThumb($filename);
    if (file_exists($file)) {
        unlink($file);
    }
}

function getFileType($file_type){
    $file_type = preg_replace("/^(.+?);.*$/", "\\1", $file_type);
    return strtolower($file_type);    
}

function is_image($path)
{
    $images = explode(',',"gif,png,jpg,bmp,jpe,jpeg");
    foreach ($images as $value) {
        if (image_type_to_extension(intval(@exif_imagetype($path)), false) != $value)
        {
            return false;
        }
    }
    return true;
}

function deleteArquivo($filename){
    $file_path = uploadPath() . 'arquivo/';
    $file    = $file_path . $filename;
    if (file_exists($file)) {
        unlink($file);
    }
}

/*
 * Função que formata de acordo com o formato da data passada.
 *
 * @uses echo formataData('01/02/2010'); // output 2010-02-01
 * @uses echo formataData('2010-02-01'); // output 01/02/2010
 *
 * @param string $date
 * @return void
 * @author Igor Escobar
 */
function dateDb($date){
    return (strstr($date, '-')) ? implode('/', array_reverse(explode('-',$date))) : implode('-', array_reverse(explode('/',$date)));
}

function formataData($formato, $datetime){
    $month = substr($datetime,5,2);
    $date = substr($datetime,8,2);
    $year = substr($datetime,0,4);
    $hour = substr($datetime,11,2);
    $minutes = substr($datetime,14,2);
    $seconds = substr($datetime,17,4);
    return date($formato, mktime($hour,$minutes,$seconds,$month,$date,$year));
}

function mandaEmail($de, $para, $assunto, $mensagem, $nome='')
{
    $ci =& get_instance();
    $ci->load->library('email');
    
    $config['charset']      = 'iso-8859-1';
    $config['protocol']     = 'smtp';
    $config['smtp_port']    = '25';
    $config['smtp_host']    = 'localhost';
    $config['mailtype']     = 'html';
    
    $ci->email->initialize($config);
    $ci->email->from($de, $nome);
    $ci->email->to($para);
    $ci->email->subject(utf8_decode($assunto));

    $msg = utf8_decode($mensagem);
    
    $ci->email->message($msg);
    try {
        $ci->email->send();
        return true;
    } catch (Exception $e) {}
}

/**
 * Varre um arquivo, procurando qualquer coincidência do texto passado
 * como chave de uma array associativo, e substitue estas
 * ocorrencias pelos valores deste mesmo array.
 * @param string $file caminho do arquivo de template para o email
 * @param array $dados Array  associativo contendo chave e valor, onde a chave
 * corresponde ao item entre chaves("{}") no template
 * @example loadTemplate('template_mail.txt', Array('nome' => 'Luciano')).
 * Imprime o texto 'Luciano' na posição {nome} do template de email
 * @return string Retorna um template com as devidas substituições.
 */
function loadTemplate ($file, $dados)
{
    if (!file_exists($file)) return false;

    $template = file_get_contents($file, 'r');
    foreach ($dados as $key => $value){
      $template = str_replace('{'.$key.'}', str_replace("'","",$value), $template);
    }
    return $template;
}

/**
 * Lista todos os arquivos em uma pasta passada em $path
 * @param string $path Caminho completo dos arquivos que serão listados
 */
function getFilesByPath($path='')
{
    if (!is_dir($path)) return array();
    $iterator = new DirectoryIterator($path);
    
    $files = array();
    foreach ( $iterator as $entry ) {
        if (!$entry->isFile()) continue;
        $files[] = $entry->getFilename();
    }
    return $files;
}
/**
 * Simplifica uma string, retirando espaços ou trocando por anderline, também
 * retira os acentos desta palavra.
 * @param string $string Texto a ser simplificado
 * @param bool $spaces True para deixar os espaços, False para retira-los ou
 * uma string para substituir os espaços.
 * @param int $alter 0 para manter a string como está, 1 para converter em
 * uppercase e -1 para converter em lowercase.
 * @return string Retorna a string tratada.
 */
function simplificaString ($string='',$spaces=false, $alter=0) {
    $return = retiraAcentos($string);
    if ($spaces !== false) {
        $return = str_replace (' ', ($spaces===true?'':$spaces) , $return);
    }
    switch ($alter) {
        case 1:  $return = mb_strtoupper($return);
        case -1: $return = mb_strtolower($return);
    }
    return $return;
}

/**
 * Retira todos os acentos de uma palavra
 * @param string $palavra Palavra a ser tratada
 * @return string Palavra com os acentos retirados
 */
function retiraAcentos($palavra){
    $new_palavra = strtr(
        utf8_decode($palavra),
        utf8_decode("áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ"),
        "aaaaeeiooouucAAAAEEIOOOUUC"
    );
    return $new_palavra;
}

/*---------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------------------------*/

/**
 * getReminders Retorna os os dados de todas as pessoas que assinaram o lemtrete do evento
 * @param array $where condição para a busca
 * @param long $limit limite para exibição
 * @return array Array associativo com campo e valor retornado do recordset 
 */
function getReminders($where = null, $limit=null) {
    $ci =& get_instance();
    $return = $ci->db->getwhere('lembrete', $where, $limit)->result_array();
    if (!$return) {
        return array();
    }
    return $return;
}

function atualizaStatusLembrete($id)
{
    $ci =& get_instance();
    // $this->db->update('usuarios', array('controle'=>md5($dados['uid'])), array('email'=>$dados['email']));
    $result = $ci->db->update('lembrete', array('status'=>1), array('lembrete_id'=>$id));
    return $ci->db->affected_rows();
}

/**
 * retorna o caminho absoluto da imagem no servidor
 * @param int $id
 * @return string
 */
function getMediaPathById($id) {
    if(!$id) return;
    $media = getProductById($id);
    return uploadPath().$media['media_category']."/".$media['id_usuario']."/".$media['media_url'];
}

/**
 * convert uma url de vídeo do youtube em url da miniatura da imagem do vídeo no youtube
 * @param string $hRef
 * @return string
 */
function getVideoThumbUrl($hRef) {
    $videoUrl = explode('=', $hRef);
    $imgUrl = str_replace('watch?v', 'vi/', str_replace('www', 'img', $videoUrl[0])).$videoUrl[1].'/1.jpg';
    return $imgUrl;
}

/**
 * seleciona o nome da imagem/url do vídeo no banco de dados
 * @param int $id
 * @return array ( tipo(imagem ou vídeo), nome da imagem/url do vídeo )
 */
/*
function getMediaById($id) {
    if(!$id) return;
    $ci =& get_instance();
    $ci->db->select(array('media_type','media_url', 'media_category'));
    return $ci->db->get_where('imagens', array('id'=>$id))->row_array();
}
*/

/**
 * retorna o caminho absoluto da aplicação
 * @return string
 */
function base_path() {
    return dirname(dirname(__FILE__));
}

function geraCodeConfirm($dados) {
    $cod=array();
    $k = '';
    foreach ($dados as $key => $value) {
        if(in_array($key, array('time', 'uag')))
            $k .= md5($value);
        else
            $cod[$key] = ($key=='id')?$value:md5($value);
    }
    $cod['key'] = $k;
    return $cod;
}

function geraCodeTrocaSenha($dados) {
    $cod=array();
    foreach ($dados as $key => $value) {
        $cod[$key] = ($key=='controle')?$value:md5($value);
    }
    return $cod;
}

/**
 *
 * Quebra a string recebida a partir do email de confirmação, com os dados do usuário, transformando em um array associativo
 * @param string $url Dados codificados de confirmação
 * @return array Url quebrada com chave e valor
 */
function decodeUrl($url) {
    $url_key = explode('.', $url);
    foreach ($url_key as $item) {
        $url_new[] = explode('-',$item);
    }
    $url_key = array();
    foreach ($url_new as $item) {
        $url_key[$item[0]] = $item[1];
    }
    return $url_key;
}

function getPontosById($id) {
    $ci =& get_instance();
    $ci->db->select_sum('pontos');
    $query = $ci->db->get_where( 'imagens', array('id' => $id) )->row_array();
    if ($query) return (int)$query['pontos'];
}

function countVotos ($id) {
    $ci =& get_instance();
    $query = $ci->db->get_where( 'eleitores', array('img_id'=>$id, 'votou'=> '1') );
    return $query->num_rows();
}

function renderHtmlRating($img_id) {
    $item = '<a class="votar" href="%s?iframe=true&width=%s&height=%s" rel="prettyPhotos">Vote</a>';
    printf($item, site_url('galeria/vote/'.$img_id), 400, 200);
}

/*
function defineFunc($funcao) {
    require_once(APPPATH.'config/'.$funcao.EXT);
    if ( isset($config['max_image']) ) {
        function max_image(){
            return $config['max_image'];
        }
    }
}
*/


/**
 * geraNovaSenha()
 * @return string Retorna uma senha aleatória gerada a partir de um número randômico
 */
function geraNovaSenha() {
    $controle = rand(0,1000000000);
    return substr(md5($controle), 0, 8);
}

/**
 *    Send the headers necessary to ensure the page is
 *    reloaded on every request. Otherwise you could be
 *    scratching your head over out of date test data.
 *    @access public
 */
function sendNoCacheHeaders() {
    if (!headers_sent()) {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }
}

/**
 * verifyPath() Verifica se uma datermidada url existe, caso não exista ele cria e atribue permissões
 * @param string $folder Caminho a ser criado Ex.: /home/ldmotta/abc
 * @param string $mode Valor da permissao, default 0777
 */
function verifyPath($folder, $mode=0777)
{
    $pasta = '';
    $f = explode('/', $folder);
    foreach ($f as $p) {
        $pasta .= $p . '/';
        if(!is_dir($pasta))
        {
            try{
                mkdir($pasta, $mode);
            }catch ( ErrorException $e ){
                return False;
            }
        }
    }
    return $folder;
}

function clearArr($arr)
{
    $new_arr = array();
    foreach($arr as $item){
        if($item)$new_arr[] = $item;
    }
    return $new_arr;
}

function getAllMedia()
{
    $ci =& get_instance();
    return base_url().$ci->config->item('upload_path'). $media['media_category']."/". $media['id_usuario'] .'/'. $media['media_url'];
}

