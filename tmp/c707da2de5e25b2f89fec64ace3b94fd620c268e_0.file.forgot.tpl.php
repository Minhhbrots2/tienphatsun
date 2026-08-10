<?php
/* Smarty version 3.1.33, created on 2026-07-30 22:16:12
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/auth/forgot.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6b6abca26042_64256546',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c707da2de5e25b2f89fec64ace3b94fd620c268e' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/auth/forgot.tpl',
      1 => 1784299632,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./_brand.tpl' => 1,
  ),
),false)) {
function content_6a6b6abca26042_64256546 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="sky-auth">
	<div class="sky-auth__card">
	<?php $_smarty_tpl->_subTemplateRender("file:./_brand.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	<div class="sky-auth__form">
		<div class="sky-auth__form-inner">
			<h1 class="sky-auth__title">Quên mật khẩu</h1>
			<p class="sky-auth__sub">Nhập email đã đăng ký, chúng tôi sẽ gửi hướng dẫn đặt lại mật khẩu qua email cho bạn.</p>
			<?php if ($_smarty_tpl->tpl_vars['err_email_exist']->value == '0') {?>
			<div class="alert alert-danger"><b>Khôi phục mật khẩu không thành công.</b><br />Email / tên tài khoản không tồn tại trong hệ thống.</div>
			<?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['error_msg']->value)) {?>
			<div class="alert alert-danger"><?php echo $_smarty_tpl->tpl_vars['error_msg']->value;?>
</div>
			<?php }?>
			<form name="frm-forgot" class="frm-forgot sky-form" action="<?php echo $_SERVER['SCRIPT_URI'];?>
" method="POST">
				<div class="sky-field">
					<label for="user_email">Email</label>
					<?php if ($_smarty_tpl->tpl_vars['err_user_email']->value) {?><div class="sky-error"><?php echo $_smarty_tpl->tpl_vars['err_user_email']->value;?>
</div><?php }?>
					<div class="sky-inwrap">
						<i class='bx bx-envelope sky-lead'></i>
						<input type="text" class="form-control email required sky-input" id="user_email" name="user_email" placeholder="Email bạn đã đăng ký" autofocus value="<?php echo $_smarty_tpl->tpl_vars['user_email']->value;?>
" />
					</div>
				</div>
				<div class="sky-field sky-recaptcha">
					<div class="g-recaptcha" data-sitekey="<?php echo $_smarty_tpl->tpl_vars['reCAPTCHA_KEY']->value;?>
"></div>
					<?php if ($_smarty_tpl->tpl_vars['errMsg']->value != '') {?><div class="sky-error"><?php echo $_smarty_tpl->tpl_vars['errMsg']->value;?>
</div><?php }?>
				</div>
				<button class="btn btn-primary sky-submit" type="submit">Tiếp tục <i class='bx bx-right-arrow-alt'></i></button>
				<input type="hidden" name="submit" value="forgot" />
				<input type="hidden" name="return_url" value="<?php echo $_smarty_tpl->tpl_vars['return_url']->value;?>
" />
			</form>
			<a class="sky-back" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/<?php if (!empty($_smarty_tpl->tpl_vars['return_url']->value)) {?>dang-nhap/ret=<?php echo $_smarty_tpl->tpl_vars['return_url']->value;
} else { ?>dang-nhap.html<?php }?>"><i class='bx bx-chevron-left'></i> Quay lại đăng nhập</a>
		</div>
	</div>
	</div>
</div>
<?php }
}
