{$scriptJs}
<script type="text/javascript">
	var issue_type = '{$issue_type}';
</script>
<div class="container-xxl flex-grow-1 pt-0 container-p-y issue_page">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-3">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Quản lý công việc{if $issue_type eq 'dev'} DEV{/if}</span></h4>
			<p class="text-muted mb-0">Kế hoạch công việc của bạn</p>
		</div>
		<div class="p__right d-flex align-items-center gap-1">
			{if $deviceType ne 'phone'}
				{$core->getBlock('issue_search')}
			{/if}
			<div class="btn-group">
				<button type="button" onClick="$Core.global.issue.open_issue(this, event)" 
					issue_id="0" parent_id="0" class="btn btn-icon btn-outline-primary"><i class="bx bx-plus"></i></button>
				<a type="button" href="/issue-target.html" class="btn btn-icon btn-outline-primary"><i class="bx bx-target-lock"></i></a>
			</div>
		</div>
	</div>
	{if $_ss_view eq "grid"}
	<div class="card">
		{if $deviceType eq 'phone'}
		<div class="card-header d-flex flex-wrap align-items-center justify-content-center">
			{$core->getBlock('issue_search')}
		</div>
		{/if}
		{foreach name=i from=$list_issue_blocks key=block item = _oBlock}
		<h5 class="card-header position-relative">{$clsISO->makeIcon($_oBlock.icon, $_oBlock.title)}</h5>
		<div class="card-body">
			<div block="{$block}" delay="{$_oBlock.delay}" class="tableIssue overflow-x-auto{if $deviceType ne 'phone'} text-nowrap{/if}">
				<table class="table table-striped mb-1" width="100%">
					<thead><tr>
						{if $deviceType eq 'phone'}
						<!-- <th class="align-center w-px-20"></th> -->
						<th width="60%" class="align-center">Tên công việc</th>
						<th width="10%" class="align-center">Giao{$clsISO->makeIcon('bx-right-arrow-alt')}Nhận</th>
						{else}
						<th class="align-center w-px-20"></th>
						<th class="align-center">Tên công việc</th>
						<th class="align-center text-right">Tình trạng</th>
						<th class="align-center w-px-150">TG. dự kiến</th>
						<th class="align-center w-px-50">N.giao</th>
						<th class="align-center w-px-50">N.nhận</th>
						<th class="align-center">Uư tiên</th>
						<th class="align-center w-px-75">Tiến độ</th>
						<th class="align-center w-px-125">Ngày tạo</th>
						<th class="align-center" width="40px"></th>
						{/if}
					</tr></thead>
					<tbody class="holder_issue_{$block}">
						<tr>
							<td class="text-center" colspan="9">Loading...</td>
						</tr>
					</tbody>
				</table>
			</div>
			<input type="hidden" name="hid_current_page_{$block}" value="1" />
			<div id="pager_{$block}" class="mt-2 ui-pagination sample-pagination"></div>
		</div>
		{if !$smarty.foreach.i.last}
			<hr class="my-2" />
		{/if}
		{/foreach}
	</div>
	{else}
	<div class="app-kanban">
		<div class="kanban-wrapper ps">
			<div class="kanban-container d-grid gap-2" id="kanban-canvas"></div>
		</div>
	</div>
	{/if}
</div>
{literal}
<style type="text/css">
	.ui-datepicker{
		z-index:9999 !important;
	}
</style>
<script type="text/javascript">
	$(function(){
		$('.tableIssue').each((_i, _elem) => {
			var block = $(_elem).attr('block'),
				delay = $(_elem).attr('delay');
			setTimeout(() => {
				load_issue_globe(block, {});
			}, delay);
			
		});
		$Core.issue.load_issue_kanban("",{});	
	})
</script>
{/literal}