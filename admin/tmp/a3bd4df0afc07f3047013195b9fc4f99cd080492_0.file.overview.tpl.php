<?php
/* Smarty version 3.1.33, created on 2026-08-08 17:31:40
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/overview.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a77058c160897_27332167',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a3bd4df0afc07f3047013195b9fc4f99cd080492' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/overview.tpl',
      1 => 1784691720,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a77058c160897_27332167 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<header class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="text-muted" style="font-size:14px"><i class="fa fa-angle-left"></i> Dự án</a>
					&nbsp;/&nbsp;<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>

				</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="ui-button ui-button--primary ui-title-bar__action"><i class="fa fa-edit"></i> Sửa thông tin</a>
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=trash&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="ui-button ui-button--transparent ui-title-bar__action confirm_delete" title="Chuyển vào thùng rác"><i class="fa fa-trash"></i> Xóa</a>
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" data-title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oneItem']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" class="ui-button ui-button--transparent ui-title-bar__action js_delete_project" title="Xóa vĩnh viễn dự án và dữ liệu liên quan"><i class="fa fa-times-circle"></i> Xóa vĩnh viễn</a>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">

						<div class="ui-layout__item">
				<div class="ui-card">
					<div class="ui-card__section">
						<div class="form-row" style="align-items:center">
							<div class="col-md-7">
								<h2 class="ui-heading" style="margin:0 0 6px"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
</h2>
								<p class="text-muted" style="margin:0">
									<span class="label label-primary"><?php echo $_smarty_tpl->tpl_vars['stat_blocks']->value;?>
</span> phân khu &nbsp;·&nbsp;
									<span class="label label-default"><?php echo $_smarty_tpl->tpl_vars['stat_buildings']->value;?>
</span> tòa
									<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['reg_date']) {?>&nbsp;·&nbsp; Tạo <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['oneItem']->value['reg_date'],"%d/%m/%Y");
}?>
								</p>
							</div>
							<div class="col-md-5 text-right">
								<label class="switch-inline" style="margin-right:18px">
									<span class="text-muted" style="margin-right:6px">Khóa</span>
									<label class="switch">
										<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_lock" tp="_project" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_lock'] == '1') {?> checked<?php }?>>
										<span class="slider round"></span>
									</label>
								</label>
								<label class="switch-inline">
									<span class="text-muted" style="margin-right:6px">Quick Menu</span>
									<label class="switch">
										<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_menu" tp="_project" value="1" for_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_menu'] == '1') {?> checked<?php }?>>
										<span class="slider round"></span>
									</label>
								</label>
							</div>
						</div>
					</div>

										<div class="ui-card__section">
						<div class="form-row proj-actions">
							<div class="col-md-4 proj-group proj-group--struct">
								<h4 class="proj-group-title"><i class="fa fa-sitemap"></i> Cấu trúc</h4>
								<a href="#blocks" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-th-large"></i></span><span class="proj-tile__tx">Phân khu &amp; Tòa</span></a>
								<?php if (!empty($_smarty_tpl->tpl_vars['block_type_arrs']->value)) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['block_type_arrs']->value, '_bt');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_bt']->value) {
?>
									<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=map&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
&stock_type=<?php echo $_smarty_tpl->tpl_vars['_bt']->value;?>
" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-map-marker"></i></span><span class="proj-tile__tx">Bản đồ <?php if ($_smarty_tpl->tpl_vars['_bt']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>(Cao tầng)<?php } elseif ($_smarty_tpl->tpl_vars['_bt']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>(Thấp tầng)<?php }?></span></a>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php } else { ?>
								<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=map&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-map-marker"></i></span><span class="proj-tile__tx">Bản đồ dự án</span></a>
								<?php }?>
								<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=draw_map&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-pencil-square-o"></i></span><span class="proj-tile__tx">Vẽ bản đồ</span></a>
							</div>
							<div class="col-md-4 proj-group proj-group--content">
								<h4 class="proj-group-title"><i class="fa fa-file-text-o"></i> Nội dung</h4>
								<a href="javascript:void(0);" onClick="open_progress(this, event)" block_id="0" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" _openFrom="_project" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-tasks"></i></span><span class="proj-tile__tx">Tiến độ dự án</span></a>
								<button type="button" onClick="$Core.project.open_sop(this, event)" sop_id="0" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-clipboard"></i></span><span class="proj-tile__tx">SOP / Tiêu chuẩn</span></button>
								<button type="button" onClick="$Core.utilities.open(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" utilities_id="" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-cubes"></i></span><span class="proj-tile__tx">Tiện ích dự án</span></button>
							</div>
							<div class="col-md-4 proj-group proj-group--config">
								<h4 class="proj-group-title"><i class="fa fa-cog"></i> Thông tin &amp; cấu hình</h4>
								<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&project_id=<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-edit"></i></span><span class="proj-tile__tx">Sửa thông tin dự án</span></a>
								<?php if (!empty($_smarty_tpl->tpl_vars['block_type_arrs']->value)) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['block_type_arrs']->value, '_bt');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_bt']->value) {
?>
									<button type="button" onClick="$Core.project.open_config_column(this, event)" block_type="<?php echo $_smarty_tpl->tpl_vars['_bt']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-table"></i></span><span class="proj-tile__tx">Cấu hình <?php if ($_smarty_tpl->tpl_vars['_bt']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>(Cao tầng)<?php } elseif ($_smarty_tpl->tpl_vars['_bt']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>(Thấp tầng)<?php }?></span></button>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
			</div>

						<div class="ui-layout__item" id="blocks">
				<div class="ui-card">
					<div class="ui-card__section">
						<div class="d-flex" style="justify-content:space-between; align-items:center; margin-bottom:10px">
							<h3 class="ui-heading" style="margin:0">Phân khu &amp; Tòa</h3>
							<a href="javascript:void(0);" onClick="open_block(this, event)" block_id="0" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" _openFrom="_project" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Thêm phân khu</a>
						</div>
						<div class="holderBlock">
							<?php echo $_smarty_tpl->tpl_vars['blocks_html']->value;?>

						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<?php echo '<script'; ?>
>window.__projectView = 'overview';<?php echo '</script'; ?>
>

<?php }
}
