<?php

class category_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
	}

    public function get_big_class($id)
    {
        $this->setTable('big_class');
        $query = $this->get_query_by_where(array('id' => $id));
        
        return $query->row();
    }    
}

?>
