<?php
/* Smarty version 3.1.33, created on 2026-08-08 11:14:32
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/profile/_ajax.open_import.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76ad28de7dd1_32473040',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e2eef74e6937c31fe29da9c16c5ef52375146c12' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/profile/_ajax.open_import.tpl',
      1 => 1785414864,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76ad28de7dd1_32473040 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog" style="width:540px">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void(0)" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>Import nhân sự từ Google Sheet</strong></h3>
		</div>
		<form method="POST" action="" id="form_profile_import">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Link Google Sheet <span class="text-red">*</span></label>
					<input type="text" name="sheet_url" class="form-control" placeholder="https://docs.google.com/spreadsheets/d/..../edit#gid=0" />
					<small class="text-muted">Sheet phải chia sẻ công khai (Bất kỳ ai có đường liên kết). Chọn đúng tab qua <b>gid</b> trên link.</small>
				</div>
				<div class="form-group">
					<label class="col-form-label">Mật khẩu mặc định cho tài khoản mới <span class="text-red">*</span></label>
					<input type="text" name="default_pass" class="form-control" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['default_pass']->value, ENT_QUOTES, 'UTF-8', true);?>
" />
					<small class="text-muted">Đặt sẵn tại Cấu hình hệ thống &rsaquo; Nhân sự để lần sau không phải nhập lại.</small>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="read_import(this, event)">Tải &amp; chọn cột</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Close');?>
</button>
			</div>
		</form>
	</div>
</div>
<?php }
}
