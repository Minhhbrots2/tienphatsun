<div class="modal-dialog modal-standard">
	<form method="POST" class="modal-content" onsubmit="return false;">
		<div class="modal-header">
			<h5 class="modal-title">Đăng ký ngân sách chạy Marketing<br />
				<span class="text-fs-12 text-main">
					<i class="bx bx-user"></i> {$clsProfile->getFullName($profile_id, $oneProfile)} - Tháng {$month}
				</span>
			</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<p class="text-muted">Phân bổ ngân sách marketing theo từng dự án và từng kênh quảng cáo trong tháng. </p>
			<div class="alert alert-warning mb-3">
				<i class='bx bx-time'></i> Thời hạn đăng ký  <strong>{$clsISO->convertTimeToText($regis_deadline, true)}</strong><br />
				Vui lòng hoàn tất và cập nhật ngân sách trước thời điểm này để được xét duyệt.
			</div>
			<div class="table-container overflow-x-auto no-shadow mb-2">
				<table cellpadding="0" cellspacing="0" class="table table-bordered mb-0">
					<thead><tr>
						{if $deviceType ne 'phone'}
						<th class="bg-lighter text-center" width="45px" rowspan="2">STT</th>
						{/if}
						<th class="bg-lighter" width="30%" rowspan="2">Dự án</th>
						<th class="text-center h-px-35 bg-lighter" colspan="4">Ngân sách</th>
						<th class="bg-lighter border-bottom-0" width="45px"></th>
					</tr>
					<tr>
						{foreach from=$arr_chanels item = _oT name = i}
						<th class="bg-lighter min-w-px-100 border-end{if $smarty.foreach.i.first} no-sticky{/if} h-px-35 text-center">{$_oT}</th>
						{/foreach}
						<th class="bg-lighter" width="45px"></th>
					</tr></thead>
					<tbody class="holder_regis js__holder_regis">
						{if !empty($list_registers)}
							{foreach from=$list_registers item = _oI name = i}
							{assign var = uid value = $clsISO->getUniqid()}
							{assign var = _more_information value = $_oI.more_information}
							<tr class="js__tr_marketing nohover">
								{if $deviceType ne 'phone'}
								<td class="text-center">{$smarty.foreach.i.iteration}</td>
								{/if}
								<td class="align-center text-left">
									<select{if $is_edit_content eq '0'} disabled{/if} name="tblData[{$uid}][project_mapping_id]" class="form-control form-select js__select-project min-w-px-175" 
										onchange="$Core.marketing.check(this, event)">
										<option value="0">Lựa chọn dự án</option>
										{foreach from=$arr_projects item = _oProject}
										<option{if $_more_information.project_mapping_id eq $_oProject.setting_id} selected{/if} value="{$_oProject.setting_id}">{$_oProject.title}</option>
										{/foreach}
									</select>
								</td>
								{foreach from=$arr_chanels key=_oK item = _oT}
								<td class="align-center js__td-budget text-left">
									<input{if $is_edit_content eq '0'} disabled{/if} placeholder="{$clsISO->getRate()}" name="tblData[{$uid}][budgets][{$_oK}]" 
										class="form-control price-In js__input-budget min-w-px-100" value="{$_oI.$_oK}" />
								</td>
								{/foreach}
								<td class="align-center text-center">
									<button{if $is_edit_content eq '0'} disabled{/if} type="button" class="btn btn-icon btn-sm btn-outline-default" 
										onClick="$Core.marketing.delete_line(this, event)">
										<i class="bx bx-trash"></i>
									</button>
								</td>
							</tr>
							{/foreach}
						{/if}
						{if !empty($arr_rows)}
							{section name = i loop=$arr_rows}
							{assign var = uid value = $clsISO->getUniqid()}
							<tr class="js__tr_marketing nohover">
								{if $deviceType ne 'phone'}
								<td class="text-center">{$arr_rows[i]}</td>
								{/if}
								<td class="align-center text-left">
									<select{if $is_edit_content eq '0'} disabled{/if} name="tblData[{$uid}][project_mapping_id]" class="form-control js__select-project form-select min-w-px-175" 
										onchange="$Core.marketing.check(this, event)">
										<option value="0">Lựa chọn dự án</option>
										{foreach from=$arr_projects item = _oProject}
										<option value="{$_oProject.setting_id}">{$_oProject.title}</option>
										{/foreach}
									</select>
								</td>
								{foreach from=$arr_chanels key=_oK item = _oT}
								<td class="align-center js__td-budget text-left">
									<input{if $is_edit_content eq '0'} disabled{/if} placeholder="{$clsISO->getRate()}" name="tblData[{$uid}][budgets][{$_oK}]" 
										class="form-control price-In min-w-px-100 js__input-budget" />
								</td>
								{/foreach}
								<td class="align-center text-center">
									<button{if $is_edit_content eq '0'} disabled{/if} type="button" class="btn btn-icon btn-sm btn-outline-default" 
										onClick="$Core.marketing.delete_line(this, event)">
										<i class="bx bx-trash"></i>
									</button>
								</td>
							</tr>
							{/section}
						{/if}
						<tr class="js__tr-addline nohover">
							<td colspan="10">
								<button type="button" class="btn btn-sm btn-link cursor-pointer text-muted js__add-line {if $is_edit_content eq '0'} preventDefault{/if}" 
									data-toggle="ripple" uid="{$uid}" onClick="$Core.marketing.add_line(this, event)"><i class="bx bx-plus"></i> Thêm dự án</button
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="d-flex align-items-center mb-2">
				<small class="text-muted">Bạn có thể chỉnh sửa ngân sách cho đến khi hết hạn đăng ký</small>
			</div>
			<div class="p-3 bg-lighter border rounded-2">
				<h5 class="mb-2 text-fs-16"><i class="bx bx-help-circle"></i> Hướng dẫn cập nhật ngân sách</h5>
				<ul class="pl-3 mb-0">
					<li>Mỗi dòng tương ứng với 1 dự án</li>
					<li>Nhập ngân sách cho các kênh sẽ chạy</li>
					<li>Để trống hoặc nhập 0 nếu không triển khai</li>
					<li>Có thể thêm hoặc xoá dự án bất kỳ lúc nào</li>
				</ul>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="month" value="{$month}" />
			<input type="hidden" name="submit" value="register" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button data-toggle="ripple" type="button" onClick="$Core.marketing.do_regis(this, event)" 
				class="btn btn-primary" action="{$action}"{if $is_edit_content eq '0'} disabled{/if} uid="{$uid}">
				{if $action eq '_add'}Đăng ký{else}Cập nhật{/if}
			</button>
		</div>
	</form>
</div>
