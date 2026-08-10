{if !empty($lstCustomer)}
	{foreach from=$lstCustomer item=_oItem}
		<div class="d-flex justify-content-between align-items-center mb-6">
			<div>
				<h6 class="mb-0 text-truncate">{$_oItem.name}</h6>
				<div class="d-flex align-items-center">
					<small class="text-truncate text-body">17/01/2025</small>
					<small class="text-truncate text-body">30 ngày</small>
				</div>
			</div>
			<div class="btn-group">
				<a href="javascript:void(0);" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default view_customer" onclick="$Core.crm.open_customer(this,event)" customer_id="{$_oItem.customer_id}" route="/customer/{$_oItem.customer_id}/overview"><i class='bx bx-edit-alt' ></i></a>
				<a href="javascript:void(0);" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.set_archived(this, event);" customer_id="{$_oItem.customer_id}">{$_oItem.icon_archived}</a>
				<a href="javascript:void(0);" title="Follow-ups" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}"><i class="bx bx-bell"></i></a>
				<a href="javascript:void(0);" title="Follow-ups" onClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}" class="btn btn-icon btn-sm btn-outline-default text-main">{$_oItem.total_followups_next}</a>
			</div>
		</div>
	{/foreach}
{/if}