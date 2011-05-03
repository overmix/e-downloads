<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * email do admin do sistema
 */
$config['admin_email'] = 'seu_email@xyz.com.br';
$config['admin_name']  = 'e-Downloads';

/*
 * Define se a liberação dos pedidos será através do retorno_automatico
 * ou manualmente.
 * Troque de true para false se deseja liberar pedidos manualmente
 */
$config['usar_retorno']  = true;

/*
 * Edite a linha 20, trocando o texto "seu_email@xyz.com.br" pelo seu email no pagseguro
 * Edite a linha 21, trocando o texto "0123456789" pelo seu token no pagseguro (opcional)
 */
$config['dados_pgs'] = array(
    'email'              => 'seu_email@xyz.com.br',
    'token'              => '0123456789'
);

