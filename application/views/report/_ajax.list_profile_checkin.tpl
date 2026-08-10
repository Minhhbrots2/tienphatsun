<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header"> 
			<h3 class="modal-title"><strong>{if $_type eq 'not_checkin'}Danh sách chưa check-in{elseif $_type eq 'all'}Danh sách nhân sự{elseif $_type eq 'on_time'}Danh sách đúng giờ{elseif $_type eq 'late'}Danh sách đi muộn{elseif $_type eq 'checked_out'}Danh sách đã check-out{elseif $_type eq 'early_leave'}Danh sách về sớm{else}Danh sách đã check-in{/if}</strong></h3>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body pt-0">
			<ul class="list-group list-group-flush overflow-y-auto" style="max-height: calc(100vh - 200px)">
				{if !empty($list_profile_ids)}
					{foreach from=$list_profile_ids item=_oItem key=key name=i}
						<li class="list-group-item d-flex align-items-center gap-2 py-2 px-3">
							<a href="javascript:void(0);" onclick="$Core.report_checkin.load_profile_journey(this, event)" data-profile="{$_oItem.profile_id}" class="d-flex align-items-center gap-2 text-decoration-none flex-grow-1 text-dark" title="Xem hành trình check-in">
								<img class="rounded-pill" src="{$_oItem.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="36" height="36" alt="" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oItem.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-width="300">
								<div class="flex-grow-1 overflow-hidden">
									<small class="text-muted d-block">{$_oItem.department_name}</small>
									<span class="fw-semibold text-truncate d-block" style="max-width:180px" title="{$_oItem.full_name}">{$_oItem.full_name}</span>
									<small class="d-block" style="font-size:11px"><span class="text-muted">Vào:</span> <span class="fw-semibold text-success">{if $_oItem.time_in}{$_oItem.time_in}{else}--{/if}</span> <span class="text-muted ms-2">Ra:</span> {if $_oItem.time_out eq 'Chưa check-out'}<span class="fw-semibold text-warning">Chưa check-out</span>{elseif $_oItem.time_out && $_oItem.time_out neq '--'}<span class="fw-semibold text-danger">{$_oItem.time_out}</span>{else}<span class="text-muted">--</span>{/if}</small>
								</div>
								{if $_oItem.count}<span class="badge bg-label-primary flex-shrink-0">{$_oItem.count} lần</span>{/if}
								<i class="bx bx-chevron-right text-muted fs-5 flex-shrink-0"></i>
							</a>
						</li>
					{/foreach}
				{else}
					<li><div class="text-center p-4 fs-6">Chưa có check-in nào</div></li>
				{/if}
			</ul>
		</div>
	</div>
</div>