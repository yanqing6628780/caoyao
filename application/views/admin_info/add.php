<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">行业</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <select datatype="*" class="form-control" name="table">
                            <option value="">请选择分类</option>
                            <?php foreach ($categories as $key => $value): ?>
                            <option value="<?=$value['table']?>"><?=$value['name']?></option>
                            <?php endforeach ?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">价格</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input class="form-control" type='text' name="price" value='' datatype="*"/>
                            <span class="input-group-addon">信用</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">时限</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input class="form-control" type='text' name="time_limit" value='' datatype="n"/>
                            <span class="input-group-addon">小时</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">类型</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="type" value='' datatype="*" nullmsg="请输入类型！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">省市区</label>
                    <div class="col-md-4">
                        <div id="area">                        
                            <select class="form-control" name="province"></select>
                            <select class="form-control" name="city"></select>
                            <select class="form-control" name="district"></select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">单位</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="company" value='' datatype="*" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">地址</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="address" value='' datatype="*" nullmsg="请输入地址！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">邮编</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="zipcode" value=''/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">区号</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="citycode" value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="tel" value=''/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">传真</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="fax" value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">邮编</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="zipcode" value=''/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">职务</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="duties" value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">姓名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="name" value=''/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">手机</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="mobile" value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">qq</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="qq" value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">E-mail</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="email" value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">网址</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="website" value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">代理品牌</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="actingbrand" value='' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">备注</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="remark" value='' />
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <input type='hidden' name="table" value='<?=$table?>'/>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?php echo base_url()?>js/cityselect.js"></script>
<script type="text/javascript">
$(function () {
    DatePicker.init1();
    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){            
                if(confirm('是否继续添加')){
                    form.resetForm();
                    query()
                }else{
                    $('#myModal').modal('hide');
                    query()
                }
            }
        }
    });
    $('#area').citySelect();
});
</script>