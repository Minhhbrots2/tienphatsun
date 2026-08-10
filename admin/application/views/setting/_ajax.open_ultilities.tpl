<div class="modal-dialog modal-xs">
	<form class="modal-content" method="POST">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Chọn tiện ích</strong></h3>
		</div>
		<div class="modal-body modal-body-scrollable">
			{if !empty($list_ultilites)}
				<div class="checkbox py-2">
					<input type="checkbox" id="check_all_{$uid}" class="check_all styled" toId="{$uid}" value="1" onChange="$Core.global.select_checkbox(this,event)" _type="_all" >
					<label for="check_all_{$uid}">Chọn tất cả</label>
				</div>
				{foreach from=$list_ultilites item = _oItem}
				<div class="border radius-3 p-3 mb-2">
					<div class="checkbox">
						<input type="checkbox" name="utility_id[]" class="chkitem_{$uid} styled" value="{$_oItem.property_id}"  onChange="$Core.global.select_checkbox(this,event)" _type="_item" toId="{$uid}" id="{$uid}_{$_oItem.property_id}" {if $clsISO->checkItemInArray($_oItem.property_id,$permiss_ultilities)}checked{/if} >
						<label for="{$uid}_{$_oItem.property_id}">{$_oItem.title}{if !empty($_oItem.role)}<span class="fs-tiny">({$_oItem.role})</span>{/if}</label>
					</div>
				</div>
				{/foreach}
			{/if}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success" for_id="{$for_id}" 
				profile_type="{$profile_type}" onClick="$Core.property.save_list_ultilities(this, event)">
				<span>Lưu lại</span>
			</button>
		</div>
	</form>
</div>
	