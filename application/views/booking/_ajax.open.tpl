<div class="modal-dialog modal-ipad-xl">
	{assign var = toId value = $clsISO->getUniqid()}
	<form class="d-none" action="#" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="hid" value="upload" />
		<input type="file" toId="{$toId}" class="upload_image_{$toId}" onChange="$Core.booking.upload_image(this, event)" 
		name="upload_image" booking_id="{$booking_id}" />
	</form>
	<form method="POST" action="#" class="modal-content">
		<div class="modal-header border-bottom bg-lighter">
			<h5 class="modal-title">{if $action eq '_add'}Thêm mới{else}Chỉnh sửa{/if} booking</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if $booking_type eq $smarty.const._BOOKING_TYPE_INVESTOR_ID}
			<div class="form-row mb-2">
				<div class="col-4">
					<label for="trans_code" class="form-label mb-1">Loại Booking</label>
					<div class="clearfix"></div>
					{assign var = rf_Id value = $clsISO->getUniqid()}
					<div class="btn-group d-flex xs:w-100" role="group" aria-label="Sắp xếp" bis_skin_checked="1">
						<input type="radio"{if $oneBooking.booking_type eq $smarty.const._BOOKING_TYPE_INTERNAL_ID} checked{/if} 
						class="btn-check" uid="{$rf_Id}" onchange="$Core.calendar.do_reload(this, event)" name="booking_type" 
						id="internal_{$rf_Id}" value="{$smarty.const._BOOKING_TYPE_INTERNAL_ID}">
						<label data-toggle="ripple" class="btn btn-outline-default text-nowrap" for="internal_{$rf_Id}">Nội bộ</label>
						<input type="radio"{if $oneBooking.booking_type eq $smarty.const._BOOKING_TYPE_INVESTOR_ID} checked{/if} 
						class="btn-check" uid="{$rf_Id}" onchange="$Core.calendar.do_reload(this, event)" name="booking_type" id="investor_{$rf_Id}" value="{$smarty.const._BOOKING_TYPE_INVESTOR_ID}">
						<label data-toggle="ripple" class="btn btn-outline-default text-nowrap" for="investor_{$rf_Id}">Chủ ĐT</label>
					</div>
				</div>
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Mã Booking</label>
					<input type="text" id="trans_code" name="booking_code" class="form-control required" 
						placeholder="Mã Booking" value="{$oneBooking.booking_code}" readonly="readonly" >
				</div>
				<div class="col-4">
					<label for="booking_date" class="form-label mb-1">Ngày booking</label>
					<input type="datetime-local" id="booking_date" name="booking_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToISOString($oneBooking.booking_date)}">
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 col-lg-8 mb-2 mb-lg-0">
					<label class="form-label mb-1">Họ và tên</label>
					<input type="text" name="customer_name" class="form-control required" placeholder="Nguyễn Văn A" 
					value="{if $action eq '_edit'}{$more_information.customer_name}{/if}">
				</div>
				<div class="col-6 col-lg-4">
					<label class="form-label mb-1">Số CCID</label>
					<input type="text" name="customer_idcard" class="form-control" placeholder="Số CCID" 
					value="{if $action eq '_edit'}{$more_information.customer_idcard}{/if}">
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="bg-lighter rounded-2 p-3 mb-2">
				<div class="form-group form-row mb-2">
					<div class="col-12 col-md-5 mb-2 mb-lg-0">
						<label class="form-label mb-1">Dự án</label>
						<div class="clearfix"></div>
						<select placeholder="Chọn dự án" class="iso-selectizeNotSearch required w-100" uid="{$uid}" name="project_id" 
						data-url="{$PCMS_URL}/index.php?mod=home&act=list_project" onChange="$Core.booking.load_block(this, event)" 
						data-optgroup="false">
							{if $action eq '_edit' && !empty($oneBooking.project_id)}
							<option value="{$oneBooking.project_id}" selected="selected">
								{$clsProject->getTitle($oneBooking.project_id)}
							</option>
							{/if}
						</select>
					</div>
					<div class="col-6 col-md-4">
						<label id="product_code" class="form-label mb-1">Phân khu</label>
						<div class="clearfix"></div>
						<div class="slb_block_{$uid}">
							<select placeholder="Phân khu" class="iso-selectizeNotSearch w-100" uid="{$uid}" name="block_id" 
							onChange="$Core.booking.load_building(this, event)" data-optgroup="false">
								<option value="0">Phân khu</option>
								{if !empty($arr_blocks)}
									{foreach from=$arr_blocks item = _oBlock}
									<option value="{$_oBlock.property_id}"{if $_oBlock.property_id eq $oneBooking.block_id} selected="selected"{/if}>{$_oBlock.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<label for="totalgrand" class="form-label mb-1">Tòa nhà</label>
						<div class="clearfix"></div>
						<div class="slb_building_{$uid}">
							<select placeholder="Tòa nhà" class="iso-selectizeNotSearch w-100" uid="{$uid}" 
							data-optgroup="false" name="building_id">
								<option value="0">Tòa nhà</option>
								{if !empty($arr_buildings)}
									{foreach from=$arr_buildings item = _oBuilding}
									<option value="{$_oBuilding.property_id}"{if $_oBuilding.property_id eq $oneBooking.building_id} selected="selected"{/if}>{$_oBuilding.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-6 col-md-3 mb-2 mb-lg-0">
						<label class="form-label mb-1">Loại căn</label>
						<div class="clearfix"></div>
						<select placeholder="Loại căn" class="iso-selectize w-100" name="bedroom_id">
							{$clsProperty->getSelectByProperty('_BEDROOM', $oneBooking.bedroom_id, "Loại căn")}
						</select>
					</div>
					<div class="col-6 col-md-3 mb-2 mb-lg-0">
						<label for="unit_axis" class="form-label mb-1">Trục căn</label>
						<input type="text" id="unit_axis" name="unit_axis" class="form-control" 
						placeholder="Trục căn" lang="vi-VN" value="{if $action eq '_edit'}{$more_information.unit_axis}{/if}">
					</div>
					<div class="col-6 col-md-3">
						<label for="floor_range" class="form-label mb-1">Khoảng tầng</label>
						<input type="text" id="floor_range" name="floor_range" class="form-control" 
						placeholder="Khoảng tầng" value="{if $action eq '_edit'}{$more_information.floor_range}{/if}">
					</div>
					<div class="col-6 col-md-3 mb-2 mb-lg-0">
						<label for="floor_type" class="form-label mb-1">Loại tầng</label>
						<select placeholder="Loại tầng" class="iso-selectize w-100" name="floor_type">
							<option value="">Loại tầng</option>
							{foreach from=$floor_arrs key = _oKey item=_oVal}
							<option{if $_oKey eq $more_information.floor_type && $action eq '_edit'} selected{/if} value="{$_oKey}">{$_oVal}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6 col-lg-3 mb-2 mb-lg-0">
					<label for="totalgrand" class="form-label mb-1">Số tiền</label>
					<div class="input-group input-group-merge">
						<input type="text" id="amount" name="amount" class="form-control numberonly price-In required" 
						autocomplete="off" placeholder="0.00" value="{$oneBooking.amount}">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
				<div class="col-6 col-lg-4 mb-2 mb-lg-0">
					<label for="totalgrand" class="form-label mb-1">Mã FT</label>
					<input type="text" name="trans_code" class="form-control required" autocomplete="off" 
					placeholder="Mã FT" value="{if $action eq '_edit'}{$more_information.trans_code}{/if}">
				</div>
				<div class="col-12 col-lg-5">
					<label for="totalgrand" class="form-label mb-1">File UNC</label>
					<div class="input-group">
						<input type="text" class="form-control payment_order_{$toId}" name="payment_order" 
						placeholder="Copy/paste UNC" onpaste="$Core.booking.upload_image_clipboard(this, event)" 
						to_field="payment_order" toId="{$toId}" booking_id="{$booking_id}" value="{if $action eq '_edit'}{$more_information.payment_order}{/if}" />
						<button href="javascript:void(0);" toId="{$toId}" to_field="payment_order" booking_id="{$booking_id}" 
						onclick="$Core.booking.select_image(this, event)" class="btn btn-sm btn-outline-default">
							<i class="bx bx-upload"></i> Tải UNC
						</button>
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<label  class="form-label mb-1">Nội dung</label>
				<textarea name="content" class="form-control required" rows="2" autocomplete="off" 
				placeholder="Nội dung UNC">{if $action eq '_edit'}{$more_information.content}{/if}</textarea>
			</div>
			{else}
			<div class="form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Loại Booking</label>
					<div class="clearfix"></div>
					<div class="btn-group d-flex xs:w-100" role="group" aria-label="Sắp xếp" bis_skin_checked="1">
						<input type="radio"{if $oneBooking.booking_type eq $smarty.const._BOOKING_TYPE_INTERNAL_ID} checked{/if} 
						class="btn-check" uid="{$uid}" onchange="$Core.calendar.do_reload(this, event)" name="booking_type" 
						id="internal_{$uid}" value="{$smarty.const._BOOKING_TYPE_INTERNAL_ID}">
						<label data-toggle="ripple" class="btn btn-outline-default" for="internal_{$uid}">Nội bộ</label>
						<input type="radio"{if $oneBooking.booking_type eq $smarty.const._BOOKING_TYPE_INVESTOR_ID} checked{/if} 
						class="btn-check" uid="{$uid}" onchange="$Core.calendar.do_reload(this, event)" name="booking_type" id="investor_{$uid}" value="{$smarty.const._BOOKING_TYPE_INVESTOR_ID}"{if !$permiss_full} disabled{/if}>
						<label data-toggle="ripple" class="btn btn-outline-default" for="investor_{$uid}">Chủ ĐT</label>
					</div>
				</div>
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Mã Booking</label>
					<input type="text" id="trans_code" name="booking_code" class="form-control required" 
						placeholder="Mã Booking" value="{$oneBooking.booking_code}" readonly="readonly" />
				</div>
				<div class="col-6 col-md-4">
					<label for="booking_date" class="form-label mb-1">Ngày booking</label>
					<input type="datetime-local" id="booking_date" name="booking_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToISOString($oneBooking.booking_date)}">
				</div>
			</div>
			{if $permiss_full eq '1' || $clsISO->checkDEV()}
			<div class="form-row mb-2">
				<div class="col-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Phòng ban</label>
					<div class="clearfix"></div>
					<select data-bind="change" class="iso-selectize required w-100" name="department_id" placeholder="Phòng ban" 
					onChange="$Core.booking.handle_dep_change(this, event)" staff_id="{$more_information.staff_id}">
						<option value="">Phòng ban</option>
						{$clsProperty->getSelectByProperty('_DEPARTMENT', $more_information.department_id, "Phòng ban")}
						<option value="10000">Đối tác [OR] Đại lý</option>
					</select>
				</div>
				<div class="col-8">
					<div class="group_partner{if $oneBooking.department_id eq '10000'}{else} d-none{/if}">
						<label class="form-label mb-1">Tên Đối tác [OR] Đại lý</label>
						<input type="text" name="amount" class="form-control" 
						autocomplete="off" placeholder="Tên đối tác" value="">
					</div>
					<div class="group_staff{if $oneBooking.department_id ne '10000'}{else} d-none{/if}">
						<label class="form-label mb-1">Nhân viên</label>
						<select class="iso-selectizeImageSync required w-100" placeholder="Nhân viên" name="staff_id">
							{if $action eq '_edit' && !empty($more_information.staff_id)}
							<option value="{$more_information.staff_id}" selected>
								{$clsProfile->getIndentity($more_information.staff_id)}
							</option>
							{/if}
						</select>
					</div>
				</div>
			</div>
			{else}
				<input type="hidden" name="staff_id" value="{$profile_id}" />
				<input type="hidden" name="department_id" value="{$oneProfile.department_id}" />
			{/if}
			<div class="widget-block mb-2">
				<div onclick="$Core.helper.toggle_block(this,event)" class="widget-header">Khách hàng UNC</div>
				<div class="widget-content">
					<div class="form-row mb-2">
						<div class="col-6 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Tên khách hàng</label>
							<input type="text" name="customer_name" class="form-control required" placeholder="Nguyễn Văn A" value="{if $action eq '_edit'}{$more_information.customer_name}{/if}">
						</div>
						<div class="col-6 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Điện thoại</label>
							{assign var = uid value = $clsISO->getUniqid()}
							<input type="text" name="customer_phone" class="form-control" placeholder="Điện thoại" value="{if $action eq '_edit'}{$more_information.customer_phone}{/if}">
						</div>
						<div class="col-12 col-md-4">
							<label class="form-label mb-1">E-mail</label>
							<input type="text" name="customer_email" class="form-control" placeholder="example@gmail.com" value="{if $action eq '_edit'}{$more_information.customer_email}{/if}">
						</div>
					</div>
					<div class="d-flex align-items-center">
						<a class="text-muted cursor-pointer" onclick="$Core.util.toggle_block(this, event)" toId="ccid_{$uid}">
							<i class="bx bx-chevron-down"></i> Thêm căn cước
						</a>
					</div>
					<div id="ccid_{$uid}" class="form-group d-none form-row">
						<div class="col-6 mb-2 mb-lg-0">
							<div class="form-label mb-1 d-flex align-items-center justify-content-between">
								<label class="mb-0">Mặt trước</label>
								<input type="hidden" name="ccid_front" class="ccid_front_{$toId}" value="{$more_information.ccid_front}">
								<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" booking_id="{$booking_id}" 
								onClick="$Core.booking.select_image(this,event);" toId="{$toId}" to_field="ccid_front" title="Tải ảnh nên">{$clsISO->makeIcon('bx-upload')}</a>
							</div>
							<div class="cursor-pointer">
								<div onpaste="$Core.booking.upload_image_clipboard(this, event)" booking_id="{$booking_id}" toId="{$toId}" to_field="ccid_back" id="ccid_front_{$toId}" class="w-100 overflow-hidden d-flex align-items-center justify-content-center border rounded-1 position-relative bg-lighter h-px-{if $deviceType eq 'phone'}100{else}175{/if} xs:h-px-100">
									{if !empty($more_information.ccid_front)}
									<img src="{$clsISO->getGoogleUrl($more_information.ccid_front)}" class="w-100 h-100 rounded-1" />
									{else}
									<div class="text-muted text-center">
										<i class='bx bx-camera'></i> Ảnh mặt trước<br />
										Paste hình ảnh vào đây
									</div>
									{/if}
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="form-label mb-1 d-flex align-items-center justify-content-between">
								<label class="mb-0">Mặt sau</label>
								<input type="hidden" name="ccid_back" class="ccid_back_{$toId}" value="{$more_information.ccid_back}" />
								<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" booking_id="{$booking_id}" 
								onClick="$Core.booking.select_image(this,event);" toId="{$toId}" to_field="ccid_back" title="Tải ảnh nên">{$clsISO->makeIcon('bx-upload')}</a>
							</div>
							<div class="cursor-pointer">
								<div onpaste="$Core.booking.upload_image_clipboard(this, event)" booking_id="{$booking_id}" toId="{$toId}" to_field="ccid_back" id="ccid_back_{$toId}" class="d-flex align-items-center justify-content-center w-100 overflow-hidden border rounded-1 position-relative bg-lighter h-px-{if $deviceType eq 'phone'}100{else}175{/if} xs:h-px-100">
									{if !empty($more_information.ccid_back)}
									<img src="{$clsISO->getGoogleUrl($more_information.ccid_back)}" class="w-100 h-100 rounded-1" />
									{else}
									<div class="text-muted text-center">
										<i class='bx bx-camera' ></i> Ảnh mặt sau <br />
										Paste hình ảnh vào đây
									</div>
									{/if}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-md-5 mb-2 mb-lg-0">
					<label class="form-label mb-1">Dự án</label>
					<div class="clearfix"></div>
					<select placeholder="Chọn dự án" class="iso-selectizeNotSearch required w-100" uid="{$uid}" name="project_id" 
					data-url="{$PCMS_URL}/index.php?mod=home&act=list_project" onChange="$Core.booking.load_block(this, event)" 
					data-optgroup="false">
						{if !empty($oneBooking.project_id)}
						<option value="{$oneBooking.project_id}" selected="selected">
							{$clsProject->getTitle($oneBooking.project_id)}
						</option>
						{/if}
					</select>
				</div>
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label id="product_code" class="form-label mb-1">Phân khu</label>
					<div class="clearfix"></div>
					<div class="slb_block_{$uid}">
						<select placeholder="Phân khu" class="iso-selectizeSync required w-100" uid="{$uid}" 
							name="block_id" onChange="$Core.booking.load_building(this, event)" data-optgroup="false">
							<option value="0">Phân khu</option>
							{if !empty($arr_blocks)}
								{foreach from=$arr_blocks item = _oBlock}
								<option value="{$_oBlock.property_id}"{if $_oBlock.property_id eq $oneBooking.block_id} selected="selected"{/if}>{$_oBlock.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</div>
				<div class="col-6 col-md-3">
					<label for="totalgrand" class="form-label mb-1">Tòa nhà</label>
					<div class="clearfix"></div>
					<div class="slb_building_{$uid}">
						<select placeholder="Tòa nhà" class="iso-selectizeSync w-100" uid="{$uid}" name="building_id" 
							onChange="$Core.booking.do_changed(this, event)" key="floor_range">
							<option value="0">Tòa nhà</option>
							{if !empty($arr_buildings)}
								{foreach from=$arr_buildings item = _oBuilding}
								<option value="{$_oBuilding.property_id}"{if $_oBuilding.property_id eq $oneBooking.building_id} selected="selected"{/if}>{$_oBuilding.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6 col-lg-3 mb-2 mb-lg-0">
					<label for="totalgrand" class="form-label mb-1">Số tiền</label>
					<div class="input-group input-group-merge">
						<input type="text" id="amount" name="amount" class="form-control numberonly price-In required" 
						autocomplete="off" placeholder="0.00" value="{$oneBooking.amount}">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
				<div class="col-6 col-lg-5 mb-2 mb-lg-0">
					<label for="totalgrand" class="form-label mb-1">File UNC</label>
					<div class="input-group">
						<input type="text" class="form-control payment_order_{$toId}" name="payment_order" placeholder="Copy/paste UNC" onpaste="$Core.booking.upload_image_clipboard(this, event)" to_field="payment_order" toId="{$toId}" booking_id="{$booking_id}" value="{if $action eq '_edit'}{$more_information.payment_order}{/if}" onpaste="$Core.booking.upload_image_clipboard(this, event)" />
						<button href="javascript:void(0);" toId="{$toId}" to_field="payment_order" booking_id="{$booking_id}" 
						onclick="$Core.booking.select_image(this, event)" class="btn btn-sm btn-outline-default">
							<i class="bx bx-upload"></i> Tải UNC
						</button>
					</div>
				</div>
				<div class="col-12 col-lg-4">
					<label for="totalgrand" class="form-label mb-1">Nội dung</label>
					<input type="text" name="content" class="form-control required" autocomplete="off" 
					placeholder="Nội dung UNC" value="{if $action eq '_edit'}{$more_information.content}{/if}">
				</div>
			</div>
			<div class="bg-lighter rounded-3 mb-2 p-2">
				<div class="form-row">
					<div class="col-6 col-md-3 mb-2 mb-lg-0">
						<label class="form-label mb-1">Loại căn</label>
						<div class="clearfix"></div>
						<select placeholder="Loại căn" class="iso-selectize w-100" name="bedroom_id" uid="{$uid}">
							<option value="">Loại căn</option>
							{$clsProperty->getSelectByProperty('_BEDROOM', $oneBooking.bedroom_id, "Loại căn")}
						</select>
					</div>
					<div class="col-6 col-md-3 mb-2 mb-lg-0">
						<label for="unit_axis" class="form-label mb-1">Trục căn</label>
						<select class="form-control form-select" onChange="$Core.booking.do_changed(this, event)" 
							key="unit_axis" name="unit_axis" uid="{$uid}">
							<option value="">Trục căn</option>
							{if !empty($template_configs)}
								{foreach from=$template_configs item = _oT}
								<option{if $action eq '_edit' && $oneBookingMeta.unit_axis eq $_oT.code} selected{/if} bedroom_id="{$_oT.bedroom_id}" value="{$_oT.code}">{$_oT.code}</option>
								{/foreach}
							{/if}
						</select>
					</div>
					<div class="col-6 col-md-3">
						<label for="floor_range" class="form-label mb-1">Khoảng tầng</label>
						<select name="floor_range" class="form-control form-select" uid="{$uid}" 
							onChange="$Core.booking.do_changed(this, event)" key="floor_level">
							{$html_floor_range_options}
						</select>
					</div>
					<div class="col-6 col-md-3">
						<label for="floor_type" class="form-label mb-1">Loại tầng</label>
						<select class="form-control form-select" uid="{$uid}" name="floor_type">
							<option value="">Loại tầng</option>
							{foreach from=$floor_arrs key = _oKey item=_oVal}
							<option{if $_oKey eq $oneBookingMeta.floor_type && $action eq '_edit'} selected{/if} value="{$_oKey}">{$_oVal}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
			{/if}
			<div class="widget-block">
				<div onclick="$Core.helper.toggle_block(this,event)" class="widget-header">Ghi chú</div>
				<div class="widget-content">
					<textarea class="form-control" placeholder="Viết ghi chú" name="staff_notes" rows="2">{if !empty($more_information.staff_notes)}{$more_information.staff_notes}{/if}</textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer border-top bg-lighter">
			<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Hủy bỏ</button>
			<button data-toggle="ripple" type="button" holderG="save" booking_id="{$booking_id}" booking_type="{$booking_type}" 
			onClick="$Core.booking.save_booking(this, event)" class="btn flex-fill btn-primary">Lưu lại</button>
			<button data-toggle="ripple" type="button" holderG="continue"  booking_id="{$booking_id}" booking_type="{$booking_type}" 
			onClick="$Core.booking.save_booking(this, event)" class="btn flex-fill no-shadow btn-success">Lưu & thêm</button>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	input[name=ms_date]{ width:100px !important;}
	.selectize-dropdown{ z-index:3 !important}
</style>
{/literal}
