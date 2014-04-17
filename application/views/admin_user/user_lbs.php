<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
body, html{width: 100%;height: 100%;overflow: hidden;margin:0;}
#map{width:100%;height: 100%}
#l-map{height:100%;width:78%;float:left;border-right:2px solid #bcbcbc;}
#r-result{height:100%;width:20%;float:left;}
</style>
<link href="<?=base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>

<!-- BEGIN THEME STYLES --> 
<link href="<?=base_url()?>assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?=base_url()?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/validform.css" />
<title>店铺定位</title>
</head>
<body>
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>店铺坐标定位</div>
    </div>
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-4">
                <form id="lbsForm" class="form-horizontal" method="post" action="<?php echo site_url('admin/user_admin/user_edit_save')?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">经度</label>
                            <div class="col-md-9">
                                <input class="form-control" value="<?=$profile['lng']?>" type="text" name="profile[lng]" id="lng"  readonly="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">纬度</label>
                            <div class="col-md-9">
                                <input class="form-control" value="<?=$profile['lat']?>" type="text" name="profile[lat]"  id="lat"  readonly="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="button" id="btn_sub" class="btn btn-success" value="保存店铺坐标">
                                <input type="hidden" name="user_id" value="<?=$profile['user_id']?>" class="btn btn-success">
                            </div>                            
                        </div>
                    </div>
                </form>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-1 control-label"></label>
                        <div class="input-group">
                            <input id="suggestId" class="form-control" type="text" placeholder="搜索地址">
                            <div class="input-group-btn">
                                <button onclick="serach()" class="btn btn-info" type="button">搜索</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn red" onclick="custom_close()">关闭本页</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script src="<?php echo base_url()?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>js/validform_v5.3.2.js"></script>
<script src="<?php echo base_url()?>js/api.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=L7KaPONl21QL03CdVH4YOZ9w"></script>
<script type="text/javascript">
$(function () {
    var form = $("#lbsForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            
        }
    });    
})
// 百度地图API功能
var map = new BMap.Map("map");
var lat = <?=$profile['lat'] ? $profile['lat'] : 22.834832 ?> ; //地理纬度
var lng = <?=$profile['lng'] ? $profile['lng'] : 113.270748 ?> ; //地理纬度
var zoom = 16; //缩放级别
var Marker = new BMap.Marker(new BMap.Point(lng, lat)); //全局图像标注

map.addOverlay(Marker);    //添加标注
map.centerAndZoom(new BMap.Point(lng, lat), zoom); //设置中心点和缩放级别
map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用

Marker.enableDragging(); //开启标注拖拽功能

var driving = new BMap.DrivingRoute(map, {renderOptions:{map: map, autoViewport: true}});

map.addEventListener("click",function(e){
    var pp = new BMap.Point(e.point.lng, e.point.lat); 
    map.centerAndZoom(pp, zoom);
    Marker.setPosition(pp);  //更新标注位置
    updateLatAndLngInput(pp.lat, pp.lng);
});

//监听标注拖动结束事件
Marker.addEventListener('dragend', function (e) {
    var pp = new BMap.Point(e.point.lng, e.point.lat);
    updateLatAndLngInput(pp.lat, pp.lng);
})

//建立一个自动完成的对象
var ac = new BMap.Autocomplete({"input" : "suggestId", "location" : map});

ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
    var str = "";
    var _value = e.fromitem.value;
    var value = "";
    if (e.fromitem.index > -1) {
        value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
    }    
    
    value = "";
    if (e.toitem.index > -1) {
        _value = e.toitem.value;
        value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
    }    

});

ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
    var _value = e.item.value;
    myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
    
    setPlace(myValue);
});
function updateLatAndLngInput(lat, lng){
    $('#lat').val(lat);
    $('#lng').val(lng);
}

function serach() {
    var value = $('#suggestId').val();
    if(value){
        setPlace(value);
    }else{
        alert("请填写关键字");
    }
}
function setPlace(value){
    function myFun(){
        var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
        map.centerAndZoom(pp, zoom);
        Marker.setPosition(pp);  //更新标注位置
        $('#suggestId').val(local.getResults().getPoi(0).address);
        updateLatAndLngInput(pp.lat, pp.lng);
    }
    var local = new BMap.LocalSearch(map, { //智能搜索
      onSearchComplete: myFun
    });
    local.search(value);
}

</script>
