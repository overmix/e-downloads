<?php
class Home {
    var $title = 'Home';
    public function get()
    {
        $titulo = $this->title;
        include TEMPLATE_PATH . 'home.php';
    }
}