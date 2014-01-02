<?php

class profiles_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
        $this->setTable('user_profiles');
	}

    public function update_used_credit($value = 0, $where = array())
    {
        $this->db->set('used_credit', 'used_credit+'.$value, FALSE);
        $this->db->where($where);
        $this->db->update($this->getTable());
    }
}

?>
