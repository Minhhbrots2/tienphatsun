<div class="row g-2 mb-2">
	<div class="col-6 col-sm-3">
		<div class="kpi-card kpi-green">
			<div class="kpi-label">💵 Dòng tiền</div>
			<div>
				<span class="kpi-value">{if $total_cash_flow lt 0}-{/if}{$clsISO->shortNumber(abs($total_cash_flow))}</span>
				{if $change_rate_cash_flow ne ""}
					<span class="kpi-badge">{if $change_rate_cash_flow > 0}▲{else}▼{/if}{$change_rate_cash_flow}</span>
				{/if}
			</div>
		</div>
	</div>
	<div class="col-6 col-sm-3">
		<div class="kpi-card kpi-blue">
			<div class="kpi-label">🏦 Tiền mặt</div>
			<div>
				<span class="kpi-value">{if $total_cash lt 0}-{/if}{$clsISO->shortNumber(abs($total_cash))}</span>
				{if $change_rate_cash ne ""}
					<span class="kpi-badge">{if $change_rate_cash > 0}▲{else}▼{/if}{$change_rate_cash}</span>
				{/if}
			</div>
		</div>
	</div>
	<div class="col-6 col-sm-3">
		<div class="kpi-card kpi-orange">
			<div class="kpi-label">📋 Phải thu</div>
			<div>
				<span class="kpi-value">{if $total_PHAITHU lt 0}-{/if}{$clsISO->shortNumber(abs($total_PHAITHU))}</span>
				{if $change_rate_PHAITHU ne ""}
					<span class="kpi-badge">{if $change_rate_PHAITHU > 0}▲{else}▼{/if}{$change_rate_PHAITHU}</span>
				{/if}
			</div>
		</div>
	</div>
	<div class="col-6 col-sm-3">
		<div class="kpi-card kpi-red">
			<div class="kpi-label">📌 Phải trả</div>
			<div>
				<span class="kpi-value">{if $total_PHAITRA lt 0}-{/if}{$clsISO->shortNumber(abs($total_PHAITRA))}</span>
				{if $change_rate_PHAITRA ne ""}
					<span class="kpi-badge">{if $change_rate_PHAITRA > 0}▲{else}▼{/if}{$change_rate_PHAITRA}</span>
				{/if}
			</div>
		</div>
	</div>
</div>
<!-- KPI ROW 2 -->
<div class="row g-2 mb-3">
	<div class="col-6 col-sm-3">
		<div class="kpi-card kpi-green2">
			<div class="kpi-label">📈 Dự thu</div>
			<div><span class="kpi-value">{$clsISO->shortNumber($total_change_DUTHU)}</span></div>
		</div>
	</div>
	<div class="col-6 col-sm-3">
		<div class="kpi-card kpi-red2">
			<div class="kpi-label">📉 Dự chi</div>
			<div><span class="kpi-value">{$clsISO->shortNumber($total_DUCHI)}</span></div>
		</div>
	</div>
	<div class="col-6 col-sm-3">
		<div class="kpi-card kpi-purple">
			<div class="kpi-label">🧾 Thuế</div>
			<div><span class="kpi-value">{$clsISO->shortNumber($total_OUTPUT_TAX)}</span></div>
		</div>
	</div>
	<div class="col-6 col-sm-3">
		<div class="kpi-card kpi-navy">
			<div class="kpi-label">💳 Vay nợ</div>
			<div><span class="kpi-value">{$clsISO->shortNumber($total_DEBT)}</span></div>
		</div>
	</div>
</div>