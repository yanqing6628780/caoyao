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

    //优惠卷发放
    function coupon_use_post()
    {
        $this->load->model('general_mdl');

        if($this->post('id') && $this->post('wx_num')){

            $this->general_mdl->setTable('coupon_kind');
            //优惠劵发行量和限取数量
            $query = $this->general_mdl->get_query_by_where( array('id' => $this->post('id')) ); 
            if($query->num_rows() > 0){
                $d[] = $coupon_circulation =  $query->row()->coupon_circulation;
                $d[] = $coupon_limit =  $query->row()->get_limit;
            }else{
                $data['status'] = 0;
                $data['error'] = "没有该优惠卷";
                $this->response($data, 404);
            }

            //获取优惠劵已经发放条数
            $this->general_mdl->setTable('coupon_use');
            $query = $this->general_mdl->get_query_by_where( array('coupon_id' => $this->post('id')) ); 
            $coupon_used_amount = $query->num_rows();

            $query = $this->general_mdl->get_query_by_where( array('coupon_id' => $this->post('id'), 'wx_num' => $this->post('wx_num')) );
            $user_own_coupon_num = $query->num_rows();

            if ($coupon_used_amount < $coupon_circulation && $user_own_coupon_num < $coupon_limit) {
                //检查优惠卷号是否重复
                do {
                    $coupon_num = generate_password(6, 1);
                } while ($this->general_mdl->get_query_by_where( array('coupon_num' => $coupon_num) )->num_rows() > 0);

                $data['coupon_num'] = $coupon_num;
                $data['wx_num'] = $this->post('wx_num');
                $data['coupon_id'] = $this->post('id');
                $this->general_mdl->setData($data);
                
                if ($this->general_mdl->create()) {
                    $data['status'] = 1;
                    $this->response($data, 200);
                } else {
                    $data['status'] = 0;
                    $this->response($data, 404);
                }
            }

            if ($user_own_coupon_num >= $coupon_limit) {
                $data['status'] = 0;
                $data['error'] = "你领取优惠劵的名额以用完";
                $this->response($data, 200);
            }

            if ($coupon_used_amount >= $coupon_circulation) {
                $data['status'] = 0;
                $data['error'] = "优惠劵已被领完";
                $this->response($data, 200);
            } 
        }else{
            $data['status'] = 0;
            $data['error'] = "请求失败";
            $this->response($data, 404);            
        }

    }

    //优惠卷打印
    function coupon_print_post()
    {
        $coupon_num = $this->post('coupon_num');
        if($coupon_num){

            $this->general_mdl->setTable('coupon_use');
            $query = $this->general_mdl->get_query_by_where( array('coupon_num' => $coupon_num) );
            if($query->num_rows() > 0){
                $row = $query->row_array();
                if($row['coupon_status'] == 1){
                    $data['status'] = 0;
                    $data['error'] = "该 优惠卷/奖品卷 已打印过";
                    $this->response($data, 404); 
                }

                $this->general_mdl->setTable('coupon_kind');
                $query = $this->general_mdl->get_query_by_where( array('id' => $row['coupon_id']) );

                if($query->num_rows() > 0){

                    $row['coupon_status'] = 1;
                    $update_data = $row;
                    $this->general_mdl->setTable('coupon_use');
                    $this->general_mdl->setData($update_data);
                    $this->general_mdl->update(array('id' => $row['id']));

                    $data['content'] = $query->row_array();
                    $data['status'] = 1;
                    $this->response($data, 200);
                }else{
                    $data['status'] = 0;
                    $data['error'] = "没有該 优惠卷/奖品卷 信息";
                    $this->response($data, 404); 
                }
            }else{
                $data['status'] = 0;
                $data['error'] = "没有此 优惠卷/奖品卷 号码";
                $this->response($data, 404); 
            }
        }else{
            $data['status'] = 0;
            $data['error'] = "请求失败";
            $this->response($data, 404);            
        }
    }
}