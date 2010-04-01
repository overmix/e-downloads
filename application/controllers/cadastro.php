<?php
class Cadastro extends Controller {

    function Cadastro () {
        parent::Controller();
        $this->load->library('validation');
        $this->load->model('user');
        $has_responsavel = (bool)(isset($_POST['faixaetaria']) AND $_POST['faixaetaria']=="-");

		/*-------------validações------------*/
        $rules['nome']              = "trim|required|xss_clean";
        $rules['email']             = "trim|required|valid_email|callback_email_check";
        $rules['senha']             = "trim|required|matches[senha2]|min_length[5]|max_length[12]";
        $rules['senha2']            = "trim|required";
        $rules['telefone']          = "trim|required|isset";

        $this->validation->set_rules($rules);

        $fields['nome']             = 'Nome';
        $fields['email']            = 'Email';
        $fields['senha']            = 'Senha';
        $fields['senha2']           = 'Confirme a senha';
        $fields['telefone']         = 'Telefone';
        $this->validation->set_fields($fields);

        $this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio');
        $this->validation->set_message('email_check', 'Email já cadastrado');
        $this->validation->set_message('valid_email', 'O campo <i>%s</i> não contém um email válido');
        $this->validation->set_message('matches', 'Senhas não conferem');
        $this->validation->set_error_delimiters('<small class="error">', '</small>');
    }

    function index ($msg='') {
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Cadastro', 'titulo'=>'CADASTRE-SE', 'description'=>'Efetuar cadastro');
        $this->load->view('cadastro', $data);
    }

    function novo () {
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Cadastro', 'titulo'=>'CADASTRE-SE', 'description'=>'Efetuar cadastro');

        //caso a validação esteja ok
        if ($this->validation->run()) {
            $dados = array (
                'nome'              =>$this->input->post('nome'),
                'email'             =>$this->input->post('email'),
                'senha'             =>md5($this->input->post('senha')),
                'telefone'          =>$this->input->post('telefone'),
            );

            $dados = $this->input->xss_clean($dados);

            if ($this->user->insertUser($dados)) {
                $this->session->set_userdata('email', $dados['email']);
                redirect('home'); die();
            }
            $this->messages->add('Erro ao gravar dados!');
        }

        $this->load->view('cadastro', $data);
    }

    function email_check($str) {
        return !$this->user->checaUser(array('email'=>$str));
    }
    
    function cpf_check($cpf) {
        return $this->validar->cpfcnpj($cpf);
    }
}
