<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:51:32
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_search/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315b4ef80e0_08084529',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8500d01d950f4ed8271b88691fcdde8ad7a14fb0' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_search/index.tpl',
      1 => 1785927056,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315b4ef80e0_08084529 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="navbar-nav align-items-center">
	<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
		<div class="search_header border search_header_mb input-group flex-nowrap rounded-2 px-2 py-1" id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
">
			<button type="button" data-toggle="ripple" class="btn_dropdown btn-sm btn-icon fs-12 hide-arrow btn dropdown-toggle text-black fw-semibold" data-bs-auto-close="outside" data-text_def="Loại căn" data-bs-toggle="dropdown" aria-expanded="true">
				<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
				fill="currentColor" viewBox="0 0 24 24" >
				<path d="m12 15.41 5.71-5.7-1.42-1.42-4.29 4.3-4.29-4.3-1.42 1.42z"></path>
				</svg>
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
			<div class="d-flex align-items-center pl-1 border-left ml-1 flex-fill gap-1">
				<input type="text" class="form-control form-control-sm border-0 p-0" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="Tìm bất cứ thứ gì..." onkeyup="$Core.helper.search_all(this, event)" onfocus="$Core.helper.search_suggest_focus(this, event)"  onblur="$Core.helper.search_suggest_blur(this, event)">
			</div>
			<div class="search_suggest rounded-3" style="display: none;">
				<div class="ss-empty">Đang tải gợi ý...</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="d-flex align-items-center nav-item position-relative">
			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
				<div class="btn-froup w-px-80">
					<select name="stock_type" class="form-select form-control no-focus form-option-sm" onchange="$Core.helper.handle_stock_type(this, event)">
						<option value="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?> selected<?php }?>>Cao tầng</option>
						<option value="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?> selected<?php }?>>Thấp tầng</option>
						<option value="1"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == '1') {?> selected<?php }?>>Thông tin</option>
					</select>
				</div>
			<?php } else { ?>
				<div class="btn-group text-nowrap " role="group" aria-label="Hiển thị" <?php echo $_smarty_tpl->tpl_vars['deviceType']->value;?>
>
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<input type="radio" class="btn-check" name="stock_type" onchange="$Core.helper.handle_stock_type(this, event)" id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" value="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?> checked="checked"<?php }?>>
					<label data-toggle="ripple" title="Cao tầng" class="btn btn-sm js__search-stock-type btn-outline-default<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?> active<?php } else {
}?>" for="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" title="Cao tầng"><?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>CT<?php } else { ?>Cao tầng<?php }?></label>
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<input type="radio" class="btn-check" name="stock_type" id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onchange="$Core.helper.handle_stock_type(this, event)" value="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?> checked="checked"<?php }?>>
					<label data-toggle="ripple" title="Thấp tầng" class="btn btn-sm js__search-stock-type btn-outline-default<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?> active<?php }?>" for="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" title="Thấp tầng"><?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>TT<?php } else { ?>Thấp tầng<?php }?></label>
					<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<input type="radio" class="btn-check" name="stock_type" id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onchange="$Core.helper.handle_stock_type(this, event)" value="1"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == '1') {?> checked="checked"<?php }?>>
					<label data-toggle="ripple" title="Thông tin" class="btn btn-sm js__search-stock-type btn-outline-default<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == '1') {?>  active<?php }?>" for="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" title="Thông tin">Thông tin</label>
				</div>
			<?php }?>
			<div class="search_header position-relative">
				<input type="text" class="form-control border-0 js__top-search-input top_select_all shadow-none" 
				placeholder="Tìm bất cứ thứ gì..." onkeyup="$Core.helper.search_all(this, event)" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" onfocus="$Core.helper.search_suggest_focus(this, event)"  onblur="$Core.helper.search_suggest_blur(this, event)" />
				<div class="search_suggest" style="display:none">
					<div class="ss-empty">Đang tải gợi ý...</div>
				</div>
			</div>		
		</div>
	<?php }?>
</div>
<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
	
	<?php echo '<script'; ?>
 type="text/javascript">
		$(function(){
			$_document.on('keydown', '.js__top-search-input', $Core.util.delay(function(){
				var _this = $(this), _keyword = _this.val();
				if(!$Core.util.isEmpty(_keyword)){
					$.post(PCMS_URL+'/index.php?mod=ajax&sub=helper&act=search_stock', {
						'ms_code' : _keyword
					}, function(respJson){
						if(respJson.html.indexOf('not_found')>=0){} else {
							_this.val("");
							$Core.popup.open('auto','auto',respJson.html,respJson.uid);
						}
					},'json');
				}
			},1000));
		});
	<?php echo '</script'; ?>
>
	
<?php }
}
}
