<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/order
     *  - or -
     *      http://example.com/index.php/order/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/order/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('cybr_book_master');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/order/";
    }

    public function index()
    {
        checkPermission('order_view');

        $order_data = array();
        $party_id_array = array();

        if($this->dx_auth->is_admin()){
            $query = $this->general_mdl->get_query();
        }else{
            $query = $this->general_mdl->get_query_by_where(array("user_id" => $this->dx_auth->get_user_id()));
        }

        $this->data['total'] = $query->num_rows();
        $order_data = $query->result_array();
        
        foreach ($order_data as $key => $value) {
            
            //计算订单总价
            $order_data[$key]['total'] = 0;

            $this->general_mdl->setTable('cybr_book_detail');
            $query = $this->general_mdl->get_query_by_where(array('ch_bookno' => $value['ch_bookno']));
            $order_dishes = $query->result_array();
            foreach ($order_dishes as $row) {
                $order_data[$key]['total'] += $row['num_num'] * $row['num_price'];
            }

            // 关联门店信息
            $this->general_mdl->setTable('admin_user_profile');
            $profile = $this->general_mdl->get_query_by_where(array('user_id' => $value['user_id']))->row_array();
            $order_data[$key]['profileName'] = $profile['name'];
        }

        $this->data['result'] = $order_data;
        $this->data['title'] = '订单管理' ;

        $this->load->view('admin_order/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_order/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);

        $this->general_mdl->setData($data);
        if($orderType_id = $this->general_mdl->create())
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

        $this->load->view('admin_order/edit', $this->data);
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

        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $row = $query->row_array();

        $confirm_code = generate_verify_code(array($row['ch_bookno'], $row['vch_tel'], $row['user_id']));
        if($code == $confirm_code)
        {
            $this->general_mdl->delete_by_id($id);

            //删除订单内的菜品和做法
            $this->general_mdl->setTable('cybr_book_detail');
            $this->general_mdl->delete(array('ch_bookno' => $row['ch_bookno']));

            $this->general_mdl->setTable('cybr_book_memo');
            $this->general_mdl->delete(array('ch_bookno' => $row['ch_bookno']));
            $response['success'] = true;
        }

        echo json_encode($response);
    }

    public function get_order_goods()
    {
        $id = $this->input->post('id');

        $this->general_mdl->setTable('cybr_book_detail');
        $query = $this->general_mdl->get_query_by_where(array('ch_bookno' => $id));
        $result = $query->result_array();

        //关联菜式资料
        foreach ($result as $key => $row) {
            $this->general_mdl->setTable('cy_bt_dish');
            $dish = $this->general_mdl->get_query_by_where(array('ch_dishno' => $row['ch_dishno']))->row_array();
            $result[$key]['vch_dishname'] = $dish['vch_dishname'];
        }

        $this->data['result'] = $result;

        $this->load->view('admin_order/order_goods', $this->data);
    }

    public function del_order_good()
    {
        $id = $this->input->post('id');
        $code = $this->input->post('code');

        $response['success'] = false;

        $this->general_mdl->setTable('cybr_book_detail');
        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $row = $query->row_array();

        if($row)
        {        
            $confirm_code = generate_verify_code(array($row['id'], $row['ch_bookno'], $row['num_num']));
            if($code == $confirm_code)
            {
                $this->general_mdl->delete_by_id($id);

                // 删除预订菜品做法表内的做法
                $this->general_mdl->setTable('cybr_book_memo');
                $this->general_mdl->delete(array('int_id' => $row['int_id'], 'ch_bookno' => $row['ch_bookno']));

                $response['success'] = true;
            }
        }

        echo json_encode($response);
        
    }

    public function verify_view() 
    {
        $this->data['ch_bookno'] = $this->input->post('ch_bookno');
        $this->data['table_nums'] = $this->input->post('table_nums');

        $this->general_mdl->setTable('cybr_book_master');
        $order = $this->general_mdl->get_query_by_where( array('ch_bookno' => $this->data['ch_bookno']) )->row_array();

        $this->general_mdl->setTable('cybr_bt_bustype');
        $query = $this->general_mdl->get_query_by_where( array('user_id' => $order['user_id']) );
        $this->data['bustype'] = $query->result_array();

        $this->general_mdl->setTable('cybr_bt_table');
        $this->data['tables'] = $this->general_mdl->get_query_by_where( array('user_id' => $order['user_id']) )->result_array();

        $this->load->view('admin_order/verify', $this->data);
    } 

    public function verify() 
    {
        $this->load->library('stt_access');

        $ch_bookno = $this->input->post('ch_bookno');
        $write_type = $this->input->post('write_type');
        $tables = $this->input->post('tables');
        $bustype = $this->input->post('bustype');

        $this->general_mdl->setTable('cybr_book_master');
        $order = $this->general_mdl->get_query_by_where(array('ch_bookno' => $ch_bookno))->row_array();
        $order['ch_bustype'] = $bustype;

        // 找出当前门店的IP地址
        $profile = $this->profile->get_profile($order['user_id'])->row_array();

        $this->general_mdl->setTable('cybr_book_detail');
        $order_detail = $this->general_mdl->get_query_by_where(array('ch_bookno' => $ch_bookno))->result_array();
        foreach ($order_detail as $key => $value) {
            $this->general_mdl->setTable('cybr_book_memo');
            $make = $this->general_mdl->get_query_by_where( array('ch_bookno' => $ch_bookno, 'int_id' => $value['int_id']) )->row_array();
            if($make){            
                $order_detail[$key]['dish_make'][0]['ch_memono'] = $make['ch_no'];
                $order_detail[$key]['dish_make'][0]['num'] = 1;
            }else{
                $order_detail[$key]['dish_make'] = array();                
            }
        }
        $order['order_detail'] = $order_detail;

        $response['status'] = 'n';

        $stt_response = $this->stt_access->order_write($profile['website'], $tables, $order, $write_type);
        if($stt_response && $stt_response->status){
            // 更改订单为审核状态
            $this->general_mdl->setTable('cybr_book_master');
            $this->general_mdl->setData(array('is_verified' => 1, 'stt_bookno' => $stt_response->ch_bookno, 'ch_bustype' => $bustype));
            $this->general_mdl->update(array('ch_bookno' => $ch_bookno));
            $response['info'] = $stt_response->error;
            $response['status'] = 'y';
        }else{
            $response['info'] = isset($stt_response->error) ? $stt_response->error : '程序发生错误,请检查分店程序已运行';
        }

        echo json_encode($response);
    }
}

/* End of file order.php */
/* Location: ./application/controllers/order.php */
