<?php

class members_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
        $this->setTable('members_view');
	}

    public function members($where = array(), $start = 0, $pageSize = '', $orderby = '')
    {

        //统计出查询数据的数量
        $query = parent::get_query_by_where($where);
        $data['total'] = $query->num_rows();

        //查询数据分页数据
        $query = parent::get_query_by_where($where, $start, $pageSize, $orderby);
        $result = $query->result_array();

        foreach($result as $key => $row)
        {

        }

        $data['result'] = $result;
        return $data;
    }
}

?>
