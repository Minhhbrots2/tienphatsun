<div class="modal-dialog modal-dialog-centered modal-ipad">
	{assign var = toId value = $clsISO->getUniqid()}
	<form class="d-none" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="hid" value="upload" />
		<input type="hidden" name="billing_code" value="{$oneItem.billing_code}" />
		<input type="file" toId="{$toId}" class="upload_file_{$toId}" onChange="$Core.crm.upload_sp_file(this, event)" 
		name="upload_file" billing_id="{$billing_id}" />
	</form>
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">
				{if $action eq '_add'}Thêm{else}Chỉnh sửa{/if} giao dịch</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Mã GD</label>
					<input type="text" id="trans_code" name="billing_code" class="form-control required" 
						placeholder="Mã GD" value="{$oneItem.billing_code}">
				</div>
				<div class="col-6">
					<label for="deposit_date" class="form-label mb-1">Ngày cọc</label>
					<input type="date" id="deposit_date" name="deposit_date" class="form-control required" 
					placeholder="dd/mm/yy" value="{if !empty($oneItem.deposit_date)}{$oneItem.deposit_date|date_format:'%Y-%m-%d'}{/if}">
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Loại hình</label>
					{assign var = uid value = $clsISO->getUniqid()}
					<select id="{$uid}" class="form-control form-select required" name="billing_type">
						{$clsProperty->getSelectByProperty('_BILLING_TYPE',$oneItem.billing_type)}
					</select>
				</div>
				<div class="col-6">
					<label class="form-label mb-1">Tình trạng KÝ HĐMB</label>
					<select class="form-control form-select required" name="contract_status_id">
						{$clsProperty->getSelectByProperty('_STATUS_CONTRACT', $oneItem.contract_status_id)}
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-md-6 mb-2 mb-lg-0">
					<label class="form-label mb-1">Dự án</label>
					<div class="clearfix"></div>
					<select placeholder="Chọn dự án" class="iso-selectizeNotSearch w-100" data-url="{$PCMS_URL}/index.php?mod=home&act=list_project" name="project_id" data-optgroup="false">
						{if !empty($oneItem.project_id)}
						<option value="{$oneItem.project_id}" selected="selected">
							{$clsProject->getTitle($oneItem.project_id)}
						</option>
						{/if}
					</select>
				</div>
				<div class="col-6 col-md-3">
					<label id="product_code" class="form-label mb-1">Mã Căn</label>
					<div class="clearfix"></div>
					<input type="text" name="stock_code" autocomplete="off" placeholder="S1.01XXXX" class="form-control required" value="{if !empty($oneItem.stock_code)}{$oneItem.stock_code}{/if}" />
				</div>
				<div class="col-6 col-md-3">
					<label for="totalgrand" class="form-label mb-1">Số tiền</label>
					<div class="input-group input-group-merge">
						<input type="text" id="totalgrand" name="totalgrand" class="form-control numberonly price-In required" autocomplete="off" placeholder="0.00" value="{if !empty($oneItem.totalgrand)}{$oneItem.totalgrand}{/if}">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="totalgrand" class="form-label mb-1">Ghi chú</label>
				<textarea class="form-control" placeholder="Viết ghi chú" name="staff_notes" rows="3">{if !empty($more_information.staff_notes)}{$more_information.staff_notes}{/if}</textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" openFrom="{$openFrom}" billing_id="{$billing_id}" 
				onClick="$Core.crm.save_billing(this, event)" class="btn btn-primary" 
				gId="{$gId}" customer_id="{$customer_id}">Lưu lại</button>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	input[name=ms_date]{ width:100px !important;}
	.selectize-dropdown{ z-index:3 !important}
</style>
{/literal}
