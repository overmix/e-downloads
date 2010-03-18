<?php
/**
 * Class Cadastro
 */
class Cadastro
{
    var $titulo  = 'Cadstro de usuário';

    public function get()
    {
        $titulo = $this->titulo;
        include TEMPLATE_PATH . 'cadastro.php';
    }

    public function post()
    {
        $titulo = $this->titulo;
        $msgObject = new Messages;
        
        $erros = $this->_validate($_POST);
        if (!count($erros)) {
            $mUser = new User;
            $dados = array(
                'nome'  => $dados['nome'],
                'email' => $dados['email'],
                'login' => $dados['login'],
                'senha' => md5($dados['senha']),
            );
            $mUser->save($dados);
            $msgObject->add('Dados salvos com sucesso', 'done');
        }else{
            $message = "Verifique os seguintes erros e tente novamente:<br />";
            $message .= implode("<br />", $erros);
            $msgObject->add($message, 'warning');
            $_SESSION['data']     = $_POST;
        }
        header('Location: ' . BASE_URL . 'cadastro');
    }

    function _validate($dados)
    {
        $validate   = new Validate();
        $mUser      = new Usuario;
        $erros = array();
        if ($validate->is_empty($dados['nome'])) {
            $erros[] = $validate->error_empty('Nome');
        }
		if ($validate->is_empty($dados['login'])) {
            $erros[] = $validate->error_empty('Login');
        }
        if ($validate->is_empty($dados['email'])) {
            $erros[] = $validate->error_empty('E-mail');
        }elseif (!$validate->is_valid_mail($dados['email'])) {
            $erros[] = $validate->error_email();
        }
        if ($validate->is_empty($dados['senha'])) {
            $erros[] = $validate->error_empty('Senha');
        }
        if (!$validate->is_match($dados['senha'], $dados['senha2'])) {
            $erros[] = $validate->error_match();
        }
        if ($mUser->login_exists($dados['login'])) {
            $erros[] = sprintf('- O login <strong>%s</strong> já existe na base de dados.', $dados['login']);
        }
        if ($mUser->email_exists($dados['email'])) {
            $erros[] = sprintf('- O email <strong>%s</strong> já exista na base de dados.', $dados['email']);
        }
        return $erros;
    }
}

