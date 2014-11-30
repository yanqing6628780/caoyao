<!-- BEGIN FOOTER -->
<div class="footer">
    <div class="footer-inner">2014 &copy; by <a href='www.mysqt.com'>Sam Leung</a> 版本:20141130 </div>
    <div class="footer-tools">
        <span class="go-top">
        <i class="icon-angle-up"></i>
        </span>
    </div>
</div>
<!-- END FOOTER -->
<div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible1="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn default">关闭</button>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->   
<!--[if lt IE 9]>
<script src="<?php echo base_url()?>assets/plugins/respond.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/excanvas.min.js"></script> 
<![endif]-->   
<script src="<?php echo base_url()?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?php echo base_url()?>assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>      
<script src="<?php echo base_url()?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
<script src="<?php echo base_url()?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>  
<script src="<?php echo base_url()?>assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
<!-- END CORE PLUGINS -->

<script src="<?=base_url()?>assets/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/select2.min.js"></script>
<script src="<?php echo base_url()?>js/validform_v5.3.2.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/sco.ajax.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/highcharts/highcharts.js"></script>

<script src="<?php echo base_url()?>assets/scripts/app.js"></script>
<script>
jQuery(document).ready(function() {    
   App.init();
});
var pageContentBody = $('.page-content .page-content-body');
</script>
<!-- END JAVASCRIPTS -->
</body>
</html>
