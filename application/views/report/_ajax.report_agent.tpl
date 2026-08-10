{foreach name=i from=$list_agents item = _OI}
{if $_OI.total_all gt '0'}
<tr class="{if $smarty.foreach.i.iteration gt '20'}d-none toogle_tr{/if}">
	{if $deviceType ne 'phone'}{/if}
	<td class="text-center">
		{$smarty.foreach.i.iteration}
	</td>
	<td class="fw-bold text-upper">{$_OI.title}</td>
	<td class="text-center">
		<strong class="text-primary">{$_OI.total_all}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-warning">{$_OI.total_mgc}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-warning">{$_OI.total_mel}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-warning">{$_OI.total_lek}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-warning">{$_OI.total_lsb}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-warning">{$_OI.total_lop}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-warning">{$_OI.total_msq}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-warning">{$_OI.total_tgc}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-danger">{$_OI.total_mik}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-success">{$_OI.total_sun}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-info">{$_OI.total_alu}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-success">{$_OI.total_vin}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-info">{$_OI.total_alc}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
	<td class="text-center">
		<strong class="text-info">{$_OI.total_np}</strong>
		{if $deviceType ne 'phone'} căn{/if}
	</td>
</tr>
{/if}
{/foreach}
<tr>
	<td class="text-center" colspan="20">
		<button onClick="$Core.util.toggle_tr(this, event)" toCls="toogle_tr" 
			class="btn btn-sm rounded-pill btn-outline-default">
			<div class="d-flex align-items-center gap-1">
				<span>Xem thêm</span>
				<i class='bx bx-chevron-down' ></i>
			</div>
		</button>
	</td>
</tr>