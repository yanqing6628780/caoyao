<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class billboard extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');

        check_front_IsLoggedIn();
    }

	public function index()
	{
        $this->load->model('order_mdl');

        $exchange_fair_id = $this->session->userdata('current_exchange_fair');
        $data['billboard'] = $this->order_mdl->product_sales_ranking($exchange_fair_id);
        
		$this->load->view('front/billboard', $data);
	}

}
