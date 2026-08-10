<div class="list_share_waiting mb-2 ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_share_waiting" data-options='{ldelim}{rdelim}'>
	<div class="card">
		<div class="card-header">
			<h5 class="card-title m-1 me-2">Báo cáo tiếp khách chờ xác thực</h5>
		</div>
		<div class="card-body">
			<ul class="p-0 m-0">
				{section name=i loop = $list_preloaders name=i max = 10}
				<li class="d-flex align-items-center gap-2{if !$smarty.foreach.i.last} mb-2{/if}">
					{if $smarty.section.i.iteration%2==0}
					<div class="animate-bg w-100 rounded-2 h-px-15"></div>
					{else}
					<div class="animate-bg w-100 rounded-2 h-px-15"></div>
					<div class="animate-bg w-100 rounded-2 h-px-15"></div>
					{/if}
				</li>
				{/section}
			</ul>
		</div>
	</div>
</div>