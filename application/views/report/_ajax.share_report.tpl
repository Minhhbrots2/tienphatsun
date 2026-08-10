<div class="card {if $show eq 'report_department'}h-100 no-shadow{/if}">
	{if !empty($is_sale)}
	<div class="card-header">
		<div class="d-flex align-items-start gap-2">
			<i class='bx bxs-check-circle text-success text-fs-26 mt-1'></i>
			<div class="d-flex flex-column flex-wrap gap-1">
				<h3 class="card-title mb-0">Hoạt động của bạn</h3>
				{if $show eq 'home_share'}<span class="text-muted fs-12">(7 ngày gần nhất)</span>{/if}
			</div>
		</div>
	</div>
	{else}
	<div class="card-header">
		<div class="d-flex align-items-start gap-2">
			<i class='bx bxs-check-circle text-success text-fs-26 mt-1'></i>
			<div class="d-flex flex-column flex-wrap gap-1">
				<h3 class="card-title mb-0">Hoạt động tiếp khách{if !empty($oneDep)}- {if !empty($oneDep.is_not_area)}PKD {/if}{$oneDep.title}{/if}</h3>
				<span class="text-muted text-fs-12">{$clsISO->convertTimeToText($start_time)} tới {$clsISO->convertTimeToText($end_time)}</span>
			</div>
		</div>
	</div>
	{/if}
	<div class="card-body">
		<div class="card p-2 mb-3">
			<div class="form-row mb-2" style="row-gap: 10px">
				<div class="col-6 flex-fill">
					<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">
						<div class="fs-12">
							<i class='bx bxs-group text-info fs-24'></i>
							<span class="">Tiếp khách</span>
						</div>
						<span class="fs-12"><strong class="fs-16">{$total_shares}</strong> lượt</span>
					</div>
				</div>
				<div class="col-6 flex-fill">
					<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">
						<div class="fs-12">
							<i class='bx bxs-group text-success fs-24'></i>
							<span class="">Tổng khách</span>
						</div>
						<span class="fs-12"><strong class="fs-16">{$total_guest_count}</strong></span>
					</div>
				</div>
				{if empty($is_sale)}
					<div class="col-6 flex-fill">
						<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap cursor-pointer" onClick="$Core.global.share.load_share_waiting(this,event)" is_confirm="0" show="{$show}">
							<div class="fs-12">
								<i class='bx bx-sad text-warning fs-24'></i>
								<span class="">Chờ duyệt</span>
							</div>
							<span class="fs-12"><strong class="fs-16">{$total_share_waiting}</strong> lượt</span>
						</div>
					</div>
					<div class="col-6 flex-fill">
						<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">
							<div class="fs-12">
								<i class='bx bxs-map text-success fs-24 ' ></i>
								<span class="">Sale h.động</span>
							</div>
							<span class="fs-12"><strong class="fs-16">{$total_sale_active}</strong>/{$total_sale}</span>
						</div>
					</div>
					{if !empty($oneDep)}
						{if empty($oneDep.is_not_area)}
							<div class="col-6 flex-fill">
								<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">
									<div class="fs-12">
										<i class='bx bx-building text-info fs-24' ></i>
										<span class="">Phòng KD</span>
									</div>
									<span class="fs-12"><strong class="fs-16">{$total_dep_active}</strong>/{$total_dep}</span>
								</div>
							</div>
						{/if}
					{else}
					<div class="col-6 flex-fill">
						<div class="bg-lighter px-1 py-2 rounded-1 h-100 d-flex justify-content-between align-items-end flex-wrap">
							<div class="fs-12">
								<i class='bx bx-building text-info fs-24' ></i>
								<span class="">Vùng KD</span>
							</div>
							<span class="fs-12"><strong class="fs-16">{$total_area_active}/{$lstDepartment|@count}</strong></span>
						</div>
					</div>
					{/if}
				{/if}
			</div>
			{if !empty($total_share_prev)}
				{if $ratio gte 0}
					<div class="alert alert-success py-2 mb-0"><i class='bx bx-trending-up'></i> + {$ratio}% lượt tiếp khách so với tháng trước</div>
				{elseif $total_shares eq 0}
					<div class="alert alert-warning py-2 mb-0"><i class='bx bx-meh-alt' ></i> Chưa có hoạt động tiếp khách nào trong tháng này </div>
				{else}
					<div class="alert alert-danger py-2 mb-0"><i class='bx bx-trending-down'></i> {$ratio}% lượt tiếp khách so với tháng trước</div>
				{/if}
			{else}
				{if $ratio eq 0}
					<div class="alert alert-warning py-2 mb-0"><i class='bx bx-meh-alt' ></i> Chưa có hoạt động tiếp khách nào trong tháng này </div>
				{else}
					<div class="alert alert-success py-2 mb-0"><i class='bx bx-trending-up'></i> + {$ratio} lượt tiếp khách so với tháng trước</div>
				{/if}
			{/if}
			{if !empty($is_sale) && !empty($time_last)}
				<div class="py-2 mb-0 d-flex align-items-center gap-1"><i class='bx bx-time-five' ></i>Lần gần nhất: <strong>{$clsISO->formatDate($time_last,3)}</strong></div>
			{/if}
		</div>		
		{if !empty($oneDep)}
			{if empty($oneDep.is_not_area)}
				{assign var=department_child value=$oneDep.department_child}
				<div class="box_progess">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<h3 class="mb-0">Phòng kinh doanh</h3>
						{if $show eq 'home_share'}
							<a href="{$clsISO->getLink('report_share')}" class="btn btn-info btn-sm fs-10 d-flex align-items-center">Xem chi tiết<i class='bx bx-chevron-right fs-14'></i></a>
						{/if}
					</div>
					<div class="mb-3">
						{assign var=rate value=$clsISO->getRateNumber($oneDep.total_share_dep,($oneDep.total_sale_dep * 8))}
						<div class="d-flex gap-2 align-items-center">
							<span class="fs-14 text-right w-px-75 text-nowrap">PKD</span>
							<div class="d-flex flex-column" style="width:calc(100% - 150px)">
								<div class="progress w-100" style="height:12px;">
								  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" 
									style="width:{$oneDep.total_share_dep*100/$oneDep.total_sale_dep}%;"></div>
								</div>
							</div>
							<span class="fs-12 text-nowrap w-px-75 text-right">
							<strong class="text-fs-18 text-main">{$oneDep.total_share_dep}</strong>/{($oneDep.total_sale_dep * 8)} {if !empty($rate)}<small class="text-success text-fs-13">({$rate}%)</small>{/if}</span>
						</div>
						{if !empty($oneDep.department_child)}
							{foreach from=$oneDep.department_child item=_oDepChild key=key name=i}
								{assign var=rate value=$clsISO->getRateNumber($_oDepChild.total_share_dep,($_oDepChild.total_sale_dep * 8))}
								<div class="d-flex gap-2 align-items-center">
									<span class="fs-14 text-right w-px-75 text-nowrap">PKD {$_oDepChild.title}</span>
									<div class="d-flex flex-column" style="width:calc(100% - 150px)">
										<div class="progress w-100" style="height:12px;">
										  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" 
											style="width:{$_oDepChild.total_share_dep*100/($_oDepChild.total_sale_dep * 8)}%;"></div>
										</div>
									</div>
									<span class="fs-12 text-nowrap w-px-75 text-right">
									<strong class="text-fs-18 text-main">{$_oDepChild.total_share_dep}</strong>/{($_oDepChild.total_sale_dep * 8)} {if !empty($rate)}<small class="text-success">({$rate}%)</small>{/if}</span>
								</div>
							{/foreach}
						{/if}
					</div>
					<div class="alert alert-warning py-2 mb-0 text-main d-flex align-items-center gap-1"><i class='bx bx-info-circle' ></i> {$total_sale - $total_sale_active} sale chưa tiếp khách</div>
				</div>
			{elseif $clsISO->checkPermissionGroup("SALE_DIRECTOR")}
				{if !empty($arr_top_share)}
					<div class="box_progess">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<h3 class="mb-0">Top hoạt động</h3>
							{if $show eq 'home_share'}
								<a href="{$clsISO->getLink('report_share')}" class="btn btn-info btn-sm fs-10 d-flex align-items-center">Xem chi tiết<i class='bx bx-chevron-right fs-14'></i></a>
							{/if}
						</div>
						<div class="mb-3">
							{section loop=$arr_top_share name=i max=3}
								<div class="d-flex gap-2 align-items-center top_item">
									<span class="icon_top_share"></span>
									<span class="">{$arr_top_share[i].full_name}</span>
									<span class="total_share_item d-flex align-items-center gap-2">{$arr_top_share[i].total} lượt</span>
								</div>
							{/section}
						</div>
					</div>
				{/if}
				<div class="alert alert-warning py-2 mb-0 text-main d-flex align-items-center gap-1"><i class='bx bx-info-circle' ></i> {$total_sale - $total_sale_active} sale chưa tiếp khách</div>
			{/if}
		{else}
			<div class="box_progess mb-2">
				<h3 class="text-fs-18">Vùng kinh doanh</h3>
				<div class="mb-3">
					{foreach from=$lstDepartment item=_oArea key=key name=i}
						{assign var=rate value=$clsISO->getRateNumber($_oArea.total_share_area,($_oArea.total_sale_area * 8))}
						<div class="card px-2 py-1 mb-2">
							<div class="d-flex gap-2 align-items-center w-100">
								<span class="fs-14 text-right w-px-75 text-nowrap">{$clsISO->replace($_oArea.title, 'kinh doanh', '')}</span>
								<div class="d-flex flex-column" style="width:calc(100% - 175px)">
									<div class="progress w-100" style="height:12px;">
									  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" 
										style="width:{$_oArea.total_share_area*100/($_oArea.total_sale_area * 8)}%;"></div>
									</div>
								</div>
								<span class="fs-12 text-nowrap w-px-100 text-right">
								<strong class="text-fs-18 text-main">{$_oArea.total_share_area}</strong>/{($_oArea.total_sale_area * 8)} {if !empty($rate)}<small class="text-success  text-fs-13">({$rate}%)</small>{/if}</span>
							</div>
							<div class="d-flex gap-2 align-items-center justify-content-start">
								<span class="fs-14 text-right w-px-75 text-nowrap"></span><span class="fs-10">PKD: <strong class="text-main">{$_oArea.total_share_dep}</strong></span>
								{if !empty($_oArea.department_child)}
									{foreach from=$_oArea.department_child item=_oDepChild key=key name=i}
										<span class="fs-10">{$_oDepChild.title}: <strong class="text-main">{$_oDepChild.total_share_dep}</strong></span>
									{/foreach}
								{/if}
							</div>
						</div>
					{/foreach}
				</div>
			</div>
		{/if}
	</div>
</div>