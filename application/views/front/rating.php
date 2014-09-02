<form id="rating_form">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">
		<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
	</button>
	<h4 class="modal-title" id="myModalLabel"><?=$row['product_name']?></h4>
</div>
<div class="modal-body">
	<div class="media">
		<a class="pull-left" href="#">
			<img width="200" src="<?=$row['picture'] ? $row['picture'] : 'http://placehold.it/300x200/999999'?>" alt="...">
		</a>
		<div class="media-body">
			<h4 class="media-heading">评分</h4>
			<div class="form-group">
				<div class="radio-inline">
			    <label>
					<input style="margin-top: 15px;" type="radio" name="score" value="1"> 
					<input value="1" type="number" class="rating" data-size="sm" data-readonly="true" data-show-clear="false" data-show-caption="false"/>
			    </label>
			    </div>
			    <div class="radio-inline">
			    <label>
			    	<input style="margin-top: 15px;" type="radio" name="score" value="2"> 
			    	<input value="2" type="number" class="rating" data-size="sm" data-readonly="true" data-show-clear="false" data-show-caption="false"/>
			    </label>
			    </div>
			    <div class="radio-inline">
			    <label>
			    	<input style="margin-top: 15px;" type="radio" name="score" value="3"> 
					<input value="3" type="number" class="rating" data-size="sm" data-readonly="true" data-show-clear="false" data-show-caption="false"/>
			    </label>
			    </div>
			    <div style="margin-left:0" class="radio-inline">
			    <label>
			    	<input style="margin-top: 15px;" type="radio" name="score" value="4"> 
					<input value="4" type="number" class="rating" data-size="sm" data-readonly="true" data-show-clear="false" data-show-caption="false"/>
			    </label>
			    </div>
			    <div class="radio-inline">
			    <label>
			    	<input checked style="margin-top: 15px;" type="radio" name="score" value="5"> 
					<input value="5" type="number" class="rating" data-size="sm" data-readonly="true" data-show-clear="false" data-show-caption="false"/>
			    </label>
			    </div>
			</div>
		</div>
	</div>
	<hr>
	<h4>评论</h4>
	<div class="form-group">
		<textarea class="form-control" name="comment_content" cols="30" rows="5"></textarea>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
	<button id="save_rating" type="button" class="btn btn-primary">评价</button>
	<input name="good_id" type="hidden" value="<?=$row['id']?>">
</div>
</form>
<script type="text/javascript" src="<?=base_url('js/star-rating.min.js')?>"></script>
<script type="text/javascript">
$(function () {
	$('.rating').rating();

	$('#save_rating').click(function(event) {
		var data = $('#rating_form').serialize();
		$.ajax({
	        type: "POST",
	        url: siteUrl('goods/rating_save'),
	        data: data,
	        dataType: "json",
	        success: function (response) {
        		alert(response.info);
	        }
	    });
	});
})
</script>