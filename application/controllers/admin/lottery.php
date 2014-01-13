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
            $this->general_mdl->setTable("lottery");
            $query = $this->general_mdl->get_query_by_where(array("lotteryType_id" => $value['id']));
            if($query->num_rows() > 0)
            {
                $lottery_data[$key]['content_string'] = "";
                foreach ($query->result() as $k => $row) 
                {
                    $lottery_data[$key]['content_string'] .= sprintf("%s(人数:%s)<br/>", $row->content, $row->num);
                }
            }

            $lottery_data[$key]['code'] = md5($value['id'].$value['party_id'].$value['title']);

            //关联会议名称
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
        $query = $this->general_mdl->get_query_by_where(array("user_id" => $user_id, "isLottery" => 1));
        $this->data['partys'] = $query->result_array();

        $this->load->view('admin/min-head');
        $this->load->view('admin_lottery/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $party_id = $this->input->post("party_id");
        $title = $this->input->post("title");
        $content = $this->input->post("content");
        $content_num = $this->input->post("content_num");

        $data['party_id'] = $party_id;
        $data['title'] = $title;

        $this->general_mdl->setTable('party');
        $query = $this->general_mdl->get_query_by_where(array("id" => $party_id, "isLottery" => 1));

        if($query->num_rows() > 0)
        {
            $this->general_mdl->setTable('lotterytype');
            $this->general_mdl->setData($data);
            if($lotteryType_id = $this->general_mdl->create())
            {
                $this->general_mdl->setTable("lottery");
                foreach ($content as $key => $value) 
                {
                    if(!empty($value))
                    {
                        $lottery = array(
                            "content" => $value,
                            "num" => $content_num[$key],
                            "lotteryType_id" => $lotteryType_id
                        );
                        $this->general_mdl->setData($lottery);
                        $this->general_mdl->create();
                    }
                }
                $response['status'] = "y";
                $response['info'] = "添加成功";
            }else{
                $response['status'] = "n";
                $response['info'] = "添加失败";
            }
        }
        else
        {
            $response['status'] = "n";
            $response['info'] = "添加失败,该会议不能使用抽奖功能";
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

        $row['content'] = array();
        // 将奖项转换为html字符串
        $this->general_mdl->setTable("lottery");
        $query = $this->general_mdl->get_query_by_where(array("lotteryType_id" => $this->data['id']));
        if($query->num_rows() > 0)
        {
            $row['content'] = $query->result_array();
        }

        $this->data['row'] = $row;

        /*取出当前用户管理的会议*/
        $user_id = $this->dx_auth->get_user_id();
        $this->general_mdl->setTable('party');
        $query = $this->general_mdl->get_query_by_where(array("user_id" => $user_id, "isLottery" => 1));
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
        $title = $this->input->post("title");
        $content = $this->input->post("content");
        $content_num = $this->input->post("content_num");
        
        $lotterytype_row = $this->general_mdl->get_query_by_where(array("id" => $id))->row();

        $this->general_mdl->setTable('party');
        $query = $this->general_mdl->get_query_by_where(array("id" => $lotterytype_row->party_id, "isLottery" => 1));

        if($query->num_rows() > 0)
        {
            $this->general_mdl->setTable("lotterytype");
            // 更新抽奖名称
            $this->general_mdl->setData(array("title" => $title));
            $where = array('id'=>$id);
            $lt_isUpdated = $this->general_mdl->update($where);

            $this->general_mdl->setTable("lottery");
            if($content)
            {        
                foreach ($content as $key => $value) 
                {
                    if($value)
                    {
                        $lottery = array(
                            "content" => $value,
                            "num" => $content_num[$key] ? $content_num[$key] : 1,
                            "lotteryType_id" => $id
                        );
                        $this->general_mdl->setData($lottery);
                        $this->general_mdl->create();
                    }
                }
            }

            if($lt_isUpdated){
                $response['status'] = "y";
                $response['info'] = "修改成功";
            }else{
                $response['status'] = "n";
                $response['info'] = "修改完成";
            }
        }
        else
        {
            $response['status'] = "n";
            $response['info'] = "修改失败,该会议不能使用讨论功能";
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

    //指定中奖人
    public function watchdog()
    {
        $this->data['partys'] = array();

        $params = $this->uri->uri_to_assoc();
        $this->data['id'] = $params['watchdog']; //抽奖id

        // 抽奖数据
        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $row = $query->row_array();

        // 抽奖的奖项数据
        $this->general_mdl->setTable("lottery");
        $query = $this->general_mdl->get_query_by_where(array("lotteryType_id" => $row['id']));
        if($query->num_rows() > 0)
        {
            $lottery = $query->result_array();
            // 指定中奖人数据
            $this->general_mdl->setTable("lotteryWatchDog");
            foreach ($lottery as $key => $value) 
            {
                $query = $this->general_mdl->get_query_by_where(array("lottery_id" => $value['id']));
                if($query->num_rows() > 0)
                {
                    $lotteryWatchDog = $query->row_array();
                    $lottery[$key]['watchdog'] = json_decode($lotteryWatchDog['watchdog']);
                }
            }
        }

        $this->data['row'] = $row;
        $this->data['lottery'] = isset($lottery) ? $lottery : array(); // 奖项数据

        /*会议参与人*/
        $this->general_mdl->setTable("customer");
        $query = $this->general_mdl->get_query_by_where(array("party_id" => $row['party_id']));
        $customer_row = $query->row_array();
        $customers = array();
        if($query->num_rows() > 0)
        {
            if($customer_row['user_id'])
            {
                $user_id_array = json_decode($customer_row['user_id']);
                foreach ($user_id_array as $key => $value) 
                {
                    /*关联会员名字*/
                    $this->general_mdl->setTable('user_profiles');
                    $row_profile = $this->general_mdl->get_query_by_where(array("user_id" =>$value))->row();

                    $customers[$key]['user_id'] = $value;
                    $customers[$key]['name'] = $row_profile->name ? $row_profile->name : $row_profile->mobile;
                    
                }
            }

        }
        $this->data['customers'] = isset($customers) ? $customers : array(); // 奖项数据

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
            $this->load->view('admin_lottery/watchdog', $this->data);
        }
        else
        {
            show_404();
        }
    }

    //保存指定中奖人数据
    public function watchdog_save()
    {
        $watchdog = $this->input->post("watchdog");
        $response['info'] = "";

        foreach ($watchdog as $key => $value) 
        {
            $where['lottery_id'] = $key;
            $this->general_mdl->setTable("lotteryWatchDog");
            $query = $this->general_mdl->get_query_by_where($where);

            $watchdog = array_filter($value);
            $data['lottery_id'] = $key;
            $data['watchdog'] = $watchdog ? json_encode( $watchdog ) : "";

            if($query->num_rows() == 0)
            {
                $this->general_mdl->setData($data);
                $this->general_mdl->create();
                $response['info'] = "添加成功";
            }
            else
            {
                $this->general_mdl->setData($data);
                $this->general_mdl->update($where);
                $response['info'] = "修改成功";
            }
        }

        $response['status'] = "y";
        echo json_encode($response);
    }
}

/* End of file lottery.php */
/* Location: ./application/controllers/lottery.php */
