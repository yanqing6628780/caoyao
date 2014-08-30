<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class orders extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->load->library('logger');

        check_front_IsLoggedIn();

        $this->general_mdl->setTable('order');
    }

    public function index()
    {
        $this->load->model('order_mdl');

        $exchange_fair_id = $this->session->userdata('current_exchange_fair');

        $where = array(
            'exchange_fair_id' => $exchange_fair_id,
            'user_id' => $this->tank_auth->get_user_id()
        );

        $query = $this->general_mdl->get_query_by_where($where);
        $data['result'] = $query->result_array();

        $this->general_mdl->setTable('order_product');
        foreach ($data['result'] as $key => $value) {
            $data['result'][$key]['total'] = $this->order_mdl->sum($value['id']);
        }

        $this->load->view('front/order_list', $data);
    }    

    public function my()
    {
        $this->load->model('order_mdl');
        $this->load->model('RSTR_mdl');

        $exchange_fair_id = $this->session->userdata('current_exchange_fair');

        $where = array(
            'exchange_fair_id' => $exchange_fair_id,
            'user_id' => $this->tank_auth->get_user_id()
        );

        $query = $this->general_mdl->get_query_by_where($where);
        $data['row'] = $row = $query->row_array();

        $this->general_mdl->setTable('order_product');
        $data['row']['total'] = $this->order_mdl->sum($row['id']);

        $data['order_products'] = $this->order_mdl->product_group($row['id']);
        $data['order_small_class_sum'] = $this->order_mdl->smal_class_group($row['id']);

        $data['RSTR'] = $this->RSTR_mdl->get_RSTR($exchange_fair_id, $this->tank_auth->get_user_id());

        $this->load->view('front/my_order', $data);
    }

    public function id($id = null, $order_num = null)
    {
        $this->load->model('attr_mdl');

        $product_id = $this->input->get_post('product_id');
        $data['order_num'] = $order_num;
        
        $where = array();
        if($id){
            $where['id'] = $data['order_id'] = $id;
        }else{
            show_404();
        }


        $query = $this->general_mdl->get_query_by_where($where);
        $data['row'] = $query->row_array();

        $this->general_mdl->setTable('order_product');
        $where2 = array('order_id' => $id);

        $data['product_id'] = '';
        if($product_id){
            $data['product_id'] = $where2['product_id'] = $product_id;
        }

        $query = $this->general_mdl->get_query_by_where($where2);
        $data['products'] = $query->result_array();
        $this->general_mdl->setTable('product');
        foreach ($data['products'] as $key => $value) {
            $data['products'][$key]['info'] = $this->general_mdl->get_query_by_where(array('id' => $value['product_id']))->row_array();
        }

        $this->load->view('front/order_detail', $data);
    }

    public function product_update()
    {
        $order_id = $this->input->post('order_id');
        $order_num = $this->input->post('order_num');
        $order_products = $this->input->post('order_product');

        $this->general_mdl->setTable('order_product');
        foreach ($order_products as $id => $qty) {
            if($qty > 0){        
                $data = array('qty' => $qty);
                $this->general_mdl->setData($data);
                $this->general_mdl->update(array('id' => $id));
            }else{
                $this->product_delete($id);
            }
        }

        $response['success'] = true;
        echo json_encode($response);
    }

    public function product_delete($id)
    {
        $order_id = $this->input->get('order_id');

        $where = array();
        if($id){
            $where['id'] = $id;
        }else{
            show_404();
        }

        $this->general_mdl->setTable('order_product');
        $this->general_mdl->delete_by_id($id);

        if($order_id){
            $this->id($order_id);
        }
    }
}
