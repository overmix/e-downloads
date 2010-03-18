<?php
define ('DS', DIRECTORY_SEPARATOR);

// definições de url
define ('BASE_URL', 'http://localhost/e-downloads/code/');

// definições de path
define ('BASE_PATH', dirname(dirname(__FILE__)));
define ('TEMPLATE_PATH', BASE_PATH . DS . 'template' . DS);

define('DB_DNS', 'mysql:host=localhost;dbname=edownload');
define('DB_USERNAME', 'edownload');
define('DB_PASSWORD', 'edownload');
