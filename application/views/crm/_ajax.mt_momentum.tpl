{* _ajax.mt_momentum.tpl — Box chuyển trạng thái kỳ (momentum). *}
{* Dữ liệu: mt_momentum *}
<div class="crm-ld-panel h-100">
	<div class="crm-ld-panel-h">
		<h4><i class="bx bx-transfer"></i> Chuyển trạng thái kỳ này</h4>
	</div>
	<div class="px-3 pb-3 pt-1 d-flex flex-column gap-2">
		{if $mt_momentum}
			{foreach from=$mt_momentum item=_m}
			<div class="crm-ld-trans">
				<span class="crm-ld-pill" style="{$_m.from_style}">{$_m.from}</span>
				<i class="bx bx-right-arrow-alt"></i>
				<span class="crm-ld-pill" style="{$_m.to_style}">{$_m.to}</span>
				<b class="ms-auto js__mt-drill" style="color:{$_m.to_ink}" data-metric="momentum" data-from="{$_m.from_id}" data-to="{$_m.to_id}">{$_m.n}</b>
			</div>
			{/foreach}
		{else}
			<div class="text-center text-muted fs-12 py-2">Chưa có chuyển trạng thái trong kỳ.</div>
		{/if}
		<div class="text-muted fs-12 mt-1 mb-3">
			<i class="bx bx-info-circle"></i> 
			"Chuyển trạng thái" chỉ tính các lần đổi trạng thái qua CRM (theo lịch sử ghi nhận). Trang chỉ xem — thao tác chi tiết bổ sung sau.
		</div>
	</div>
</div>