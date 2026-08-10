<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header d-flex align-items-center justify-content-between">
			<div class="modal-header__left">
				<h5 class="modal-title">Báo cáo {$oneReport.report_code}{if $oneReport.user_id eq $profile_id} <a class="text-body mr-1" href="javascript:void(0);" report_id="{$report_id}" onClick="$Core.global.report.open(this, event);" title="Sửa báo cáo" tp="_edit">{$clsISO->makeIcon('bx-pencil')}</a>{/if}<br />
					<span class="text-danger fs-13 mr-2">
						{$clsISO->makeIcon('bx-user-plus', $clsProfile->getFullName($oneReport.user_id,$oneProfile))}
					</span>
					<span class="text-danger fs-13">
						{$clsISO->makeIcon('bx-alarm-add', $clsISO->convertTimeToText($oneReport.report_date))}
					</span>
				</h5>
				<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
		</div>
		<div class="modal-body scroller">
			<div class="widget-block">
				<div class="widget-header">Thông tin báo cáo</div>
				<div class="widget-content mb-3">
					<div class="table-wrapper">
						<table class="table table-bordered">
							<thead><tr>
								{if $deviceType ne 'phone'}
								<th width="3%" class="align-center bg-lighter text-center">STT</th>{/if}
								<th class="align-center bg-lighter text-left">Tiêu chí</th>
								<th class="align-center bg-lighter text-center">Kết quả</th>
								{if $deviceType ne 'phone' && 1==2}
								<th width="80px" class="align-center bg-lighter text-center">Đơn vị</th>
								{/if}
							</tr></thead>
							{foreach from=$list_cols name=i item = _oF}
							{assign var = _prop_id value = $_oF.property_id}
							<tr>
								{if $deviceType ne 'phone'}
								<td class="text-center">{$smarty.foreach.i.iteration}</td>{/if}
								<td class="text-left">{$_oF.title|strip_tags}</td>
								<td class="text-center">
									<strong>{$clsReport->getValue($_prop_id, $report_store, 0)}</strong> {$_oF.unit_name}
								</td>
								{if $deviceType ne 'phone' && 1==2}
								<td class="text-center">{$_oF.unit_name}</td>
								{/if}
							</tr>
							{/foreach}
						</table>
					</div>
					{if !empty($list_attachments)}
					<div class="small mt-2">File đính kèm</div>
					<div class="MultiFile-preview my-1">
						{foreach from=$list_attachments item = _OF}
						<div class="MultiFile-label">
							<a class="MultiFile-title" href="{$_OF.url}" data-fancybox>{$clsISO->formatFileName($_OF.name)}</a>
						</div>	
						{/foreach}
					</div>
					{/if}
				</div>
			</div>
			<div class="widget-block">
				<div class="widget-header">Ghi chú</div>
				<div class="widget-content">
					<form class="frmIssue mb-2" name="" action="">
						<textarea class="form-control" name="content" rows="2" for_id="{$report_id}" clsTable="Report" note_id="" onkeydown="$Core.helper.enter_notes(this,event)" placeholder="Nhập ghi chú"></textarea>
						<div class="form-text">Nhấn Ctrl+Enter để xuống dòng. Nhấn Enter để lưu lại</div>
					</form>
					<div class="mb-2">
						<span class="badge bg-grayter text-body fs-11">Danh sách ghi chú</span>
					</div>
					<div class="holder_notes_{$report_id}">
						<div class="loader p-5 text-center">Loading...</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>