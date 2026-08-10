{* Partial: Check-in mới nhất (full-width, toàn bộ danh sách) *}
<div class="card" id="card_recent">
	<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
		<h5 class="mb-0"><i class="bx bx-map-pin text-primary me-1"></i>Check-in mới nhất</h5>
		{if !empty($list_recent)}
		<span class="badge bg-label-primary">{$list_recent|@count} lượt</span>
		{/if}
	</div>
	<div class="card-body">
		{if empty($list_recent)}
		<div class="text-center text-muted py-4">Chưa có dữ liệu</div>
		{else}
		<div class="form-row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-5 g-3">
			{foreach from=$list_recent item=_row}
			<div class="col mb-2">
				<div class="border rounded-3 p-2 h-100"
					{if !empty($_row.photo)}
					data-fancybox="checkin_photos" href="{$_row.photo}"
					data-caption="<div><strong>{$_row.full_name}</strong><br>{$_row.time_label}<br>{$_row.location|escape}</div>"
					{/if}>
					<div class="d-flex align-items-center gap-2 mb-2">
						<a href="javascript:void(0);" onclick="$Core.member.view_profile(this, event)" profile_id="{$_row.profile_id}">
							<img class="rounded-pill" src="{$_row.avatar}"
								onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="36" height="36" alt="">
						</a>
						<div class="overflow-hidden">
							<div class="fw-semibold fs-13 text-truncate" title="{$_row.full_name}">{$_row.full_name}</div>
							<div class="text-muted fs-11">{$_row.time_label}</div>
						</div>
					</div>
					{if !empty($_row.location)}
					<div class="text-muted fs-11 mb-2 text-truncate" title="{$_row.location|escape}" lat="{$_row.lat}" lng="{$_row.lng}" >
						<i class="bx bx-map me-1"></i>{$_row.location}
					</div>
					{/if}
					{if !empty($_row.photo)}
					<img src="{$_row.photo}" onerror="this.src='{$URL_IMAGES}/no-image.png'"
						alt="" class="w-100 rounded-2" style="height:150px;object-fit:cover;cursor:pointer">
					{else}
					<div class="bg-light rounded-2 d-flex align-items-center justify-content-center" style="height:80px">
						<i class="bx bx-image text-muted fs-2"></i>
					</div>
					{/if}
				</div>
			</div>
			{/foreach}
		</div>
		{/if}
	</div>
</div>
