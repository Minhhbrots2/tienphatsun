{if $type eq 'BILLING'}
	{if !empty($list_billings)}
		{foreach name=i from=$list_billings item = _oBilling}
			{assign var = billing_id value = $_oBilling.billing_id}
			{assign var = more_information value = $_oBilling.more_information}
			<tr>
				<td class="align-center" data-label="STT">{$smarty.foreach.i.iteration}</td>
				<td data-label="Mã căn">
					{if $permiss_add_info eq '1'}
						{if !empty($more_information.quick_notes)}
						<i class='bx bx-check-circle text-success'></i>
						{else}
						<i class='bx bx-error text-muted'></i>
						{/if}
					{/if}
					{$_oBilling.stock_code}
					{if $permiss_edit eq '1'}
						{$_oBilling.billing_source}
						{if $_oBilling.is_alliance eq '1'}
						<span class="label d-inline-block bg-success" style="font-size:65%; transform:translateY(-2px)">LM</span>
						{/if}
					{/if}
				</td>
				<td data-label="Ngày cọc">
					{$clsISO->formatDate($_oBilling.deposit_date,3)}</td>
				<td data-label="Ngày cọc">
					<span class="estimate_date_{$billing_id}">
						{if !empty($_oBilling.estimate_date)}
							{$clsISO->formatDate($_oBilling.estimate_date,3)}
						{else}
							---
						{/if}
					</span>
				</td>
				<td data-label="Ngày ký HĐMB">
					<span class="contract_date_{$billing_id}">
						{if !empty($_oBilling.contract_date)}
							{$clsISO->formatDate($_oBilling.contract_date,3)}
						{else}
							-- 
						{/if}
					</span>
				</td>
				<td data-label="Trạng thái">
					{$_oBilling.text_contract_status}
				</td>
				<td data-label="H.Động" class="text-center">
					<div class="dropdown">
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
							data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" onClick="view_billing(this,event)" billing_id="{$_oBilling.billing_id}" 
							href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
							<hr size="0" class="dropdown-divider" />
							<a class="dropdown-item" onClick="$Core.billing.add_info(this,event)" billing_id="{$_oBilling.billing_id}" href="javascript:void(0);"><i class="bx bx-info-circle me-1"></i> Thông tin</a>
							{if $permiss_view eq '1'}{/if}
							{if $permiss_edit eq '1'}
							<a class="dropdown-item" tp="quick" onClick="open_billing(this,event)" billing_id="{$_oBilling.billing_id}" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
							{/if}
							{if $permiss_cancel eq '1'}
							<a class="dropdown-item{if $_oBilling.is_cancel eq '1'} disabled{/if}" href="javascript:void(0);" onClick="cancel_billing(this,event)" billing_id="{$_oBilling.billing_id}"><i class="bx bx-no-entry me-1"></i> Hủy</a>
							{/if}
							{if $permiss_delete eq '1'}
							<a class="dropdown-item" href="javascript:void(0);" onClick="delete_billing(this,event)" billing_id="{$_oBilling.billing_id}"><i class="bx bx-trash me-1"></i> Xóa</a>
							{/if}
						</div>
					</div>
				</td>
			</tr>
		{/foreach}
	{else}
		<tr>
			<td class="text-center" colspan="11">
				Không có giao dịch nào !
			</td>
		</tr>
	{/if}
{else if $type eq "STOCK"}
	{if !empty($list_stocks)}
		{foreach name=i from=$list_stocks item = _oStock}
			{assign var = stock_id value = $_oStock.stock_id}
			{assign var = more_information value = $_oStock.more_information}
			<tr>
				<td class="align-center" data-label="STT">{$smarty.foreach.i.iteration}</td>
				<td data-label="Mã căn">
					{$_oStock.ms_code}
				</td>
				<td data-label="Dự án">{$_oStock.project_name}</td>
				<td data-label="Phân khu">{$_oStock.block_name}</td>
				<td data-label="Toà">{$_oStock.building_name}</td>
				<td data-label="Giá">{$clsISO->shortNumber($_oStock.total_price_vat,1)}</td>
				<td data-label="Trạng thái">
					{$_oStock.status}
				</td>
			</tr>
		{/foreach}
	{else}
		<tr>
			<td class="text-center" colspan="11">
				Không có căn nào !
			</td>
		</tr>
	{/if}
{/if}