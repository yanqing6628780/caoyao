<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class orders extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        checkIsLoggedIn();
        $this->general_mdl->setTable('order');

        $this->data['controller_url'] = "admin/orders/";
    }

    public function index()
    {
        $this->load->model('order_mdl');

        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;

        $this->data['exchange_fair_id'] = $exchange_fair_id = $this->input->get_post('exchange_id');

        $where = $exchange_fair_id ? array('exchange_fair_id' => $exchange_fair_id) : array();

        //查询数据的总量,计算出页数
        $query = $this->order_mdl->get_orders();
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $query = $this->order_mdl->get_orders($where, ($start-1), $pageSize);
        $result = $query->result_array();
        $this->data['current_page'] = $start;
        
        $this->data['title'] = $exchange_fair_id ? $result[0]['exchange_fair_name'].' 订单管理' : '订单管理';
        $this->data['result'] = $result;

        $this->general_mdl->setTable('exchange_fair');
        $this->data['exchange_fairs'] = $this->general_mdl->get_query_by_where(array('state' => 1))->result_array();

        $this->load->view('admin_order/list', $this->data);
    }    

    //修改
    public function edit()
    {
        $this->data['id'] = $this->input->post('id');

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $row = $query->row_array();

        $this->data['row'] = $row;

        $this->load->view('admin_exchange/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $where = array('id'=>$data['id']);
        unset($data['id']);

        $this->general_mdl->setData($data);
        $isUpdated = $this->general_mdl->update($where);

        if($isUpdated){
            $response['status'] = "y";
            $response['info'] = "修改成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "修改完成";
        }

        echo json_encode($response);
    }

    //删除
    public function del()
    {
        $id = $this->input->post('id');
        $code = $this->input->post('code');

        $response['success'] = false;
    
        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;

        echo json_encode($response);
    }
}
