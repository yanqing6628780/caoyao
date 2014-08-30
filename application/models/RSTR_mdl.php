<?php

class RSTR_mdl extends General_mdl
{

	function __construct()
	{
		parent::__construct();
        $this->setTable('rel_stores_exchange_fair');
    }


    public function get_RSTR($exchange_fair_id, $user_id)
    {
        $where = array(
            'exchange_fair_id' => $exchange_fair_id,
            'user_id' => $user_id
        );
        $rstr_row = $this->get_query_by_where($where)->row_array();

        $this->setTable('small_class_limits');
        $this->db->join('small_class', 'small_class.id = small_class_limits.small_class_id', 'left');
        $sclimits_rs = $this->get_query_by_where(array('small_class_restrictions_id' => $rstr_row['small_class_restrictions_id']))->result_array();

        $rstr_row['sc_limits'] = $sclimits_rs;

        $this->setTable('necessities');
        $this->db->join('product', 'product.id = necessities.product_id', 'left');
        $this->db->join('attribute_values', 'attribute_values.id = necessities.attribute_values_id', 'left');
        $necessities_rs = $this->get_query_by_where(array('necessities_schem_id' => $rstr_row['necessities_scheme_id']))->result_array();

        $rstr_row['sc_limits'] = $sclimits_rs;
        $rstr_row['necessities'] = $necessities_rs;

        return $rstr_row;
    }

    public function get_necessities($necessities_scheme_id, $product_id, $attr_id = null)
    {
        $this->setTable('necessities');

        $this->db->join('attribute_values', 'attribute_values.id = necessities.attribute_values_id', 'left');
        $where = array(
            'necessities_schem_id' => $necessities_scheme_id,
            'necessities.product_id' => $product_id
        );
        if($attr_id){
            $where['attribute_values_id'] = $attr_id;
        } 

        $query = $this->get_query_by_where($where);

        return $attr_id ? $query->row_array() : $query->result_array();
    }
}

?>
