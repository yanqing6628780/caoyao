<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class my_interface extends REST_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->member_server_domain = "http://192.168.0.136:8168";
        $this->load->library('logger');
    }


    //获取门店(用户)数据
    function users_get(){
        $this->general_mdl->setTable('admin_user_profile');
        $query = $this->general_mdl->get_query();

        $result['content'] = $query->result_array();
        $result['status'] = 1;
        $this->response($result, 200); // 200 being the HTTP response code
    }

    //市别数据入库
    function bustype_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        $data = json_decode($this->post('data'));

        $resp['status'] = 0;
        $is_matche = $this->dx_auth->login($username, $password, FALSE);

        if($is_matche){
            //将接受到的数据写入数据库
            $this->post_to_db('cybr_bt_bustype', $data, 'ch_bustype');
            $resp['status'] = 1;
        }else{
            $resp['error'] = "请填写正确的用户名和密码";
        }
        $this->response($resp, 200);
    }

    //菜品数据入库
    function dish_post()
    {

        $username = $this->post('username');
        $password = $this->post('password');
        $data = json_decode($this->post('data'));

        
        $resp['status'] = 0;
        $is_matche = $this->dx_auth->login($username, $password, FALSE);

        if($is_matche){
            $this->post_to_db('cy_bt_dish', $data, 'ch_dishno');
            $resp['status'] = 1;
        }else{
            $resp['error'] = "请填写正确的用户名和密码";
        }
        $this->response($resp, 200);
    }

    //菜品沽清数据入库
    function dish_warn_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        $data = json_decode($this->post('data'));

        $resp['status'] = 0;
        $is_matche = $this->dx_auth->login($username, $password, FALSE);

        if($is_matche){
            $this->post_to_db('cybr_u_dish_warn', $data, 'int_flowID');
            $resp['status'] = 1;
        }else{
            $resp['error'] = "请填写正确的用户名和密码";
        }
        $this->response($resp, 200);
    }

    //特价菜品数据入库
    function dish_special_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        $data = json_decode($this->post('data'));

        $resp['status'] = 0;
        $is_matche = $this->dx_auth->login($username, $password, FALSE);

        if($is_matche){
            $this->post_to_db('cybr_bt_dish_special', $data, 'int_flowID');
            $resp['status'] = 1;
        }else{
            $resp['error'] = "请填写正确的用户名和密码";
        }
        $this->response($resp, 200);
    }

    //套菜数据入库
    function dish_suit_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        $data = json_decode($this->post('data'));

        $resp['status'] = 0;
        $is_matche = $this->dx_auth->login($username, $password, FALSE);

        if($is_matche){
            $this->post_to_db('cybr_bt_dish_suit', $data, 'int_flowID');
            $resp['status'] = 1;
        }else{
            $resp['error'] = "请填写正确的用户名和密码";
        }
        $this->response($resp, 200);
    }

    //菜品做法数据入库
    function dish_memo_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        $data = json_decode($this->post('data'));

        $resp['status'] = 0;
        $is_matche = $this->dx_auth->login($username, $password, FALSE);

        if($is_matche){
            $this->post_to_db('cybr_cp_dish_memo', $data, 'ch_dishno');
            $resp['status'] = 1;
        }else{
            $resp['error'] = "请填写正确的用户名和密码";
        }
        $this->response($resp, 200);
    }

    //餐台数据入库
    function table_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        $data = json_decode($this->post('data'));

        $resp['status'] = 0;
        $is_matche = $this->dx_auth->login($username, $password, FALSE);

        if($is_matche){
            $this->post_to_db('cybr_bt_table', $data, 'ch_tableno');
            $resp['status'] = 1;
        }else{
            $resp['error'] = "请填写正确的用户名和密码";
        }
        $this->response($resp, 200);
    }

    // 数据入库
    private function post_to_db($table, $db_data, $matche_field){
        $user_id = $this->dx_auth->get_user_id();
        $this->general_mdl->setTable($table);

        if(is_array($db_data)){$db_data = (object)$db_data;}

        if(is_object($db_data)){        
            foreach ($db_data as $key => $row) {
                $row->user_id = $user_id;
                $query = $this->general_mdl->get_query_by_where( array($matche_field => $row->{$matche_field}, 'user_id' => $user_id) );
                $this->general_mdl->setData($row);
                // 如果已有数据则更新,没有则创建
                if($query->num_rows()){
                    $this->general_mdl->update( array($matche_field => $row->{$matche_field}) );
                }else{
                    $this->general_mdl->create();
                }
            }
        }
    }

    //接受门店上传的菜单数据
    function receive_goods_post(){
        $username = $this->post('username');
        $password = $this->post('password');

        $resp['status'] = 0;
        $is_matche = $this->dx_auth->login($username, $password, FALSE);

        if($is_matche){
            $resp['status'] = 1;
            $user_id = $this->dx_auth->get_user_id();

            //将接受到的菜单写入数据库
            // code...
        }else{
            $resp['error'] = "请填写正确的用户名和密码";
        }
        $this->response($resp, 200);
    }

    //获取门店的菜单数据
    function get_user_goods_post(){
        $result['status'] = 0;
        $user_id = $this->post('user_id');

        $this->general_mdl->setTable('goods');
        $query = $this->general_mdl->get_query_by_where( array('user_id' => $user_id) );

        if($query->num_rows() > 0){        
            $result['content'] = $query->result_array();
            $result['status'] = 1;
            $this->response($result, 200);
        }else{
            $result['error'] = 'no data';
            $result['status'] = 0;
            $this->response($result, 200);
        }
    }

    //下单
    function orders_post(){
        
    }

    //获取订单表数据
    function orders_get(){

        $order_sn = $this->get('sn');
        $wechat_openid = $this->get('openid');

        $this->general_mdl->setTable('order');
        if($order_sn){
            $where = array('order_sn' => $order_sn);
        }else if($wechat_openid){
            $where = array('wechat_openid' => $wechat_openid);
        }else{
            $where = array();
        }
        $query = $this->general_mdl->get_query_by_where($where);

        if ($order_sn) {
            $row = $query->row_array();

            $this->general_mdl->setTable('order_goods');
            $order_goods = $this->general_mdl->get_query_by_where(array('order_id' => $row['id']))->result_array();
            $result['goods'] = $order_goods;
            
            $result['status'] = 1;
            $this->response($result, 200); // 200 being the HTTP response code
        } else if ($query->num_rows() > 0) {
            $result = $query->result_array();

            foreach ($result as $key => $row) {
                $this->general_mdl->setTable('admin_user_profile');
                $profile = $this->general_mdl->get_query_by_where(array('user_id' => $row['user_id']))->row_array();
                $result[$key]['profile'] = $profile;
                
                $this->response($result, 200); // 200 being the HTTP response code
            }

        } else {
            $result['status'] = 0;
            $result['error'] = 'no data';
            $this->response($result, 200);
        }
    }

    //优惠卷发放
    function coupon_use_post()
    {
        $this->load->model('general_mdl');

        if($this->post('id') && $this->post('wx_num')){

            $this->general_mdl->setTable('coupon_kind');
            //优惠劵发行量和限取数量
            $query = $this->general_mdl->get_query_by_where( array('id' => $this->post('id')) ); 
            if($query->num_rows() > 0){
                $d[] = $coupon_circulation =  $query->row()->coupon_circulation;
                $d[] = $coupon_limit =  $query->row()->get_limit;
            }else{
                $data['status'] = 0;
                $data['error'] = "没有该优惠卷";
                $this->response($data, 200);
            }

            //获取优惠劵已经发放条数
            $this->general_mdl->setTable('coupon_use');
            $query = $this->general_mdl->get_query_by_where( array('coupon_id' => $this->post('id')) ); 
            $coupon_used_amount = $query->num_rows();

            $query = $this->general_mdl->get_query_by_where( array('coupon_id' => $this->post('id'), 'wx_num' => $this->post('wx_num')) );
            $user_own_coupon_num = $query->num_rows();

            if ($coupon_used_amount < $coupon_circulation && $user_own_coupon_num < $coupon_limit) {
                //检查优惠卷号是否重复
                do {
                    $coupon_num = generate_password(6, 1);
                } while ($this->general_mdl->get_query_by_where( array('coupon_num' => $coupon_num) )->num_rows() > 0);

                $data['coupon_num'] = $coupon_num;
                $data['wx_num'] = $this->post('wx_num');
                $data['coupon_id'] = $this->post('id');
                $this->general_mdl->setData($data);
                
                if ($this->general_mdl->create()) {
                    $data['status'] = 1;
                    $this->response($data, 200);
                } else {
                    $data['status'] = 0;
                    $this->response($data, 200);
                }
            }

            if ($user_own_coupon_num >= $coupon_limit) {
                $data['status'] = 0;
                $data['error'] = "你领取优惠劵的名额以用完";
                $this->response($data, 200);
            }

            if ($coupon_used_amount >= $coupon_circulation) {
                $data['status'] = 0;
                $data['error'] = "优惠劵已被领完";
                $this->response($data, 200);
            } 
        }else{
            $data['status'] = 0;
            $data['error'] = "请求失败";
            $this->response($data, 200);            
        }

    }

    //优惠卷打印
    function coupon_print_post()
    {
        $coupon_num = $this->post('coupon_num');
        if($coupon_num){

            $this->general_mdl->setTable('coupon_use');
            $query = $this->general_mdl->get_query_by_where( array('coupon_num' => $coupon_num) );
            if($query->num_rows() > 0){
                $row = $query->row_array();
                if($row['coupon_status'] == 1){
                    $data['status'] = 0;
                    $data['error'] = "该 优惠卷/奖品卷 已打印过";
                    $this->response($data, 200); 
                }

                $this->general_mdl->setTable('coupon_kind');
                $query = $this->general_mdl->get_query_by_where( array('id' => $row['coupon_id']) );

                if($query->num_rows() > 0){

                    $row['coupon_status'] = 1;
                    $update_data = $row;
                    $this->general_mdl->setTable('coupon_use');
                    $this->general_mdl->setData($update_data);
                    $this->general_mdl->update(array('id' => $row['id']));

                    $data['content'] = $query->row_array();
                    $data['status'] = 1;
                    $this->response($data, 200);
                }else{
                    $data['status'] = 0;
                    $data['error'] = "没有該 优惠卷/奖品卷 信息";
                    $this->response($data, 200); 
                }
            }else{
                $data['status'] = 0;
                $data['error'] = "没有此 优惠卷/奖品卷 号码";
                $this->response($data, 200); 
            }
        }else{
            $data['status'] = 0;
            $data['error'] = "请求失败";
            $this->response($data, 200);            
        }
    }

    //获取微信图文文章内容
    public function wechat_articles_get(){
        $id = $this->get('id');
        $this->general_mdl->setTable('wechat_msg_news');
        if($id) {
            $query = $this->general_mdl->get_query_by_where(array("id" => $id));
            $response = $query->row_array();
        }

        echo json_encode($response);
    }

    //微信会员检查
    public function wechat_member_check_get() {
        $openid = $this->get('openid') ? $this->get('openid') : "";

        $this->general_mdl->setTable('wechat_member_bind');
        $query = $this->general_mdl->get_query_by_where(array("openid" => $openid));
        
        if($query->num_rows() > 0){
            $resp['status'] = TRUE;
            $resp['cardNo'] = $query->row()->cardNo;
        }else{
            $resp['status'] = FALSE;
        }

        echo json_encode($resp);
    }

    //微信会员绑定
    public function wechat_member_bind_get() 
    {
        $this->load->library('curl');

        $openid = $this->get('openid');
        $cardNo = $this->get('cardNo');

        $url = $this->member_server_domain."/stt_access/get_leaguer";
        $post_data['filter'] = json_encode( array('vch_memberno' => $cardNo) );
        $this->curl->create($url);
        $this->curl->http_login('sqt', 'YWaWMTIzNA', 'basic');
        $this->curl->post($post_data);
        $leaguer_resp = json_decode($this->curl->execute());

        $response['status'] = FALSE;
        $this->general_mdl->setTable('wechat_member_bind');
        if(isset($leaguer_resp->leaguer) && !empty($leaguer_resp->leaguer) ){        
            if($openid && $cardNo) {
                $query = $this->general_mdl->get_query_by_where(array("cardNo" => $cardNo));
                if($query->num_rows() > 0){
                    $response['info'] = '此卡号已绑定,请不要重复绑定';
                }else{
                    $create_data['openid'] = $openid;
                    $create_data['cardNo'] = $cardNo;
                    $this->general_mdl->setData($create_data);
                    $this->general_mdl->create();
                    $response['status'] = TRUE;
                    $response['info'] = '绑定成功!';
                }
            }else{
                $response['info'] = '请输入卡号!';
            }
        }else{
            $response['info'] = '请输入正确的卡号!';
        }

        echo json_encode($response);
    }
}