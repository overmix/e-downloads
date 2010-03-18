<?php

class Template_Publica
{
    var $titulo = 'e-Downloads';

    function __construct()
    {
        include TEMPLATE_PATH . 'header.php';
    }

    function __destruct()
    {
        include TEMPLATE_PATH . 'footer.php';
    }
}

class Home
{
    var $titulo = 'Home';
    public function get()
    {
        $titulo = $this->title;
        include TEMPLATE_PATH . 'home.php';
    }
}

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

class Download
{
    var $title = 'Download';
    public function get()
    {
        $titulo = $this->title;
        include TEMPLATE_PATH . 'download.php';
    }
}