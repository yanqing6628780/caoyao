<html>
	<head><title>Manage unactivated users</title></head>
	<body>
	<?php  				
		// Show error
		echo validation_errors();
		
		$this->table->set_heading('', 'Username', 'Email', 'Register IP', 'Activation Key', 'Created');
		
		foreach ($users as $user) 
		{
			$this->table->add_row(
				form_checkbox('checkbox_'.$user->id, $user->username).form_hidden('key_'.$user->id, $user->activation_key),
				$user->username, 
				$user->email, 
				$user->last_ip, 				
				$user->activation_key, 
				date('Y-m-d', strtotime($user->created)));
		}
		
		echo form_open($this->uri->uri_string());
				
		echo form_submit('activate', '激活用户');
		
		echo '<hr/>';
		
		echo $this->table->generate(); 
		
		echo form_close();
		
		echo $pagination;
        echo '<a href='.base_url().'>返回首页</a>'
	?>
	</body>
</html>