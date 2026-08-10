{if $more_information.is_tiles eq '1'}
	<link rel="stylesheet" href="{$URL_CSS}/leaflet/leaflet.min.css"/>
	<script src="{$URL_JS}/leaflet/leaflet.min.js?v={$upd_version}"></script>
	<div id="map_canvas" class="map_canvas"></div>
	{$scriptJs}
	{literal}
	<style type="text/css">
		.map_canvas{
			width:100%;
			height:800px;
			border-radius:3px;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			overflow:hidden;
		}
		.leaflet-container{
			background:var(--bs-white) !important;
		}
		@media screen and (max-width:575px){
			.map_canvas{
				height:400px;
			}
		}
	</style>
	<script type="text/javascript">
		$(() => {
			var map = L.map('map_canvas', {
				minZoom: 1,
				maxZoom: map_configs.max_zoom,
				center: map_configs.center_point,
				zoom:3
			});
			L.tileLayer(map_configs.tiles, {
				noWrap: true,
				tileSize: 256,
				bounds: map_configs.max_bounds,
				attribution: '<a href="'+PCMS_URL+'">© ' + BRAND_NAME + '</a>',
				maxZoom: map_configs.max_zoom,
				tms: map_configs.tms_enable,
			}).addTo(map);
		});
	</script>
	{/literal}
{else}
<div class="img-container rounded-1 relative overflow-hidden">
	<div id="panzoom-container" class="d-block panzoom-container">
		<img class="img-fluid w-100" src="{$clsISO->getGoogleUrl($more_information.layout)}" />
	</div>
	<div class="zoom-cmd d-flex flex-column">
		<a id="zoom-in" title="Zoom in" class="zoom-in"></a>
		<a id="zoom-out" title="Zoom out" class="zoom-out"></a>
	</div>
	<div class="zoom-map">
		<map name="positionMap" class="positionMapClass">
			<area id="topPositionMap" shape="rect" coords="20,0,40,20" title="move up" alt="move up">
			<area id="leftPositionMap" shape="rect" coords="0,20,20,40" title="move left" alt="move left">
			<area id="rightPositionMap" shape="rect" coords="40,20,60,40" title="move right" alt="move right">
			<area id="bottomPositionMap" shape="rect" coords="20,40,40,60" title="move bottom" alt="move bottom">
		</map>
		<img src="https://myoceancity.vn/application/themes/images/SmartZoom/position.png" usemap="#positionMap">  
	</div>
</div>
{/if}