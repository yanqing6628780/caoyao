<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->load->library('logger');

        check_front_IsLoggedIn();

        $this->load->model('tank_auth/profiles_mdl', 'profiles_mdl');
    }

	public function index()
	{
        $this->general_mdl->setTable('small_class');
        $data['small_classes'] = $this->general_mdl->get_query()->result_array();

		$this->load->view('front/head');
		$this->load->view('front/home', $data);
	}

    public function show_error($code=404, $head='找不到页面' ,$msg='')
    {
        $data['title'] = "错误信息";
        $data['code'] = $code;
        $data['head'] = $head;
        $data['msg'] = $msg;
        $this->load->view('front/head');
        $this->load->view('front/error', $data);
        die();
    }

}
