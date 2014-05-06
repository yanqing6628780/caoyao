<body>
<?php $this->load->view('front/nav'); ?>
<div class="page-container">
	<?php $this->load->view('front/breadcrumbs'); ?>
	<div class="container">
		<div class="row search-form-default">
		    <form id="search" action="<?=site_url('home/search') ?>" class="form-inline">
		        <div class="row">
					<div class="col-md-2">					
						<select name="table" class="form-control">
						<?php foreach ($categories as $key => $value): ?>
						<option value="<?=$value['table']?>" <?=option_select($value['table'], $table) ?> ><?=$value['name']?></option>
						<?php endforeach ?>
						</select>
					</div>
					<div class="col-md-10">
						<div class="input-group">
						    <div class="input-cont">   
						       <input name="q" type="text" class="form-control" datatype="*" nullmsg="请填写关键词" placeholder="关键词..." value="<?=$q?>">
						    </div>
						    <span class="input-group-btn">
						    	<button type="submit" class="btn btn-info">搜索 <i class="icon-search"></i></button>
						    </span>    
						</div>
					</div>
		        </div>
		    </form>
		</div>
		<?php if($result): ?>
		<div class="row">
			<?php foreach ($result as $key => $value): ?>				
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<a href="<?=site_url('home/info/'.$table.'/'.$value['id']) ?>"><?=$value['company']?></a>
							<small><a class="search-link" href="#">价格:<?=$value['price']?></a></small>
							<small class="search-link pull-right"><a href="<?=site_url('home/info/'.$table.'/'.$value['id']) ?>">详细信息</a></small>
						</h3>
					</div>
					<div class="panel-body">
						类型:<?=$value['type']?><br>
						代理品牌:<?=$value['actingbrand'] ? $value['actingbrand'] : '无'?>
					</div>
				</div>
		    </div>
			<?php endforeach ?>
		</div>
		<?php else: ?>
		<div class="row">
			<div class="col-md-12 search-result-item">
		       <h4><a href="#">没有符合的结果</a></h4>
		    </div>
		</div>
		<?php endif; ?>
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