<?php
class Pagamento extends Controller {
    function Pagamento () {
        parent::Controller();
        $this->load->model('product');
        $this->load->library('pgs');
    }

    function index ($pedido=0) {
    	if (!$this->auth->logged())
        {
            setLastUri($this->uri->segment(1));
            redirect('inicio');
        }
        $pagamento = $this->product->getPedidoById(array('id_pedido'=>$pedido));
        
        if (!$pagamento) {
            redirect('home'); die();
        }
        include('app/template/public/pagamento.php');


        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Pagamento', 'titulo'=>'Efetuar compra', 'description'=>'Efetuar compra');
        echo "<pre>"; print_r($data); echo "</pre>"; die('fim');
    }
}