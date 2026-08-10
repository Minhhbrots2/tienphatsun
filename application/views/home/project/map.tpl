<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
<script src="{$URL_JS}/leaflet/leaflet.min.js?v={$upd_version}"></script>
<script src="{$URL_JS}/leaflet/leaflet.draw.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 container-p-y pt-1">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item">
				<a href="{$PCMS_URL}/bang-hang/">Bảng hàng dự án</a>
			</li>
			<li class="breadcrumb-item">
				<a href="{$clsProject->getLinkDetail($project_id,0,0,$oneProject)}">{$clsProject->getCode($project_id,$oneProject)}</a>
			</li>
			<li class="breadcrumb-item">
				<a href="{$clsProject->getLinkDetail($project_id,$block_id,0,$oneProject)}">{$clsProperty->getTitle($block_id)}</a>
			</li>			
			<li class="breadcrumb-item active">{$oneBuilding.title}</li>
		</ol>
	</nav>
	<div class="clearfix"></div>
	{$core->getBlock("banner_stock", ['oneBuilding' => $oneBuilding])}
	<div class="clearfix"></div>
	<div class="card">
		<div class="card-body">
			<div class="d-flex justify-content-center">
				<div class="input-group justify-content-center mb-2 no-shadow">
					{if !empty($vr_link)}
					<a data-toggle="ripple" class="btn text-white{if $deviceType eq 'phone'} btn-sm gap-1 d-flex align-items-center justify-content-center{/if}" title="Xem VR360" href="{$vr_link}" data-fancybox data-type="iframe" style="background:#5a5a5a"><img src="{$URL_IMAGES}/vr360.png" class="w-px-20" style="filter:invert(1);">VR360</a>
					{/if}
					<a data-toggle="ripple" href="{$PCMS_URL}/project/p{$project_id}/b{$building_id}.html" 
						class="btn btn-outline-default{if $deviceType eq 'phone'} btn-sm{/if}">
						<i class="bx bx-table"></i> 
						{if $deviceType ne 'phone'}Xem dạng bảng{else}Bảng{/if}
					</a>
					<a data-toggle="ripple" href="javascript:void(0);" class="btn btn-primary{if $deviceType eq 'phone'} btn-sm{/if}">
						<i class="bx bx-map-alt"></i> 
						{if $deviceType ne 'phone'}Xem dạng mặt bằng{else}Mặt bằng{/if}
					</a>
				</div>
			</div>
			<!-- Start Map -->
			<div class="map" id="map"></div>
			<!-- End Map -->
			<!-- End bảng hàng -->
			{if !empty($onePolicy)}
			<div class="py-2 d-flex flex-wrap gap-2 align-items-center justify-content-center">
				{if !empty($vr_link)}
				<a class="btn btn-outline-default text-main fw-bold{if $deviceType eq 'phone'} flex-fill{/if}" href="{$vr_link}" data-fancybox data-type="iframe" data-caption="{if !empty($vr_source)}{$vr_source}{else}Bản quyền thuộc về masterihomes.com{/if}"><i class="material-icons-outlined text-main">360</i> Xem sa bàn ảo 360<sup>o</sup></a>
				{/if}
				<a href="{$onePolicy.link_ns}" class="btn btn-outline-default{if $deviceType eq 'phone'} flex-fill{/if}" target="_blank">{$clsISO->makeIcon('bx-check-shield','Chính sách bán hàng')} {$oneBuilding.title}</a> 
				<a href="{$onePolicy.link_ms}" class="btn btn-outline-default{if $deviceType eq 'phone'} flex-fill{/if}" target="_blank">{$clsISO->makeIcon('bx-spreadsheet','Phiếu tính giá')} {$oneBuilding.title}</a>
			</div>
			{/if}
		</div>
		{if !empty($oneBuilding.intro)}
		<div class="p-4 mt-4 bg-lighter rounded-2">
			<div class="tinyContent js__readmore-content">
				{$oneBuilding.intro}
			</div>
		</div>
		{/if}
	</div>	
</div>
<script type="text/javascript">
	var building_id = '{$building_id}',
		project_id = '{$project_id}',
		block_id = '{$block_id}';
</script>
{literal}
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
<script type="text/javascript">
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
			imageUrl = '{/literal}{$img_layout}{literal}';
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
</script>
{/literal}
