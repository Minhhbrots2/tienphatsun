<div class="modal-dialog modal-dialog-centered">
	<form class="modal-content" id="frmIssue" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title">Lịch sử truy cập</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if !empty($list_reports)}
			<div class="form-group mb-2">
				<div class="table-wrapper">
					<table class="table table-bordered">
						<thead><tr>
							{if $deviceType ne 'phone'}
							<th width="3%" class="align-center bg-lighter text-center">No.</th>{/if}
							<th class="align-center bg-lighter text-left">URL truy cập</th>
							<th class="align-center bg-lighter text-center">Thời gian</th>
						</tr></thead>
						{foreach from=$list_reports name=i item = _oTime}
						<tr>
							{if $deviceType ne 'phone'}
							<td class="text-center">{$smarty.foreach.i.iteration}</td>{/if}
							<td class="text-left">{$_oTime.url}</td>
							<td>{$clsISO->convertTimeToText($_oTime.reg_date, true)}</td>
						</tr>
						{/foreach}
					</table>
				</div>
			</div>
			{/if}
		</div>
	</form>
</div>
