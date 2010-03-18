<?php
/**
 * Class Cadastro
 */
class Cadastro extends Template_Publica
{
    var $titulo  = 'Cadstro de usuário';

    public function get()
    {
        include TEMPLATE_PATH . 'cadastro.php';
    }

    public function post()
    {
        var_dump($_POST);
        $mUser = new User;
        $dados = array(
            'nome'  => $_POST['nome'],
            'email' => $_POST['email'],
            'login' => $_POST['login'],
            'senha' => md5($_POST['senha']),
        );
        $mUser->save($dados);
    }
}
