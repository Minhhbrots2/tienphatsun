<div class="card bg-main h-100">
	<div class="card-header d-flex align-items-center justify-content-between pb-2">
		<h5 class="card-title text-white m-0">
			<i class='bx bx-bell'></i>
			<span>Sự kiện & Đào tạo</span>
		</h5>
		{if $clsISO->checkPermission("view_all_course")}
		<a href="{$clsISO->getLink('course')}" class="text-white text-decoration-underline" title="Xem tất cả">Xem tất cả</a>
		{/if}
	</div>
	<div class="card-body mt-0">
		<div class="ajax home_events" data-options="{ldelim}{rdelim}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=course&act=home_events">
			<div class="row">
				<div class="col-12">
					<div class="animate-bg w-100 h-px-20 rounded-pill mb-3"></div>
					<div class="animate-bg w-60 rounded-pill h-px-15 mb-2"></div>
					<div class="animate-bg w-80 rounded-pill mb-2 h-px-15"></div>
					<div class="animate-bg w-100 h-px-15 rounded-pill mb-2"></div>
					<div class="animate-bg w-60 rounded-pill h-px-15 mb-2"></div>
				</div>
			</div>
		</div>
	</div>
</div>

