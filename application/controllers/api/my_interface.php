<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class my_interface extends REST_Controller
{
    
    //获取订单表数据
    function get_order_post(){
        $order_sn = $this->post('order_sn');
        $this->general_mdl->setTable('order');
        $query = $this->general_mdl->get_query_by_where(array('order_sn' => $order_sn));
        if ($query->num_rows() > 0) {
            $result = $query->row_array();

            $this->general_mdl->setTable('order_goods');
            $order_goods = $this->general_mdl->get_query_by_where(array('order_id' => $result['id']))->result_array();
            $result['goods'] = $order_goods;
            
            $result['status'] = 1;
            $this->response($result, 200); // 200 being the HTTP response code
        } else {
            $result['status'] = 0;
            $result['error'] = 'no data';
            $this->response($result, 404);
        }
    }



}