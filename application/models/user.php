<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class User extends Model {

    var $dadosUser = array();
    function User() {
        parent::Model();
    }

    /**
     * Retorna true se o usuário estiver logado
     * @return Boolean
     */
    function loginUser() {
        $this->dadosUser = is_array(func_get_arg(0))?func_get_arg(0):func_get_args();
        if (!count($this->dadosUser)) return false;
        $where = array(
                'email'	=>$this->dadosUser['email'],
                'senha'	=>$this->dadosUser['senha']
        );
        $query = $this->db->getwhere('usuarios', $where);
        return (bool)$query->num_rows();
    }

    function insertUser() {
        $this->dadosUser = is_array(func_get_arg(0))?func_get_arg(0):func_get_args();
        $this->db->insert('usuarios', $this->dadosUser);
        return $this->db->insert_id();
    }

    function updateUser() {
        $this->dadosUser = is_array(func_get_arg(0))?func_get_arg(0):func_get_args();
        $this->db->update('usuarios', $this->dadosUser, "email = '".$this->auth->userMail()."'" );
        //$this->session->set_userdata('email', $this->dadosUser['email']);
        return $this->db->affected_rows();
    }

    function alteraSenha($controle, $novasenha) {
        $this->db->update('usuarios', array('senha'=>md5($novasenha), 'controle'=>''), "controle = '".$controle."'" );
        return $this->db->affected_rows();
    }

    function insertLembrete() {
        $this->dadosUser = is_array(func_get_arg(0))?func_get_arg(0):func_get_args();
        $this->db->insert('lembrete', $this->dadosUser);
        return $this->db->insert_id();
    }

    function getUserDataByEmail($email) {
        //$this->db->join('imagens', 'imagens.id_usuario = usuarios.id');
        return $this->db->getwhere('usuarios', array('email'=>$email))->row_array('id_usuario');
        //echo "<pre>"; print_r($return); echo "</pre>"; die("fim");
    }

    function getUserDataById($id) {
        //$this->db->join('imagens', 'imagens.id_usuario = usuarios.id');
        return $this->db->getwhere('usuarios', array('id_usuarios'=>$id))->row_array('id');
        //echo "<pre>"; print_r($return); echo "</pre>"; die("fim");
    }

    /**
     * getAllUserByGroup Retorna todos usuários de um grupo específico, passado como parâmetro
     * @param array $where Array associativo contendo 'campo' => 'valor' que será o critério da pesquisa
     * @param int $limit Número que determina a quantidade de registros retornados
     * @return array Retorna todos os campos da tabela usuários
     */
    function getAllUserByGroup($where=null, $limit=null) {
        $this->db->orderby("nome", "asc");
        $return = $this->db->getwhere('usuarios', $where, $limit)->result_array();
        if (!$return) {
            return array();
        }
        return $return;
    }
    /**
     * Carrega as imagens ou vídeo do usuário
     * @return Array();
     */
    function getUserMediaByType($type) {
        $where = array(
                'id_usuario'=>$this->getUserIdByEmail($this->auth->userMail()),
                'media_type'=>$type,
        );
        return $this->db->getwhere('imagens', $where)->result_array();
    }

    function getPagedList($type, $limit = 10, $offset = 0, $order_by = 'id') {
        $where = array(
                'media_type'=>$type,
                'status > ' => 0,
        );
        $this->db->where($where);
        $this->db->order_by($order_by,'random');
        return $this->db->get('imagens', $limit, $offset);
    }

    /**
     * Carrega todas as imagens e vídeo do usuário
     * @return Array();
     */
    function getAllUserMedia() {
        $where = array(
                'id_usuario'=>$this->getUserIdByEmail($this->auth->userMail())
        );
        return $this->db->getwhere('imagens', $where)->result_array();
    }

    /**
     *
     * Carrega todas as imagens e vídeo do usuário
     * @return Array();
     */
    function countUserMediaByType($type) {
        $where = array(
                'id_usuario'=>$this->getUserIdByEmail($this->auth->userMail()),
                'media_type'=>$type
        );
        return $this->db->getwhere('imagens', $where)->num_rows();
    }

    function countAllMediaByType($type) {
        $where = array(
                'media_type'=>$type
        );
        $this->db->where($where);
        return $this->db->getwhere('imagens')->num_rows();
    }

    function getUserIdByEmail($email) {
        $this->db->select('id_usuario');
        return $this->db->getwhere('usuarios', array('email'=>$email))->row()->id;
    }

    function checaUser($where) {
        return $this->db->getwhere('usuarios', $where)->num_rows();
    }

    function checaSenha($where) {
        return $this->db->getwhere('usuarios', $where)->num_rows()?true:false;
    }

    function checaLembrete($where) {
        return $this->db->getwhere('lembrete', $where)->num_rows();
    }

    /**
     * Insere novo ou atualiza os dados da imagem no banco
     * @param Array $dados_img
     * @return String
     */
    function gravaImg($dados_img) {
        $this->db->insert('imagens', $dados_img);
        return $this->db->insert_id();
    }

    function userID() {
        $this->db->select('id_usuario');
        $email = $this->session->userdata('email')?$this->session->userdata('email'):"";
        $query = $this->db->getwhere('usuarios', array('email'=>$email));
        if ( $query->num_rows() ) {
            return $query->row()->id_usuario;
        }
        return false;
    }

    function deleteImg($id_media) {
        $where = array(
                'id'    =>    $id_media,
        );

        if(!isAdmin($this->userID())) {
            $where['id_usuario'] = $this->userID();
        }

        return $this->db->delete('imagens', $where);
    }

    /**
     * aprovaImg Atualiza o status da imagem como 1
     * @param int $id_media Id da imagem na tabela imagens
     * @return boolean Retorna true caso a imagem tenha sido aprovada com sucesso
     */
    function aprovaImg($id_media) {
        $data = array('status'=>1);
        $this->db->where('id', $id_media);
        return $this->db->update('imagens', $data);
    }

    /**
     * aprovaImg Atualiza o status da imagem como 0
     * @param int $id_media Id da imagem na tabela imagens
     * @return boolean Retorna true caso a imagem tenha sido reprovada com sucesso
     */
    function reprovaImg($id_media) {
        $data = array('status'=>0);
        $this->db->where('id_usuario', $id_media);
        return $this->db->update('imagens', $data);
    }
    
    /**
     * setCommonToAdmin Define que um determinado usuário será administrador do sistema
     * @param int $user_id ID do usuário que será tratado
     * @return boolean True que determina o sucesso na atribuição.
     */
    function setCommonToAdmin($user_id=0) {
        $data = array('group'=>1);
        $this->db->where('id_usuario', $user_id);
        return $this->db->update('usuarios', $data);
    }

    /**
     * setAdminToCommon Define que um determinado usuário não será um administrador do sistema
     * @param int $user_id ID do usuário que será tratado
     * @return boolean True que determina o sucesso na atribuição.
     */
    function setAdminToCommon($user_id=0) {
        $data = array('group'=>0);
        $this->db->where('id_usuario', $user_id);
        return $this->db->update('usuarios', $data);
    }

    /**
     * canceladmin Retira a atribuição de usuário administrador
     * @param int $user_id ID do usuário que será tratado
     * @return boolean True caso a atriguição tenha sido efetuada com sucesso.
     */
    function canceladmin($user_id=0) {
        $data = array('group'=>0);
        $this->db->where('id_usuario', $user_id);
        return $this->db->update('usuarios', $data);
    }

    function allowImage() {
        return ($this->user->countUserMediaByType(1) < (int)$this->config->item('max_image'));
    }
    function allowVideo() {
        return ($this->user->countUserMediaByType(2) < (int)$this->config->item('max_video'));
    }

    function getAllMedia($where = null, $limit=null) {
        $this->db->orderby("data", "desc");
        $return = $this->db->getwhere('produtos', $where, $limit)->result_array();
        if (!$return) {
            return array();
        }
        return $return;
    }

    function getRecentMedia($limit=null) {
        $where = array(
                'status > ' => 0,
        );
        $this->db->orderby("atualizado", "desc");

        $this->db->join('usuarios', 'imagens.id_usuario = usuarios.id')
                ->select('usuarios.nome, imagens.*');
        $return = $this->db->getwhere('imagens', $where, $limit)->result_array();
        if (!$return) {
            return array();
        }
        return $return;
    }

    /**
     *
     * Verifica se uma determidada pessoa já votou, comparando os dados recebidos com os gravados em banco,
     * caso encontre o email, é porque já votou e retorna false, caso contrario insere os dados recebidos
     * @param array $dados rash maluco do email e user agent
     * @return variant Retorna vazio ou o número de registros encontrados
     */
    function preparaVoto($dados) {
        $query = $this->db->getwhere('eleitores', array('email'=>$dados['email'], 'img_id'=>$dados['img_id']));

        // se encontrar e pq já votou e retorna false
        if($query->num_rows())return false;

        // se não encontrar, grava os dados no banco
        $this->db->insert('eleitores', $dados);

        // retorna o id do registro inserido
        return $this->db->insert_id();
    }

    /**
     * preparaTrocaSenha Verifica se o usuário existe e grava o hash para troca se senha
     * @param array $dados Rash de controle utilizando o email e user agent para confirmação de troca de senha
     */
    function preparaTrocaSenha($dados){
        $this->db->update('usuarios', array('controle'=>md5($dados['uid'])), array('email'=>$dados['email']));
        return $this->db->affected_rows();
    }

    /**
     * Pega os dados do usuário, baseado controle
     * @param array $codigo array associativo contendo apenas o hash de controle
     * @return array Array contendo os dados capturados do banco, ou array vazio caso não encontre o código especificado
     */
    function getPreTrocaSenha($codigo) {
        $this->db->select('email, controle');
        $query = $this->db->getwhere('usuarios', $codigo)->row_array();
        if ($query) return $query;
    }

    /**
     * Pega os dados do eleitor, baseado no id vindo do email
     * @param array $codigo array associativo contendo apenas o código do eleitor
     * @return array Array contendo os dados capturados do banco, ou array vazio caso não encontre o código especificado
     */
    function getPreVoto($codigo) {
        $this->db->select('id, email, uid, time, uag');
        $query = $this->db->getwhere('eleitores', $codigo)->row_array();
        if ($query) return $query;
    }

    function getVotosById ($id) {
        $this->db->select('img_id, votos');
        $query = $this->db->getwhere( 'eleitores', array('id' => $id) )->row_array();
        if ($query) return $query;
    }

    function getPontosById ($id) {
        $this->db->select('pontos');
        $query = $this->db->getwhere( 'imagens', array('id' => $id) )->row_array();
        if ($query) return $query['pontos'];
    }

    function confirmaVoto ($dados) {
        $this->db->where($dados);
        $this->db->update('eleitores', array('votou'=>1));
        return true;
    }

    function atualizaPontos ($dados) {
        $voto = $this->getVotosById($dados['id']);
        $pontos = $this->getPontosById($voto['img_id']);
        $this->db->where('id_usuario',$voto['img_id']);
        $pontos = array('pontos'=>(int)$pontos + (int)$voto['votos']);
        $this->db->update('imagens', $pontos);
    }

}
?>