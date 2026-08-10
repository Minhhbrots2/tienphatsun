<div class="modal-dialog modal-standard">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Nhập bảng hàng cho thuê</strong></h3>
		</div>
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<table class="table" width="100%">
					<thead><tr>
						<th class="text-center" width="3%">No.</th>
						<th>Dự án</th>
						<th class="text-center" width="120px">Craw</th>
					</tr></thead>
					{if !empty($list_projects)}
						{foreach name=i from=$list_projects item = _oProject}
						{assign var=project_id value= $_oProject.project_id}
						<tr>
							<td class="text-center">{$smarty.foreach.i.iteration}</td>
							<td class="text-left">{$_oProject.title} <br />
								{if !empty($_oProject.spreadsheetLeasingId)}
								<a href="https://docs.google.com/spreadsheets/d/{$_oProject.spreadsheetLeasingId}/edit" target="_blank">https://docs.google.com/spreadsheets/d/{$_oProject.spreadsheetLeasingId}/edit</a>
								{else}
								<span class="text-muted">{$core->makeIcon('exclamation-triangle','Chưa có file cài đặt')}</span>
								{/if}
							</td>
							<td class="text-center">
								<button onclick="$Core.stock.start_import_leasing(this, event)"{if !empty($_oProject.spreadsheetLeasingId)}{else} disabled{/if} project_id="{$project_id}" spreadsheetLeasingId="{$spreadsheetLeasingId}" class="btn btn-success">Crawl</button>
							</td>
						</tr>
						{/foreach}
					{/if}
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>