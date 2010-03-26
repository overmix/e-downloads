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
            $this->session->set_userdata(array('produto'=>$produto));
            redirect('inicio'); die();
        }
        $produto = $produto ? $produto : $this->session->userdata('produto');
        $dados = array(
            'id_produto'    => $produto,
            'id_usuario'    => $this->user->getUserIdByEmail($this->auth->userMail()),
            'pedido_em'     => date('Y-m-d H:i:s'),
            'status'        => 'Bloqueado',
        );

        //$pedido = $this->product->geraPedido($dados);
        $pedido = 1;
        
        if ($pedido) {
            $dadosUser = $this->user->getUserDataByEmail($this->auth->userMail());
            $dadosProd = $this->product->getProductById($produto);
            $content = array(
                'user_nome'         => $dadosUser['nome'],
                'prod_nome'         => $dadosProd['nome'],
                'prod_preco'        => 'R$'.$dadosProd['preco'].',00',
                'prod_descricao'    => $dadosProd['descricao'],
                'admin_nome'        => $this->config->item('admin_name'),
                'admin_email'       => $this->config->item('admin_email'),
                'url'               => base_url() . 'downloads',
            );
            $msgUser = loadTemplate(TEMPLATEPATH . 'views/template_compra.html', $content);
            
            $content = array(
                'user_nome'         => $dadosUser['nome'],
                'prod_nome'         => $dadosProd['nome'],
                'prod_preco'        => 'R$'.$dadosProd['preco'].',00',
                'prod_descricao'    => $dadosProd['descricao'],
            );
            $msgEdownloads = loadTemplate(TEMPLATEPATH . 'views/template_pedido.html', $content);

            mandaEmail($this->config->item('admin_email'), $this->auth->userMail(), 'Pedido de download', $msgUser, $this->config->item('admin_nome'));
            mandaEmail($this->config->item('admin_email'), $this->config->item('admin_email'), 'Olá! tem um pedido pra você!', $msgEdownloads, $this->config->item('admin_nome'));
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