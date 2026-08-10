<div class="ui-card">
	<div class="ui-card__section">
		<header class="ui-stack ui-stack--wrap mb-3">
			<h2 class="ui-stack-item ui-stack-item--fill ui-heading">Bản đồ</h2>
		</header>
		<div class="ui-type-container">
			<div class="p-md-3">
				<div class="form-group">
					<div class="col-xs-12 col-md-12">
						<div class="input-group w-100">
							<input id="search-input" name="map_address" value="{$more_information.map_address}" type="text" class="form-control" placeholder="Nhập địa chỉ"/>
						</div>
						<div class="form-group">
							<ul id="suggestions" class="autocomplete-list"></ul>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<label class="col-form-label">Nhấc con trỏ đặt vào địa điểm của bạn.</label>
						 <div style="width:100%; height:400px; overflow:hidden;z-index:1" id="map_canvas"></div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12 col-md-6">
						<input type="text" id="map_la" value="{$more_information.map_la}" name="map_la" class="form-control" />
					</div>
					<div class="col-xs-12 col-md-6">
						<input type="text" id="map_lo" value="{$more_information.map_lo}" name="map_lo" class="form-control" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui-card">
	<div class="ui-card__section">
		<header class="ui-stack ui-stack--wrap mb-3">
			<h2 class="ui-stack-item ui-stack-item--fill ui-heading">Map phân khu</h2>
			<div class="ui-stack-item">
				<button type="button" class="btn btn-default btn-add_property-map" _type="block" _openFrom="_project" project_id="">+ Thêm</button>
			</div>
		</header>
		<div class="ui-type-container">
			<div class="p-md-3 box-location-block box-group-location">
				<div class="form-row">
					<div class="col-12 col-md-6 col-lg-8">
						<div class="form-group">
							<div class="col-xs-12 col-md-6">
								<label class="label-control">Cấu hình Zoom cho bản đồ</label>
								<input type="number" min="0" max="19" value="{$more_information.map_zoom}" name="map_zoom" class="form-control" placeholder="Độ phóng đại của bản đồ"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-form-label">Nhấc con trỏ đặt vào địa điểm của bạn.</label>
								 <div class="canvas_type" style="width:100%; height:400px; overflow:hidden;z-index:1" _type="block" id="my_canvas_block"></div>
							</div>
						</div>					
					</div>
					<div class="col-12 col-md-6 col-lg-4">
						<div class="overflow-y-auto" style="max-height: 500px">
							{if $more_information.location_block|@count > 0}
								{foreach from = $more_information.location_block item = locationItem name=loop}
								<div class="form-group item-location item-location_block mt-3 d-flex align-item-center">
									<div class="form-row">
										<div class="col-xs-12 col-md-12 mt-2">
											<label class="form-label position-location">Vị trí {$smarty.foreach.loop.iteration}</label>
										</div>
										<div class="col-xs-12 col-md-12 mt-2">
											<div class="input-group w-100">
												<input id="search-input" name="map_address_block[]" value="{$locationItem.address}" type="text" class="form-control map_address_block" placeholder="Nhập địa chỉ"/>
											</div>
											<div class="form-group">
												<ul id="suggestions" class="autocomplete-list suggestions"></ul>
											</div>
										</div>
										<div class="col-xs-12 col-md-4 mt-2">
											<select name="map_block_id[]" id="" class="form-control form-select">
												{$clsProperty->getSelectByPropertyOrigin("_BLOCK",$pvalTable,$locationItem.block_id)}
											</select>
										</div>
										<div class="col-xs-12 col-md-4 mt-2">
											<input type="text" id="map_la_{$smarty.foreach.loop.iteration}" value="{$locationItem.lat}" name="map_block_lat[]" class="form-control input_map_block_lat" placeholder="lat"/>
										</div>
										<div class="col-xs-12 col-md-4 mt-2">
											<input type="text" id="map_lo_{$smarty.foreach.loop.iteration}" value="{$locationItem.lng}" name="map_block_lng[]" class="form-control input_map_block_lng" placeholder="long"/>
										</div>
									</div>
									<div class="delete-location {if $more_information.location_block|@count <= 1} d-none {/if}">
										<div class="col-xs-12 col-md-1 mt-2" style="padding-top: 26px;">
											<button title="Xóa" class="btn btn-default btn-delete-location-map" data-type="block"><i class="fa fa-trash"></i></button>
										</div>
									</div>
								</div>
								{/foreach}
							{else}
								<div class="form-group item-location item-location_block mt-3 d-flex align-item-center">
									<div class="form-row">
										<div class="col-xs-12 col-md-12 mt-2">
											<label class="form-label position-location">Vị trí 1</label>
										</div>
										<div class="col-xs-12 col-md-12 mt-2">
											<div class="input-group w-100">
												<input id="search-input" name="map_address_block[]" value="{$locationItem.address}" type="text" class="form-control map_address_block" placeholder="Nhập địa chỉ"/>
											</div>
											<div class="form-group">
												<ul id="suggestions" class="autocomplete-list suggestions"></ul>
											</div>
										</div>
										<div class="col-xs-12 col-md-4 mt-2">
											<select name="map_block_id[]" id="" class="form-control form-select">
												{$clsProperty->getSelectByPropertyOrigin("_BLOCK",$pvalTable,$locationItem.block_id)}
											</select>
										</div>
										<div class="col-xs-12 col-md-4 mt-2">
											<input type="text" id="map_la_1" value="{$locationItem.lat}" name="map_block_lat[]" class="form-control input_map_block_lat" placeholder="lat"/>
										</div>
										<div class="col-xs-12 col-md-4 mt-2">
											<input type="text" id="map_lo_1" value="{$locationItem.lng}" name="map_block_lng[]" class="form-control input_map_block_lng" placeholder="long"/>
										</div>
									</div>
									<div class="delete-location {if $more_information.location_block|@count <= 1} d-none {/if}">
										<div class="col-xs-12 col-md-1 mt-2" style="padding-top: 26px;">
											<button title="Xóa" class="btn btn-default btn-delete-location-map" data-type="block"><i class="fa fa-trash"></i></button>
										</div>
									</div>
								</div>
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui-card">
	<div class="ui-card__section">
		<header class="ui-stack ui-stack--wrap mb-3">
			<h2 class="ui-stack-item ui-stack-item--fill ui-heading">Địa điểm Nổi bật</h2>
			<div class="ui-stack-item">
				<button type="button" class="btn btn-default btn-add_property-map" _type="highlight" _openFrom="_project" project_id="">+ Thêm</button>
			</div>
		</header>
		<div class="ui-type-container">
			<div class="p-md-3 box-location-highlight box-group-location">
				<div class="form-row">
					<div class="col-12 col-md-6 col-lg-8">
						<div class="form-group">
							<div class="col-xs-12 col-md-6">
								<label class="label-control">Cấu hình Zoom cho bản đồ</label>
								<input type="number" min="0" max="19" value="{$more_information.map_zoom}" name="map_zoom" class="form-control" placeholder="Độ phóng đại của bản đồ"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-form-label">Nhấc con trỏ đặt vào địa điểm của bạn.</label>
								 <div class="canvas_type" style="width:100%; height:400px; overflow:hidden;z-index:1" _type="highlight" id="my_canvas_highlight"></div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 col-lg-4">
						<div class="overflow-y-auto" style="max-height: 500px">
							{if $more_information.location_highlight|@count > 0}
								{foreach from = $more_information.location_highlight item = locationItem name=loop}
									<div class="form-group item-location item-location_highlight mt-3 d-flex align-item-center">
										<div class="form-row">
											<div class="col-xs-12 col-md-12 mt-2">
												<label class="form-label position-location">Vị trí {$smarty.foreach.loop.iteration}</label>
											</div>
											<div class="col-xs-12 col-md-12 mt-2">
											{* {if $core->_USER['user_id'] == '64'} *}
												<div class="input-group w-100">
													<input id="search-input" name="map_address_highlight[]" value="{$locationItem.address}" type="text" class="form-control map_address_highlight" placeholder="Nhập địa chỉ"/>
													{* <div class="input-group-btn">
														<button type="button" id="search_map_highlight" class="btn btn-default search_map">{$core->makeIcon('search', 'Tìm')}</button>
													</div> *}
												</div>
												<div class="form-group">
													<ul id="suggestions" class="autocomplete-list suggestions"></ul>
												</div>
											{* {else}
												<div class="input-group">
													<input id="map_address_highlight" name="map_address_highlight[]" value="{$locationItem.address}" type="text" class="form-control map_address_highlight" />
													<div class="input-group-btn">
														<button type="button" id="search_map_highlight" class="btn btn-default">{$core->makeIcon('search', 'Tìm')}</button>
													</div>
												</div>

											{/if} *}
											</div>
											<div class="col-xs-12 col-md-4 mt-2">
												<input type="text" value="{$locationItem.name}" name="map_highlight_name[]" class="form-control input_map_highlight_name" placeholder="tên địa điểm"/>
											</div>
											<div class="col-xs-12 col-md-4 mt-2">
												<input type="text" id="map_la_{$smarty.foreach.loop.iteration}" value="{$locationItem.lat}" name="map_highlight_lat[]" class="form-control input_map_highlight_lat" placeholder="lat"/>
											</div>
											<div class="col-xs-12 col-md-4 mt-2">
												<input type="text" id="map_lo_{$smarty.foreach.loop.iteration}" value="{$locationItem.lng}" name="map_highlight_lng[]" class="form-control input_map_highlight_lng" placeholder="long"/>
											</div>
										</div>
										<div class="delete-location {if $more_information.location_highlight|@count <= 1} d-none {/if}">
											<div class="col-xs-12 col-md-1 mt-2" style="padding-top: 26px;">
												<button title="Xóa" class="btn btn-default btn-delete-location-map" data-type="highlightt"><i class="fa fa-trash"></i></button>
											</div>
										</div>
									</div>
								{/foreach}
							{else}
								<div class="form-group item-location item-location_highlight mt-3 d-flex align-item-center">
									<div class="form-row">
										<div class="col-xs-12 col-md-12 mt-2">
											<label class="form-label position-location">Vị trí 1</label>
										</div>
										<div class="col-xs-12 col-md-12 mt-2">
										{* {if $core->_USER['user_id'] == '64'} *}
											<div class="input-group w-100">
												<input id="search-input" name="map_address_highlight[]" value="{$locationItem.address}" type="text" class="form-control map_address_highlight" />
												{* <div class="input-group-btn">
													<button type="button" id="search_map_highlight" class="btn btn-default search_map">{$core->makeIcon('search', 'Tìm')}</button>
												</div> *}
											</div>
											<div class="form-group">
												<ul id="suggestions" class="autocomplete-list suggestions"></ul>
											</div>
										{* {else}
											<div class="input-group">
												<input id="map_address_highlight" name="map_address_highlight[]" value="" type="text" class="form-control map_address_highlight" />
												<div class="input-group-btn">
													<button type="button" id="search_map_highlight" class="btn btn-default">{$core->makeIcon('search', 'Tìm')}</button>
												</div>
											</div>
										{/if} *}
										</div>
										<div class="col-xs-12 col-md-4 mt-2">
											<input type="text" value="" name="map_highlight_name[]" class="form-control input_map_highlight_name" placeholder="tên địa điểm"/>
										</div>
										<div class="col-xs-12 col-md-4 mt-2">
											<input type="text" id="map_la_1" value="" name="map_highlight_lat[]" class="form-control input_map_highlight_lat" placeholder="lat"/>
										</div>
										<div class="col-xs-12 col-md-4 mt-2">
											<input type="text" id="map_lo_1" value="" name="map_highlight_lng[]" class="form-control input_map_highlight_lng" placeholder="long"/>
										</div>
									</div>
									<div class="delete-location d-none">
										<div class="col-xs-12 col-md-1 mt-2" style="padding-top: 26px;">
											<button title="Xóa" class="btn btn-default btn-delete-location-map" data-type="highlight"><i class="fa fa-trash"></i></button>
										</div>
									</div>
								</div>
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Tính năng thêm khoanh vùng -->
<div class="ui-card">
	<div class="ui-card__section">
		<header class="ui-stack ui-stack--wrap mb-3 justify-content-between">
            <div>
                <h2 class="ui-stack-item ui-stack-item--fill ui-heading">Nhập tọa độ khoanh vùng dự án</h2>
                {* <small><i>*Bạn hay lấy các tọa độ phạm vi của dự án theo thứ tự và điền vào bên dưới</i></small><br>
                <small><i>Hãy dùng google map hoặc các công cụ bản đồ liên quan để có thể lấy tọa độ</i></small> *}
            </div>
		</header>
		<div class="ui-type-container">
			<div class="p-md-3 box-location-territory box-group-location">
                {* {if $core->_USER['user_id'] == '64'} *}
                    <div class="input-group w-100">
                        <input id="map_address_territory" name="map_address_territory" value="{if !empty($more_information.map_address_territory)} {$more_information.map_address_territory} {else} {$more_information.map_address} {/if}" type="text" class="form-control map_address_territory" placeholder="Nhập địa chỉ ..." />
                        {* <div class="input-group-btn">
                            <button type="button" class="btn btn-default search_map">{$core->makeIcon('search', 'Tìm')}</button>
                        </div> *}
                    </div>
                    <div class="form-group">
                        <ul class="autocomplete-list suggestions"></ul>
                    </div>
                {* {else}
                    <div class="input-group">
                        <input id="map_address_territory" name="map_address_territory" value="{if !empty($more_information.map_address_territory)} {$more_information.map_address_territory} {else} {$more_information.map_address} {/if}" type="text" class="form-control map_address_territory" />
                        <div class="input-group-btn">
                            <button type="button" id="search_map_territory" class="btn btn-default">{$core->makeIcon('search', 'Tìm')}</button>
                        </div>
                    </div>
                {/if} *}
                <div class="form-group mt-3">
                    <div class="col-md-12 pl-0 pr-0">
                            <div style="width:100%; height:400px; overflow:hidden;z-index:1" id="map-territory"></div>
                    </div>
                </div>
                <div class="form-group row list-location mt-3 d-flex align-item-center" data-territory={$info_map_territory|@json_encode}>
                    {foreach from=$info_map_territory item=item_territory key=key name=name}
                        <div class="item_round round_{$key}" data-round="{$key}">
                            {foreach from=$item_territory item=item_territory_location key=key_location name=name_location}
                            <input type="hidden" id="map_la_1" value="{$item_territory_location[0]}" name="map_territory_lat[{$key}][]" class="form-control input_map_territory_lat" placeholder="lat"/>
                            <input type="hidden" id="map_lo_1" value="{$item_territory_location[1]}" name="map_territory_lng[{$key}][]" class="form-control input_map_territory_lng" placeholder="long"/>
                            {/foreach}
                        </div>
                    {/foreach}
                </div>
			</div>
		</div>
	</div>
</div>
<!-- End - Tính năng thêm khoanh vùng -->
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCBckOSR54eG9pSvVGDniTY8k93RdbK9QQ&sensor=false&libraries=places"></script>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet.path.drag@0.0.6/src/Path.Drag.min.js"></script> 
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    var user_id = "{$core->_USER['user_id']}";
    var lat = "{$more_information.map_la}";
    var lng = "{$more_information.map_lo}";
</script>
{literal}
<script type="text/javascript">
    // if (user_id == '64') {
        $(function(){	
            var mapLeaflet, mapLeafletLocation, mapLeafletHighlight,mapLeafletBlock;
            var list_location_territory = '';
            var edittinglayout = null;
    
            function intiMapLeaflet(id = '') {
                let mapLeaflet = null;
                if(id != '') {
                    mapLeaflet = L.map(id);
                }
                return mapLeaflet;
            }

            function setViewLeaflet(object, lat, lng, map_zoom = 13) {
               object.setView([lat, lng], 13);
               L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(object)
            }

            function geocodeLeaflet(object) {
                L.Control.geocoder({
                    defaultMarkGeocode: true
                })
                .on('markgeocode', function (e) {
                    const bbox = e.geocode.bbox;
                    const poly = L.polygon([
                    bbox.getSouthEast(),
                    bbox.getNorthEast(),
                    bbox.getNorthWest(),
                    bbox.getSouthWest()
                    ]);
                    object.fitBounds(poly.getBounds());
                })
                .addTo(object);
            }

            function debounce(func, delay)
            {
                let timer;
                return function (...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(this, args), delay);
                };
            }
    
            function initialize() {
                let mapLa = $('#map_la').val();
                let mapLo = $('#map_lo').val();
                let address = $('#search-input').val();
                mapLeafletLocation = intiMapLeaflet('map_canvas');
                setViewLeaflet(mapLeafletLocation, lat, lng);
                geocodeLeaflet(mapLeafletLocation);

                let marker;
                if (mapLa.length > 0 && mapLo.length > 0) {
                    marker = L.marker([mapLa, mapLo], {
                        draggable: true
                    }).addTo(mapLeafletLocation);
                    marker.on('dragend', function (e) {
                        const position = marker.getLatLng();
                        $('#map_la').val(position.lat.toFixed(6));
                        $('#map_lo').val(position.lng.toFixed(6));
                    });
                }

                $('#search-input').on('input', debounce(function () {
                    const query = $(this).val();

                    if (query.length < 3) {
                        $('#suggestions').empty();
                        return;
                    }
                    $.get('https://nominatim.openstreetmap.org/search', {
                        format: 'json',
                        q: query,
                        addressdetails: 1,
                        limit: 5
                    }, function (data) {
                        $('#suggestions').empty();
                        data.forEach(function (item) {
                            const displayName = item.display_name;
                            const lat = item.lat;
                            const lon = item.lon;

                            $('#suggestions').append(
                                `<li data-lat="${lat}" data-lon="${lon}">${displayName}</li>`
                            );
                        });
                    });
                }, 1000));

                $('#suggestions').on('click', 'li', function () {
                    const lat = $(this).data('lat');
                    const lon = $(this).data('lon');
                    const name = $(this).text();

                    $('#search-input').val(name);
                    $('#suggestions').empty();

                    mapLeafletLocation.setView([lat, lon], 16);
                    $('#map_la').val(lat);
                    $('#map_lo').val(lon);
                    if (marker) marker.remove();
                    marker = L.marker([lat, lon], {
                        draggable: true
                    }).addTo(mapLeafletLocation);
                    marker.on('dragend', function (e) {
                        const position = marker.getLatLng();
                        $('#map_la').val(position.lat.toFixed(6));
                        $('#map_lo').val(position.lng.toFixed(6));
                    });
                });
            } /**/
    
            function initializeMapHighlight(type) {
                let locationItem = $('.item-location_'+type);
                if(!$('#my_canvas_'+type).hasClass('initialize')) {
                    $('#my_canvas_'+type).addClass('initialize');
					if(type == "block") {
						mapLeafletBlock = intiMapLeaflet('my_canvas_'+type);
						setViewLeaflet(mapLeafletBlock, lat, lng);
						geocodeLeaflet(mapLeafletBlock);
					}else {
						mapLeafletHighlight = intiMapLeaflet('my_canvas_'+type);
						setViewLeaflet(mapLeafletHighlight, lat, lng);
						geocodeLeaflet(mapLeafletHighlight);
					}                    
                }
				if(type == "block") {
					var mapLeafletInit = mapLeafletBlock;
				}else {
					var mapLeafletInit = mapLeafletHighlight;
				}

                if (locationItem.length > 0) {
                    locationItem.not('initialize').each(function(index) {
                        $(this).addClass('initialize');
                        let self = $(this);
                        let markerHighlight;
                        let mapAddressHighlight = $(this).find('.map_address_'+type);
                        let suggestionsEl = $(this).find('.suggestions');
                        let input = $(this).find('.map_address_'+type)[0];
                        let indexEl = index + 1;
                        let mapLa = $(this).find('#map_la_'+indexEl).val();
                        let mapLo = $(this).find('#map_lo_'+indexEl).val();
                        if ( typeof mapLa != 'undefined' && typeof mapLo != 'undefined' && mapLa.length > 0 && mapLo.length > 0) {
                            markerHighlight = L.marker([mapLa, mapLo], {
                                draggable: true
                            }).addTo(mapLeafletInit);
                            markerHighlight.on('dragend', function (e) {
                                const position = markerHighlight.getLatLng();
                                self.find('.input_map_'+type+'_lat').val(position.lat.toFixed(6));
                                self.find('.input_map_'+type+'_lng').val(position.lng.toFixed(6));
                            });
                        }

                        mapAddressHighlight.on('input', debounce(function () {
                            const query = $(this).val();

                            if (query.length < 3) {
                                suggestionsEl.empty();
                                return;
                            }
                            $.get('https://nominatim.openstreetmap.org/search', {
                                format: 'json',
                                q: query,
                                addressdetails: 1,
                                limit: 5
                            }, function (data) {
                                suggestionsEl.empty();
                                data.forEach(function (item) {
                                    const displayName = item.display_name;
                                    const lat = item.lat;
                                    const lon = item.lon;

                                    suggestionsEl.append(
                                        `<li data-lat="${lat}" data-lon="${lon}">${displayName}</li>`
                                    );
                                });
                            });
                        }, 1000));

                        suggestionsEl.on('click', 'li', function () {
                            const lat = $(this).data('lat');
                            const lon = $(this).data('lon');
                            const name = $(this).text();

                            $(this).parents('.item-location_'+type).find('.map_address_'+type).val(name);
                            suggestionsEl.empty();
                            if (markerHighlight != null) {
                                markerHighlight.remove();
                            }
                            mapLeafletInit.setView([lat, lon], 16);
                            self.find('.input_map_'+type+'_lat').val(lat);
                            self.find('.input_map_'+type+'_lng').val(lon);
                            markerHighlight = L.marker([lat, lon], {
                                draggable: true
                            }).addTo(mapLeafletInit);
                            markerHighlight.on('dragend', function (e) {
                                const position = markerHighlight.getLatLng();
                                self.find('.input_map_'+type+'_lat').val(position.lat.toFixed(6));
                                self.find('.input_map_'+type+'_lng').val(position.lng.toFixed(6));
                            });
                        });
                    })
                }
                
            } /**/
            function initializeMapTerritory() {
                let mapAddressTerritory = $('.map_address_territory');
                let suggestionsEl = $('.map_address_territory').parents('.box-location-territory').find('.suggestions');
                var markerTerritory;
                mapLeaflet = intiMapLeaflet('map-territory');
                list_location_territory = $('.list-location').attr('data-territory');

                if (list_location_territory.length >0) {
                    list_location_territory = JSON.parse(list_location_territory);
                }
                if (typeof lat != 'undefined' && typeof lng != 'undefined' && lat.length > 0 && lng.length > 0) {
                    mapLeaflet.setView([lat, lng], 17);
                }
                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(mapLeaflet);
                markerTerritory = L.marker([lat, lng]).addTo(mapLeaflet)
                const drawnItems = new L.FeatureGroup();
                
                if (list_location_territory.length > 0) {
                    list_location_territory.forEach(function(item_location, idx_location) {
                        
                        const polygon = L.polygon(item_location, {
                            color: 'blue',
                        }).addTo(mapLeaflet);
                        polygon._customId = 'round_'+idx_location
                        drawnItems.addLayer(polygon);
                    })
                }
    
                mapLeaflet.addLayer(drawnItems);
                const drawControl = new L.Control.Draw({
                    draw: {
                        polygon: {
                            allowIntersection: false,
                            showArea: true,
                            shapeOptions: {
                            color: 'blue'
                            }
                        },
                        polyline: false,
                        rectangle: false,
                        circle: false,
                        marker: false,
                        circlemarker: false
                    },
                    edit: {
                        featureGroup: drawnItems,
                        remove: true              
                    }
                });
                mapLeaflet.addControl(drawControl);
    
                // 5. Sự kiện khi vẽ xong
                mapLeaflet.on(L.Draw.Event.CREATED, function (event) {
                    const layer = event.layer;
    
                    // Lấy tọa độ đa giác
                    const latlngs = layer.getLatLngs()[0]; // Mảng các điểm
                    const coords = latlngs.map(p => [p.lat, p.lng]);
                    let countItemLocation = $('.list-location').find('.item_round').length;
                    layer._customId = 'round_' + countItemLocation;
                    let item_location = '';
                    drawnItems.addLayer(layer);
    
                    coords.forEach(function(el, idx) {
                        item_location += `
                            <input type="hidden" value="${el[0]}" name="map_territory_lat[`+countItemLocation+`][]" class="form-control item_lat_lng input_map_territory_lat" placeholder="lat"/>
                            <input type="hidden" value="${el[1]}" name="map_territory_lng[`+countItemLocation+`][]" class="form-control item_lat_lng input_map_territory_lng" placeholder="long"/>
                        `;
                    })
                    let htmlLocation = `
                     <div class="item_round round_${countItemLocation}" data-round="${countItemLocation}">
                        `+item_location+`
                    </div>
                    `;
                    $('.list-location').append(htmlLocation);
                    layer.on('click', function() {
                        // tắt các layout khác trước mở edit layout hiện tại
                        if (edittinglayout != null) {
                            edittinglayout.editing.disable();
                            edittinglayout = null;
                        }
                        // bật edit cho vùng này
                        this.editing.enable(); 
                        editableLayer = this;
                        edittinglayout = editableLayer;
                        console.log('Đang sửa vùng:', this._customId);
                    });
                    console.log("Tọa độ vùng đã vẽ:", coords);
                });
    
                mapLeaflet.on('draw:edited', function (e) {
                    const layers = e.layers;
                    layers.eachLayer(function (layer) {
                        console.log('layer', layer._customId);
                        const latlngs = layer.getLatLngs()[0];
                        const newCoords = latlngs.map(p => [p.lat, p.lng]);;
                        let id = layer._customId;
                        let countItemLocation = $('.'+id).attr('data-round');
                        $('.'+id).find('.item_lat_lng').remove();
                        $('.'+id).remove();
                        let item_location = '';
                        newCoords.forEach(function(el, idx) {
                            item_location += `
                                <input type="hidden" value="${el[0]}" name="map_territory_lat[`+countItemLocation+`][]" class="form-control item_lat_lng input_map_territory_lat" placeholder="lat"/>
                                <input type="hidden" value="${el[1]}" name="map_territory_lng[`+countItemLocation+`][]" class="form-control item_lat_lng input_map_territory_lng" placeholder="long"/>
                            `;
                        })
                        let htmlLocation = `
                         <div class="item_round ${id}" data-round="${countItemLocation}">
                            `+item_location+`
                        </div>
                        `;
                        $('.list-location').append(htmlLocation);
    
                        console.log("Tọa độ sau khi chỉnh sửa:", newCoords);
                        // newCoords là mảng latlng mới → bạn có thể lưu lại hoặc xử lý thêm
                    });
                });
                mapLeaflet.on('draw:deleted', function (e) {
                    const layers = e.layers;
                    layers.eachLayer(function (layer) {
                        let id = layer._customId;
                        $('.'+id).find('.item_lat_lng').remove();
                        $('.'+id).remove();
                      if (edittinglayout != null) {
                        edittinglayout.remove();
                      }
                      
                    });
                    console.log("Vùng đã bị xóa");
                });

                mapAddressTerritory.on('input', debounce(function () {
                    const query = $(this).val();

                    if (query.length < 3) {
                        suggestionsEl.empty();
                        return;
                    }
                    $.get('https://nominatim.openstreetmap.org/search', {
                        format: 'json',
                        q: query,
                        addressdetails: 1,
                        limit: 5
                    }, function (data) {
                        suggestionsEl.empty();
                        data.forEach(function (item) {
                            const displayName = item.display_name;
                            const lat = item.lat;
                            const lon = item.lon;

                            suggestionsEl.append(
                                `<li data-lat="${lat}" data-lon="${lon}">${displayName}</li>`
                            );
                        });
                    });
                }, 2000));

                suggestionsEl.on('click', 'li', function () {
                    const lat = $(this).data('lat');
                    const lon = $(this).data('lon');
                    const name = $(this).text();

                    suggestionsEl.empty();
                    if (markerTerritory != null) {
                        markerTerritory.remove();
                    }
                    mapLeaflet.setView([lat, lon], 16);
                    // if (marker) marker.remove();
                    markerTerritory = L.marker([lat, lon]).addTo(mapLeaflet);
                });
            } /**/
            
            $(document).ready(function() {
                initialize();
				$(".canvas_type").each(function(index,elm) {
					var type = $(elm).attr("_type");
					initializeMapHighlight(type);
				});
                
                initializeMapTerritory();
                $('#' + 'search_map').click(function() {
                    var address = $('#map_address').val();
                    showAddress(address);
                    return false;
                });
                /*$('#search_map_highlight').click(function() {
                    let addressHl = $('#map_address_highlight').val();
                    showAddressHighlight(addressHl);
                    return false;
                });
                $('#search_map_territory').click(function() {
                    let addressHl = $('#map_address_territory').val();
                    if (!addressHl) return;
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ 'address': addressHl }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var lat = results[0].geometry.location.lat();
                            var lng = results[0].geometry.location.lng();
                            // Focus vào vị trí trên bản đồ Leaflet
                            mapLeaflet.setView([lat, lng], 16);
                            // Thêm marker nếu muốn
                            L.marker([lat, lng]).addTo(mapLeaflet).bindPopup(results[0].formatted_address).openPopup();
                        } else {
                            console.log('Không tìm thấy địa chỉ này!');
                        }
                    });
                });
                if($('#map_address_territory').length > 0 && $('#map_address_territory').val().length != '') {
                    $('#search_map_territory').trigger('click');
                }*/
                $('.btn-add_property-map').click(function() {
					var type = $(this).attr("_type");
                    add_location(this, type);
                    initializeMapHighlight(type);
                });
                $('.btn-add-location-territory').click(function() {
                    add_location(this, 'territory');
                });
    
                $('.box-group-location').on('click', '.btn-delete-location-map', function() {
                    let type = $(this).attr('data-type');
                    $(this).parents('.item-location').remove();
                    let countItem = $('.box-location-' + type).find('.item-location').length;
                    if (countItem > 1) {
                        $('.box-location-' + type).find('.item-location').each(function(index) {
                            $(this).find('.position-location').text('Vị trí ' + (index + 1));
                        })
                    } else {
                        let el = $('.box-location-' + type).find('.item-location').find('.delete-location');
                        $('.box-location-' + type).find('.position-location').text('Vị trí 1');
                        if (!el.hasClass('d-none')) {
                            el.addClass('d-none');
                        }
                    }
                });
               
            })
        });	
        function add_location(el, type) {
            let itemLocation = $('.box-location-'+ type).find('.item-location').clone();
			console.log(itemLocation);
            let countLocation = $('.box-location-'+ type).find('.item-location').length;
            let elDeleteLocation = $('.box-location-'+ type).find('.item-location').find('.delete-location');
            itemLocation.find('input').val('');
            itemLocation.find('.delete-location').removeClass('d-none');
            itemLocation.find('.position-location').text('Vị trí ' +( countLocation + 1));
            $('.item-location_'+ type + ":last-child").after(itemLocation[0]);
            $('.box-location-'+ type).find('.delete-location').removeClass('d-none');
            
        }
    // } else {
    //     $(function(){	
    //         var map, mapHighlight, marker, markerHighlight, infoWindow, geocoder = new google.maps.Geocoder();
    //         var mapLeaflet = intiMapLeaflet('map-territory');
    //         var mapLeafletLocation = intiMapLeaflet('map_canvas');
    //         var mapLeafletHighlight = intiMapLeaflet('my_canvas_highligh');
    
    
    //         var list_location_territory = $('.list-location').attr('data-territory');
    //         var edittinglayout = null;
            
    //         function $getID(id) {
    //             return document.getElementById(id);
    //         }
    
    //         function geocode(position) {
    //             geocoder.geocode({
    //                 latLng: position
    //             }, function(responses) {
    //                 $getID('map_address').value = responses[0].formatted_address;
    //                 $getID('map_la').value = marker.getPosition().lat();
    //                 $getID('map_lo').value = marker.getPosition().lng();
    //                 mapLeaflet.setView([marker.getPosition().lat(), marker.getPosition().lng()], 13);
    //                 map.panTo(marker.getPosition());
    //             });
    //         }
    
    //         function intiMapLeaflet(id = '') {
    //             let mapLeaflet = null;
    //             if(id != '') {
    //                 mapLeaflet = L.map(id);
    //             }
    //             return mapLeaflet;
    //         }
    
    //         function initialize() {
    //             var mapOptions = {
    //                 center: new google.maps.LatLng(map_la, map_lo),
    //                 zoom: 8,
    //                 mapTypeId: google.maps.MapTypeId.ROADMAP
    //             };
    //             map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
    //             var input = document.getElementById('map_address');
    //             var autocomplete = new google.maps.places.Autocomplete(input);
    //             autocomplete.bindTo('bounds', map);
    //             var location = new google.maps.LatLng(map_la, map_lo);
    //             marker = new google.maps.Marker({
    //                 position: location
    //             });
    //             marker.setMap(map);
    //             marker.setDraggable(true);
    //             google.maps.event.addListener(marker, "dragend", function(event) {
    //                 var point = marker.getPosition();
    //                 map.panTo(point);
    //                 geocode(point);
    //             }); /**/
    //             google.maps.event.addListener(autocomplete, 'place_changed', function() {
    //                 var place = autocomplete.getPlace();
    //                 if (place.geometry.viewport) {
    //                     map.fitBounds(place.geometry.viewport);
    //                 } else {
    //                     map.setCenter(place.geometry.location);
    //                     map.setZoom(8);
    //                 }
    //                 geocode(place.geometry.location);
    //                 marker.setPosition(place.geometry.location);
    //             });
    //         } /**/
    //         function showAddress(address) {
    //             geocoder = new google.maps.Geocoder();
    //             geocoder.geocode({
    //                 'address': address
    //             }, function(results, status) {
    //                 if (status == google.maps.GeocoderStatus.OK) {
    //                     marker.setPosition(results[0].geometry.location);
    //                     geocode(results[0].geometry.location);
    //                 } else {
    //                     alert("Sorry but Google Maps could not find this location.");
    //                 }
    //             });
    //         }; /*End showAddress*/
            
    //         /*Phần highligh*/
    
    //         function geocodeHighlight(position) {
    //             geocoder.geocode({
    //                 latLng: position
    //             }, function(responses) {
    //                 $getID('map_address_highlight').value = responses[0].formatted_address;
    //                 $getID('map_la').value = markerHighlight.getPosition().lat();
    //                 $getID('map_lo').value = markerHighlight.getPosition().lng();
    //                 mapHighlight.panTo(markerHighlight.getPosition());
    //             });
    //         }
    
    //         function initializeMapHighlight() {
    //             var mapOptions = {
    //                 center: new google.maps.LatLng(map_la, map_lo),
    //                 zoom: 8,
    //                 mapTypeId: google.maps.MapTypeId.ROADMAP
    //             };
    //             mapHighlight = new google.maps.Map(document.getElementById('my_canvas_highligh'), mapOptions);
    //             let locationItem = $('.item-location');
    //             if (locationItem.length > 0) {
    //                 locationItem.not('initialize').each(function(index) {
    //                     $(this).addClass('initialize');
    //                     let input = $(this).find('.map_address_highlight')[0];
    //                     let indexEl = index + 1;
    //                     let map_la = $(this).find('#map_la_'+indexEl).val();
    //                     let map_lo = $(this).find('#map_lo_'+indexEl).val();
    //                     let autocomplete = new google.maps.places.Autocomplete(input);
    //                     autocomplete.bindTo('bounds', mapHighlight);
    //                     let location = new google.maps.LatLng(map_la, map_lo);
    //                     let markerHighlightPoint = new google.maps.Marker({
    //                         position: location
    //                     });
    //                     markerHighlightPoint.setMap(mapHighlight);
    //                     markerHighlightPoint.setDraggable(true);
    //                     let self = $(this);
    //                     google.maps.event.addListener(markerHighlightPoint, "dragend", function(event) {
    //                         var point = markerHighlightPoint.getPosition();
    //                         mapHighlight.panTo(point);
    //                         geocoder.geocode({
    //                             latLng: point
    //                         }, function(responses) {
    //                             self.find('.map_address_highlight').val(responses[0].formatted_address);
    //                             self.find('#map_la_'+indexEl).val( markerHighlightPoint.getPosition().lat());
    //                             self.find('#map_lo_'+indexEl).val( markerHighlightPoint.getPosition().lng());
    //                         });
    //                     }); /**/
    //                     google.maps.event.addListener(autocomplete, 'place_changed', function() {
    //                         var place = autocomplete.getPlace();
    //                         if (place.geometry.viewport) {
    //                             mapHighlight.fitBounds(place.geometry.viewport);
    //                         } else {
    //                             mapHighlight.setCenter(place.geometry.location);
    //                             mapHighlight.setZoom(8);
    //                         }
    //                         geocoder.geocode({
    //                             latLng: place.geometry.location
    //                         }, function(responses) {
    //                             self.find('.map_address_highlight').val(responses[0].formatted_address);
    //                             self.find('.input_map_highlight_lat').val( markerHighlightPoint.getPosition().lat());
    //                             self.find('.input_map_highlight_lng').val( markerHighlightPoint.getPosition().lng());
    //                         });
    //                         markerHighlightPoint.setPosition(place.geometry.location);
    //                     });
    //                 })
    //             }
                
    //         } /**/
    
    //         function showAddressHighlight(address) {
    //             geocoder = new google.maps.Geocoder();
    //             geocoder.geocode({
    //                 'address': address
    //             }, function(results, status) {
    //                 if (status == google.maps.GeocoderStatus.OK) {
    //                     markerHighligh.setPosition(results[0].geometry.location);
    //                     geocodeHighlight(results[0].geometry.location);
    //                 } else {
    //                     alert("Sorry but Google Maps could not find this location.");
    //                 }
    //             });
    //         }; /*End showAddressTerritory*/
    
    //         function initializeMapTerritory() {
    //             if (list_location_territory.length >0) {
    //                 list_location_territory = JSON.parse(list_location_territory);
    //             }
    //             if (typeof lat != 'undefined' && typeof lng != 'undefined' && lat.length > 0 && lng.length > 0) {
    //                 mapLeaflet.setView([lat, lng], 17);
    //             }
    //             L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(mapLeaflet);
    //             const drawnItems = new L.FeatureGroup();
                
    //             if (list_location_territory.length > 0) {
    //                 list_location_territory.forEach(function(item_location, idx_location) {
                        
    //                     const polygon = L.polygon(item_location, {
    //                         color: 'blue',
    //                     }).addTo(mapLeaflet);
    //                     polygon._customId = 'round_'+idx_location
    //                     drawnItems.addLayer(polygon);
    //                 })
    //             }
    
    //             mapLeaflet.addLayer(drawnItems);
    //             const drawControl = new L.Control.Draw({
    //                 draw: {
    //                     polygon: {
    //                         allowIntersection: false,
    //                         showArea: true,
    //                         shapeOptions: {
    //                         color: 'blue'
    //                         }
    //                     },
    //                     polyline: false,
    //                     rectangle: false,
    //                     circle: false,
    //                     marker: false,
    //                     circlemarker: false
    //                 },
    //                 edit: {
    //                     featureGroup: drawnItems,
    //                     remove: true              
    //                 }
    //             });
    //             mapLeaflet.addControl(drawControl);
    
    //             // 5. Sự kiện khi vẽ xong
    //             mapLeaflet.on(L.Draw.Event.CREATED, function (event) {
    //                 const layer = event.layer;
    
    //                 // Lấy tọa độ đa giác
    //                 const latlngs = layer.getLatLngs()[0]; // Mảng các điểm
    //                 const coords = latlngs.map(p => [p.lat, p.lng]);
    //                 let countItemLocation = $('.list-location').find('.item_round').length;
    //                 layer._customId = 'round_' + countItemLocation;
    //                 let item_location = '';
    //                 drawnItems.addLayer(layer);
    
    //                 coords.forEach(function(el, idx) {
    //                     item_location += `
    //                         <input type="hidden" value="${el[0]}" name="map_territory_lat[`+countItemLocation+`][]" class="form-control item_lat_lng input_map_territory_lat" placeholder="lat"/>
    //                         <input type="hidden" value="${el[1]}" name="map_territory_lng[`+countItemLocation+`][]" class="form-control item_lat_lng input_map_territory_lng" placeholder="long"/>
    //                     `;
    //                 })
    //                 let htmlLocation = `
    //                  <div class="item_round round_${countItemLocation}" data-round="${countItemLocation}">
    //                     `+item_location+`
    //                 </div>
    //                 `;
    //                 $('.list-location').append(htmlLocation);
    //                 layer.on('click', function() {
    //                     // tắt các layout khác trước mở edit layout hiện tại
    //                     if (edittinglayout != null) {
    //                         edittinglayout.editing.disable();
    //                         edittinglayout = null;
    //                     }
    //                     // bật edit cho vùng này
    //                     this.editing.enable(); 
    //                     editableLayer = this;
    //                     edittinglayout = editableLayer;
    //                     console.log('Đang sửa vùng:', this._customId);
    //                 });
    //                 console.log("Tọa độ vùng đã vẽ:", coords);
    //             });
    
    //             mapLeaflet.on('draw:edited', function (e) {
    //                 const layers = e.layers;
    //                 layers.eachLayer(function (layer) {
    //                     console.log('layer', layer._customId);
    //                     const latlngs = layer.getLatLngs()[0];
    //                     const newCoords = latlngs.map(p => [p.lat, p.lng]);;
    //                     let id = layer._customId;
    //                     let countItemLocation = $('.'+id).attr('data-round');
    //                     $('.'+id).find('.item_lat_lng').remove();
    //                     $('.'+id).remove();
    //                     let item_location = '';
    //                     newCoords.forEach(function(el, idx) {
    //                         item_location += `
    //                             <input type="hidden" value="${el[0]}" name="map_territory_lat[`+countItemLocation+`][]" class="form-control item_lat_lng input_map_territory_lat" placeholder="lat"/>
    //                             <input type="hidden" value="${el[1]}" name="map_territory_lng[`+countItemLocation+`][]" class="form-control item_lat_lng input_map_territory_lng" placeholder="long"/>
    //                         `;
    //                     })
    //                     let htmlLocation = `
    //                      <div class="item_round ${id}" data-round="${countItemLocation}">
    //                         `+item_location+`
    //                     </div>
    //                     `;
    //                     $('.list-location').append(htmlLocation);
    
    //                     console.log("Tọa độ sau khi chỉnh sửa:", newCoords);
    //                     // newCoords là mảng latlng mới → bạn có thể lưu lại hoặc xử lý thêm
    //                 });
    //             });
    //             mapLeaflet.on('draw:deleted', function (e) {
    //                 const layers = e.layers;
    //                 layers.eachLayer(function (layer) {
    //                     let id = layer._customId;
    //                     $('.'+id).find('.item_lat_lng').remove();
    //                     $('.'+id).remove();
    //                   if (edittinglayout != null) {
    //                     edittinglayout.remove();
    //                   }
                      
    //                 });
    //                 console.log("Vùng đã bị xóa");
    //             });
    //             const autocomplete = new google.maps.places.Autocomplete(
    //                 document.getElementById('map_address_territory')
    //             );
    //             autocomplete.addListener('place_changed', function () {
    //                 const place = autocomplete.getPlace();
    
    //                 // Kiểm tra nếu place có geometry (tọa độ)
    //                 if (place.geometry) {
    //                     const lat = place.geometry.location.lat();
    //                     const lng = place.geometry.location.lng();
    
    //                     // Nếu bạn dùng Leaflet:
    //                     L.marker([lat, lng]).addTo(mapLeaflet).bindPopup(place.formatted_address).openPopup();
    //                     mapLeaflet.setView([lat, lng], 16);
    //                 } else {
    //                     alert("Không lấy được tọa độ. Hãy chọn một địa điểm từ gợi ý.");
    //                 }
    //             });
    //         } /**/
            
    //         $(document).ready(function() {
    //             initialize();
    //             initializeMapHighlight();
    //             initializeMapTerritory();
    //             $('#' + 'search_map').click(function() {
    //                 var address = $('#map_address').val();
    //                 showAddress(address);
    //                 return false;
    //             });
    //             $('#search_map_highlight').click(function() {
    //                 let addressHl = $('#map_address_highlight').val();
    //                 showAddressHighlight(addressHl);
    //                 return false;
    //             });
    //             $('#search_map_territory').click(function() {
    //                 let addressHl = $('#map_address_territory').val();
    //                 if (!addressHl) return;
    //                 var geocoder = new google.maps.Geocoder();
    //                 geocoder.geocode({ 'address': addressHl }, function(results, status) {
    //                     if (status == google.maps.GeocoderStatus.OK) {
    //                         var lat = results[0].geometry.location.lat();
    //                         var lng = results[0].geometry.location.lng();
    //                         // Focus vào vị trí trên bản đồ Leaflet
    //                         mapLeaflet.setView([lat, lng], 16);
    //                         // Thêm marker nếu muốn
    //                         L.marker([lat, lng]).addTo(mapLeaflet).bindPopup(results[0].formatted_address).openPopup();
    //                     } else {
    //                         console.log('Không tìm thấy địa chỉ này!');
    //                     }
    //                 });
    //             });
    //             if($('#map_address_territory').length > 0 && $('#map_address_territory').val().length != '') {
    //                 $('#search_map_territory').trigger('click');
    //             }
    //             $('.btn-add_property-map').click(function() {
    //                 add_location(this, 'highlight');
    //                 initializeMapHighlight();
    //             });
    //             $('.btn-add-location-territory').click(function() {
    //                 add_location(this, 'territory');
    //             });
    
    //             $('.box-group-location').on('click', '.btn-delete-location-map', function() {
    //                 let type = $(this).attr('data-type');
    //                 $(this).parents('.item-location').remove();
    //                 let countItem = $('.box-location-' + type).find('.item-location').length;
    //                 if (countItem > 1) {
    //                     $('.box-location-' + type).find('.item-location').each(function(index) {
    //                         $(this).find('.position-location').text('Vị trí ' + (index + 1));
    //                     })
    //                 } else {
    //                     let el = $('.box-location-' + type).find('.item-location').find('.delete-location');
    //                     $('.box-location-' + type).find('.position-location').text('Vị trí 1');
    //                     if (!el.hasClass('d-none')) {
    //                         el.addClass('d-none');
    //                     }
    //                 }
    //             });
               
    //         })
    //     });	
    //     function add_location(el, type) {
    //         let itemLocation = $('.box-location-'+ type).find('.item-location').clone();
    //         let countLocation = $('.box-location-'+ type).find('.item-location').length;
    //         let elDeleteLocation = $('.box-location-'+ type).find('.item-location').find('.delete-location');
    //         itemLocation.find('input').val('');
    //         itemLocation.find('.delete-location').removeClass('d-none');
    //         itemLocation.find('.position-location').text('Vị trí ' +( countLocation + 1));
    //         $('.box-location-'+ type).append(itemLocation[0]);
    //         $('.box-location-'+ type).find('.delete-location').removeClass('d-none');
            
    //     }
    // }
</script>
{/literal}