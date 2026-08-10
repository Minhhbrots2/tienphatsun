{if !empty($list_staffs)}

	{foreach name=i from=$list_staffs name=i key=key item=_oItem}

	<tr class="{if $smarty.foreach.i.iteration gt '15'}d-none toggle-tr{/if}">

		<td class="text-center">{$smarty.foreach.i.iteration}</td>

		<td class="text-left">

			<span class="badge bg-label-purple">{$_oItem.department_name}</span>

			{$_oItem.full_name}

		</td>

		<td class="text-center">
			{if !empty($_oItem.total_social)}
				<a class="text-link" data-trigger="hover" style="button" data-width="300" data-url="/index.php?mod=report&act=load_social_profile&user_id={$_oItem.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-width="300">{$_oItem.total_social} link</a>
			{else}
				{$_oItem.total_social} link
			{/if}
		</td>
		<td class="text-center">

			<strong class="text-warning">{$_oItem.total_share}</strong> lượt

		</td>

		<td class="text-center">

			<strong class="text-warning">{$_oItem.total_search}</strong> lượt

		</td>

		<td class="text-center">

			{if !empty($_oItem.target_quantity)}

			<span class="text-info fw-bold">{$_oItem.target_quantity} <small>GD</small>

			{else}

			--

			{/if}

		</td>

		<td class="text-center">

			{if !empty({$_oItem.target_achieved_quantity})}

			<span class="text-info fw-bold">{$_oItem.target_achieved_quantity} <small>GD</small>

			{else}

			<span class="text-muted fw-bold">{$_oItem.target_achieved_quantity} <small>GD</small>

			{/if}

		</td>

		<td class="text-left">

			<div class="progress progress_quantity">

				<div class="progress-bar bg-info" role="progressbar" style="width:{$_oItem.target_quantity_rate}%" 

					aria-valuemin="0" aria-valuemax="100">{$_oItem.target_quantity_rate}%</div>

			</div>

		</td>

		<td class="text-center">

			{if !empty($_oItem.target_quantity)}

			<span class="text-warning fw-bold">{$clsISO->shortNumber($_oItem.target_amount,1)}</span>

			{else}

			--

			{/if}

		</td>	

		<td class="text-center">

			{if !empty($_oItem.target_achieved_amount)}

			<span class="text-warning fw-bold">{$clsISO->shortNumber($_oItem.target_achieved_amount,1)}</span>

			{else}

			<span class="text-muted fw-bold">{$clsISO->shortNumber($_oItem.target_achieved_amount,1)}</span>

			{/if}

		</td>	

		<td class="text-left">

			<div class="progress progress_amount">

				<div class="progress-bar bg-warning" role="progressbar" style="width:{$_oItem.target_amount_rate}%" 

					aria-valuemin="0" aria-valuemax="100">{$_oItem.target_amount_rate}%</div>

			</div>

		</td>	

		<td class="text-center">

			{$_oItem.total_work_unit}/26

		</td>

	</tr>

	{/foreach}

	<!-- <tr class="position-sticky bottom-0 zindex-3">

		<td class="align-center text-center text-upper bg-lighter h-px-35" colspan="2">Tổng</td>

		<td class="align-center text-center bg-lighter h-px-35" colspan="">

			<strong class="text-warning">{$total_share}</strong> lượt

		</td>

		<td class="align-center text-center bg-lighter h-px-35" colspan="">

			<strong class="text-warning">{$total_search}</strong> lượt

		</td>

		<td class="align-center text-center bg-lighter h-px-35" colspan="">

			{if !empty($total_target_quantity)}

			<span class="text-info fw-bold">{$total_target_quantity} <small>GD</small></span>

			{else}

			--

			{/if}

		</td>

		<td class="align-center text-center bg-lighter h-px-35" colspan="">

			{if !empty($total_achieved_quantity)}

			<span class="text-info fw-bold">{$total_achieved_quantity} <small>GD</small></span>

			{else}

			--

			{/if}

		</td>

		<td class="align-center bg-lighter h-px-35" colspan="">

			<div class="w-px-100">

				<div class="small fw-medium">GD {$total_target_quantity_rate}</div>

				<div class="progress progress_quantity h-px-10">

					<div class="progress-bar bg-info" role="progressbar" style="width: {$total_target_quantity_rate}" 

						aria-valuemin="0" aria-valuemax="100"></div>

				</div>

			</div>

		</td>

		<td class="align-center text-center bg-lighter h-px-35" colspan="">

			{if !empty($total_target_amount)}

				<span class="text-warning fw-bold">{$clsISO->shortNumber($total_target_amount,1,1)}</span>

			{else}--{/if}

		</td>

		<td class="align-center text-center bg-lighter h-px-35" colspan="">

			{if !empty($total_achieved_amount)}

				<span class="text-warning fw-bold">{$clsISO->shortNumber($total_achieved_amount,1,1)}</span>

			{else}--{/if}

		</td>

		<td class="align-center bg-lighter h-px-35">

			<div class="w-px-100">

				<div class="small fw-medium">DS {$total_target_amount_rate}</div>

				<div class="progress progress_amount h-px-10">

					<div class="progress-bar bg-warning" role="progressbar" style="width: {$total_target_amount_rate}" 

						aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>

				</div>

			</div>

		</td>

	</tr> -->

	{if $smarty.foreach.i.iteration gt '15'}

	<tr>

		<td class="text-center bg-lighter" colspan="11">

			<button data-toggle="ripple" type="button" onClick="$Core.util.toggle_tr(this, event)" toCls="toggle-tr" 

				class="btn btn-sm btn-outline-default px-3 rounded-pill">

				<i class="bx bx-chevron-down"></i> Xem tất cả

			</button>

		</td>

	</tr>

	{/if}

{else}

	<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}5{else}3{/if}">Dữ liệu trống</td></tr>

{/if}