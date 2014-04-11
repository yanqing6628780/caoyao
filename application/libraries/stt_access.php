<?php
class Stt_access
{
	private $_ci;

	private $access_username;
	private $access_psw;
	public $master_server_domain;
	public $branch_server_domain;
	public $errno;
	public $error_string;
	public $info;

	function __construct()
	{
		$this->_ci =& get_instance();

        $this->_ci->load->library('logger'); //日志类
		$this->_ci->load->library('curl'); //日志类

		$this->access_username = 'sqt';		
		$this->access_psw = 'YWaWMTIzNA';		

		$this->master_server_domain = 'http://192.168.0.136:8168';
		$this->branch_server_domain = 'http://192.168.0.136:8168';
	}

	//获取会员资料
	public function get_members($value = '')
	{
		$url = $this->master_server_domain."/stt_access/get_leaguer";
		$post_data = array();
		if($value){
			$post_data['filter'] = json_encode( array('vch_memberno' => $value, 'vch_tel' => $value) );
		}else{
			return FALSE;
		}
		$response = $this->execute($url, $post_data);
		if($response and $response->status){
			return $response;
		}else{
			return FALSE;
		}
	}	

	//获取会员积分
	public function get_members_credit($value = '')
	{
		$url = $this->master_server_domain."/stt_access/get_leaguer_credit";
		$post_data = array();
		if($value){
			$post_data['vch_memberno'] = $value;
		}else{
			return FALSE;
		}
		$response = $this->execute($url, $post_data);
		if($response and $response->status){
			return $response;
		}else{
			return FALSE;
		}
	}	

	//获取菜品规格
	public function get_dish_spec($value = '')
	{
		$url = $this->master_server_domain."/stt_access/get_leaguer_credit";
		$post_data = array();
		if($value){
			$post_data['vch_memberno'] = $value;
		}else{
			return FALSE;
		}
		$response = $this->execute($url, $post_data);
		if($response and $response->status){
			return $response;
		}else{
			return FALSE;
		}
	}

	public function order_write($url, $tables, $order, $type)
	{
		$posturl = $url."/stt_access/order_write";

		// 格式化数据
		foreach ($tables as $key => $value) {
			$table_arr[$key]['ch_tableno'] = $value;
		}

		$order_write_rep = array(
			'table' => $table_arr,
			'order' => $order,
			'write_type' => $type,
			'paid' => $order['is_paid'] ? TRUE : FALSE,
		);
		$post_data['order_write_rep'] = json_encode($order_write_rep);

		$response = $this->execute($posturl, $post_data);
		
		// echo $url;
		// echo $posturl;
		// var_dump($this->_ci->curl->error_code);
		// var_dump($this->_ci->curl->error_string);
		// var_dump($this->_ci->curl->info);
		// var_dump($response);
		// die();
		return $response;
	}

	// 执行
	public function execute($url, $post_data)
	{

		$this->_ci->curl->create($url);
		$this->_ci->curl->http_login($this->access_username, $this->access_psw, 'basic');
		$this->_ci->curl->post($post_data);

		//发起HTTP请求
		try {
            $resp = $this->_ci->curl->execute();
		} catch (Exception $e) {
			$result['code'] = $e->getCode();
			$result['msg'] = $e->getMessage();
			log_message('error', 'cURL 执行错误 错误码:'.$e->getCode()." 错误信息: ".$e->getMessage());
			return FALSE;
		}
		if($this->_ci->curl->error_code){
			$result['error'] = 'cURL 执行错误 错误码:'.$this->_ci->curl->error_code;
			$result['status'] = FALSE;
			return (object)$result;
		}

		$this->errno = $this->_ci->curl->error_code;
		$this->error_string = $this->_ci->curl->error_string;
		$this->info = $this->_ci->curl->info;

		//解析返回结果
		$respObject = json_decode($resp);

		return $respObject;
	}
}
