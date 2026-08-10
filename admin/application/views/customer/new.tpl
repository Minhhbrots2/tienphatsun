<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('CustomerPage')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar__main-group">
		<div class="ui-title-bar__heading-group">
			{if $pvalTable gt '0'}
			<h1 class="ui-title-bar__title w-100">{$clsClassTable->getTitle($pvalTable)}</h1>
			<div class="action-bar__item action-bar__item--link-container">
				<div class="action-bar__top-links">
					<a href="{$DOMAIN_NAME}{$clsClassTable->getLink($pvalTable)}" class="ui-button ui-button--transparent action-bar__link"  target="_blank">{$core->makeIcon('eye', $core->get_Lang('Trang khách hàng'))}</a>
				</div>
			</div>
			{else}
			<h1 class="ui-title-bar__title">{$core->get_Lang('Addnew Customer')}
			{/if}
		</div>
	</div>
</div>
<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="row">
			<div class="col-md-8">
				<div class="box light">
					<div class="box-title">
						<div class="caption">
							<span class="bold">Thông tin cơ bản</span>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Họ <span class="requiredMark">*</span></label>
									<input type="text" class="form-control f_name" name="iso-first_name" value="{$oneItem.first_name}" placeholder="Nhập họ" required="true" />
								</div>
								<div class="col-md-6">
									<label>Tên <span class="requiredMark">*</span></label>
									<input type="text" class="form-control" name="iso-last_name" value="{$oneItem.last_name}" placeholder="Nhập tên" required="true" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Email <span class="requiredMark">*</span></label>
							<input type="text" class="form-control" name="iso-email" value="{$oneItem.email}" placeholder="Nhập e-mail" required="true" />
						</div>
					</div>
				</div>
				<div class="box light">
					<div class="box-title">
						<div class="caption">
							<span class="bold">Thông tin địa chỉ</span>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Họ</label>
									<input type="text" name="iso-address_first_name" class="form-control" value="{$oneItem.first_name}" placeholder="Nhập họ" />
								</div>
								<div class="col-md-6">
									<label>Tên</label>
									<input type="text" name="iso-address_last_name" class="form-control" value="{$oneItem.last_name}" placeholder="Nhập tên" />
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Công ty</label>
									<input type="text" class="form-control" name="iso-company" value="{$oneItem.company}" placeholder="Nhập công ty" />
								</div>
								<div class="col-md-6">
									<label>Điện thoại</label>
									<input type="text" class="form-control" name="iso-phone" value="{$oneItem.phone}" placeholder="Nhập điện thoại" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Địa chỉ</label>
							<input type="text" class="form-control" name="iso-address" value="{$oneItem.address}" placeholder="Nhập địa chỉ" />
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Quốc gia</label>
									<select class="form-control" name="iso-country_id" id="country_id">
										{$clsCountry->makeSelectOption()}
									</select>
								</div>
								<div class="col-md-6">
									<label>Postal / Zip Code</label>
									<input type="text" class="form-control" name="iso-zipcode" value="{$oneItem.address}" placeholder="Nhập Postal / Zip Code"  />
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Tỉnh/Thành phố</label>
									<select class="form-control" name="iso-city_id" id="city_id">
										<option value="">-- Tỉnh/ Thành phố --</option>
									</select>
								</div>
								<div class="col-md-6">
									<label>Quận/Huyện</label>
									<select class="form-control" name="iso-district_id"  id="district_id">
										<option value="">-- Quận/ Huyện --</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="box light">
					<div class="box-title no-bottom-border">
						<div class="caption">
							<span class="bold">Ghi chú</span>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<textarea class="form-control" name="iso-note" placeholder="Nhập ghi chú" rows="5"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<input value="Update" name="submit" type="hidden">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary">
				<div class="ui-page-actions__button-group">
					{if $pvalTable gt '0'}
					<a class="btn btn-warning" data-bind-event-click="deleteModal.show()">{$core->get_Lang('Delete')}</a>
					{/if}
				</div>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<div class="ui-page-actions__button-group">
					<a class="btn btn-default" href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Cancel')}</a>
					{$saveBtn} {$saveList}
				</div>
			</div>
		</div>
	</div>
</form>
{literal}
<script type="text/javascript">
	$(function(){
		$('input[name=iso-first_name],input[name=iso-last_name]').keyup(function(){
			var $_this = $(this);
			if($_this.hasClass('f_name')){
				$('input[name=iso-address_first_name]').val($_this.val());
			}else{
				$('input[name=iso-address_last_name]').val($_this.val());
			}
		});
		$('select[name=iso-country_id]').change(function(){
			var $_this=$(this);
			loadCity($_this.val(),'');
		});
		$('select[name=iso-city_id]').change(function(){
			var $_this=$(this);
			loadDistrict($_this.val(),'');
		});
		$('.ajCreateQuickNewCity').live('click',function(){
			var $this = $(this);
			var $country_id = $('#country_id').val();
			if($country_id==''){
				$('#country_id').focus();
				alert(field_is_required);
				return false;
			}
			global_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=country&act=ajLoadCreateNewCity",
				data: {'country_id': $country_id},
				dataType: "html",
				success: function(html){
					makepopup(400,'auto',html,'pop_CityBox');
					global_loading(0);
				}
			});
			return false;
		});
		$('.ajSubmitQuickCity').live('click',function(){
			var $_this = $(this);
			var $title = $('#pop_CityBox #title');
			var $city_id = $_this.attr('city_id');
			var $country_id = $_this.attr('country_id');
			/**/
			if($title.val()==''){
				$title.focus();
				alertify.error(field_is_required);
				return false;
			}
			var adata = {
				'city_id' : $city_id,
				'title' : $title.val(),
				'country_id' : $country_id
			};
			global_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=country&act=ajSubmitQuickCity",
				data: adata,
				dataType: "html",
				success: function(html){
					global_loading(0);
					if(html.indexOf('_EXIST')>=0){
						alertify.error(message_error_exit);
					}else if(html.indexOf('_SUCCESS')>=0){
						var $htm = html.split('$$');
						loadCity($country_id,$htm[1]);
						$_this.closest('#pop_CityBox').find('.close_pop').trigger('click');
					}else{
						alertify.error(message_error_sys);
					}
				}
			});
			return false;
		});
		/**/
		$('.ajCreateQuickNewDistrict').live('click',function(){
			var $this = $(this);
			var $city_id = $('#city_id').val();
			if($city_id==''){
				$('#city_id').focus();
				alert(message_city_required);
				return false;
			}
			global_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=country&act=ajLoadCreateNewDistrict",
				data: {'city_id': $city_id},
				dataType: "html",
				success: function(html){
					makepopup(400,'auto',html,'pop_DistrictBox');
					global_loading(0);
				}
			});
			return false;
		});
		$('.ajSubmitQuickDistrict').live('click',function(){
			var $_this = $(this);
			var $title = $('#pop_DistrictBox #title');
			var $district_id = $_this.attr('district_id');
			var $city_id = $_this.attr('city_id');
			/**/
			if($title.val()==''){
				$title.focus();
				alertify.error(field_is_required);
				return false;
			}
			var adata = {
				'district_id' : $district_id,
				'title' : $title.val(),
				'city_id' : $city_id
			};
			global_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=country&act=ajSubmitQuickDistrict",
				data: adata,
				dataType: "html",
				success: function(html){
					global_loading(0);
					if(html.indexOf('_EXIST')>=0){
						alertify.error(message_error_exit);
					}else if(html.indexOf('_SUCCESS')>=0){
						var $htm = html.split('$$');
						loadDistrict($city_id,$htm[1]);
						$_this.closest('#pop_DistrictBox').find('.close_pop').trigger('click');
					}else{
						alertify.error(message_error_sys);
					}
				}
			});
			return false;
		});
	});
	function loadCity($country_id,$city_id){
		$('select[name=iso-city_id]').html('<option value="">Loading...</option>');
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=country&act=ajMakeSelectboxCity",
			data: {"country_id"	: $country_id, 'city_id':$city_id},
			dataType: "html",
			success: function(html){
			  $('select[name=iso-city_id]').html(html);
			}
		});
	}
	function loadDistrict($city_id,$district_id){
		$('select[name=iso-district_id]').html('<option value="">Loading...</option>');
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=country&act=ajMakeSelectboxDistrict",
			data: {"city_id":$city_id, 'district_id':$district_id},
			dataType: "html",
			success: function(html){
			  $('select[name=iso-district_id]').html(html);
			}
		});
	}
</script>
{/literal}