<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 space-mobile">
                <!-- BEGIN ABOUT -->                    
                <h2>关于我们</h2>
                <p class="margin-bottom-30">Vivamus imperdiet felis consectetur onec eget orci adipiscing nunc. Pellentesque fermentum, ante ac interdum ullamcorper.</p>                   
                <!-- END ABOUT -->                                   
            </div>
            <div class="col-md-4 col-sm-4 space-mobile">
                <!-- BEGIN CONTACTS -->                                    
                <h2>联系我们</h2>
                <address class="margin-bottom-40">
                    Loop, Inc. <br>
                    795 Park Ave, Suite 120 <br>
                    San Francisco, CA 94107 <br>
                    P: (234) 145-1810 <br>
                    Email: <a href="mailto:info@keenthemes.com">info@keenthemes.com</a>                        
                </address>
                <!-- END CONTACTS -->                                    
            </div>
            <div class="col-md-4 col-sm-4">
                <!-- BEGIN TWITTER BLOCK -->                                                    
                <h2>最新微博</h2>
                <dl class="dl-horizontal f-twitter">
                    <dt><i class="icon-weibo"></i></dt>
                    <dd>
                        <a href="#">@keenthemes</a>
                        Imperdiet condimentum diam dolor lorem sit consectetur adipiscing
                        <span>3 min ago</span>
                    </dd>
                </dl>                    
                <dl class="dl-horizontal f-twitter">
                    <dt><i class="icon-weibo"></i></dt>
                    <dd>
                        <a href="#">@keenthemes</a>
                        Sequat ipsum dolor onec eget orci fermentum condimentum lorem sit consectetur adipiscing
                        <span>8 min ago</span>
                    </dd>
                </dl>                    
                <dl class="dl-horizontal f-twitter">
                    <dt><i class="icon-weibo"></i></dt>
                    <dd>
                        <a href="#">@keenthemes</a>
                        Remonde sequat ipsum dolor lorem sit consectetur adipiscing
                        <span>12 min ago</span>
                    </dd>
                </dl>                    
                <dl class="dl-horizontal f-twitter">
                    <dt><i class="icon-weibo"></i></dt>
                    <dd>
                        <a href="#">@keenthemes</a>
                        Pilsum dolor lorem sit consectetur adipiscing orem sequat
                        <span>16 min ago</span>
                    </dd>
                </dl>                    
                <!-- END TWITTER BLOCK -->                                                                        
            </div>
        </div>
    </div>
</div>
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-8">
                <p>
                    <span class="margin-right-10">2013 &copy; 顺企通. 版权所有.</span> 
                    <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
                </p>
            </div>
            <div class="col-md-4 col-sm-4">
                <ul class="social-footer">
                    <li><a href="#"><i class="icon-weibo"></i></a></li>
                </ul>                
            </div>
        </div>
    </div>
</div>
<div id='msgbox1'></div>
<div id='msgbox2'></div>
<div id='alert-win'></div>
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
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
<script type="text/javascript" src="<?=base_url('js/api.js')?>"></script>
<script type="text/javascript" src="<?=base_url('js/validform_v5.3.2.js')?>"></script>
