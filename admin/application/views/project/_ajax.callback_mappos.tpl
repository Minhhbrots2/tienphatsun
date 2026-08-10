{literal}
<script>
	var _map = L.map('map_canvas_{/literal}{$uid}{literal}', {
		crs: L.CRS.Simple,
		attributionControl: false,
		boxZoom: false,
		doubleClickZoom: true,
		dragging: true,
		keyboard: false,
		maxBoundsViscosity: 1.0,
		maxZoom: 5.2,
		minZoom: -2.2,
		scrollWheelZoom: true,
		tap: true,
		touchZoom: true,
		zoomControl: true,
		zoomSnap: 0
	});
	// Thêm lớp công cụ vẽ vào bản đồ
	var _drawnItems = new L.FeatureGroup();
	_map.addLayer(_drawnItems);
	// Thêm ảnh nền
	var _img = new Image(), 
		imageUrl = `{/literal}{$image_map_src}{literal}`;
	_img.src = imageUrl;
	_img.onload = () => {
		var natWidth = _img.naturalWidth,
			natHeight = _img.naturalHeight,
			imageBounds = [[0,0], [natHeight, natWidth]],
			maxBounds = [[-1500,-1500], [natHeight+1500, natWidth+1500]]; 
		L.imageOverlay(imageUrl, imageBounds).addTo(_map);
		// Cố định bản đồ vào đúng vùng ảnh
		_map.fitBounds(imageBounds);
		// Giới hạn di chuyển bản đồ trong phạm vi ảnh
		_map.setMaxBounds(maxBounds); 
		// Vẽ shapes
		get_shapes(`{/literal}{$stock_type}{literal}`, `{/literal}{$project_id}{literal}`, `{/literal}{$block_id}{literal}`,`{/literal}{$building_id}{literal}`, _map, _drawnItems);
	}
	async function get_shapes(stock_type, project_id, block_id, building_id, _map, _drawnItems){
		try {
			let response = await fetch(`/admin/index.php?mod=${mod}
				&act=get_shapes&stock_type=${stock_type}&project_id=${project_id}
				&block_id=${block_id}&building_id=${building_id}'.$_getType.'`),
				respJson = await response.json();
			if(respJson.msg.indexOf('_success') >= 0){
				_drawnItems.clearLayers();
				$Core.project.shapes = respJson.shapes;
				$.each(respJson.shapes, (i, shape) => {
					if(shape.html_shapes != ""){
						let _layer = null;
						if (shape.shape_type === 'polygon') {
							_layer = L.polygon(shape.coordinates,{weight:1});
						} else if (shape.shape_type === 'rectangle') {
							_layer = L.rectangle(shape.coordinates,{weight:1});
						} 
						_layer.addTo(_map);
						let _latlng = _layer.getBounds().getCenter();
						if(typeof(shape.latlng) !== 'undefined'){
							_latlng = shape.latlng;
						}
						console.log(shape.array_shapes);
						var _direction = 'top',
							_marker = L.marker(_latlng, {
							draggable: true,
							opacity: 1 // Ẩn marker
						}).addTo(_map);
						if(typeof(shape.direction) !== 'undefined'){
							_direction = shape.direction;
						}
						_marker.bindTooltip(`${shape.html_shapes}`, {
							permanent: true, 
							direction: _direction, 
							interactive: true,
							className : "box_tool_tip"
						}).openTooltip();
						_marker.on("dragend", function (e) {
							var latlng = e.target.getLatLng();
							_marker.setLatLng(latlng);
							$.post(`${path_ajax_script}/index.php?mod=${mod}&act=update_pos`, {
								 'stock_shape_id' : respJson.stock_shape_id,
								 'shape_id' : shape.shape_id,
								 'latlng' : JSON.stringify(latlng)
							}, (html) => {
								console.log(html);
							});
						});
						function getCentroid(latlngs) {
							let latSum = 0,
								lngSum = 0,
								count = latlngs.length;
							latlngs.forEach(latlng => {
								latSum += latlng.lat;
								lngSum += latlng.lng;
							});
							return L.latLng(latSum / count, lngSum / count);
						}
						var centroid = getCentroid(_layer.getLatLngs()[0]),
							polyline = L.polyline([centroid, _latlng], { 
							color: 'yellow', 
							weight: 3
						}).addTo(_map);
						_drawnItems.addLayer(_layer);
					}
				});
			}
		} catch (err) {
			console.error("Lỗi khi load hình:", err);
		}
	}
</script>
{/literal}