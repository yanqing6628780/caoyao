<div role="navigation" class="header navbar navbar-default lgx-navbar navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="index.html" class="navbar-brand logo-v1">
                <img alt="订货会" id="logoimg" src="">
            </a>
        </div>
        <div class="navbar-collapse collapse" style="height: 1px;">
            <ul class="nav navbar-nav">
                <!-- <li title="首页"><a href="<?=site_url('home')?>">首页</a></li> -->
            </ul>
            <div class="collapse navbar-collapse navbar-left">
                <a class="btn btn-danger navbar-btn" type="button"><span class="glyphicon glyphicon-list"></span>热销榜单</a>
                <!-- <a data-trigger="ajax" href="<?=site_url('cart')?>" data-target="#main" class="btn btn-success navbar-btn" type="button"><span class="glyphicon glyphicon-shopping-cart"></span></span>进入购物车</a> -->
                <a data-trigger="ajax" href="<?=site_url('orders/my')?>" data-target="#main" class="btn btn-info navbar-btn" type="button"><span class="glyphicon glyphicon-list-alt"></span>我的订单</a>                
            </div>
            <ul class="nav navbar-nav navbar-left">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?=$this->tank_auth->get_username() ?> <span class="caret"></span></a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="<?=site_url('login/logout')?>">登出</a></li>
                    </ul>
                </li>
            </ul>
            <form id="search" role="search" class="navbar-form navbar-right my-navbar-right">
                <div class="form-group">
                    <input name="barcode" type="text" placeholder="条码" class="form-control" onclick="select();">
                </div>
                <div class="form-group">
                    <input name="product_name" type="text" placeholder="快速查找" class="form-control" onclick="select();">
                </div>
                <button class="btn btn-default" type="submit">查找</button>
            </form>
        </div>
    </div>
</div>