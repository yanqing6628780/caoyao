<body>
<div class="container">
    <div class="row">
        <div class="col-xs-offset-2 col-xs-6 col-md-offset-3 col-md-5">
            <form class="form-horizontal" method="post" action="<?=site_url('/login')?>" role="form">
                <div class="form-group">
                    <label for="login" class="col-xs-3 col-md-3 control-label">用户名</label>
                    <div class="col-xs-9 col-md-9">
                        <input class="form-control" type="text" id="login" placeholder="username" name="login">
                        <span class="help-block"><?php echo form_error('login'); ?><?php echo isset($errors['login'])?$errors['login']:''; ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-xs-3 col-md-3 control-label">密码</label>
                    <div class="col-xs-9 col-md-9">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <span class="help-block"><?php echo form_error('password'); ?><?php echo isset($errors['password'])?$errors['password']:''; ?></span>
                    </div>
                </div>
                <?php if ($show_captcha): ?>
                <div class="form-group">
                    <label for="captcha" class="col-xs-2 col-md-2 control-label">验证码</label>
                    <div class="col-xs-10 col-md-10">
                        <input class="form-control" class="captcha" type="text" maxlength="8" id="captcha" placeholder="captcha" name="captcha">
                        <span class="help-block"><?php echo $captcha_html; ?></span>
                        <span class="help-block"><?php echo form_error('password'); ?><?php echo form_error('captcha') ? form_error('captcha') : ''; ?></span>
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <div class="col-xs-offset-3 col-xs-9">
                        <button type="submit" class="btn btn-default">登录</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
</body>
</html>
