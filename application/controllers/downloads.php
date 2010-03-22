<?php
class Downloads extends Controller {
    function Downloads () {
        parent::Controller();
        $this->load->model('user');
        $has_responsavel = (bool)(isset($_POST['faixaetaria']) AND $_POST['faixaetaria']=="-");
    }

    function index ($msg='') {
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Downloads', 'titulo'=>'Lista de downloads', 'description'=>'Lista de downloads');
        $this->load->view('download-list', $data);
    }
}