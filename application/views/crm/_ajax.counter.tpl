{if $deviceType eq 'phone'}
	{foreach from = $list_totals item = _oK}
	<div class="flex-fill card overflow-hidden rounded-1 h-100">
		<div class="card-body py-2 px-1">
			<h6 class="mb-2 text-nowrap fs-12">{$_oK.title}</h6>
			<h4 class="fs-16 text-nowrap mb-0">
				<span class="text-main">{$_oK.total}</span>
				<span class="fs-11 fw-normal text-muted">{$label}</span>
			</h4>
		</div>
	</div>
	{/foreach}
{else}
	<div class="row gy-4 gy-sm-1">
		{foreach name=i from = $list_totals item = _oK}
		<div class="col-sm-6 col-lg-3">
			<div class="d-flex justify-content-between align-items-start{if !$smarty.foreach.i.last} border-end{/if} p-2 pb-sm-0">
				<div class="">
					<h6 class="mb-2">{$_oK.title}</h6>
					<h4 class="mb-0">
						<span class="text-main">{$_oK.total}</span>
						<span class="fs-12 fw-normal text-muted">{$label}</span>
					</h4>
				</div>
				<div class="avatar me-lg-4">
					<span class="avatar-initial rounded bg-label-secondary">
						<i class="bx {$_oK.icon} bx-sm"></i>
					</span>
				</div>
			</div>
		</div>
		{/foreach}
	</div>
{/if}