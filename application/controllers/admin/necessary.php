<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class necessary extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/necessary
     *  - or -
     *      http://example.com/index.php/necessary/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/necessary/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('necessities_scheme');

        $this->data['controller_url'] = "admin/necessary/";
    }

    public function index()
    {
        checkPermission('necessary_view');

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
        
        $this->data['title'] = '必需品方案管理';
        $this->data['result'] = $necessary_data;

        $this->load->view('admin_necessary/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_necessary/add', $this->data);
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

        $this->load->view('admin_necessary/edit', $this->data);
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

    public function necessaries_edit()
    {
        $id = $this->data['id'] = $this->input->get('id');
        $product_ids = $this->data['product_ids'] = $this->input->get('product_id');

        $row = $this->general_mdl->get_query_by_where(array('id' => $id))->row_array();
        $this->data['row'] = $row;

        $this->general_mdl->setTable('product');
        if($product_ids){
            $products = $this->general_mdl->get_query_by_where_in('id', $product_ids)->result_array();
            foreach ($products as $key => $value) {
                $this->general_mdl->setTable('attribute_values');
                $attrs_arr = $this->general_mdl->get_query_by_where(array('attribute_type' => 0, 'product_id' => $value['id']))->result_array();
                foreach ($attrs_arr as $k => $v) {
                    $this->general_mdl->setTable('necessities');
                    $row = $this->general_mdl->get_query_by_where(array('necessities_schem_id' => $id, 'attribute_values_id' => $v['id'], 'product_id' => $v['product_id']))->row_array();
                    $attrs_arr[$k]['necessities_id'] = $row ? $row['id'] : '';
                    $attrs_arr[$k]['MQP'] = $row ? $row['MQP'] : '';                   
                }
                $products[$key]['attrs'] = $attrs_arr;
            }
        }else{
            $products = $this->general_mdl->get_query()->result_array();
            $this->general_mdl->setTable('necessities');
            foreach ($products as $key => $value) {
                $query = $this->general_mdl->get_query_by_where(array('necessities_schem_id' => $id, 'product_id' => $value['id']));
                $products[$key]['selected'] = $query->num_rows() ? "selected" : "";
            }            
        }

        $this->data['products'] =  $products;

        $this->load->view('admin_necessary/necessaries_edit', $this->data);
    }

    public function necessaries_edit_save()
    {
        $id = $this->input->post('id');
        $colors = $this->input->post('color');
        $necessities_ids = $this->input->post('necessities_ids');

        $response['status'] = "n";
        $response['info'] = "保存失败";

        $this->general_mdl->setTable('necessities');
        $base_data = array('necessities_schem_id' => $id);
        // 创建
        if($colors){        
            foreach ($colors as $product_id => $item) {
                foreach ($item as $color_id => $mqp) {
                    if($mqp != 0){                
                        $base_data['product_id'] = $product_id;
                        $base_data['attribute_values_id'] = $color_id;
                        $base_data['MQP'] = $mqp;
                        $this->general_mdl->setData($base_data);
                        $this->general_mdl->create();
                    }
                }
            }
        }
        // 如果是已有的就更新
        if($necessities_ids){  
            foreach ($necessities_ids as $necessities_id => $mqp) {
                if($mqp != 0){
                    $data2['MQP'] = $mqp;
                    $this->general_mdl->setData($data2);
                    $this->general_mdl->update(array('id' => $necessities_id));
                }else{
                    $this->general_mdl->delete_by_id($necessities_id);
                }
            }
        }

        $response['status'] = "y";
        $response['info'] = "保存成功";

        echo json_encode($response);
    }
}

/* End of file necessary.php */
/* Location: ./application/controllers/necessary.php */
