{if $template eq '_form'}
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$core->get_Lang('Add_Address')}</strong></h3>
		</div>
		<form method="post">
			<div class="modal-body form-horizontal">
				<div class="p-md-3">
					<div class="form-group">
						<div class="col-md-6 col-xs-12">
							<label for="" class="col-form-label">{$core->get_Lang('FullName')} <span class="text-red">*</span></label>
							<input type="text" class="form-control required" value="{$address_info.fullname}" autocomplete="off" name="fullname" placeholder="Nhập họ tên khách hàng" />
						</div>
						<div class="col-md-6 col-xs-12">
							<label for="" class="col-form-label">Email</label>
							<input type="text" class="form-control" name="email" value="{$address_info.email}" autocomplete="off" placeholder="example@youremail.com" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-xs-12">
							<label for="" class="col-form-label">{$core->get_Lang('Company')}</label>
							<input type="text" class="form-control" name="companyname" value="{$address_info.companyname}" placeholder="Nhập tên công ty" />
						</div>
						<div class="col-md-6 col-xs-12">
							<label for="" class="col-form-label">{$core->get_Lang('Phone')} <span class="text-red">*</span></label>
							<input type="text" class="form-control required" name="phone" value="{$address_info.phone}" autocomplete="off" placeholder="Nhập số điện thoại" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12 col-xs-12">
							<label for="" class="col-form-label">{$core->get_Lang('Address')}</label>
							<input type="text" class="form-control" name="address" value="{$address_info.address}" autocomplete="off" placeholder="Nhập họ địa chỉ" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-xs-12">
							<label for="" class="col-form-label">{$core->get_Lang('Country')}</label>
							<select name="country_id" forId="address_edit_city" class="form-control" onChange="get_select_city(this)">
								{$clsCountry->makeSelectOption($address_info.country_id)}
							</select>
						</div>
						<div class="col-md-6 col-xs-12">
							<label for="" class="col-form-label">Postal/Zip Code</label>
							<input type="text" class="form-control" name="postcode" value="{$address_info.postcode}" placeholder="Nhập Zip Code" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-xs-12">
							<label for="" class="col-form-label">{$core->get_Lang('City')}</label>
							<select name="city_id" id="address_edit_city" forId="address_edit_district" onChange="get_select_district(this)" class="form-control">
								{if $address_info.country_id gt '0'}
									{$clsCity->makeSelectboxOption($address_info.country_id, $address_info.city_id)}
								{else}
								<option value="0">Tỉnh/Thành phố</option>	
								{/if}
							</select>
						</div>
						<div class="col-md-6 col-xs-12">
							<label for="" class="col-form-label">{$core->get_Lang('District')}</label>
							<select name="district_id" id="address_edit_district" class="form-control">
								{if $address_info.city_id gt '0'}
									{$clsDistrict->makeSelectOption($address_info.city_id, $address_info.district_id)}
								{else}
								<option value="0">Quận/Huyên</option>	
								{/if}
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="save_address_book(this)" address_id="{$address_id}" profile_id="{$profile_id}">
					{$core->get_Lang('Save')}
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>
{else}
	{if $billing_address}
		{foreach from=$billing_address key = address_id item = address_info name = i}
		<div class="form-group{if !$smarty.foreach.i.last} lines{/if}">
			<p class="type--subdued m-0">{$address_info.fullname}</p>
			<p class="type--subdued m-0">{$address_info.email}</p>
			<p class="type--subdued m-0">{$address_info.companyname}</p>
			<p class="type--subdued m-0">{$address_info.phone}</p>
			<p class="type--subdued m-0">{$address_info.address}</p>
			{if $address_info.country_id gt '0'}
			<p class="type--subdued m-0">{$clsCountry->getTitle($address_info.country_id)}</p>
			{/if}
			<p class="type--subdued m-0">{$address_info.zipcode}</p>
			{if $address_info.city_id gt '0'}
			<p class="type--subdued m-0">{$clsCity->getTitle($address_info.city_id)}</p>
			{/if}
			{if $address_info.district_id gt '0'}
			<p class="type--subdued m-0">{$clsDistrict->getTitle($address_info.district_id)}</p>
			{/if}
			<p class="clearfix">
				<a href="#" onClick="open_address_book(this)" class="mr-half" address_id="{$address_id}">{$core->get_Lang('Edit')}</a>
				<a href="#" onClick="delete_address_book(this)" address_id="{$address_id}">{$core->get_Lang('Delete')}</a>
			</p>
		</div>
		{/foreach}
	{/if}
{/if}