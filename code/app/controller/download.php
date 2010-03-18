<?php
/**
 * Class Download
 */
class Download
{
    var $title = 'Download';
    public function get()
    {
        $titulo = $this->title;
        include TEMPLATE_PATH . 'download.php';
    }
}
