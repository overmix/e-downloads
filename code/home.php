<?php
include 'ice/app.php';
include 'app/config.php';
include 'app/controllers.php';

app (array(
    '^/?$' => 'Home',
));