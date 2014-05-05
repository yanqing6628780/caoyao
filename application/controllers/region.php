<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class region extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/region
     *  - or -
     *      http://example.com/index.php/region/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/region/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
    }

    public function index()
    {
        $local_name = $this->input->get_post('local_name');
        $data['selected'] = $this->input->get_post('selected');
        $where = array();
        $this->general_mdl->setTable('regions');
        if($local_name){
            $where['local_name'] = $local_name;
            $row = $this->general_mdl->get_query_by_where($where)->row();

            $where = array('p_region_id' => $row->region_id);
            $query = $this->general_mdl->get_query_by_where($where);
        }else{
            $where['region_grade'] = 1;
            $query = $this->general_mdl->get_query_by_where($where);
        }

        $data['regions'] = $query->result();

        $this->load->view('region/list', $data);
    }
}

/* End of file region.php */
/* Location: ./application/controllers/region.php */
