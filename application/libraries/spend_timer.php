<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

 class spend_timer
 {

    private $StartTime = 0;//程序运行开始时间
    private $StopTime  = 0;//程序运行结束时间
    private $TimeSpent = 0;//程序运行花费时间

    function start() //程序运行开始
    {
        $this->StartTime = microtime();
    }
    function stop() //程序运行结束
    {
        $this->StopTime = microtime();
    }

    //程序运行花费的时间
    function spend()
    {
        if ($this->TimeSpent)
        {
            return $this->TimeSpent;
        }
        else
        {
            list($StartMicro, $StartSecond) = explode(" ", $this->StartTime);
            list($StopMicro, $StopSecond) = explode(" ", $this->StopTime);
            $start = doubleval($StartMicro) + $StartSecond;
            $stop = doubleval($StopMicro) + $StopSecond;
            $this->TimeSpent = $stop - $start;
            return substr($this->TimeSpent,0,8)."秒";//返回获取到的程序运行时间差
        }
    }
}
?>
