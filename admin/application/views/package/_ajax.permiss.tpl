<div class="modal-dialog modal-md">
	<form class="modal-content" method="POST">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>Phân quyền gói tài khoản</strong></h3>
		</div>
		<div class="modal-body modal-body-scrollable">
			{if !empty($list_permiss)}
				{foreach from=$list_permiss item = _oGroup}
				{assign var = list_items value = $_oGroup.list_items}
				<fieldset>
					<legend>{$_oGroup.title}</legend>
					<div class="row">
						{foreach from=$list_items item = _oItem}
						<div class="col-12 col-md-4 mb-2">
							<div class="d-flex align-items-center" style="height: 40px">
								<label class="switch mr-2">
									<input type="checkbox" name="permiss_mod[{$_oItem.code}]"{if $_oItem.checked eq '1'} checked{/if} value="1" />
									<span class="slider round"></span>
								</label>
								<span>{$_oItem.title}</span>
							</div>
						</div>
						{/foreach}
					</div>
				</fieldset>
				{/foreach}
			{else}
				<p class="text-muted text-center py-3">Chưa có quyền nào cho loại "{$profile_type}". (Cần clone permiss MF trước.)</p>
			{/if}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success" for_id="{$for_id}" profile_type="{$profile_type}"
				onClick="$Core.permiss.storage(this, event)">
				<span>Lưu lại</span>
			</button>
		</div>
	</form>
</div>
