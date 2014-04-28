<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->load->library('logger');

        $this->load->model('tank_auth/profiles_mdl', 'profiles_mdl');
    }

	public function index()
	{
        $this->general_mdl->setTable('category');
        $data['categories'] = $this->general_mdl->get_query()->result_array();

		$this->load->view('front/head');
		$this->load->view('front/home', $data);
	}

    public function search()
    {
        if(!$this->tank_auth->is_logged_in()){
            $this->show_error('403', '权限不足', '搜索功能只对会员开放,请先登录!');
        }else{
            $this->general_mdl->setTable('category');
            $data['categories'] = $this->general_mdl->get_query()->result_array();

            $data['q'] = $q = $this->input->get('q');
            $data['table'] = $table = $this->input->get('table');

            $fileds = array('type', 'province', 'city', 'district', 'company', 'address');
            $info_data = array();
            $like = array();

            if($q){
                foreach ($fileds as $key => $value) {
                    $like[$value] = $q;
                }
            }

            if($table){
                $this->general_mdl->setTable($table);
                $query = $this->general_mdl->get_query_or_like($like);
                $info_data = $query->result_array();
            }

            $data['result'] = $info_data;
            $data['title'] = '搜索结果';

            $this->load->view('front/head');
            $this->load->view('front/search_result', $data);
        }

    }

    public function info()
    {
        $this->load->model('buylogs_mdl');

        if(!$this->tank_auth->is_logged_in()){
            $this->show_error('403', '权限不足', '搜索功能只对会员开放,请先登录!');
        }else{        
            $array = $this->uri->uri_to_assoc();
            $table = key($array);
            $id = current($array);

            $user_id = $this->tank_auth->get_user_id();
            $profile = $this->profiles_mdl->get_by_user_id($user_id)->row();

            //找出所要信息
            $this->general_mdl->setTable($table);
            $query = $this->general_mdl->get_query_by_where(array('id' => $id));
            $info_row = $query->row();

            //检查用户是否已经购买过;
            $where = array(
                'member_id' => $user_id,
                'table' => $table,
                'info_id' => $id
            );
            $query = $this->buylogs_mdl->get_query_by_where($where, 0 , "", 'id desc');
            $buy_log_row = $query->row(); //只取最新那条记录

            if($buy_log_row){ //已购买
                if(!$info_row->time_limit){ //信息未设置时限
                    $this->show_page($info_row);
                }else{

                    //计算信息到期时间
                    $now = new DateTime("now");
                    $buy_time = new DateTime($buy_log_row->buy_time);
                    $interval = new DateInterval('PT'.$info_row->time_limit.'H');
                    $end_time = $buy_time->add($interval);

                    if($now < $end_time){ //未到期
                        $this->show_page($info_row);
                    }else{ //信息到期
                        $this->buy_info($profile, $info_row, $table); //购买
                    }
                }
            }else{ //未购买
                $this->buy_info($profile, $info_row, $table); //购买
            }
            
        }

    }

    //显示页面
    private function show_page($info_row) {
        $data['title'] = '信息详情';
        $data['detail'] = $info_row;
        $this->load->view('front/head');
        $this->load->view('front/info_detail', $data);
    }

    //购买信息
    private function buy_info($profile, $info_row, $table) {
        $this->load->model('buylogs_mdl');
        //判断用户信用额度是否足够
        if($profile->credit - $info_row->price >= 0){
            //创建用户购买记录
            $db_data = $where = array(
                'member_id' => $profile->user_id,
                'table' => $table,
                'info_id' => $info_row->id,
                'buy_time' => date('Y-m-d H:i:s')
            );
            $this->buylogs_mdl->setData($db_data);
            $this->buylogs_mdl->create();

            //更新用户的信用额度
            $this->profiles_mdl->update_credit($info_row->price, $profile->user_id, FALSE);

            $this->show_page($info_row);
        }else{
            $this->show_error('403', '信用不足', '您的帐户信用额度不足，请及时充值');
        }
    }

    public function show_error($code=404, $head='找不到页面' ,$msg='')
    {
        $data['title'] = "错误信息";
        $data['code'] = $code;
        $data['head'] = $head;
        $data['msg'] = $msg;
        $this->load->view('front/head');
        $this->load->view('front/error', $data);
    }
}
