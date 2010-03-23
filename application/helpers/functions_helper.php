<?php
/**
 *
 * Retorna true caso exista algum valor na sessão email
 * @return boolean
 */
function logged () {
    $ci =& get_instance();
    return (bool)isset($ci->session->userdata['email']);
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
 * retorna a url absoluta da miniatura de uma imagem ou vídeo gravado pelo usuário
 * @param int $id
 * @return string
 */
function getThumbUrlById($id) {
    $ci =& get_instance();
    if(!$id) return;
    $product = getProductById($id);
    $thumb = imgToThumb($product['image']);
    return base_url() . $ci->config->item('upload_path') . "image/" . $thumb;
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
    $ext = getExtension($filename);
    return str_replace($ext, '', $filename) . "_thumb" . $ext;
}

/**
 * retorna a extensão do arquivo passado em $filename
 * @param string $filename
 * @return string
 */
function getExtension($filename) {
    $x = explode('.', $filename);
    return '.'.end($x);
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
 * retorna o caminho absoluto da miniatura de uma imagem gravada pelo usuário
 * @param int $id
 * @return string
 */
function getThumbPathById($id) {
    if(!$id) return;
    $media = getProductById($id);
    $thumb = imgToThumb($media['media_url']);
    return uploadPath().$media['media_category']."/".$media['id_usuario']."/".$thumb;
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

/**
 * retorna o caminho absoluto da pasta de uploads
 * @return string
 */
function uploadPath() {
    $ci =& get_instance();
    return dirname(dirname(dirname(__file__))) . "/" . $ci->config->item('upload_path');
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
 * isAdmin Verifica se o usuário logado é administrador
 * @return boolean Retorna True caso o usuário logado seja administrador
 */
function isAdmin(){
    $ci =& get_instance();
    $query = $ci->db->get_where( 'usuarios', array('id_usuario'=>$ci->user->userID(), 'group'=>1));
    return (bool)$query->num_rows();
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
/**
 * geraNovaSenha()
 * @return string Retorna uma senha aleatória gerada a partir de um número randômico
 */
function geraNovaSenha() {
    $controle = rand(0,1000000000);
    return substr(md5($controle), 0, 8);
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
    foreach ($f as $p)
    {
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

/**
 * Lista todos os arquivos em uma pasta passada em $path
 * @param string $path Caminho completo dos arquivos que serão listados
 */
function getFilesByPath($path='')
{
    $iterator = new DirectoryIterator($path);
    $files = array();
    foreach ( $iterator as $entry ) {
        $files[] = $entry->getFilename();
    }
    return $files;
}

function getAllMedia()
{
    $ci =& get_instance();
    return base_url().$ci->config->item('upload_path'). $media['media_category']."/". $media['id_usuario'] .'/'. $media['media_url'];
}