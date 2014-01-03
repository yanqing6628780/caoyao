<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Party extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/party
     *  - or -
     *      http://example.com/index.php/party/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/party/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('party');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');
        
        $this->data['controller_url'] = "admin/party/";
    }

    public function index()
    {
        /*会议数据*/
        $query = $this->general_mdl->get_query();
        $party_data = $query->result_array();
        $this->data['total'] = $query->num_rows();

        /*关联用户名*/
        foreach($party_data as $key => $row)
        {
            $row_profile = $this->profile->get_profile($row['user_id'])->row();
            $row_user = $this->users->get_user_by_id($row['user_id'])->row();

            $party_data[$key]['name'] = $row_profile->name ? $row_profile->name : $row_user->username;
        }

        $this->data['result'] = $party_data;

        $this->load->view('admin/head');
        $this->load->view('admin_party/list', $this->data);
    }

    //评价添加
    public function add()
    {        

        $this->general_mdl->setTable('admin_users');
        $query = $this->general_mdl->get_query();
        $users = $query->result_array();

        foreach($users as $key => $row){
            $row_profile = $this->profile->get_profile($row['id'])->row();

            $users[$key]['cnname'] = $row_profile->name;
            $users[$key]['mobile'] = $row_profile->mobile;
            $users[$key]['sex'] = $row_profile->sex;
            $users[$key]['email'] = $row_profile->email;
            $users[$key]['photo'] = $row_profile->photo;

        }

        $this->data['users'] = $users;
        $this->load->view('admin/head');
        $this->load->view('admin_party/add',$this->data);
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
        $params = $this->uri->uri_to_assoc();
        $this->data['id'] = $params['edit'];

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $this->data['row'] = $query->row_array();

        $this->general_mdl->setTable('admin_users');
        $query = $this->general_mdl->get_query();
        $users = $query->result_array();

        foreach($users as $key => $row){
            $row_profile = $this->profile->get_profile($row['id'])->row();

            $users[$key]['cnname'] = $row_profile->name;
            $users[$key]['mobile'] = $row_profile->mobile;
            $users[$key]['sex'] = $row_profile->sex;
            $users[$key]['email'] = $row_profile->email;
            $users[$key]['photo'] = $row_profile->photo;
        }

        $this->data['users'] = $users;

        $this->load->view('admin/head');
        $this->load->view('admin_party/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $id = $this->input->post('id');
        $post_data = $this->input->post(NULL, TRUE);
        unset($post_data['id']);

        $post_data['isDiscussion'] = isset($post_data['isDiscussion']) ? $post_data['isDiscussion'] : 0;
        $post_data['isVote'] = isset($post_data['isVote']) ? $post_data['isVote'] : 0;
        $post_data['isLottery'] = isset($post_data['isLottery']) ? $post_data['isLottery'] : 0;

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

/* End of file party.php */
/* Location: ./application/controllers/party.php */
