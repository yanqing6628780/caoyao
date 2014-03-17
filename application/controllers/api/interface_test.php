<?php
class interface_test extends CI_Controller{

	public function __construct() {
	    parent::__construct();

        $this->load->library('curl_tool');
	    $this->load->library('curl');
	}

    function index() {
    	
        $http_header = array(
            'Authorization: Basic '.base64_encode("sqt:YWaWMTIzNA")
        );

        // $data = array('username' => 'admin', 'password' => '123456');
        // $url = site_url("/api/my_interface/users");
        $url = "http://192.168.0.136:8168/stt_access/get_leaguer";
        $data = array('filter' => '{"vch_memberno":"H1"}');
        $this->curl->create($url);
        $this->curl->http_login('sqt', 'YWaWMTIzNA', 'basic');
        $this->curl->post($data);
        var_dump($this->curl->execute());
        echo "<br>";
        // var_dump($this->curl->error_string);
        echo "<br>";
        // var_dump($this->curl->error_code);
        echo "<br>";
        // var_dump($this->curl->info);
        // var_dump($this->curl_tool->post($url, $http_header, $data));

    }

    function get_wx_token() {
    	$url = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s', 'wxff4248454f4747c2', '54ff252ebfbf896b0d2fb1c3783d0f79');
    	echo $url;
    	var_dump($this->curl_tool->get($url, '', ''));
    }

    public function create_menu()
    {	
    	$access_token = 'HT9BHYiiUhYxMZ41hMHBPU7dxUOzP33qzGY7C3rzT7dL0YTDBbv5L8uc20ju7gbxaDxhouixmlqY7Ah227yskQKnPw1XIzqVKMFmSTFtLQgbLWbmbg8Dwry95HJuidq3nm1b4wifXeJuTD4of1_paQ';
    	$url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s', $access_token);

    	$data['button'] = array();
    	$data['button'][] = array(
    		'type' => 'click',
    		'name' => 'test',
    		'key' => 'lottery'
		);
		$data['button'][] = array(
    		'type' => 'view',
    		'name' => '抽奖页',
    		'url' => 'http://192.168.0.136/ci/wx_lottery/rotate'
		);
		$data['button'][] = array(
    		'name' => '多级菜单',
    		'sub_button' => array(
    			array(
    				'type' => 'view',
    				'name' => '百度',
    				'url' => 'http://www.baidu.com'
				),
				array(
    				'type' => 'click',
    				'name' => '赞一下我们',
    				'key' => 'good'
				)
			)
		);
		// html_print($data);
		// echo json_encode($data);
    	var_dump( $this->curl_tool->post( $url, array(), json_encode($data, JSON_UNESCAPED_UNICODE) ) );
    }
}
