<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class category extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/category
     *  - or -
     *      http://example.com/index.php/category/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/category/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('big_class');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/category/";
    }

    public function index()
    {
        checkPermission('category_view');

        $category_data = array();
        $party_id_array = array();

        $query = $this->general_mdl->get_query();

        $this->data['total'] = $query->num_rows();
        $category_data = $query->result_array();
        
        $this->data['result'] = $category_data;
        $this->data['title'] = '分类管理';

        $this->load->view('admin_category/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_category/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);

        if ($this->db->table_exists($data['table'])) {
            $response['status'] = "n";
            $response['info'] = "添加失败,表名已存在";
            echo json_encode($response);
            exit();
        }

        $this->general_mdl->setData($data);
        if($categoryType_id = $this->general_mdl->create())
        {
            //创建表
            $this->load->dbforge();
            $fields = array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'type' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'province' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'city' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'district' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'company' => array(
                    'type' => 'TEXT',
                ),
                'address' => array(
                    'type' => 'TEXT',
                ),
                'zipcode' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'citycode' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'tel' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'fax' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'duties' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'mobile' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'qq' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '64',
                ),
                'website' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '64',
                ),
                'actingbrand' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                ),
                'remark' => array(
                    'type' => 'text'
                ),
                'time_limit' => array(
                    'type' => 'int'
                ),
                'price' => array(
                    'type' => 'float',
                    'default' => 0
                )
            );

            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $info = $this->dbforge->create_table($data['table'], TRUE);
            $response['status'] = "y";
            $response['info'] = "添加成功,".$info;           
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

        $this->load->view('admin_category/edit', $this->data);
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
        $table = $this->input->post('code');

        $response['success'] = false;

        $this->load->dbforge();
        $this->dbforge->drop_table($table);

        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;
        
        echo json_encode($response);
    }

    public function check(){
        $table = $this->input->post('param');
        $query = $this->general_mdl->get_query_by_where(array('table'=>$table));
        if($query->num_rows()==0){
            if ($this->db->table_exists($table)){
                $data['status'] = "n";
                $data['info'] = "表名已存在";            
            }else{            
                $data['status'] = "y";
                $data['info'] = "表名可以使用";
            }
        }else{
            $data['status'] = "n";
            $data['info'] = "表名已存在";            
        }
        echo json_encode($data);
    }
}

/* End of file category.php */
/* Location: ./application/controllers/category.php */
