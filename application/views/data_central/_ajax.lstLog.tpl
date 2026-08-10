<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable">
		<form method="POST" enctype="multipart/form-data" class="modal-content">
			<div class="modal-content">
				<div class="modal-header d-flex align-items-center justify-content-between">
					<div class="modal-header__left">
						<h5 class="modal-title">Lịch sử log</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
					</div>
				</div>
				<div class="modal-body">	
					<div class="overflow-x-auto no-shadow text-nowrap">
						<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">
							<thead><tr>
								{if $deviceType ne 'phone'}
								<th class="text-left" width="5%">No.</th>{/if}
								<th class="text-left">Họ tên</th>
								<th class="text-left">Thời gian</th>
								<th class="text-left">Hành động</th>
							</tr></thead>
							<tbody>
								{if !empty($log_call)}
									{foreach from=$log_call item=_oItem key=key name=i }
										<tr>
											{if $deviceType ne 'phone'}
											<td class="text-center">{$smarty.foreach.i.iteration}</td>{/if}
											<td class="text-nowrap">
												<a href="javascript:void(0);" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oItem.profile_id}" data-toggle="webui-popover" data-trigger="hover" class="text-nowrap" data-target="webuiPopover0">
													<img class="avatar avatar-xxs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oItem.profile_id)}">{$clsProfile->getFullName($_oItem.profile_id)}</a>
											</td>
											<td class="align-center">{$clsISO->formatDate($_oItem.reg_date,4)}</td>
											<td class="text-center"><span class="text-success">Gọi điện</span></td>
										</tr>
										{math equation="x+1" x=$stt assign="stt"}
									{/foreach}
								{else}
									<tr><td class="text-center" colspan="5">Danh sách trống</td></tr>
								{/if}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
