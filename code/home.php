<?php
include 'ice/app.php';
include 'ice/model.php';
include 'app/config.php';
include 'app/model.php';
include 'app/controllers.php';

app (array(
    // Cadastro do usuario
    '^/cadastro/?$'     => 'Cadastro',
    '^/downloads?/?$'   => 'Download',

    // Deve estar sempre por último
    '^/?$'              => 'Home',
));