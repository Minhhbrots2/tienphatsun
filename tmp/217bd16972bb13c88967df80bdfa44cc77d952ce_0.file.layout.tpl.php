<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:14:21
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/layout.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a730cfd7a65e3_31303429',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '217bd16972bb13c88967df80bdfa44cc77d952ce' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/layout.tpl',
      1 => 1785923540,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a730cfd7a65e3_31303429 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/leaflet/leaflet.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"/>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/leaflet/leaflet.draw.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"/>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/leaflet/Control.MiniMap.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/leaflet/Control.FullScreen.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/leaflet/store.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/leaflet/Control.FullScreen.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
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
">
					<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getCode($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['oneProject']->value);?>
</a>
			</li>
			<?php if ($_smarty_tpl->tpl_vars['is_project_block']->value == '1') {?>
			<li class="breadcrumb-item active"><?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
</li>
			<?php }?>
		</ol>
	</nav>
	<div class="clearfix"></div>
	<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("banner_stock",array('total_stock'=>$_smarty_tpl->tpl_vars['total_stock']->value));?>

	<div class="clearfix"></div>
	<div class="card">
		<div class="card-body">
			<div class="d-flex justify-content-center mb-3">
				<div class="btn-group">
					<?php if (!empty($_smarty_tpl->tpl_vars['is_project_block']->value)) {?>
					<a href="<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getLink('stock',array('project_id'=>$_smarty_tpl->tpl_vars['project_id']->value,'block_id'=>$_smarty_tpl->tpl_vars['block_id']->value));?>
" class="btn flex-fill btn-outline-default">
						<i class="bx bx-map-pin"></i> 
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>Xem dạng mặt bằng<?php } else { ?>Mặt bằng<?php }?>
					</a>
					<?php } else { ?>
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/project/p<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
.html" class="btn flex-fill btn-outline-default">
						<i class="bx bx-table"></i> 
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>Xem dạng bảng<?php } else { ?>Bảng<?php }?>
					</a>
					<?php }?>
					<a href="javascript:void(0);" class="btn flex-fill btn-primary">
						<i class="bx bx-map-pin"></i> 
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>Xem dạng mặt bằng<?php } else { ?>Mặt bằng<?php }?>
					</a>
				</div>
			</div>
			<div class="js__block-tabs d-flex flex-wrap justify-content-center mb-3" style="display:none;"></div>
			<div class="map-container">
				<div class="loading text-center text-white">
					<div class="mb-2">
						<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
					</div>
					<p class="mb-0">Đang tải dữ liệu</p>
				</div>
				<div class="map" id="map"></div>
			</div>
		</div>
	</div>	
</div>
<?php echo $_smarty_tpl->tpl_vars['scriptJS']->value;?>


<style type="text/css">
	.map,.map-container{
		margin: auto;
		width: 100%;
		height: 680px;
		position: relative;
		border-radius:3px;
		-moz-border-radius:3px;
		-khtml-border-radius:3px;
		-khtml-border-radius:3px;
	}
	.loading{
		display:none;
		background:rgba(0,0,0,0.5);
		width:150px;
		height:100px;
		padding:20px;
		z-index:9999;
		position:absolute;
		left:50%; top:50%;
		transform:translate(-50%, -50%);
		-moz-transform:translate(-50%, -50%);
		-webkit-transform:translate(-50%, -50%);
		border-radius:10px;
		-moz-border-radius:10px;
		-webkit-border-radius:10px;
		-khtml-border-radius:10px;
		transition:.3s ease-in-out;
		-moz-transition:.3s ease-in-out;
		-webkit-transition:.3s ease-in-out;
		-khtml-transition:.3s ease-in-out;
	}
	.leaflet-container{
		background:var(--bs-white) !important;
	}
	.leaflet-tooltip {
		font-weight: bold;
		font-size: 14px;
		color:var(--bs-white);
		background: rgba(0,0,0,0.8); !important;
		border: none !important;
		box-shadow: none !important;
	}
	.leaflet-interactive{
		outline:none;
	}
	.leaflet-back{
		position:relative;
		color:var(--bs-white);
		background:rgba(0,0,0,0.8);
		padding-left:30px;
		transition: all .3s ease-in-out;
		-moz-transition: all .3s ease-in-out;
		-webkit-transition: all .3s ease-in-out;
		-khtml-transition: all .3s ease-in-out;
	}
	.leaflet-back:hover{
		color:var(--bs-white);
	}
	.leaflet-back:before{
		font:normal normal normal 14px/1 FontAwesome;
		content:"\f112";
		position:absolute;
		left:10px; top:10px;
		width:16px;
		height:14px;
	}
	.fh_AI, .bottom-navbar, .modal{
		z-index:9999 !important;
	}
	@media screen and (max-width:575px){
		.map,.map-container{
			height:450px;
		}
		.leaflet-tooltip{
			font-size: 12px;
		}
	}
	@keyframes dash {
	  to {
		stroke-dashoffset: 0;
	  }
	}
	.animated-border {
	  stroke-dasharray: 10,3; /* Dấu gạch và khoảng cách */
	  stroke-dashoffset: 20;
	  animation: dash 3s linear infinite;
	}
	.leaflet-pane{
		z-index:2;
	}
	/* Pin giọt nước hiển thị giá */
	.lf-price-pin-wrap { background:transparent; border:0; }
	.lf-price-pin {
		width:34px; height:34px; background:#f5e10a;
		border-radius:50% 50% 50% 0; transform:rotate(-45deg);
		box-shadow:0 1px 2px rgba(0,0,0,.35);
		display:flex; align-items:center; justify-content:center;
	}
	.lf-price-pin>span { transform:rotate(45deg); font-weight:700; font-size:11px; line-height:1; color:#3a2a00; white-space:nowrap; }
	.lf-price-pin--dq { background:#960c26; }
	.lf-price-pin--dq>span { color:#fff; }
</style>

<?php if ($_smarty_tpl->tpl_vars['is_map_tiles']->value == '1') {?>
	
	<?php echo '<script'; ?>
 type="text/javascript">
		$(() => {
			map = new L.map('map', {
				minZoom: 1,
				maxZoom: map_configs.max_zoom,
				center: map_configs.center_point,
				fullscreenControl: true,
				fullscreenControlOptions: {
					position: 'topleft'
				},
				zoom:map_configs.curr_zoom
			});
			L.tileLayer(map_configs.tiles, {
				noWrap: true,
				tileSize: 256,
				bounds: map_configs.max_bounds,
				attribution: '<a href="' + PCMS_URL + '">© ' + BRAND_NAME + '</a>',
				maxZoom: map_configs.max_zoom,
				tms: map_configs.tms_enable,
			}).addTo(map);
			let miniMap = new L.Control.MiniMap(
			  L.tileLayer(map_configs.tiles, {
				maxZoom: map_configs.max_zoom,
				minZoom: 1,
				noWrap: true
			  }),{
				toggleDisplay: true,
				minimized: false,
				position: 'bottomleft',
				width: 120,
				height: 80,
				aimingRectOptions: { 
					color: '#ff7800',
					weight: 1, 
					clickable: false 
				}
			  }
			).addTo(map);
			// Thêm công cụ vẽ vào bản đồ
			drawnItems = new L.FeatureGroup();
			map.addLayer(drawnItems);
			// Xử lý khi người dùng vẽ xong
			$Core.project.get_shapes_upgraded(project_id, {"block_id":block_id}, drawnItems);
		});
	<?php echo '</script'; ?>
>
	
<?php } else { ?>
	
	<?php echo '<script'; ?>
 type="text/javascript">
		var map, drawnItems, currentImage, currentMiniMap;
		$(function(){
			var mapMaxZoom = 8;
			var mapMinZoom = (deviceType=='phone' ? 2 : 5);
			var mapMaxResolution = 1.00000000;
			var mapMinResolution = Math.pow(2, mapMaxZoom) * mapMaxResolution;
			//var tileExtent = [0.00000000, -11830.00000000, 16800.00000000, 0.00000000];
			var crs = L.CRS.Simple; // Sử dụng hệ tọa độ đơn giản
			//crs.transformation = new L.Transformation(1, -tileExtent[0], -1, tileExtent[3]);
			crs.scale = function(zoom) {
				return Math.pow(2, zoom) / mapMinResolution;
			};
			crs.zoom = function(scale) {
				return Math.log(scale * mapMinResolution) / Math.LN2;
			};
			// Khởi tạo bản đồ Leaflet (dùng ảnh thay vì bản đồ)
			map = L.map('map', {
				crs: crs, 
				attributionControl: false,
				boxZoom: false,
				doubleClickZoom: true,
				dragging: true,
				keyboard: false,
				maxBoundsViscosity: 1.0,
				maxZoom: mapMaxZoom,
				minZoom: mapMinZoom,
				scrollWheelZoom: true,
				tap: true,
				touchZoom: true,
				zoomControl: false,
				zoomSnap: 0
			});
			L.control.zoom({ 
				position: 'topright' 
			}).addTo(map);
			drawnItems = new L.FeatureGroup();
			map.addLayer(drawnItems);
			// 3 trạng thái: A=ảnh dự án / B=tab phân khu / C=empty.
			// Ưu tiên phân khu tiles: có phân khu tiles thì căn nằm ở đó -> bỏ qua ảnh dự án (State A).
			var _tileBlocks = (typeof map_blocks !== 'undefined' && map_blocks) ? map_blocks.filter(function(b){ return b.is_tiles == 1; }) : [];
			if((typeof has_project_map === 'undefined' || has_project_map == 1) && !_tileBlocks.length){
			// Thêm ảnh nền (State A: dự án có ảnh mặt bằng)
			var img = new Image(), 
				imageUrl = '<?php echo $_smarty_tpl->tpl_vars['img_layout']->value;?>
';
			img.src = imageUrl;
			img.onload = () => {
				var natWidth = img.naturalWidth,
					natHeight = img.naturalHeight,
					imageBounds = [[0,0], [natHeight, natWidth]], // Kích thước ảnh (cao × rộng)
					maxBounds = [[-1000,-1000], [natHeight+1000, natWidth+1000]];
				currentImage = L.imageOverlay(imageUrl, imageBounds).addTo(map);
				// Cố định bản đồ vào đúng vùng ảnh
				map.fitBounds(imageBounds, {animate: true, duration: 1.5});
				// Giới hạn di chuyển bản đồ trong phạm vi ảnh
				map.setMaxBounds(maxBounds);
				// Thêm MiniMap cho ảnh
				currentMiniMap = new L.Control.MiniMap(L.imageOverlay(imageUrl, imageBounds), {
					toggleDisplay: true, 
					position: 'bottomleft',
					zoomLevelOffset: -1,
					width: 120,
					height: 80,
				}).addTo(map);
			};
			$Core.project.get_shapes(project_id, {}, drawnItems);
			} else if(typeof map_blocks !== 'undefined' && map_blocks && map_blocks.length){
				// STATE B: phân khu có bản đồ (tiles/ảnh) -> thanh tab; tự mở phân khu tiles đầu (nếu có)
				var _firstBlk = _tileBlocks[0] || map_blocks[0];
				$Core.project.render_block_tabs(map_blocks, _firstBlk.block_id);
				$Core.project.init_block_map(_firstBlk);
			} else {
				// STATE C: không có ảnh nào -> mặt bằng đang được hoàn thiện
				$Core.project.show_map_empty();
			}
		});	
	<?php echo '</script'; ?>
>
	
<?php }
}
}
