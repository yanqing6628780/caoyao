<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dish extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/dish
     *  - or -
     *      http://example.com/index.php/dish/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/dish/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('cy_bt_dish');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/dish/";
    }

    public function index()
    {
        checkPermission('coupon_view');

        $dish_data = array();

        $query = $this->general_mdl->get_query_by_where(array("user_id" => $this->dx_auth->get_user_id()));

        $this->data['total'] = $query->num_rows();
        $dish_data = $query->result_array();
        
        $this->general_mdl->setTable('dish_photo');
        foreach ($dish_data as $key => $value) {
            $query = $this->general_mdl->get_query_by_where( array('dishno' => $value['ch_dishno']) );
            $dish_data[$key]['photo'] = "";
            if($query->num_rows()>0){
                $dish_data[$key]['photo'] = $query->row()->photo;
            }
        }

        $this->data['result'] = $dish_data;
        $this->data['title'] = '菜式管理';

        $this->load->view('admin/head');
        $this->load->view('admin_dish/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_dish/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);

        $this->general_mdl->setData($data);
        if($dishType_id = $this->general_mdl->create())
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

        $this->load->view('admin_dish/edit', $this->data);
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

    //保存
    public function photo_save()
    {
        $post_data = $this->input->post(NULL, TRUE);
        $where = array('dishno' => $post_data['dishno'], 'user_id' => $post_data['user_id']);
        $this->general_mdl->setTable('dish_photo');
        $query = $this->general_mdl->get_query_by_where( $where );
        $this->general_mdl->setData($post_data);
        $isUpdated = FALSE;
        if($query->num_rows()>0){
            //删除旧图片
            $row = $query->row();
            if( is_file($row->photo) ){
                unlink($row->photo);
            }
            //更新数据
            $isUpdated = $this->general_mdl->update($where);
        }else{
            $this->general_mdl->create();
        }


        if($isUpdated){
            $response['status'] = "y";
            $response['info'] = "修改成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "添加完成";
        }

        echo json_encode($response);
    }

    //删除
    public function del()
    {
        $id = $this->input->post('id');
        $code = $this->input->post('code');

        $response['success'] = false;

        if($id != 1){        
            $this->general_mdl->delete_by_id($id);
            $response['success'] = true;
        }

        echo json_encode($response);
    }

}

/* End of file dish.php */
/* Location: ./application/controllers/dish.php */
