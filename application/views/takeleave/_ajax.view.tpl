<div class="modal-dialog modal-ipad">
	{assign var = toId value = $clsISO->getUniqid()}
	<div class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title lh-sm">Chi tiết nghỉ phép {$oneItem.code}</h5>
			<button type="button" onClick="$Core.overtime.close(this,event)" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="highlights">
			<div class="highlight-panel">
				<div class="highlight-list gap-2">
					<div class="highlight-item no-gap">
						<div class="highlight-label">Mã</div>
						<div class="metadata-row-viewer">
							<i class='bx bx-code'></i>
							{$oneTakeLeave.code} FH/NP{$oneTakeLeave.takeleave_id}
						</div>
					</div>
					<div class="highlight-item no-gap">
						<div class="highlight-label">Loại nghỉ phép</div>
						<div id class="metadata-row-viewer status_{$toId}">
							{$clsProperty->getTitle($oneTakeLeave.cat_property_id)} <i class="bx bx-info-circle fs-13"></i>
						</div>
					</div>
					<div class="highlight-item no-gap">
						<div class="highlight-label">Tình trạng duyệt</div>
						<div  class="metadata-row-viewer text-nowrap done_ratio_{$toId} mt-2">
							Đã duyệt 0/4
						</div>
					</div>
					<div class="highlight-item no-gap">
						<div class="highlight-label">Ngày tạo</div>
						<div class="metadata-row-viewer text-nowrap">
							<i class="material-icons-outlined">more_time</i>
							{$clsISO->convertTimeToText($oneTakeLeave.reg_date, true)}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-body">
			<div class="widget-block mb-4">
				<div class="widget-header">Tình trạng xét duyệt</div>
				<div class="widget-content">
					<div class="d-flex align-items-center gap-2">
					{foreach from=$leave_approver_arrs item=item name=item key=tp}
						{if $tp eq 'curator'}
							{assign var=tp_title value='Người phụ trách'}
						{elseif $tp eq 'head_of_dep'}
							{assign var=tp_title value='Trưởng phòng'}
						{elseif $tp eq 'director_of_dep'}
							{assign var=tp_title value='Giám đốc bộ phận'}
						{elseif $tp eq 'hrad'}
							{assign var=tp_title value='Hành chính nhân sự'}
						{elseif $tp eq 'director'}
							{assign var=tp_title value='Ban giám đốc'}
						{/if}
						<div class="gbox flex-fill border text-center p-2">
							<div class="icon mb-2">
								{if $item.approval_status eq '0'}
								<i class="bx bx-time fs-3"></i>
								{elseif $item.approval_status eq '1'}
								<i class="bx text-success fs-3 bx-check-circle"></i>
								{elseif $item.approval_status eq '2'}
								<i class="bx bx-no-entry text-danger fs-3"></i>
								{/if}
							</div>
							<p class="fs-12 text-muted mb-1">{$tp_title}</p>
							<h5 class="fs-6 mb-0">{$clsProfile->getFullName($item.profile_id)}</h5>
						</div>
					{/foreach}
					</div>
				</div>
			</div>
		
			<div class="widget-block">
				<div class="widget-header">Thông tin</div>
				<div class="widget-content">
					<table class="clientssummarystats" width="100%">
						<tr>
							<td class="text-right" width="25%">Mã</td>
							<td class="text-left">{$oneTakeLeave.code}</td>
						</tr>
						<tr>
							<td class="text-right">Ví trí</td>
							<td class="text-left">{$oneTakeLeave.position}</td>
						</tr>
						<tr>
							<td class="text-right">Nhân viên</td>
							<td class="text-left">{$clsProfile->getFullName($oneTakeLeave.user_id)}</td>
						</tr>
						<tr>
							<td class="text-right">Ngày đăng ký</td>
							<td class="text-left">{$clsISO->convertTimeToText($oneTakeLeave.start_date)}</td>
						</tr>
						<tr>
							<td class="text-right">Bắt đầu</td>
							<td class="text-left">{$clsISO->convertTimeToText($oneTakeLeave.end_date, true)}</td>
						</tr>
						<tr>
							<td class="text-right" style="vertical-align:top">Lý do nghỉ phép</td>
							<td class="text-left">{$oneTakeLeave.reason|nl2br}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		
	</div>
</div>
		
