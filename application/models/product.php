<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Classe de usuários
 */
class Product extends Model {
    var $dadosProduct = array();
    function Product() {
        parent::Model();
    }

    /**
     * getAllProducts Retorna todos os dados de todos os produtos em forma de array
     * @param array $where Array contendo os critérios de busca
     * @param int $limit limit de itens para retornar, null para todos
     * @return array Array contendo todos os produtos para download
     */
    function getAllProducts($where = null, $limit=null) {
        $this->db->orderby("data", "desc");
        $return = $this->db->getwhere('produtos', $where, $limit)->result_array();
        if (!$return) {
            return array();
        }
        return $return;
    }

    /**
     * getProdutoById Busca o produto baseado no id passado em $id
     * @param int $id Id do produto
     * @return array Array contendo todos os detalhes do produto em questão
     */
    function getProductById($id)
    {
        $this->db->orderby("data", "desc");
        $return = $this->db->getwhere('produtos', array('id_produto'=>$id))->row_array();
        if (!count($return)) {
            return array();
        }
        return $return;
    }


    function getAllowFileDownloadById($id)
    {
        $ci =& get_instance();
        $ci->load->model('user');
        
        if(!$id) return;
        $where = array(
            'id_usuario' =>  $ci->user->getUserIdByEmail($this->auth->userMail()),
            'DATEDIFF(liberado_em, pedido_em) >= ' => 0,    // que tenha sido liberado
            'DATEDIFF(usar_ate, liberado_em) >= ' => 0,
            'limite - downloads >=' => 0,
            'produtos.id_produto' => $id
        );
        $this->db->select('produtos.id_produto, produtos.arquivo')
            ->join('produtos', 'produtos.id_produto = pedidos.id_produto');
            
        $return = $this->db->getwhere('pedidos', $where)->row();
        return $return ? $return->arquivo : FALSE;
    }

    /**
     * updateQuantity Atualiza a quantidade de dawnloads efetuados em + 1
     * @param int $id Id do produto
     */
    function updateQuantity($id)
    {
        if(!$id) return;
        $ci =& get_instance();
        $ci->load->model('user');
        $where = array(
            'id_produto'=>$id,
            'id_usuario' =>  $ci->user->getUserIdByEmail($this->auth->userMail()),
        );
        $ci->db->set('downloads','downloads + 1', FALSE)
        ->where($where)
        ->update('pedidos');
        return $ci->db->affected_rows();
    }

    function getPedidoById($pedido)
    {
        $ci =& get_instance();
        $ci->load->model('user');
        if(!$id) return;
        $where = array(
            'id_usuario' =>  $ci->user->getUserIdByEmail($this->auth->userMail()),
            'produtos.id_pedido' => $pedido,
        );
        $this->db->select('produtos.id_produto, produtos.arquivo')
            ->join('produtos', 'produtos.id_produto = pedidos.id_produto');

        $return = $this->db->getwhere('pedidos', $where)->row();
        return $return ? $return->id_pedido : FALSE;
    }
}