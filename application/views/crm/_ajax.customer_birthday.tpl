<table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th class="align-center">Khách hàng</th>
			<th class="align-center">Ngày sinh</th>
			<th class="align-center text-center">Tuổi</th>
			<th class="align-center text-right">Hành động</th>
		</tr>
	</thead>
	<tbody class="table-border-bottom-0">
		{if !empty($lstCustomer)}
			{foreach from=$lstCustomer item=_oItem}
				{assign var="data_html" value=$_oItem.data_html}
				<tr class="pointer-event" onDblClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}" class="trCustomer">
					<td class="text-left">
						{$_oItem.name}
					</td>
					<td class="text-left">{$_oItem.birthday}</td>
					<td class="text-center">
						<div class="d-flex align-items-center gap-1">
							<img src="{$URL_IMAGES}/birthday.png" alt="" width="20" height="20">
							<span class="">{$_oItem.age} tuổi</span>
						</div>
					</td>
					<td width="60px" class="text-center">
						<div class="btn-group">
							<a href="javascript:void(0);" data-bs-toggle="tooltip" title="Chỉnh sửa" class="btn btn-icon btn-sm btn-outline-default view_customer" onclick="$Core.crm.open_customer(this,event)" customer_id="{$_oItem.customer_id}" route="/customer/{$_oItem.customer_id}/overview"><i class='bx bx-edit-alt' ></i></a>
							<a href="javascript:void(0);" data-bs-toggle="tooltip" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.set_archived(this, event);" customer_id="{$_oItem.customer_id}">{$_oItem.icon_archived}</a>
							<a href="javascript:void(0);" data-bs-toggle="tooltip" title="Follow-ups" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}"><i class="bx bx-bell"></i></a>
							<a href="javascript:void(0);" data-bs-toggle="tooltip" title="Tổng số Follow-ups" onClick="$Core.crm.view_activity(this, event);" customer_id="{$_oItem.customer_id}" class="btn btn-icon btn-sm btn-outline-default text-main">{$_oItem.total_followups_next}</a>
						</div>
					</td>
				</tr>
			{/foreach}
		{else}
			<tr>
				<td colspan="10">{$empty}</td>
			</tr>
		{/if}
	</tbody>
</table>