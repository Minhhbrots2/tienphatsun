<div class="modal-dialog modal-xl">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">
				{if $action eq '_add'}Thêm{else}Chỉnh sửa{/if} xác nhận hoa hồng<br />
				<span class="text-danger fs-13">
					{$clsISO->makeIcon('bx-user-plus', 'Người tạo: ')}
					{$clsProfile->getFullName($profile_id, $oneProfile)}
				</span>
			</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if !empty($list_transaction_type)}
			<div class="form-group d-flex gap-2 mb-3 align-items-center">
				<label class="col-form-label">Loại giao dịch</label>
				<div class="btn-group d-flex gap-2" role="group">
					{foreach name=i from=$list_transaction_type item = _oItem}
					<label class="we-radio" >
						<input type="radio"{if $oneTransaction.transaction_type eq $_oItem.property_id} checked{/if} 
						name="transaction_type" value="{$_oItem.property_id}">
						<span>{$_oItem.title}</span>
					</label>
					{/foreach}
				</div>
			</div>
			{/if}
			<div class="form-group form-row mb-3">
				<div class="col-6 col-lg-2 mb-2 mb-lg-0">
					<label class="form-label mb-1">Mã xác nhận</label>
					<input type="text" class="form-control required" name="code" maxlength="255" value="{if $action eq '_edit'}{$oneTransaction.code}{else}{$clsTransaction->genCode()}{/if}" />
				</div>
				<div class="col-6 col-lg-2 mb-2 mb-lg-0">
					<label class="form-label mb-1">Tháng gửi</label>
					<input type="month" class="form-control required" onchange="$Core.global.transaction.handle_month(this, event)" 
					name="send_month" placeholder="mm/yyyy" value="{$oneTransaction.send_date|date_format:'%Y-%m'}" />
				</div>
				<div class="col-6 col-lg-3">
					<label class="form-label mb-1">Tài khoản NH</label>
					<select name="bank_id" class="form-control form-select">
						{$html_banks}
					</select>
				</div>
				<div class="col-6 col-lg-3">
					<label class="form-label mb-1">Dự án</label>
					<div class="clearfix"></div>
					<select class="form-control iso-select2" name="project_id" onchange="$Core.global.transaction.handle_project(this, event)" 
					data-width="100%" data-allow-clear="true">
						{foreach from=$list_trans_projects item = _oProject}
						<option{if $oneTransaction.project_id eq $_oProject.property_id} selected{/if} value="{$_oProject.property_id}">{$_oProject.title}</option>
						{/foreach}
					</select>
				</div>
				<div class="col-6 col-lg-2">
					<label class="form-label mb-1">Tình trạng</label>
					<select type="text" disabled class="form-control form-select" name="status_id">
						{$clsProperty->getSelectByProperty('_TRANSACTION_STATUS', $smarty.const._TRANSACTION_STATUS_NEW)}
					</select>
				</div>
			</div>
			<hr class="my-3" />
			<div class="mb-2">
				<label class="form-label mb-1">Danh sách giao dịch</label>
				<div class="table-responsive freeze-table dragscroll text-nowrap">
					<table class="table table-responsive table-bordered table-transaction mb-1" style="width:calc(100% + 250px)">
						<thead><tr>
							<th class="text-center bg-lighter" rowspan="3" width="3%">STT</th>
							<th width="125px" class="text-center bg-lighter" rowspan="3">Giao dịch</th>
							<th width="100px" class="text-center bg-lighter" rowspan="3">Mã căn</th>
							<th width="100px" class="text-center bg-lighter" rowspan="3">Dự án</th>
							<th width="130px" class="text-center bg-lighter" rowspan="3">Ngày kí<br />HĐMB</th>
							<th width="120px" class="text-center bg-lighter" rowspan="3">Gía trị<br />HĐMB</th>
							<th class="text-center bg-lightest" colspan="4">Thông tin hoa hồng</th>
							<th width="150px" class="text-center bg-lighter" rowspan="3">Ghi chú</th>
							<th width="40px" class="text-center bg-lighter" rowspan="3"></th>
						</tr>
						<tr>
							<th width="80px" class="text-center bg-lighter" rowspan="2">Tỷ lệ<br />HH(%)</th>
							<th class="text-center bg-lighter" colspan="2">Thưởng</th>
							<th width="100px" class="text-center bg-lighter" rowspan="2">Hỗ trợ<br />(Nếu có)</th>
						</tr>
						<tr>
							<th width="120px" class="text-center bg-lighter">Đại lý</th>
							<th width="120px" class="text-center bg-lighter">CĐT</th>
						</tr></thead>
						<tbody>
							{if !empty($billing_store)}
								{foreach name=i from = $billing_store key= _uid item = _obilling}
								<tr class="{$_uid} transaction_item_{$transaction_id}">
									<td class="p-0 text-center">1</td>
									<td class="p-0 w-px-125">
										<input type="hidden" class="billing_id" name="billing_store[{$_uid}][billing_id]" value="{$_obilling.billing_id}" /> 
										<input type="hidden" class="billing_code" name="billing_store[{$_uid}][billing_code]" value="{$_obilling.billing_code}" /> 
										<select uid="{$_uid}" prompt="Giao dịch" class="cboProduct" id="cboProduct_{$_uid}" 
										data-options="panelWidth:900,
											url:'/index.php?mod=transaction&act=search_billing&uid={$_uid}&staff_id={$profile_id}',
											mode:'remote',
											method:'post',
											height:'34px',
											width:'130px',
											multiple:false, 
											value:'{$_obilling.billing_code}',
											columns: [[
												{ldelim}field:'billing_code',title:'Mã GD',width:70,align:'left'{rdelim},
												{ldelim}field:'deposit_date',title:'Ngày cọc',width:85,align:'left'{rdelim},
												{ldelim}field:'staff_name',title:'Nhân viên',width:120,align:'left'{rdelim},
												{ldelim}field:'stock_code',title:'Mã căn',width:80{rdelim},
												{ldelim}field:'project_name',title:'Dự án',width:120,align:'left'{rdelim},
												{ldelim}field:'totalgrand',title:'Tồng tiền',width:80,align:'left'{rdelim}
											]],
											idField:'billing_code',
											textField:'billing_code',
											fitColumns:true,
											showFooter:true,
											pagination:true,
											pageSize: 20">
										</select>
									</td>
									<td class="p-0"><input type="text" class="form-control no-focus stock_code" 
										name="billing_store[{$_uid}][stock_code]" value="{$_obilling.stock_code}" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus project_name" 
										name="billing_store[{$_uid}][project_name]" value="{$_obilling.project_name}" placeholder="Dự án..." /></td>
									<td class="p-0"><input type="date" placeholder="dd/mm/yy" name="billing_store[{$_uid}][contract_date]" 
										class="form-control datepicker no-focus contract_date" value="{if !empty($_obilling.contract_date)}{$_obilling.contract_date}{/if}" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In total_price" 
										placeholder="0.00đ" name="billing_store[{$_uid}][total_price]" value="{$_obilling.total_price}" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly commission" 
										name="billing_store[{$_uid}][commission]" placeholder="0%" value="{$_obilling.commission}" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ms" 
										placeholder="0.00đ" name="billing_store[{$_uid}][price_ms]" value="{$_obilling.price_ms}" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ns" 
										placeholder="0.00đ" name="billing_store[{$_uid}][price_ns]" value="{$_obilling.price_ns}" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_sp" 
										placeholder="0.00đ" name="billing_store[{$_uid}][price_sp]" value="{$_obilling.price_sp}" /></td>
									<td class="p-0"><input type="text" placeholder="Viết ghi chú..." class="form-control notes" 
										name="billing_store[{$_uid}][notes]" value="{$_obilling.notes}" /></td>
									<td class="text-center">
										{if $smarty.foreach.i.first}
										<!-- Non -->
										{else}
										<a href="javascript:void(0);" onClick="$Core.helper.deleteline(this, vent)">
											{$clsISO->makeIcon('bx-trash')}
										</a>
										{/if}
									</td>
								</tr>
								{/foreach}
							{else}
								{if $openFrom eq '_billing'}
									{$clsTransaction->get_row_billing($uid, $transaction_id, $billing_id, $oneBilling)}
								{else}
								<tr class="{$uid} transaction_item_{$transaction_id}">
									<td class="p-0 text-center">1</td>
									<td class="p-0 w-px-125">
										<input type="hidden" class="billing_id" name="billing_store[{$uid}][billing_id]" /> 
										<input type="hidden" class="billing_code" name="billing_store[{$uid}][billing_code]" /> 
										<select prompt="Giao dịch" uid="{$uid}" class="cboProduct" id="cboProduct_{$uid}" data-options="panelWidth:900,
											url:'/index.php?mod=transaction&act=search_billing&uid={$uid}&staff_id={$profile_id}',
											mode:'remote',
											method:'post',
											height:'34px',
											width:'130px',
											multiple:false,
											columns: [[
												{ldelim}field:'billing_code',title:'Mã GD',width:70,align:'left'{rdelim},
												{ldelim}field:'deposit_date',title:'Ngày cọc',width:85,align:'left'{rdelim},
												{ldelim}field:'staff_name',title:'Nhân viên',width:120,align:'left'{rdelim},
												{ldelim}field:'stock_code',title:'Mã căn',width:80{rdelim},
												{ldelim}field:'project_name',title:'Dự án',width:120,align:'left'{rdelim},
												{ldelim}field:'totalgrand',title:'Tồng tiền',width:80,align:'left'{rdelim}
											]],
											idField:'billing_code',
											textField:'billing_code',
											fitColumns:true,
											showFooter:true,
											pagination:true,
											pageSize: 20">
										</select>
									</td>
									<td class="p-0"><input type="text" name="billing_store[{$uid}][stock_code]" placeholder="Mã căn" 
										class="form-control no-focus stock_code" /></td>
									<td class="p-0"><input type="text" name="billing_store[{$uid}][project_name]" 
										class="form-control no-focus project_name" placeholder="Dự án" /></td>
									<td class="p-0"><input type="date" placeholder="dd/mm/yy" name="billing_store[{$uid}][contract_date]" 
										class="form-control no-focus contract_date" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In total_price" 
										placeholder="0.00đ" name="billing_store[{$uid}][total_price]" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly commission" 
										name="billing_store[{$uid}][commission]" placeholder="0%" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ms" 
										placeholder="0.00đ" name="billing_store[{$uid}][price_ms]" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ns" 
										placeholder="0.00đ" name="billing_store[{$uid}][price_ns]" /></td>
									<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ms" 
										placeholder="0.00đ" name="billing_store[{$uid}][price_ms]" /></td>
									<td class="p-0"><input type="text" placeholder="Viết ghi chú..." class="form-control no-focus notes" 
										name="billing_store[{$uid}][notes]" /></td>
									<td class="text-center"></td>
								</tr>
								{/if}
							{/if}
						</tbody>
						<tfoot><tr>
							<td class="text-left" colspan="12">
								<a href="javascript:void(0);" onClick="$Core.global.transaction.add_line(this, event)" 
								class="text-link" transaction_id="{$transaction_id}">+ Thêm dòng</a>
							</td>
						</tr></tfoot>
					</table>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<label class="col-form-label">Ghi chú</label>
				<div class="clearfix"></div>
				<textarea class="form-control" name="notes" cols="255" rows="2" placeholder="Viết ghi chú">{if $action eq '_edit'}{$more_information.notes}{/if}</textarea>
			</div>
			<div class="form-group mb-2">
				<label class="col-form-label">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
					{if !empty($list_attachments)}
						{foreach name=i from = $list_attachments item = _oFile}
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
			<button type="button" class="btn btn-outline-secondary close_pop" data-bs-dismiss="modal">Đóng</button>
			<button type="button" transaction_id="{$transaction_id}" openFrom="{$openFrom}" onClick="$Core.global.transaction.pop_save_transaction(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	.textbox,
	.table-transaction .form-control {
		margin:1px;
		border-radius: 0;
		-moz-border-radius: 0;
		-webkit-border-radius: 0;
		-khtml-border-radius: 0;
		border-style: dotted;
		border-width: 0 0 0 0;
	}
	.selectize-input{
		padding-top:7px;
		padding-bottom:7px;
		border-radius:4px;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		-khtml-border-radius:4px;
	}
</style>
{/literal}