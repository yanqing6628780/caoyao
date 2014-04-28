<?php

class profiles_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
        $this->setTable('user_profiles');
	}

    /*
    * 用户信用额度更新
    * $value int 增减的数值
    * $user_id int 用户id
    * $plus bool 信用额度运算方法 false-减 true-加
    */
    public function update_credit($value = 0, $user_id, $plus = true)
    {
        if($plus){
            $this->db->set('credit', 'credit+'.$value, FALSE);
        }else{
            $this->db->set('credit', 'credit-'.$value, FALSE);
        }
        $this->db->where(array('user_id'=>$user_id));
        $this->db->update($this->getTable());
    }

    public function get_by_user_id($id)
    {
        return $this->get_query_by_where(array('user_id' => $id));
    }
}

?>
