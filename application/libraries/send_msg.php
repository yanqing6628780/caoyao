<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class send_msg
{
	/**
    * CI句柄
    * 
    * @access private
    * @var object
    */
	private $_CI;
    /**
    * message_mdl模型
    * 
    * @access private
    * @var object
    */
	private $_MsgMdl;
    
	private $form_id;//发送人ID
	private $to_id;//接收人ID
	private $title;//信息标题
	private $msg;//信息内容
	private $id;//信息ID
    
    private $Fields = array();
    
    public function __construct()
    {
        /** 获取CI句柄 */
		$this->_CI = & get_instance();
        
        $this->_CI->load->model('message_mdl', 'msg_mdl');
        $this->_MsgMdl = $this->_CI->msg_mdl;
    }
    
    //设置发送人id
    public function setFormId($fromid){
        $this->form_id = $fromid;
        $this->Fields['from_id'] = $fromid;
    }
    
    //设置接收人id
    public function setToId($toid){
        $this->to_id = $toid;
        $this->Fields['to_id'] = $toid;
    }
    
    //设置信息标题
    public function setTitleId($title){
        $this->title = $title;
        $this->Fields['title'] = $title;
    }
    
    //设置信息内容
    public function setMsg($msg){
        $this->msg = $msg;
        $this->Fields['message'] = $msg;
    }
    
    //设置信息id
    public function setId($id){
        $this->id = $id;
    }
    
    //发送信息
    /*
    ** 保存到数据库
    **
    */
    public function send_message()
    {
        $this->_MsgMdl->setData($this->Fields);
        return $this->_MsgMdl->create();
    }
    
    //发送给多个人
    public function send_multi_messages()
    {
         $toArray = split ('[,;]', $this->to_id);
         foreach($toArray as $row){
            $this->setToId($row);
            $msgIdArray[] = $this->send_message();
         }
         return $msgIdArray;
    }
    
    //删除信息
    public function del_message()
    {        
        return $this->_MsgMdl->delete_by_id($this->id);
    }
    
    //删除多条信息
    public function del_multi_messages()
    {
         $idArray = split ('[,;]', $this->id);
         foreach($idArray as $row){
            $this->setId($row);
            $Array[] = $this->del_message();
         }
         return $Array;
    }
}
?>