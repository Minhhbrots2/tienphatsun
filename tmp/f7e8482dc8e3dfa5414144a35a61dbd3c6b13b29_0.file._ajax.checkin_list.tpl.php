<?php
/* Smarty version 3.1.33, created on 2026-08-07 17:38:01
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.checkin_list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75b58966f457_33184318',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f7e8482dc8e3dfa5414144a35a61dbd3c6b13b29' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.checkin_list.tpl',
      1 => 1784299672,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75b58966f457_33184318 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="card" id="card_recent">
	<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
		<h5 class="mb-0"><i class="bx bx-map-pin text-primary me-1"></i>Check-in mới nhất</h5>
		<?php if (!empty($_smarty_tpl->tpl_vars['list_recent']->value)) {?>
		<span class="badge bg-label-primary"><?php echo count($_smarty_tpl->tpl_vars['list_recent']->value);?>
 lượt</span>
		<?php }?>
	</div>
	<div class="card-body">
		<?php if (empty($_smarty_tpl->tpl_vars['list_recent']->value)) {?>
		<div class="text-center text-muted py-4">Chưa có dữ liệu</div>
		<?php } else { ?>
		<div class="form-row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-5 g-3">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_recent']->value, '_row');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_row']->value) {
?>
			<div class="col mb-2">
				<div class="border rounded-3 p-2 h-100"
					<?php if (!empty($_smarty_tpl->tpl_vars['_row']->value['photo'])) {?>
					data-fancybox="checkin_photos" href="<?php echo $_smarty_tpl->tpl_vars['_row']->value['photo'];?>
"
					data-caption="<div><strong><?php echo $_smarty_tpl->tpl_vars['_row']->value['full_name'];?>
</strong><br><?php echo $_smarty_tpl->tpl_vars['_row']->value['time_label'];?>
<br><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_row']->value['location'], ENT_QUOTES, 'UTF-8', true);?>
</div>"
					<?php }?>>
					<div class="d-flex align-items-center gap-2 mb-2">
						<a href="javascript:void(0);" onclick="$Core.member.view_profile(this, event)" profile_id="<?php echo $_smarty_tpl->tpl_vars['_row']->value['profile_id'];?>
">
							<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['_row']->value['avatar'];?>
"
								onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" width="36" height="36" alt="">
						</a>
						<div class="overflow-hidden">
							<div class="fw-semibold fs-13 text-truncate" title="<?php echo $_smarty_tpl->tpl_vars['_row']->value['full_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['_row']->value['full_name'];?>
</div>
							<div class="text-muted fs-11"><?php echo $_smarty_tpl->tpl_vars['_row']->value['time_label'];?>
</div>
						</div>
					</div>
					<?php if (!empty($_smarty_tpl->tpl_vars['_row']->value['location'])) {?>
					<div class="text-muted fs-11 mb-2 text-truncate" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_row']->value['location'], ENT_QUOTES, 'UTF-8', true);?>
" lat="<?php echo $_smarty_tpl->tpl_vars['_row']->value['lat'];?>
" lng="<?php echo $_smarty_tpl->tpl_vars['_row']->value['lng'];?>
" >
						<i class="bx bx-map me-1"></i><?php echo $_smarty_tpl->tpl_vars['_row']->value['location'];?>

					</div>
					<?php }?>
					<?php if (!empty($_smarty_tpl->tpl_vars['_row']->value['photo'])) {?>
					<img src="<?php echo $_smarty_tpl->tpl_vars['_row']->value['photo'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'"
						alt="" class="w-100 rounded-2" style="height:150px;object-fit:cover;cursor:pointer">
					<?php } else { ?>
					<div class="bg-light rounded-2 d-flex align-items-center justify-content-center" style="height:80px">
						<i class="bx bx-image text-muted fs-2"></i>
					</div>
					<?php }?>
				</div>
			</div>
			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</div>
		<?php }?>
	</div>
</div>
<?php }
}
