<?php

class buylogs_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
        $this->setTable('buy_logs');
	}

    public function get_by_member_id($id)
    {
        $query = $this->get_query_by_where(array('member_id' => $id));
        if($query->num_rows()>0){
            return $query->row();
        }else{
            return FALSE;
        }
    }    
}

?>
