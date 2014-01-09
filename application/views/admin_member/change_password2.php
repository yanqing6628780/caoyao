<script type="text/javascript">
Ext.onReady(function() {

    var changePasswordForm = new Ext.form.FormPanel({
        labelAlign:'right',
        labelWidth: 80,
        buttonAlign:'left',
        autoHeight:true,
        layout: 'form',
        defaultType: 'textfield',
        defaults:{
            msgTarget: 'side'
        },
        url:siteUrl('admin/user/change_password'),
        items:[
            {
                fieldLabel: '输入旧密码',
                name: 'old_password',
                inputType: 'password',
                allowBlank: false
            },
            {
                fieldLabel: '输入新密码',
                name: 'new_password',
                inputType: 'password',
                vtype: 'alphanum',
                allowBlank: false
            },
            {
                fieldLabel: '确认新密码',
                name: 'confirm_new_password',
                inputType: 'password',
                vtype: 'alphanum',
                allowBlank: false
            }
        ],
        buttons:[
            {
                text:'保存',
                handler: function(){
                    var basicform = changePasswordForm.getForm()
                    if(basicform.isValid()){
                        basicform.submit({
                            success:function(form, action){
                                Ext.Msg.alert('信息', '修改密码成功')
                                basicform.reset()
                            },
                            failure:function(form, action){
                                if(action.failureType == Ext.form.Action.SERVER_INVALID){
                                    Ext.Msg.alert('失败', '修改密码失败')
                                }else{
                                    Ext.Msg.alert('失败', '无法访问后台')
                                }
                            }
                        })
                    }
                }
            }
        ]
    })

    var centerPanel = new Ext.Panel({
        frame:false,
        border:false,
        layout: 'fit',
        defaults:{
            border:false,
            frame:false,
            autoScroll: true
        },
        items:[changePasswordForm]
    })

    centerPanel.render('index');
})
</script>
<div id='index'></div>
