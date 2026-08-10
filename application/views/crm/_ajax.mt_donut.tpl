{* _ajax.mt_donut.tpl — Box donut phân bố trạng thái. *}
{* Dữ liệu: mt_donut, mt_donut_total, mt_donut_grad *}
<div class="crm-ld-panel h-100">
	<div class="crm-ld-panel-h"><h4><i class="bx bx-doughnut-chart"></i> Phân bố trạng thái</h4></div>
	<div class="p-3 d-flex align-items-center gap-3 flex-wrap">
		{if $mt_donut_total > 0}
		<div class="crm-ld-donut" style="background:conic-gradient({$mt_donut_grad})"></div>
		<div class="crm-ld-legend">
			{foreach from=$mt_donut item=_d}
			<div><i style="background:{$_d.color}"></i> {$_d.label} — {$_d.count}</div>
			{/foreach}
		</div>
		{else}
		<div class="text-muted fs-12 py-3">Chưa có dữ liệu phân bố.</div>
		{/if}
	</div>
</div>