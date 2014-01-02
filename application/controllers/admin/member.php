<?php
class Member extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		checkIsLoggedIn();

        $this->load->model('tank_auth/users', 'users');
        $this->load->model('members_mdl');

        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');

        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('form');
	}

    public function index()
    {
        checkPermission('member_view');
        $this->members();
    }

    //会员列表
    public function members()
    {
        checkPermission('member_view');

        $where = array();
        $start = $this->uri->segment(4);
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : 10;

        $members = $this->members_mdl->members($where, $start, $limit);

        $config['base_url'] = site_url('admin/member/members');
        $config['total_rows'] = $members['total'];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['page_links'] = $this->pagination->create_links();

        $data['members'] = $members['result'];

        $this->load->view('admin/head', $data);
        $this->load->view('member/list');
    }


    //添加用户
    public function add()
    {

        $query = $this->insurance_mdl->get_query();
        $data['insurances'] = $query->result();

        $this->load->view('member/add', $data);
    }

    //保存添加用户
    public function add_save()
    {
        $data['success'] = FALSE;

        $profile = $this->input->post('profile');

        $this->form_validation->set_rules('username', '用户名', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');

        $this->form_validation->set_rules('password', '密码', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
        $this->form_validation->set_rules('confirm_password', '确认密码', 'trim|required|xss_clean|matches[password]');
        $this->form_validation->set_rules('profile[tel]', '联系电话', 'trim|required|xss_clean');
        $this->form_validation->set_rules('profile[address]', '联系地址', 'trim|required|xss_clean');

        $email_activation = $this->config->item('email_activation', 'tank_auth');

        if ($this->form_validation->run()) {                                // validation ok
            if (!is_null($data = $this->tank_auth->create_user(
                    $this->form_validation->set_value('username'),
                    '',
                    $this->form_validation->set_value('password'),
                    $email_activation)))
            {                                  // success
                $data['success'] = TRUE;
                unset($data['password']); // Clear password (just for any case)

                //保存用户profile

                //序列化保险数据
                if(isset($profile['give_insurance']))
                {
                    $profile['give_insurance'] = serialize($profile['give_insurance']);
                }

                $this->general_mdl->setTable('user_profiles');
                $this->general_mdl->setData($profile);
                $this->general_mdl->update(array("user_id" => $data['user_id']));
            } else {
                $errors = $this->tank_auth->get_error_message();
                foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
            }
        }else{
            $data['errors']['username'] = form_error('username');
            $data['errors']['password'] = form_error('password');
            $data['errors']['confirm_password'] = form_error('confirm_password');
            $data['errors']['tel'] = form_error('profile[tel]');
            $data['errors']['address'] = form_error('profile[address]');
        }

        echo json_encode($data);
    }


    //用户信息修改
    public function edit()
    {
        $query = $this->insurance_mdl->get_query();
        $data['insurances'] = $query->result();

        $user_id = $this->input->post('user_id');

        $data['user_id'] = $user_id;
        $data['member'] = $this->users->get_user_by_id($user_id, TRUE);

        $this->general_mdl->setTable('user_profiles');
        $data['profile'] = $this->general_mdl->get_query_by_where(array('user_id' => $user_id))->row();
        $data['profile']->give_insurance = (array)unserialize($data['profile']->give_insurance);

        $this->load->view('member/edit', $data);
    }

    //用户信息修改
    public function edit_save()
    {
        $data['success'] = FALSE;

        $user_id = $this->input->post('user_id');
        $old_username = $this->input->post('old_username');
        $username = $this->input->post('username');
        $profile = $this->input->post('profile');

        //检查用户名是否有修改
        if($username !== $old_username)
        {
            $this->form_validation->set_rules('username', '用户名', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
        }

        $this->form_validation->set_rules('profile[tel]', '联系电话', 'trim|required|xss_clean');
        $this->form_validation->set_rules('profile[address]', '联系地址', 'trim|required|xss_clean');

        if ($this->form_validation->run())
        {
            //用户名有修改
            if($username !== $old_username AND $this->tank_auth->is_username_available($username))
            {
                $this->general_mdl->setTable('users');
                $this->general_mdl->setData(array('username' => $username));
                $this->general_mdl->update(array('id' => $user_id));
            }else{
                $data['errors']['username'] = "用名不可用";
            }

            //保存用户profile

            if(isset($profile['give_insurance']))
            {
                //序列化保险数据
                $profile['give_insurance'] = serialize($profile['give_insurance']);
            }else{
                $profile['give_insurance'] = "";
            }

            $this->general_mdl->setTable('user_profiles');
            $this->general_mdl->setData($profile);
            $this->general_mdl->update(array("user_id" => $user_id));

            $data['success'] = TRUE;
        }
        else
        {
            $data['errors']['username'] = form_error('username');
            $data['errors']['tel'] = form_error('profile[tel]');
            $data['errors']['address'] = form_error('profile[address]');
        }

        echo json_encode($data);
    }

    //重置密码
    public function reset_password()
    {
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
            $this->load->view('member/reset_password', $data);
        }

    }

    //检查用户名
    public function username_check()
	{
        $username = $this->input->post('username');
		$data['result'] = $this->users->is_username_available($username);
		echo json_encode($data);
	}

    public function del()
    {
        $data['success'] = false;
        $user_id = $this->input->post('id');

        $this->users->delete_user($user_id);

        $data['success'] = true;
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
