{if $type eq "_TOTAL"}
<div class="form-row">
	<div class="col-6 col-sm-3 col-lg-2 flex-fill order-1 mb-2">
		<div class="card h-100 no-shadow">
			<div class="card-body">
				<div class="d-flex align-items-center mb-3 justify-content-between">
					<div class="p-left">
						<h5 class="card-title mb-0 text-nowrap text-dark">Tổng</h5>
					</div>
				</div>
				<h3 class="fs-5 mb-0 fw-bold text-main">
					<span data-from="0" data-to="{$totalPoint}">{$clsISO->formatNumber2($totalPoint)}</span>
				</h3>
			</div>
		</div>
	</div>
	{foreach from=$arr_department item=department key=key}
		{assign var=lstChild value=$department.lstChild}
		{if $key ne $smarty.const._DEPARTMENT_SALE_ID}
			<div class="col-6 col-sm-4 col-lg-4 col-xxl-2 flex-fill order-1 mb-2">
				<div class="card h-100 no-shadow rounded-1">
					<div class="card-body">
						<div class="d-flex align-items-center mb-3 justify-content-between">
							<div class="p-left">
								<h5 class="card-title mb-0 text-nowrap fs-6 text-dark fw-semibold">{$department.title}</h5>
							</div>
						</div>
						<h3 class="fs-5 mb-0 fw-bold text-main">
							<span data-from="0" data-to="{$department.point}">{$clsISO->formatNumber2($department.point)}</span>
						</h3>
					</div>
				</div>
			</div>
		{else}
			<div class="col-12 order-2 mb-2">
				<div class="card h-100 no-shadow rounded-1">
					<div class="card-body">
						<div class="d-flex align-items-center mb-3 justify-content-between">
							<div class="p-left">
								<h5 class="card-title mb-0 text-nowrap text-dark fw-semibold">{$department.title}</h5>
							</div>
						</div>
						<div >
							<div class="form-row row">
								<div class="col-6 col-sm-3 col-md-2 flex-fill mb-2">
									<div class="gbox gotoLink px-2 py-3 h-100">
										<h5 class="mb-2 fs-14">Tổng</h5> 
										<h3 class="fs-5 mb-0 fw-bold text-main">
											<span data-from="0" data-to="{$department.point}">{$clsISO->formatNumber2($department.point)}</span>
										</h3>
									</div>
								</div>
								{if !empty(lstChild)}
									{foreach from=$lstChild item=dep_child key=k}
									<div class="col-6 col-sm-3 col-md-2 flex-fill mb-2">
										<div class="gbox gotoLink px-2 py-3 h-100">
											<h5 class="mb-2 fs-14">{$dep_child.title}</h5> 
											<h3 class="fs-5 mb-0 fw-bold text-main">
												<span data-from="0" data-to="{$dep_child.point}">{$clsISO->formatNumber2($dep_child.point)}</span>
											</h3>
										</div>
									</div>
									{/foreach}
								{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
		{/if}
	{/foreach}
</div>
{else if $type eq '_TOP'}
	{if !empty($lstTopProfile)}
	<ul class="p-0 m-0">
		{foreach name=i from=$lstTopProfile name=i key=key item=item}
		<li class="d-flex align-items-center mb-3 pb-1">
			<div class="fw-semibold fs-16 text-center me-2 rounded-pill border" style="width: 30px;height: 30px;line-height:30px">{$smarty.foreach.i.iteration}</div>
			<div class="d-flex flex-fill">
				<div class="avatar position-relative flex-shrink-0 me-2" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$item.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400">
					<img src="{$item.avatar}" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" alt="{$item.full_name}" class="rounded" />
					{$clsProfile->get_icon_verified($item.profile_id, $item.more_information)}
				</div>
				<div class="w-100">
					<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
						<h6 class="mb-0">{$item.full_name}</h6>
						<div class="user-progress d-flex align-items-center gap-1">
							<h6 class="mb-0"><a href="javascript:void(0);" class="text-orange" onClick="$Core.global.open_Lpoint(this, event)" staff_id="{$item.profile_id}">{$item.total_Lpoint}</a></h6>
						</div>
					</div>
					<small class="text-muted d-block">{$item.department_name}</small>
				</div>
			</div>
		</li>
		{/foreach}
	</ul>
	{/if}
{else if $type eq '_LIST' || $type eq "_PAGE"}
<div class="table-wrapper overflow-auto d-flex flex-wrap">
	<table class="table table-bordered" width="100%">
		<thead><tr>
			{if $deviceType ne 'phone'}
			<th width="30px" class="align-center nosort bg-lighter">STT</th>
			{/if}
			<th class="align-center text-left bg-lighter">Họ và tên</th>
			<th class="align-center nosort text-center bg-lighter" style="width:150px">Phòng ban</th>
			<th class="align-center nosort text-center bg-lighter" style="width:150px">Điểm</th>
			<th class="align-center nosort text-center bg-lighter" style="width:150px">Ngày</th>
		</tr></thead>
		{if !empty($lstProfile)}
			{foreach name=i from=$lstProfile name=i key=key item=item}
				<tr>
					{if $deviceType ne 'phone'}
					<td class="text-center">{$smarty.foreach.i.iteration}</td>{/if}					
					<td class="text-left">{$item.full_name}</td>	
					<td class="text-center">{$item.department_name}</td>
					<td class="text-center text-orange">
						{if $item.act eq '_mimus'}-{else}+{/if}{$item.score}
					</td>
					<td class="text-center">
						{$clsISO->formatDate($item.reg_date,4)}
					</td>
				</tr>
			{/foreach}
		{else}
			<tr><td class="text-center" colspan="4">Dữ liệu trống</td></tr>
		{/if}
	</table>
</div>
{/if}
