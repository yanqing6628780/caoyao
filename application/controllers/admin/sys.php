<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys extends CI_Controller {

    private $_data;

    function __construct()
    {
        parent::__construct();

        checkIsLoggedIn();

        $this->load->model('general_mdl');
        $this->_data['basetitle'] = '系统设置';
        $this->general_mdl->setTable('ci_system');
    }

	public function index()
	{
        checkRoles('admin');
        checkPermission('user_admin');
        $this->_data['system'] = $this->general_mdl->get_query()->row();

        $this->load->view('system', $this->_data);

	}

    public function save()
    {
        $tel_point = $this->input->post('tel_point');
        $shopex_url = $this->input->post('shopex_url');
        $array = array('tel_point' => $tel_point);
        $array = array('shopex_url' => $shopex_url);
        $where['id'] =  1;
        $this->general_mdl->setData($array);
        $this->general_mdl->update($where);

        $data['success'] = true;
        $data['msg'] = '更新成功';
        echo json_encode($data);
    }

    public function tree()
    {
        $json = array();
        $dxAuth = $this->dx_auth;

        $plane = array();
        $ticket = array();
        $admin = array();
        $mypanel = array();
        $course = array();
		$member = array();

        $plane = getTreeArray('机票管理', '', 'sys-tree', false, true);
		$ticket = getTreeArray('车票管理', '', 'sys-tree', false, true);
        $admin = getTreeArray('管理面板', '', 'admin-tree', false, true);
        $mypanel = getTreeArray('我的面板', '', 'my-tree', false, true);
		$member = getTreeArray('会员管理', '', 'member-tree', false, true);

        /*机票管理*/
        $plane['children'][] = getTreeArray('机票信息查询', 'admin/plane/query');
        $plane['children'][] = getTreeArray('机票订票信息', 'admin/plane/');


        /*车票管理*/
        $ticket['children'][] = getTreeArray('车票信息查询', 'admin/ticket/query');
        $ticket['children'][] = getTreeArray('车票订票信息', 'admin/ticket/');

        /*管理面板*/
        if(checkPermission2('role_view'))
        {
            $admin['children'][] = getTreeArray('角色管理', 'admin/manage/roles');
        }
        if(checkPermission2('user_view'))
        {
            $admin['children'][] = getTreeArray('用户管理', 'admin/user/users');
            $admin['children'][] = getTreeArray('系统设置', 'admin/manage/system_config');
        }
        if(checkPermission2('perm_admin'))
        {
            $admin['children'][] = getTreeArray('权限设置', 'admin/manage/permissions');
        }

        /*我的面板*/
        $mypanel['children'][] = getTreeArray('修改密码', 'admin/user/change_password_view');
        $mypanel['children'][] = getTreeArray('资料设置', 'admin/user/profile');

        /*会员管理*/
        if(checkPermission2('member_view'))
        {
            $member['children'][] = getTreeArray('会员管理', 'parent_info/');
        }



        $json[] = $plane;
        $json[] = $ticket;
        $json[] = $admin;
        $json[] = $mypanel;
        $json[] = $member;
        header( "Content-Type: application/json" );
        header( "Content-Length: ". strlen(json_encode($json)) );
        echo json_encode($json);
    }

    //信用额度不足警报
    public function credit_alert()
    {
        $this->load->model("members_mdl");

        $this->general_mdl->setTable("system_config");
        $credit_alert = $this->general_mdl->get_query_by_where(array("name" => "credit_alert"))->row();

        $members = $this->members_mdl->members();
        $data["msg"] = array();
        $data["has_msg"] = FALSE;
        foreach ($members['result'] as $key => $member)
        {
            if ( ($member['credit'] - $member['used_credit']) <= $credit_alert->value )
            {
                $data["msg"][] = sprintf("用户<span class='red'>%s</span>, 信用余额不足(<span class='red'>余:%s</span>),请及时结算订单或者提高信用额度", $member['username'], ($member['credit'] - $member['used_credit']));
                $data["has_msg"] = TRUE;
            }
        }

        echo json_encode($data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
