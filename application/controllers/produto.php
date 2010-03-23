<?php
class Produto extends Controller {
    function Produto () {
        parent::Controller();
        $this->load->model('product');
    }

    function index ($id=0) {
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Produto', 'titulo'=>'Detalhes do produto', 'description'=>'Detalhes do produto');
        $data['product'] = $this->product->getProductById($id);
        $this->load->view('produto', $data);
    }
}