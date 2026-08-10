<div class="modal-dialog modal-lg">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}<br />
				<span class="text-danger fs-12">
					{$clsISO->makeIcon('bx-user-plus', 'Người tạo: ')}
					{$clsProfile->getFullName($profile_id,$oneProfile)}
				</span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Tên chỉ tiêu</label>
				<input type="text" placeholder="Tên chỉ tiêu" name="title" class="form-control required" value="{$oneKPI.title}">
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-md-3">
					<label class="form-label mb-1">Chỉ tiêu lặp theo</label>
					<select name="period" onChange="$Core.kpi.set_loop(this, event)" class="form-control form-select">
						<option{if $oneKPI.period eq 'ALLMONTH'} selected{/if} value="ALLMONTH">Lặp lại hàng tháng</option>
						<option{if $oneKPI.period eq 'MONTH'} selected{/if} value="MONTH">Lặp lại theo tháng</option>
					</select>
				</div>
				<div class="col-12 col-md-2">
					<label class="form-label mb-1">Chọn năm</label>
					<input data-y="{$smarty.now|date_format:'%Y'}" data-m="{$current_month}" type="number" 
					name="year_period" onChange="$Core.kpi.set_year(this, event)" class="form-control required numberonly" value="{$oneKPI.year_period}" />
				</div>
			</div>
			<div class="form-group cMXqMfvNJX{if $oneKPI.period eq 'ALLMONTH'} d-none{/if} mb-2">
				<label class="form-label mb-1">Chọn tháng</label>
				<div class="d-flex align-items-center cMXaBZwAuJ">
					{foreach name=i from=$list_months item = _month}
					<label class="el-checkbox rbLnbPAdeY">
						<input{if $clsISO->checkInArray($oneKPI.month_period, $_month)} checked{/if} class="eNXMGIDuZu" type="checkbox" name="month_period[]"{if $current_month gt $_month && $current_year eq $oneKPI.year_period} disabled{/if} value="{$_month}" />
						<span>{$_month}</span>
					</label>
					{/foreach}
				</div>
			</div>
			<div class="form-group">
				<label for="deposit_date" class="form-label">Chỉ tiêu</label>
				<div class="table-wrapper" style="max-height:360px; overflow-y:auto;">
					<table class="table table-bordered" width="100%" style="table-layout:fixed">
						<thead><tr>
							<th class="align-center bg-lighter text-center" width="8%">No.</th>
							<th class="align-center bg-lighter text-left" width="25%">Tên phòng ban</th>
							<th class="align-center bg-lighter text-left" width="30%">Nhân viên</th>
							<th class="align-center bg-lighter text-left">Chỉ tiêu</th>
							<th class="align-center bg-lighter text-center" width="10%">Tình trạng</th>
						</tr></thead>
						{foreach name=i from=$list_departments item = _oDep}
						{assign var = department_id value = $_oDep.property_id}
						{assign var = list_teams value = $_oDep.teams}
						{if $department_id ne $smarty.const._DEPARTMENT_SALE_ID}
							<tr>
								<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
								<td class="align-center text-left"><strong>{$_oDep.title}</strong></td>
								<td class="align-center text-left">{$clsProfile->getHTMLStaff($department_id)}</td>
								<td class="align-center bZkTAmfvrm">
									<input type="text" name="configs[{$department_id}][total_sale]" onclick="this.select()" 
									class="form-control price-In required numberonly" value="{$configs_arrs[$department_id]['total_sale']}" />
								</td>
								<td class="align-center text-center">
									<label class="switch">
										<input type="checkbox"{if $configs_arrs[$department_id]['status'] eq '1'} checked="checked"{/if} name="configs[{$department_id}][status]" value="1">
										<span class="slider round"></span>
									</label>
								</td>
							</tr>
							{if !empty($list_teams)}
								{foreach from=$list_teams item = _oTeam name = k}
								{assign var = team_id value = $_oTeam.property_id}
								<tr>
									<td class="align-center text-center">{$smarty.foreach.i.iteration}. {$smarty.foreach.k.iteration}</td>
									<td class="align-center text-left">{$_oTeam.title}</td>
									<td class="align-center text-left"></td>
									<td class="align-center bZkTAmfvrm">
										<input type="text" name="configs[{$department_id}][teams][{$team_id}][total_sale]" onclick="this.select()" 
										class="form-control price-In required numberonly" value="{$configs_arrs[{$department_id}]['teams'][$team_id]['total_sale']}" />
									</td>
									<td class="align-center text-center">
										<label class="switch">
											<input type="checkbox"{if $configs_arrs[{$department_id}]['teams'][$team_id]['status'] eq '1'} checked="checked"{/if} name="configs[{$department_id}][teams][{$team_id}][status]" value="1">
											<span class="slider round"></span>
										</label>
									</td>
								</tr>
								{/foreach}
							{/if}
						{/if}
						{/foreach}
					</table>
				</div>
			</div>
		</div>
		<div class="modal-footer justify-content-between align-items-center">
			<div class="d-flex align-items-center gap-2">
				<label class="switch">
					<input type="checkbox"{if $oneKPI.is_team eq '1'} checked="checked"{/if} name="is_team" value="1">
					<span class="slider round"></span>
				</label>
				<span>Hiển thị team</span>
			</div>
			<div class="buttons">
				<button type="button" class="btn btn-outline-secondary d-none d-lg-inline-block" data-bs-dismiss="modal">Đóng</button>
				<button type="button" kpi_id="{$kpi_id}" onClick="$Core.kpi.pop_save_kpi(this, event)" class="btn btn-primary">Lưu lại</button>
			</div>
		</div>
	</form>
</div>