<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/lib.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/front.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>jquery/jquery.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/tip-green.css" />

<script type="text/javascript" src="<?php echo base_url()?>jquery/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>jquery/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-ui-timepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>jquery/jquery.ui.datepicker-zh-CN.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.poshytip.js"></script>

<script type="text/javascript">
var BASEURL = '<?php echo base_url()?>'
function resetPassword()
{
    $("#msgbox1").empty()
    $("#msgbox1").dialog({"title": "重置密码",width:400})
    $("#msgbox1").dialog("open")
    LoadAjaxPage('member/reset_password', {}, 'msgbox1')
}
</script>
<script type="text/javascript" src="<?php echo base_url()?>js/api.js"></script>
</head>
<body>
<div class="warp ovh">
    <div id="header">
        <div id="logo">
            <img src="<?php echo base_url()?>/images/logo.jpg" />
        </div>
        <div id="nav">
            <?php if ($this->tank_auth->is_logged_in()) {?>
			<a class='r btn8 mr10 mt5' href='<?php echo site_url('login/logout')?>'>退出</a>
            <a class='r btn8 mr5 mt5' href='<?php echo site_url("home")?>'>返回</a>
			<a class='r btn8 mr5 mt5' href='javascript:resetPassword()'>修改密码</a>
			<?php }?>
        </div>
    </div>
</div>
