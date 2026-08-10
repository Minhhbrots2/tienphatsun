{if $template_type eq '_form'}
<div class="modal-dialog modal-ipad">
	<form class="modal-content" id="frmIssue" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">
				{if $action eq '_add'}Thêm{else}Sửa{/if} báo cáo vắng mặt {$smarty.const.BRAND_NAME}<br />
				<span class="text-danger fs-13">
					{$clsISO->makeIcon('bx-user-plus', 'Người tạo: ')}
					{$clsProfile->getFullName($profile_id,$oneProfile)}
				</span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-3">
				<div class="col-12 col-md-4 mb-3 mb-lg-2">
					<label class="form-label">Ngày báo cáo</label>
					<input type="date" name="worktime_date" class="form-control required" 
					placeholder="dd/mm/yy" value="{if $action eq '_edit'}{$oneWorktime.worktime_date|date_format:'%Y-%m-%d'}{else}{$smarty.now|date_format:'%Y-%m-%d'}{/if}">
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label">Team báo cáo</label>
					<input type="text" name="department_name" readonly class="form-control required" value="{$clsProperty->getTitle($department_id)}">
				</div>
			</div>
			<div class="form-group mb-3">
				<div class="alert alert-warning alert-dismissible" role="alert">
					Mặc địn là tất cả nhân viên trong Team đi làm đầy đủ
				</div>
				{if $deviceType eq 'phone'}
					{if !empty($list_staffs)}
						{foreach name=i from=$list_staffs item = _oStaff}
							{assign var = _profile_id value = $_oStaff.profile_id}
							{assign var = gid value = $clsISO->getUniqid()}
							<div id="{$gid}" class="gbox mb-2">
								<div class="gbox-title">
									{$clsProfile->getIndentityV4($_oStaff.profile_id, $_oStaff)}
								</div>
								<div class="gbox-body">
									<div class="form-group d-flex align-items-center mb-2">
										<label class="col-form-label mr-2">Vắng mặt</label>
										<label class="switch">
											<input gid="{$gid}" name="content[{$_profile_id}][status]" onchange="$Core.worktime.set_worktime(this,event)" type="checkbox"{if $_oStaff.status eq '1'} checked{/if} value="1">
											<span class="slider round"></span>
										</label>
									</div>
									<div class="form-group mb-2">
										<div class="form-floating">
											<select id="type_{$gid}" name="content[{$_profile_id}][type]"{if $_oStaff.status eq '0'} disabled{/if} class="form-control {$gid} form-select">
												<option{if $_oStaff.type eq '_ALLDAY'} selected{/if} value="_ALLDAY">Cả ngày</option>
												<option{if $_oStaff.type eq '_MORNING'} selected{/if} value="_MORNING">Buổi sáng</option>
												<option{if $_oStaff.type eq '_AFTERNOON'} selected{/if} value="_AFTERNOON">Buổi chiều</option>
											</select>
											<label for="type_{$gid}">Loại</label>
										</div>
									</div>
									<div class="form-group">
										<div class="form-floating">
											<input id="reason_{$gid}" name="content[{$_profile_id}][reason]"{if $_oStaff.status eq '0'} disabled{/if} placeholder="Lý do vắng mặt" class="form-control {$gid}" value="{$_oStaff.reason}" />
											<label for="reason_{$gid}">Lý do</label>
										</div>
									</div>
								</div>
							</div>
						{/foreach}
					{/if}
				{else}
					<div class="table-responsive freeze-table dragscroll text-nowrap overflow-y">
						<table width="100%" class="table table-bordered">
							<thead><tr>
								<th class="" width="5%">No.</th>
								<th>Nhân viên</th>
								<th>Vắng mặt</th>
								<th>Loại</th>
								<th>Lý do</th>
							</tr></thead>
							{if !empty($list_staffs)}
								{foreach name=i from=$list_staffs item = _oStaff}
								{assign var = _profile_id value = $_oStaff.profile_id}
								{assign var = gid value = $clsISO->getUniqid()}
								<tr id="{$gid}">
									<td data-label="No." class="text-center">
										{$smarty.foreach.i.iteration}
									</td>
									<td data-label="Nhân viên">
										{$clsProfile->getIndentityV4($_oStaff.profile_id, $_oStaff)}
									</td>
									<td data-label="Vắng mặt" class="text-center">
										<label class="switch">
											<input gid="{$gid}" name="content[{$_profile_id}][status]" onchange="$Core.worktime.set_worktime(this,event)" type="checkbox"{if $_oStaff.status eq '1'} checked{/if} value="1">
											<span class="slider round"></span>
										</label>
									</td>
									<td data-label="Loại">
										<select name="content[{$_profile_id}][type]"{if $_oStaff.status eq '0'} disabled{/if} class="form-control {$gid} form-select">
											<option{if $_oStaff.type eq '_ALLDAY'} selected{/if} value="_ALLDAY">Cả ngày</option>
											<option{if $_oStaff.type eq '_MORNING'} selected{/if} value="_MORNING">Buổi sáng</option>
											<option{if $_oStaff.type eq '_AFTERNOON'} selected{/if} value="_AFTERNOON">Buổi chiều</option>
										</select>
									</td>
									<td data-label="Lý do">
										<input name="content[{$_profile_id}][reason]"{if $_oStaff.status eq '0'} disabled{/if} placeholder="Lý do vắng mặt" class="form-control {$gid}" value="{$_oStaff.reason}" />
									</td>
								</tr>
								{/foreach}
							{/if}
						</table>
					</div>
				{/if}
				<div class="form-group">
					<label for="deposit_date" class="form-label">Ghi chú</label>
					<textarea class="form-control" name="staff_notes" placeholder="Ghi chú..." rows="2">{if $action eq '_edit'}{$oneWorktime.staff_notes}{/if}</textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			<input type="hidden" name="department_id" value="{$department_id}" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" worktime_id="{$worktime_id}" 
			onClick="$Core.worktime.save(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
{else}
<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center justify-content-between">
				<div class="modal-header__left">
					<h5 class="modal-title" id="modalTopTitle">Thông tin báo cáo {$oneReport.report_code}<br />
						<span class="text-muted fs-13 mr-2">
							{$clsISO->makeIcon('bx-user-plus', 'Người tạo: ')}
							{$clsProfile->getFullName($oneReport.user_id,$oneProfile)}
						</span>
						<span class="text-muted fs-13">
							{$clsISO->makeIcon('bx-alarm-add', 'Ngày tạo: ')}
							{$clsISO->convertTimeToText($oneReport.report_date)}
						</span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>
			<div class="modal-body scroller">
				<div class="widget-block">
					<div class="widget-header">Thông tin báo cáo</div>
					<div class="widget-content mb-3">
						{$oneReport.content}
					</div>
				</div>
				<div class="widget-block">
					<div class="widget-header">Ghi chú</div>
					<div class="widget-content">
						<form class="frmIssue mb-5" name="" action="">
							<textarea class="form-control" name="content" rows="2" placeholder="Nhập ghi chú"></textarea>
							<div class="clearfix mt-2">
								<button type="button" tp="_create" class="btn btn-outline-primary" 
								for_id="{$report_id}" clsTable="Report" note_id="" onClick="$Core.helper.save_notes(this,event)">Thêm</button>
							</div>
						</form>
						<small>Danh sách ghu chú</small>
						<div class="holder_notes_{$report_id}">
							<div class="loader p-5 text-center">Loading...</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
</div>
{/if}