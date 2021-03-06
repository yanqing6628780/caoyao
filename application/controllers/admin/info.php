<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class info extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/info
     *  - or -
     *      http://example.com/index.php/info/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/info/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/info/";
    }

    public function index()
    {
        checkPermission('info_view');

        $this->load->library('pagination');

        $this->data['table'] = $table = $this->input->get_post('table');
        $this->data['q'] = $q = $this->input->get_post('q');
        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 30;

        $this->general_mdl->setTable('category');
        $this->data['categories'] = $this->general_mdl->get_query()->result_array();

        $fileds = array('type', 'province', 'city', 'district', 'company', 'address');
        $info_data = array();
        $like = array();

        if($q){
            foreach ($fileds as $key => $value) {
                $like[$value] = $q;
            }
        }

        if($table){
            $this->general_mdl->setTable($table);
            //查询数据的总量,计算出页数
            $query = $this->general_mdl->get_query_or_like($like);
            $page = ceil($query->num_rows()/$pageSize);
            $this->data['page'] = $page;

            //取出当前面数据
            $query = $this->general_mdl->get_query_or_like($like, array(), $start, $pageSize);
            $info_data = $query->result_array();
            $this->data['current_page'] = $start;
        }
        $this->data['result'] = $info_data;
        $this->data['title'] = '信息管理';

        $this->load->view('admin_info/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->general_mdl->setTable('category');
        $this->data['categories'] = $this->general_mdl->get_query()->result_array();

        $this->load->view('admin_info/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $table = $data['table'];
        unset($data['table']);
        $this->general_mdl->setTable($table);
        $this->general_mdl->setData($data);
        if($infoType_id = $this->general_mdl->create())
        {
            $response['status'] = "y";
            $response['info'] = "添加成功,";
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
        $this->data['table'] = $this->input->post('table');

        $row = array();
        if($this->data['table']){        
            $this->general_mdl->setTable($this->data['table']);
            $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
            $row = $query->row_array();
        }

        $this->data['row'] = $row;

        $this->load->view('admin_info/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $data = $this->input->post(NULL, TRUE);

        $table = $data['table'];
        unset($data['table']);

        $where = array('id'=>$data['id']);
        unset($data['id']);

        $this->general_mdl->setTable($table);
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
        $table = $this->input->post('table');

        $response['success'] = false;

        $this->general_mdl->setTable($table);
        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;
        
        echo json_encode($response);
    }

    public function copy_to_free()
    {
        $ids = $this->input->get_post('id');
        $table = $this->input->get_post('table');
        foreach ($ids as $value) {
            $this->general_mdl->setTable($table);
            $query = $this->general_mdl->get_query_by_where(array('id' => $value['value']));
            $row = $query->row_array();

            unset($row['id']);
            $row['table'] = $table;
            $this->general_mdl->setTable('free_info');
            $this->general_mdl->setData($row);
            $this->general_mdl->create();
        }

        $response['success'] = true;
        echo json_encode($response);
    }
}

/* End of file info.php */
/* Location: ./application/controllers/info.php */
