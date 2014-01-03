<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Program extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/program
     *  - or -
     *      http://example.com/index.php/program/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/program/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('program');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/program/";
    }

    public function index()
    {
        $program_data = array();
        $party_id_array = array();

        /*取出当前用户管理的会议*/
        $user_id = $this->dx_auth->get_user_id();
        $this->general_mdl->setTable('party');
        $query = $this->general_mdl->get_fields(array("user_id" => $user_id), "id");
        $user_party_ids = $query->result_array();
        foreach ($user_party_ids as $key => $value) {
            $party_id_array[] = $value['id'];
        }

        if($party_id_array)
        {        
            $this->general_mdl->setTable('program');
            $query = $this->general_mdl->get_query_by_where_in('party_id',$party_id_array, 0, '', "party_id ASC");
            $program_data = $query->result_array();
            $this->data['total'] = $query->num_rows();
            foreach ($program_data as $key => $value) {
                $this->general_mdl->setTable('party');
                $query = $this->general_mdl->get_query_by_where(array("id" => $value['party_id']));
                $program_data[$key]['party_title'] = $query->row()->title;
            }
        }

        $this->data['result'] = $program_data;
        $this->load->view('admin/head');
        $this->load->view('admin_program/list', $this->data);
    }

    //添加
    public function add()
    {        
        /*取出当前用户管理的会议*/
        $this->data['partys'] = array();
        $user_id = $this->dx_auth->get_user_id();
        $this->general_mdl->setTable('party');
        $query = $this->general_mdl->get_query_by_where(array("user_id" => $user_id));
        $this->data['partys'] = $query->result_array();

        $this->load->view('admin/head');
        $this->load->view('admin_program/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $post_data = $this->input->post(NULL,TRUE);

        $this->general_mdl->setData($post_data);
        if($this->general_mdl->create()){
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
        $this->data['partys'] = array();

        $params = $this->uri->uri_to_assoc();
        $this->data['id'] = $params['edit'];

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $this->data['row'] = $query->row_array();

        /*取出当前用户管理的会议*/
        $user_id = $this->dx_auth->get_user_id();
        $this->general_mdl->setTable('party');
        $query = $this->general_mdl->get_query_by_where(array("user_id" => $user_id));
        $this->data['partys'] = $query->result_array();

        $this->load->view('admin/head');
        $this->load->view('admin_program/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $id = $this->input->post('id');
        $post_data = $this->input->post(NULL, TRUE);
        unset($post_data['id']);
        
        $this->general_mdl->setData($post_data);
        $where = array('id'=>$id);

        if($this->general_mdl->update($where)){
            $response['status'] = "y";
            $response['info'] = "添加成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "添加失败";
        }
        echo json_encode($response);
    }

    //删除
    public function del()
    {
        $id = $this->input->post('id');

        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;

        echo json_encode($response);
    }
}

/* End of file program.php */
/* Location: ./application/controllers/program.php */
