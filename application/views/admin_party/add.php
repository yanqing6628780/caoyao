<script type="text/javascript" src="<?php echo base_url()?>js/api.js"></script>
<script type="text/javascript">
$(function(){

})
function FormSubmit(obj)
{
    var _this = $(obj);
    var fields = _this.parents('form').serializeArray();
    var fields_array = convertArray(fields);

    //提交数据
    if(fields_array['data[stdnum]'] != ''){
        $.ajax({
            type: "POST",
            url: 'news/add_save',
            dataType: 'json',
            data: fields,
            success: function(respone){
                if(respone.success){
                    alert('添加成功')
                    location.reload(true)
                    _this.parents('form')[0].reset();
                }
            }
        });
    }else{
        alert('学号和姓名不能为空')
        return false;
    }

}
</script>
<body>
<div class='form_table'>
    <form>
        <table width='100%' border="1" cellpadding="1" cellspacing="1">
            <tr>
                <td class='label' width='100'>标题</td>
                <td><input name="data[title]" value=""></td>
            </tr>
            <tr>
                <td class='label' width='100'>内容</td>
                <td><textarea name="data[content]"></textarea></td>
            </tr>
            <tr>
                <td colspan='2' align='center'>
                    <input type='button' value='保存' onclick='FormSubmit(this)'/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
