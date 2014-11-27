<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class small_class_limit extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/small_class_limit
     *  - or -
     *      http://example.com/index.php/small_class_limit/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/small_class_limit/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('small_class_restrictions');

        $this->data['controller_url'] = "admin/small_class_limit/";
    }

    public function index()
    {
        checkPermission('scheme_view');

        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;

        $necessary_data = array();


        //查询数据的总量,计算出页数
        $query = $this->general_mdl->get_query();
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $query = $this->general_mdl->get_query(($start-1), $pageSize);
        $necessary_data = $query->result_array();
        $this->data['current_page'] = $start;
        
        $this->data['title'] = '方案管理';
        $this->data['result'] = $necessary_data;

        $this->load->view('admin_small_class_limit/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_small_class_limit/add', $this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);

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

        $this->load->view('admin_small_class_limit/edit', $this->data);
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

    public function scheme_edit()
    {
        $id = $this->data['id'] = $this->input->get('id');
        $ids = $this->data['ids'] = $this->input->get('class_id');

        $row = $this->general_mdl->get_query_by_where(array('id' => $id))->row_array();
        $this->data['row'] = $row;

        $this->general_mdl->setTable('small_class');
        if($ids){
            $small_classes = $this->general_mdl->get_query_by_where_in('id', $ids)->result_array();
            $this->general_mdl->setTable('small_class_limits');
            foreach ($small_classes as $key => $value) {
                $query = $this->general_mdl->get_query_by_where(array('small_class_restrictions_id' => $id, 'small_class_id' => $value['id']));
                $small_classes[$key]['percentage'] = $query->num_rows() ? $query->row()->percentage : "";
                $small_classes[$key]['scheme_id'] = $query->num_rows() ? $query->row()->id : "";
            }
        }else{
            $small_classes = $this->general_mdl->get_query()->result_array();
            $this->general_mdl->setTable('small_class_limits');
            foreach ($small_classes as $key => $value) {
                $query = $this->general_mdl->get_query_by_where(array('small_class_restrictions_id' => $id, 'small_class_id' => $value['id']));
                $small_classes[$key]['selected'] = $query->num_rows() ? "selected" : "";
            }            
        }

        $this->data['small_classes'] =  $small_classes;

        $this->load->view('admin_small_class_limit/scheme_edit', $this->data);
    }

    public function scheme_edit_save()
    {
        $id = $this->input->post('id');
        $small_class = $this->input->post('small_class');
        $scheme_ids = $this->input->post('scheme_ids');

        $response['status'] = "n";
        $response['info'] = "保存失败";

        $this->general_mdl->setTable('small_class_limits');
        $base_data = array('small_class_restrictions_id' => $id);
        // 创建
        if($small_class){        
            foreach ($small_class as $small_class_id => $mqp) {
                if($mqp != 0){                
                    $base_data['small_class_id'] = $small_class_id;
                    $base_data['percentage'] = $mqp;
                    $this->general_mdl->setData($base_data);
                    $this->general_mdl->create();
                }
            }
        }
        // 如果是已有的就更新
        if($scheme_ids){  
            foreach ($scheme_ids as $id => $mqp) {
                if($mqp != 0){
                    $data2['percentage'] = $mqp;
                    $this->general_mdl->setData($data2);
                    $this->general_mdl->update(array('id' => $id));
                }else{
                    $this->general_mdl->delete_by_id($id);
                }
            }
        }

        $response['status'] = "y";
        $response['info'] = "保存成功";

        echo json_encode($response);
    }
}

/* End of file small_class_limit.php */
/* Location: ./application/controllers/small_class_limit.php */
