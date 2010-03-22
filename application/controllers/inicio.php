<?php
class Inicio extends Controller {

	function Inicio()
	{
		parent::Controller();
		$this->load->library('validation');
        $this->load->library('session');
		$this->load->model('user');

		/*-------------validações------------*/
		$rules['email']	= "trim|required|valid_email";
		$rules['senha']	= "trim|required";
		$this->validation->set_rules($rules);

		$fields['email'] = 'Email';
		$fields['senha'] = 'Senha';
		$this->validation->set_fields($fields);

		$this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio');
		$this->validation->set_message('valid_email', 'O campo <i>%s</i> não contém um email válido');
		$this->validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	function index()
	{
		$data = array('logged'=>$this->auth->logged(),'page_title'=>'Login', 'titulo'=>'Efetuar login');
		$this->load->view('inicio', $data);
	}

	function login ()
	{
		$data = array('logged'=>$this->auth->logged(),'page_title'=>'Login', 'titulo'=>'Efetuar login');
		if ($this->validation->run())
		{
            $dados = array (
				'email'	=>$this->input->post('email'),
				'senha'	=>md5($this->input->post('senha'))				
			);
            $dados = $this->input->xss_clean($dados);

            if ($this->user->loginUser($dados)){
                $session_data = array(
                    'email' => $dados['email'],
                    'logado' => true
                );
                $this->session->set_userdata($session_data);

                if(isAdmin()){
                    redirect('admin'); die();
                }else{
                    redirect('profile');die();
                }

            }
            $this->messages->add("Usuário ou senha inválida!");
		}
        $this->load->view('inicio', $data);
        return false;
	}

    function sair(){
        $this->auth->sair();
        redirect('home', 'refresh');
    }

}