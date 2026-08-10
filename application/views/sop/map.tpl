<link rel="stylesheet" href="{$URL_CSS}/leaflet/leaflet.min.css?v={$upd_version}"/>
<link rel="stylesheet" href="{$URL_CSS}/leaflet/leaflet.draw.css?v={$upd_version}"/>
<link rel="stylesheet" href="{$URL_CSS}/leaflet/Control.MiniMap.css?v={$upd_version}" />
<script src="{$URL_JS}/leaflet/store.min.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="d-flex align-items-center flex-wrap justify-content-between pb-2">
		<div class="sop_left mb-2 mb-lg-0">
			<h5 class="fw-bold mb-1">Danh sách gọi Telesales {$oneProject.title}</h5>
			<span class="srp-total-count text-muted">Tổng có <strong class="total_record text-main">0</strong> kết quả.</span>
		</div>
		<div class="d-flex xs:w-100 align-centertext-center gap-2">
			<a href="https://docs.google.com/spreadsheets/d/1bNa0cJFapZ2bQolg_yyhY_VIOpSr7HQnDgX0Ji8WgXY/edit?gid=822135708#gid=822135708" title="Xem bảng Excel" class="btn flex-fill btn-outline-primary"><i class='bx bx-link-external'></i> Excel</a>
			<a href="{$PCMS_URL}/telesale.html" title="Xem danh sách" class="btn flex-fill btn-outline-danger"><i class='bx bx-table'></i> Danh sách</a>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="card">
		<div class="card-body py-2">
			<div id="sum" class="form-row row-cols-2 row-cols-lg-6 my-2">
				{foreach from=$arr_blocks key = _oKey item = _oBlock}
				<div class="col mb-2 flex-fill mb-lg-0 ">
					<div class="p-3 rounded-2 text-white tele_box relative tele_box-{$_oKey}" 
						style="background-color:{$_oBlock.bgcolor}">
						<h3 class="mb-2 fs-4">Loading...</h3>
						<p class="mb-0">{$_oBlock.title}</p>
					</div>
				</div>
				{/foreach}
			</div>
			<hr class="my-4" />
			<div class="map-container">
				<div class="loading">
					<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>
				</div>
				<div class="map" id="map"></div>
			</div>
		</div>
	</div>	
</div>
{$scriptJS}
<script>
	var _TELESALE_STATUS_UNCHECK_ID = '{$smarty.const._TELESALE_STATUS_UNCHECK_ID}',
		_TELESALE_STATUS_CHECKED_ID = '{$smarty.const._TELESALE_STATUS_CHECKED_ID}',
		_TELESALE_STATUS_SOLD_ID = '{$smarty.const._TELESALE_STATUS_SOLD_ID}';
</script>
{literal}
<style type="text/css">
	.map,.map-container{
		margin: auto;
		width: 100%;
		height: 720px;
		position: relative;
		border-radius:3px;
		-moz-border-radius:3px;
		-khtml-border-radius:3px;
		-khtml-border-radius:3px;
	}
	.loading{
		display:none;
		background:rgba(0,0,0,0.5);
		width:55px;
		height:50px;
		padding:10px;
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
</style>
<script type="text/javascript">
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
		// Thêm ảnh nền
		var img = new Image(), 
			imageUrl = '{/literal}{$img_layout}{literal}';
		img.src = imageUrl;
		img.onload = () => {
			var natWidth = img.naturalWidth,
				natHeight = img.naturalHeight,
				imageBounds = [[0,0], [natHeight, natWidth]], // Kích thước ảnh (cao × rộng)
				maxBounds = [[-50,-50], [natHeight+50, natWidth+50]];
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
		// Thêm công cụ vẽ vào bản đồ
		drawnItems = new L.FeatureGroup();
		map.addLayer(drawnItems);
		// Xử lý khi người dùng vẽ xong
		$Core.sop.get_shapes(project_id, {}, drawnItems);
	});	
</script>
{/literal}