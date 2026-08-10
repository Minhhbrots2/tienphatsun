<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<h3 class="modal-title"><strong>Lịch sử cập nhật {$clsProperty->getTitle($agency_id)}</strong></h3>
		</div>
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<table class="table" width="100%">
					<thead><tr>
						<th width="5%">No.</th>
						<th width="30%">Ngày</th>
						<th>Người cập nhật</th>
					</tr></thead>
					{if !empty($list_logs)}
						{foreach name=i from=$list_logs item = _oLog}
						<tr>
							<td class="text-center">{$smarty.foreach.i.iteration}</td>
							<td class="text-left">{$clsISO->convertTimeToText($_oLog.date, true)}</td>
							<td class="text-left">{$_oLog.full_name}</td>
						</tr>
						{/foreach}
					{else}
						<tr>
							<td colspan="3" class="text-center">
								<p>Chưa có lịch sử cập nhật nào!</p>
							</td>
						</tr>
					{/if}
				</table>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
			</div>
		</form>
	</div>
</div>