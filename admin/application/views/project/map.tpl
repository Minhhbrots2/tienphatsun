<link rel="stylesheet" type="text/css" href="{$URL_JS}/leaflet/leaflet.css?v={$upd_version}" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>

<!-- <script async src="https://docs.opencv.org/4.5.1/opencv.js"></script> -->

<!-- <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script> -->

<!-- <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd"></script> -->

<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/leaflet-draw@1.0.4/dist/leaflet.draw.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/leaflet.path.drag@0.0.6/src/Path.Drag.min.js"></script>

<form method="post" action="#" class="p-5">

	<div class="d-flex align-items-center justify-content-between mb-3">

		<div class="d-flex align-items-center gap-2">

			<select class="form-control" required="true" name="project_id" stock_type="{$stock_type}">

				{if !empty($list_projects)}

					{foreach from=$list_projects item = _oProject}

					<option{if $project_id eq $_oProject.project_id} selected{/if} 

						value="{$_oProject.project_id}">{$_oProject.title}</option>

					{/foreach}

				{/if}

			</select>

			<select class="form-control" required="true" name="block_id" stock_type="{$stock_type}">

				<option value="0">Chọn Block</option>

				{if !empty($list_blocks)}

					{foreach from=$list_blocks item = _oBlock}

					<option{if $block_id eq $_oBlock.property_id} selected{/if} 

						value="{$_oBlock.property_id}">{$_oBlock.title}</option>

					{/foreach}

				{/if}

			</select>

			<input type="hidden" name="hid" value="hid" />

			<input type="hidden" name="stock_type" value="{$stock_type}" />

			<button type="submit" title="Tải lại" class="btn btn-icon btn-default">

				{$core->makeIcon('refresh')}

			</button>

		</div>

		<div class="d-flex gap-2 align-items-center">

			<button type="button" onClick="$Core.project.save_stock_shapes(this, event)" project_id="{$project_id}" block_id="{$block_id}" title="Lưu lại" 

			stock_type="{$stock_type}" holderG="project" class="btn btn_save_all btn-default">{$core->makeIcon('check', 'Lưu lại')}</button>

			<button type="button" onClick="$Core.project.merge_stock_shapes(this, event)" project_id="{$project_id}" title="Gộp lại" 

			stock_type="{$stock_type}" holderG="project" class="btn btn-default">{$core->makeIcon('compress', 'Merge')}</button>

			<button type="button" onClick="$Core.project.open_map_config(this, event)" stock_type="{$stock_type}" project_id="{$project_id}" 

			title="Lưu lại" class="btn btn-default">{$core->makeIcon('cog', 'Cấu hình')}</button>

			<button type="button" onClick="$Core.project.loadModalStock(this, event)" project_id="{$project_id}" title="Lưu lại" stock_type="{$stock_type}" holderG="project" class="btn btn-default">{$core->makeIcon('list', 'Quỹ căn')}</button>

		</div>

	</div>

	<div id="map" class="map zindex-1"></div>

</form>

{$scriptJs}

<script type="text/javascript">

	var holderG = 'project',

		project_id = '{$project_id}',

		block_id = '{$block_id}',

		stock_type = '{$stock_type}';

</script>

{literal}

<style type="text/css">

	.map {

		margin: auto;

		width: 100%;

		height: calc(100vh - 200px);

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

	.item_pop_favourite:hover {

		color: red;

	}

</style>

<script type="text/javascript">

	$().ready(function(){

		var map = L.map('map', {

			zoom:3,

			minZoom: 3,

			maxZoom: map_configs.max_zoom,

			center: map_configs.center_point,

			attributionControl: false,

			boxZoom: false,

			doubleClickZoom: true,

			dragging: true,

			keyboard: false,

			maxBoundsViscosity: 1.0,

			scrollWheelZoom: true,

			tap: true,

			touchZoom: true,

			zoomControl: true,

			zoomSnap: 0

		});

		L.tileLayer(map_configs.tiles, {

			noWrap: true,

			tileSize: 256,

			bounds: map_configs.max_bounds,

			attribution: '<a href="/">© Future Tech</a>',

			maxZoom: map_configs.max_zoom,

			tms: map_configs.tms_enable,

		}).addTo(map);

		// Thêm lớp công cụ vẽ vào bản đồ

		var drawnItems = new L.FeatureGroup();

		map.addLayer(drawnItems);

		// Load shapes

		draw_shapes(stock_type, project_id,block_id);

		async function draw_shapes(stock_type, project_id, block_id){

			try {

				let response = await fetch(`/admin/index.php?mod=${mod}

					&act=get_stock_shapes&stock_type=${stock_type}&project_id=${project_id}&block_id=${block_id}&holderG=${holderG}`),

					respJson = await response.json();

				// console.log(respJson);

				if(respJson.msg.indexOf('_success') >= 0){

					drawnItems.clearLayers();

					$Core.project.shapes = respJson.shapes;

					if(respJson.merge_shapes.length){

						$.each(respJson.merge_shapes, (i, shape) => {

							let layer = null, coordinates = shape.coordinates;

							if (shape.shape_type === 'polygon') {

								layer = L.polygon(coordinates,{weight:1, color: '#C00000', draggable: false});

							} else if (shape.shape_type === 'rectangle') {

								layer = L.rectangle(coordinates,{weight:1, color: '#C00000', draggable: false});

							} else if(shape.shape_type === 'circlemarker'){

								layer = L.circleMarker(coordinates.center, { 

									color: '#C00000', 

									radius:coordinates.radius, 

									weight:1,draggable: false

								});

							}

							drawnItems.addLayer(layer);

						});

					}

					$.each(respJson.shapes, (i, shape) => {

						let layer = null, coordinates = shape.coordinates;

						if (shape.shape_type === 'polygon') {

							layer = L.polygon(coordinates,{weight:1,draggable: false});

						} else if (shape.shape_type === 'rectangle') {

							layer = L.rectangle(coordinates,{weight:1,draggable: false});

						} else if(shape.shape_type === 'circlemarker'){

							layer = L.circleMarker(coordinates.center, {radius:coordinates.radius, weight:1,draggable: false});

						}

						layer.holderG = respJson.holderG;

						layer.shape_id = shape.shape_id;

						layer.on('click', async function(e) {

							let shape_id = shape.shape_id, 

								holderG = respJson.holderG;

							try {

								let response = await fetch(`/admin/index.php?mod=${mod}&act=get_pop

								&_leaflet_id=${layer._leaflet_id}&shape_id=${shape_id}&holderG=${holderG}

								&project_id=${project_id}&block_id=${block_id}&stock_type=${stock_type}`);

								let data = await response.text();

								layer.bindPopup(data, {minWidth:300}).openPopup();

								if($(`input[name="stock_code"]`).length > 0) {

									$(`input[name="stock_code"]`).focus();

								}

							} catch (error){

								console.error("Error fetching popup content:", error);

							}

						});

						layer.on('dragend', function(e) {

							let shapes = $Core.project.shapes,

								shape_id = e.target.shape_id;

							if(shapes.length && !$Core.util.isEmpty(shape_id)){

								for (let i = 0; i < shapes.length; i++) {

									if(shape_id == shapes[i].shape_id){

										$Core.project.shapes[i]['coordinates'] = e.target._latlngs;

									}

								}

								// $('.btn_save_all').trigger('click');

							}

						});

						drawnItems.addLayer(layer);

					});

				}

			} catch (err) {

                console.error("Lỗi khi load hình:", err);

            }

		}

		drawControl = new L.Control.Draw({

			edit: {

				featureGroup: drawnItems,

				remove: true // Bật nút xóa

			},

			draw: {

				polyline: false,

				circle: false,

				marker: false

			}

		});

		map.addControl(drawControl);

		// Xử lý khi người dùng vẽ xong

		map.on('draw:created', function (event) {

			var layer = event.layer,

				holderG = 'project',

				shape_id = new Date().getTime(),

				shape_type = event.layerType;

			

			if (!layer) return;

			layer.holderG = holderG;

			layer.shape_id = shape_id;

			// Gán weight nếu có thể

			if (layer.setStyle) {

				layer.setStyle({ weight: 1 });

			}

			if(shape_type == 'circlemarker'){

				var radius = layer.getRadius(),

					center = layer.getLatLng(),

					coordinates = {center : center, 'radius':radius};

			} else {

				var coordinates = layer.getLatLngs()[0].map(function (latlng) {

					return [latlng.lat, latlng.lng]; // Đảm bảo đúng thứ tự

				});

			}

			$Core.project.shapes.push({shape_id, shape_type, coordinates, stock_id: 0});

			drawnItems.addLayer(layer);

			layer.on('click', async function(e) {

				let response = await fetch(`${path_ajax_script}/index.php?mod=${mod}&act=get_pop

					&_leaflet_id=${layer._leaflet_id}&shape_id=${shape_id}&holderG=${holderG}

					&stock_type=${stock_type}&project_id=${project_id}&block_id=${block_id}`);

				let data = await response.text();

				layer.bindPopup(data, {minWidth:300}).openPopup();

			});

		});

		map.on(L.Draw.Event.EDITED, (event) => {

			var shapes = $Core.project.shapes;

			event.layers.eachLayer((layer) => {

				for (let i = 0; i < shapes.length; i++) {

					if(layer.shape_id == shapes[i].shape_id){

						var shape_type = shapes[i].shape_type;

						if(shape_type == 'circlemarker'){

							var radius = layer.getRadius(),

								center = layer.getLatLng(),

								coordinates = {center : center, 'radius':radius};

						} else {

							var coordinates = layer.getLatLngs()[0].map(function (latlng) {

								return [latlng.lat, latlng.lng]; // Đảm bảo đúng thứ tự

							});

						}

						$Core.project.shapes[i]['coordinates'] = coordinates;

					}

				}	

			});

			// Lưu ở đây

			$('.btn_save_all').trigger('click');

		});

		map.on(L.Draw.Event.DELETED, (event) => {

			if (drawnItems.getLayers().length === 0){

				$Core.project.shapes = [];

			} else {

				event.layers.eachLayer((layer) =>  {

					$Core.project.shapes = $Core.project.shapes.filter(shape => shape.shape_id !== layer.shape_id);

					$('.btn_save_all').trigger('click');

				});

			}

		});

		// Hàm sửa hình

		window.edit_shape = function(_this, e) {

			var shape_id = $(_this).attr('shape_id'),

				_leaflet_id = $(_this).attr('_leaflet_id'),

				layer = drawnItems.getLayer(_leaflet_id);

			editHandler = new L.EditToolbar.Edit(map, {

				featureGroup: L.featureGroup([layer])

			});

			editHandler.enable();

			// layer.openPopup();

			$(`.js__edit_shape_${shape_id}`).after(`<button type="button" class="btn flex-fill btn-sm btn-default 

				js__update_shape_${shape_id}">Cập nhật sửa</button>`);

			$_document.on('click', `.js__update_shape_${shape_id}`, (e) => {

				e.preventDefault();

				// Tắt chỉnh sửa

				editHandler.disable(); 

				// Kích hoạt sự kiện EDITED

				map.fire(L.Draw.Event.EDITED, { layers: L.layerGroup([layer]) }); 

				layer.closePopup();

				setTimeout(() => {

					layer.openPopup()

				}, 300);

				return false;

			});	

		};

		window.copy_shape = function(_this, e){

			let shape_type = "polygon",

				shape_id = new Date().getTime(),

				holderG = $(_this).attr('holderG');

			let _leaflet_id = $(_this).attr('_leaflet_id'),

				_layer = drawnItems.getLayer(_leaflet_id);

			if(!_layer) return false;

			if (_layer instanceof L.Rectangle) {

				shape_type = "rectangle";

			} else if (_layer instanceof L.circleMarker) {

				shape_type = "circlemarker";

			}

			// Lấy tọa độ gốc

			let coordinates = _layer.getLatLngs()[0].map(latlng => 

				L.latLng(latlng.lat - 0.05, latlng.lng + 0.05) // Dịch chuyển vị trí mới

			);

			let newLayer;

			if(shape_type == 'polygon'){

				newLayer = L.polygon(coordinates, { weight:1, color: '#8900b8', draggable: true,});

			} else if(shape_type == 'rectangle') {

				newLayer = L.rectangle(coordinates, {weight:1, color: '#8900b8', draggable: true,});

			} else if(shape_type == 'circlemarker') {

				newLayer = L.circleMarker(coordinates, {weight:1, color: '#8900b8', draggable: true,});

			}

			$Core.project.shapes.push({shape_id, shape_type, coordinates, stock_id: 0});

			newLayer.holderG = holderG;

			newLayer.shape_id = shape_id;

			drawnItems.addLayer(newLayer);

			newLayer.on('click', async function(e) {

				let response = await fetch(`${path_ajax_script}/index.php?mod=${mod}&act=get_pop

					&_leaflet_id=${newLayer._leaflet_id}&shape_id=${shape_id}&holderG=${holderG}

					&project_id=${project_id}&block_id=${block_id}&stock_type=${stock_type}`),

					data = await response.text();

				newLayer.bindPopup(data, {minWidth:300}).openPopup();

			});

			newLayer.on('dragend', function(e) {

				let shapes = $Core.project.shapes,

					shape_id = e.target.shape_id;

				if(shapes.length && !$Core.util.isEmpty(shape_id)){

					for (let i = 0; i < shapes.length; i++) {

						if(shape_id == shapes[i].shape_id){

							$Core.project.shapes[i]['coordinates'] = e.target._latlngs;

						}

					}

					// $('.btn_save_all').trigger('click');

				}

			});

			// Đóng Popup

			_layer.closePopup();

		};

		window.delete_shape = function (_this, e) {

			var shape_id = $(_this).attr('shape_id'),

				_leaflet_id = $(_this).attr('_leaflet_id'),

				layer = drawnItems.getLayer(_leaflet_id);

			drawnItems.removeLayer(layer);

			map.fire(L.Draw.Event.DELETED, { layers: L.layerGroup([layer]) }); 

		};

		window.add_stock = function(_this, e){

			e.preventDefault();

			var uid = $(_this).attr('uid'),

				shape_id = $(_this).attr('shape_id'),

				_leaflet_id = $(_this).attr('_leaflet_id'),

				project_id = $(_this).attr('project_id'),

				stock_id = $(`input[name=stock_id][shape_id=${shape_id}]`).val(),

				stock_code = $(`input[name=stock_code][shape_id=${shape_id}]`).val();

			if(!$Core.util.isEmpty(stock_id) && !$Core.util.isEmpty(stock_code)){

				$.each($Core.project.shapes, (_i, _shape) => {

					if(_shape.shape_id == shape_id){

						$Core.project.shapes[_i]['stock_id'] = stock_id;

						$Core.project.shapes[_i]['stock_code'] = stock_code;

						var layer = drawnItems.getLayer(_leaflet_id);

						layer.closePopup();

						$('.btn_save_all').trigger('click');

					}

				});

			} else {

				$(`input[uid=${uid}][shape_id=${shape_id}]`).focus();

			}

			return false;

		};

		window.do_copy = function(_this, e){

			var stock_id = $(_this).attr('stock_id'),

				stock_code = $(_this).attr('stock_code');

			if($('input[name=stock_code]:visible').length){

				var _wrapper = $('input[name=stock_code]:visible').closest('.leaflet-popup-content');

				$('input[name=stock_code]', _wrapper).val(stock_code);

				$('input[name=stock_id]', _wrapper).val(stock_id);

				$('.js__btn_add_stock',_wrapper).trigger('click');

				setTimeout(function(){

					$(_this).closest("li").remove();

				},300);

			}

		}

	});	

</script>

{/literal}