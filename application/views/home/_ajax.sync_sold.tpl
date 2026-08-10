{if $template_type eq "_form"}
<div class="modal-dialog modal-dialog-centered">
	<form class="d-none" enctype="multipart/form-data">
		<input id="select_image_{$uid}" accept="image/jpeg,image/jpg,image/png,application/pdf" 
		type="file" tp="gd" p_id="{$p_id}" p_field="{$p_field}" onchange="$Core.billing.upload_image(this,event)" name="image" />
	</form>
	<form class="modal-content" method="POST" >
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="table table-wrapper mb-0">
				<table width="100%" class="table table-bordered m-0">
					<thead><tr>
						<th class="align-center bg-lighter h-px-35">Tiêu đề</th>
						<th class="align-center bg-lighter h-px-35">File Upload</th>
						<th class="w-px-50 bg-lighter h-px-30"></th>
					</tr></thead>
					<tbody class="holder_{$p_field}_{$uid}">
						{if !empty($list_items)}
							{foreach from=$list_items key = gId item = _oI}
							<tr class="tr_{$p_field}_{$uid} tr_{$p_field}_{$gId}">
								<td class="text-left">
									<input type="text" placeholder="Tiêu đề" name="{$p_field}[{$gId}][title]" 
									class="form-control" value="{$_oI.title}" />
								</td>
								<td class="text-left">
									<div class="input-group">
										<input type="text" placeholder="Nhập ảnh..." name="{$p_field}[{$gId}][image]" 
										class="form-control required {$p_field}_{$uid}" p_field="{$p_field}" p_id="{$p_id}" uid="{$gId}" onPaste="$Core.billing.upload_clipboard(this, event)" maxlength="255" value="{$_oI.image}" />
										<button type="button" toId="select_image_{$uid}" uid="{$gId}" onClick="$Core.billing.select_image(this, event)" class="btn btn-icon btn-outline-default">{$core->makeIcon('upload')}</button>
									</div>
								</td>
								<td class="text-left w-px-50">
									<button type="button" onClick="$Core.billing.delete_sp_file(this, event)" uid="{$gId}" p_field="{$p_field}" toId="{$stock_id}" class="btn p-2 btn-outline-default">{$clsISO->makeIcon('bx-trash-alt')}</button>
								</td>
							</tr>
							{/foreach}
						{else}
						<tr class="tr_{$p_field}_{$uid} tr_{$p_field}_{$uid}">
							<td class="text-left">
								<input type="text" placeholder="Tiêu đề" name="{$p_field}[{$uid}][title]" class="form-control required" />
							</td>
							<td class="text-left">
								<div class="input-group">
									<input type="text" placeholder="Nhập ảnh..." p_field="{$p_field}" class="form-control required {$p_field}_{$uid}" 
									p_id="{$p_id}" uid="{$uid}" onPaste="$Core.billing.upload_clipboard(this, event)" name="{$p_field}[{$uid}][image]" />
									<button type="button" toId="select_image_{$uid}" uid="{$uid}" onClick="$Core.helper.select_image(this, event)" class="btn btn-icon btn-outline-default">{$core->makeIcon('upload')}</button>
								</div>
							</td>
							<td  class="text-left w-px-50"></td>
						</tr>
						{/if}
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<div class="w-100 d-flex justify-content-between">
				<div class="p__left">
					<button type="button" toId="{$uid}" p_id="{$p_id}" p_field="{$p_field}" onClick="$Core.billing.add_sp_file(this, event)" class="btn btn-outline-default">{$clsISO->makeIcon('bx-plus', 'Thêm dòng')}</button>
				</div>
				<div class="p__right">
					<button type="button" onClick="$Core.billing.save_sp_file(this, event)" toId="{$toId}" p_id="{$p_id}" p_field="{$p_field}" class="btn btn-primary">Cập nhật</button>
				</div>
			</div>
		</div>
	</form>
</div>
{elseif $template_type eq '_deposit_paid'}
<div class="modal-dialog">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cập nhật {$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if $holderG eq 'deposit_paid'}
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="action_date" class="form-label mb-1">Ngày thực hiện</label>
					<input type="datetime-local" id="action_date" name="action_date" class="form-control required" 
					placeholder="dd/mm/yy" lang="vi-VN" value="{$clsISO->convertTimeToISOString($deposit_paid_to_company.action_date)}">
				</div>
				<div class="col-6">
					<label for="deposit_paid_amount" class="form-label mb-1">Số tiền</label>
					<div class="input-group input-group-merge mb-1">
						<input type="text" id="deposit_paid_amount" name="deposit_paid_amount" class="form-control numberonly price-In required" autocomplete="off" placeholder="0.00" value="{$deposit_paid_to_company.deposit_paid_amount}">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6">
					<label for="payer_name" class="form-label mb-1">Tên KH/UNC</label>
					<input type="text" id="payer_name" name="payer_name" class="form-control required" placeholder="Nguyễn Văn A" 
					value="{$deposit_paid_to_company.payer_name}">
				</div>
				<div class="col-6">
					<label for="trans_code" class="form-label mb-1">Mã FT</label>
					<input type="text" id="trans_code" name="trans_code" class="form-control" value="{$deposit_paid_to_company.trans_code}" placeholder="Mã giao dịch">
				</div>
			</div>
			<div class="form-group mb-2">
				<label for="trans_code" class="form-label mb-1">Ghi chú</label>
				<textarea class="form-control" placeholder="Viết ghi chú" name="notes" rows="2">{$deposit_paid_to_company.notes}</textarea>
			</div>
			{else}
				{foreach from=$loop_arrs key= _oKey item = _oText}
				<div class="mb-2 border rounded-2 p-3 interest_accrued_{$uid}">
					<input type="hidden" name="interest_accrued[{$_oKey}][status]" value="0" />
					<label class="d-flex align-items-center gap-2 mb-2 ant-checkbox">
						<input uid="{$uid}" type="checkbox" class="js__interest_accrued-status" value="1" 
							name="interest_accrued[{$_oKey}][status]"{if $interest_accrued.$_oKey.status eq '1'} checked{/if}>
						<div class="d-flex flex-column gap text-nowrap">{$_oText}</div>
					</label>
					<div class="form-row mb-2">
						<div class="col-6 mb-2 mb-lg-0">
							<label class="form-label mb-1">Số tiền</label>
							<div class="input-group input-group-merge mb-1">
								<input uid="{$uid}" type="text" name="interest_accrued[{$_oKey}][amount]" autocomplete="off" placeholder="0.00" 
									class="form-control numberonly price-In required_field" value="{$interest_accrued.$_oKey.amount}">
								<span class="input-group-text">.đ</span>
							</div>
						</div>
						<div class="col-6">
							<label class="form-label mb-1">Ngày phát sinh</label>
							<input uid="{$uid}" type="date" name="interest_accrued[{$_oKey}][accrual_date]" autocomplete="off" placeholder="dd/mm/YYYY" class="form-control required_field" value="{$interest_accrued.$_oKey.accrual_date}">
						</div>
					</div>
					<div class="form-group mb-2">
						<label for="trans_code" class="form-label mb-1">Ghi chú</label>
						<textarea class="form-control" name="interest_accrued[{$_oKey}][notes]" placeholder="Viết ghi chú" rows="2">{$interest_accrued.$_oKey.notes}</textarea>
					</div>
					<div class="p-3 bg-lighter rounded-2">
						<div class="d-flex gap-2 align-items-center">
							<input type="hidden" name="interest_accrued[{$_oKey}][is_completed]" value="0" />
							<label class="switch">
								<input type="checkbox" value="1"{if $interest_accrued.$_oKey.is_completed eq '1'} checked{/if} 
									name="interest_accrued[{$_oKey}][is_completed]">
								<span class="slider round"></span>
							</label>
							<span class="text-muted">Hoàn thành {$_oText}</span>
						</div>
					</div>
				</div>
				{/foreach}
			{/if}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" class="btn btn-primary flex-fill" holderG="{$holderG}" billing_id="{$billing_id}" 
				onClick="$Core.global.billing.save_activity(this, event)" uid="{$uid}">Cập nhật</button>
		</div>
	</form>
</div>
{elseif $template_type eq '_add_info'}
<div class="modal-dialog modal-ipad">
	{assign var = toId value = $clsISO->getUniqid()}
	<form class="d-none" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="hid" value="upload" />
		<input type="file" toId="{$toId}" class="upload_file_{$toId}" onChange="$Core.billing.upload_sp_file(this, event)" 
		name="upload_file" billing_id="{$billing_id}" />
	</form>
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cập nhật thông tin căn bán</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="widget-block mb-3">
				<div class="widget-header" onClick="$Core.helper.toggle_block(this, event)">A. Thông tin khách hàng</div>
				<div class="widget-content">
					<div class="form-group form-row mb-2">
						<div class="col-6 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Họ & tên</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class='bx bx-user'></i></span>
								<input type="text" class="form-control" name="customer_name" value="{if !empty($more_information.customer_name)}{$more_information.customer_name}{/if}" placeholder="Họ và tên" />
							</div>
						</div>
						<div class="col-6 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Số điện thoại</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class='bx bx-phone-call'></i></span>
								<input type="text" class="form-control" name="customer_phone" value="{if !empty($more_information.customer_phone)}{$more_information.customer_phone}{/if}" placeholder="+84" />
							</div>
						</div>
						<div class="col-12 col-md-4">
							<label class="form-label mb-1">Email</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class='bx bx-envelope'></i></span>
								<input type="text" class="form-control" name="customer_email" value="{if !empty($more_information.customer_email)}{$more_information.customer_email}{/if}" placeholder="example@gmail.com" />
							</div>
						</div>
					</div>
					<div class="form-group form-row mb-3">
						<div class="col-6 col-md-3 mb-2 mb-lg-0">
							<label class="form-label mb-1">CMT/CCID</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class='bx bx-barcode-reader' ></i></span>
								<input type="text" class="form-control" name="identity_card" value="{if !empty($more_information.identity_card)}{$more_information.identity_card}{/if}" placeholder="CMT/CCID" />
							</div>
						</div>
						<div class="col-6 col-md-3 mb-2 mb-lg-0">
							<label class="form-label mb-1">Ngày cấp</label>
							<input type="datetime" class="form-control datepick" name="issuance_date" value="{if !empty($more_information.issuance_date)}{$more_information.issuance_date}{/if}" placeholder="dd/mm/yyyy" />
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label mb-1">Nơi cấp</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class='bx bx-map'></i></span>
								<input type="text" class="form-control" name="issuance_location" value="{if !empty($more_information.issuance_location)}{$more_information.issuance_location}{/if}" placeholder="Nơi cấp..." />
							</div>
						</div>
					</div>
					<div class="form-group form-row mb-2">
						<div class="col-6 mb-2 mb-lg-0">
							<div class="form-label mb-1 d-flex align-items-center justify-content-between">
								<label class="mb-0">Mặt trước</label>
								<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" onClick="select_sp_file(this,event);" toId="{$toId}" to_field="ccid_front" title="Tải ảnh nên">{$clsISO->makeIcon('bx-upload')}</a>
							</div>
							<div class="cursor-pointer">
								<div id="ccid_front_{$toId}" class="w-100 overflow-hidden d-flex align-items-center justify-content-center border rounded-1 bg-lighter h-px-{if $deviceType eq 'phone'}100{else}175{/if}">
									{if !empty($more_information.ccid_front)}
									<img src="{$clsISO->getGoogleUrl($more_information.ccid_front)}" class="w-100 h-100 rounded-1" />
									{else}
									<div class="text-muted text-center">
										<i class='bx bx-camera'></i> Ảnh mặt trước
									</div>
									{/if}
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="form-label mb-1 d-flex align-items-center justify-content-between">
								<label class="mb-0">Mặt sau</label>
								<a class="cursor-pointer btn btn-icon btn-sm btn-outline-default text-none" onClick="select_sp_file(this,event);" toId="{$toId}" to_field="ccid_back" title="Tải ảnh nên">{$clsISO->makeIcon('bx-upload')}</a>
							</div>
							<div class="cursor-pointer">
								<div id="ccid_back_{$toId}" class="w-100 overflow-hidden border d-flex align-items-center justify-content-center rounded-1 bg-lighter h-px-{if $deviceType eq 'phone'}100{else}175{/if}">
									{if !empty($more_information.ccid_back)}
									<img src="{$clsISO->getGoogleUrl($more_information.ccid_back)}" class="w-100 h-100 rounded-1" />
									{else}
									<div class="text-muted text-center">
										<i class='bx bx-camera' ></i> Ảnh mặt sau
									</div>
									{/if}
								</div>
							</div>
						</div>
					</div>
					<div class="form-group form-row mb-2">
						<div class="col-12 col-md-6 mb-2 mb-lg-0">
							<label class="form-label mb-1">Địa chỉ thường trú 
								<a class="text-muted" title="Nhập giống trong sổ hộ khẩu">
									<i class="bx bx-help-circle fs-11"></i>
								</a>
							</label>
							<div class="input-group input-group-merge mb-1">
								<span class="input-group-text"><i class='bx bx-map'></i></span>
								<input type="text" class="form-control" name="permanent_address" value="{if !empty($more_information.permanent_address)}{$more_information.permanent_address}{/if}" placeholder="Nhập địa chỉ" />
							</div>
							<small class="form-text"></small>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label mb-1">Địa chỉ liên hệ</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class='bx bx-map'></i></span>
								<input type="text" class="form-control" name="contact_address" value="{if !empty($more_information.contact_address)}{$more_information.contact_address}{/if}" placeholder="Nhập địa chỉ" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="widget-block mb-2">
				<div class="widget-header " onClick="$Core.helper.toggle_block(this, event)">B. Thông tin giao dịch</div>
				<div class="widget-content">
					<div class="form-group form-row mb-2">
						<div class="col-6 col-md-3 mb-2 mb-lg-0">
							<label class="form-label mb-1">Phương án TT</label>
							<select class="form-control form-select" name="billing_method">
								<option value="0">Lựa chọn phương án TT</option>
								{$clsProperty->getSelectByProperty('_BILLING_METHOD',$oBilling.billing_method)}
							</select>
						</div>
						<div class="col-6 col-md-3 mb-2 mb-lg-0">
							<label class="form-label mb-1">Bảo lãnh NH</label>
							<select class="form-control form-select" name="bank_guarantee_id">
								<option value="0">Lựa chọn bảo lãnh NH</option>
								{$clsProperty->getSelectByProperty('_BANK_GUARANTEE',$more_information.bank_guarantee_id)}
							</select>
						</div>
						<div class="col-12 col-md-3">
							<label class="form-label mb-1">Ngày ký HĐMB</label>
							<input type="datetime-local" class="form-control" name="contract_date" value="{if !empty($oBilling.contract_date)}{$clsISO->formatDate($oBilling.contract_date,5)}{/if}" placeholder="dd/mm/yyyy" toCls="note_contract" onchange="$Core.billing.toggle_rescheduling_reason(this,event)"/>
						</div>
						<div class="col-3 col-md-3">
							<label for="totalgrand" class="form-label mb-1">Số tiền</label>
							<div class="input-group input-group-merge">
								<input type="text" id="totalgrand" name="totalgrand" class="form-control numberonly price-In required" autocomplete="off" 
									placeholder="0.00" value="{$oBilling.totalgrand}">
								<span class="input-group-text">.đ</span>
							</div>
						</div>
					</div>
					<div class="alert alert-danger">Vui lòng nhập giá tiền chính xác của căn hộ</div>
					 <div class="form-group note_contract d-none mb-2">
						<label class="col-form-label pb-1">Lý do đổi lịch ký</label> 
						<textarea class="form-control" cols="5" name="rescheduling_reason" ></textarea>
					</div>
					<div class="form-group mb-2">
						<div class="d-flex align-items-center justify-content-between form-label">
							<label>Xác nhận cư trú/định danh mức 2</label>
							<a href="javascript:void(0);" toId="{$toId}" p_field="residence_info" p_id="{$billing_id}" onCLick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default text-none">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
						</div>
						<div class="clearfix"></div>
						<div id="residence_info_{$toId}">
							{if !empty($more_information.residence_info)}
								{foreach from=$more_information.residence_info item = _oI}
								<a class="download" data-fancybox="true" href="{$clsISO->getGoogleUrl($_oI.image)}">{$clsISO->makeIcon('bx-download',$_oI.title)}<a/>
								{/foreach}
							{else}
								<span class="text-muted fs-12">Chưa có xác nhận cư chú</span>
							{/if}
						</div>
					</div>
					<div class="form-group mb-2">
						<div class="d-flex align-items-center justify-content-between form-label">
							<label>File CSBH</label>
							<a href="javascript:void(0);" toId="{$toId}" onClick="select_sp_file(this, event);" to_field="sale_policy_file" class="btn btn-sm btn-outline-default text-none">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
						</div>
						<div class="clearfix"></div>
						<div id="sale_policy_file_{$toId}">
							{if !empty($more_information.sale_policy_file)}
								<a class="download" target="_blank" href="{$more_information.sale_policy_file}">{$more_information.sale_policy_file}<a/>
							{else}
								<span class="text-muted fs-12">Chưa có File CSBH</span>
							{/if}
						</div>
					</div>
					<div class="form-group mb-2">
						<div class="d-flex align-items-center justify-content-between form-label">
							<label>File PTG</label>
							<a href="javascript:void(0);" toId="{$toId}" p_field="price_sheet_file" p_id="{$billing_id}" onCLick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default text-none">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
						</div>
						<div class="clearfix"></div>
						<div id="price_sheet_file_{$toId}">
							{if !empty($more_information.price_sheet_file)}
								{foreach from=$more_information.price_sheet_file item = _oI}
								<a class="download" data-fancybox="true" href="{$clsISO->getGoogleUrl($_oI.image)}">{$clsISO->makeIcon('bx-download',$_oI.title)}<a/>
								{/foreach}
							{else}
								<span class="text-muted fs-12">Chưa có File PTG</span>
							{/if}
						</div>
					</div>
					<div class="form-group mb-2">
						<div class="d-flex align-items-center justify-content-between form-label">
							<label>Ủy nhiệm chi</label>
							<a href="javascript:void(0);" toId="{$toId}" p_field="payment_order" p_id="{$billing_id}" onClick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default text-none">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
						</div>
						<div class="clearfix"></div>
						<div class="payment_order_{$toId}">
							{if !empty($more_information.payment_order)}
								{foreach from=$more_information.payment_order item = _oI}
								<a class="download" data-fancybox="true" target="_blank" href="{$clsISO->getGoogleUrl($_oI.image)}">{$clsISO->makeIcon('bx-download', $_oI.title)}<a/>
								{/foreach}
							{else}
								<span class="text-muted fs-12">Ủy nhiệm chi</span>
							{/if}
						</div>
					</div>
					<div class="form-group mb-2">
						<div class="d-flex align-items-center justify-content-between form-label">
							<label>Hợp đồng ký</label>
							<a href="javascript:void(0);" toId="{$toId}" p_field="contract_files" p_id="{$billing_id}" onClick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default text-none">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
						</div>
						<div class="clearfix"></div>
						<div class="contract_{$toId}">
							{if !empty($more_information.contract_files)}
								{foreach from=$more_information.contract_files item = _oI}
								<a class="download" data-fancybox="true" target="_blank" href="{$clsISO->getGoogleUrl($_oI.image)}">{$clsISO->makeIcon('bx-download', $_oI.title)}<a/>
								{/foreach}
							{else}
								<span class="text-muted fs-12">Hợp đồng ký</span>
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary flex-fill js__save-billing" onClick="$Core.billing.save_info(this, event)" uid="{$uid}" is_ignore_confirmed="0" billing_id="{$billing_id}">Cập nhật</button>
		</div>
	</form>
</div>
{else}
<tr>
	<td width="{if $deviceType eq 'phone'}35{else}25{/if}%" class="text-right">Ngày bán</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="date" placeholder="dd/mm/YYYY" class="form-control text-muted form-control-none" onchange="$Core.billing.autosave_inline_field(this, {ldelim}p_field:'sale_date', 'p_id':{$billing_id}{rdelim})" p_id="{$billing_id}" p_field="sale_date" value="{if !empty($more_information.sale_date)}{$more_information.sale_date}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right">CSBH căn bán</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="date" placeholder="dd/mm/YYYY" class="form-control form-control-none" onchange="$Core.billing.autosave_inline_field(this, {ldelim}p_field:'sale_policy_date', 'p_id':{$billing_id}{rdelim})" p_id="{$billing_id}" p_field="sale_policy_date" value="{if !empty($more_information.sale_policy_date)}{$more_information.sale_policy_date}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Dự kiến ký HĐMB</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="datetime-local" placeholder="dd/mm/YYYY" class="form-control form-control-none" onchange="$Core.billing.autosave_inline_field(this, {ldelim}p_field:'estimate_date', 'p_id':{$billing_id}{rdelim})" p_id="{$billing_id}" p_field="estimate_date" value="{if !empty($oneBilling.estimate_date)}{$oneBilling.estimate_date}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Ngày ký HĐMB</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="date" placeholder="dd/mm/YYYY" class="form-control form-control-none" onchange="$Core.billing.autosave_inline_field(this, {ldelim}p_field:'contract_date', 'p_id':{$billing_id}{rdelim})" p_id="{$billing_id}" p_field="contract_date" value="{if !empty($oneBilling.contract_date)}{$oneBilling.contract_date}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Khách hàng</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" placeholder="Nhập..." class="form-control form-control-none" onchange="$Core.billing.autosave_inline_field(this, {ldelim}p_field:'customer_name', 'p_id':{$billing_id}{rdelim})" p_id="{$billing_id}" p_field="customer_name" value="{if !empty($more_information.customer_name)}{$more_information.customer_name}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Điện thoại</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'customer_phone', 'p_id':{$billing_id}{rdelim})" placeholder="Nhập..." class="form-control form-control-none" p_id="{$billing_id}" p_field="customer_phone" value="{if !empty($more_information.customer_phone)}{$more_information.customer_phone}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Email</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'customer_email', 'p_id':{$billing_id}{rdelim})" placeholder="Nhập..." class="form-control form-control-none" p_id="{$billing_id}" p_field="customer_email" value="{if !empty($more_information.customer_email)}{$more_information.customer_email}{/if}" />
	</td>
</tr>
{if $deviceType eq 'phone'}
<tr>
	<td class="text-right text-nowrap">CMND/CCID<br /><small>(Mặt trước)</small></td>
	<td class="InputCRMHandler" colspan="3">
		{if !empty($more_information.ccid_front)}
			<a class="download" target="_blank" href="{$more_information.ccid_front}">{$clsISO->formatFileName($more_information.ccid_front,15)}<a/>
		{else}
			<span class="text-muted fs-12">Chưa có CMND/CCID</span>
		{/if}
		<div class="clearfix"></div>
		{assign var = toId value = $clsISO->getUniqid()}
		<form class="d-none" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="hid" value="upload" />
			<input type="file" class="upload_file_{$toId} upload_sp_file" to_field="ccid_front" name="upload_file" billing_id="{$billing_id}" />
		</form>
		<a href="javascript:void(0);" toId="{$toId}" onClick="select_sp_file(this, event);" to_field="ccid_front" class="btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
	</td>
</tr>
<tr>
	<td class="text-right">CMND/CCID<br /><small>(Mặt sau)</small></td>
	<td class="InputCRMHandler" colspan="3">
		{if !empty($more_information.ccid_back)}
			<a class="download" target="_blank" href="{$more_information.ccid_back}">{$clsISO->formatFileName($more_information.ccid_back,15)}<a/>
		{else}
			<span class="text-muted fs-12">Chưa có CMND/CCID</span>
		{/if}
		<div class="clearfix"></div>
		{assign var = toId value = $clsISO->getUniqid()}
		<form class="d-none" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="hid" value="upload" />
			<input type="file" class="upload_file_{$toId} upload_sp_file" to_field="ccid_back" name="upload_file" billing_id="{$billing_id}" />
		</form>
		<a href="javascript:void(0);" toId="{$toId}" onClick="select_sp_file(this, event);" to_field="ccid_back" class="btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
	</td>
</tr>
{else}
<tr>
	<td class="text-right">CMND/CCID<br /><small>(Mặt trước)</small></td>
	<td class="InputCRMHandler border-end">
		{if !empty($more_information.ccid_front)}
			<a class="download" target="_blank" href="{$more_information.ccid_front}">{$clsISO->formatFileName($more_information.ccid_front,15)}<a/>
		{else}
			<span class="text-muted fs-12">Chưa có CMND/CCID</span>
		{/if}
		<div class="clearfix"></div>
		{assign var = toId value = $clsISO->getUniqid()}
		<form class="d-none" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="hid" value="upload" />
			<input type="file" class="upload_file_{$toId} upload_sp_file" to_field="ccid_front" name="upload_file" billing_id="{$billing_id}" />
		</form>
		<a href="javascript:void(0);" toId="{$toId}" onClick="select_sp_file(this, event);" to_field="ccid_front" class="btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
	</td>
	<td class="text-right">CMND/CCID<br /><small>(Mặt sau)</small></td>
	<td class="InputCRMHandler">
		{if !empty($more_information.ccid_back)}
			<a class="download" target="_blank" href="{$more_information.ccid_back}">{$clsISO->formatFileName($more_information.ccid_back,15)}<a/>
		{else}
			<span class="text-muted fs-12">Chưa có CMND/CCID</span>
		{/if}
		<div class="clearfix"></div>
		{assign var = toId value = $clsISO->getUniqid()}
		<form class="d-none" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="hid" value="upload" />
			<input type="file" class="upload_file_{$toId} upload_sp_file" to_field="ccid_back" name="upload_file" billing_id="{$billing_id}" />
		</form>
		<a href="javascript:void(0);" toId="{$toId}" onClick="select_sp_file(this, event);" to_field="ccid_back" class="btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
	</td>
</tr>
{/if}
<tr>
	<td class="text-right text-nowrap">File CSBH</td>
	<td class="InputCRMHandler" colspan="3">
		{if !empty($more_information.sale_policy_file)}
			<a class="download" target="_blank" href="{$more_information.sale_policy_file}">{$more_information.sale_policy_file}<a/>
		{else}
			<span class="text-muted fs-12">Chưa có File CSBH</span>
		{/if}
		<div class="clearfix"></div>
		{assign var = toId value = $clsISO->getUniqid()}
		<form class="d-none" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="hid" value="upload" />
			<input type="file" class="upload_file_{$toId} upload_sp_file" to_field="sale_policy_file" name="upload_file" billing_id="{$billing_id}" />
		</form>
		<a href="javascript:void(0);" toId="{$toId}" onClick="select_sp_file(this, event);" to_field="sale_policy_file" class="btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-upload', 'Tải file')}</a>
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">File PTG</td>
	<td colspan="3">
		{if !empty($more_information.price_sheet_file)}
			{foreach from=$more_information.price_sheet_file item = _oI}
			<a class="download" data-fancybox="true" href="{$clsISO->getGoogleUrl($_oI.image)}">{$clsISO->makeIcon('bx-download',$_oI.title)}<a/>
			{/foreach}
		{else}
			<span class="text-muted fs-12">Chưa có File PTG</span>
		{/if}
		<div class="clearfix"></div>
		<a href="javascript:void(0);" p_field="price_sheet_file" p_id="{$billing_id}" onClick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-check', 'Tải file')}</a>
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Ủy nhiệm chi</td>
	<td colspan="3">
		{if !empty($more_information.payment_order)}
			{foreach from=$more_information.payment_order item = _oI}
			<a class="download" data-fancybox="true" target="_blank" href="{$clsISO->getGoogleUrl($_oI.image)}">{$clsISO->makeIcon('bx-download', $_oI.title)}<a/>
			{/foreach}
		{else}
			<span class="text-muted fs-12">Ủy nhiệm chi</span>
		{/if}
		<div class="clearfix"></div>
		<a href="javascript:void(0);" p_field="payment_order" p_id="{$billing_id}" onCLick="$Core.billing.open_sp_file(this, event)" class="btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-check', 'Tải file')}</a>
	</td>
</tr>
<tr>
	<td colspan="4" class="bg-lighter fw-bold text-upper">A. Phần HHMG nhận về</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Nguồn căn</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<div class="selectize-none">
			<select class="iso-selectizeNotSearch w-100" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'stock_resource', 'p_id':{$billing_id}{rdelim})" placeholder="Nguồn gốc" p_id="{$billing_id}" p_field="stock_resource" data-url="{$PCMS_URL}/index.php?mod=ajax&sub=helper&act=get_property&property_type=_AGENCY" name="stock_resource" data-optgroup="false">
				{if isset($more_information.stock_resource) && $more_information.stock_resource gt '0'}
				<option value="{$more_information.stock_resource}" selected="selected">{$clsProperty->getTitle($more_information.stock_resource)}</option>
				{/if}
			</select>
		</div>
		<!-- <input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'stock_resource', 'p_id':{$billing_id}{rdelim})" placeholder="Nhập..." class="form-control form-control-none" p_id="{$billing_id}" p_field="stock_resource" value="{if !empty($more_information.stock_resource)}{$more_information.stock_resource}{/if}" /> -->
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Hoa hồng</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'commission', 'p_id':{$billing_id}{rdelim})" class="form-control form-control-none" placeholder="Nhập..." p_id="{$billing_id}" p_field="commission" value="{if !empty($more_information.commission)}{$more_information.commission}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Thưởng đại lý</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'agency_bonus', 'p_id':{$billing_id}{rdelim})" placeholder="Nhập..." class="form-control form-control-none" p_id="{$billing_id}" p_field="agency_bonus" value="{if !empty($more_information.agency_bonus)}{$more_information.agency_bonus}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Thưởng Marketing</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'marketing_bonus', 'p_id':{$billing_id}{rdelim})" placeholder="Nhập..." class="form-control form-control-none" p_id="{$billing_id}" p_field="marketing_bonus" value="{if !empty($more_information.marketing_bonus)}{$more_information.marketing_bonus}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Thưởng sale</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'sale_ps_bonus', 'p_id':{$billing_id}{rdelim})" placeholder="Nhập..." class="form-control form-control-none" p_id="{$billing_id}" p_field="sale_ps_bonus" value="{if !empty($more_information.sale_ps_bonus)}{$more_information.sale_ps_bonus}{/if}" />
	</td>
</tr>
<tr>
	<td colspan="4" class="bg-lighter fw-bold text-upper">B. Phần HHMG phải trả (HH=Hoa hồng)</td>
</tr>
<tr>
	<td class="text-right text-nowrap">HH PKD/Đối tác</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'commission_agency', 'p_id':{$billing_id}{rdelim})" placeholder="Nhập..." class="form-control form-control-none" p_id="{$billing_id}" p_field="commission_agency" value="{if !empty($more_information.commission_agency)}{$more_information.commission_agency}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">HH sale</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'commission_sale', 'p_id':{$billing_id}{rdelim})" placeholder="Nhập..." class="form-control form-control-none" p_id="{$billing_id}" p_field="commission_sale" value="{if !empty($more_information.commission_sale)}{$more_information.commission_sale}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Thưởng sale</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'sale_bonus', 'p_id':{$billing_id}{rdelim})" class="form-control form-control-none" placeholder="Nhập..." p_id="{$billing_id}" p_field="sale_bonus" value="{if !empty($more_information.sale_bonus)}{$more_information.sale_bonus}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Hỗ trợ sale(Nếu có)</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<input type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'support_sale', 'p_id':{$billing_id}{rdelim})" placeholder="Nhập..." class="form-control form-control-none" p_id="{$billing_id}" p_field="support_sale" value="{if !empty($more_information.support_sale)}{$more_information.support_sale}{/if}" />
	</td>
</tr>
<tr>
	<td class="text-right text-nowrap">Ghi chú</td>
	<td class="InputCRMHandler p-px-1" colspan="3">
		<textarea type="text" onchange="$Core.billing.autosave_inline_field(this,{ldelim}p_field:'notes', 'p_id':{$billing_id}{rdelim})" class="form-control form-control-none" placeholder="Nhập..." p_id="{$billing_id}" p_field="notes">{if !empty($more_information.notes)}{$more_information.notes}{/if}</textarea>
	</td>
</tr>
{/if}