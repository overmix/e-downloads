<?php
class Pagamento extends Controller {
    function Pagamento () {
        parent::Controller();
        $this->load->model('product');
        $this->load->library('pgs');
    }

    function index ($produto=0) {
    	if (!$this->auth->logged())
        {
            setLastUri($this->uri->segment(1));
            redirect('inicio');
        }

        $dados = array(
            'id_produto'    => $produto,
            'id_usuario'    => $this->user->getUserIdByEmail($this->auth->userMail()),
            'pedido_em'     => date('Y-m-d H:i:s'),
            'status'        => 'Bloqueado',
        );
        $pedido = $this->product->geraPedido($dados);
        if ($pedido) {
            $mensagem = "oi";
            //mandaEmail($this->config->item('admin_email'), $this->auth->userMail(), 'Pedido de download', $mensagem, $this->config->item('admin_nome'));
        }
        $data = array(
            'logged'        =>$this->auth->logged(),
            'page_title'    =>'Pagamento',
            'titulo'        =>'Efetuar compra',
            'description'   =>'Efetuar compra'
        );
        $this->load->view('pagamento', $data);
    }
}