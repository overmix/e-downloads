<?php
/**
 * Classe Home
 */
class Home
{
    var $titulo = 'Home';
    public function get()
    {
        $titulo = $this->titulo;
        include TEMPLATE_PATH . 'home.php';
    }
}

?>
