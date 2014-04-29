<div role="navigation" class="header navbar navbar-default navbar-static-top">
<div class="front-topbar">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <ul class="list-unstyle inline">
                    <li><i class="icon-phone topbar-info-icon top-2"></i>Call us: <span>(1) 456 6717</span></li>
                    <li class="sep"><span>|</span></li>
                    <li><i class="icon-envelope-alt topbar-info-icon top-2"></i>Email: <span>info@keenthemes.com</span></li>
                </ul>
            </div>
            <div class="col-md-6 col-sm-6 topbar-social">
                <ul class="list-unstyled inline">
                    <li><a href="#"><i class="icon-weibo"></i></a></li>
                    <?php if($this->tank_auth->is_logged_in()): ?>
                    <li>欢迎你, <?=$this->tank_auth->get_username()?></li>
                    <li><a href="<?=site_url('member')?>">[会员中心]</a></li>
                    <li><a href="<?=site_url('login/logout')?>">[退出]</a></li>
                    <?php else: ?>
                    <li><a href="<?=site_url('login')?>">[登录]</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>        
</div>
    <div class="container">
        <div class="navbar-header">
            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="index.html" class="navbar-brand logo-v1">
                <img alt="涂料平台" id="logoimg" src="assets/img/logo_blue.png">
            </a>
        </div>
        <div class="navbar-collapse collapse" style="height: 1px;">
            <ul class="nav navbar-nav">
                <li title="首页"><a href="<?=site_url('home')?>">首页</a></li>
                <li title="搜索"><a href="<?=site_url('home/search')?>">搜索</a></li>
            </ul>
        </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
</div>