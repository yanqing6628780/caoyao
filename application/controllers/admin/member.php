<?php
class Member extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		checkIsLoggedIn();

        $this->load->model('tank_auth/users', 'users');

        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');

        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('form');
	}

    public function index()
    {
        checkPermission('member_view');

        $this->general_mdl->setTable('users');
        $query = $this->general_mdl->get_query();
        $result = $query->result_array();

        foreach($result as $key => $row)
        {
            $this->general_mdl->setTable('user_profiles');
            $row_profile = $this->general_mdl->get_query_by_where(array("user_id" =>$row['id']))->row();

            $result[$key]['cnname'] = $row_profile->name;
            $result[$key]['mobile'] = $row_profile->mobile;
            $result[$key]['sex'] = $row_profile->sex;
            $result[$key]['company'] = $row_profile->company;
            $result[$key]['department'] = $row_profile->department;
            $result[$key]['jobs'] = $row_profile->jobs;
            $result[$key]['job_title'] = $row_profile->job_title;
            $result[$key]['photo'] = $row_profile->photo ? $row_profile->photo : base_url()."images/tavatar.gif";
            $result[$key]['code'] = md5($row['id'].$row['password']);
        }

        $data['users'] = $result;

        $this->load->view('admin_member/list', $data);
    }


    //添加用户
    public function add()
    {
        checkPermission('member_edit');
        $this->general_mdl->setTable('users');
        $query = $this->general_mdl->get_query();
        $data['num_rows'] = $query->num_rows();

        $this->load->view('admin_member/add', $data);
    }

    //保存添加用户
    public function add_save()
    {
        $data['status'] = "n";
        $data['info'] = "";

        $profile = $this->input->get_post('profile');

        $this->form_validation->set_rules('username', '用户名', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash|callback__username_check');

        $this->form_validation->set_rules('password', '密码', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
        $this->form_validation->set_rules('confirm_password', '确认密码', 'trim|required|xss_clean|matches[password]');

        $email_activation = $this->config->item('email_activation', 'tank_auth');

        if ($this->form_validation->run()) // validation ok
        { 
            if (!is_null($data = $this->tank_auth->create_user(
                    $this->form_validation->set_value('username'),
                    '',
                    $this->form_validation->set_value('password'),
                    $email_activation)))
            {                                  // success
                $data['status'] = "y";
                $data['info'] = "添加成功";
                unset($data['password']); // Clear password (just for any case)

                //保存用户profile
                $this->general_mdl->setTable('user_profiles');
                $this->general_mdl->setData($profile);
                $this->general_mdl->update(array("user_id" => $data['user_id']));
            } else {
                $errors = $this->tank_auth->get_error_message();
                foreach ($errors as $k => $v)   $data['info'] .= $this->lang->line($v)."/";
            }
        }else{
            $data['info'] .= form_error('username') ? form_error('username')."/" : "";
            $data['info'] .= form_error('password') ? form_error('password')."/" : "";
            $data['info'] .= form_error('confirm_password') ? form_error('confirm_password')."/" : "";
        }

        echo json_encode($data);
    }


    //用户信息修改
    public function edit()
    {
        checkPermission('member_edit');

        $user_id = $this->input->post('user_id');

        $data['user_id'] = $user_id;
        $data['member'] = $this->users->get_user_by_id($user_id, TRUE);

        $this->general_mdl->setTable('user_profiles');
        $data['profile'] = $this->general_mdl->get_query_by_where(array('user_id' => $user_id))->row();

        $this->load->view('admin_member/edit', $data);
    }

    //用户信息修改
    public function edit_save()
    {
        checkPermission('member_edit');

        $data['status'] = "n";

        $user_id = $this->input->post('user_id');
        $username = $this->input->post('username');
        $profile = $this->input->post('profile');

        //保存用户profile
        $this->general_mdl->setTable('user_profiles');
        $this->general_mdl->setData($profile);
        $this->general_mdl->update(array("user_id" => $user_id));

        $data['status'] = "y";
        $data['info'] = "修改成功";

        echo json_encode($data);
    }

    //重置密码
    public function reset_password()
    {
        checkPermission('member_edit');

        $data['success'] = FALSE;
        $data['username'] = $username = $this->input->post('username');

        if($this->input->post('password'))
        {
            $this->form_validation->set_rules('password', '密码', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
            $this->form_validation->set_rules('confirm_password', '确认密码', 'trim|required|xss_clean|matches[password]');

            if ($this->form_validation->run())
            {
                $forgotten_data = $this->tank_auth->forgot_password($username);
                if(!is_null($forgotten_data))
                {
                    $reset_data = $this->tank_auth->reset_password($forgotten_data['user_id'], $forgotten_data['new_pass_key'], $this->form_validation->set_value('password'));

                    if(!is_null($reset_data))
                    {
                        $data['success'] = TRUE;
                    }

                }else{
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                }
            }
            else
            {
                $data['errors']['password'] = form_error('password');
                $data['errors']['confirm_password'] = form_error('confirm_password');
            }

            echo json_encode($data);
        }
        else
        {
            $this->load->view('admin_member/reset_password', $data);
        }

    }

    function _username_check($username)
    {
        $result = $this->tank_auth->is_username_available($username);
        if ( ! $result)
        {
            $this->form_validation->set_message('_username_check', '用户名已存在。请重新填写用户名');
        }
                
        return $result;
    }

    //检查用户名
    public function username_check()
	{
        $username = $this->input->post('param');
		$result = $this->users->is_username_available($username);
        if($result){
            $data['status'] = "y";
            $data['info'] = "用户名可用";
        }else{
            $data['status'] = "n";
            $data['info'] = "用户名已存在";

        }
		echo json_encode($data);
	}

    public function del()
    {
        checkPermission('user_edit');

        $user_id = $this->input->post('id');
        $code = $this->input->post('code');

        $response['success'] = false;

        $row = $this->users->get_user_by_id($user_id,1);

        $confirm_code = md5($user_id.$row->password);
        if($code == $confirm_code)
        {
            $this->users->delete_user($user_id);
            $response['success'] = true;
        }

        echo json_encode($response);

    }


    public function ban()
    {
        $data['success'] = false;
        $user_id = $this->input->post('id');
        $reason = $this->input->post('reason') ? $this->input->post('reason') : "禁用帐户并非删除";

        $this->users->ban_user($user_id, $reason);

        $data['success'] = true;
    }
}
?>
