<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header border-bottom pb-3"> 
			<h5 class="modal-title">Cửa hàng/ Tiện ích <br />
				<span class="text-muted fs-12">Hiện có <strong class="text-main">{$list_shops|@count}</strong> cửa hàng, dịch vụ, tiện ích</span>
			</h5>
		</div>
		<div class="modal-body border-bottom box_infomation">
			{section name=i loop=$list_shops}
			<div class="py-2 d-flex flex-wrap align-items-center{if !$smarty.section.i.last} border-bottom{/if}">
				<span class="mr-2">{$smarty.section.i.iteration}/ {$list_shops[i].title}</span>
				{if !empty($list_shops[i].list_cats)}
					<div class="d-flex gap-1 flex-wrap">
					{foreach from=$list_shops[i].list_cats item = _oCat}
						<span class="badge bg-label-primary">{$_oCat.title}</span>
					{/foreach}
					</div>
				{else}
					<span class="badge bg-label-primary">{$clsProperty->getTitle($list_shops[i].cat_id)}</span>
				{/if}
			</div>
			{/section}
		</div>
		<div class="modal-footer justify-content-center">
			<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
		</div>
	</div>
</div>