<?php
/* Smarty version 3.1.33, created on 2026-08-06 08:31:18
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/exclusive_search/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a73e3e6d1a1f5_61034511',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '22450ceac034b874ee6ac1c1411b9e2c26201cf7' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/exclusive_search/index.tpl',
      1 => 1785927035,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a73e3e6d1a1f5_61034511 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="form-row om-sm:mb-2">

	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Dự án</label>

		<select class="form-control search_field multiselect" onChange="$Core.stock.select_block(this, event)" 

		name="project_id" id="slb_Project_Id" toId="slb_Block_Id" data-width="100%" data-field="project_id">

			<option value="0">Chọn dự án</option>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_projects']->value, 'project', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['project']->value) {
?>

			<option<?php if ($_smarty_tpl->tpl_vars['project']->value['project_id'] == $_smarty_tpl->tpl_vars['_ss_project_id']->value) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['project']->value['title'];?>
</option>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		</select>

	</div>

	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Phân khu</label>

		<select class="form-control search_field multiselect" onChange="$Core.stock.select_building(this, event)" 

		data-placeholder="Phân khu" data-width="100%" data-header="true" data-filter="true" multiple id="slb_Block_Id" 

		toId="slb_Building_Id" data-field="blocks_ids[]">

			<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, 'block', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['block']->value) {
?>

				<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['_ss_blocks_ids']->value,$_smarty_tpl->tpl_vars['block']->value['property_id'])) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['block']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['block']->value['title'];?>
</option>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

		</select>

	</div>

	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Tòa căn hộ</label>

		<select class="form-control search_field multiselect" onChange="$Core.stock.do_search(this, event)" data-width="100%" 

		data-placeholder="Tòa căn hộ" data-header="true" data-filter="true" multiple id="slb_Building_Id" data-field="building_ids[]">

			<?php if (!empty($_smarty_tpl->tpl_vars['_ss_blocks_ids']->value)) {?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_ss_buildings']->value, 'list_buildings', false, 'block_id');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['block_id']->value => $_smarty_tpl->tpl_vars['list_buildings']->value) {
?>

				<optgroup label="<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['block_id']->value);?>
">

					<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, 'building', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['building']->value) {
?>

						<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['_ss_building_ids']->value,$_smarty_tpl->tpl_vars['building']->value['property_id'])) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['building']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['building']->value['title'];?>
</option>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<?php }?>

				</optgroup>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php } else { ?>

				<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, 'building', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['building']->value) {
?>

					<option value="<?php echo $_smarty_tpl->tpl_vars['building']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['building']->value['title'];?>
</option>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php }?>

			<?php }?>

		</select>

	</div>

	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Loại căn</label>

		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Loại căn" data-width="100%" data-allowclear="true" onChange="$Core.stock.do_search(this, event)" multiple data-field="bedroom_ids[]">

			<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_BEDROOM',$_smarty_tpl->tpl_vars['_ss_bedroom_ids']->value);?>


		</select>

	</div>

	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Khoảng tầng</label>

		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Khoảng tầng" data-width="100%" data-allowclear="true" onChange="$Core.stock.do_search(this, event)" multiple data-field="floor_range[]">

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_range_floors']->value, '_oRange');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oRange']->value) {
?>

			<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['_ss_floor_range']->value,$_smarty_tpl->tpl_vars['_oRange']->value)) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oRange']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_oRange']->value;?>
</option>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		</select>

	</div>

	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Hướng BC</label>

		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Hướng BC" data-width="100%" data-allowclear="true" multiple onChange="$Core.stock.do_search(this, event)" data-field="direction_ids[]">

			<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_DIRECTION',$_smarty_tpl->tpl_vars['_ss_direction_ids']->value);?>


		</select>

	</div>

	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill om-xs:mb-1">

		<label class="form-text d-none d-lg-block mb-1">Loại hình</label>

		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Loại hình" data-width="100%" data-allowclear="true" multiple onChange="$Core.stock.do_search(this, event)" data-field="type_ids[]">

			<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getSelectByPropertyV2('_TYPE',$_smarty_tpl->tpl_vars['_ss_type_ids']->value);?>


		</select>

	</div>

	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill">

		<label class="form-text d-none d-lg-block mb-1">Trục căn</label>

		<select class="form-control search_field multiselect" data-header="true" data-filter="true" data-placeholder="Trục căn" data-width="100%" data-allowclear="true" multiple onChange="$Core.stock.do_search(this, event)" data-field="axis_ids[]">

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_range_axis']->value, 'axis');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['axis']->value) {
?>

			<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkInArray($_smarty_tpl->tpl_vars['_ss_axis_ids']->value,$_smarty_tpl->tpl_vars['axis']->value)) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['axis']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['axis']->value;?>
</option>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		</select>

	</div>

	<div class="col-6 col-lg-20 col-xxxl-1/10 flex-fill">

		<label class="form-text d-none d-lg-block mb-1">Khoảng giá (tỷ)</label>

		<div class="btn-group w-full js__block-price-list">

			<button type="button" data-toggle="ripple" class="multiselect js__block-price-drowndown btn btn-outline-default w-100 hide-arrow dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">

				<span class="multiselect-selected-text" text="Khoảng giá">Khoảng giá</span>

				<b class="caret"></b>

			</button>

			<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-start">

				<div class="px-2 py-2">

					<div class="form-group mb-3">

						<label class="col-form-label">Mức giá</label>

						<div class="slider-container">

							<div id="price-range"></div>

						</div>

					</div>

					<label class="form-label">Hoặc nhập khoảng (đơn vị VNĐ)</label>

					<div class="form-group form-row">

						<div class="col-6">

							<div class="form-floating">

								<input type="text" class="form-control numberonly search_field price-In no-focus" 

								data-field="price_min" name="price_min" placeholder="Giá từ" maxlength="255" 

								readonly value="<?php echo $_smarty_tpl->tpl_vars['_ss_price_min']->value;?>
">

								<label for="floatingInput">Giá từ</label>

							  </div>

						</div>

						<div class="col-6">

							<div class="form-floating">

								<input type="text" class="form-control numberonly search_field price-In no-focus" 

								data-field="price_max" name="price_max" placeholder="Giá đến" maxlength="255" 

								readonly value="<?php echo $_smarty_tpl->tpl_vars['_ss_price_max']->value;?>
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

</div>



<style type="text/css">

	.btn-outline-default{

		background:var(--bs-white);

	}

	.multiselect-container {

		width: max-content;

	}

	@media screen and (min-width:1400px){

		.col-xxxl-1\/10{

			width:10%;

		}

	}

</style>

<?php echo '<script'; ?>
 type="text/javascript">

	var _rsSlider;

	$('.js__block-price-drowndown').on('shown.bs.dropdown', () => {

		var _min_price = $('input[name=price_min]').val(),

			_max_price = $('input[name=price_max]').val(),

			_min = $Core.util.toNumber(_min_price)/1000000000,

			_max = $Core.util.toNumber(_max_price)/1000000000;

		$("#price-range").slider({

			min: 0,

			max: 15,

			range: true,

			values: [parseInt(_min), parseInt(_max)],

			slide: function( event, ui ) {

				var _min = ui.values[0],

					_max = ui.values[1],

					_min_price =  $Core.chart.formatPrice(_min*1000000000),

					_max_price =  $Core.chart.formatPrice(_max*1000000000);

				$('input[name=price_min]').val(_min_price);

				$('input[name=price_max]').val(_max_price);

			}

		});	

	}).on('hidden.bs.dropdown', () => {

		$("#price-range").slider("destroy");

	});

<?php echo '</script'; ?>
>

<?php }
}
