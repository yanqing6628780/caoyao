<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class small_class extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/small_class
     *  - or -
     *      http://example.com/index.php/small_class/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/small_class/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('small_class');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/small_class/";
    }

    public function index()
    {
        checkPermission('category_view');

        $this->load->model('category_mdl');

        $small_class_data = array();

        $query = $this->general_mdl->get_query();

        $this->data['total'] = $query->num_rows();
        $small_class_data = $query->result_array();
        
        $this->data['result'] = $small_class_data;
        $this->data['title'] = '小类管理';

        $this->load->view('admin_small_class/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->general_mdl->setTable('big_class');
        $this->data['big_classes'] = $this->general_mdl->get_query()->result_array();
        $this->load->view('admin_small_class/add', $this->data);
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

        $this->general_mdl->setTable('big_class');
        $this->data['big_classes'] = $this->general_mdl->get_query()->result_array();

        $this->load->view('admin_small_class/edit', $this->data);
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

/* End of file small_class.php */
/* Location: ./application/controllers/small_class.php */
