<div class="table-wrapper overflow-auto">
	<table class="table table-bordered" width="100%">
		<thead><tr>
			{if $deviceType ne 'phone'}
			<th width="30px" class="align-center nosort bg-lighter">STT</th>
			{/if}
			<th class="align-center text-left bg-lighter">Loại hình</th>
			<th class="align-center nosort text-left bg-lighter">Họ và tên</th>
			<th width="10%" class="align-center nosort text-left bg-lighter">Điện thoại</th>
			<th width="10%" class="align-center nosort bg-lighter">CCID</th>
			<th class="align-center text-left sortable bg-lighter">Mã căn</th>
			<th class="align-center nosort bg-lighter">Thời gian</th>
			<th class="align-center nosort bg-lighter">Ngày đăng ký</th>
			<td class="align-center nosort bg-lighter w-px-45"></td>
			<td class="align-center nosort bg-lighter w-px-45"></td>
		</tr></thead>
		{if !empty($list_regiser_mwfs)}
			{foreach name=i from=$list_regiser_mwfs key = _oK item = _oI}
			{assign var = type_id value = $_oI.type_id}
			<tr>
				{if $deviceType ne 'phone'}
				<td class="text-center">{$smarty.foreach.i.iteration}</td>
				{/if}
				<td class="text-left">{$arr_property_cached.$type_id}</td>
				<td class="text-left">{$_oI.full_name}</td>
				<td class="text-left">*****{$_oI.phone}</td>
				<td class="text-left">*****{$_oI.CCID}</td>
				<td class="text-left">
					{if !empty($_oI.stock_code)} 
						{$_oI.stock_code}
					{else}
						--
					{/if}
				</td>
				<td class="text-left">{$_oI.time}</td>
				<td>{$clsISO->convertTimeToText($_oI.reg_date, true)}</td>
				<td class="text-center">
					<form enctype="multipart/form-data" class="d-none">
						<input type="hidden" name="hid" value="upload" />
						<input type="file" class="select_qrCode_{$_oK}" uid="{$_oK}" onChange="$Core.report.upload_qrCode(this, event)" name="qrCode" />
					</form>
					{if !empty($_oI.qrCode)}
						<a href="{$_oI.qrCode}" class="text-dark" data-fancybox="true" title="Xem QrCode">
							<i class="bx bx-qr me-1"></i>
						</a>
					{else}
						{if $permiss_action eq '1'}
						<a data-bs-toggle="tooltip" class="text-dark" title="Tải QrCode" data-bs-trigger="hover" onclick="$Core.report.select_image_qrCode(this,event)" uid="{$_oK}" href="javascript:void(0);"><i class="bx bx-upload me-1"></i></a>
						{else}
							--
						{/if}
					{/if}
				</td>
				<td class="text-center">
					{if isset($_oI.is_confirmed) && $_oI.is_confirmed eq '1'}
					<a href="javascript:void(0)" data-bs-toggle="tooltip" class="text-success" title="Thành công" data-bs-trigger="hover"><i class='bx bx-check-circle'></i></a>
					{else}
					<a href="javascript:void(0)" data-bs-toggle="tooltip" class="text-dark" title="Xác nhận" data-bs-trigger="hover" onclick="$Core.report.confirm_register_mwf(this,event)" uid="{$_oK}"><i class='bx bx-check-circle'></i></a>
					{/if}
				</td>
			</tr>
			{/foreach}
		{/if}
	</table>
</div>
