<?php
class Pagamento extends Controller {
    function Pagamento () {
        parent::Controller();
        $this->load->model('product');
    }

    function index ($produto=0) {
        session_start();
        if($_SESSION['posted']){
            redirect('home');
        }
        $_SESSION['posted']=1;

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
        $pedido = $this->product->geraPedido($dados);

        if ($pedido) {
            $dadosUser = $this->user->getUserDataByEmail($this->auth->userMail());
            $dadosProd = $this->product->getProductById($produto);

            $form_pgs = $this->save(array(
                'nome'      => $dadosProd['nome'],
                'pedido'    => $pedido,
                'preco'     => $dadosProd['preco'],
                'produto'   => $produto,
            ));
            $dados = array('form_pgs' => $form_pgs[1]);
            $this->product->updatePedido(array('id_pedido'=> $pedido), $dados);

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
            
            // seleciona o pagamento pelo n° do pedido
            $pagamento = $this->product->getPedidoById($pedido);

            // se não tiver pagamento, vai para a home
            if (!count($pagamento)) {
                redirect('home'); die();
            }

            // caso contrário, chama o template de pagamento
            $data = array(
                'logged'        =>$this->auth->logged(),
                'page_title'    =>'Pagamento',
                'titulo'        =>'Efetuar compra',
                'description'   =>'Efetuar compra',
                'form_pgs'      =>$form_pgs[1],
            );
            $this->load->view('pagamento', $data);
        }
    }

    function save($data, array $where = array(), $table = null){
        $link = $data['pedido'];
        $config = (object)$this->config->item('dados_pgs');
        $params = array('email_cobranca'=>$config->email, 'ref_transacao'=>$link);
        $this->load->library('pgs', $params);
        $this->pgs->adicionar(array(
            'descricao'     => $data['nome'],
            'quantidade'    => 1,
            'valor'         => $data['preco'],
            'id'            => $data['produto'],
        ));
        $return = array($link, $this->pgs->mostra(array('show_submit'=>FALSE, 'close_form'=>FALSE, 'print'=>FALSE)));
        return $return;

    }

}