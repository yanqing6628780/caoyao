<?php

class attr_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
        $this->setTable('attribute_values');
    }

    public function get_attr($pid, $attr_type, $output = 'string')
    {
        //关联尺码
        $query = $this->get_query_by_where(array('product_id' => $pid,'attribute_type' => $attr_type));
        if($query->num_rows()){
            foreach ($query->result_array() as $k => $v) {
                $arr[] = $v['values'];
            }
        }
        switch ($output) {
            case 'string':
                return $query->num_rows() ? implode(',', $arr) : false;
                break;
            case 'array':
                return $query->num_rows() ? $arr : array();
                break;
            default:
                return $query->result_array();
                break;
        }
    }

    public function get_attr_values($id)
    {
        $query = $this->get_query_by_where(array('id' => $id));
        return $query->num_rows() ? $query->row()->values : '未定义';
    }   

    public function attr_type_to_cn($type)
    {
        switch ($type) {
            case '0':
                return '颜色';
                break;
            case '1':
                return '尺码';
                break;
            default:
                return '未定义';
                break;
        }
    }
}

?>
