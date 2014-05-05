<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">价格</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input class="form-control" type='text' name="price" value='<?=$row['price']?>' datatype="*"/>
                            <span class="input-group-addon">信用</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">时限</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input class="form-control" type='text' name="time_limit" value='<?=$row['time_limit']?>' datatype="n"/>
                            <span class="input-group-addon">小时</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">类型</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="type" value='<?=$row['type']?>' datatype="*" nullmsg="请输入类型！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">省市区</label>
                    <div class="col-md-4">
                        <div id="area">                        
                            <select data-selected="<?=$row['province']?>" class="form-control" name="province"></select>
                            <select data-selected="<?=$row['city']?>" class="form-control" name="city"></select>
                            <select data-selected="<?=$row['district']?>" class="form-control" name="district"></select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">单位</label>
                    <div class="col-md-9">
                        <input class="form-control" type='text' name="company" value='<?=$row['company']?>' datatype="*" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">地址</label>
                    <div class="col-md-9">
                        <input class="form-control" type='text' name="address" value='<?=$row['address']?>' datatype="*" nullmsg="请输入地址！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">邮编</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="zipcode" value='<?=$row['zipcode']?>'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">区号</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="citycode" value='<?=$row['citycode']?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">电话</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="tel" value='<?=$row['tel']?>'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">传真</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="fax" value='<?=$row['fax']?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">职务</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="duties" value='<?=$row['duties']?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">姓名</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="name" value='<?=$row['name']?>'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">手机</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="mobile" value='<?=$row['mobile']?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">qq</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="qq" value='<?=$row['qq']?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">E-mail</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="email" value='<?=$row['email']?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">网址</label>
                    <div class="col-md-9">
                        <input class="form-control" type='text' name="website" value='<?=$row['website']?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">代理品牌</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="actingbrand" value='<?=$row['actingbrand']?>' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-3 control-label">备注</label>
                    <div class="col-md-9">
                        <input class="form-control" type='text' name="remark" value='<?=$row['remark']?>' />
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <input type='hidden' name="table" value='<?=$table?>'/>
                    <input type='hidden' name="id" value='<?=$id?>'/>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?php echo base_url()?>js/cityselect.js"></script>
<script type="text/javascript">
$(function () {
    DatePicker.init1();
    var form = $("#editForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:3,
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){
                $('#myModal').modal('hide');
                query()
            }
        }
    });
    $('#area').citySelect();
})
</script>