<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vote extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/vote
     *  - or -
     *      http://example.com/index.php/vote/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/vote/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('votetype');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/vote/";
    }

    public function index()
    {
        $vote_data = array();
        $party_id_array = array();

        $party_id = $this->input->get("party");

        if($this->dx_auth->is_admin())
        {
            $this->general_mdl->setTable('party');
            $query = $this->general_mdl->get_query();
            $user_party_ids = $query->result_array();
            foreach ($user_party_ids as $key => $value) {
                $party_id_array[] = $value['id'];
            }
        }
        else
        {
            /*取出当前用户管理的会议*/
            $user_id = $this->dx_auth->get_user_id();
            $this->general_mdl->setTable('party');
            $query = $this->general_mdl->get_fields(array("user_id" => $user_id), "id");
            $user_party_ids = $query->result_array();
            foreach ($user_party_ids as $key => $value) {
                $party_id_array[] = $value['id'];
            }
        }

        $this->general_mdl->setTable('votetype');
        if($party_id)
        {
            if(in_array($party_id, $party_id_array))
            {
                $query = $this->general_mdl->get_query_by_where(array("party_id" => $party_id), 0, '', "party_id ASC");
            }
        }
        else
        {
            if($party_id_array)
            {        
                $query = $this->general_mdl->get_query_by_where_in('party_id',$party_id_array, 0, '', "party_id ASC");
            }
        }

        $this->data['total'] = $query->num_rows();
        $vote_data = $query->result_array();
        foreach ($vote_data as $key => $value) 
        {
            // 将选项转换为html字符串
            $vote_content = json_decode($value['content']);
            $vote_data[$key]['content_string'] = "";
            foreach ($vote_content as $k => $row) 
            {
                $vote_data[$key]['content_string'] .= sprintf("%s.%s<br/>", $k+1, $row);
            }
            $vote_data[$key]['code'] = md5($value['id'].$value['party_id'].$value['isSimple']);

            $this->general_mdl->setTable('party');
            $query = $this->general_mdl->get_query_by_where(array("id" => $value['party_id']));
            $vote_data[$key]['party_title'] = $query->row()->title;
        }
        $this->data['result'] = $vote_data;
        $this->load->view('admin/head');
        $this->load->view('admin_vote/list', $this->data);
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
        $this->load->view('admin_vote/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $post_data = $this->input->post(NULL,TRUE);

        // 将选项json结构化
        $post_data['content'] = json_encode((object)$post_data['content'], JSON_UNESCAPED_UNICODE);

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
        $row = $query->row_array();

        $row['content'] = json_decode($row['content']);
        $this->data['row'] = $row;

        /*取出当前用户管理的会议*/
        $user_id = $this->dx_auth->get_user_id();
        $this->general_mdl->setTable('party');
        $query = $this->general_mdl->get_query_by_where(array("user_id" => $user_id));
        $this->data['partys'] = $partys = $query->result_array();

        $party_id_array = array();
        foreach ($partys as $key => $value) 
        {
            $party_id_array[] = $value['id'];
        }

        if($row and in_array($row['party_id'], $party_id_array))
        {

            $this->load->view('admin/head');
            $this->load->view('admin_vote/edit', $this->data);
        }
        else
        {
            show_404();
        }
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
        $code = $this->input->post('code');

        $response['success'] = false;

        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $row = $query->row_array();

        $confirm_code = md5($id.$row['party_id'].$row['isSimple']);
        if($code == $confirm_code)
        {
            $this->general_mdl->delete_by_id($id);
            $response['success'] = true;
        }

        echo json_encode($response);
    }
}

/* End of file vote.php */
/* Location: ./application/controllers/vote.php */
