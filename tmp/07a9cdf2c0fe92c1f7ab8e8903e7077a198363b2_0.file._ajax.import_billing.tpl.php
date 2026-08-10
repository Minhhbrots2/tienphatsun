<?php
/* Smarty version 3.1.33, created on 2026-08-08 17:46:04
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.import_billing.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7708ec537b21_80695263',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '07a9cdf2c0fe92c1f7ab8e8903e7077a198363b2' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.import_billing.tpl',
      1 => 1784299653,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7708ec537b21_80695263 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-lg">
	<form method="POST" enctype="multipart/form-data" class="modal-content" id="billing_import_form_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
		<div class="modal-header">
			<h5 class="modal-title mb-0"><i class="bx bx-import me-1"></i> Import giao dịch từ Google Sheet</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="alert alert-info py-2 d-flex align-items-start mb-3">
				<i class="bx bx-info-circle me-2 mt-1"></i>
				<small>
					Chia sẻ Google Sheet tới <strong>service account</strong> của hệ thống trước.
					Khối / Phòng / GĐ được suy tự động theo <strong>Mã nhân viên</strong>.
					Loại giao dịch lấy từ cột "Loại" (map trong ⚙) — không map thì dùng Loại mặc định.
					Luôn bấm <strong>Xem trước</strong> để kiểm tra rồi mới ghi.
				</small>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-6 mb-2">
					<label class="form-label mb-1">Spreadsheet ID</label>
					<input type="text" class="form-control required" name="spreadsheetId" placeholder="ID trong URL Google Sheet"
						toId="bi_sheet_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['saved_spreadsheet']->value, ENT_QUOTES, 'UTF-8', true);?>
" onChange="$Core.billingImport.loadSheets(this,event)">
				</div>
				<div class="col-12 col-md-6 mb-2">
					<label class="form-label mb-1">Sheet (tab)</label>
					<div class="input-group">
						<select name="sheet_name" class="form-select required" id="bi_sheet_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
							<option value="">Chọn sheet</option>
						</select>
						<button type="button" class="btn btn-outline-default" title="Cấu hình map cột"
							onClick="$Core.billingImport.openConfig(this,event)"><i class="bx bx-cog"></i></button>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-6 col-md-4 mb-2">
					<label class="form-label mb-1">Dòng bắt đầu</label>
					<input type="number" class="form-control required" name="start_row" value="2" min="1">
				</div>
				<div class="col-6 col-md-4 mb-2">
					<label class="form-label mb-1">Loại mặc định <small class="text-muted">(nếu cột trống)</small></label>
					<select name="billing_type" class="form-select">
						<option value="">— Lấy từ cột "Loại" —</option>
						<?php echo $_smarty_tpl->tpl_vars['billing_type_options']->value;?>

					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 d-flex align-items-end">
					<button type="button" class="btn btn-outline-primary w-100" onClick="$Core.billingImport.preview(this,event)">
						<i class="bx bx-search-alt me-1"></i> Xem trước
					</button>
				</div>
			</div>
			<input type="hidden" name="is_preview" value="1">
			<div class="billing-import-report mt-2"></div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary bi-btn-run disabled" disabled onClick="$Core.billingImport.confirmRun(this,event)">
				<i class="bx bx-save me-1"></i> Ghi vào hệ thống
			</button>
		</div>
	</form>
</div>
<?php }
}
