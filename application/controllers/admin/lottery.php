<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lottery extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/lottery
     *  - or -
     *      http://example.com/index.php/lottery/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/lottery/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();
        $this->general_mdl->setTable('lotterytype');

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/lottery/";
    }

    public function index()
    {
        $lottery_data = array();
        $party_id_array = array();

        $party_id = $this->input->get("party");

        if($this->dx_auth->is_admin())
        {
            $this->general_mdl->setTable('party');
            $query = $this->general_mdl->get_query();
            $user_party_ids = $query->result_array();
            foreach ($user_party_ids as $key => $value) {
                $party_id_array[] = $value['id'];
            }
        }
        else
        {
            /*取出当前用户管理的会议*/
            $user_id = $this->dx_auth->get_user_id();
            $this->general_mdl->setTable('party');
            $query = $this->general_mdl->get_fields(array("user_id" => $user_id), "id");
            $user_party_ids = $query->result_array();
            foreach ($user_party_ids as $key => $value) {
                $party_id_array[] = $value['id'];
            }
        }

        $this->general_mdl->setTable('lotterytype');
        if($party_id)
        {
            if(in_array($party_id, $party_id_array))
            {
                $query = $this->general_mdl->get_query_by_where(array("party_id" => $party_id), 0, '', "party_id ASC");
            }
        }
        else
        {
            if($party_id_array)
            {        
                $query = $this->general_mdl->get_query_by_where_in('party_id',$party_id_array, 0, '', "party_id ASC");
            }
        }

        $this->data['total'] = $query->num_rows();
        $lottery_data = $query->result_array();
        foreach ($lottery_data as $key => $value) 
        {
            // 将奖项转换为html字符串
            $lottery_content = json_decode($value['content']);
            $lottery_data[$key]['content_string'] = "";
            foreach ($lottery_content as $k => $row) 
            {
                $lottery_data[$key]['content_string'] .= sprintf("%s(人数:%s)<br/>", $k, $row);
            }

            // 将指定中奖人转换为html字符串
            $lottery_watchdog = json_decode($value['watchdog']);
            $lottery_data[$key]['watchdog_string'] = "";
            foreach ($lottery_watchdog as $k => $row) 
            {
                /*会员基础数据*/
                $this->general_mdl->setTable('users');
                $query = $this->general_mdl->get_query_by_where(array("id" => $row));
                $member = $query->row();

                /*关联会员详细资料*/
                $this->general_mdl->setTable('user_profiles');
                $row_profile = $this->general_mdl->get_query_by_where(array("user_id" =>$row))->row();

                $name = $row_profile->name ? $row_profile->name : $member->username;

                $lottery_data[$key]['watchdog_string'] .= sprintf("%s<br/>", $name);
            }

            $lottery_data[$key]['code'] = md5($value['id'].$value['party_id'].$value['title']);

            $this->general_mdl->setTable('party');
            $query = $this->general_mdl->get_query_by_where(array("id" => $value['party_id']));
            $lottery_data[$key]['party_title'] = $query->row()->title;
        }
        $this->data['result'] = $lottery_data;
        $this->load->view('admin/head');
        $this->load->view('admin_lottery/list', $this->data);
    }

    //添加
    public function add()
    {

        /*取出当前用户管理的会议*/
        $this->data['partys'] = array();
        $user_id = $this->dx_auth->get_user_id();
        $this->general_mdl->setTable('party');
        $query = $this->general_mdl->get_query_by_where(array("user_id" => $user_id));
        $this->data['partys'] = $query->result_array();

        $this->load->view('admin/min-head');
        $this->load->view('admin_lottery/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $post_data = $this->input->post(NULL,TRUE);

        // 将奖项json结构化
        foreach ($post_data['content'] as $key => $value) 
        {
            $content[$value] = $post_data['content_num'][$key];
        }
        $post_data['content'] = json_encode($content, JSON_UNESCAPED_UNICODE);
        $post_data['watchdog'] = json_encode($post_data['watchdog'], JSON_UNESCAPED_UNICODE);
        unset($post_data['content_num']);

        $this->general_mdl->setData($post_data);
        if($this->general_mdl->create()){
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
        $this->data['partys'] = array();

        $params = $this->uri->uri_to_assoc();
        $this->data['id'] = $params['edit'];

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $row = $query->row_array();

        $row['content'] = json_decode($row['content']);
        $watchdog = json_decode($row['watchdog']);
        $this->data['row'] = $row;

        /*会议参与人*/
        $customer_user_ids = array();
        $this->general_mdl->setTable("customer");
        $query = $this->general_mdl->get_query_by_where(array("party_id" => $row['party_id']));
        $customer_row = $query->row_array();
        if(isset($customer_row['user_ids']) and $customer_row['user_ids'])
        {
            $customer_user_ids = json_decode($customer_row['user_ids']);
        }

        $this->data['watchdog'] = array();
        if($customer_user_ids)
        {
            foreach ($customer_user_ids as $key => $value) 
            {
                /*会员基础数据*/
                $this->general_mdl->setTable('users');
                $query = $this->general_mdl->get_query_by_where(array("id" => $value));
                $member = $query->row();

                /*关联会员详细资料*/
                $this->general_mdl->setTable('user_profiles');
                $row_profile = $this->general_mdl->get_query_by_where(array("user_id" =>$value))->row();

                $name = $row_profile->name ? $row_profile->name : $member->username;
                // 判断会员是否已在指定中奖人名单内
                $selected = "";
                if(in_array($value, $watchdog))
                {
                    $selected = "selected='selected'";
                }
                $this->data['watchdog'][] = sprintf("<option value='%s' %s>%s</option>", $member->id, $selected, $name);
            }
        }

        /*取出当前用户管理的会议*/
        $user_id = $this->dx_auth->get_user_id();
        $this->general_mdl->setTable('party');
        $query = $this->general_mdl->get_query_by_where(array("user_id" => $user_id));
        $partys = $query->result_array();

        $party_id_array = array();
        foreach ($partys as $key => $value) 
        {
            $party_id_array[$key] = $value['id'];
            $temp[$value['id']] = $value;
        }

        // 判断该会议是否是当前用户管理
        if($row and in_array($row['party_id'], $party_id_array))
        {
            $this->data['row']['party'] = $temp[$row['party_id']];
            $this->load->view('admin/min-head');
            $this->load->view('admin_lottery/edit', $this->data);
        }
        else
        {
            show_404();
        }
    }

    //修改保存
    public function edit_save()
    {
        $id = $this->input->post('id');
        $post_data = $this->input->post(NULL, TRUE);
        unset($post_data['id']);
        
        // 将奖项json结构化
        foreach ($post_data['content'] as $key => $value) 
        {
            if(!empty($value))
            {
                $content[$value] = $post_data['content_num'][$key];
            }
        }
        $post_data['content'] = json_encode($content, JSON_UNESCAPED_UNICODE);
        $post_data['watchdog'] = json_encode($post_data['watchdog'], JSON_UNESCAPED_UNICODE);
        unset($post_data['content_num']);
        $this->general_mdl->setData($post_data);
        $where = array('id'=>$id);

        if($this->general_mdl->update($where)){
            $response['status'] = "y";
            $response['info'] = "修改成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "修改失败";
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

        $confirm_code = md5($id.$row['party_id'].$row['title']);
        if($code == $confirm_code)
        {
            $this->general_mdl->delete_by_id($id);
            $response['success'] = true;
        }

        echo json_encode($response);
    }

    //会议参与人
    public function get_party_customer()
    {
        $party_id = $this->input->post("party_id");

        /*会议参与人*/
        $customer_user_ids = array();
        $this->general_mdl->setTable("customer");
        $query = $this->general_mdl->get_query_by_where(array("party_id" => $party_id));
        $customer_row = $query->row_array();
        if(isset($customer_row['user_ids']) and $customer_row['user_ids'])
        {
            $customer_user_ids = json_decode($customer_row['user_ids']);
        }

        $this->data['customer_user_ids'] = $customer_user_ids;
        if($customer_user_ids)
        {
            foreach ($customer_user_ids as $key => $value) 
            {
                /*会员基础数据*/
                $this->general_mdl->setTable('users');
                $query = $this->general_mdl->get_query_by_where(array("id" => $value));
                $member = $query->row();

                /*关联会员详细资料*/
                $this->general_mdl->setTable('user_profiles');
                $row_profile = $this->general_mdl->get_query_by_where(array("user_id" =>$value))->row();

                $name = $row_profile->name ? $row_profile->name : $member->username;
                $data[] = sprintf("<option value='%s'>%s</option>", $member->id, $name);
            }
        }

        echo json_encode($data);
    }
}

/* End of file lottery.php */
/* Location: ./application/controllers/lottery.php */
