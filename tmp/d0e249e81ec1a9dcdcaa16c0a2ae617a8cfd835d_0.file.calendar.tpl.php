<?php
/* Smarty version 3.1.33, created on 2026-08-06 09:16:27
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/calendar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a73ee7b849949_74483016',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd0e249e81ec1a9dcdcaa16c0a2ae617a8cfd835d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/calendar.tpl',
      1 => 1785982507,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a73ee7b849949_74483016 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/fullcalendar-1.6.0/fullcalendar/fullcalendar.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" media="all" />
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/fullcalendar-1.6.0/fullcalendar/moment.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/fullcalendar-1.6.0/fullcalendar/fullcalendar.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/fullcalendar-1.6.0/fullcalendar/gcal.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-xxxl-10 offset-xxxl-1">
			<div class="d-flex flex-wrap align-items-center gap-2 py-1 mb-2">
				<div class="me-auto">
					<h4 class="fw-bold mb-0">Đăng ký phòng họp</h4>
					<span class="text-muted d-none d-md-block">Đăng ký sử dụng phòng họp</span>
				</div>
				<div class="d-flex align-items-center gap-2 <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>flex-fill justify-content-end<?php }?>">
					<div class="input-group flex-nowrap js__room-filter-group <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>flex-fill<?php }?>">
						<span class="input-group-text px-2"><i class='bx bx-buildings'></i></span>
						<select class="form-control js__room-filter" name="room_id" title="Chọn phòng họp" onChange="$Core.calendar.change_room(this, event)">
							<?php if (!empty($_smarty_tpl->tpl_vars['room_offices']->value)) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['room_offices']->value, '_oOffice');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oOffice']->value) {
?>
									<?php $_smarty_tpl->_assignInScope('_oid', $_smarty_tpl->tpl_vars['_oOffice']->value['office_id']);?>
									<?php if (!empty($_smarty_tpl->tpl_vars['room_list']->value[$_smarty_tpl->tpl_vars['_oid']->value])) {?>
										<optgroup label="<?php echo $_smarty_tpl->tpl_vars['_oOffice']->value['title'];?>
">
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['room_list']->value[$_smarty_tpl->tpl_vars['_oid']->value], '_oRoom');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oRoom']->value) {
?>
												<option value="<?php echo $_smarty_tpl->tpl_vars['_oRoom']->value['office_id'];?>
:<?php echo $_smarty_tpl->tpl_vars['_oRoom']->value['room_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['_oRoom']->value['office_id'] == $_smarty_tpl->tpl_vars['default_office_id']->value && $_smarty_tpl->tpl_vars['_oRoom']->value['room_id'] == $_smarty_tpl->tpl_vars['default_room_id']->value) {?> selected<?php }?>><?php if ($_smarty_tpl->tpl_vars['_oRoom']->value['is_default']) {?>Phòng họp <?php }
echo $_smarty_tpl->tpl_vars['_oRoom']->value['title'];?>
</option>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										</optgroup>
									<?php }?>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php }?>
						</select>
					</div>
					<div class="d-flex gap-2 flex-shrink-0 <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>flex-fill justify-content-end<?php }?>">
						<button type="button" data-toggle="ripple" title="Thống kê" openFrom="_dashboard" onClick="$Core.calendar.loadTotal_calendar(this, event)" data-type="_OPEN" class="btn btn-primary text-nowrap <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'computer') {?>btn-icon<?php }?>"><?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'computer') {?><i class="bx bx-bar-chart-square"></i><?php } else { ?>Thống kê<?php }?></button>
						<button type="button" data-toggle="ripple" title="Thêm mới" openFrom="_dashboard" onClick="$Core.calendar.open(this, event)" class="btn btn-outline-primary text-nowrap <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'computer') {?>btn-icon<?php }?>"><i class='bx bx-plus'></i><?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'computer') {?> Thêm mới<?php }?></button>
					</div>
				</div>
			</div>
			<div class="overflow-x-auto">
				<div class="fh-calendar" id="fh-calendar">
					<div class="p-5 text-center text-muted">Loading...</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.selectize-dropdown,
	.selectize-dropdown.form-control {
		height: auto;
		padding: 0;
		margin: 2px 0 0;
		z-index: 1000;
		background: #fff;
		border: 1px solid #ccc;
		border: 1px solid rgba(0,0,0,.15);
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		-webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
		box-shadow: 0 6px 12px rgba(0,0,0,.175)
	}
</style>
<?php }
}
