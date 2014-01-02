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
    }

    public function index()
    {
        $query = $this->general_mdl->get_query();
        $data['result'] = $query->result();
        $data['total'] = $query->num_rows();
        $this->load->view('ad_party/main', $data);
    }

    //评价添加
    public function add()
    {
        $this->load->view('ad_party/add');
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post('data');

        $data['created'] = date("Y-m-d H:i:s");
        $this->general_mdl->setData($data);
        if($this->general_mdl->create()){
            $data['success'] = true;
        }else{
            $data['success'] = false;
        }
        echo json_encode($data);
    }

    //修改
    public function edit()
    {
        $data['id'] = $this->input->post('id');

        $query = $this->general_mdl->get_query_by_where(array('id' => $data['id']));
        $data['row'] = $query->row_array();

        $this->load->view('ad_party/edit', $data);
    }

    //修改保存
    public function edit_save()
    {
        $id = $this->input->post('id');
        $data = $this->input->post('data');

        $this->general_mdl->setData($data);
        $where = array('id'=>$id);
        $this->general_mdl->update($where);

        $data['success'] = true;
        echo json_encode($data);
    }

    //删除
    public function del()
    {
        $id = $this->input->post('id');

        $this->general_mdl->delete_by_id($id);
        $data['success'] = true;

        echo json_encode($data);
    }
}

/* End of file party.php */
/* Location: ./application/controllers/party.php */
