<div class="rounded-3" style="overflow:auto;">

	<table class="table table-sort" width="100%">

		<thead><tr>

			<th colspan="7" class="align-center nosort ulpVdojZSe text-center bg-main fs-4">

				<div class="d-flex align-items-center justify-content-center gap-2">
					
					<img src="{$clsConfiguration->getValue('LogoWhite')}" width="{$clsConfiguration->getImageWidth('LogoWhite')}" height="{$clsConfiguration->getImageHeight('LogoWhite')}" alt="{$header_configs.CompanyName}" />

					<span class="text-white">Tổng hợp doanh số bán hàng {$m}</span>

				</div>

			</th>

		</tr>

		<tr>

			<th width="5%" rowspan="2" class="align-center nosort ulpVdojZSe text-center">No.</th>

			<th rowspan="2" class="align-center nosort ulpVdojZSe text-left">Mã nhóm.</th>

			<th rowspan="2" class="align-center nosort ulpVdojZSe text-left">Leaders.</th>

			<th class="align-center nosort text-center">Chỉ tiêu/{if $_ss_view_kpi eq 'quarter'}Quý{else if $_ss_view_kpi eq 'year'}Năm{else}Tháng{/if}</th>

			<th width="" class="align-center nosort text-center" colspan="2">Tổng các dự án</th>

			<th width="120px" rowspan="2" class="nosort align-center ulpVdojZSe text-center">% Hoàn<br />Thành</th>

		</tr>

		<tr>

			<th class="align-center ulpVdojZSe text-center">Doanh số</th>

			<th class="align-center ulpVdojZSe text-center sortable">

				{if $deviceType eq 'phone'}Tổng GD{else}T. Giao Dịch{/if}

			</th>

			<th class="align-center ulpVdojZSe text-center sortable">Doanh số</th>

		</tr></thead>

		<tbody>

			{if !empty($list_configs)}

				{foreach name=i from=$list_configs name=i item = _oconfig}

					{assign var = department_id value = $_oconfig.department_id}

					{assign var = list_teams value = $_oconfig.list_teams}

					{if isset($_oconfig.status) && $_oconfig.status eq '1'}

					<tr class="{if $smarty.foreach.i.index eq '0' && $_oconfig.total_depth_sales gt '0'}top-1{elseif $smarty.foreach.i.index eq '1' && $_oconfig.total_depth_sales gt '0'}top-2{elseif $smarty.foreach.i.index eq '2' && $_oconfig.total_depth_sales gt '0'}top-3{/if}">

						<td class="text-center">

							{if $smarty.foreach.i.index eq '0' && $_oconfig.total_depth_sales gt '0'}

							<img src="{$URL_IMAGES}/top-1.png?v={$smarty.now}" class="w-px-20" />

							{elseif $smarty.foreach.i.index eq '1' && $_oconfig.total_depth_sales gt '0'}

							<img src="{$URL_IMAGES}/top-2.png?v={$smarty.now}" class="w-px-20" />

							{elseif $smarty.foreach.i.index eq '2' && $_oconfig.total_depth_sales gt '0'}

							<img src="{$URL_IMAGES}/top-3.png?v={$smarty.now}" class="w-px-20" />

							{else} {$smarty.foreach.i.iteration} {/if}

						</td>

						{if $department_id eq 'PARTNER'}

						<td class="text-left">PARTNER</td>

						<td class="text-left font-bold fs-6">

							<div class="d-flex align-items-center">

								{$clsProfile->getIndentityV3($smarty.const._PROFILE_PARTNER_ID, true)}

							</div>

						</td>

						{elseif $department_id eq 'OTHER'}

						<td class="text-left">OTHER</td>

						<td class="text-left font-bold fs-6">

							<div class="d-flex align-items-center gap-1">

								<div class="avatar avatar-xxs" bis_skin_checked="1">

									<img class="avatar avatar-xs mr-2 rounded-pill" src="/files/thumb/60/60//images/avatar/2023-10-04-09-00-48-logo-h.png">

								</div>

								Nhóm tổng hợp

							</div>

						</td>

						{else}

						<td class="text-left">{$clsProperty->getTitle($department_id)}</td>

						<td class="text-left font-bold fs-6">

							<div class="d-flex align-items-center">

								{$clsProfile->getQLeader($department_id)}

							</div>

						</td>

						{/if}

						<td class="text-center fw-bold">{$_oconfig.total_sale}</td>

						<td class="text-center fw-bold">{$_oconfig.total_billings}</td>

						<td class="text-center fw-bold">{$clsISO->shortNumberV2($_oconfig.total_sales,2)} </td>

						<td class="text-center">{$_oconfig.kpi_percent}%</td>

					</tr>

					{if !empty($list_teams)}

						{foreach from=$list_teams item = _oTeam}

						<tr>

							<td class="text-center">↳</td>

							<td class="text-left">{$_oTeam.team_name}</td>

							<td class="text-left font-bold fs-6">{$clsProfile->getQLeader($_oTeam.team_id)}</td>

							<td class="text-center fw-bold">{$_oTeam.total_sale}</td>

							<td class="text-center fw-bold">{$clsISO->formatNumberToEasyRead($_oTeam.total_billings)}</td>

							<td class="text-center fw-bold">{$clsISO->shortNumberV2($_oTeam.total_sales,2)}</td>

							<td class="text-center">{$_oTeam.kpi_percent}%</td>

						</tr>

						{/foreach}

					{/if}

					{/if}

				{/foreach}

			{/if}

		</tbody>

		<tfoot><tr class="nohover">

			<td class="text-center font-bold" colspan="3">TỔNG CỘNG</td>

			<td class="text-center font-bold">{$clsISO->shortNumberV2($total_goals,0)} {$clsISO->getRate()}</td>

			<td class="text-center font-bold">{$total_billings} GD</td>

			<td class="text-center font-bold">{$clsISO->shortNumberV2($total_sales,2)} {$clsISO->getRate()}</td>

			<td class="text-center font-bold">{$clsKPI->getPercent($total_sales, $total_goals)}%</td>

		</tr></tfoot>

	</table>

</div>

