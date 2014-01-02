<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        checkIsLoggedIn();
        $this->load->model('dx_auth/user_Profile', 'profile');
    }

	public function index()
	{
        $nickname = $this->profile->get_profile($this->dx_auth->get_user_id())->row()->name;
        $nickname = $nickname ? $nickname : '佚名';
        $data['title'] = '龙山客运站';
        $data['header_welcome_msg'] = sprintf("欢迎你, %s(%s)", $this->dx_auth->get_username(), $nickname);
        $this->load->view('admin/head', $data);
        $this->load->view('admin/home');
	}

}
