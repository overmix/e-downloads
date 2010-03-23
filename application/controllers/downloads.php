<?php
class Downloads extends Controller {
    function Downloads () {
        parent::Controller();
        $this->load->model('user');
    }

    function index ($msg='') {
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Downloads', 'titulo'=>'Lista de downloads', 'description'=>'Lista de downloads');
        
        $data['downloads'] = $this->user->getUserDownloads();

        $this->load->view('download-list', $data);
    }
}