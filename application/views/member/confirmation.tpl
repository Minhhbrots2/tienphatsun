<div class="container">
    <div class="row">
    	<div class="col-lg-4 col-md-4 col-md-offset-2">
            {$core->getBlock('member_benefit')}
        </div>
        <div class="col-lg-4 col-md-4 col-md-offset-2">
            <div class="member_box">
                <h2 class="headPage">Xác minh tài khoản</h2>
                <div class="member_in_box">
                    <div class="wrap">
                        <div id="row-field">
                            <form method="post" action="" name="frm-confirm" id="frm-confirm" class="frm-confirm">
                                {if $message eq ''}
                                <div class="form-group mb10">
                                   Bạn hãy vào email của mình để lấy mã kích hoạt
                                </div>
                                {else}
                                    Cảm ơn bạn, hướng dẫn lấy lại mật khẩu đã được gửi đến email của bạn. 
                                    <br /><br />
                                    Xin vui lòng kiểm tra và sử dụng thư điện tử để xử lý các bước tiếp theo.
                                    <br /><br />
                                {/if}
                                <div class="content mt20">
                                    <div class="blog">
                                        {if $err_last_name ne ''}
                                        <div class="register-error">
                                        	<div>{$err_last_name}</div>
                                        </div>
                                        {/if}
                                        <div class="form-group">
                                            <label class="lbl">Mã xác thực Email <font color="#c00000">*</font></label>
                                            <input type="text" class="form-control required" name="confirm_code" placeholder="Nhập mã xác thực" value="{$user_name}" />
                                            <p>Ex: {$clsProfile->generateConfirmCode('example@email.com')}</p>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="memberBtn">Xác minh</button>
                                            <input type="hidden" name="submit" value="confirm" />
                                        </div>
                                        <div class="form-group noteTxt">Chú ý: Bạn nên kiểm tra bulk hoặc spam nếu không nhận được email của chúng tôi.</div>
                                    </div>
                                </div>
                            </form>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{literal}
<script type="text/javascript">
	$().ready(function(){
		$('#frm-confirm').validate();
	});
</script>
{/literal}