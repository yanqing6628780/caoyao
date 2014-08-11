<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class branch extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/branch
     *  - or -
     *      http://example.com/index.php/branch/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/branch/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('branch');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/branch/";
    }

    public function index()
    {
        checkPermission('branch_view');

        $branch_data = array();

        $query = $this->general_mdl->get_query();

        $this->data['total'] = $query->num_rows();
        $branch_data = $query->result_array();
        
        $this->data['title'] = '分公司管理';

        $this->general_mdl->setTable('region');
        foreach ($branch_data as $key => $value) {
            $regions = $this->general_mdl->get_query_by_where(array('branch_id' => $value['id']))->result_array();
            if($regions){
                foreach ($regions as $k => $v) {
                    $region_arr[] = $v['region_name'];
                }
            }
            $branch_data[$key]['regions'] = $regions ? implode(',',$region_arr) : '';
        }
        $this->data['result'] = $branch_data;

        $this->load->view('admin_branch/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_branch/add', $this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);

        $this->general_mdl->setData($data);
        if($this->general_mdl->create())
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

        $this->load->view('admin_branch/edit', $this->data);
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

    //添加
    public function add_region()
    {
        $this->data['id'] = $this->input->post('id');
        $this->load->view('admin_branch/add_region', $this->data);
    }

    //添加保存
    public function add_region_save()
    {
        $this->general_mdl->setTable('region');

        $data = $this->input->post(NULL, TRUE);

        $this->general_mdl->setData($data);
        if($this->general_mdl->create())
        {
            $response['status'] = "y";
            $response['info'] = "添加成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "添加失败";
        }

        echo json_encode($response);
    }
}

/* End of file branch.php */
/* Location: ./application/controllers/branch.php */
