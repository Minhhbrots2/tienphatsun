<div class="modal-dialog modal-dialog-centered">
	{assign var = toId value = $clsISO->getUniqid()}
	<form class="d-none" action="#" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="hid" value="upload" />
		<input type="file" toId="{$toId}" class="upload_image_{$toId}" onChange="$Core.booking.upload_image(this, event)" 
		name="upload_image" booking_id="{$booking_id}" />
	</form>
	<form method="POST" action="#" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Chỉnh sửa{/if} {$titlePage} <br />
				<span class="text-main text-fs-11">{$clsProfile->getFullName($profile_id, $oneProfile)} -  {$clsISO->convertTimeToText($smarty.now, true)}</span>
			</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if $holderG eq 'priority'}
			<div class="form-group  mb-2">
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
			<div class="form-group form-row mb-2">
				<div class="col-6 col-md-6">
					<label id="product_code" class="form-label mb-1">Phân khu</label>
					<div class="clearfix"></div>
					<div class="slb_block_{$uid}">
						<select placeholder="Phân khu" class="iso-selectizeNotSearch w-100" uid="{$uid}" name="block_id" 
						onChange="$Core.booking.load_building(this, event)" data-optgroup="false">
							<option value="0">Phân khu</option>
							{if !empty($list_blocks)}
								{foreach from=$list_blocks item = _oBlock}
								<option value="{$_oBlock.property_id}"{if $_oBlock.property_id eq $oneBooking.block_id} selected="selected"{/if}>{$_oBlock.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</div>
				<div class="col-6 col-md-6">
					<label for="totalgrand" class="form-label mb-1">Tòa nhà</label>
					<div class="clearfix"></div>
					<div class="slb_building_{$uid}">
						<select placeholder="Tòa nhà" class="iso-selectizeNotSearch w-100" uid="{$uid}" 
						data-optgroup="false" name="building_id" onChange="$Core.booking.load_floor_range(this, event)">
							<option value="0">Tòa nhà</option>
							{if !empty($list_buildings)}
								{foreach from=$list_buildings item = _oBuilding}
								<option value="{$_oBuilding.property_id}"{if $_oBuilding.property_id eq $oneBooking.building_id} selected="selected"{/if}>{$_oBuilding.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</div>
			</div>
			<hr class="my-3" />
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Ngày thực hiện</label>
					<input type="datetime-local" id="action_date" name="action_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToISOString($oneItem.action_date)}">
				</div>
				<div class="col-6">
					<label for="unit_axis" class="form-label mb-1">Trục căn</label>
					<select name="unit_axis" id="unit_axis" class="form-control form-select" uid="{$uid}" >
						<option value="">Trục căn</option>
						{if !empty($template_configs)}
							{foreach from=$template_configs item = _oT}
							<option{if $action eq '_edit' && $more_information.unit_axis eq $_oT.code} selected{/if} value="{$_oT.code}">{$_oT.code}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="floor_range" class="form-label mb-1">Khoảng tầng</label>
					<div class="input-group">
						<select name="floor_range" class="form-control form-select" uid="{$uid}" 
							onChange="$Core.booking.load_floor_level(this, event)">
							{$html_floor_range_options}
						</select>
						<input type="number" id="priority" name="priority" class="form-control numberonly" 
							placeholder="Ưu tiên" value="{if $action eq '_edit'}{$more_information.priority}{/if}">
					</div>
				</div>
				<div class="col-6 mb-2 mb-lg-0">
					<label for="floor_type" class="form-label mb-1">Loại tầng</label>
					<select placeholder="Loại tầng" class="form-control form-select" uid="{$uid}" name="floor_type">
						<option value="">Loại tầng</option>
						{foreach from=$floor_arrs key = _oKey item=_oVal}
						<option{if $_oKey eq $more_information.floor_type && $action eq '_edit'} selected{/if} value="{$_oKey}">{$_oVal}</option>
						{/foreach}
					</select>
				</div>
			</div>
			{elseif $holderG eq 'matched'}
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Ngày thực hiện</label>
					<input type="datetime-local" id="action_date" name="action_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToISOString($oneItem.action_date)}">
				</div>
				<div class="col-6">
					<label for="stock_code" class="form-label mb-1">Mã căn</label>
					<input type="text" id="stock_code" name="stock_code" class="form-control required" 
						placeholder="Mã căn hộ" value="{$more_information.stock_code}" />
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="amount" class="form-label mb-1">Số tiền</label>
					{assign var = reId value = $clsISO->getUniqid()}
					<div class="input-group input-group-merge mb-1">
						<input type="text" id="amount" name="amount" class="form-control {$reId} numberonly price-In required" 
						autocomplete="off" placeholder="0.00" value="{$more_information.amount}">
						<span class="input-group-text">.đ</span>
					</div>
					<div class="d-flex gap-2 align-items-center text-fs-12">
						<span class="text-muted">Số tiền:</span>
						{if !empty($money_arrs)}
							{foreach from=$money_arrs item = _money}
							<a onClick="$Core.booking.select_this(this, event)" data-money="{$_money}" 
							toId="{$reId}" class="btn btn-xs rounded-pill btn-outline-default">{$_money}</a>
							{/foreach}
						{/if}
					</div>
				</div>
				<div class="col-6">
					<label for="trans_code" class="form-label mb-1">Người đứng số</label>
					<select class="iso-selectizeImageSearch required w-100" placeholder="Nhân viên" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" name="staff_sale_id" data-optgroup="false">
						{if $action eq '_edit' && !empty($more_information.staff_sale_id)}
						<option value="{$more_information.staff_sale_id}" selected>{$clsProfile->getFullName($more_information.staff_sale_id)}</option>
						{/if}
					</select>
				</div>
			</div>
			{elseif $holderG eq 'refund_req'}
			<div class="form-row">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Ngày thực hiện</label>
					<input type="datetime-local" id="action_date" name="action_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToISOString($oneItem.action_date)}">
				</div>
				<div class="col-6">
					<label for="booking_date" class="form-label mb-1">Số tiền hoàn</label>
					{assign var = reId value = $clsISO->getUniqid()}
					<div class="input-group input-group-merge mb-1">
						<input type="text" id="amount" name="amount" class="form-control {$reId} numberonly price-In required" 
						autocomplete="off" placeholder="0.00" value="{if $action eq '_edit'}{$more_information.amount}{/if}">
						<span class="input-group-text">.đ</span>
					</div>
					<div class="d-flex gap-2 align-items-center text-fs-12">
						<span class="text-muted">Số tiền:</span>
						{if !empty($money_arrs)}
							{foreach from=$money_arrs item = _money}
							<a onClick="$Core.booking.select_this(this, event)" data-money="{$_money}" 
							toId="{$reId}" class="btn btn-xs rounded-pill btn-outline-default">{$_money}</a>
							{/foreach}
						{/if}
					</div>
				</div>
			</div>
			<div class="mt-n2 mb-2">
				<label for="trans_code" class="form-label mb-1">Người nhận</label>
				<select class="iso-selectizeImageSearch required w-100" placeholder="Người nhận" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=all" name="receiver_id" data-optgroup="false">
					{if $action eq '_edit' && !empty($more_information.receiver_id)}
					<option value="{$more_information.receiver_id}" selected="selected">
						{$clsProfile->getFullName($more_information.receiver_id)}
					</option>
					{/if}
				</select>
			</div>
			{elseif $holderG eq 'refund'}
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Ngày thực hiện</label>
					<input type="datetime-local" id="action_date" name="action_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToISOString($oneItem.action_date)}">
				</div>
				<div class="col-6">
					<label for="trans_code" class="form-label mb-1">Người nhận</label>
					<select class="iso-selectizeImageSearch required w-100" placeholder="Người nhận" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" name="receiver_id" data-optgroup="false">
						{if $action eq '_edit' && !empty($more_information.receiver_id)}
						<option value="{$more_information.receiver_id}" selected="selected">
							{$clsProfile->getFullName($more_information.receiver_id)}
						</option>
						{/if}
					</select>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="booking_date" class="form-label mb-1">Số tiền hoàn</label>
					{assign var = reId value = $clsISO->getUniqid()}
					<div class="input-group input-group-merge mb-1">
						<input type="text" id="amount" name="amount" class="form-control {$reId} numberonly price-In required" 
						autocomplete="off" placeholder="0.00" value="{if $action eq '_edit'}{$more_information.amount}{/if}">
						<span class="input-group-text">.đ</span>
					</div>
					<div class="d-flex gap-2 align-items-center text-fs-12">
						<span class="text-muted">Số tiền:</span>
						{if !empty($money_arrs)}
							{foreach from=$money_arrs item = _money}
							<a onClick="$Core.booking.select_this(this, event)" data-money="{$_money}" 
							toId="{$reId}" class="btn btn-xs rounded-pill btn-outline-default">{$_money}</a>
							{/foreach}
						{/if}
					</div>
				</div>
				<div class="col-6 col-lg-6">
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
			{elseif $holderG eq 'unit_match'}
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Ngày ký XNĐK</label>
					<input type="date" id="action_date" name="action_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToTextFormat($oneItem.action_date,'Y-m-d')}">
				</div>
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Mã căn</label>
					<input type="text" id="action_date" name="stock_code" class="form-control required" 
					placeholder="Mã căn" lang="vi-VN" value="">
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6">
					<label for="reg_sign_link" class="form-label mb-1">Link XNĐK</label>
					<input type="text" id="reg_sign_link" name="reg_sign_link" class="form-control required" 
					placeholder="Link XNĐK" lang="vi-VN" value="{if $action eq '_edit'}{$more_information.reg_sign_link}{/if}">
				</div>
				<div class="col-6">
					<label for="name_owner" class="form-label mb-1">Người đứng tên</label>
					<input type="text" id="name_owner" name="name_owner" class="form-control required" 
					placeholder="Nguyễn Văn A" lang="vi-VN" value="{if $action eq '_edit'}{$more_information.name_owner}{/if}">
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 col-lg-6">
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
				<div class="col-6 col-lg-6">
					<label for="totalgrand" class="form-label mb-1">File UNC tra soát</label>
					<div class="input-group">
						<input type="text" class="form-control payment_order_check_{$toId}" name="payment_order_check" 
						placeholder="Copy/paste UNC" onpaste="$Core.booking.upload_image_clipboard(this, event)" 
						to_field="payment_order_check" toId="{$toId}" booking_id="{$booking_id}" value="{if $action eq '_edit'}{$more_information.payment_order_check}{/if}" />
						<button href="javascript:void(0);" toId="{$toId}" to_field="payment_order_check" booking_id="{$booking_id}" 
						onclick="$Core.booking.select_image(this, event)" class="btn btn-sm btn-outline-default">
							<i class="bx bx-upload"></i> Tải UNC
						</button>
					</div>
				</div>
			</div>
			{elseif $holderG eq 'recall'}
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Ngày thu hồi</label>
					<input type="datetime-local" id="action_date" name="action_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToISOString($oneItem.action_date)}">
				</div>
			</div>
			{else}
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Ngày tra soát</label>
					<input type="date" id="action_date" name="action_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToTextFormat($oneItem.action_date,'Y-m-d')}">
				</div>
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Mã căn</label>
					<input type="text" id="action_date" name="stock_code" class="form-control required" 
					placeholder="Mã căn" lang="vi-VN" value="{if $action eq '_edit'}{$more_information.stock_code}{/if}">
				</div>
			</div>
			<div class="form-form mb-2">
				<label for="action_date" class="form-label mb-1">Nội dung tra soát</label>
				<textarea class="form-control" placeholder="Nội dung tra soát" name="content" rows="2">{if !empty($more_information.content) && $action eq '_edit'}{$more_information.content}{/if}</textarea>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 col-lg-6 mb-2 mb-lg-0">
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
				<div class="col-6 col-lg-6">
					<label for="totalgrand" class="form-label mb-1">File UNC tra soát</label>
					<div class="input-group">
						<input type="text" class="form-control payment_order_check_{$toId}" name="payment_order_check" 
						placeholder="Copy/paste UNC" onpaste="$Core.booking.upload_image_clipboard(this, event)" 
						to_field="payment_order_check" toId="{$toId}" booking_id="{$booking_id}" value="{if $action eq '_edit'}{$more_information.payment_order_check}{/if}" />
						<button href="javascript:void(0);" toId="{$toId}" to_field="payment_order_check" booking_id="{$booking_id}" 
						onclick="$Core.booking.select_image(this, event)" class="btn btn-sm btn-outline-default">
							<i class="bx bx-upload"></i> Tải UNC
						</button>
					</div>
				</div>
			</div>
			{/if}
			<div class="form-group mb-2">
				<label for="trans_code" class="form-label mb-1">Ghi chú</label>
				<textarea class="form-control" placeholder="Viết ghi chú" name="notes" rows="2">{if !empty($more_information.staff_notes)}{$more_information.notes}{/if}</textarea>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
					{if !empty($oneReport.attachments)}
						{foreach name=i from = $oneReport.attachments item = _oFile}
						<div class="MultiFile-label">
							<a class="MultiFile-remove" href="javascript:void(0)" data-url="{$_oFile}">x</a> 
							<span><span class="MultiFile-label" title="{$_oFile}">
								<span class="MultiFile-title">{$_oFile}</span></span>
							</span>
						</div>
						{/foreach}
					{/if}
				</div>
				<div class="clearfix"></div>
				<input name="attachments[]" type="file" multiple="multiple" class="maxsize-10240" id="attachments_{$uid}" />
			</div>
		</div>
		<div class="modal-footer">
			<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
			<button data-toggle="ripple" type="button" holderG="{$holderG}" activity_id="{$activity_id}" booking_id="{$booking_id}" booking_type="{$booking_type}" onClick="$Core.booking.save_activity(this, event)" class="btn btn-primary"><i class="bx bx-check"></i> Lưu lại</button>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	input[name=ms_date]{ width:100px !important;}
	.selectize-dropdown{ z-index:3 !important}
</style>
{/literal}
