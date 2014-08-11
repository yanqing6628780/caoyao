<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Goods extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->load->library('logger');

        if(!$this->tank_auth->is_logged_in()){
            redirect('/login/');
        }

        $this->load->model('tank_auth/profiles_mdl', 'profiles_mdl');
        $this->general_mdl->setTable('product');
    }

    public function index()
    {
        $data['class_id'] = $class_id = $this->input->get('class');
        $where = array();
        if($class_id){
            $where['small_class_id'] = $class_id;
        }

        $query = $this->general_mdl->get_query_by_where($where);
        $data['result'] = $query->result_array();

        $this->load->view('front/good_list', $data);
    }

	public function query()
	{
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

        if(isset($query)){
            $data['result'] = $query->result_array();
        }

		$this->load->view('front/good_list', $data);
	}

    public function id($id)
    {
        $where = array();
        if($id){
            $where['id'] = $id;
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

        $this->load->view('front/good_detail', $data);
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
        $post_data = $this->input->post(NULL, true);

        //检查是否已经创建订单
        if(!$this->session->userdata('order_id')){        
            $this->general_mdl->setTable('order');
            do {
                $data['order_number'] = get_order_sn();
                $query = $this->general_mdl->get_query_by_where(array('order_number' => $data['order_number'] ));
            } while ($query->num_rows() > 0);

            //创建订单
            $data['user_id'] = $this->tank_auth->get_user_id();
            $data['exchange_fair_id'] = 1;
            $data['create_time'] = date("Y-m-d H:i:s");;
            $this->general_mdl->setData($data);
            $order_id = $this->general_mdl->create();
            $this->session->set_userdata('order_id', $order_id);
        }

        if($this->session->userdata('order_id')){        
            $product_data['order_id'] = $where['order_id'] = $this->session->userdata('order_id');
            $this->general_mdl->setTable('order_product');

            foreach ($post_data['color'] as $color_id => $item) {
                foreach ($item as $size_id => $qty) {
                    if($qty) {  //判断是否有数量
                        $product_data['product_id'] = $where['product_id'] = $post_data['id'];
                        $product_data['first_attribute_values_id'] = $where['first_attribute_values_id'] = $color_id;
                        $product_data['second_attribute_values_id'] = $where['second_attribute_values_id'] = $size_id;

                        //查询是否已有相同规格的商品
                        $query = $this->general_mdl->get_query_by_where($where);
                        $product_data['qty'] = $query->num_rows() > 0 ? (int)$query->row()->qty + (int)$qty : (int)$qty;

                        $this->general_mdl->setData($product_data);
                        if($query->num_rows() > 0){
                            $this->general_mdl->update( array('id' => $query->row()->id) );
                        }else{
                            $this->general_mdl->create();
                        }

                    }
                }
            }
            $response['success'] = true;
        }

        echo json_encode($response);
    }
}
