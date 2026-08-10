<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="index.html" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
								<img class="img-fluid" src="{$URL_IMAGES}/logo-header.png" width="200px" />
							</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2 text-center">Đăng ký tài khoản</h4>
					{if $_login_google eq '1' || $_login_facebook eq '1'}		
					<div class="auth-social-wrapper">
						<div class="divider">
							<div class="divider-text">Đăng nhập với tài khoản</div>
						</div>	
						<ul class="auth-social">
							{if $_login_facebook eq '1'}			
							<li><a data-toggle="ripple" title="Đăng nhập qua Facebook"{if $deviceType eq 'phone'} href="{$facebookLoginUrl}"{/if} class="signin-via-facebook{if $deviceType ne 'phone'} clickable{/if} login-network" mod_page="{$mod}" act_page="{$act}" rel="_FACEBOOK"></a></li>
							{/if}			
							{if $_login_google eq '1'}				
							<li><a data-toggle="ripple" title="Đăng nhập qua Google"{if $deviceType eq 'phone'} href="{$googleLoginUrl}"{/if} class="signin-via-google{if $deviceType ne 'phone'} clickable{/if} login-network" mod_page="{$mod}" act_page="{$act}" rel="_GOOGLE"></a></li>
							{/if}			
						</ul>
					</div>
					<div class="divider">
						<div class="divider-text">Hoặc</div>
					</div>
					{/if}
					{if $err_msg ne ''}
					<div class="err_msg_box">{$err_msg}</div>
					{/if}
					<div class="form-group alert alert-danger wrap">
						<em>Những thông tin dưới đây (<span class="required">*</span>) là bắt buộc nhập.</em>
					</div>
                    <form action="{$smarty.server.SCRIPT_URI}" method="POST" name="frm-signup" id="frm-signup" class="frm-signup mb-3">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ và tên</label>
							{if $err_full_name ne ''}
								<div class="error register-error">{$err_full_name}</div>
							{/if}
                            <input type="text" class="form-control required" id="full_name" name="full_name"
                                placeholder="Họ & tên" value="{$full_name}" autofocus />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
							{if $err_email ne ''}<div class="register-error error">{$err_email}</div>{/if}
                            <input type="text" class="form-control email required" id="email" name="email"
                                placeholder="Email đăng nhập" value="{$email}" />
                        </div>	
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="user_pass">Mật khẩu</label>
							{if $err_pass ne ''}
							<div class="error register-error">{$err_pass}</div>
							{/if}			
                            <div class="input-group input-group-merge">
                                <input type="password" id="user_pass" class="form-control required" name="user_pass" aria-describedby="password" autocomplete="off" placeholder="Mật khẩu" />
                                <span class="input-group-text cursor-pointer">
									<i class="bx bx-hide"></i>
								</span>
                            </div>
                        </div>
						<div class="mb-3 form-password-toggle">
                            <label class="form-label" for="user_cpass">Xác nhận mật khẩu</label>
                            {if $err_cpass ne ''}
							<div class="register-error error">{$err_cpass}</div>
							{/if}
							<div class="input-group input-group-merge">
                                <input type="password" id="user_cpass" class="form-control required" name="user_cpass"
                                    placeholder="Xác nhận mật khẩu" autocomplete="off" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer">
									<i class="bx bx-hide"></i>
								</span>
                            </div>
                        </div>
						{if $_ISOCMS_CAPTCHA eq 'IMG'}
						<div class="mb-3">
							{if $err_secure ne ''}
							<div class="register-error error">{$err_secure}</div>
							<div class="clearfix"></div>
							{/if}
							<img class="fl mr10" src="{$PCMS_URL}captcha.php?sid={$sid}" onclick="this.src='{$base_url}/captcha.php?'+Math.random()+'&sid={$sid}';" width="100" height="33">
							<input autocomplete="off" type="text" class="form-control pull-left m-r-10 required" name="security_code" id="security_code" maxlength="5" />
						</div>
						{else}
						<div class="mb-3">
							<div class="g-recaptcha" data-sitekey="{$clsISO->getVar('reCAPTCHA_KEY')}"></div>
							{if $errMsg ne ''}
							<div class="error" style="color: red">{$errMsg}</div>
							{/if} 
						</div>
						{/if}
						<input type="hidden" name="submit" value="register" />
                        <button type="submit" class="btn btn-primary d-grid w-100">Đăng ký</button>
                    </form>
                    <p class="text-center">
                        <span>Bạn đã có tài khoản?</span>
                        <a href="{$PCMS_URL}/dang-nhap.html" title="Đăng nhập">
                            <span>đăng nhập</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- Register Card -->
        </div>
    </div>
</div>
{literal}
<script type="text/javascript">
	$().ready(function(){
		$('#frm-signup').validate();
	});
</script>
{/literal}