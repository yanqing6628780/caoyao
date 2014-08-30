<?php

class order_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
    }

    public function sum($order_id)
    {
        $total = 0;

        $this->setTable('order_product');
        $this->db->select('product_id');
        $this->db->select_sum('qty', 'sum_qty');
        $this->db->group_by("product_id"); 
        $query = $this->get_query_by_where(array('order_id' => $order_id));
        $order_products = $query->result_array();

        foreach ($order_products as $key => $value) {
            $this->setTable('product');
            $query = $this->get_query_by_where(array('id' => $value['product_id']));
            $total += $query->row()->unit_price * $value['sum_qty'];
        }

        return $total;
    }

    public function check_is_pass($order_id)
    {
        $this->setTable('order');
        $query = $this->get_query_by_where(array('id' => $order_id));
        return $query->row()->is_pass;
    }    

    public function product_group($order_id)
    {
        $this->setTable('order_product');
        $this->db->select('product_id, count(product_id) as style_num');
        $this->db->select_sum('qty', 'sum_qty');
        $this->db->group_by("product_id"); 
        $query = $this->get_query_by_where(array('order_id' => $order_id));
        $order_products = $query->result_array();

        foreach ($order_products as $key => $value) {
            $this->setTable('product');
            $this->db->join('small_class', 'small_class.id = product.small_class_id', 'left');
            $query = $this->get_query_by_where(array('product.id' => $value['product_id']));
            $order_products[$key]['info'] = $query->row_array();
        }
        return $order_products;
    }    

    public function smal_class_group($order_id)
    {
        $this->setTable('order_product');
        $this->db->select('small_class.small_class_name');
        $this->db->select_sum('qty', 'sum_qty');
        $this->db->join('product', 'product.id = order_product.product_id', 'left');
        $this->db->join('small_class', 'small_class.id = product.small_class_id', 'left');
        $this->db->group_by("product.small_class_id"); 
        $query = $this->get_query_by_where(array('order_id' => $order_id));
        $rs = $query->result_array();

        return $rs;
    }

    public function get_qty($order_id, $product_id, $first_id, $second_id)
    {
        $this->setTable('order_product');
        $where = array(
            'order_id' => $order_id,
            'product_id' => $product_id,
            'first_attribute_values_id' => $first_id,
            'second_attribute_values_id' => $second_id
        );
        $query = $this->get_query_by_where($where);

        return $query->num_rows() ? $query->row()->qty : 0;  
    }
}

?>
