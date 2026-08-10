<?php
/* Smarty version 3.1.33, created on 2026-08-08 14:07:44
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/map_FH.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76d5c05d0e48_30446428',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ae785a6b162b302528a7a0a3344e979dba63e638' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/map_FH.tpl',
      1 => 1784691720,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76d5c05d0e48_30446428 (Smarty_Internal_Template $_smarty_tpl) {
?><form method="post" action="#" class="p-5">

	<div class="d-flex align-items-center justify-content-between mb-3">

		<div class="d-flex align-items-center gap-2">

			<select class="form-control" required="true" name="project_id" toId="slb_BlockId" onChange="$Core.project.select_block(this, event)" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
">

				<?php if (!empty($_smarty_tpl->tpl_vars['list_projects']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, '_oProject');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
?>

					<option<?php if ($_smarty_tpl->tpl_vars['project_id']->value == $_smarty_tpl->tpl_vars['_oProject']->value['project_id']) {?> selected<?php }?> 

						value="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</option>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php }?>

			</select>

			<select class="form-control" name="block_id" id="slb_BlockId" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" 

				onChange="$Core.project.select_building(this, event)" toId="slb_BuildingId">

				<option value="">Lựa chọn phân khu</option>

				<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>

					<option<?php if ($_smarty_tpl->tpl_vars['block_id']->value == $_smarty_tpl->tpl_vars['_oBlock']->value['property_id']) {?> selected<?php }?> 

						value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
">Phân khu <?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php }?>

			</select>

			<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

			<select class="form-control" name="building_id" id="slb_BuildingId" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
">

				<option value="">Lựa chọn toà</option>

				<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oBuilding');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBuilding']->value) {
?>

					<option<?php if ($_smarty_tpl->tpl_vars['building_id']->value == $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id']) {?> selected<?php }?> 

						value="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'];?>
">Phân khu <?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
</option>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				<?php }?>

			</select>

			<?php }?>

			<input type="hidden" name="hid" value="hid" />

			<input type="hidden" name="stock_type" value="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" />

			<button type="submit" title="Tải lại" class="btn btn-icon btn-default">

				<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('refresh');?>


			</button>

		</div>

		<div class="d-flex gap-2 align-items-center">

			<button type="button" onClick="$Core.project.save_stock_code_shapes(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" title="Lưu lại" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" holderG="stock_FH" class="btn btn_save_all btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check','Lưu lại');?>
</button>

			<button type="button" onClick="$Core.project.map_FH.addPoint(this, event)" stock_type="<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" toId="map" title="Lưu lại" _type="_ADD" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus','Thêm điểm');?>
</button>

		</div>

	</div>

	<div class="box_tooltip d-none">

		<div class="item_tooltip rounded-3">

			<div class="box_code">C3Z2-08-08A</div>

			<div class="body_tooltip">

				<div class="form-row">

					<div class="col-6 col-xs-6">

						<div class="d-flex flex-column box_text">

							<span class="">Thông thủy</span>

							<span class="text-value">45.5m<sup>2</sup></span>

						</div>

						<div class="d-flex flex-column box_text">

							<span class="">Giá TTS</span>

							<span class="text-price">2,6 tỷ</span>

						</div>

					</div>

					<div class="col-6 col-xs-6">

						<div class="d-flex flex-column box_text">

							<span class="">Hướng</span>

							<span class="text-value">TN</span>

						</div>

						<div class="d-flex flex-column box_text">

							<span class="">Giá vay</span>

							<span class="text-price">3,6 tỷ</span>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div id="map" class="map"  style="max-width:1000px; margin: auto;position:relative">

		<?php if (!empty($_smarty_tpl->tpl_vars['shapes']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['shapes']->value, 'shape', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['shape']->value) {
?>

				<div id="item_drag_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" shap_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" class="draggable item_drag" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" code="<?php echo $_smarty_tpl->tpl_vars['shape']->value['code'];?>
" top="<?php echo $_smarty_tpl->tpl_vars['shape']->value['top'];?>
" left="<?php echo $_smarty_tpl->tpl_vars['shape']->value['left'];?>
" style="top:<?php echo $_smarty_tpl->tpl_vars['shape']->value['top'];?>
%;left:<?php echo $_smarty_tpl->tpl_vars['shape']->value['left'];?>
%;'"><?php echo $_smarty_tpl->tpl_vars['shape']->value['code'];?>
</div>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php }?>

		<img src="<?php echo $_smarty_tpl->tpl_vars['image_map_src']->value;?>
" alt="" class="w-100 h-auto">

	</div>

</form>

<?php echo '<script'; ?>
 type="text/javascript">

	var block_id = '<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
',

		project_id = '<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
',

		stock_type = '<?php echo $_smarty_tpl->tpl_vars['stock_type']->value;?>
',

		building_id = '<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
',

		has_block = '<?php echo $_smarty_tpl->tpl_vars['more_information']->value['has_block'];?>
';

<?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"><?php echo '</script'; ?>
>



<style>

	.item_drag {

		width: 20px;

		height: 20px;

		background: #5f9ea099;

		position: absolute;

		border: 1px solid cadetblue;

		border-radius: 50%;

		font-size: 9px;

		line-height: 20px;

		text-align: center;

		color: #FFF;

		cursor: pointer

	}

</style>

<?php echo '<script'; ?>
>

	$(function(){

		// Lấy kích thước container

		var containerWidth = $("#map").width();

		var containerHeight = $("#map").height();

		$(".draggable").draggable({

		// Giới hạn kéo thả bên trong container

			containment: "#map",

			drag: function(event, ui) {

				// Tính toán tọa độ theo phần trăm dựa trên vị trí hiện tại

				var percentX = (ui.position.left / containerWidth) * 100;

				var percentY = (ui.position.top  / containerHeight) * 100;

				$(this).css({"left":percentX,"top":percentY});

				$(this).attr("left",percentX);

				$(this).attr("top",percentY);

			},

			stop: function(event, ui) {

				// Cập nhật hiển thị tọa độ với 2 chữ số thập phân

				$Core.project.map_FH.update_shape();

			}

		});

	});

<?php echo '</script'; ?>
>

<?php }
}
