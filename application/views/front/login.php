<div class='warp ovh'>
    <div class="front_login">
        <div class="top"></div>
        <div class='bd'>
            <form method="post" action="<?=site_url('/login')?>">
                <table width="100%" cellpadding="0" cellspacing="10">
                    <tr>
                        <td width="25%" align="right"><label>用户名:</label></td>
                        <td align="left">
                            <input class="text_input" type="text" size="30" maxlength="80" id="login" value="" name="login">
                            </br>
                            <span class="red">
                                <?php echo form_error('login'); ?><?php echo isset($errors['login'])?$errors['login']:''; ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><label>密　码:</label></td>
                        <td align="left">
                            <input class="text_input" type="password" size="30" id="password" value="" name="password">
                            </br>
                            <span class="red">
                                <?php echo form_error('password'); ?><?php echo isset($errors['password'])?$errors['password']:''; ?>
                            </span>
                        </td>
                    </tr>
                    <?php if ($show_captcha): ?>
                    <tr>
                        <td align="right">验证码:</td>
                        <td align="left">
                            <input class="text_input" class="captcha" type="text" maxlength="8" id="captcha" value="" name="captcha">
                            <?php echo $captcha_html; ?>

                            </br>
                            <span class="red"><?php echo form_error('captcha') ? form_error('captcha') : ''; ?></span>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <!-- <tr>
                        <td align="right"><label>手机验证码:</label></td>
                        <td align="left">
                            <input class="mobile_captcha text_input" type="text" size="30" id="mobile_captcha" value="" name="mobile_captcha">
                            <input class="mobile_captcha_btn" type="button" size="30" id="get_mobile_captcha" value="获取手机验证码">
                            </br>
                            <span class="red">
                                <?php echo form_error('mobile_captcha'); ?><?php echo isset($errors['mobile_captcha'])?$errors['mobile_captcha']:''; ?>
                            </span>
                        </td>
                    </tr> -->
                    <tr>
                        <td align="right"></td>
                        <td align="left"><input class="login_btn" type="submit" value="登录" name="submit"><input class="login_btn" type="reset" value="重置"></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="bottom"></div>
        <div class="shadow">
            <img src="<?php echo base_url()?>/images/login_shadow.jpg" />
        </div>
    </div>
    <div class="cl mt20" style="width:937px"><img src="<?php echo base_url() ?>/images/bus_ad.jpg" alt=""></div>
    <div class="cl mt20" style="width:937px"><img src="<?php echo base_url() ?>/images/air_ad.jpg" alt=""></div>
    <div class="cl mt20" style="width:937px"><img src="<?php echo base_url() ?>/images/hotel_ad.jpg" alt=""></div>
</div>
<script src="<?php echo base_url() ?>/js/validform_v5.3.2.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
    $('#get_mobile_captcha').click(function() {
        var $this = $(this);
        var count = <?php echo $mobile_captcha_expire?>;

        var username = $('#login').val()
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("login/send_mobile_captcha")?>/' + username,
            dataType: "json",
            success: function(resp){
                alert(resp.msg)
                if(resp.success){
                    var countdown = setInterval(CountDown, 1000);
                }
            }
        });

        //倒计时
        function CountDown() {
            $this.addClass("mobile_captcha_btn_disabled");
            $this.attr("disabled", true);
            $this.val(count + "秒可以再次发送!");
            if (count == 0) {
                $this.val("获取手机验证码").removeAttr("disabled");
                $this.removeClass("mobile_captcha_btn_disabled");
                clearInterval(countdown);
            }
            count--;
        }
    })

});
</script>
</body>
</html>
