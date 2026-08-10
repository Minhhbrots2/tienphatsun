{if $clsISO->checkPermissionGroup('SALE')}
<table border="0" class="table table-responsive table-striped mb-4" width="100%">
	<thead><tr>
		<th class="align-center" width="20%">Team báo cáo</th>
		<th class="align-center text-left">Nội dung</th>
		<th class="align-center text-center">Ghi chú</th>
		<th class="align-center text-right" width="120px">Ngày báo cáo</th>
		<th class="align-center text-right" width="180px">Ngày cập nhật</th>
		<th width="40px"></th>
	</tr></thead>
	{$htmlTable}
</table>
<div class="clearfix"></div>
<div id="pager_worktime"></div>
{else}
	{$htmlTable}
{/if}