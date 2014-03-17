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
                        $this->general_mdl->setTable('msg_news');
                        $id = $this->general_mdl->get_query()->num_rows() + 1;
                        if( empty($post['url']) && !empty($post['news_content']) ) {
                            $post['url'] = base_url('wechat/article.html?id='.$id);
                        }
                        $create_data = array(
                            'id' => $id,
                            'title' => $post['title'],
                            'description' => $post['description'],
                            'url' => $post['url'],
                            'picurl' => get_image_url($post['picurl']),
                            'content' => $post['news_content']
                        );
                        $this->general_mdl->setData($create_data);
                        $this->general_mdl->create();
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
            'type' => 'click',
            'name' => 'test',
            'key' => 'lottery'
        );
        $data['button'][] = array(
            'type' => 'view',
            'name' => urlencode('抽奖页'),
            'url' => 'http://192.168.0.136/ci/wx_lottery/rotate'
        );
        $data['button'][] = array(
            'name' => urlencode('多级菜单'),
            'sub_button' => array(
                array(
                    'type' => 'view',
                    'name' => urlencode('百度'),
                    'url' => 'http://www.baidu.com'
                ),
                array(
                    'type' => 'view',
                    'name' => urlencode('会员绑定'),
                    'url' => $bind_url
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
