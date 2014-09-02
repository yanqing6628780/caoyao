<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">
		<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
	</button>
	<h4 class="modal-title" id="myModalLabel"><?=$name?> 组图</h4>
</div>
<div class="modal-body">
	<div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 640px; height: 480px;margin:0 auto">
	    <!-- Slides Container -->
	    <div u="slides" style="cursor: move; position: absolute; overflow: hidden; left: 0px; top: 0px; width: 640px; height: 480px;">
	    	<?php foreach ($map as $key => $item): ?>
	        <div><img class="img-responsive" u="image" src="<?=$path.'/'.$item ?>" /></div>
	        <?php endforeach ?>
	    </div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
<script>
$(function ($) {
    var options = {};                            
    var jssor_slider1 = new $JssorSlider$('slider1_container', options);

    //responsive code begin
    //you can remove responsive code if you don't want the slider scales
    //while window resizes
    function ScaleSlider() {
        var parentWidth = $('#slider1_container').parent().width();
        console.log(parentWidth);
        if (parentWidth) {
            jssor_slider1.$SetScaleWidth(parentWidth);
        }
    }
    //Scale slider after document ready
    window.onload = function(){
    	ScaleSlider();
    }
});
</script>