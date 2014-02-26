<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/order
     *  - or -
     *      http://example.com/index.php/order/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/order/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('order');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/order/";
    }

    public function index()
    {
        checkPermission('order_view');

        $order_data = array();
        $party_id_array = array();

        if($this->dx_auth->is_admin()){
            $query = $this->general_mdl->get_query();
        }else{
            $query = $this->general_mdl->get_query_by_where(array("user_id" => $this->dx_auth->get_user_id()));
        }

        $this->data['total'] = $query->num_rows();
        $order_data = $query->result_array();
        
        $this->data['result'] = $order_data;
        $this->data['title'] = '订单管理' ;

        $this->load->view('admin_order/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_order/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);

        $this->general_mdl->setData($data);
        if($orderType_id = $this->general_mdl->create())
        {
            $response['status'] = "y";
            $response['info'] = "添加成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "添加失败";
        }

        echo json_encode($response);
    }

    //修改
    public function edit()
    {
        $this->data['id'] = $this->input->post('id');

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $row = $query->row_array();

        $this->data['row'] = $row;

        $this->load->view('admin_order/edit', $this->data);
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

        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $row = $query->row_array();

        $confirm_code = generate_verify_code(array($row['order_sn'], $row['mobile'], $row['user_id']));
        if($code == $confirm_code)
        {
            $this->general_mdl->delete_by_id($id);
            $response['success'] = true;
        }

        echo json_encode($response);
    }

    public function get_order_goods()
    {
        $id = $this->input->post('id');

        $this->general_mdl->setTable('order_goods');
        $query = $this->general_mdl->get_query_by_where(array('order_id' => $id));
        $result = $query->result_array();

        $this->data['result'] = $result;

        $this->load->view('admin_order/order_goods', $this->data);
    }

    public function del_order_good()
    {
        $id = $this->input->post('id');
        $code = $this->input->post('code');

        $response['success'] = false;

        $this->general_mdl->setTable('order_goods');
        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $row = $query->row_array();

        if($row)
        {        
            $confirm_code = generate_verify_code(array($row['id'], $row['order_id'], $row['nums']));
            if($code == $confirm_code)
            {
                $this->general_mdl->delete_by_id($id);
                $response['success'] = true;
            }
        }

        echo json_encode($response);
        
    }
}

/* End of file order.php */
/* Location: ./application/controllers/order.php */
