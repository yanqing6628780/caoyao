<body>
<?php $this->load->view('front/nav'); ?>
<div class="page-container">
	<div class="row margin-bottom-40"></div>
	<div class="container">
		<div class="row search-form-default">
		    <form id="search" action="<?=site_url('home/search') ?>" class="form-inline">
		        <div class="row">
					<div class="col-md-2">					
						<select name="table" class="form-control">
						<?php foreach ($categories as $key => $value): ?>
						<option value="<?=$value['table']?>" ><?=$value['name']?></option>
						<?php endforeach ?>
						</select>
					</div>
					<div class="col-md-10">
						<div class="input-group">
						    <div class="input-cont">   
						       <input name="q" type="text" class="form-control" datatype="*" nullmsg="请填写关键词" placeholder="关键词..." >
						    </div>
						    <span class="input-group-btn">
						    	<button type="submit" class="btn btn-info">搜索 <i class="icon-search"></i></button>
						    </span>    
						</div>
					</div>
		        </div>
		    </form>
		</div>
	</div>
</div>
<?php $this->load->view('front/footer'); ?>
<script type="text/javascript">
$(function () {
	var form = $("#search").Validform({
	    tiptype:1,
	    ajaxPost:false,
	    callback:function(data){

	    }
	});
})
</script>
</body>
</html>