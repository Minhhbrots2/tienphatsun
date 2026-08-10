<?php
/* Smarty version 3.1.33, created on 2026-07-30 14:16:36
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/stock_search/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6afa543429f6_14479992',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '596b0ac4f34f17bc546a317f9093ea6ba8c701fd' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/stock_search/index.tpl',
      1 => 1784299566,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6afa543429f6_14479992 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="form-group form-row flex-wrap">

	<div class="col-6 col-sm-4 col-md-3 col-xxxl-1/12 flex-fill om-xs:mb-1 sm:mb-1<?php if (!empty($_smarty_tpl->tpl_vars['is_project_block']->value)) {?> d-none<?php }?>">

		<label class="form-text d-none d-lg-block mb-1">Phân khu/Block</label>

		<?php if ($_smarty_tpl->tpl_vars['project_id']->value == @constant('_PROJECT_VHGG_ID')) {?>

		<select multiple class="form-control search_field multiselect" data-placeholder="Phân khu" 

		data-header="true" data-filter="true" data-width="100%" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" 

		onChange="$Core.stock.load_range(this,event)" toId="slb_Range_Id" data-field="block_ids[]">

		<?php if (!empty($_smarty_tpl->tpl_vars['list_range_groups']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_range_groups']->value, '_oGroup');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oGroup']->value) {
?>

			<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['get_blocks_ids']->value,$_smarty_tpl->tpl_vars['_oGroup']->value['property_id'])) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['title'];?>
</option>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php }?>

		</select>

		<?php } else { ?>

		<select multiple class="form-control search_field multiselect" data-placeholder="Phân khu" data-header="true" data-filter="true" 

		data-width="100%" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" onChange="$Core.stock.load_range(this,event)" toId="slb_Range_Id" data-field="block_ids[]">

			<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, 'block', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['block']->value) {
?>

				<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['get_blocks_ids']->value,$_smarty_tpl->tpl_vars['block']->value['property_id'])) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['block']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['block']->value['title'];?>
</option>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

		</select>

		<?php }?>

	</div>

	<div class="col-6 col-sm-4 col-md-3 col-xxxl-1/12 flex-fill om-xs:mb-1 sm:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Dãy nhà</label>

		<select class="form-control search_field multiselect" id="slb_Range_Id" data-placeholder="Dãy nhà" data-width="100%" data-header="true" data-filter="true" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" onChange="$Core.stock.do_search(this,event)" multiple data-field="range_ids[]">

			<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oBuilding');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBuilding']->value) {
?>

				<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['get_buildings_ids']->value,$_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'])) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
</option>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

		</select>

	</div>

	<div class="col-6 col-sm-4 col-md-3 col-xxxl-1/12 flex-fill om-xs:mb-1 sm:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Loại hình</label>

		<select class="form-control search_field multiselect" data-placeholder="Chọn loại hình" data-width="100%" onChange="$Core.stock.do_search(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" data-header="true" data-filter="true" multiple data-field="type_ids[]">

			<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_TYPE_VILLA',$_smarty_tpl->tpl_vars['get_type_ids']->value,'',true);?>


		</select>

	</div>

	<?php if ($_smarty_tpl->tpl_vars['permiss_view_stock_resource']->value == '1' || $_smarty_tpl->tpl_vars['clsISO']->value->check_view_resource(@constant('_BLOCK_TYPE_LOWFLOOR_SALE'))) {?>

	<div class="col-6 col-sm-4 col-md-3 col-xxxl-1/12 flex-fill om-xs:mb-1 sm:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Đại lý</label>

		<select class="form-control search_field multiselect" data-placeholder="Chọn đại lý" data-width="100%" onChange="$Core.stock.do_search(this, event)" data-header="true" data-filter="true" multiple data-field="agency_ids[]">

			<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_AGENCY',$_smarty_tpl->tpl_vars['get_agency_ids']->value,$_smarty_tpl->tpl_vars['get_type_ids']->value,'',true);?>


		</select>

	</div>

	<?php } else { ?>

	<div class="col-6 col-sm-4 col-md-3 col-xxxl-1/12 flex-fill om-xs:mb-1 sm:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Loại quỹ</label>

		<select class="form-control search_field multiselect" data-placeholder="Loại quỹ" data-width="100%" data-header="true" data-filter="true" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" onChange="$Core.stock.do_search(this,event)" multiple data-field="stock_hold_ids[]">

			<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_STOCK_HOLD',$_smarty_tpl->tpl_vars['get_stock_hold_id']->value,'',true);?>


		</select>

	</div>

	<?php }?>

	<div class="col-6 col-sm-4 col-md-3 col-xxxl-1/12 flex-fill om-xs:mb-1 sm:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Hướng</label>

		<select class="form-control search_field multiselect" data-placeholder="Hướng nhà" data-width="100%" data-header="true" data-filter="true" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" onChange="$Core.stock.do_search(this, event)" multiple data-field="home_direction_ids[]">

			<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_DIRECTION',$_smarty_tpl->tpl_vars['get_home_direction_ids']->value,'',true);?>


		</select>

	</div>

	<div class="col-6 col-sm-4 col-md-3 flex-fill col-xxxl-1/12">

		<label class="form-text d-none d-lg-block mb-1">Khoảng giá (tỷ)</label>

		<div class="btn-group w-full js__block-price-list">

			<button type="button" data-toggle="ripple" data-target="price" data-min="0" data-max="150" class="multiselect js__block-price-drowndown btn btn-outline-default w-100 hide-arrow dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">

				<span class="multiselect-selected-text" text="Khoảng giá">Khoảng giá</span>

				<b class="caret"></b>

			</button>

			<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-start">

				<div class="px-2 py-2">

					<div class="form-group mb-3">

						<label class="col-form-label">Mức giá</label>

						<div class="slider-container">

							<div id="price-slider"></div>

						</div>

					</div>

					<label class="form-label">Hoặc nhập khoảng (đơn vị VNĐ)</label>

					<div class="form-group form-row">

						<div class="col-6">

							<div class="form-floating">

								<input type="text" class="form-control numberonly search_field price-In no-focus" data-field="price_min" name="price_min" placeholder="Giá từ" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['_ss_price_min']->value;?>
">

								<label for="floatingInput">Giá từ</label>

							  </div>

						</div>

						<div class="col-6">

							<div class="form-floating">

								<input type="text" class="form-control numberonly search_field price-In no-focus" data-field="price_max" name="price_max" placeholder="Giá đến" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['_ss_price_max']->value;?>
">

								<label for="floatingInput">Giá đến</label>

							  </div>

						</div>

					</div>

				</div>

				<hr class="my-2" />

				<div class="d-flex align-items-center p-2 justify-content-between">

					<button type="button" onClick="$Core.stock.clear_search(this, event)" class="btn btn-outline-default">Đặt lại</button>

					<button type="button" onClick="$Core.stock.start_search(this, event)" class="btn btn-primary">Áp dụng</button>

				</div>

			</div>

		</div>

	</div>

	<div class="col-6 col-sm-4 col-md-3 flex-fill col-xxxl-1/12">

		<label class="form-text d-none d-lg-block mb-1">Diện tích(m2)</label>

		<div class="btn-group w-full js__block-area-list">

			<button type="button" data-toggle="ripple" data-target="area" data-min="0" data-max="500" class="multiselect js__block-area-drowndown btn btn-outline-default w-100 hide-arrow dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">

				<span class="multiselect-selected-text" text="Diện tích">Diện tích</span>

				<b class="caret"></b>

			</button>

			<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-start">

				<div class="px-2 py-2">

					<div class="form-group mb-3">

						<label class="col-form-label">Diện tích</label>

						<div class="slider-container">

							<div id="area-slider"></div>

						</div>

					</div>

					<label class="form-label">Hoặc nhập khoảng (đơn vị m2)</label>

					<div class="form-group form-row">

						<div class="col-6">

							<div class="form-floating">

								<input type="text" class="form-control numberonly search_field no-focus" data-field="area_min" name="area_min" placeholder="Diện tích từ" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['_ss_area_min']->value;?>
">

								<label for="floatingInput">Diện tích từ</label>

							  </div>

						</div>

						<div class="col-6">

							<div class="form-floating">

								<input type="text" class="form-control numberonly search_field no-focus" data-field="area_max" name="area_max" placeholder="Diện tích đến" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['_ss_area_max']->value;?>
">

								<label for="floatingInput">Diện tích đến</label>

							  </div>

						</div>

					</div>

				</div>

				<hr class="my-2" />

				<div class="d-flex align-items-center p-2 justify-content-between">

					<button type="button" onClick="$Core.stock.clear_search(this, event)" class="btn btn-outline-default">Đặt lại</button>

					<button type="button" onClick="$Core.stock.start_search(this, event)" class="btn btn-primary">Áp dụng</button>

				</div>

			</div>

		</div>

	</div>

	<div class="dropdown">

		<button data-bs-toggle="dropdown" class="btn zYqzRAbxuN btn-icon hide-arrow btn-outline-default dropdown-toggle" aria-expanded="false" data-bs-auto-close="outside"><i class="bx bx-filter"></i></button>

		<div class="dropdown-menu p-3 mega-dropdown-menu w-px-300">

			<?php if ($_smarty_tpl->tpl_vars['permiss_view_stock_resource']->value == '1' || $_smarty_tpl->tpl_vars['clsISO']->value->check_view_resource(@constant('_BLOCK_TYPE_LOWFLOOR_SALE'))) {?>

			<div class="form-group mb-2">

				<label class="form-text d-none d-lg-block mb-1">Loại quỹ</label>

				<select class="form-control search_field multiselect" data-placeholder="Loại quỹ" data-width="100%" data-header="true" data-filter="true" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" onChange="$Core.stock.do_search(this,event)" multiple data-field="stock_hold_ids[]">

					<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_STOCK_HOLD',$_smarty_tpl->tpl_vars['get_stock_hold_id']->value,'',true);?>


				</select>

			</div>

			<?php }?>

			<div class="form-group mb-2">

				<label class="form-text d-none d-lg-block mb-1">Loại hình ký</label>

				<select class="form-control search_field multiselect" data-placeholder="Loại hình ký" data-width="100%" data-header="true" data-filter="true" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" onChange="$Core.stock.do_search(this,event)" multiple data-field="contract_type_ids[]">

					<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_CONTRACT_TYPE',$_smarty_tpl->tpl_vars['get_contract_type_ids']->value,'',true);?>


				</select>

			</div>

			<div class="form-group mb-2">

				<label class="form-text d-none d-lg-block mb-1">Tiêu chuẩn bàn giao</label>

				<select class="form-control js__search-TCBG-select-list search_field multiselect" data-placeholder="Tiêu chuẩn bàn giao" data-width="100%" data-header="true" data-filter="true" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" onChange="$Core.stock.do_search(this,event)" multiple data-field="TCBG_ids[]"></select>

			</div>

		</div>

	</div>

</div>



<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		$('.js__block-area-drowndown,.js__block-price-drowndown').each((_i, _elem) => {

			var min = $(_elem).data('min'),

				max = $(_elem).data('max'),

				target = $(_elem).data('target');

			$(_elem).on('shown.bs.dropdown', () => {

				var _min_input = $(`input[name=${target}_min]`).val(),

					_max_input = $(`input[name=${target}_max]`).val();

				if(target == 'price'){

					_min_input = $Core.util.toNumber(_min_input)/1000000000;

					_max_input = $Core.util.toNumber(_max_input)/1000000000;

				}	

				$(`#${target}-slider`).slider({

					range: true,

					min: parseInt(min),

					max: parseInt(max),

					values: [parseInt(_min_input), parseInt(_max_input)],

					slide: function( event, ui ) {

						var _min_number = ui.values[0],

							_max_number = ui.values[1];

						if(target == 'price'){

							_min_number =  $Core.chart.formatPrice(_min_number*1000000000),

							_max_number =  $Core.chart.formatPrice(_max_number*1000000000);

						}

						$(`input[name=${target}_min]`).val(_min_number);

						$(`input[name=${target}_max]`).val(_max_number);

					}

				});	

			}).on('hidden.bs.dropdown', () => {

				$(`#${target}-slider`).slider("destroy");

			});

		});

	});

<?php echo '</script'; ?>
>





<?php }
}
