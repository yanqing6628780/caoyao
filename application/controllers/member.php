<?php
class Member extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

        $this->load->model('tank_auth/users', 'users');
        $this->load->model('members_mdl');

        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');

        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('form');

        if (!$this->tank_auth->is_logged_in()) {
            redirect('/login/');
        }
	}

    public function index()
    {
        die("非法进入");
    }

    //重置密码
    public function reset_password()
    {
        $data['success'] = FALSE;
        $username = $this->tank_auth->get_username();

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
            $this->load->view('front/memeber_reset_password.php', $data);
        }

    }



    //检查用户名
    public function username_check()
	{
        $username = $this->input->post('username');
		$data['result'] = $this->users->is_username_available($username);
		echo json_encode($data);
	}

}
?>
