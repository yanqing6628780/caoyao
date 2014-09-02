<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Goods extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->load->library('logger');

        check_front_IsLoggedIn();

        $this->load->model('tank_auth/profiles_mdl', 'profiles_mdl');
        $this->general_mdl->setTable('product');
    }

    public function index()
    {
        $this->load->model('RSTR_mdl');
        $this->load->model('attr_mdl');

        $data['class_id'] = $class_id = $this->input->get('class');
        $where = array();
        if($class_id){
            $where['small_class_id'] = $class_id;
        }

        $query = $this->general_mdl->get_query_by_where($where);
        $result = $query->result_array();

        $this->general_mdl->setTable('evaluation_score');
        foreach ($result as $key => $value) {
            $sum_score = 0;
            $where = array(
                'product_id' => $value['id'],
                'exchange_fair_id' => $this->session->userdata('current_exchange_fair')
            );
            $score_rs = $this->general_mdl->get_query_by_where($where)->result_array();
            if($score_rs){            
                foreach ($score_rs as $score) {
                    $sum_score += $score['score'];
                }
                $result[$key]['sum_score'] = $sum_score/count($score_rs);
            }else{
                $result[$key]['sum_score'] = 5;
            }
        }

        $data['result'] = $result;

        $this->load->view('front/good_list', $data);
    }

	public function query()
	{
        $this->load->model('RSTR_mdl');
        $this->load->model('attr_mdl');
        
        $post_data = $this->input->post(NULL, true);
        $data = array();
        $where = array();

        if($post_data['barcode']){
            $where['barcode'] = $post_data['barcode'];
            $query = $this->general_mdl->get_query_by_where($where);
        }

        if($post_data['product_name']){
            $like['product_name'] = $post_data['product_name'];
            $query = $this->general_mdl->get_query_or_like($like, array());
        }

        $data['result'] = array();
        if(isset($query)){
            $result = $query->result_array();

            $this->general_mdl->setTable('evaluation_score');
            foreach ($result as $key => $value) {
                $sum_score = 0;
                $where = array(
                    'product_id' => $value['id'],
                    'exchange_fair_id' => $this->session->userdata('current_exchange_fair')
                );
                $score_rs = $this->general_mdl->get_query_by_where($where)->result_array();
                if($score_rs){            
                    foreach ($score_rs as $score) {
                        $sum_score += $score['score'];
                    }
                    $result[$key]['sum_score'] = $sum_score/count($score_rs);
                }else{
                    $result[$key]['sum_score'] = 5;
                }
            }
            $data['result'] = $result;
        }

		$this->load->view('front/good_list', $data);
	}

    public function id($id)
    {
        $this->load->model('order_mdl');
        $this->load->model('RSTR_mdl');
        $this->load->model('product_mdl');

        $where = array();
        if($id){
            $where['id'] = $id;
        }else{
            show_error('找不到页面', 404, '');
        }
        $query = $this->general_mdl->get_query_by_where($where);
        $data['row'] = $query->row_array();

        $this->general_mdl->setTable('attribute_values');
        $where_attr = array('product_id' => $id, 'attribute_type' => 0);

        $query = $this->general_mdl->get_query_by_where($where_attr);
        $data['colors'] = $query->result_array();

        $where_attr['attribute_type'] = 1;
        $query = $this->general_mdl->get_query_by_where($where_attr);
        $data['sizes'] = $query->result_array();

        $data['rel_products'] = $this->product_mdl->get_rel_products($id);

        $data['order_id'] = $this->session->userdata('order_id') ? $this->session->userdata('order_id') : 0;
        $data['necessities_scheme_id'] = $this->session->userdata('necessities_scheme_id') ? $this->session->userdata('necessities_scheme_id') : 0;

        if($this->input->get('view')){
            $this->load->view('front/rel_good_detail', $data);
        }else{
            $this->load->view('front/good_detail', $data);
        }
    }

    public function to_cart()
    {
        $this->load->library('cart');
        $post_data = $this->input->post(NULL, true);

        $data = array();
        $base_data = array(
            'id'      => $post_data['id'],
            'qty'     => 0,
            'price'   => $post_data['price'],
            'name'    => $post_data['name'],
            'options' => array()
        );

        foreach ($post_data['color'] as $color_id => $item) {
            foreach ($item as $size_id => $qty) {
                $base_data['qty'] = $qty;
                $base_data['options'] = array('0' => $color_id, '1' => $size_id);
                $data[] = $base_data;
            }
        }
        $rowid_arr[] = $this->cart->insert($data);

        $response['success'] = empty($rowid_arr) ? false : true;
        echo json_encode($response);
    }

    public function to_order()
    {
        $response['success'] = false;
        $response['info'] = '保存失败!';
        $post_data = $this->input->post(NULL, true);

        $this->load->model('order_mdl');

        $exchange_fair_id = $this->session->userdata('current_exchange_fair');

        //检查是否有已经存在的订单
        if($exchange_fair_id){
            $this->general_mdl->setTable('order');
            $order_where = array(
                'exchange_fair_id' => $exchange_fair_id,
                'user_id' => $this->tank_auth->get_user_id(),
                'is_pass' => 0
            );
            $query = $this->general_mdl->get_query_by_where($order_where);
            if ($query->num_rows()) {
                $this->session->set_userdata('order_id', $query->row()->id);
            }
        }else{
            show_error('当前没有订货会 <a href="'.site_url('login/logout').'">返回</a>', 404, '找不到页面');
        }

        //检查是否已经创建订单
        if(!$this->session->userdata('order_id')){        
            $this->general_mdl->setTable('order');
            do {
                $data['order_number'] = get_order_sn();
                $query = $this->general_mdl->get_query_by_where(array('order_number' => $data['order_number'] ));
            } while ($query->num_rows() > 0);

            //创建订单
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['exchange_fair_id'] = $this->session->userdata('current_exchange_fair');
            $data['create_time'] = date("Y-m-d H:i:s");;
            $this->general_mdl->setData($data);
            $order_id = $this->general_mdl->create();
            $this->session->set_userdata('order_id', $order_id);
        }

        if($this->session->userdata('order_id')){        
            $product_data['order_id'] = $where['order_id'] = $this->session->userdata('order_id');

            if( !$this->order_mdl->check_is_pass( $where['order_id'] ) ){
                $this->general_mdl->setTable('order_product');
                foreach ($post_data['color'] as $color_id => $item) {
                    foreach ($item as $size_id => $qty) {

                        //查询订单内是否已有相同规格的商品
                        $product_data['product_id'] = $where['product_id'] = $post_data['id'];
                        $product_data['first_attribute_values_id'] = $where['first_attribute_values_id'] = $color_id;
                        $product_data['second_attribute_values_id'] = $where['second_attribute_values_id'] = $size_id;
                        $query = $this->general_mdl->get_query_by_where($where);

                        if($qty > 0) {  //判断是否有数量
                            $product_data['qty'] = (int)$qty;
                            $this->general_mdl->setData($product_data);
                            $query->num_rows() > 0 ? $this->general_mdl->update( array('id' => $query->row()->id) ) : $this->general_mdl->create();
                        }else{
                            if($query->num_rows() > 0) { 
                                $this->general_mdl->delete_by_id( $query->row()->id );
                            }
                        }
                    }
                }
                $response['success'] = true;
                $response['info'] = '保存成功';
            }else{
                $response['info'] = '订单已结算!请不要继续下单';
            }
        }

        echo json_encode($response);
    }

    public function rating($good_id = null)
    {
        if($good_id){        
            $query = $this->general_mdl->get_query_by_where(array('id' => $good_id));
            $data['row'] = $query->row_array();
        }

        $this->load->view('front/rating', $data);
    }

    public function rating_save()
    {
        $good_id = $this->input->post('good_id');
        $score = $this->input->post('score');
        $comment_content = $this->input->post('comment_content');

        $where = $data = $data2 = array(
            'product_id' => $good_id,
            'user_id' => $this->tank_auth->get_user_id(),
            'exchange_fair_id' => $this->session->userdata('current_exchange_fair')
        );
        $data['score'] = $score;

        $this->general_mdl->setTable('evaluation_score');

        if($this->general_mdl->get_query_by_where($where)->num_rows()){
            $response['info'] = "你已经评价过该商品，请不要重复评价";
        }else{
            $this->general_mdl->setData($data);
            $response['info'] = $this->general_mdl->create() ? "评价成功" : "评价失败";
        }

        $this->general_mdl->setTable('comments');
        if($comment_content){
            $data2['comments_content'] = $comment_content;
            $this->general_mdl->setData($data2);
            $response['info'] = $this->general_mdl->create() ? "评论成功" : "评价失败";
        }

        echo json_encode($response);
    }

    public function pic()
    {
        $this->load->helper('directory');

        $name = $this->input->get('name');
        $path = sprintf("%s/%s", 'product_pic', $name);
        $map = directory_map($path, 0);
        $data['map'] = $map ? $map : array();
        $data['path'] = $path;
        $data['name'] = $name;
        $this->load->view('front/pic', $data);
    }
}
