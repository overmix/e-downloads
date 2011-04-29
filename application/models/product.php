<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Classe de usuários
 */
class Product extends Model {
    var $dados = array();
    function Product() {
        parent::Model();
    }
    
    /**
     * updateProduct Atualiza os dados do produto
     * @param array $where Condição para atualização
     * @param array $dados Dados para atualizar
     * @return true caso tenha atualizado 
     */
    function updateProduct($where=null, $dados=null) {
        $this->db->update('produtos', $dados, $where );
        return (bool)$this->db->affected_rows();
    }

    /**
     * insertProduct Adiciona novos produtos a base de dados
     * @return <type> Id do produto recém inserido
     */
    function insertProduct() {
        $this->dados = is_array(func_get_arg(0))?func_get_arg(0):func_get_args();
        $this->db->insert('produtos', $this->dados);
        return $this->db->insert_id();
    }

    /**
     * getAllProducts Retorna todos os dados de todos os produtos em forma de array
     * @param array $where Array contendo os critérios de busca
     * @param int $limit limit de itens para retornar, null para todos
     * @return array Array contendo todos os produtos para download
     */
    function getAllProductsByStatus($status, $limit=null) {
        $this->db->orderby("atualizado", "desc");
        $return = $this->db->getwhere('produtos', array('status'=>(int)$status), $limit)->result_array();
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
        $this->db->orderby("atualizado", "desc");
        $return = $this->db->getwhere('produtos', array('id_produto'=>$id))->row_array();
        if (!count($return)) {
            return array();
        }
        return $return;
    }

    /**
     * Verifica se o produto está liberado para o usuário logado, e retorna os dados deste produto
     * @param int $id Id do produto que s
     * @return <type>
     */
    function getUserDownloadById($id)
    {
        $ci =& get_instance();
        $ci->load->model('user');

        if(!$id) return;
        $sql = "SELECT `produtos`.`id_produto`, `produtos`.`arquivo`
        FROM (`pedidos`)
        JOIN `produtos` ON `produtos`.`id_produto` = `pedidos`.`id_produto`
        WHERE `id_usuario` = '" . $ci->user->getUserIdByEmail($this->auth->userMail()) . "'
        AND DATEDIFF(liberado_em, pedido_em) >=  0
        AND IF(DATE_ADD(`usar_ate`, INTERVAL 1 DAY), DATEDIFF(usar_ate, liberado_em) >= 0, TRUE)
        AND IF(`limite` > 0,`limite` - downloads > 0, TRUE)
        AND `pedidos`.`status` = 'Ativo'
        AND `produtos`.`id_produto` = '" .$id. "'";

        $return = $this->db->query($sql)->row();
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
        if(!$pedido) return;
        $where = array(
            'id_pedido' => $pedido,
        );
        $this->db->select('pedidos.*,  produtos.id_produto, produtos.arquivo')
            ->join('produtos', 'produtos.id_produto = pedidos.id_produto');

        $return = $this->db->getwhere('pedidos', $where)->row_array();
        if (!count($return)) {
            return array();
        }
        return $return;

    }

    function getPedidosByUserId($userId)
    {
        $ci =& get_instance();
        $ci->load->model('user');
        if(!$userId) return;
        $where = array(
            'id_usuario' =>  $userId,
        );
        $this->db->select('pedidos.*,  produtos.id_produto, produtos.arquivo')
            ->join('produtos', 'produtos.id_produto = pedidos.id_produto');

        $return = $this->db->getwhere('pedidos', $where)->row_array();
        if (!count($return)) {
            return array();
        }
        return $return;

    }

    /**
     * getAllPedidos Pega todos os pedidos ativos ou nao, de todos os usuários
     * @return array Array contendo os dados do pedido
     */
    function getAllPedidos()
    {
        $this->db->select('pedidos.*, usuarios.*')
            ->join('produtos', 'produtos.id_produto = pedidos.id_produto')
            ->join('usuarios', 'usuarios.id_usuario = pedidos.id_usuario');

        $return = $this->db->get('pedidos')->result_array();
        if (!count($return)) {
            return array();
        }
        return $return;
    }

    /**
     * desativarProduto Atualiza o status do produto como 0
     * @param int $id Id do produto
     * @return boolean Retorna true caso o produto tenha sido desativado com sucesso
     */
    function desativarProduto($id) {
        $data = array('status'=>0);
        $this->db->where('id_produto', $id);
        return $this->db->update('produtos', $data);
    }

    /**
     * desativarProduto Atualiza o status do produto como 0
     * @param int $id Id do produto
     * @return boolean Retorna true caso o produto tenha sido desativado com sucesso
     */
    function reativarProduto($id) {
        $data = array('status'=>1);
        $this->db->where('id_produto', $id);
        return $this->db->update('produtos', $data);
    }
    
    /**
     * updatePedido Atualiza os dados do pedido
     * @return <type> 
     */
    function updatePedido($where=null, $dados=null) {
        $this->db->update('pedidos', $dados, $where );
        return $this->db->affected_rows();
    }

    /**
     * geraPedido Cria um pedido pendente
     * @param array $dados Array assossiativo contendo o campo e valor a ser inserido
     * @return bool True em caso de sucesso, ou FALSE
     */
    function geraPedido($dados)
    {
        $return = $this->db->insert('pedidos', $dados);
        return $return ? $this->db->insert_id() : FALSE;
    }
}





























