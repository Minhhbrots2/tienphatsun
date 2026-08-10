{$scriptJs}
<script type="text/javascript">
	var issue_type = '{$issue_type}';
</script>
<div class="container-xxl flex-grow-1 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-3">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Quản lý công việc</span></h4>
			<p class="text-muted mb-0">Kế hoạch công việc của bạn</p>
		</div>
		<div class="p__right d-flex">
			{if $deviceType ne 'phone'}
				{$core->getBlock('issue_search')}
			{/if}
			<button type="button" onClick="open_issue(this, event)" issue_id="0" parent_id="0" class="btn btn-outline-primary ml-2">Thêm mới</button>
		</div>
	</div>
    <div class="app-kanban">
        <!-- Add new board -->
        <!-- Kanban Wrapper -->
        <div class="kanban-wrapper ps">
            <div class="kanban-container d-grid gap-3" id="kanban-canvas"></div>
        </div>
    </div>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$Core.issue.load_issue_kanban("",{});		
	})
</script>
{/literal}