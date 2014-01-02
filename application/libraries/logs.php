<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logs
{
	/**
    * CI句柄
    * 
    * @access private
    * @var object
    */
	private $_CI;
    /**
    * logs_mdl模型
    * 
    * @access private
    * @var object
    */
	private $_LogsMdl;
    
	private $log_time;//操作时间
	private $user_id;//操作人ID
	private $log_info;//操作内容
	private $ip_address;//操作人IP地址
	private $id;//操作记录的ID号
    
    private $Fields = array();
    
    public function __construct()
    {
        /** 获取CI句柄 */
		$this->_CI = & get_instance();
        
        $this->_CI->load->model('general_mdl');
        $this->_CI->load->model('logs_mdl');
        $this->_LogsMdl = $this->_CI->logs_mdl;
    }
    
    //设置操作人id
    public function setUserId($userid){
        $this->user_id = $userid;
        $this->Fields['user_id'] = $userid;
    }
    
    //设置操作内容
    public function setLogInfo($loginfo){
        $this->log_info = $loginfo;
        $this->Fields['log_info'] = $loginfo;
    }
    
    //设置操作记录的ID号
    public function setId($id){
        $this->id = $id;
        $this->Fields['id'] = $id;
    }
    
    //设置操作人IP地址
    public function setIp($ipaddress){
        $this->ip_address = $ipaddress;
        $this->Fields['ip_address'] = $ipaddress;
    }
    
    //创建操作记录
    public function create_log()
    {
        $this->_LogsMdl->setData($this->Fields);
        $this->_LogsMdl->create();
    }
}
?>