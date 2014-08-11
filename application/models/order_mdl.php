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

}

?>
