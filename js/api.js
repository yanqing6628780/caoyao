function common_del(url, id, code, reload_view){
    if(confirm('确认删除?'))
    {
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {id: id, code: code},
            success: function(respone){
                if(respone.success){                
                    alert( '删除成功' );
                    if(reload_view != false){
                        $(reload_view).click();
                    }
                }else{
                    alert( '删除失败' );
                }
            }
        });
    }
}

function custom_close(){
    if(confirm("您确定要关闭本页吗？")){
        window.opener=null;
        window.open('','_self');
        window.close();
    }else{

    }
}

//返回网站地址加string链接
function siteUrl(string)
{
    var url = BASEURL + string;
    return url;
}

//遍历对象并alert
function eachAlert(obj)
{
    $.each(obj, function(item, index){
        alert('下标:'+ item + ' 值:' + index)
    })
}

//将jquery序列化后的值转为name:value的形式。
function convertArray(o) {
    var v = {};
    for (var i in o) {
        if (typeof (v[o[i].name]) == 'undefined') v[o[i].name] = o[i].value;
        else v[o[i].name] += "," + o[i].value;
    }
    return v;
}

//阻止浏览器默认操作
function stopDefault(e) {
    if ( e && e.preventDefault ){
        e.preventDefault();
    }
    else{
        window.event.returnValue = false;
    }
    return false;
}


//在$('.page-content .page-content-body')位置加载页面
/*
** url 发送请求的地址 String
** data 发送到服务器的数据 Json&String
*/
function LoadPageContentBody(url, data)
{
    $.ajax({
        type: "GET",
        cache: false,
        url: url,
        dataType: "html",
        data: data,
        success: function (res) {
            pageContentBody.html(res);
        },
        async: false
    });
}

//页面某位置加载页面
/*
** url 发送请求的地址 String
** data 发送到服务器的数据 Json&String
** Element 加载返回页面的元素ID String
*/
function LoadAjaxPage(url, data, Element, title)
{
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "html",
        beforeSend: function(){
            var obj = $('#'+ Element)
            obj.find("h3").text("")
            if(obj.find(".modal-body").length){
                obj.find(".modal-body").html("")
            }
        },
        success: function(data){
            var obj = $('#'+ Element)
            obj.find("h4").text(title)
            if(obj.find(".modal-body").length){
                obj.find(".modal-body").html(data)
            }else{
                obj.html(data);
            }
        },
        complete: function(){
        }
    });
}


//checkbox点击全选
function allChecked(obj, parentString)
{
    var _this = $(obj)
    var _parent = $('#' + parentString)
    if($(obj).attr('checked') == 'checked'){
        _parent.find(':checkbox').attr('checked', true)
    }
    else{
        _parent.find(':checkbox').attr('checked', false)
    }
}

//返回网站地址加string链接
function reloadPage()
{
    location.reload(true)
}

var DatePicker = function(){
    return {
        //main function to initiate the module
        init1: function () {
            if (jQuery().datepicker) {
                $('.date-picker').datepicker({
                    language:'zh-CN',
                    format: 'yyyy-mm-dd'
                });
                $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
            }
        }
    };
}();

var TableAdvanced = function () {

    var oLanguage = {
        "sSearch": "搜索:",
        "sLengthMenu": "显示 _MENU_ 条",
        "sInfo": "显示 _START_ - _END_ 共 _TOTAL_ 记录",
        "sEmptyTable": "没有任何记录",
        "sInfoEmpty": "没有任何记录",
        "sZeroRecords": "没有匹配数据"
    };

    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            var oTable = $('#sample_1').dataTable({
                "aoColumnDefs": [
                    {"bSortable": false, "aTargets": [ 0 ] }
                ],
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 5,
                "oLanguage": oLanguage
            });

            jQuery('#sample_1 .group-checkable').change(function () {
                var set = jQuery(this).attr("data-set");
                var checked = jQuery(this).is(":checked");
                jQuery(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                });
                jQuery.uniform.update(set);
            });

            jQuery('#sample_1_wrapper .dataTables_filter input').addClass("form-control input-small"); // modify table search input
            jQuery('#sample_1_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            jQuery('#sample_1_wrapper .dataTables_length select').select2(); // initialize select2 dropdown
        },

        initCT: function () {
            if (!jQuery().dataTable) {
                return;
            }
            var oTable = $('.Ctable').dataTable({
                "aoColumnDefs": [
                    {"bSortable": false, "aTargets": [ 0 ] }
                ],
                "aaSorting": [[1, 'asc']],
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 10,
                "oLanguage": oLanguage
            });

            jQuery('.Ctable .dataTables_filter input').addClass("form-control input-small"); // modify table search input
            jQuery('.Ctable .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            jQuery('.Ctable .dataTables_length select').select2(); // initialize select2 dropdown
            return oTable;
        }
    };

}();