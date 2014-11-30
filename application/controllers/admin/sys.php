<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys extends CI_Controller {

    private $_data;

    function __construct()
    {
        parent::__construct();

        checkIsLoggedIn();

        $this->general_mdl->setTable('sys_config');
    }

	public function index()
	{
        checkRoles('admin');
        checkPermission('sys_admin');
        $this->_data['system'] = $this->general_mdl->get_query()->row();

        $this->load->view('system', $this->_data);

	}

    public function save()
    {
        $tel_point = $this->input->post('tel_point');
        $shopex_url = $this->input->post('shopex_url');
        $array = array('tel_point' => $tel_point);
        $array = array('shopex_url' => $shopex_url);
        $where['id'] =  1;
        $this->general_mdl->setData($array);
        $this->general_mdl->update($where);

        $data['success'] = true;
        $data['msg'] = '更新成功';
        echo json_encode($data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
