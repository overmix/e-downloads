<?php
class Esqueci extends Controller {

	function Esqueci()
	{
		parent::Controller();
		$this->load->library('validation');
        $this->load->library('session');
        $this->load->library('email');

		$this->load->model('user');

		/*-------------validações------------*/
		$rules['email']	= "trim|required|valid_email";
		$this->validation->set_rules($rules);

		$fields['email'] = 'Email';
		$this->validation->set_fields($fields);

		$this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio');
		$this->validation->set_message('valid_email', 'O campo <i>%s</i> não contém um email válido');
		$this->validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	function index()
	{
		$data = array('logged'=>$this->auth->logged(),'page_title'=>'Esqueci minha senha', 'titulo'=>'Esqueci minha senha');
		$this->load->view('esqueci', $data);
	}

    function lembrar() {
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Esqueci minha senha', 'titulo'=>'Esqueci minha senha');

        $myEmail = $this->input->post('email');

        if ($this->validation->run()) {
            $hasThisEmail = $this->user->checaUser( array('email' => $myEmail));
            
            if (!$hasThisEmail) {
                $this->messages->add("O Email digitado ainda não foi cadastrado!", "warning");
                redirect("esqueci"); die();
            }

            $dc = array(
                'email'     =>$this->input->post('email'),
                'uid'       =>uniqid( rand(), true ),
            );

            $this->user->preparaTrocaSenha($dc);

            // email-e6bc09600a3921f2b9dfee84d29bff19.uid-777ca9e6b260bc08b05990545f9d0e09
            $url = sprintf('email-%s.uid-%s',md5($dc['email']), md5($dc['uid']));
            
            $config['charset']      = 'iso-8859-1';
            $config['protocol']     = 'smtp';
            $config['smtp_port']    = '25';
            $config['smtp_host']    = 'localhost';
            $config['mailtype']     = 'html';

            $this->email->initialize($config);

            $this->email->from($this->config->item('admin_email'), $this->config->item('admin_name'));
            $this->email->to($myEmail);

            $this->email->subject(utf8_decode('Troca de senha'));

            $href = base_url()."index.php/esqueci/confirmacao/".$url;
            $dadosEmail = $this->user->getUserDataByEmail($myEmail);
            
            $msg = "<p>Olá ${dadosEmail['nome']},</p>

            <p>Este é o email de confirmação para alteração de senha, caso não tenha pedido a alteração
            da sua senha, ignore-o. </p>

            <p>Abaixo segue o link para confirmar a alteração da senha.</p>";
            
            $msg.= "<p><a href='".$href."'>Clique aqui para confirmar a alteração de sua senha de acesso ao site da e-Downloads.</a></p>";

            $msg .= "<p>Este é um email automático, portanto, não responda essa mensagem.</p>";

            $msg = utf8_decode($msg);

            $this->email->message($msg);
            try {
                if($this->email->send()){
                    //$this->user->alteraSenha($myEmail, $novasenha);
                    $this->messages->add('A senha foi enviada para o email indicado.', 'done');
                }
            } catch (Exception $e) {
                $this->messages->add("Erro ao enviar email.", 'error');
            }
        }
        $this->load->view('esqueci', $data);
    }

    /**
     * Confirma o voto atualizando o campo 'votou' como 1 na tabela 'eleitores'. Também atualiza a quantidade de votos
     * da imagem votada.
     * @param string $url_key Dados de controle criptografados, que foram enviados via email para confirmação do usuário (eleitor)
     * @return void
     */
    function confirmacao($url_key) {
        //email-23bfe665574956641a782f3e7f9380d3
        //uid-111464b68226c117149.52077306
        if (!$url_key) return false;
        $url_key = decodeUrl($url_key);
        $query = $this->user->getPreTrocaSenha( array('controle'=>$url_key['uid']) );
        if(count($query)) {
            $code_compare = geraCodeTrocaSenha($query);
            if (!array_diff($code_compare, $url_key)) {
                redirect('trocasenha/index/'.$url_key['uid']); die();
            }
        }
        redirect('home'); die();
    }

    function sair(){
        $this->auth->sair();
        redirect('home', 'refresh');
    }

}