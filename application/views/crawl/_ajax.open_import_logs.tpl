<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
			<h3 class="modal-title"><strong>Lịch sử cập nhật {$clsProperty->getTitle($agency_id)}</strong></h3>
		</div>
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="table-container no-shadow overflow-auto" style="max-height:calc(100vh - 120px)">
					<table class="table table-bordered dragable installed" cellpadding="0" cellspacing="0" width="100%">
						<thead class="position-sticky top-0 zindex-3 bg-lighter" style="background: #f5f7f8 !important">
							<tr>
								<th class="align-center bg-lighter h-px-40 zindex-3" class="text-center" colspan="{if $deviceType ne 'phone'}4{else}3{/if}" style="border-right: 0">	
									<div class="d-flex align-items-center gap-1 justify-content-center">
										<div class="d-flex align-items-center gap-1 text-center">
											<span class="d-block border rounded-pill w-px-15 h-px-15 bg-danger"></span>
											<span class="fs-11">Lỗi không đọc được file</span>
										</div>
										<div class="d-flex align-items-center gap-1 text-center">
											<span class="d-block border rounded-pill w-px-15 h-px-15 bg-warning"></span>
											<span class="fs-11">File thay đổi</span>
										</div>
										<div class="d-flex align-items-center gap-1 text-center">
											<span class="d-block border rounded-pill w-px-15 h-px-15 bg-success"></span>
											<span class="fs-11">Thành công</span>
										</div>
									</div>
								</th>
							</tr>
							<tr>
								{if $deviceType ne 'phone'}
								<th class="align-center bg-lighter h-px-40 zindex-3" width="5%">No.</th>
								{/if}
								<th class="align-center bg-lighter h-px-40 zindex-3" width="25%">Ngày</th>
								<th class="align-center bg-lighter h-px-40 zindex-3">Người cập nhật</th>
								<th class="align-center bg-lighter h-px-40 zindex-3" class="text-center">Nguồn cập nhật</th>
							</tr>
						</thead>
						{if !empty($list_logs)}
							{foreach name=i from=$list_logs item = _oLog}
								<tr {if $_oLog.result_type eq "read_speadsheet" || $_oLog.result_type eq 'copy_speadsheet'}
										class="bg-danger text-white nohover cursor-pointer"
									{else if $_oLog.result_type eq 'change_field'}
										class="bg-warning text-white nohover cursor-pointer"
									{else}
										class="bg-success text-white nohover cursor-pointer"
									{/if} title='{$_oLog.title_log}'>
									{if $deviceType ne 'phone'}
										<td class="text-center h-px-40" style="background: inherit !important">{$smarty.foreach.i.iteration}</td>
									{/if}
									<td class="text-left h-px-40 text-nowrap" style="background: inherit !important">{$clsISO->convertTimeToText($_oLog.time, true)}</td>
									<td class="text-left h-px-40">{$_oLog.full_name}</td>
									<td class="text-center h-px-40">{$_oLog.type_name}</td>
								</tr>
							{/foreach}
						{else}
							<tr>
								<td colspan="{if $deviceType ne 'phone'}4{else}3{/if}" class="text-center">
									<p>Chưa có lịch sử cập nhật nào!</p>
								</td>
							</tr>
						{/if}
					</table>				
				</div>
			</div>
		</form>
	</div>
</div>