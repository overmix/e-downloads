<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  ?>
<?php
class Auth {
    var $ci = '';

    function __construct () {
        $this->ci =& get_instance();
        $this->ci->load->library('session');
    }

    function inc($__file, $vars= array()) {
        extract($vars);
        include dirname(dirname(__FILE__).'../')."/views/{$__file}.php";
    }

    /**
     *
     * Retorna true caso exista algum valor na sessão email
     * @return boolean
     */
    function logged () {
        return (bool)(isset($this->ci->session->userdata['email']) AND $this->ci->session->userdata['email']);
    }

    /**
     *
     * Retorna o valor da sessão email, ou redireciona para a home
     * @return void
     */
    function verificaLogin() {
        $this->ci =& get_instance();
        $this->clearCache();
        if (!$this->logged()) {
            $this->ci->messages->add('Usuário não cadastrado. Efetue o cadastro para poder enviar seu trabalho.', 'warning');
            redirect ('cadastro');
            die(); // Morre para não ter problemas com o redirecionamento
        }
        return $this->userMail();
    }

    /**
     *
     * Retorna o valor da sessão email
     * @return string
     */
    function userMail() {
        return $this->ci->session->userdata('email');
    }

    function sair ()
    {
        $this->ci->session->unset_userdata('email');
        $this->ci->session->sess_destroy();
    }

    function clearCache() {
        $this->ci->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->ci->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->ci->output->set_header("Pragma: no-cache");
    }

}

?>
