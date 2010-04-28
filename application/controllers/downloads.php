<?php
class Downloads extends Controller {
    function Downloads () {
        parent::Controller();
        $this->load->model('user');
        $this->load->model('product');
        $this->load->config('upload');
    }

    function index ($msg='') {
        if (!$this->auth->logged()) redirect('inicio');
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Downloads', 'titulo'=>'Lista de downloads', 'description'=>'Lista de downloads');
        
        $data['downloads'] = $this->user->getUserDownloads();
        
        $this->load->view('download-list', $data);
    }

    function get($id)
    {
        if (!$this->auth->logged()) redirect('inicio');
        $dir= make_path($this->config->item('upload_path') . 'arquivo/');

        $file = $this->product->getUserDownloadById($id);

        if ($file) {
            $file = $dir . $file;
            header("Content-type: application/force-download");
            header("Content-Transfer-Encoding: Binary");
            header("Content-length: ".filesize($file));
            header("Content-disposition: attachment; filename=\"".basename($file)."\"");
            readfile("$file");
            $this->product->updateQuantity($id);
        } else {
            $this->messages->add("Desculpe, o limite de download para este produto foi esgotado!");
            redirect('downloads'); die();
        }

    }
}