<div class="modal-dialog modal-dialog-centered modal-ipad-xl">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header border-bottom d-flex align-items-center justify-content-between">
			<h5 class="modal-title" id="modalTopTitle">Thêm mới công việc</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			{if $parent_id gt '0'}
			<div class="rounded-2 p-3 mb-2 bg-lightest">
				<h3 class="mb-2 fs-6">Công việc cha:</h3>
				{$clsIssue->getTree($parent_id)}
			</div>
			{/if}
			<div class="form-group mb-2">
				<label class="form-label mb-1">Tên công việc</label>
				<input type="text" class="form-control fw-bold autofocus required" data-val-required="Bạn chưa nhập vào tên công việc" name="title" maxlength="255" placeholder="Tên công việc" />
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label class="form-label mb-1">Thời gian thực hiện</label>
					<input type="datetime-local" class="form-control" name="start_date" placeholder="Từ ngày" value="{$start_date|date_format:'%Y-%m-%dT%H:%M'}" />
				</div>
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label class="form-label mb-1">tới</label>
					<input type="datetime-local" class="form-control" name="end_date" placeholder="Tới ngày" value="{$end_date|date_format:'%Y-%m-%dT%H:%M'}" />
				</div>
				<div class="col-12 col-md-6">
					<label class="form-label mb-1">Người thực hiện</label>
					<select class="iso-selectizeNotSearch required w-100" placeholder="Nhân viên" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" name="assign_to_id" data-optgroup="false">
						{if $action eq '_edit'}
							<option value="{$oneBilling.staff_id}" selected="selected">{$clsProfile->getIndentity($oneBilling.staff_id)}</option>
						{else}
							<option value="{$profile_id}" selected="selected">{$oneProfile.code}-{$oneProfile.full_name}</option>
						{/if}
					</select>
				</div>
			</div>
			<div class="form-group d-none mb-2">
				<label class="form-label mb-1">Màu công việc</label>
				<div class="d-flex mt-3 pl-3 gap-4">
					{foreach from = $list_colors item = _oColor}
					<label class="el-radio-color mr-3">
						<input name="color" style="color:{$_oColor}" type="radio" />
					</label>
					{/foreach}
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Loại công việc</label>
					{assign var = toId value = $clsISO->getUniqid()}
					<select id="{$toId}" class="form-control form-select required" name="type_id">
						{$clsProperty->getSelectByProperty('_ISSUE_TYPE',0)}
					</select>
				</div>
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Tình trạng</label>
					{assign var = toId value = $clsISO->getUniqid()}
					<select id="{$toId}" class="form-control form-select required" name="status_id">
						{$clsProperty->getSelectByProperty('_ISSUE_STATUS',0)}
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label mb-1">Độ ưu tiên</label>
					{assign var = toId value = $clsISO->getUniqid()}
					<select id="{$toId}" class="form-control form-select required" name="priority_id">
						{$clsProperty->getSelectByProperty('_ISSUE_PRIORITY',0)}
					</select>
				</div>
			</div>
			<div class="form-group bg-lighter rounded-2 p-2 mb-2">
				<div class="dropdown">
					<!-- onClick="$Core.issue.add_target(this, event)" -->
					Mục tiêu công việc: <a href="javascript:void(0)" class="badge issue_target_{$uid} text-uppercase bg-label-secondary dropdown-toggle" 
						data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">Thêm mục tiêu</a>
					<div class="dropdown-menu w-px-350" data-popper-placement="bottom-start">
						<h6 class="dropdown-header mt-2 text-uppercase">Thêm mục tiêu công việc</h6>
						<div class="dropdown-body py-2 px-3">
							{assign var = _toId value = $clsISO->getUniqid()}
							<div class="form-group mb-2">
								<label class="form-label mb-1">Chọn mục tiêu</label>
								<div class="clearfix"></div>
								<select toId="{$_toId}" onChange="$Core.issue.load_select_target(this, event)" 
									class="form-control form-select" placeholder="Phòng ban" name="department_id">
									{if $clsISO->checkPermissionGroup('DIRECTOR')}
										{$clsISO->getSelectByPropertyTypeNotTitle('_DEPARTMENT', $department_id)}
									{else}
									<option value="{$department_id}" selected>{$clsProperty->getTitle($department_id)}</option>
									{/if}
								</select>
							</div>
							<div class="form-group mb-3">
								<label class="form-label mb-1">Chọn mục tiêu</label>
								<div id="issue_target_{$_toId}" class="issue_target_{$_toId}">
									<select name="issue_target_id" class="iso-selectizeNotSearch" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=get_issue_target&department_id={$oneProfile.department_id}" 
										data-optgroup="false" placeholder="Chọn mục tiêu công việc"></select>
								</div>
							</div>
							<button type="button" uid="{$uid}" onClick="$Core.issue.add_target(this, event)" issue_id="{$issue_id}" 
								class="btn btn-primary">Thêm mục tiêu</button>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Người tham gia</label>
				<div class="clearfix"></div>
				<select class="slb_participants" multiple="multiple" name="participants[]" 
				placeholder="Tên công việc" >
					{if !empty($list_staffs)}
						{foreach from = $list_staffs item = _oStaff}
						<option value="{$_oStaff.profile_id}">{$_oStaff.full_name}</option>
						{/foreach}
					{/if}
				</select>
				<div class="clearfix"></div>
				<div class="d-inline-block pt-2 issue_participants_{$issue_id}"></div>
			</div>
			<div class="widget-block mb-2 collapsed">
				<div onClick="$Core.helper.toggle_block(this,event)" class="widget-header">Thông tin thêm</div>
				<div class="widget-content">
					<div class="form-row">
						<div class="col-12 col-md-6 mb-2 mb-lg-0">
							<label class="form-label mb-1">Khách hàng</label>
							<div class="input-group input-group-merge">
								<div class="form-control p-0 border-0">
									{assign var = uid value = $clsISO->getUniqid()}
									<select placeholder="Chọn khách hàng" name="customer_id" id="{$uid}" class="iso-selectizeNotSearch required w-100" data-url="{$PCMS_URL}/index.php?mod=home&act=list_customer" data-optgroup="false">
									{if $action eq '_edit'}
									<option value="{$oneBilling.customer_id}" selected>{$clsCustomer->getName($oneBilling.customer_id)}</option>
									{/if}
									</select>
								</div>
								<button type="button" uid="{$uid}" onClick="open_customer(this, event)" class="btn btn-outline-primary">+</button>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label mb-1">Dự án</label>
							<select{if $action eq '_edit'} disabled{/if} placeholder="Chọn dự án" class="iso-selectizeNotSearch required w-100" data-url="{$PCMS_URL}/index.php?mod=home&act=list_project" name="project_id" onChange="load_holder_stock(this)" data-optgroup="false">
								{if $action eq '_edit'}
								<option value="{$oneBilling.project_id}" selected="selected">
									{$clsProject->getTitle($oneBilling.project_id)}
								</option>
								{/if}
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				{assign var = editorId value = $clsISO->getUniqid()}
				<label class="form-label mb-1">Nội dung</label>
				<textarea id="{$editorId}" class="form-control isoTextArea" rows="2" height="150px" cols="255" data-name="content" placeholder="Tên công việc" /></textarea>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="parent_id" value="{$parent_id}" />
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Đóng</button>
			<button type="button" issue_id="{$issue_id}" issue_type="{$issue_type}" class="btn btn-primary" 
			title="Lưu lại" onClick="$Core.global.issue.pop_save_issue(this, event)"><i class="bx bx-check"></i> Lưu lại</button>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	.issue_participant{
		float:left;
		padding:3px;
		font-size:12px;
		margin:0 3px 3px 0;
		border:1px solid #DDD;
		border-radius:30px;
		-moz-border-radius:30px;
		-webkit-border-radius:30px;
	}
</style>
{/literal}