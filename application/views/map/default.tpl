<link rel="stylesheet" type="text/css" href="{URL_CSS}/leaflet/leaflet.css?v={$upd_version}" />
<link rel="stylesheet" href="{URL_CSS}/leaflet/leaflet.draw.css?v={$upd_version}"/>
<link rel="stylesheet" href="{URL_CSS}/leaflet/leaflet-routing-machine.css?v={$upd_version}" />
<script src="{URL_JS}/leaflet/leaflet.js?v={$upd_version}"></script>
<script src="{URL_JS}/leaflet/leaflet.draw.js?v={$upd_version}"></script>
<script src="{URL_JS}/leaflet/Path.Drag.min.js?v={$upd_version}"></script> 
<script src="{URL_JS}/leaflet/leaflet-routing-machine.min.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="d-flex justify-content-between align-items-center header-page">
			<div class="d-flex flex-column">
				<h5 class="fw-bold mb-1">Bản đồ vị trí dự án</h5> 
				<span class="text-muted">Vị trí dự án bất động sản.</span>
			</div>
			<div class="mt-2">
				<button data-toggle="ripple" class="btn btn-outline-default dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-haspopup="true" aria-expanded="false">Chọn dự án ({$info_locations|count})</button>
				{if !empty($info_locations)}
				<div class="dropdown-menu dropdown-menu-end" style="z-index: 1003;">
					<div class="p-3 router" style="height: 50vh;overflow: auto;">
						{foreach from = $info_locations item = _oProject key=key name=loop}
						<h3 class="card-title mb-3 fs-5 item-router" data-index="{$smarty.foreach.loop.index}" data-map-zoom="{$_oProject.infomation_ex.map_zoom}" style="cursor: pointer;">{$key + 1} - {$_oProject.title}</h3>
						{/foreach}
					</div>
				</div>
				{/if}
			</div>
		</div>
    </div>
	<div class="mt-3 box-map">
		<div class="position-relative w-100 h-100">
			<div class="position-relative min-height-500 zindex-1" id="map" 
				data-location="{$location|escape:'htmlall'}" data-map-zoom="{$map_zoom}"></div>
		</div>
	</div>
</div>
{foreach from = $info_locations item = _oProject}
<div class="box-info-project card w-px-250 h-100 no-shadow project-{$_oProject.project_id} d-none">
	<a class="d-block" href="{$clsProject->getLinkDetail($_oProject.project_id,0,0,'overview',$_oProject)}" title="{$_oProject.title}">
		<div class="position-relative text-white mb-3">
			<span class="position-absolute top-px-20 right-px-20 bg-success rounded-pill py-1 px-3 fs-12">Đang mở bán</span>
			<img class="card-img-top img-project img-fluid" src="{$_oProject.image}" style="width: 100%; width: 100%; height: 177px;">
		</div>
		<div class="d-flex align-items-center justify-content-between">
			<div class="awe__project-info">
				<h4 class="card-title mb-2">
					<span class="text-dark fw-semibold">{$_oProject.title}</span>
				</h4>
				<p class="text-dark mb-1">
					<i class='bx bx-map'></i> {$_oProject.infomation_ex.address}
				</p>
				{if !empty($_oProject.apartment)}
				<div class="d-flex gap-1 mb-1 align-items-center text-muted">
					<i class='bx bx-home-alt'></i> Quy mô: {$_oProject.apartment}
				</div>
				{/if}
				{if !empty($_oProject.arcreage)}
				<div class="d-flex gap-1 align-items-center text-muted">
					<i class='bx bx-code'></i> Diện tích: {$_oProject.arcreage}
				</div>
				{/if}
				<div class="d-flex group-button-detail mt-2 align-items-center gap-2">
					<a href="{$clsProject->getLinkDetail($_oProject.project_id,0,0,'overview',$_oProject)}" class="btn flex-fill btn-link">
						<i class='bx bx-right-arrow-alt'></i>Thông tin</a>
					<a href="{$clsProject->getLinkInfo($_oProject.project_id, 0, 0, '_map')}" class="btn flex-fill btn-link">
						<i class='bx bx-right-arrow-alt'></i>Chi tiết</a>
				</div>
			</div>
		</div>
	</a>
</div>
{/foreach}
