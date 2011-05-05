<?php
class Trocasenha extends Controller {

	function Trocasenha()
	{
		parent::Controller();
		$this->load->library('validation');
        $this->load->library('session');

		$this->load->model('user');

		/*-------------validações------------*/
        //$rules['email']		= "trim|required|xss_clean|valid_email|callback_email_check";
        $requerido = $this->pass_check($this->input->post('senha2'));
        $rules['senha']		= "trim|min_length[5]|max_length[12]".$requerido;
        $requerido = $this->pass_check($this->input->post('senha'));
        $rules['senha2']	= "trim".$requerido;
        $rules['uid']       = "callback_verify_uid";
		$this->validation->set_rules($rules);

		//$fields['email']    = 'Email';
		$fields['senha']    = 'Senha';
        $fields['senha2']	= 'Confirmação';
		$this->validation->set_fields($fields);

		$this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio');
        //$this->validation->set_message('email_check', 'Email não cadastrado');
		//$this->validation->set_message('valid_email', 'O campo <i>%s</i> não contém um email válido');
        $this->validation->set_message('matches', 'Senhas não conferem!');
        $this->validation->set_message('min_length', 'O campo <i>%s</i> deve ter pelo menos 5 caracteres de comprimento.');
        $this->validation->set_message('max_length', 'O campo <i>%s</i> não pode exceder 12 caracteres de comprimento.');        
		$this->validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	function index($uid)
	{
        // verificar se o uid de troca de senha existe no banco
		$data = array(
            'logged'    =>$this->auth->logged(),
            'page_title'=>'Troca de senha',
            'titulo'    =>'Alteração de senha',
            'uid'       =>$uid
        );
		$this->load->view('trocasenha', $data);
	}

    function alterar()
    {
   		$data = array(
            'logged'    =>$this->auth->logged(),
            'page_title'=>'Troca de senha',
            'titulo'    =>'Alteração de senha',
            'uid'       =>$this->input->post('uid')
        );

        if ($this->validation->run())
        {
            $dados = array(
                'controle'      =>$this->input->post('uid'),
                'senha'         =>$this->input->post('senha'),
            );
            $result = $this->user->alteraSenha($dados['controle'], $dados['senha']);
            if(!$result)
            {
                $this->messages->add('Erro ao atualizar senha, entre em contato com o administrador do sistema.', 'error');
            }
            $this->messages->add('Senha atualizada com sucesso', 'done');
            redirect('inicio'); die();
        }
        $this->load->view('trocasenha', $data);
    }

    function pass_check($str) {
        return ($str!='')?"|required":"";
    }

    function email_check($str) {
        return (bool)$this->user->checaUser(array('email'=>$str));
    }

    function matches($str){
        return (bool)$str==$this->input->post('senha');
    }

}
