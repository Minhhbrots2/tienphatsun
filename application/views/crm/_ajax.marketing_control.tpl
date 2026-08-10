{* Marketing control-view — "Khách tôi đẩy". Render bởi default_load_marketing_control(). *}
<div class="crm-marketing-control">
	<div class="d-flex align-items-center gap-2 alert-message bg-label-info p-2 rounded-2 mb-2">
		<div class="w-px-30 p-2"><i class="fa fa-bullhorn text-fs-20"></i></div>
		<div class="d-flex flex-column">
			<h3 class="mb-0 text-fs-14">Khách tôi đẩy <span class="badge bg-info ms-1">{$mc_total}</span></h3>
			<small>Xếp theo mức độ bê trễ — chưa chạm &amp; lâu chưa liên hệ lên đầu</small>
		</div>
	</div>
	{if $lst}
	<table class="table table-no-border-end table-middle mb-0" width="100%">
		<thead>
			<tr class="fs-12 text-muted">
				<th class="text-left">Khách</th>
				<th>SĐT</th>
				<th>Sale đang ôm</th>
				<th>Ngày tạo</th>
				<th>Liên hệ cuối</th>
				<th>Phân loại</th>
				<th class="text-end">Bê trễ</th>
			</tr>
		</thead>
		{foreach from=$lst item=_o}
		<tr customer_id="{$_o.customer_id}">
			<td class="text-left">
				<a href="javascript:void(0);" class="link goLink font-bold view_customer" onClick="$Core.crm.open_customer(this,event)" route="/customer/{$_o.customer_id}/overview" customer_id="{$_o.customer_id}">{$_o.name|escape}</a>
			</td>
			<td class="text-nowrap">{if $_o.phone}<a href="tel:{$_o.phone|escape}" class="text-body fs-12">{$_o.phone_mask|escape}</a>{else}---{/if}</td>
			<td class="text-nowrap">
				<a href="javascript:void(0);" onClick="$Core.crm.change_assigned(this, event)" customer_id="{$_o.customer_id}" title="Phân lại / thu hồi Sale" class="d-inline-flex align-items-center text-body text-decoration-none">
					<img class="avatar avatar-xs rounded-pill me-1" src="{$_o.sale_avatar}">
					<span class="fs-12{if $_o.is_undistributed} text-warning fw-bold{/if}">{$_o.sale_name|escape}</span>
					<i class="bx bx-transfer-alt ms-1 text-muted"></i>
				</a>
			</td>
			<td class="text-nowrap fs-12 text-muted">{$_o.created_text}</td>
			<td class="text-nowrap fs-12">{$_o.last_contact}</td>
			<td>{if $_o.status_title}<span class="badge" style="background:{$_o.status_bg|escape};color:{$_o.status_color|escape}">{$_o.status_title|escape}</span>{/if}</td>
			<td class="text-end text-nowrap">
				{if $_o.fu_count == 0}<span class="badge bg-label-danger">Chưa chạm</span>
				{else}<span class="fs-12{if $_o.days_idle >= 7} text-danger fw-bold{elseif $_o.days_idle >= 3} text-warning{/if}">{$_o.days_idle} ngày</span>{/if}
				{if $_o.overdue > 0} <span class="badge bg-label-warning" title="Follow-up quá hạn">{$_o.overdue} quá hạn</span>{/if}
			</td>
		</tr>
		{/foreach}
	</table>
	{if $mc_total > $mc_limit}<div class="text-center fs-11 text-muted py-1">Hiển thị {$mc_limit}/{$mc_total} khách bê trễ nhất</div>{/if}
	{else}
	<div class="border text-center rounded-2 p-3 border-dashed text-muted">Bạn chưa đẩy khách nào (hoặc tất cả đã chốt/loại).</div>
	{/if}
</div>
