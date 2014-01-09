function common_del(url, id, code){
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
                    location.reload(true)
                }else{
                    alert( '删除失败' );
                }
            }
        });
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
            obj.find(".modal-body").html("")
        },
        success: function(data){
            var obj = $('#'+ Element)
            obj.find("h3").text(title)
            obj.find(".modal-body").html(data)
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
