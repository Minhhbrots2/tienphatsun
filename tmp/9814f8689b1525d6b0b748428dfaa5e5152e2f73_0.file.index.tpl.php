<?php
/* Smarty version 3.1.33, created on 2026-08-06 11:12:17
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/search_mobile/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7409a17ab277_31522423',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9814f8689b1525d6b0b748428dfaa5e5152e2f73' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/search_mobile/index.tpl',
      1 => 1785927050,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7409a17ab277_31522423 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
<div class="position-relative d-flex align-items-center box_search_header">						
	<button data-toggle="ripple" type="button" class="btn btn-icon icon_menu btn_search_header rounded-pill <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?>text-white<?php }?>" onclick="$Core.search_top.show_search(this,event)" toId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" ><i class='bx bx-search <?php if (!($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == "default" && $_smarty_tpl->tpl_vars['act']->value == "default")) {?>fs-24<?php }?>'></i></button>
	<div class="search_header search_header_home_mb input-group flex-nowrap rounded-pill px-2 py-1 " id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
		<button type="button" data-toggle="ripple" class="btn_dropdown btn-sm fs-12 px-1 d-flex align-items-center btn dropdown-toggle justify-content-between text-black fw-semibold" data-bs-auto-close="outside" data-text_def="Loại căn" data-bs-toggle="dropdown" aria-expanded="true"><div class="material-ink animate" ></div>
			<span class="select-text-content fs-12 select-check-bedroom ng-binding" id="label_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" ><?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>Cao tầng<?php } elseif ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>Thấp tầng<?php } else { ?>Thông tin<?php }?></span>
		</button>
		<ul class="dropdown-menu position-absolute" style="">
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_highfloor">
					<input gid="type_all" type="radio" name="stock_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-check-input" id="chk_type_highfloor" title="Cao tầng" value="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" <?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?> checked<?php }?> onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" >
					<span class="form-check-label ml-1">Cao tầng</span>
				</label>
			</li>
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_lowfloor">
					<input gid="type_all" type="radio" name="stock_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-check-input" id="chk_type_lowfloor" title="Thấp tầng" value="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?> checked<?php }?> onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
"  >
					<span class="form-check-label ml-1">Thấp tầng</span>
				</label>
			</li>
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_info">
					<input gid="type_all" type="radio" name="stock_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-check-input" id="chk_type_info" title="Thông tin" value="1" onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == 1) {?> checked<?php }?>  >
					<span class="form-check-label ml-1">Thông tin</span>
				</label>
			</li>
		</ul>
		<div class="d-flex align-items-center pl-2 border-left ml-2 flex-fill gap-1">
			<input type="text" class="form-control form-control-sm border-0 p-0" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="Tìm bất cứ thứ gì..." onkeyup="$Core.helper.search_all(this, event)" onfocus="$Core.helper.search_suggest_focus(this, event)"  onblur="$Core.helper.search_suggest_blur(this, event)">
			<i class="bx bx-search fs-20 text-main"></i>
		</div>
		<div class="search_suggest rounded-3" style="display: none;">
			<div class="ss-empty">Đang tải gợi ý...</div>
		</div>
	</div>
</div>
<!--<div class="position-relative d-flex align-items-center box_search_header">						
	<button data-toggle="ripple" type="button" class="btn btn-icon icon_menu btn_search_header rounded-pill text-white" onclick="$Core.search_top.show_search(this,event)" toId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" ><i class='bx bx-search'></i></button>
	<div class="search_header search_header_home_mb input-group flex-nowrap rounded-pill px-2 py-1 " id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
		<button type="button" data-toggle="ripple" class="btn_dropdown btn-sm fs-12 px-1 d-flex align-items-center btn dropdown-toggle justify-content-between text-black fw-semibold" data-bs-auto-close="outside" data-text_def="Loại căn" data-bs-toggle="dropdown" aria-expanded="true"><div class="material-ink animate" ></div>
			<span class="select-text-content fs-12 select-check-bedroom ng-binding" id="label_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" ><?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>Cao tầng<?php } elseif ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>Thấp tầng<?php } else { ?>Thông tin<?php }?></span>
		</button>
		<ul class="dropdown-menu" style="">
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_highfloor">
					<input gid="type_all" type="radio" name="stock_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-check-input" id="chk_type_highfloor" title="Cao tầng" value="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
" <?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?> checked<?php }?> onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" >
					<span class="form-check-label ml-1">Cao tầng</span>
				</label>
			</li>
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_lowfloor">
					<input gid="type_all" type="radio" name="stock_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-check-input" id="chk_type_lowfloor" title="Thấp tầng" value="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?> checked<?php }?> onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
"  >
					<span class="form-check-label ml-1">Thấp tầng</span>
				</label>
			</li>
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_info">
					<input gid="type_all" type="radio" name="stock_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" class="form-check-input" id="chk_type_info" title="Thông tin" value="1" onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == 1) {?> checked<?php }?>  >
					<span class="form-check-label ml-1">Thông tin</span>
				</label>
			</li>
		</ul>
		<div class="d-flex align-items-center pl-2 border-left ml-2 flex-fill gap-1">
			<input type="text" class="form-control form-control-sm border-0 p-0" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="Tìm bất cứ thứ gì..." onkeyup="$Core.helper.search_all(this, event)" onfocus="$Core.helper.search_suggest_focus(this, event)"  onblur="$Core.helper.search_suggest_blur(this, event)">
			<i class="bx bx-search fs-20 text-main"></i>
		</div>
		<div class="search_suggest rounded-3" style="top: 93px; display: none;">
			<div class="ss-empty">Đang tải gợi ý...</div>
		</div>
	</div>
</div>--><?php }
}
