<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class orders extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->load->library('logger');

        if(!$this->tank_auth->is_logged_in()){
            redirect('/login/');
        }

        $this->general_mdl->setTable('order');
    }

    public function index()
    {
        $this->load->model('order_mdl');

        $query = $this->general_mdl->get_query();
        $data['result'] = $query->result_array();

        $this->general_mdl->setTable('order_product');
        foreach ($data['result'] as $key => $value) {
            $data['result'][$key]['total'] = $this->order_mdl->sum($value['id']);
        }

        $this->load->view('front/order_list', $data);
    }

    public function id($id = null, $order_num = null)
    {
        $this->load->model('attr_mdl');
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
        $id = $this->input->post('rowid');
        $qty = $this->input->post('qty');

        $this->general_mdl->setTable('order_product');
        if($qty > 0){        
            $data = array('qty' => $qty);
            $this->general_mdl->setData($data);
            $this->general_mdl->update(array('id' => $id));
        }else{
            $this->product_delete($id);
        }

        $this->id($order_id);
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
