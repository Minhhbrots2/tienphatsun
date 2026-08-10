<?php
/* Smarty version 3.1.33, created on 2026-08-05 14:25:12
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/map.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72e558015622_28807073',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '460f53a4bc5b8444071c017f985f101e15de3482' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/map.tpl',
      1 => 1784300231,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72e558015622_28807073 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/leaflet/leaflet.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/leaflet/leaflet.draw.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>

<div class="container-xxl flex-grow-1 container-p-y pt-1">

	<nav aria-label="breadcrumb">

		<ol class="breadcrumb mb-2">

			<li class="breadcrumb-item">

				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bang-hang/">Bảng hàng dự án</a>

			</li>

			<li class="breadcrumb-item">

				<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,0,0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getCode($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['oneProject']->value);?>
</a>

			</li>

			<li class="breadcrumb-item">

				<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['block_id']->value,0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['block_id']->value);?>
</a>

			</li>			

			<li class="breadcrumb-item active"><?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</li>

		</ol>

	</nav>

	<div class="clearfix"></div>

	<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("banner_stock",array('oneBuilding'=>$_smarty_tpl->tpl_vars['oneBuilding']->value));?>


	<div class="clearfix"></div>

	<div class="card">

		<div class="card-body">

			<div class="d-flex justify-content-center">

				<div class="input-group justify-content-center mb-2 no-shadow">

					<?php if (!empty($_smarty_tpl->tpl_vars['vr_link']->value)) {?>

					<a data-toggle="ripple" class="btn text-white<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm gap-1 d-flex align-items-center justify-content-center<?php }?>" title="Xem VR360" href="<?php echo $_smarty_tpl->tpl_vars['vr_link']->value;?>
" data-fancybox data-type="iframe" style="background:#5a5a5a"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/vr360.png" class="w-px-20" style="filter:invert(1);">VR360</a>

					<?php }?>

					<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/project/p<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
/b<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
.html" 

						class="btn btn-outline-default<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm<?php }?>">

						<i class="bx bx-table"></i> 

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>Xem dạng bảng<?php } else { ?>Bảng<?php }?>

					</a>

					<a data-toggle="ripple" href="javascript:void(0);" class="btn btn-primary<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm<?php }?>">

						<i class="bx bx-map-alt"></i> 

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>Xem dạng mặt bằng<?php } else { ?>Mặt bằng<?php }?>

					</a>

				</div>

			</div>

			<!-- Start Map -->

			<div class="map" id="map"></div>

			<!-- End Map -->

			<!-- End bảng hàng -->

			<?php if (!empty($_smarty_tpl->tpl_vars['onePolicy']->value)) {?>

			<div class="py-2 d-flex flex-wrap gap-2 align-items-center justify-content-center">

				<?php if (!empty($_smarty_tpl->tpl_vars['vr_link']->value)) {?>

				<a class="btn btn-outline-default text-main fw-bold<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> flex-fill<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['vr_link']->value;?>
" data-fancybox data-type="iframe" data-caption="<?php if (!empty($_smarty_tpl->tpl_vars['vr_source']->value)) {
echo $_smarty_tpl->tpl_vars['vr_source']->value;
} else { ?>Bản quyền thuộc về masterihomes.com<?php }?>"><i class="material-icons-outlined text-main">360</i> Xem sa bàn ảo 360<sup>o</sup></a>

				<?php }?>

				<a href="<?php echo $_smarty_tpl->tpl_vars['onePolicy']->value['link_ns'];?>
" class="btn btn-outline-default<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> flex-fill<?php }?>" target="_blank"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-check-shield','Chính sách bán hàng');?>
 <?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</a> 

				<a href="<?php echo $_smarty_tpl->tpl_vars['onePolicy']->value['link_ms'];?>
" class="btn btn-outline-default<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> flex-fill<?php }?>" target="_blank"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-spreadsheet','Phiếu tính giá');?>
 <?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</a>

			</div>

			<?php }?>

		</div>

		<?php if (!empty($_smarty_tpl->tpl_vars['oneBuilding']->value['intro'])) {?>

		<div class="p-4 mt-4 bg-lighter rounded-2">

			<div class="tinyContent js__readmore-content">

				<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['intro'];?>


			</div>

		</div>

		<?php }?>

	</div>	

</div>

<?php echo '<script'; ?>
 type="text/javascript">

	var building_id = '<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
',

		project_id = '<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
',

		block_id = '<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
';

<?php echo '</script'; ?>
>



<style type="text/css">

	.map {

		margin: auto;

		width: 100%;

		height: 750px;

		position: relative;

		border-radius:3px;

		-moz-border-radius:3px;

		-khtml-border-radius:3px;

		-khtml-border-radius:3px;

	}

	.leaflet-image-layer{

		border-radius:4px;

		-moz-border-radius:4px;

		-khtml-border-radius:4px;

		-khtml-border-radius:4px;

	}

	.leaflet-container{

		background:#3E3E3E !important;

	}

	.leaflet-pane{

		z-index:2;

	}

	.leaflet-tooltip {

		font-weight: bold;

		font-size: 11px;

		color:var(--bs-white);

		background: #a04123;

		border: none !important;

		box-shadow: none !important;

		border-radius:10px 0 10px 0;

	}

	.leaflet-tooltip:before,

	.leaflet-tooltip .leaflet-tooltip-arrow {

		display: none;

	}

	.fh_AI, .bottom-navbar, .modal, .dropdown-menu{

		z-index:9999 !important;

	}

	@media screen and (max-width:575px){

		.map{

			height:calc(100vh - 150px);

		}

		.leaflet-tooltip{

			font-size: 12px;

		}

	}

</style>

<?php echo '<script'; ?>
 type="text/javascript">

	var map, drawnItems;

	$(function() {

		map = L.map('map', {

			crs: L.CRS.Simple,

			attributionControl: false,

			boxZoom: true,

			doubleClickZoom: true,

			dragging: true,

			keyboard: false,

			maxBoundsViscosity: 1.0,

			minZoom: -3,

			maxZoom: 5,

			scrollWheelZoom: true,

			tap: true,

			touchZoom: true,

			zoomControl: true,

			zoomSnap: 0,

			zoom:3

		});

		console.log(map.getZoom());

		// Thêm công cụ vẽ vào bản đồ

		drawnItems = new L.FeatureGroup();

		map.addLayer(drawnItems);

		// Thêm ảnh nền

		var img = new Image(), 

			imageUrl = '<?php echo $_smarty_tpl->tpl_vars['img_layout']->value;?>
';

		img.src = imageUrl;

		img.onload = () => {

			var natWidth = img.naturalWidth,

				natHeight = img.naturalHeight,

				imageBounds = [[0,0], [natHeight, natWidth]], // Kích thước ảnh (cao × rộng)

				maxBounds = [[-1000,-1000], [natHeight+1000, natWidth+1000]];  

			L.imageOverlay(imageUrl, imageBounds).addTo(map);

			// Cố định bản đồ vào đúng vùng ảnh

			map.fitBounds(imageBounds);

			// Giới hạn di chuyển bản đồ trong phạm vi ảnh

			map.setMaxBounds(maxBounds); 

			// Set Zoom

			$Core.project.draw_shapes(block_id, building_id, {}, map, drawnItems);

		};

	});	

<?php echo '</script'; ?>
>



<?php }
}
