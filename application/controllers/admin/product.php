<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class product extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/product
     *  - or -
     *      http://example.com/index.php/product/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/product/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('product');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/product/";
    }

    public function index()
    {
        checkPermission('product_view');

        $this->load->model('attr_mdl');
        
        $product_data = array();

        $this->data['q'] = $q = $this->input->get_post('q');
        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;

        $like = array();

        if($q){
            $like['product_name'] = $q;
        }

        //查询数据的总量,计算出页数
        $query = $this->general_mdl->get_query_or_like();
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $query = $this->general_mdl->get_query_or_like($like, array(), $start-1, $pageSize);
        $product_data = $query->result_array();
        $this->data['current_page'] = $start;
        

        foreach ($product_data as $key => $value) {
            $this->general_mdl->setTable('small_class');
            $query = $this->general_mdl->get_query_by_where(array('id' => $value['small_class_id']));
            $product_data[$key]['small_class'] = $query->row()->small_class_name;

            $product_data[$key]['color'] = $this->attr_mdl->get_attr($value['id'], 0);
            $product_data[$key]['size'] = $this->attr_mdl->get_attr($value['id'], 1);
        }

        $prev_link = $this->data['controller_url'].'?page='.($start-1);
        $prev_link .= $q ? '&q='.$q : '';

        $next_link = $this->data['controller_url'].'?page='.($start+1);
        $next_link .= $q ? '&q='.$q : '';

        $this->data['prev_link'] = $prev_link;
        $this->data['next_link'] = $next_link;

        $page_link = array();
        for ($i=1; $i <= $page; $i++){
            $page_link[$i] = $this->data['controller_url'].'?page='.$i;
            $page_link[$i] .= $q ? '&q='.$q : '';
        }
        $this->data['page_links'] = $page_link;

        $this->data['title'] = '产品管理';
        $this->data['result'] = $product_data;

        $this->load->view('admin_product/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->general_mdl->setTable('small_class');
        $this->data['small_classes'] = $this->general_mdl->get_query()->result_array();

        $this->load->view('admin_product/add', $this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $color = $data['color'];
        $size = $data['size'];
        unset($data['color']);
        unset($data['size']);

        $this->general_mdl->setData($data);
        if($product_id = $this->general_mdl->create())
        {
            $this->general_mdl->setTable('attribute_values');
            $color_arr = explode(',', $color);
            $size_arr = explode(',', $size);

            $color_data['product_id'] = $size_data['product_id'] = $product_id;
            foreach ($color_arr as $key => $value) {
                $color_data['attribute_type'] = 0;
                $color_data['values'] = $value;
                $this->general_mdl->setData($color_data);
                $this->general_mdl->create();
            }
            foreach ($size_arr as $key => $value) {
                $size_data['attribute_type'] = 1;
                $size_data['values'] = $value;
                $this->general_mdl->setData($size_data);
                $this->general_mdl->create();
            }

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

        //分类
        $this->general_mdl->setTable('small_class');
        $this->data['small_classes'] = $this->general_mdl->get_query()->result_array();

        $this->load->model('attr_mdl');

        //关联颜色
        $row['color'] = $this->attr_mdl->get_attr($row['id'], 0);

        //关联尺码
        $row['size'] = $this->attr_mdl->get_attr($row['id'], 1);

        $this->data['row'] = $row;

        $this->load->view('admin_product/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $data = $this->input->post(NULL, TRUE);
        
        $id = $data['id'];
        $color = $data['color'];
        $size = $data['size'];

        unset($data['id']);
        unset($data['color']);
        unset($data['size']);

        $this->general_mdl->setData($data);
        $isUpdated = $this->general_mdl->update(array('id'=>$id));

        if($isUpdated){
            $response['status'] = "y";
            $response['info'] = "修改成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "修改完成";
        }

        $this->load->model('attr_mdl');

        $this->general_mdl->setTable('attribute_values');
        $color_data['product_id'] = $size_data['product_id'] = $id;

        $new_color_arr = explode(',', $color);
        $new_size_arr = explode(',', $size);
        //已有颜色 已有尺码
        $old_color_arr = $this->attr_mdl->get_attr($id, 0, 'array');
        $old_size_arr = $this->attr_mdl->get_attr($id, 1, 'array');

        foreach ($new_color_arr as $key => $value) {
            //添加新颜色属性
            if(!in_array($value, $old_color_arr)){
                $color_data['attribute_type'] = 0;
                $color_data['values'] = $value;
                $this->general_mdl->setData($color_data);
                $this->general_mdl->create();
            }
        }       

        foreach ($old_color_arr as $key => $value) {
            //删除旧颜色属性
            if(!in_array($value, $new_color_arr)){
                $where = array('product_id' => $id, 'values' => $value, 'attribute_type' => 0);
                $this->general_mdl->delete($where);
            }
        }

        foreach ($new_size_arr as $key => $value) {
            //添加新尺码属性
            if(!in_array($value, $old_size_arr)){
                $size_data['attribute_type'] = 1;
                $size_data['values'] = $value;
                $this->general_mdl->setData($size_data);
                $this->general_mdl->create();
            }
        }       

        foreach ($old_size_arr as $key => $value) {
            //删除旧尺码属性
            if(!in_array($value, $new_size_arr)){
                $where = array('product_id' => $id, 'values' => $value, 'attribute_type' => 1);
                $this->general_mdl->delete($where);
            }
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

/* End of file product.php */
/* Location: ./application/controllers/product.php */
