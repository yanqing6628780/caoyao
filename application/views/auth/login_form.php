<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- BEGIN GLOBAL MANDATORY STYLES -->          
<link href="<?=base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES --> 
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/select2/select2_metro.css" />
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES --> 
<link href="<?=base_url()?>assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?=base_url()?>assets/css/pages/login.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
  
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
<body class="login">
<!-- BEGIN LOGO -->
<div class="logo">
    <img src="<?=base_url()?>assets/img/logo-big.png" alt="" /> 
</div>
<!-- END LOGO -->
<div class="content">          
    <form id="loginform" class="login-form" method="post" action="<?php echo site_url('admin/auth')?>" />
        <h3 class="form-title">登录</h3>
        <div class="form-group <?php if(form_error($username['name'])){echo 'has-error';} ?>">
            <label class="control-label visible-ie8 visible-ie9">用户名</label>
            <div class="input-icon">
                <i class="icon-user"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="用户名" name="username"/>
            </div>
            <span class="help-block"><?php echo form_error($username['name']); ?></span>
        </div>
        <div class="form-group <?php if(form_error($username['name'])){echo 'has-error';} ?>">
            <label class="control-label visible-ie8 visible-ie9">密码</label>
            <div class="input-icon">
                <i class="icon-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" name="password"/>
            </div>
            <span class="help-block"><?php echo form_error($password['name']);?></span>
        </div>
        <?php if ($show_captcha): ?>
        <div class="form-group <?php if(form_error($confirmation_code['name'])){echo 'has-error';}?>">
            <div class="input-prepend">
                <?php echo $this->dx_auth->get_captcha_image(); ?>
            </div>
            <span class="help-block">请输入显示的代码;代码没有零。</span>
        </div>
        <div class="form-group <?php if(form_error($confirmation_code['name'])){echo 'has-error';}?>">
            <label class="control-label visible-ie8 visible-ie9">验证码</label>
            <div class="input-icon">
                <i class="icon-key"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="验证码" name="captcha"/>
            </div>            
            <span class="help-block"><?php echo form_error($confirmation_code['name']); ?></span>
        </div>
        <?php endif; ?>
        <div class="form-actions">
            <button type="submit" class="btn green pull-right">登录 <i class="m-icon-swapright m-icon-white"></i></button>            
        </div>
    </form>
</div>
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->   
<!--[if lt IE 9]>
<script src="<?=base_url()?>assets/plugins/respond.min.js"></script>
<script src="<?=base_url()?>assets/plugins/excanvas.min.js"></script> 
<![endif]-->   
<script src="<?=base_url()?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
<script src="<?=base_url()?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>  
<script src="<?=base_url()?>assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?=base_url()?>assets/plugins/select2/select2.min.js"></script>     
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?=base_url()?>assets/scripts/app.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/scripts/login.js" type="text/javascript"></script> 
<!-- END PAGE LEVEL SCRIPTS --> 
<script>
jQuery(document).ready(function() {     
    App.init();
});
</script>
<!-- END JAVASCRIPTS -->
</body>
</html>
