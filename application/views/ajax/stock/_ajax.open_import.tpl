<div class="modal-dialog">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cập nhật bảng hàng</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="table-wrapper">
				<table width="100%" class="table table-bordered">
				{if !empty($list_group_projects)}
					{foreach name=i from=$list_group_projects item = _oProject}
					{assign var = list_groups value = $_oProject.list_groups}
					{if !empty($list_groups)}
						<tr>
							{if $deviceType ne 'phone'}
							<td width="10%" class="bg-lighter text-center">{$smarty.foreach.i.iteration}</td>{/if}
							<td class="bg-lighter" colspan="3">
								<strong>{$_oProject.title}</strong>
							</td>
						</tr>
						{foreach name=k from=$list_groups item = _oI}
						<tr class="text-nowrap">
							{if $deviceType ne 'phone'}
							<td class="text-center">{$smarty.foreach.i.iteration}. {$smarty.foreach.k.iteration}</td>{/if}
							<td class="text-left">{$_oI.title}</td>
							<td class="text-center" width="25%"><a target="_blank" class="btn btn-outline-default" href="https://docs.google.com/spreadsheets/d/{$_oI.spreadsheetId}/edit#gid=0">Link <i class="bx bx-link-external fs-12"></i></a></td>
							<td width="10%" class="text-center"><button{if !empty($_oI.spreadsheetId)}{else} disabled{/if} stock_type="{$_oI.type_id}" project_id="{$_oProject.project_id}" spreadsheetId="{$_oI.spreadsheetId}" onClick="$Core.global.stock.start_import(this, event)" class="btn btn-primary">Crawl</button></td>
						</tr>
						{/foreach}
					{/if}
					{/foreach}
				{/if}
				</table>
			</div>
		</div>
	</form>
</div>
