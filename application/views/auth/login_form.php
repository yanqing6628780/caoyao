<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?php echo base_url()?>css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url()?>css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="<?php echo base_url()?>css/unicorn.login.css" />
<link rel="stylesheet" href="css/uniform.css" />    
</head>
<?php
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 30,
	'value' => set_value('username')
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30
);

$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0'
);

$confirmation_code = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
    'style' => 'text-transform:uppercase;'
);

?>
<body>
<div id="logo">
    <img src="img/logo.png" alt="" />
</div>
<div class="login_box" id="loginbox">            
    <form id="loginform" class="form-vertical" method="post" action="<?php echo site_url('admin/auth')?>" />
        <p></p>
        <div class="control-group <?php if(form_error($username['name'])){echo 'error';} ?>">
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-user"></i></span><input type="text" name="username" placeholder="用户名" />
                    <label generated="true" class="help-block"><?php echo form_error($username['name']); ?></label>
                </div>
            </div>
        </div>
        <div class="control-group <?php if(form_error($password['name'])){echo 'error';}?>">
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-lock"></i></span><input type="password" name="password" placeholder="密码" />
                    <label generated="true" class="help-block"><?php echo form_error($password['name']);?></label>
                </div>
            </div>
        </div>
        <?php if ($show_captcha): ?>
        <div class="control-group <?php if(form_error($confirmation_code['name'])){echo 'error';}?>">
            <div class="controls">
                <div class="input-prepend">
                    <?php echo $this->dx_auth->get_captcha_image(); ?>
                </div>
            </div>
        </div>
        <div class="control-group <?php if(form_error($confirmation_code['name'])){echo 'error';}?>">
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-lock"></i></span><input type="text" id="captcha" name="captcha" placeholder="请输入显示的代码;代码没有零。" style="text-transform:uppercase;"/>
                    <label generated="true" class="help-block"><?php echo form_error($confirmation_code['name']); ?></label>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-actions">                
            <span class="pull-right"><input type="submit" class="btn btn-inverse" value="登录" /></span>
        </div>
    </form>
</div>
<script src="js/jquery.min.js"></script>  
<script src="js/unicorn.login.js"></script>
<script src="js/jquery.validate.js"></script>
</body>
</html>
