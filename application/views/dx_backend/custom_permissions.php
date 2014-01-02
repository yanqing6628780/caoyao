<?php
    // Build drop down menu
    foreach ($roles as $role)
    {
        $options[$role->id] = $role->name;
    }

    // Change allowed uri to string to be inserted in text area
    if ( ! empty($allowed_uri))
    {
        $allowed_uri = implode("\n", $allowed_uri);
    }
    
    if (empty($edit))
    {
        $edit = FALSE;
    }
        
    if (empty($delete))
    {
        $delete = FALSE;
    }
    
    // Build form
    echo form_open($this->uri->uri_string());
    
    echo form_label('角色', 'role_name_label');
    echo form_dropdown('role', $options); 
    echo form_submit('show', '显示权限'); 
    
    echo form_label('', 'uri_label');
            
    echo '<hr/>';
    
    echo form_checkbox('operater', '1', $operater);
    echo form_label('操作员', 'operater_label');
    echo '<br/>';
    
    echo form_checkbox('verify', '1', $verify);
    echo form_label('审核', 'verify_label');
    echo '<br/>';
    
    echo form_checkbox('adduser', '1', $adduser);
    echo form_label('添加用户', 'adduser_label');
    echo '<br/>';
                
    echo '<br/>';
    echo form_submit('save', 'Save Permissions');
    
    echo '<br/>';
    
    //echo '打开 '.anchor('auth/custom_permissions/').' 查看结果<br/>';
    echo '如果你改变了角色的权限，请重新登录';
    
    echo form_close();
        
?>