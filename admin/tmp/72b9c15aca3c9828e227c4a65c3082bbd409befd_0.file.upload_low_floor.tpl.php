<?php
/* Smarty version 3.1.33, created on 2026-07-31 17:38:48
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/ajax/upload_low_floor.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6c7b380b8913_32690398',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '72b9c15aca3c9828e227c4a65c3082bbd409befd' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/ajax/upload_low_floor.tpl',
      1 => 1784691580,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6c7b380b8913_32690398 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-standard" style="max-width:500px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>Cấu hình</strong></h3>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label required">Cấu trúc mã căn hộ</label>
					<input id="inputor" style="height:34px" onchange="$(this).val($(this).val().replace('%',''))" title="Gõ % để lựa chọn" data-toggle="tooltip" class="form-control disabled-resize-y required" placeholder="[MaDay][CanHo]" name="config_stock[stock_template]" value="<?php echo $_smarty_tpl->tpl_vars['config_stock']->value['stock_template'];?>
" />
				</div>
				<div class="form-group">
					<label class="col-form-label required">Spreadsheet dự án</label>
					<div class="form-row">
						<div class="col-md-5">
							<input type="text" class="form-control required spreadsheetId_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-label="Spreadsheet ID" onclick="this.select();" name="config_stock[spreadsheet_id]" value="<?php echo $_smarty_tpl->tpl_vars['config_stock']->value['spreadsheet_id'];?>
" placeholder="Spreadsheet ID">
						</div>
						<div class="col-md-7">
							<div class="input-group">
								<input type="hidden" gid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="config_stock[sheet_id]" value="<?php echo $_smarty_tpl->tpl_vars['config_stock']->value['sheet_id'];?>
" class="sheet_id_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
								<input type="text" gid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="form-control sheet_name required sheet_name_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-label="Sheet name" onclick="this.select();" name="config_stock[sheet_name]" value="<?php echo $_smarty_tpl->tpl_vars['config_stock']->value['sheet_name'];?>
" placeholder="SHEET_1" readonly>
								<div class="input-group-btn">
									<button type="button" onclick="$Core.project.open_sheet(this, event)" gid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" stock_type="178" class="btn btn-default" title="Chọn sheet">
										<i class="fa fa-cog"></i> Chọn sheet
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="text-right col-form-label">Cấu hình số căn hộ (nếu có)</label>
					<small>VD: căn số 13 chuyển thành căn số 12A</small>
					<div class="form-row">
						<?php if (!empty($_smarty_tpl->tpl_vars['config_stock']->value['convert_from'])) {?>
							<?php $_smarty_tpl->_assignInScope('convertTo', $_smarty_tpl->tpl_vars['config_stock']->value['convert_to']);?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['config_stock']->value['convert_from'], 'convert_from', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['convert_from']->value) {
?>
								<div class="col-md-12">
									<div class="d-flex align-items-center justify-content-center convert_item">
										<input type="text" class="form-control" placeholder="VD:13" name="config_stock[convert_from][]" value="<?php echo $_smarty_tpl->tpl_vars['convert_from']->value;?>
" >
										<i class="fa fa-arrow-right ml-2 mr-2" aria-hidden="true"></i>
										<input type="text" class="form-control" placeholder="VD:12A" name="config_stock[convert_to][]" value="<?php echo $_smarty_tpl->tpl_vars['convertTo']->value[$_smarty_tpl->tpl_vars['key']->value];?>
" >
										<button type="button" class="btn btn-add-convert ml-2" onclick="$Core.project.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="add"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
										<button type="button" class="btn btn-delete-convert ml-2 d-none" onclick="$Core.project.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="delete"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
									</div>
								</div>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php } else { ?>
							<div class="col-md-12">
								<div class="d-flex align-items-center justify-content-center convert_item">
									<input type="text" class="form-control" placeholder="VD:13" name="config_stock[convert_from][]" value="" >
									<i class="fa fa-arrow-right ml-2 mr-2" aria-hidden="true"></i>
									<input type="text" class="form-control" placeholder="VD:12A" name="config_stock[convert_to][]" value="" >
									<button type="button" class="btn btn-add-convert ml-2" onclick="$Core.project.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="add"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
									<button type="button" class="btn btn-delete-convert ml-2 d-none" onclick="$Core.project.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="delete"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
								</div>
							</div>
						<?php }?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" class="form-control " name="project_id" value="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
">
				<button type="submit" class="btn btn-success" onClick="$Core.project.import_data_from_link_doc_gg(this, event)" data-project-id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" action="_SAVE">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Save'));?>

				</button>
				<button type="submit" class="btn btn-primary" onClick="$Core.project.import_data_from_link_doc_gg(this, event)" data-project-id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" action="_CREATE">Lưu & Tạo bảng hàng</button>
			</div>
		</form>
	</div>
</div><?php }
}
