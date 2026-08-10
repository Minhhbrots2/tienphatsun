<?php
/* Smarty version 3.1.33, created on 2026-08-05 14:18:22
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/address.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72e3beece395_86199742',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '09efb43feae1396c1ea6b128333bcc1c80d3e335' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/address.tpl',
      1 => 1784691722,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72e3beece395_86199742 (Smarty_Internal_Template $_smarty_tpl) {
?><header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default ui-breadcrumb">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>

					<span class="ui-breadcrumb__item"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Setting');?>
</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Cấu hình</h1>
			</div>
		</div>
	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>
</header>
<div class="clearfix"></div>
<form method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Thông tin liên hệ</h2>
								</div>
								<div class="ui-annotated-section__description">
									Thông tin được sử dụng trong các thông báo về đơn hàng và địa chỉ để liên hệ đến cửa hàng.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">Tên kinh doanh <span class="text-red">*</span></label>
															<input type="text" class="form-control require" required="true" placeholder="Tên kinh doanh" name="iso-company_name" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('company_name');?>
" />
														</div>
													</div>
													<div class="form-group">
														<div class="col-md-6">
															<label class="col-form-label">Điện thoại <span class="text-red">*</span></label>
															<input type="text" class="form-control require" required="true" placeholder="Nhập điện thoại liên hệ" name="iso-company_phone" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('company_phone');?>
" />
														</div>
														<div class="col-md-6">
															<label class="col-form-label">Hotline <span class="text-red">*</span></label>
															<input type="text" class="form-control require" placeholder="Nhập số hotline" name="iso-company_hotline" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('company_hotline');?>
" />
														</div>
													</div>
													<div class="form-group">
														<div class="col-md-6">
															<label class="col-form-label">E-Mail <span class="text-red">*</span></label>
															<input type="text" class="form-control require" required="true" placeholder="Nhập E-Mail liên hệ" name="iso-company_email" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('company_email');?>
" />
														</div>
														<div class="col-md-6">
															<label class="col-form-label">Fax</label>
															<input type="text" class="form-control" placeholder="Nhập số Fax" name="iso-company_fax" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('company_fax');?>
" />
														</div>
													</div>
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">Địa chỉ <span class="text-red">*</span></label>
															<textarea rows="2" class="form-control required" required placeholder="Nhập địa chỉ liên hệ" name="iso-company_address"><?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('company_address');?>
</textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Giao diên đang sử dụng</h2>
								</div>
								<div class="ui-annotated-section__description">
									Lựa chọn giao diện phù hợp cho website.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-xs-12 col-md-12">
															<div class="input-group">
																<input id="map_address" name="iso-map_address" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('map_address');?>
" type="text" class="form-control" />
																<div class="input-group-btn">
																	<button type="button" id="search_map" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('search','Tìm');?>
</button>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">Nhấc con trỏ đặt vào địa điểm của bạn.</label>
															 <div style="width:100%; height:400px; overflow:hidden;" id="map_canvas"></div>
														</div>
													</div>
													<div class="form-group">
														<div class="col-xs-12 col-md-6">
															<input type="text" id="map_la" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('map_la');?>
" name="iso-map_la" class="form-control" />
														</div>
														<div class="col-xs-12 col-md-6">
															<input type="text" id="map_lo" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('map_lo');?>
" name="iso-map_lo" class="form-control" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<!-- End section -->
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group"><?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>
</div>
			</div>
		</div>
	</div>
</form>
<?php echo '<script'; ?>
 type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCBckOSR54eG9pSvVGDniTY8k93RdbK9QQ&sensor=false&libraries=places"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
	var map_la = '<?php echo $_smarty_tpl->tpl_vars['map_la']->value;?>
';
	var map_lo = '<?php echo $_smarty_tpl->tpl_vars['map_lo']->value;?>
';
<?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 type="text/javascript">
	var map, marker, infoWindow,
		geocoder=new google.maps.Geocoder();
	function $getID(id){
		return document.getElementById(id);
	}
	function geocode(position) {
		geocoder.geocode({
			latLng: position
			},function(responses) {
				$getID('map_address').value = responses[0].formatted_address;
				$getID('map_la').value = marker.getPosition().lat(); 
				$getID('map_lo').value = marker.getPosition().lng();
				map.panTo(marker.getPosition());
		 });
	}
	function initialize(){
		var mapOptions = {
			center: new google.maps.LatLng(map_la,map_lo),
			zoom: 8,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}; 
		map = new google.maps.Map(document.getElementById('map_canvas'),mapOptions); 
		var input = document.getElementById('map_address'); 
		var autocomplete = new google.maps.places.Autocomplete(input); 
		autocomplete.bindTo('bounds', map); 
		var location = new google.maps.LatLng (map_la,map_lo); 
		marker = new google.maps.Marker({ position:location}); 
		marker.setMap(map); 
		marker.setDraggable(true); 
		google.maps.event.addListener(marker, "dragend", function(event){ 
			var point = marker.getPosition();
			map.panTo(point);
			geocode(point);
		}); 
		/**/ 
		google.maps.event.addListener(autocomplete, 'place_changed', function(){
			var place = autocomplete.getPlace();
			if(place.geometry.viewport){ 
				map.fitBounds(place.geometry.viewport); 
			}else{
				map.setCenter(place.geometry.location); map.setZoom(8); 
			}
			geocode(place.geometry.location);
			marker.setPosition(place.geometry.location); 
		}); 
	} 
	/**/ 
	function showAddress(address){
		geocoder = new google.maps.Geocoder(); 
		geocoder.geocode({'address': address},function(results,status){
			if (status == google.maps.GeocoderStatus.OK) {
				marker.setPosition(results[0].geometry.location);
				geocode(results[0].geometry.location);
			} else {
				alert("Sorry but Google Maps could not find this location.");
			}
		});
	}; 
	/*End showAddress*/ 
	$(document).ready(function(){
		initialize(); 
		$('#'+'search_map').click(function(){
			var address=$('#map_address').val(); 
			showAddress(address); 
			return false; 
		}); 
	});
<?php echo '</script'; ?>
>
<?php }
}
