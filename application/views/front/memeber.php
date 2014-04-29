<body>
<?php $this->load->view('front/nav'); ?>
<div class="page-container">
    <?php $this->load->view('front/breadcrumbs'); ?>
    <div class="row margin-bottom-20"></div>
    <div class="container min-hight margin-bottom-40">
        <div class="row">
            <div class="col-xs-3 col-md-3">
                <ul class="ver-inline-menu tabbable margin-bottom-10">
                    <li class=""><a href="#tab_1" data-toggle="tab">基础资料</a></li>
                    <li class="active"><a href="#tab_2" data-toggle="tab">购买记录</a></li>
                    <li class=""><a href="#tab_3" data-toggle="tab">充值中心</a></li>
                    <li class=""><a href="#tab_4" data-toggle="tab">密码修改</a></li>
                </ul>        
            </div>
            <div class="col-xs-9 col-md-9">
                <div class="tab-content">
                    <div class="tab-pane" id="tab_1">
                        <?php $this->load->view('front/member_profile'); ?>
                    </div>
                    <div class="tab-pane active" id="tab_2">
                        <?php $this->load->view('front/member_buy_logs'); ?>
                    </div>
                    <div class="tab-pane" id="tab_3"></div>
                    <div class="tab-pane" id="tab_4">
                        <?php $this->load->view('front/member_reset_password'); ?>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
</body>
</html>