<?php

class General_mdl extends CI_Model
{

    private $_table;
    private $_from;
    private $_data;
    public $sys_configs;

	function __construct()
	{
		parent::__construct();
        //系统配置数据
        $this->setTable('sys_config');
        $res = $this->get_query_by_where(array('cat' => 'sys'))->result_array();
        foreach ($res as $key => $item) {
            $this->sys_configs[$item['name']] = $item['value'];
        }
        $this->setTable('');
        $this->setData(array());
	}

	// General function
	public function setTable($table = '')
    {
        $this->_table = $table;
    }

    public function getTable()
    {
        return $this->_table;
    }


    public function setData($data = array())
    {
        $this->_data = $data;
    }

    public function getData()
    {
        return $this->_data;
    }


    //插入数据
    /*
    **
    ** return INT or false INT是插入数据库的ID
    */
    public function create()
    {
        if($this->_data){
            $this->db->insert($this->_table, $this->_data);
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

    //删除数据
    /*
    **
    **
    */
    public function delete($where = array())
    {
        $this->db->delete($this->_table, $where);
        return $this->db->affected_rows();
    }

    //删除数据
    /*
    ** 根据id删除数据
    **
    */
    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->_table);
    }

    //更新数据
    /*
    **
    ** return INT or false INT是插入数据库的ID
    */
    public function update($where = array())
    {

        if($where){
            $this->db->where($where);
            if($this->_data)
            {
                $this->db->update($this->_table, $this->_data);
                return $this->db->affected_rows();
            }else{
                return false;
            }
        }
        return false;
    }

    public function update_where_in($field, $array = array())
    {

        if($array){
            $this->db->where_in($field, $array);
            if($this->_data){
                return $this->db->update($this->_table, $this->_data);
            }else{
                return false;
            }
        }
        return false;
    }

    public function get_query($start = 0, $pageSize = '', $orderby = '')
    {

        if($orderby)
        {
            $this->db->order_by($orderby);
        }

        if($pageSize)
        {
            $query = $this->db->get($this->_table, $pageSize, $start);
        }else{
            $query = $this->db->get($this->_table);
        }

        return $query;
    }

    public function get_fields($where = array(), $fields = '*',$start = 0, $pageSize = '')
    {
        if($where){
            foreach($where as $key=>$row)
            {
                $this->db->where($key, $row);
            }
        }

        $this->db->select($fields);

        return $this->get_query($start, $pageSize);
    }

    public function get_query_by_where_in($where_field = '',$where = array(), $start = 0, $pageSize = '', $orderby = "")
    {
        if($where_field){
            $this->db->where_in($where_field, $where);
        }

        return $this->get_query($start, $pageSize, $orderby);
    }


    public function get_query_by_where($where = array(), $start = 0, $pageSize = '', $orderby = '')
    {
        if($where){
            foreach($where as $key=>$row)
            {
                $this->db->where($key, $row);
            }
        }

        if($orderby)
        {
            $this->db->order_by($orderby);
        }

        return $this->get_query($start, $pageSize);
    }


    public function get_query_by_or_where($or_where=array(), $start = 0, $pageSize = '', $orderby = '')
    {
        if($or_where){
            foreach($or_where as $key=>$row)
            {
                $this->db->or_where($key, $row);
			}
        }

        if($orderby)
        {
            $this->db->order_by($orderby);
        }

        return $this->get_query($start, $pageSize);
    }

    public function get_query_or_like($like = array(), $where = array(), $start = 0, $pageSize = '')
    {
        if($like){
            $this->db->or_like($like);
        }

        if($where){
            foreach($where as $key => $row)
            {
                $this->db->where($key, $row);
            }
        }

        return $this->get_query($start, $pageSize);
    }    

    public function get_query_like($like = array(), $where = array(), $start = 0, $pageSize = '')
    {
        if($like){
            $this->db->like($like);
        }

        if($where){
            foreach($where as $key => $row)
            {
                $this->db->where($key, $row);
            }
        }

        return $this->get_query($start, $pageSize);
    }

    //获取自增ID
    public function get_auto_increment()
    {
        $this->db->select_max('id', 'last_id');
        $query = $this->get_query();
        return $query->row()->last_id + 1;
    }
}

?>
