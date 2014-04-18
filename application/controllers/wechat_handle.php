<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class wechat_handle extends CI_Controller
{

    var $FromUserName = null;
    var $ToUserName = null;
    var $wechat_config = array();

    function __construct()
    {
        parent::__construct();
        $this->load->library("logger");
        
        $this->general_mdl->setTable('sys_config');
        $result = $this->general_mdl->get_query_by_where(array('cat' => 'wechat'))->result_array();

        foreach($result as $item){
            $this->wechat_config[$item['name']] = $item['value'];
        }
    }

    public function index()
    {
        $this->message();
    }

    public function member_bind()
    {
        $this->load->library("curl");

        $code = $this->input->get('code');

        $this->general_mdl->setTable('sys_config');
        $config = $this->general_mdl->get_query_by_where(array('cat' => 'wechat'))->result_array();
        $appid = $config[0]['value'];
        $secret = $config[1]['value'];

        if($code){        
            //获取用户
            $url_template = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
            $url = sprintf($url_template, $appid, $secret, $code);
            $this->curl->create($url);
            $this->curl->ssl(FALSE,FALSE);
            $response = json_decode($this->curl->execute());

            if( isset($response->openid) ){
                $redirect_url = base_url('wechat/bind.html?openid='.$response->openid);
                redirect($redirect_url);
            }else{
                echo $response->errmsg;
            }
        }else{
            show_404();
        }
    }

    // 在微信平台上设置的对外 URL
    public function message()
    {
        if ($this->_valid())
        {
            // 判读是不是只是验证
            $echostr = $this->input->get('echostr');
            if (!empty($echostr))
            {
                $this->load->view('valid_view', array('output' => $echostr));
            }
            else
            {
                // 实际处理用户消息
                $this->_responseMsg();
            }
        }
        else
        {
            $this->load->view('valid_view', array('output' => 'Error!'));
        }
    }

    // 用于接入验证
    private function _valid()
    {
        $token = isset($this->wechat_config['token']) ? $this->wechat_config['token'] : null;

        $signature = $this->input->get('signature');
        $timestamp = $this->input->get('timestamp');
        $nonce = $this->input->get('nonce');

        $tmp_arr = array($token, $timestamp, $nonce);
        sort($tmp_arr);
        $tmp_str = implode($tmp_arr);
        $tmp_str = sha1($tmp_str);

        return ($tmp_str == $signature);
    }

    // 这里是处理消息的地方，在这里拿到用户发送的字符串
    private function _responseMsg()
    {
        $post_str = $GLOBALS["HTTP_RAW_POST_DATA"];

        $this->logger->conf['log_file'] = "wechat_receive_logs.txt";
        $this->logger->log(array(date("Y-m-d H:i:s"),$post_str));

        if (!empty($post_str))
        {
            // 解析微信传过来的 XML 内容
            $post_obj = simplexml_load_string($post_str, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->FromUserName = $post_obj->FromUserName;
            $this->ToUserName = $post_obj->ToUserName;
            // $keyword 就是用户输入的内容
            $keyword = trim($post_obj->Content);

            // 分析消息类型，并分发给对应的函数
            switch ($post_obj->MsgType)
            {
              case 'event':
                switch ($post_obj->Event)
                {
                  case 'subscribe':
                    $this->onSubscribe();
                    break;

                  case 'unsubscribe':
                    $this->onUnsubscribe();
                    break;

                  case 'SCAN':
                    $this->onScan();
                    break;

                  case 'LOCATION':
                    $this->onEventLocation($post_obj);
                    break;

                  case 'CLICK':
                    $this->onClick($post_obj->EventKey);
                    break;
                }

                break;

              case 'text':
                $this->onText($keyword);
                break;

              case 'image':
                $this->onImage();
                break;

              case 'location':
                $this->onLocation($post_obj);
                break;

              case 'link':
                $this->onLink();
                break;

              case 'voice':
                $this->onVoice();
                break;

              default:
                $this->onUnknown();
                break;

            }

        }
        else
        {
            $this->load->view('valid_view', array('output' => 'Error!'));
        }
    }

    // 用户关注时触发
    private function onSubscribe()
    {
      $content = isset($this->wechat_config['subscribe']) ? $this->wechat_config['subscribe'] : "欢迎关注";
      $this->responseText($content);
    }

    // 收到文本消息时触发
    private function onText($keyword)
    {
      $this->_parseMessage($keyword);
    }

    // 收到菜单点击事件时触发
    public function onClick($eKey)
    {
        $event_arr = explode('_', $eKey);
        switch ($event_arr[0]) {
            case 'text':
                $this->responseText("感谢你的关注,功能正在建设中,请耐心等候.");                
                break;
            case 'news':
                $this->general_mdl->setTable('wechat_msg_news');
                $query = $this->general_mdl->get_query_by_where( array('id' => $event_arr[1]) );
                if($query->num_rows()){
                    $row = $query->row_array();
                    $articles[] = $this->setNewsArticleArr($row);
                    if($row['is_mult']){ // 当为多图文时
                        $query = $this->general_mdl->get_query_by_where( array('parent_id' => $row['id']) );
                        foreach ($query->result_array() as $key => $value) {
                            $articles[] = $this->setNewsArticleArr($value);
                        }
                    }

                    $this->responseNews($articles);
                }else{
                    $this->responseText("感谢你的关注,功能正在建设中,请耐心等候.".$event_arr[1]);
                }

                break;
            default:
                $this->responseText("感谢你的关注,功能正在建设中,请耐心等候.");
                break;
        }
    }

    //地理位置消息
    public function onLocation($post_obj)
    {
        $this->general_mdl->setTable('admin_users');
        $query = $this->general_mdl->get_query_by_where(array('role_id'=> 2));

        //将店铺资料拼成图文信息发送
        $result = array();
        foreach ($query->result_array() as $key => $value) {
            $this->general_mdl->setTable('admin_user_profile');
            $query = $this->general_mdl->get_query_by_where(array('user_id'=> $value['id']));
            $row = $query->row_array();
            $params_arr = array(
                'userLat' => $post_obj->Location_X,
                'userLng' => $post_obj->Location_Y,
                'precision' => $post_obj->Scale,
                'shopLat' => $row['lat'],
                'shopLng' => $row['lng'],
                'userId' => $value['id']
            );
            $params_str = http_build_query($params_arr);
            $arr = array(
                'title' => $row['name'],
                'description' => $row['address'],
                'picurl' => get_image_url($row['photo']),
                'url' => site_url("wechat/lbs.html?".$params_str)
            );
            $articles[] = $this->setNewsArticleArr($arr);
        }
        $this->responseNews($articles);
    }

    private function setNewsArticleArr($row) 
    {
        $arr = array(
            'Title' => $row['title'],
            'Description' => $row['description'],
            'PicUrl' => $row['picurl'],
            'Url' => $row['url']
        );
        return $arr;
    }

    //发送被动响应文本消息
    private function responseText($content)
    {
      $tpl = "<xml>
              <ToUserName><![CDATA[%s]]></ToUserName>
              <FromUserName><![CDATA[%s]]></FromUserName>
              <CreateTime>%s</CreateTime>
              <MsgType><![CDATA[%s]]></MsgType>
              <Content><![CDATA[%s]]></Content>
              <FuncFlag>0</FuncFlag>
              </xml>";
      $resultStr = sprintf($tpl, $this->FromUserName, $this->ToUserName, time(), "text", $content);
      $resultXML = preg_replace('/[\r|\t]/', '', $resultStr);
      echo $resultXML;
    }

    //发送被动响应图文消息
    private function responseNews($articles)
    {
        $item_tpl = "<item>
                  <Title><![CDATA[%s]]></Title>
                  <Description><![CDATA[%s]]></Description>
                  <PicUrl><![CDATA[%s]]></PicUrl>
                  <Url><![CDATA[%s]]></Url>
                  </item>";
        $articles_str = "";

        foreach ($articles as $key => $value) {
            $articles_str .= sprintf($item_tpl, $value['Title'], $value['Description'], $value['PicUrl'], $value['Url']);
        }
        $tpl = "<xml>
                  <ToUserName><![CDATA[%s]]></ToUserName>
                  <FromUserName><![CDATA[%s]]></FromUserName>
                  <CreateTime>%s</CreateTime>
                  <MsgType><![CDATA[%s]]></MsgType>
                  <ArticleCount>%s</ArticleCount>
                  <Articles>%s</Articles>
                  <FuncFlag>0</FuncFlag>
                  </xml>";
        $resultStr = sprintf(
            $tpl,
            $this->FromUserName, $this->ToUserName, time(), "news",
            count($articles), $articles_str
        );
        $resultXML = preg_replace('/[\r|\t]/', '', $resultStr);

        $this->logger->conf['log_file'] = "wechat_logs.txt";
        $logData = array(
            date("Y-m-d H:i:s"),
            $resultStr,
            $this->FromUserName,
            $this->ToUserName
        );
        $this->logger->log($logData);

        echo $resultXML;
    }


    // 解析用户输入的字符串
    private function _parseMessage($message)
    {
        $this->logger->conf['log_file'] = "wechat_logs.txt";

        $reply_row = array();
        $this->general_mdl->setTable('wechat_autoreply');
        $query = $this->general_mdl->get_query_by_where(array("keyword" => $message));
        if($query->num_rows()){
            $reply_row = $query->row_array();
        }

        // 记录发送日志
        $logData = array(
            date("Y-m-d H:i:s"),
            $message
        );

        if(!empty($reply_row)){
            switch ($reply_row['msgtype']) {
                case 'text':
                    $reply_data = json_decode($reply_row['reply_data']);

                    $logData[] = $message;
                    $logData[] = $reply_data->content;
                    $this->logger->log($logData);

                    $this->responseText($reply_data->content);
                    break;
                case 'news':
                    $news_id = $reply_row['reply_data'];

                    $this->general_mdl->setTable('wechat_msg_news');
                    $query = $this->general_mdl->get_query_by_where( array('id' => $news_id) );
                    if($query->num_rows()){
                        $row = $query->row_array();
                        $articles[] = $this->setNewsArticleArr($row);
                        if($row['is_mult']){ // 当为多图文时
                            $query = $this->general_mdl->get_query_by_where( array('parent_id' => $row['id']) );
                            foreach ($query->result_array() as $key => $value) {
                                $articles[] = $this->setNewsArticleArr($value);
                            }
                        }

                        $this->responseNews($articles);
                    }else{
                        $this->responseText("感谢你的关注,功能正在建设中,请耐心等候.".$event_arr[1]);
                    }
                    break;
            }
        }else{
            $this->responseText("收到的信息：".$message);
        }
        // TODO: 在这里做一些字符串解析，比如分析某关键字，返回什么信息等等
    }

}


/* End of file wechat.php */
/* Location: ./application/controllers/wechat.php */
