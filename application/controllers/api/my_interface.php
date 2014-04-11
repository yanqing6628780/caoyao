<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class my_interface extends REST_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->library('logger');
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
            $this->post_to_db('cybr_bt_bustype', $data);
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
            $this->post_to_db('cy_bt_dish', $data);
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
            $this->post_to_db('cybr_u_dish_warn', $data);
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
            $this->post_to_db('cybr_bt_dish_special', $data);
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
            $this->post_to_db('cybr_bt_dish_suit', $data);
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
            $this->post_to_db('cybr_cp_dish_memo', $data);
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
            $this->post_to_db('cybr_bt_table', $data);
            $resp['status'] = 1;
        }else{
            $resp['error'] = "请填写正确的用户名和密码";
        }
        $this->response($resp, 200);
    }

    // 数据入库
    private function post_to_db($table, $db_data){

        $user_id = $this->dx_auth->get_user_id();

        $this->general_mdl->setTable($table);

        if(is_array($db_data)){$db_data = (object)$db_data;}

        if(is_object($db_data)){
            $this->general_mdl->delete(array('user_id' => $user_id)); //清空该用户的数据
            foreach ($db_data as $key => $row) {
                $row->user_id = $user_id;
                $this->general_mdl->setData($row);
                $this->general_mdl->create();
            }
        }
    }

    //获取门店(用户)数据
    function users_get(){
        $this->general_mdl->setTable('admin_user_profile');
        $query = $this->general_mdl->get_query();

        $result['content'] = $query->result_array();
        $result['status'] = 1;
        $this->response($result, 200); 
    }

    //获取订单表数据
    function orders_get() {

        $ch_bookno = $this->get('sn');
        $wechat_openid = $this->get('openid');

        $this->general_mdl->setTable('cybr_book_master');
        if($ch_bookno){
            $where = array('ch_bookno' => $ch_bookno);
        }else if($wechat_openid){
            $where = array('openid' => $wechat_openid);
        }else{
            $where = array();
        }
        $query = $this->general_mdl->get_query_by_where($where, 0, "", "id DESC");

        if ($ch_bookno) { //返回订单内的菜式
            $row = $query->row_array(); //订单信息
            if($row){
                $result['master'] = $row;
                // 取出订单内的菜式
                $this->general_mdl->setTable('cybr_book_detail');
                $order_dishes = $this->general_mdl->get_query_by_where(array('ch_bookno' => $ch_bookno))->result_array();
                //关联菜式资料
                foreach ($order_dishes as $key => $value) {
                    $this->general_mdl->setTable('cy_bt_dish');
                    $dish = $this->general_mdl->get_query_by_where(array('ch_dishno' => $value['ch_dishno']))->row_array();
                    $order_dishes[$key]['photo'] = $dish['photo'];
                    $order_dishes[$key]['vch_dishname'] = $dish['vch_dishname'];
                }
                $result['dishes'] = $order_dishes;
                    $result['is_verified'] = $row['is_verified'] ? TRUE : FALSE;
                    $result['is_paid'] = $row['is_paid'] ? TRUE : FALSE;
                $result['status'] = 1;
            }else{
                $result['status'] = 0;
            }
            
            $this->response($result, 200); 
        } else if ($query->num_rows() > 0) { //返回用户订单列表
            $result = $query->result_array();

            foreach ($result as $key => $row) {

                // 检查订单是否已点菜
                $this->general_mdl->setTable('cybr_book_detail');
                $query = $this->general_mdl->get_query_by_where(array('ch_bookno' => $row['ch_bookno']));
                $result[$key]['bookDish_nums'] = $query->num_rows();

                // 关联门店信息
                $this->general_mdl->setTable('admin_user_profile');
                $profile = $this->general_mdl->get_query_by_where(array('user_id' => $row['user_id']))->row_array();
                $result[$key]['profileName'] = $profile['name'];
                
            }
            $this->response($result, 200);

        } else {
            $result['status'] = 0;
            $result['error'] = 'no data';
            $this->response($result, 200);
        }
    }

    //获取门店的菜单数据
    function dish_get(){
        $result['status'] = 0;
        $dish_id = $this->get('DishId');
        $user_id = $this->get('user_id');

        $where['user_id'] = $user_id ? $user_id : 1;
        if($dish_id){
            $where['ch_dishno'] = $dish_id;
        }

        $this->general_mdl->setTable('cy_bt_dish');
        $query = $this->general_mdl->get_query_by_where($where);

        if($query->num_rows() > 0){
            if($dish_id){ //获取单个菜式
                $result = $query->row_array();
                // 获取菜式做法
                $this->general_mdl->setTable('cybr_cp_dish_memo');
                $query = $this->general_mdl->get_query_by_where(array('ch_dishno' => $dish_id));
                $result['memos'] = $query->result_array();
                $result['memo'] = "";
                $result['nums'] = 1;

                // 查询是否有子菜
                $this->general_mdl->setTable('cybr_bt_dish_suit');
                $query = $this->general_mdl->get_query_by_where(array('ch_suitno' => $dish_id));
                if($query->num_rows()){
                    $chlid_dishes = $query->result_array(); //所有子菜数据集

                    // 查询子菜资料
                    $this->general_mdl->setTable('cy_bt_dish');
                    foreach ($chlid_dishes as $key => $row) {
                        $result['children'][] = $this->general_mdl->get_query_by_where( array('ch_dishno' => $row['ch_dishno']) )->row_array();
                    }
                }

                // 获取菜品图片
                $this->general_mdl->setTable('dish_photo');
                $query = $this->general_mdl->get_query_by_where( array('dishno' => $result['ch_dishno'], 'user_id' => $user_id) );
                if($query->num_rows() > 0){
                    $result['photo'] = get_image_url($query->row()->photo);
                }

            }else{ //获取所有菜式
                $result = $query->result_array();

                foreach ($result as $key => $value) {
                    // 获取菜品沽清信息 
                    $result[$key]['warn'] = FALSE;
                    $this->general_mdl->setTable('cybr_u_dish_warn');
                    $query = $this->general_mdl->get_query_by_where( array('ch_dishno' => $value['ch_dishno'], 'user_id' => $user_id) );
                    if($query->num_rows() > 0){
                        $warn_row = $query->row();
                        // 当菜品沽清则不显示
                        if($warn_row->num_sale - $warn_row->num_warn == 0){                            
                            $result[$key]['warn'] = TRUE;
                        }
                    }

                    // 获取菜品图片
                    $this->general_mdl->setTable('dish_photo');
                    $query = $this->general_mdl->get_query_by_where( array('dishno' => $value['ch_dishno'], 'user_id' => $user_id) );
                    if($query->num_rows() > 0){
                        $result[$key]['photo'] = get_image_url($query->row()->photo);
                    }
                }
            }
            $this->response($result, 200);
        }else{
            $result['error'] = 'no data';
            $result['status'] = 0;
            $this->response($result, 200);
        }
    }

    function orders_dish_delete() {
        $id = $this->get("id");
        $bookno = $this->input->get("sn");
        $int_id = $this->input->get("int_id");

        // 删除预订菜品明细表内的菜品
        $this->general_mdl->setTable('cybr_book_detail');
        $this->general_mdl->delete(array('id' => $id));

        // 删除预订菜品做法表内的做法
        $this->general_mdl->setTable('cybr_book_memo');
        $this->general_mdl->delete(array('int_id' => $int_id, 'ch_bookno' => $bookno));

        $response['info'] = "删除成功";
        $this->response($response, 200);
    }

    //下单
    function book_post() {

        $db_data['user_id'] = $this->post('user_id');
        $db_data['vch_tel'] = $this->post('tel');
        $db_data['int_person'] = $this->post('person');
        $db_data['table_nums'] = $this->post('table_nums');
        $db_data['vch_booker'] = $this->post('booker');
        $db_data['dt_come'] = $this->post('come');
        $db_data['openid'] = $this->post('openid');
        $db_data['ch_type'] = 1;

        $this->general_mdl->setTable('cybr_book_master');
        $this->general_mdl->setData($db_data);
        $insert_id = $this->general_mdl->create();

        //更新订单号
        if($insert_id) {
            $flowID = str_pad($insert_id, 4, 0, STR_PAD_LEFT);
            $db_data = array("ch_bookno" => date("Ymd").$flowID);
            $this->general_mdl->setData($db_data);
            $this->general_mdl->update(array("id" => $insert_id));

            $response['ch_bookno'] = $db_data['ch_bookno'];
            $response['status'] = TRUE;
        }else{
            $response['status'] = FALSE;
        }

        $this->response($response, 200);
    }

    //下单
    function book_detail_post() {

        $response['status'] = FALSE;
        $post_data = json_decode(file_get_contents("php://input"), TRUE);

        if( isset($post_data['bookno']) && $post_data['bookno'] ){

            foreach ($post_data['cart'] as $key => $row) {
                $this->general_mdl->setTable('cybr_book_detail');
                $int_id = $key+1;
                $db_data = array(
                    'ch_bookno' => $post_data['bookno'],
                    'int_id' => $int_id,
                    'ch_dishno' => $row['ch_dishno'],
                    'num_num' => $row['nums'],
                    'num_price' => $row['price'],
                    'ch_suitflag' => $row['ch_suitflag'],
                    'vch_print_memo' => isset($row['vch_memo']) ? $row['vch_memo'] : ""
                );

                $this->general_mdl->setData($db_data);
                $this->general_mdl->create();

                if(isset($row['ch_memono']) && $row['ch_memono']){
                    $this->general_mdl->setTable('cybr_book_memo');
                    $db_data = array(
                        'ch_bookno' => $post_data['bookno'],
                        'vch_print_memo' => $row['vch_memo'],
                        'int_id' => $int_id,
                        'ch_no' => $row['ch_memono']
                    );
                    $this->general_mdl->setData($db_data);
                    $this->general_mdl->create();
                }
            }

            $response['status'] = TRUE;
        }else{
            $response['info'] = '未指定订单号';
        }
        $this->response($response, 200);
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

        $this->response($response, 200);
    }

    //微信会员检查
    public function wechat_member_check_get() {
        $openid = $this->get('openid') ? $this->get('openid') : "";

        $this->general_mdl->setTable('wechat_member_bind');
        $query = $this->general_mdl->get_query_by_where(array("openid" => $openid));
        
        if($query->num_rows() > 0){
            $resp['status'] = TRUE;
            $resp['cardNo'] = $query->row()->cardNo;
            $resp['wechat_cardno'] = $query->row()->wechat_cardno;
        }else{
            $resp['status'] = FALSE;
        }

        $this->response($resp, 200);
    }

    //微信会员绑定
    public function wechat_member_bind_get() 
    {
        $this->load->library('curl');

        $openid = $this->get('openid');
        $name = $this->get('name');
        $tel = $this->get('tel');

        $response['status'] = FALSE;
        $this->general_mdl->setTable('wechat_member_bind');
        if( $openid ){        
            if($name && $tel) {
                $query = $this->general_mdl->get_query_by_where(array("tel" => $tel));
                if($query->num_rows() > 0){
                    $response['info'] = '此电话已绑定,请不要重复绑定';
                }else{
                    $auto_increment = $this->general_mdl->get_auto_increment();
                    $create_data['wechat_cardno'] = generate_username($auto_increment);
                    $create_data['openid'] = $openid;
                    $create_data['name'] = $name;
                    $create_data['tel'] = $tel;
                    $this->general_mdl->setData($create_data);
                    $this->general_mdl->create();
                    $response['status'] = TRUE;
                    $response['wechat_cardno'] = $create_data['wechat_cardno'];
                }
            }else{
                $response['info'] = '请输入姓名和电话!';
            }
        }else{
            $response['info'] = '非法进入!';
        }

        $this->response($response, 200);
    }

    public function wechat_member_credit_get()
    {
        $this->load->library('stt_access');
        $cardNo = $this->get('cardNo');

        $stt_resp = $this->stt_access->get_members_credit($cardNo);
        $this->response($stt_resp, 200);
    }
}