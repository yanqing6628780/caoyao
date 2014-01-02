<?php

class Logs_mdl extends General_mdl
{    

	function __construct()
	{
		parent::__construct();
        $this->setTable('ci_logs');
	}    
}

?>