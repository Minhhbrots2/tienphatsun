<div class="container-xxl flex-grow-1 pt-0 container-p-y pt-2">
	<div class="d-flex mb-3 flex-wrap justify-content-between align-items-center">
		<div class="sVzLpANGCk mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1"><span>Quản lý mục tiêu</span></h4>
			<p class="text-muted mb-0">Quản lý chi tiết mục tiêu công việc</p>
		</div>
		<div class="njcrCZeanR d-flex gap-2">
			<button type="button" onClick="$Core.global.issue.open_target(this, event)" 
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
			{if $view_by eq 'table'}
			<div class="table-container no-shadow overflow-x-auto{if $deviceType ne 'phone'} text-nowrap{/if}">
				<table cellpadding="0" cellspacing="0" class="table table-striped mb-0" width="100%">
					<thead><tr>
						<th class="align-center h-px-35 bg-lighter">Tháng</th>
						<th class="align-center h-px-35 bg-lighter">Tên mục tiêu</th>
						<th class="align-center h-px-35 text-center bg-lighter">Công việc</th>
						<th class="align-center h-px-35 text-left bg-lighter">Tình trạng</th>
						<th class="align-center h-px-35 text-left bg-lighter">Thời gian</th>
						<th class="align-center h-px-35 bg-lighter">Uư tiên</th>
						<th class="align-center h-px-35 bg-lighter w-px-75">Tiến độ</th>
						<th class="align-center h-px-35 bg-lighter w-px-125">Ngày tạo</th>
						<th class="align-center h-px-35 bg-lighter" width="40px"></th>
					</tr></thead>
					<tbody class="holder_issue_target">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			{else if $view_by eq 'month'}
			<div class="holder_issue_target">
				Loading...
			</div>
			{/if}
		</div>
	</div>
</div>