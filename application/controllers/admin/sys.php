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
        $data['system'] = $this->general_mdl->get_query()->row();
        $data['title'] = '';
        $this->load->view('admin/sys_config', $data);

	}

    //修改保存
    public function config_save()
    {
        $data = $this->input->post(NULL, TRUE);

        foreach ($data as $key => $value) {
            $this->general_mdl->setData( array('value' => $value) );
            $isUpdated[] = $this->general_mdl->update( array('name' => $key) );
        }

        $response['info'] = $isUpdated;

        echo json_encode($response);
    }
}

/* End of file sys.php */
/* Location: ./application/controllers/sys.php */
