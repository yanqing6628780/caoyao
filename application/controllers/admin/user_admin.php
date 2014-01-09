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
        $data['msg'] = '';
        $user_id = $this->input->post('user_id');
        $role_id = $this->input->post('role_id');
        $profile = $this->input->post('profile');

		$this->users->set_role($user_id, $role_id);

        $this->profile->set_profile($user_id, $profile);
        $data['success'] = true;
        $data['msg'] = '修改成功';
        echo json_encode($data);
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
            $result[$key]['sex'] = $row_profile->sex;
            $result[$key]['email'] = $row_profile->email;
            $result[$key]['photo'] = $row_profile->photo;

            $result[$key]['role'] = $row_role->cnname;

        }

        $data['users'] = $result;
        $data['roles'] = $this->roles->get_all()->result_array();

        $this->load->view('admin/head', $data);
        $this->load->view('admin_user/user_list');
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
        $data['msg'] = '';
        $user_id = $this->dx_auth->get_user_id();
        $data['user_id'] = $user_id;
        $data['username'] = $this->dx_auth->get_username();

        $data['user_profile'] = $this->profile->get_profile($user_id)->row_array();

        $this->load->view('admin/head', $data);
        $this->load->view('admin_user/profile', $data);
    }

    //设置中心资料保存
    public function profile_save()
    {
        $data['msg'] = '';
        $thumbimage_config = $this->config->item('thumbimage');
        $widths = $thumbimage_config['thumb_width']; // 需要处理的图片宽度尺寸
        $heights = $thumbimage_config['thumb_height']; // 需要处理的图片高度尺寸

        $user_id = $this->input->post('user_id');
        $profile = $this->input->post('profile');

        //图片上传
        $upload_path = 'uploads/users';
        $config['upload_path'] = './'.$upload_path;
        $config['encrypt_name'] = true;
        $config['allowed_types'] = "*"; //CI的文件上传类型验证有bug 设置为*用自定义验证方法
        $config['max_size'] = '2048'; // 允许上传文件大小的最大值
        $config['max_width'] = '1024'; // 上传文件的宽度最大值
        $config['max_height'] = '1024'; // 上传文件的高度最大值

        $this->load->library('upload', $config);

        $field_name = 'imgfile';
        $allowed_type = "gif|jpg|png"; //允许上传的文件类型组

        if( $_FILES[$field_name]['name'] != '' )
        {
            $ext = getLowerExt($_FILES[$field_name]['name']); //文件上传扩展名

            //检查扩展名
            if( ! checkAllowedTypes($allowed_type, $ext) ){
                $data['msg'] .= '上传图片失败，错误:不允许上传的文件类型 ';
            }else{
                //上传文件
                if( ! $this->upload->do_upload($field_name) ){
                    $data['msg'] .= sprintf('上传图片失败，错误:%s '  ,$this->upload->display_errors('',''));
                }else{
                    $uploaded_data = $this->upload->data();

                    // 图片处理
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $uploaded_data['full_path']; //原图绝对路径
                    $config['create_thumb'] = TRUE;
                    $config['maintain_ratio'] = TRUE;

                    $this->load->library('image_lib');
                    //批量处理图片
                    foreach($widths as $key=>$row){
                        $config['width'] = $widths[$key];
                        $config['height'] = $heights[$key];
                        $config['thumb_marker'] = '_'.$row;
                        $this->image_lib->initialize($config);
                        if ( ! $this->image_lib->resize()){
                            $data['msg'] .= sprintf('图片处理失败，错误:%s '  ,$this->image_lib->display_errors('',''));
                        }else{
                            $this->image_lib->clear();
                        }
                    }

                    $profile['photo'] = sprintf('%s%s/%s%s', base_url(), $upload_path, $uploaded_data['raw_name'], $uploaded_data['file_ext']);
                }
            }
        }

        $this->profile->set_profile($user_id, $profile);

        redirect('admin/user/profile');
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

    public function roles()
    {
        checkPermission('role_view');

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
                $this->roles->delete_role($id);
            }
        }

        // Get all roles from database
        $data['roles'] = $this->roles->get_all()->result();

        // Load view
        $this->load->view('admin/head', $data);
        $this->load->view('admin/roles');
    }

    public function del_roles()
    {
        checkPermission('role_edit');

        $default_ids = array(1);
        $data['success'] = false;
        $ids = $this->input->post('id');
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
        $role_id = $this->input->post('role') ? $this->input->post('role') : 1;

        $data['roles'] = $this->roles->get_all()->result();
        $data['current_role'] = $role_id;

        /*角色所拥有权限*/
        $user_perms_data = $this->permissions->get_permission_data($role_id);

        /*所有权限的数据*/
        $this->general_mdl->setTable('permissions_code');
        $query = $this->general_mdl->get_query();
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
