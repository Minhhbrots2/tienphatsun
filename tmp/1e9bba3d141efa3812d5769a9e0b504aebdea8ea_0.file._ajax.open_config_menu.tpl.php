<?php
/* Smarty version 3.1.33, created on 2026-08-07 11:27:39
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.open_config_menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a755ebb986b28_30495447',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1e9bba3d141efa3812d5769a9e0b504aebdea8ea' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.open_config_menu.tpl',
      1 => 1786076676,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a755ebb986b28_30495447 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
<div class="modal fade bottom modal_fade_bottom" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" tabindex="-1" aria-modal="true" role="dialog"> 
<?php }?>
	<div class="modal-dialog modal-xs modal-dialog-centered">
		<form action="" class="modal-content overflow-hidden">
			<div class="modal-header border-bottom d-flex align-items-center justify-content-between">
				<div class="d-flex gap-2 align-items-center">
					<button type="button" class="btn_close btn btn-icon btn-sm" data-bs-dismiss="modal" aria-label="Close"><i class='bx bx-x' ></i></button>
					<h5 class="modal-title" id="modalTopTitle">Tùy chỉnh tính năng</h5>
				</div>				
				<button type="button" class="btn btn-outline-primary btn-sm ml-2" onClick="$Core.mobile.saveMenu(this,event)">Lưu</button>				
			</div>
			<div class="modal-body scroller p-0">
				<div class="p-3" style="background: #0080000a">
					<div class="d-flex flex-wrap list_active drag_menu_active" id="list_active_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['utilities_active']->value, '_oUtilities', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oUtilities']->value) {
?>
							<div class="item_menu_grid item_menu_active w-25 mb-2 px-2" id="<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" >
								<label class="text-dark text-center text-center d-block" href="javascript:void(0);" for="item_active_<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
									<div class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto position-relative">
										<i class="fs-30 text-main <?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['icon'];?>
"></i>
										<button class="btn btn-icon btn-xs bg-white rounded-pill" onClick="$Core.mobile.removeMenu(this,event)" toId="item_unactive_<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" style="position: absolute;top: -12px;right: -12px;box-shadow: 0px 0px 2px #00000061"><i class='bx bx-minus'></i></button>
									</div>
									<span class="text-dark fw-semibold fs-12"><?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['title'];?>
</span>
									<input type="checkbox" name="utilites_menu[]" value="<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['id'];?>
" id="item_active_<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="d-none" checked>
								</label>
							</div>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					</div>					
				</div>
				<div class="alert-warning text-center p-2">Cần chọn tối thiểu 8 tính năng </div>
				<div class="p-3 list_unactive">
					<h3 class="text-dark">Tính năng đề xuất</h3>
					<div class="form-row row-cols-4  " style="row-gap: 10px">
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_utilities']->value, '_oUtilities', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oUtilities']->value) {
?>
							<div class="col item_menu_grid position-relative <?php if (!$_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oUtilities']->value['id'],$_smarty_tpl->tpl_vars['utilities_unactive']->value)) {?>d-none<?php }?>" id="item_unactive_<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['id'];?>
_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
								<a class="text-dark text-center text-center d-block" href="javascript:void(0);">
									<div class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
										<i class="fs-30 text-main <?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['icon'];?>
"></i>
										<button class="btn btn-icon btn-xs bg-white rounded-pill" onClick="$Core.mobile.addMenu(this,event)" toId="list_active_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" options='{"utilities_id":"<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['id'];?>
","icon":"<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['icon'];?>
","title":"<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['title'];?>
"}' utilities_id="<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['id'];?>
" style="position: absolute;top: -12px;right: -12px;box-shadow: 0px 0px 2px #00000061"><i class='bx bx-plus'></i></button>
									</div>
									<span class="text-dark fw-semibold fs-12"><?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['title'];?>
</span>
								</a>								
							</div>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					</div>
				</div>
			</div>
		</form>
	</div>
<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
</div>
<?php }?>

<style>
	.ui-sortable-placeholder{
		visibility: visible !important
	}
.ui-sortable-helper {
	display:block;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important; /* Giữ nền trong suốt nếu icon có card */
    pointer-events: none; /* Tránh cản trở việc tính toán drop zone */
}
/* Tùy chỉnh thêm để bản sao trông nổi bật hơn khi kéo */
.ui-sortable-helper .item_icon {
    box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
    transform: scale(1.05); /* Phóng to nhẹ để tạo cảm giác đang nhấc lên */
    transition: transform 0.2s;
}
</style>
<?php }
}
