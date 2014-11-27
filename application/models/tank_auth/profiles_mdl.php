<?php

class profiles_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
        $this->setTable('user_profiles');
	}

    public function get_profiles($where = array())
    {
        $this->db->join('branch', 'branch.id = user_profiles.branch_id', 'left');
        return $this->get_query_by_where($where);
    }

    public function get_by_user_id($id)
    {
        return $this->get_query_by_where(array('user_id' => $id));
    }
}

?>
