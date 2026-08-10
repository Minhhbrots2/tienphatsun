<div class="sky-auth">
	<div class="sky-auth__card">
	{include file="./_brand.tpl"}
	<div class="sky-auth__form">
		<div class="sky-auth__form-inner">
			<h1 class="sky-auth__title">Quên mật khẩu</h1>
			<p class="sky-auth__sub">Nhập email đã đăng ký, chúng tôi sẽ gửi hướng dẫn đặt lại mật khẩu qua email cho bạn.</p>
			{if $err_email_exist eq '0'}
			<div class="alert alert-danger"><b>Khôi phục mật khẩu không thành công.</b><br />Email / tên tài khoản không tồn tại trong hệ thống.</div>
			{/if}
			{if !empty($error_msg)}
			<div class="alert alert-danger">{$error_msg}</div>
			{/if}
			<form name="frm-forgot" class="frm-forgot sky-form" action="{$smarty.server.SCRIPT_URI}" method="POST">
				<div class="sky-field">
					<label for="user_email">Email</label>
					{if $err_user_email}<div class="sky-error">{$err_user_email}</div>{/if}
					<div class="sky-inwrap">
						<i class='bx bx-envelope sky-lead'></i>
						<input type="text" class="form-control email required sky-input" id="user_email" name="user_email" placeholder="Email bạn đã đăng ký" autofocus value="{$user_email}" />
					</div>
				</div>
				<div class="sky-field sky-recaptcha">
					<div class="g-recaptcha" data-sitekey="{$reCAPTCHA_KEY}"></div>
					{if $errMsg ne ''}<div class="sky-error">{$errMsg}</div>{/if}
				</div>
				<button class="btn btn-primary sky-submit" type="submit">Tiếp tục <i class='bx bx-right-arrow-alt'></i></button>
				<input type="hidden" name="submit" value="forgot" />
				<input type="hidden" name="return_url" value="{$return_url}" />
			</form>
			<a class="sky-back" href="{$PCMS_URL}/{if !empty($return_url)}dang-nhap/ret={$return_url}{else}dang-nhap.html{/if}"><i class='bx bx-chevron-left'></i> Quay lại đăng nhập</a>
		</div>
	</div>
	</div>
</div>
