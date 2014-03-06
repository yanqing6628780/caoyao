<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lottery extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/lottery
     *  - or -
     *      http://example.com/index.php/lottery/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/lottery/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('lottery_items');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/lottery/";
    }

    public function index()
    {
        checkPermission('coupon_view');

        $lottery_data = array();
        $party_id_array = array();

        $query = $this->general_mdl->get_query();

        $this->data['total'] = $query->num_rows();
        $lottery_data = $query->result_array();
        
        $this->data['result'] = $lottery_data;
        $this->data['title'] = '奖品管理';

        $this->load->view('admin/head');
        $this->load->view('admin_lottery/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_lottery/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);

        $this->general_mdl->setData($data);
        if($lotteryType_id = $this->general_mdl->create())
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

        $this->load->view('admin_lottery/edit', $this->data);
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

        if($id != 1){        
            $this->general_mdl->delete_by_id($id);
            $response['success'] = true;
        }

        echo json_encode($response);
    }

}

/* End of file lottery.php */
/* Location: ./application/controllers/lottery.php */
