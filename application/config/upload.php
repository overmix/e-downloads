<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['upload_path']      = 'uploads/';
$config['allowed_types']    = 'gif|jpg|png';
$config['allowed_videos']   = 'flv|mpeg|avi';
$config['overwrite']        = FALSE;
$config['language']         = "portugues";

$config['image_categories_info'] = array (
    'baixa' =>  'Câmera ou celular - tamanho máximo: 1600 x 1200 pixels.',
    'alta'  =>  'Fotos em alta resolução - tamanho mínimo: 3000 x 2000 pixels (gif, jpg, png).'
);

/*
|--------------------------------------------------------------------------
| Baixa
|--------------------------------------------------------------------------
|
| Arquivos definidos como sendo de baixa resolução, fotos tiradas de
| Câmeras ou celulares
| 
|
*/
$config['baixa'] = array (
    'max_width'  => '1600',
    'max_height' => '1200'   
);

/*
|--------------------------------------------------------------------------
| Alta
|--------------------------------------------------------------------------
|
| Arquivos definidos como sendo de alta resolução.
| 
|
*/
$config['alta']  =  array (
    'max_width'  => '3000',
    'max_height' => '2000'   
);

