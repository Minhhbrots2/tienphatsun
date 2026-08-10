<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Lịch sử cập nhật {$clsProperty->getTitle($agency_id)}</strong></h3>
		</div>
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<table class="table" width="100%">
					<thead><tr>
						<th width="5%">No.</th>
						<th width="25%">Ngày</th>
						<th>Người cập nhật</th>
						<th class="text-center">Nguồn cập nhật</th>
					</tr></thead>
					{if !empty($list_logs)}
						{foreach name=i from=$list_logs item = _oLog}
						<tr>
							<td class="text-center">{$smarty.foreach.i.iteration}</td>
							<td class="text-left">{$clsISO->convertTimeToText($_oLog.date, true)}</td>
							<td class="text-left">{$_oLog.full_name}</td>
							{if $_oLog.from_site eq "_admin"}
								<td class="text-center">Admin</td>
							{else}
								<td class="text-center">CA.FH</td>
							{/if}
						</tr>
						{/foreach}
					{else}
						<tr>
							<td colspan="4" class="text-center">
								<p>Chưa có lịch sử cập nhật nào!</p>
							</td>
						</tr>
					{/if}
				</table>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="start_import_file(this, event)" 
				tp="{$tp}" stock_type="{$stock_type}" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}">{if $tp eq 'blank'}Upload{else}Import{/if}</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>