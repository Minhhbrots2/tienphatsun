<?php
/* Smarty version 3.1.33, created on 2026-08-05 16:53:09
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/_footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a730805dc73b0_69907212',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '90fd56b3bf17dec274048730d316cda4a826f25e' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/_footer.tpl',
      1 => 1785923358,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a730805dc73b0_69907212 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
		<!-- <?php if ($_smarty_tpl->tpl_vars['profile_id']->value == @constant('_PROFILE_TECH_ID')) {?>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('callaction');?>

		<?php }?> -->
		<div class="modal right fade" id="online-modal" tabindex="-1" aria-modal="true" aria-hidden="true" role="dialog">
			<div class="modal-dialog modal-dialog-scrollable" role="document">
				<div class="modal-content">
					<div class="modal-header pb-2">
						<h5 class="modal-title text-fs-16">Người Online</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body pt-2">
						<div id="online-list" class="online-list"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl py-2 py-lg-3">
				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone' && ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['act']->value == 'default')) {?>
					<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_ranker_25');?>

				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['sub']->value != 'lucky_wheel' && !($_smarty_tpl->tpl_vars['mod']->value == 'fund' && $_smarty_tpl->tpl_vars['sub']->value == 'dashboard')) {?>
					<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('contact_footer');?>

				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['header_configs']->value['CompanyName']) {?>
					<div class="mb-0 text-center mb-md-0">
						©Copyright <?php echo smarty_modifier_date_format(time(),"%Y");?>
 by <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['header_configs']->value['CompanyName'], ENT_QUOTES, 'UTF-8', true);?>

					</div>
				<?php }?>
            </div>
        </footer>
        <!-- /Footer -->
		<!-- Menu bottom -->
		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
		<div class="bottom-navbar zindex-4">
			<div class="pt-2 pb-4 px-2 d-flex">
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('menu_bottom',array('oneColor'=>$_smarty_tpl->tpl_vars['oneColor']->value));?>

			</div>
		</div>
		<div class="modal fade bottom modal_fade_bottom" id="modal_search_mobile" tabindex="-1" aria-modal="true" role="dialog"> 
			<div class="modal-dialog modal-xs modal-dialog-centered">
				<form class="modal-content">
					<div class="modal-header">
						<h3 class="title_modal text-center flex-fill mb-0">Tìm kiếm</h3>
						<button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-close btn-icon btn-sm p-0" style="background: #ffffff26;box-shadow: 0 0 3px #FFF"><i class='bx bx-x' ></i></button> 
					</div>
					<div class="modal-body">
						<div class="form-group mb-2">
							<label class="form-label">Loại hình</label>
							<div class="btn-group text-nowrap w-100" role="group" aria-label="Hiển thị" <?php echo $_smarty_tpl->tpl_vars['deviceType']->value;?>
>
								<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
								<input type="radio" class="btn-check" name="stock_type" onchange="$Core.helper.handle_stock_type(this, event)" id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" value="<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?> checked="checked"<?php }?>>
								<label data-toggle="ripple" title="Cao tầng" class="btn js__search-stock-type <?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>btn-outline-danger active<?php } else { ?>btn-outline-default<?php }?>" for="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" title="Cao tầng">Cao tầng</label>
								<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
								<input type="radio" class="btn-check" name="stock_type" id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onchange="$Core.helper.handle_stock_type(this, event)" value="<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?> checked="checked"<?php }?>>
								<label data-toggle="ripple" title="Thấp tầng" class="btn js__search-stock-type <?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>btn-outline-danger active<?php } else { ?>btn-outline-default<?php }?>" for="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" title="Thấp tầng">Thấp tầng</label>
								<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
								<input type="radio" class="btn-check" name="stock_type" id="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" onchange="$Core.helper.handle_stock_type(this, event)" value="1"<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == '1') {?> checked="checked"<?php }?>>
								<label data-toggle="ripple" title="Thông tin" class="btn js__search-stock-type<?php if ($_smarty_tpl->tpl_vars['get_stock_type']->value == '1') {?> btn-outline-danger active<?php } else { ?> btn-outline-default<?php }?>" for="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" title="Thông tin">Thông tin</label>
							</div>
						</div>
						<div class="form-group mb-2">
							<label class="form-label">Từ khóa</label>
							<input type="text" class="form-control form-control-lg js__top-search-input top_select_all shadow-none" name="keyword" placeholder="Tìm bất cứ thứ gì..."  value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" />
						</div>
					</div>
					<div class="modal-footer justify-content-center">
						<button data-toggle="ripple" class="btn btn-outline-primary btn-lg m-0 form-control" type="button" 
							onClick="$Core.mobile.search_all(this,event)">Tìm kiếm</button>
					</div>
				</form>
			</div>
		</div>
		<?php }?>
		<!-- /Menu bottom -->
        <div class="content-backdrop fade"></div>
    </div>
    <!-- /Content wrapper -->
</div>
<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('chat');?>

<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('quick_action');?>

<?php }
}
