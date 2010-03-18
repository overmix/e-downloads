<?php
/**
 * geraSenha() Gera uma senha aleatória com 6 digitos.
 * @param int $digit número de digitos pegos do rash
 * @return string Retorna uma senha aleatória gerada a partir de um número randômico
 */
function geraSenha($digit = 6) {
    $controle = rand(0,1000000000);
    return substr(md5($controle), 0, $digit);
}

function geraToken($digit = 15){
	return substr(hash('sha512', uniqid()), 0 , $digit);
}

/**
 * isAdmin Verifica se o usuário logado é administrador
 * @return boolean Retorna True caso o usuário logado seja administrador
 */
function isAdmin(){
    return (bool)(isset($_SESSION['grupo']) AND $_SESSION['grupo']=='admin');
}

/**
 * Pega o grupo do usuário que pode ser admin ou vazio (usuários comuns)
 * @return Grupo do usuário
 */
function userGroup(){
    return (isset($_SESSION['grupo']) AND $_SESSION['grupo'])?$_SESSION['grupo']:'';
}

/**
 * Varre um arquivo, procurando qualquer coincidência do texto passado
 * como chave de uma array associativo, e substitue estas
 * ocorrencias pelos valores deste mesmo array.
 * @param string $file caminho do arquivo de template para o email
 * @param array $dados Array  associativo contendo chave e valor, onde a chave
 * corresponde ao item entre chaves("{}") no template
 * @example loadTemplate('template_mail.txt', Array('nome' => 'Luciano')).
 * Imprime o texto 'Luciano' na posição {nome} do template de email
 * @return string Retorna um template com as devidas substituições.
 */
function loadTemplate ($file, $dados)
{
    if (!file_exists($file)) return false;

    $template = file_get_contents($file, 'r');
    foreach ($dados as $key => $value){
      $template = str_replace('{'.$key.'}', str_replace("'","",$value), $template);
    }
    return $template;
}

/**
 * Carrega uma sessão com a chave passada como parametro
 * @param string $sessao Chave de sessão válida
 * @return string Valor contido na sessão ou vazio
 */
function session_load($sessao){
    return isset( $_SESSION[$sessao] ) ? $_SESSION[$sessao] : '';
}