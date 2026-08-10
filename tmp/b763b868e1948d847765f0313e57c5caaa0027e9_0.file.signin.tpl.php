<?php
/* Smarty version 3.1.33, created on 2026-07-30 13:46:04
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/auth/signin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6af32c116c14_30846822',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b763b868e1948d847765f0313e57c5caaa0027e9' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/auth/signin.tpl',
      1 => 1785393962,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./_brand.tpl' => 1,
  ),
),false)) {
function content_6a6af32c116c14_30846822 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 src="https://accounts.google.com/gsi/client" async defer><?php echo '</script'; ?>
>
<div id="g_id_onload" data-client_id="<?php echo @constant('appIdGoogle');?>
" data-login_uri="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('signin');?>
" data-auto_prompt="true" data-context="use"></div>
<div class="sky-auth">
	<div class="sky-auth__card">
	<?php $_smarty_tpl->_subTemplateRender("file:./_brand.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	<div class="sky-auth__form">
		<div class="sky-auth__form-inner">
			<?php if ($_smarty_tpl->tpl_vars['_ss_forgot_password']->value == '_success') {?>
			<div class="alert alert-info">Yêu cầu đặt lại mật khẩu đã được gửi qua email cho bạn. Vui lòng làm theo hướng dẫn trong email đó.</div>
			<?php }?>
			<h1 class="sky-auth__title">Đăng nhập</h1>
			<p class="sky-auth__sub">Chào mừng trở lại, vui lòng đăng nhập tài khoản.</p>

			<?php if ($_smarty_tpl->tpl_vars['_login_google']->value == '1' || $_smarty_tpl->tpl_vars['_login_facebook']->value == '1') {?>
			<div class="auth-social-wrapper w-100 sky-social">
				<div class="auth-social w-100">
					<?php if ($_smarty_tpl->tpl_vars['_login_google']->value == '1') {?>
					<a data-toggle="ripple" title="Đăng nhập qua Google"<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> href="<?php echo $_smarty_tpl->tpl_vars['googleLoginUrl']->value;?>
"<?php }?>
						class="signin-via-google<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?> clickable<?php }?> login-network sky-google" mod_page="<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" 
							act_page="<?php echo $_smarty_tpl->tpl_vars['act']->value;?>
" rel="_GOOGLE"><i class='bx bxl-google'></i> Đăng nhập với Google
					</a>
					<?php }?>
				</div>
			</div>
			<div class="sky-divider">hoặc</div>
			<?php }?>
			<form id="frm-signin" class="frm-signin sky-form" action="#" method="POST">
				<div class="sky-field">
					<label for="email">Tên đăng nhập</label>
					<div class="sky-inwrap">
						<i class='bx bx-user sky-lead'></i>
						<input type="text" class="form-control sky-input" id="email" name="user_email" placeholder="Email đăng nhập" autofocus />
					</div>
				</div>
				<div class="sky-field form-password-toggle">
					<div class="sky-label-row">
						<label for="password">Mật khẩu</label>
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/quen-mat-khau.html?return_url=<?php echo $_smarty_tpl->tpl_vars['return_url']->value;?>
">Quên mật khẩu?</a>
					</div>
					<div class="sky-inwrap input-group">
						<i class='bx bx-lock-alt sky-lead'></i>
						<input type="password" id="password" class="form-control sky-input" name="user_pass" placeholder="Nhập mật khẩu" aria-describedby="password" />
						<span class="input-group-text cursor-pointer sky-eye"><i class="bx bx-hide"></i></span>
					</div>
				</div>
				<button class="btn btn-primary sky-submit" type="submit">Đăng nhập <i class='bx bx-right-arrow-alt'></i></button>
				<input type="hidden" name="submit" value="signin">
				<input type="hidden" name="return" value="<?php echo $_smarty_tpl->tpl_vars['return']->value;?>
">
				<input type="hidden" name="mod_page" value="<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
">
				<input type="hidden" name="act_page" value="<?php echo $_smarty_tpl->tpl_vars['act']->value;?>
">
			</form>
			<p class="sky-auth__support">Gặp vấn đề về tài khoản?<br />Vui lòng liên hệ Ban công nghệ <a href="mailto:<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_it');?>
"><?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('mail_it');?>
</a></p>
		</div>
	</div>
	</div>
</div>

<?php echo '<script'; ?>
 type="text/javascript">
	$(function(){
		if($.fn.textillate){ $('.tlt').textillate(); }
	});
<?php echo '</script'; ?>
>

<?php }
}
