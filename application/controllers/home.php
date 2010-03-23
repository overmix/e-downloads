<?php

class Home extends Controller {
    function Home() {
        parent::Controller();
        $this->load->library('lightbox');
        $this->load->model('product');
    }
    
    function index() {
        $data = $this->getDados();
        $this->load->view('home', $data);
    }

    function getDados() {
        $data = array(
                'page_title'=>'e-Downloads',
                'titulo'=>'Home',
        );
        $data['products'] = $this->product->getAllProducts();
        return $data;
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */