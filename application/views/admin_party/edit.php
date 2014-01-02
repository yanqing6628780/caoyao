<script type="text/javascript" src="<?php echo base_url()?>js/api.js"></script>
<script type="text/javascript">
function FormSubmit(obj)
{
    var _this = $(obj);
    var fields = _this.parents('form').serializeArray();

    //提交数据
    $.ajax({
        type: "POST",
        url: 'news/edit_save',
        dataType: 'json',
        data: fields,
        success: function(respone){
            if(respone.success){
                alert('修改成功')
                location.reload(true)
                _this.parents('form')[0].reset();
            }
        }
    });
}
</script>
<body>
<div class='form_table'>
    <form>
        <input type="hidden" value="<?=$id?>" name="id">
        <table width='100%' border="1" cellpadding="2" cellspacing="1">
            <tr>
                <td class='label' width='100'>名字</td>
                <td><input name="data[title]" value="<?php echo $row['title'] ?>"></td>
            </tr>
            <tr>
                <td class='label' width='100'>公司简介</td>
                <td><textarea name="data[content]"><?php echo $row['content'] ?></textarea></td>
            </tr>
            <tr>
                <td class='label' width='100'>创建时间</td>
                <td><input name="data[created]" value="<?php echo $row['created'] ?>"></td>
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
