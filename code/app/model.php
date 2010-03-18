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

class User extends Model
{}