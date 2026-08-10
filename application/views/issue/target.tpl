<div class="container-xxl flex-grow-1 pt-0 container-p-y">
	<div class="d-flex py-2 flex-wrap justify-content-between align-items-center">
		<div class="vRHjwnrkGa d-flex gap-2">
			<a href="{$clsISO->getLink('issue')}" class="back" title="Quay lại">
				<img src="{$smarty.const.ICON_BACK}" />
			</a>
			<div class="ysXwpeiJqL">
				<h4 class="fw-bold mb-1">Quản lý mục tiêu</h4>
				<small class="text-muted fs-13">Quản lý chi tiết mục tiêu công việc</small>
			</div>
		</div>
		<div class="njcrCZeanR d-flex gap-2">
			<button type="button" onClick="$Core.issue.open_target(this, event)" 
				issue_id="0" parent_id="0" class="btn btn-outline-primary d-flex gap-1 align-items-center">
				<i class="bx bx-plus"></i> Thêm mới
			</button>
			<div class="btn-group" role="group" aria-label="Hiển thị">
				{assign var = toId value = $clsISO->getUniqid()}
				<input type="radio" call_from="issue_target" onchange="$Core.issue.set_view(this,event);" class="btn-check" 
					name="view" id="{$toId}_list" value="table"{if $view_by eq 'table'} checked{/if}>
				<label class="btn btn-icon btn-outline-default" for="{$toId}_list"><i class="bx bx-table"></i></label>
				<input type="radio" call_from="issue_target" onchange="$Core.issue.set_view(this,event);" 
					class="btn-check" name="view" id="{$toId}_grid"{if $view_by eq 'month'} checked{/if} value="month">
				<label class="btn btn-icon btn-outline-default" for="{$toId}_grid"><i class="bx bx-grid"></i> </label>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="holder_issue_target">
						
			</div>
		</div>
	</div>
</div>