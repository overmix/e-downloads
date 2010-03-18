<?php
/**
 * Library de autenticação de usuários
 */
class Auth extends Model
{
    function login($user=null, $pass=null, $grupo='')
    {
        $user = $this->quote($user);
        $pass = $this->quote(md5($pass.SALT));
        
        $sql  = "SELECT * from " . $this->_table . " WHERE (login=$user OR email=$user) AND senha=$pass";
        $result = $this->query($sql);
        if (!$result->rowCount()) return false;

        $_SESSION['user_id'] = $result->fetchObject()->id_usuario;
        $_SESSION['tipo']  = '';
        return (bool) (int) $result->rowCount();
    }

    /**
     * Verifica se o login passado já existe na base de dados
     * @param string $login Texto que será verificado no campo login
     * @param int $id_user Id do usuário quando estiver editando.
     * @return bool True caso exita
     */
    function login_exists($login, $id_user=0){
        $mUser = new Usuario;
        return (bool) $mUser->select(array('login'=>$login, 'id_usuario <> '=>$id_user));
    }

    /**
     * Verifica se o email já existe na base de dados
     * @param string $email Texto que será verificado no campo email
     * @return bool True caso exista
     */
    function email_exists($email, $id_user=0){
        $mUser = new Usuario;
        return (bool) $mUser->select(array('email'=>$email, 'id_usuario <> '=>$id_user));
    }

    function clear_session(){
        unset($_SESSION['login']);
        unset($_SESSION['tipo']);
    }
    
	public static function get_login(){
		$mUser = new Usuario;
        return $mUser->get(session_load('user_id'));
	}
}
