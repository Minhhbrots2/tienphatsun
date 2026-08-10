<div class="modal-dialog modal-dialog-scrollable">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Mục tiêu nhóm chiến dịch</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="table-wrapper">
				<table class="table table-bordered" cellpadding="0" cellspacing="0" width="100%">
					<thead>
						<th width="10%" class="text-center bg-lighter">STT</th>
						<th class="align-center bg-lighter">Họ và tên</th>
						{foreach from=$list_targets key= _oField item = _oTarget}
						<th class="align-center bg-lighter" width="20%">{$_oTarget}</th>
						{/foreach}
					</thead>
					{if !empty($group_members)}
						{foreach name=i from=$group_members key = user_id item = number_target}
						<tr>
							<td class="text-center">{$smarty.foreach.i.iteration}</td>
							<td class="text-left">{$clsProfile->getFullName($user_id)}</td>
							{foreach from=$list_targets key= _oField item = _oTarget}
							<td class="text-left">
								<input type="number" name="campaign_target[{$user_id}][{$_oField}]" value="{$number_target.$_oField}" class="form-control form-control-sm required numberonly" />
							</td>
							{/foreach}
						</tr>
						{/foreach}
					{/if}
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" selector="{$selector}" uid="{$uid}" campaign_id="{$campaign_id}" 
			onClick="$Core.campaign.save_target(this, event)" class="btn flex-fill btn-primary">Lưu lại</button>
		</div>
	</form>
</div>