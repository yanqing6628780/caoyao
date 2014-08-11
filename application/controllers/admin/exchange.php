<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class exchange extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/exchange
     *  - or -
     *      http://example.com/index.php/exchange/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/exchange/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('exchange_fair');

        $this->data['controller_url'] = "admin/exchange/";
    }

    public function index()
    {
        checkPermission('exchange_view');

        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;

        $exchange_data = array();


        //查询数据的总量,计算出页数
        $query = $this->general_mdl->get_query();
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $query = $this->general_mdl->get_query(($start-1), $pageSize);
        $exchange_data = $query->result_array();
        $this->data['current_page'] = $start;
        
        $this->data['title'] = '订货会管理';
        $this->data['result'] = $exchange_data;

        $this->load->view('admin_exchange/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_exchange/add', $this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $data['create_time'] = date("Y-m-d H:i:s");

        $this->general_mdl->setData($data);
        if($this->general_mdl->create())
        {
            $response['status'] = "y";
            $response['info'] = "添加成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "添加失败";
        }

        echo json_encode($response);
    }

    //修改
    public function edit()
    {
        $this->data['id'] = $this->input->post('id');

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $row = $query->row_array();

        $this->data['row'] = $row;

        $this->load->view('admin_exchange/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $where = array('id'=>$data['id']);
        unset($data['id']);

        $this->general_mdl->setData($data);
        $isUpdated = $this->general_mdl->update($where);

        if($isUpdated){
            $response['status'] = "y";
            $response['info'] = "修改成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "修改完成";
        }

        echo json_encode($response);
    }

    //删除
    public function del()
    {
        $id = $this->input->post('id');
        $code = $this->input->post('code');

        $response['success'] = false;
 
        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;

        echo json_encode($response);
    }

}

/* End of file exchange.php */
/* Location: ./application/controllers/exchange.php */
