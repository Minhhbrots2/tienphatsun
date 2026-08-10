<div class="modal-dialog modal-xs">
	<form class="modal-content" method="POST">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Phân quyền menu tiện ích</strong></h3>
		</div>
		<div class="modal-body modal-body-scrollable">
			{if !empty($list_ultilites)}
			<div class="drag_ultilites">
				{foreach from=$list_ultilites item = _oItem}
				<div class="d-flex align-items-center gap-2 mb-2 ultilites-item p-2 border" data-id="{$_oItem.property_id}" id="{$uid}_{$_oItem.property_id}">
					<span class="btn btn-sm btn-icon btn-default zindex-1 bg-white btn_drag" title="Giữ vào kéo thả" type="button" style="right:calc(var(--bs-gutter-x) * 0.5)"><i class="fa fa-arrows" aria-hidden="true"></i></span>
					<span>{$_oItem.title}</span>
				</div>
				{/foreach}
			</div>
			{/if}
		</div>
		<div class="modal-footer">
			<input type="hidden" name="permiss_ultilities" value='{$permiss_ultilities}'>
			<button type="button" class="btn btn-success" for_id="{$for_id}" profile_type="{$profile_type}" 
				onClick="$Core.property.save_permiss_ultilities(this, event)">
				<span>Lưu lại</span>
			</button>
		</div>
	</form>
</div>
	