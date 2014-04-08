<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class wechat extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/wechat
     *  - or -
     *      http://example.com/index.php/wechat/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/wechat/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();

        checkIsLoggedIn();

        $this->load->library('curl_tool');
        $this->load->library("curl");

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->general_mdl->setTable('sys_config');
        $this->data['config'] = $this->general_mdl->get_query_by_where(array('cat' => 'wechat'))->result_array();
        $this->appid = $this->data['config'][0]['value'];
        $this->secret = $this->data['config'][1]['value'];

        $this->data['controller_url'] = "admin/wechat/";
    }

    public function index()
    {
        checkPermission('wechat_admin');
        $this->load->view('wechat/home', $this->data);
    }

    // 配置
    public function config()
    {
        checkPermission('wechat_admin');
        $this->load->view('wechat/config', $this->data);
    }

    // 配置
    public function config_save()
    {
        checkPermission('wechat_admin');
        $data = $this->input->post(NULL, TRUE);
        $this->general_mdl->setTable('sys_config');
        foreach ($data as $key => $value) {
            $where = array('name' => $key);
            $update_data = array('value' => $value);
            $this->general_mdl->setData($update_data);
            $this->general_mdl->update($where);
        }
        $response['status'] = 'y';
        $response['info'] = '修改成功';
        
        echo json_encode($response);
    }

    public function msgsend()
    {
        checkPermission('wechat_admin');

        $token = $this->session->userdata('wechat_access_token');
        //检查是否有access_token
        if(!$token) {        
            $response = $this->get_access_token();
            $this->data['wechat_resp'] = $response;
        }

        if($token) {
            $user_response = $this->get_users($token);
            if( isset($user_response->data) ) {                
                $this->data['openid'] = join($user_response->data->openid, ',');

                // $next_openid = $user_response->next_openid;
                // while ( $next_openid != "") {
                //     $new_resp = $this->get_users($token, $next_openid);
                //     if(isset($new_resp->data)) {
                //         $next_openid = $new_resp->next_openid;
                //         array_merge($this->data['openid'], $new_resp->data->openid);
                //     }
                // }
            }
        }
        $this->load->view('wechat/msgsend', $this->data);
    }

    public function msgsend_save() 
    {
        $post = $this->input->post(NULL, TRUE);

        $token = $this->session->userdata('wechat_access_token');
        $data['status'] = 'n';

        $openid_array = explode(",", $post['openid']);
        
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;

        $output = array();
        if($post['openid']) {
            switch ($post['type'])
            {
                case 'text':

                    //发送
                    foreach ($openid_array as $key => $value) {
                        $output[] = $this->_sendText($url, $value, $post['content']);
                        $data['errors'][] = $this->curl->error_string;
                        sleep(1);
                    }

                    break;
                case 'news':

                    //将图文信息入库
                    if( $post['title'] && $post['picurl'] ) {
                        $this->general_mdl->setTable('wechat_msg_news');
                        $id = $this->general_mdl->get_query()->num_rows() + 1;
                        $create_data = array(
                            'id' => $id,
                            'title' => $post['title'],
                            'description' => $post['description'],
                            'url' => $post['url'],
                            'picurl' => get_image_url($post['picurl']),
                            'content' => $post['news_content']
                        );
                        $this->general_mdl->setData($create_data);
                        $insert_id = $this->general_mdl->create();
                        if( $insert_id && empty($post['url']) && !empty($post['news_content']) ) {
                            $update_data['url'] = base_url('wechat/article.html?id='.$id);
                            $this->general_mdl->setData($update_data);
                            $this->general_mdl->update(array('id' => $insert_id));
                        }
                    }

                    //发送
                    foreach ($openid_array as $key => $value) {
                        $output[] = $this->_sendNews($url, $value, $post);
                        $data['errors'][] = $this->curl->error_string;
                        sleep(1);
                    }

                    break;
            }
            $data['status'] = empty($output) ? 'y' : 'n';
            $data['info'] = empty($output) ? '发送完成' : $output;
        }else{
            $data['info'] = '发送失败';
        }
        
        echo json_encode($data);
    }

    public function autoreply()
    {
        checkPermission('wechat_admin');
        $this->load->view('wechat/autoreply', $this->data);
    }

    // 图文管理页
    public function appmsg()
    {
        checkPermission('wechat_admin');

        $this->general_mdl->setTable('wechat_msg_news');
        $query = $this->general_mdl->get_query();
        $this->data['result'] = $query->result_array();

        $this->data['title'] = "图文管理";
        $this->load->view('wechat/appmsg', $this->data);
    }

    public function newsedit()
    {
        checkPermission('wechat_admin');

        $id = $this->input->get('id');

        $this->general_mdl->setTable('wechat_msg_news');
        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $this->data['row'] = $query->row_array();

        $this->load->view('wechat/newsedit', $this->data);
    }

    public function newsadd()
    {
        checkPermission('wechat_admin');
        $this->load->view('wechat/newsadd', $this->data);
    }

    public function news_save()
    {
        $post_data = $this->input->post(NULL, TRUE);

        $this->general_mdl->setTable('wechat_msg_news');

        if(isset($post_data['id']) && !empty($post_data['id']) ){
            if( empty($post_data['url']) && !empty($post_data['content']) ) {
                $post_data['url'] = base_url('wechat/article.html?id='.$post_data['id']);
            }
            $this->general_mdl->setData($post_data);
            $this->general_mdl->update(array('id' => $post_data['id']));
            $response['info'] = '修改完成';
        }else{
            $this->general_mdl->setData($post_data);
            $insert_id = $this->general_mdl->create();
            if( $insert_id && empty($post_data['url']) && !empty($post_data['content']) ) {
                $update_data['url'] = base_url('wechat/article.html?id='.$id);
                $this->general_mdl->setData($update_data);
                $this->general_mdl->update(array('id' => $insert_id));
            }
            $response['info'] = '保存成功';
        }

        $response['status'] = 'y';
        echo json_encode($response);
    }

    // 图文素材删除
    public function newsdel()
    {
        $id = $this->input->post('id');

        $this->general_mdl->setTable('wechat_msg_news');
        $response['success'] = false;

        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;
        
        echo json_encode($response);
    }

    // 微信会员管理页
    public function members()
    {

        checkPermission('wechat_admin');

        $this->load->library('stt_access');

        $this->general_mdl->setTable('wechat_member_bind');
        $query = $this->general_mdl->get_query();
        $this->data['result'] = $query->result_array();

        $this->data['title'] = "微信会员管理";
        $this->load->view('wechat/members', $this->data);
    }

    public function members_edit()
    {
        checkPermission('wechat_admin');
        
        $id = $this->input->post('id');
        $this->data['action'] = $this->input->post('action');

        $this->general_mdl->setTable('wechat_member_bind');
        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $this->data['row'] = $query->row_array();

        if($this->data['action'] == "bind"){
            $this->load->view('wechat/member_bind', $this->data);
        }else{
            $this->load->view('wechat/members_edit', $this->data);
        }
    }

    public function member_save()
    {
        checkPermission('wechat_admin');

        $id = $this->input->post('id');
        $post_data = $this->input->post(NULL, TRUE);

        $this->general_mdl->setTable('wechat_member_bind');
        if($id) { //当有id是为修改记录
            $this->general_mdl->setData($post_data);
            $this->general_mdl->update(array('id' => $post_data['id']));
            $response['info'] = '修改完成';
        }else{
            $this->general_mdl->setData($post_data);
            $insert_id = $this->general_mdl->create();
            $response['info'] = '保存成功';
        }

        $response['status'] = 'y';
        echo json_encode($response);
    }

    public function member_bind()
    {
        checkPermission('wechat_admin');

        $this->load->library('stt_access');
        
        $id = $this->input->post('id');
        $cardNo = $this->input->post('cardNo');

        $response['status'] = 'n';
        $response['info'] = '绑定失败';

        $this->general_mdl->setTable('wechat_member_bind');
        if($id && $cardNo) { //当有id是为修改记录
            $query = $this->general_mdl->get_query_by_where( array('cardNo' => $cardNo) );
            if($query->num_rows()){            
                $response['info'] = '此卡号已经绑定';
            }else{
                $stt_response = $this->stt_access->get_members($cardNo);
                if($stt_response and $stt_response->leaguer){
                    $post_data['cardNo'] = $cardNo;
                    $this->general_mdl->setData($post_data);
                    $this->general_mdl->update(array('id' => $id));
                    $response['status'] = 'y';
                    $response['info'] = '绑定成功';
                }else{
                    $response['info'] = '查无些卡号';                    
                }
            }
        }

        echo json_encode($response);
    }

    public function members_unbind()
    {
        checkPermission('wechat_admin');

        $id = $this->input->post('id');
        $response['status'] = 'n';

        $this->general_mdl->setTable('wechat_member_bind');
        if($id) {
            $post_data['cardNo'] = "";
            $this->general_mdl->setData($post_data);
            $this->general_mdl->update(array('id' => $id));
            $response['status'] = 'y';
        }

        echo json_encode($response);
    }

    public function members_del()
    {
       $id = $this->input->post('id');

       $this->general_mdl->setTable('wechat_member_bind');
       $response['success'] = false;

       $this->general_mdl->delete_by_id($id);
       $response['success'] = true;
       
       echo json_encode($response);
    }

    // 查询自定义菜单
    public function get_menu(){
        $token = $this->session->userdata('wechat_access_token');
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s', $token);

        $this->curl->create($url);
        $this->curl->ssl(FALSE, FALSE);
        $this->curl->get();
        $output = $this->curl->execute();
        html_print(json_decode($output));
    }

    // 创建自定义菜单
    public function create_menu()
    {   
        $token = $this->session->userdata('wechat_access_token');
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s', $token);


        $bind_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        $bind_url = sprintf($bind_url, $this->appid, base_url('wechat_handle/member_bind'));

        $data['button'] = array();

        $data['button'][] = array(
            'name' => urlencode('点餐预订'),
            'sub_button' => array(
                array(
                    'type' => 'click',
                    'name' => urlencode('电子菜单'),
                    'key' => 'news_1'
                ),
                array(
                    'type' => 'click',
                    'name' => urlencode('用餐订位'),
                    'key' => 'news_1'
                )
            )
        );
        $data['button'][] = array(
            'name' => urlencode('个人中心'),
            'sub_button' => array(
                array(
                    'type' => 'view',
                    'name' => urlencode('会员卡'),
                    'url' => $bind_url
                ),
                array(
                    'type' => 'click',
                    'name' => urlencode('奄尖试食团'),
                    'key' => 'news_1'
                )
            )
        );

        $data['button'][] = array(
            'name' => urlencode('奄尖大少'),
            'sub_button' => array(
                array(
                    'type' => 'click',
                    'name' => urlencode('顺德文化'),
                    'key' => 'news_1'
                ),
                array(
                    'type' => 'click',
                    'name' => urlencode('品牌故事'),
                    'key' => 'news_1'
                ),
                array(
                    'type' => 'click',
                    'name' => urlencode('附近门店'),
                    'key' => 'news_13'
                ),
                array(
                    'type' => 'click',
                    'name' => urlencode('优惠活动'),
                    'key' => 'news_15'
                )
            )
        );

        $json_menu = urldecode(json_encode($data));
        $this->curl->create($url);
        $this->curl->ssl(FALSE, FALSE);
        $this->curl->post($json_menu);
        $output = $this->curl->execute();
        var_dump($output);
    }


    //重新获取access token
    public function reset_access_token(){
        echo json_encode($this->get_access_token());
    }

    //获取access token
    private function get_access_token()
    {
        $url = sprintf(
            'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s', 
            $this->appid, 
            $this->secret
        );

        $response = json_decode($this->curl_tool->get($url, '', ''));
        if(isset($response->access_token)) {
            $this->session->set_userdata('wechat_access_token', $response->access_token);
        }
        return $response;
    }

    //获取关注者列表
    private function get_users($token, $next_openid = '')
    {
        $url_template = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=%s';

        if($next_openid) $url_template .= '&next_openid=%s';

        $url = sprintf($url_template, $token, $next_openid);
        return json_decode($this->curl_tool->get($url, '', ''));
    }

    //发送文本消息
    private function _sendText($url, $openid, $content)
    {
        $params_array = array(
            "touser" => $openid,
            "msgtype" => "text",
            "text" => array("content" => urlencode($content))
        );

        $params = urldecode(json_encode($params_array));
        return $this->curl_ssl_post($url, $params);
    }

    //发送单图文消息
    private function _sendNews($url, $openid, $post)
    {
        $params_array = array(
            "touser" => $openid,
            "msgtype" => "news",
            "news" => ""
        );
        $params_array['news']['articles'][] = array(
            "title" => urlencode($post['title']),
            "description" => urlencode($post['description']),
            "url" => $post['url'],
            "picurl" => get_image_url($post['picurl'])
        );
        $params = urldecode(json_encode($params_array));
        return $this->curl_ssl_post($url, $params);
    }

    private function curl_ssl_post($url, $params = array())
    {
        $_options = array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE);
        $this->curl->create($url);
        $this->curl->post($params, $_options);
        $output = $this->curl->execute();
        return $output;
    }

    private function curl_ssl_simple_call($url, $params = array())
    {
        $_options = array(CURLOPT_SSL_VERIFYPEER => FALSE, CURLOPT_SSL_VERIFYHOST => FALSE);
        $this->curl->options($_options);
        $output_string = $this->curl->_simple_call("get", $url, $params);
        return $output_string;
    }
}

/* End of file wechat.php */
/* Location: ./application/controllers/wechat.php */
