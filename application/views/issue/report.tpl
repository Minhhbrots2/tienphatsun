<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between py-1 mb-2">
		<div class="vRHjwnrkGa d-flex gap-2">
			<a href="{$clsISO->getLink('issue')}" class="back" title="Quay lại">
				<img src="{$smarty.const.ICON_BACK}" />
			</a>
			<div class="ysXwpeiJqL">
				<h4 class="fw-bold mb-1">Thông kê công việc</h4>
				<small class="text-muted fs-13">Thống kê công việc theo các mốc thời gian</small>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body pt-3">
			<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
				{foreach from=$arr_tabs item = _OI key = _oK}
				<li class="nav-item" role="presentation">
					<a href="javascript:void(0)" onClick="$Core.issue.click_href(this, event)" tp="{$_oK}" 
						class="nav-link{if $tp eq $_oK} active{/if}" data-href="/issue/report.html?tp={$_oK}" role="tab">{$_OI}</a>
				</li>
				{/foreach}
			</ul>
			<div class="tab-content px-0 py-2">
				<div class="tableIssue overflow-x-auto {if $deviceType ne 'phone'} text-nowrap{/if}">
					<table class="table table-striped dragable" width="100%">
						<thead><tr>
							<th class="align-center h-px-35">Tên công việc</th>
							<th class="align-center h-px-35 text-right">Tình trạng</th>
							<th class="align-center h-px-35 w-px-150">TG. dự kiến</th>
							<th class="align-center h-px-35 w-px-50">N.giao</th>
							<th class="align-center h-px-35 w-px-50">N.nhận</th>
							<th class="align-center h-px-35">Uư tiên</th>
							<th class="align-center h-px-35 w-px-75">Tiến độ</th>
							<th class="align-center h-px-35 w-px-75">Thời gian</th>
							<th class="align-center h-px-35 w-px-125">Ngày tạo</th>
						</tr></thead>
						<tbody class="holder_report">
							<tr>
								<td class="text-center" colspan="9">Loading...</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- End Bordered Tabs -->
		</div>
	</div>
</div>
<script type="text/javascript">
	var tp = "{$tp}";
</script>
{literal}
<style type="text/css">
	.tableIssue{
		border:1px solid #DDD;
		border-radius:3px;
		-moz-border-radius:3px;
		-webkit-border-radius:3px;
		-khtml-border-radius:3px;
	}
	.tableIssue tr:last-child td{
		border-bottom:0;
	}
</style>
<script type="text/javascript">
	$(function(){
	
		$Core.issue.load_issue_report({'tp':tp});	
	});
</script>
{/literal}