<?php
class Daemon
{
    var $secs = 5;
    var $_isRunning = false;    

    function __construct ()
    {
        ob_start();
        ob_implicit_flush(true);
        set_time_limit(0);
        $fp = fopen('daemon.log', 'a');
        chmod('daemon.log', 0777);
        fclose($fp);
    }

    function start ()
    {
        $this->_isRunning = true;

        while ($this->_isRunning)
        {
            $this->sleep_echo();
            ob_end_flush();
        }
    }

    function stop()
    {
        $this->_isRunning = false;
    }

    function sleep_echo() {
        $this->secs = (int)$this->secs;
        for ($i=0; $i<$this->secs; $i++) {
            $this->_doTask();
            // se tiver buffer, limpa ele
            if (ob_get_length()){
                @ob_flush();
                @flush();
            }
            sleep(1);
        }
    }

    function _doTask()
    {
      $fp = fopen('daemon.log', 'a');
      fwrite($fp, date("d/m/Y H:i:s \r\n"));
      fclose($fp);
    }
}