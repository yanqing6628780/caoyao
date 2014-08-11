<body class="page-header-fixed page-footer-fixed page-sidebar-fixed">
<?php echo $this->load->view('admin/header'); ?>
<!-- BEGIN CONTAINER -->
<div class="page-container" >
  <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
  <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
              <h4 class="modal-title">Modal title</h4>
           </div>
           <div class="modal-body">
              Widget settings form goes here
           </div>
           <div class="modal-footer">
              <button type="button" class="btn blue">Save changes</button>
              <button type="button" class="btn default" data-dismiss="modal">Close</button>
           </div>
        </div>
        <!-- /.modal-content -->
     </div>
     <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
  <!-- BEGIN SIDEBAR1 -->
  <div class="page-sidebar navbar-collapse collapse">
     <!-- BEGIN SIDEBAR MENU1 -->         
     <ul class="page-sidebar-menu">
        <li>
           <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
           <div class="sidebar-toggler hidden-xs"></div>
           <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        </li>
        <li>
			<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->                     
			<form class="sidebar-search hidden" action="extra_search.html" method="POST">
			  <div class="form-container">
			     <div class="input-box">
			        <a href="javascript:;" class="remove"></a>
			        <input type="text" placeholder="Search..."/>
			        <input type="button" class="submit" value=" "/>
			     </div>
			  </div>
			</form>
           <!-- END RESPONSIVE QUICK SEARCH FORM -->
        </li>
        <?php echo $this->load->view('admin/left_menu'); ?>
     </ul>
     <!-- END SIDEBAR MENU1 -->
  </div>
  <!-- END SIDEBAR1 -->
  <!-- BEGIN PAGE -->
  <div class="page-content">
	<!-- BEGIN STYLE CUSTOMIZER -->
	<div class="theme-panel hidden hidden-xs hidden-sm">
		<div class="toggler"></div>
		<div class="toggler-close"></div>
		<div class="theme-options">
		   <div class="theme-option theme-colors clearfix">
		      <span>主题颜色</span>
		      <ul>
		         <li class="color-black current color-default" data-style="default"></li>
		         <li class="color-blue" data-style="blue"></li>
		         <li class="color-brown" data-style="brown"></li>
		         <li class="color-purple" data-style="purple"></li>
		         <li class="color-grey" data-style="grey"></li>
		         <li class="color-white color-light" data-style="light"></li>
		      </ul>
		   </div>
		   <div class="theme-option">
		      <span>布局</span>
		      <select class="layout-option form-control input-small">
		         <option value="fluid" selected="selected">Fluid</option>
		         <option value="boxed">Boxed</option>
		      </select>
		   </div>
		   <div class="theme-option">
		      <span>头部</span>
		      <select class="header-option form-control input-small">
		         <option value="fixed" selected="selected">Fixed</option>
		         <option value="default">Default</option>
		      </select>
		   </div>
		   <div class="theme-option">
		      <span>侧栏</span>
		      <select class="sidebar-option form-control input-small">
		         <option value="fixed">Fixed</option>
		         <option value="default" selected="selected">Default</option>
		      </select>
		   </div>
		   <div class="theme-option">
		      <span>底部</span>
		      <select class="footer-option form-control input-small">
		         <option value="fixed">Fixed</option>
		         <option value="default" selected="selected">Default</option>
		      </select>
		   </div>
		</div>
	</div>
	<!-- END BEGIN STYLE CUSTOMIZER -->
	<div class="page-content-body">
		<!-- HERE WILL BE LOADED AN AJAX CONTENT -->
	</div>
  </div>
</div>
<!-- END CONTAINER -->
<?php echo $this->load->view('admin/footer'); ?>
</body>
</html>
