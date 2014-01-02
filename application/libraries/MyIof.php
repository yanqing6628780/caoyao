<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
require_once("Phpexcel.php");

class MyIof extends PHPExcel_IOFactory
{
    private $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();
    }
}

?>