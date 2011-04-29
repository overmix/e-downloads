<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * email do admin do sistema
 */
$config['admin_email'] = 'seu_email@xyz.com.br';
$config['admin_name']  = 'e-Downloads';

/*
 * Define se a liberação dos pedidos será através do retorno_automatico
 */
$config['usar_retorno']  = false;

/*
 * Seu email no pagseguro
 * Seu token no pagseguro (opcional)
 */
$config['dados_pgs'] = array(
    'email'              => 'seu_email@xyz.com.br',
    'token'              => '0123456789abcdef0123456789abcdef'
);


