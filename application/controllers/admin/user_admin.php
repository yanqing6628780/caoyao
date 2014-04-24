<?php
class User_admin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		checkIsLoggedIn();

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');
        $this->load->model('dx_auth/roles', 'roles');
        $this->load->model('dx_auth/user_temp', 'user_temp');
        $this->load->model('dx_auth/permissions', 'permissions');

        $this->config->load('thumbimage', true);
        $this->load->library('pagination');
        $this->load->library('Form_validation');
        $this->load->helper('form');
	}

    public function index()
    {
        checkPermission('user_view');
        $this->users();
    }

//用户列表
    public function users()
    {
        checkPermission('user_view');

        $this->general_mdl->setTable('admin_users');
        $query = $this->general_mdl->get_query();
        $result = $query->result_array();

        foreach($result as $key => $row){
            $row_profile = $this->profile->get_profile($row['id'])->row();
            $row_role = $this->roles->get_role_by_id($row['role_id'])->row();

            $result[$key]['cnname'] = $row_profile->name;
            $result[$key]['mobile'] = $row_profile->mobile;
            $result[$key]['email'] = $row_profile->email;
            $result[$key]['photo'] = $row_profile->photo;
            $result[$key]['lat'] = $row_profile->lat;
            $result[$key]['lng'] = $row_profile->lng;
            $result[$key]['address'] = $row_profile->address;
            $result[$key]['website'] = $row_profile->website;

            $result[$key]['role'] = $row_role->cnname;

        }

        $data['title'] = '用户管理';
        $data['users'] = $result;
        $data['roles'] = $this->roles->get_all()->result_array();

        $this->load->view('admin_user/user_list', $data);
    }

    //检查用户名
    public function username_check()
    {
        $username = $this->input->post('param');
        $result = $this->dx_auth->is_username_available($username);
        if($result){
            $data['status'] = "y";
            $data['info'] = "用户名可以使用";
        }else{
            $data['status'] = "n";
            $data['info'] = "用户名已存在";            
        }
        echo json_encode($data);
    }

    //添加用户
    public function user_add()
    {
        checkPermission('user_edit');

        $data['roles'] = $this->roles->get_all()->result();

        //$this->load->view('admin/head');
        $this->load->view('admin_user/user_add', $data);
    }

    //保存添加用户
    public function user_add_save()
    {
        checkPermission('user_edit');

        $username = $this->input->post('username');
        $role_id = $this->input->post('role_id');
        $profile = $this->input->post('profile');

        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');

        $new_user = ($this->dx_auth->register($username, $password, '', $role_id));
        //保存用户profile
        $this->profile->set_profile($new_user['user_id'], $profile);

        $data['status'] = "y";
        $data['info'] = "添加用户成功,id:".$new_user['user_id'];

        echo json_encode($data);
    }

    //用户详细信息
    public function user_detail()
    {
        checkPermission('user_edit');

        $user_id = $this->input->post('user_id');

        $user = $this->users->get_user_by_id($user_id)->row_array();
        $user_profile = $this->profile->get_profile($user_id)->row_array();

        $data['user'] = $user;
        $data['user_profile'] = $user_profile;


        $this->load->view('admin_user/user_detail', $data);
    }

    //用户信息修改
    public function user_edit()
    {
        checkPermission('user_edit');

        $user_id = $this->input->post('user_id');

        $data['user_id'] = $user_id;
        $data['user'] = $this->users->get_user_by_id($user_id)->row();
        $data['profile'] = $this->profile->get_profile($user_id)->row();


		$data['roles'] = $this->roles->get_all()->result();

        $this->load->view('admin_user/user_edit', $data);
    }

    //用户信息修改
    public function user_edit_save()
    {
        checkPermission('user_edit');

        $data['success'] = false;
        $data['info'] = "n";
        $data['msg'] = '';
        $user_id = $this->input->post('user_id');
        $role_id = $this->input->post('role_id');
        $profile = $this->input->post('profile');

        if($role_id){
		  $this->users->set_role($user_id, $role_id);
        }

        $this->profile->set_profile($user_id, $profile);
        $data['success'] = true;
        $data['status'] = "y";
        $data['info'] = '修改成功';
        echo json_encode($data);
    }

    public function del_user()
    {
        checkPermission('user_edit');

        $data['success'] = false;
        $user_id = $this->input->post('id');

        $this->users->delete_user($user_id);
        $this->profile->delete_profile($user_id);

        $data['success'] = true;
    }

    //设置中心
    public function profile()
    {
        $user_id = $this->dx_auth->get_user_id();
        $data['user_id'] = $user_id;

        $data['user'] = $this->users->get_user_by_id($user_id)->row();
        $data['profile'] = $this->profile->get_profile($user_id)->row();

        $this->load->view('admin_user/profile', $data);
    }

    //设置中心资料保存
    public function profile_save()
    {
        $data['msg'] = '';

        $user_id = $this->dx_auth->get_user_id();
        $profile = $this->input->post('profile');

        $this->profile->set_profile($user_id, $profile);

        echo json_encode($profile);
    }

    //用户地理坐标设置页
    public function user_lbs()
    {
        $user_id = $this->input->get('user_id');
        $data['profile'] = $this->profile->get_profile($user_id)->row_array();
        $this->load->view('admin_user/user_lbs.php', $data);
    }

    //头像上传
    public function avatar_upload()
    {
        $data['msg'] = '';

        $thumbimage_config = $this->config->item('thumbimage');
        $widths = $thumbimage_config['thumb_width']; // 需要处理的图片宽度尺寸
        $heights = $thumbimage_config['thumb_height']; // 需要处理的图片高度尺寸

        $config['upload_path'] = './uploads';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '2048';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        $field_name = "files";
        if ( ! $this->upload->do_upload($field_name))
        {
            $data = array('error' => $this->upload->display_errors());
        } 
        else
        {
            $upload_data = $this->upload->data();
            $profile['photo'] = $data['file']['url'] = "uploads/".$upload_data['file_name'];
            $data['file']['width'] = $upload_data['image_width'];
            $data['file']['height'] = $upload_data['image_height'];

            //修改用户头像
            $user_id = $this->dx_auth->get_user_id();
            $old_profile = $this->profile->get_profile_field($user_id, 'photo')->row_array();
            if(is_file($old_profile['photo'])) unlink($old_profile['photo']); // 删除旧的照片
            $this->profile->set_profile($user_id, $profile);
        }

        echo json_encode($data);
    }

    //修改密码页
    public function change_password_view()
    {
        $this->load->view('admin_user/change_password');
    }

    //修改密码并保存
    public function change_password()
	{
        checkPermission('user_edit');

        $data['success'] = false;
        $data['msg'] = '';

        $val = $this->form_validation;
        $this->form_validation->set_message('matches', '密码不一致');
        // Set form validation
        $val->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
        $val->set_rules('new_password', 'New Password', 'trim|required|xss_clean|matches[confirm_new_password]');
        $val->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean');

        if($val->run() AND $this->dx_auth->change_password($val->set_value('old_password'), $val->set_value('new_password'))){
            $data['success'] = true;
        }else{
            if($this->dx_auth->get_auth_error()){
                $data['errors']['old_password'] = $this->dx_auth->get_auth_error();
            }
            if(form_error('new_password')){
                $data['errors']['new_password'] = form_error('new_password');
            }
            if(form_error('confirm_new_password')){
                $data['errors']['confirm_new_password'] = form_error('confirm_new_password');
            }
        }
		echo json_encode($data);
	}

    //角色管理
    public function roles()
    {
        checkPermission('role_edit');
        $cant_delete_ids = array(1,2);
        // If Add role button pressed
        if ($this->input->post('add'))
        {
            // Create role            
            $this->roles->create_role($this->input->post('role_name'), $this->input->post('role_parent'), $this->input->post('role_cnname'));
        }
        else if ($this->input->post('delete'))
        {               
            // Loop trough $_POST array and delete checked checkbox
            $ids = $this->input->post("checkbox");
            foreach ($ids as $key => $id)
            {
                // Delete role
                if(!in_array($id, $cant_delete_ids)){
                    $this->roles->delete_role($id);
                }
            }
        }

        // Get all roles from database
        $data['roles'] = $this->roles->get_all()->result();
        $data['title'] = "角色管理";

        // Load view
        $this->load->view('admin/head', $data);
        $this->load->view('admin/roles');
    }

    public function add_roles()
    {
        checkPermission('role_edit');
        // Create role            
        $insert_id = $this->roles->create_role($this->input->post('role_name'), $this->input->post('role_parent'), $this->input->post('role_cnname'));
    }   

    public function del_roles()
    {
        checkPermission('role_edit');

        $default_ids = array(1,2);
        $data['success'] = false;
        $ids = $this->input->post('checkbox');
        // Loop trough $_POST array and delete checked checkbox
        foreach ($ids as $key => $value)
        {
            // Delete role
            if( !in_array($value ,$default_ids) ){
                $this->roles->delete_role($value);
                $data['success'] = true;
            }else{
                $data['success'] = false;
            }
        }
        echo json_encode($data);
    }

    //角色权限设置
    public function permissions()
    {
        checkPermission('perm_admin');
        $role_id = $this->input->get_post('role') ? $this->input->get_post('role') : 1;

        $data['roles'] = $this->roles->get_all()->result();
        $data['current_role'] = $role_id;

        /*角色所拥有权限*/
        $user_perms_data = $this->permissions->get_permission_data($role_id);

        /*所有权限的数据*/
        $this->general_mdl->setTable('permissions_code');
        $query = $this->general_mdl->get_query_by_where(array('is_show' => 1));
        $perms = $query->result_array();
        // $this->config->load('permissions', true);
        // $permissions = $this->config->item('permissions');
        //$perms = getPermissionsArray();
        //print_r($perms);
        
        foreach($perms as $key => $row)
        {
            $perms[$key]['hasperm'] = FALSE;
            if($user_perms_data and array_key_exists($row['action_code'], $user_perms_data) and $user_perms_data[$row['action_code']])
            {
                $perms[$key]['hasperm'] = TRUE;      
            }
        }

        $data['title'] = '角色权限管理';
        $data['perms'] = $perms;

        $this->load->view('admin/head', $data);
        $this->load->view('admin/perm');
    }

    //角色权限保存
    public function perms_save()
    {
        checkPermission('perm_admin');
        
        $data['success'] = false;
        $perms = $this->input->post('perms');

        if($perms)
        {
            foreach($perms as $perm)
            {
                $perm_data[$perm] = 1;
            }
        }
        else
        {
            $this->general_mdl->setTable('permissions_code');
            $query = $this->general_mdl->get_query();

            $result = $query->result_array();
            foreach($result as $perm)
            {
                $perm_data[$perm['action_code']] = 0;
            }
        }

        $this->permissions->set_permission_data($this->input->post('role'), $perm_data);

        $data['success'] = true;
        $data['msg'] = '修改成功';
        echo json_encode($data);
    }

    public function system_config()
    {
        $this->general_mdl->setTable("system_config");
        $data['result'] = $this->general_mdl->get_query()->result_array();
        $this->load->view('admin/head');
        $this->load->view('manage/system_config', $data);
    }

    public function system_config_save()
    {
        $data = $this->input->post("data");

        $this->general_mdl->setTable("system_config");
        foreach ($data as $key => $value) {
            # code...
            $set_data = array("value" => $value);
            $this->general_mdl->setData($set_data);
            $where = array('name' => $key);
            $this->general_mdl->update($where);
        }

        $data['success'] = true;
        echo json_encode($data);
    }
}
?>
