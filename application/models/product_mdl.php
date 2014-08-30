<?php

class product_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
        $this->setTable('product');
    }

    public function is_relation($row_id, $product_id)
    {
        $this->setTable('product_rel');

        if($row_id){        
            $string = "\"$product_id\"";
            $query = $this->get_query_like(array('product_rel_data' => $string), array('id' => $row_id));
            return $query->num_rows() ? TRUE : FALSE;
        }else{
            return FALSE;
        }
    }

    public function get_rel_products($product_id)
    {
        $this->setTable('product_rel');
        $string = "\"$product_id\"";
        $query = $this->get_query_like(array('product_rel_data' => $string));
        $relation_products_arr = array();
        if($query->num_rows()){
            foreach ($query->result_array() as $key => $item) {
                $relation_products_arr = array_merge($relation_products_arr, json_decode($item['product_rel_data']));
            }
        }
        return array_unique($relation_products_arr);
    }

    public function get_product_info($product_id)
    {
        $this->setTable('product');
        $query = $this->get_query_by_where( array('id' => $product_id) );

        return $query->row();
    }
}

?>
