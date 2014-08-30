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
        if($exchange_fair_id = $this->general_mdl->create())
        {
            /*会员数据*/
            $this->general_mdl->setTable('users');
            $query = $this->general_mdl->get_query();
            $members = $query->result_array();

            $this->general_mdl->setTable("rel_stores_exchange_fair");
            foreach ($members as $key => $value) {
                $rel_data['exchange_fair_id'] = $exchange_fair_id;
                $rel_data['user_id'] = $value['id'];
                $this->general_mdl->setData($rel_data);
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

    //参会人员设置
    public function set_members()
    {
        $id = $this->input->get('id');
        
        /*订货会资料*/
        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $this->data['row'] = $query->row_array();

        /*订货会会参与人*/
        $this->data['user_ids'] = array();
        $this->general_mdl->setTable("rel_stores_exchange_fair");
        $query = $this->general_mdl->get_query_by_where(array("exchange_fair_id" => $id));

        /*会员基础数据*/
        $members = $query->result_array();

        /*关联会员详细资料*/
        foreach($members as $key => $row){
            $this->general_mdl->setTable('user_profiles');
            $row_profile = $this->general_mdl->get_query_by_where(array("user_id" =>$row['user_id']))->row();

            $this->general_mdl->setTable('users');
            $row = $this->general_mdl->get_query_by_where(array("id" =>$row['user_id']))->row();

            $members[$key]['cnname'] = $row_profile->name;
            $members[$key]['username'] = $row->username;
        }
        $this->data['members'] = $members;

        $this->load->view('admin_exchange/set_members', $this->data);
    }

    public function members_save() 
    {
        $user_id_array = $this->input->post("user_id");
        $exchange_fair_id = $this->input->post("exchange_id");
        $response['info'] = "";

        // 过滤数组内的空值
        $user_id_array = $user_id_array ? array_filter($user_id_array) : array();

        $this->general_mdl->setTable("rel_stores_exchange_fair");
        $where['exchange_fair_id'] = $exchange_fair_id;
        $where['state'] = 1;
        $query = $this->general_mdl->get_query_by_where($where); //取出已参会人员
        $current_members = $query->result_array();
        if($current_members){        
            foreach ($current_members as $key => $value) {
                if( !in_array($value['user_id'], $user_id_array) ){
                    $this->general_mdl->setData(array('state' => 0));
                    $this->general_mdl->update(array('exchange_fair_id' => $exchange_fair_id, 'user_id' => $value['user_id']));
                }
            }
        }

        foreach ($user_id_array as $key => $value) {
            $this->general_mdl->setData(array('state' => 1));
            $this->general_mdl->update(array('exchange_fair_id' => $exchange_fair_id, 'user_id' => $value));
        }

        $response['info'] = "修改成功";
        echo json_encode($response);
    }

    public function set_members_scheme() {

        $id = $this->input->get('id');
        
        /*订货会资料*/
        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $this->data['row'] = $query->row_array();

        $this->general_mdl->setTable("rel_stores_exchange_fair");
        $where['exchange_fair_id'] = $id;
        $where['state'] = 1;
        $query = $this->general_mdl->get_query_by_where($where); //取出已参会人员
        $members = $query->result_array();

        /*关联会员详细资料*/
        foreach($members as $key => $row){
            $this->general_mdl->setTable('user_profiles');
            $row_profile = $this->general_mdl->get_query_by_where(array("user_id" =>$row['user_id']))->row();

            $this->general_mdl->setTable('users');
            $row = $this->general_mdl->get_query_by_where(array("id" =>$row['user_id']))->row();

            $members[$key]['cnname'] = $row_profile->name;
            $members[$key]['username'] = $row->username;
        }
        $this->data['members'] = $members;

        /*必需品方案数据*/
        $this->general_mdl->setTable("necessities_scheme");
        $this->data['necessities_scheme'] = $this->general_mdl->get_query()->result_array();
        /*小类限制方案数据*/
        $this->general_mdl->setTable("small_class_restrictions");
        $this->data['small_class_restrictions'] = $this->general_mdl->get_query()->result_array();

        $this->load->view('admin_exchange/set_members_scheme', $this->data);
    }

    public function members_scheme_save() {
        $amount = $this->input->post('amount');
        $necessities_scheme_id = $this->input->post('necessities_scheme_id');
        $small_class_restrictions_id = $this->input->post('small_class_restrictions_id');
        $exchange_fair_id = $this->input->post("exchange_id");

        $this->general_mdl->setTable("rel_stores_exchange_fair");
        foreach ($amount as $user_id => $value) {
            $this->general_mdl->setData( array('amount' => $value, 'necessities_scheme_id' => $necessities_scheme_id[$user_id],'small_class_restrictions_id' => $small_class_restrictions_id[$user_id]) );
            $this->general_mdl->update( array('exchange_fair_id' => $exchange_fair_id, 'user_id' => $user_id) );
        }

        $response['info'] = "修改成功";
        echo json_encode($response);
    }
}

/* End of file exchange.php */
/* Location: ./application/controllers/exchange.php */
