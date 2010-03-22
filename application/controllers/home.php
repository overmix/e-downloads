<?php

class Home extends Controller {
    function Home() {
        parent::Controller();
        //$this->load->library('lightbox');
        //$this->load->library('validation');
        //$this->load->model('user');

        ///$rules['lembrete']		= "trim|required|valid_email|callback_email_check";
        //$this->validation->set_rules($rules);

        //$fields['lembrete']	= 'Email';
        //$this->validation->set_fields($fields);

        //$this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio');
        //$this->validation->set_message('email_check', 'Email já cadastrado');
        //$this->validation->set_message('valid_email', 'O campo <i>%s</i> não contém um email válido');
        /*$this->validation->set_error_delimiters('<small class="error">', '</small>');*/
        //$this->validation->set_error_delimiters('', '');
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
        return $data;
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */