{if $deviceType ne "phone"}
<div class="card">
	<div class="card-body holder_today pt-3" >
		<div class="table-wrapper overflow-x-auto">
			<table cellpadding="0" cellspacing="0" class="table table-stripped" width="100%">
				<thead><tr>
					<th class="align-center text-left" width="25%">Tiêu đề</th>
					<th class="align-center text-left" width="100px">Mã căn</th>
					<th class="align-center text-left" width="200px">Bắt đầu</th>
					<th class="align-center text-left" width="200px">Kết thúc</th>
					<th class="align-center text-left" width="200px">Người tạo</th>
					<th class="align-center text-left" width="200px">Ngày tạo</th>
					<th class="align-center text-center" width="100px">Hiển thị</th>
					<th class="align-center text-center" width="100px">Lặp lại</th>
					<th class="align-center text-center" width="150px">Tình trạng</th>
					<th class="align-center text-left" width="45px"></th>
				</tr></thead>
				<tbody>
					{if !empty($list_today)}
					{foreach from=$list_today name=i item=today}
						<tr class="tr" id="tr_{$today.today_id}">
							<td class="text-left">
								<a onclick="$Core.today.edit_stock_today(this,event)" data-action="_detail" data-today_id="{$today.today_id}" href="javascript:void(0);">{$today.title}</a>
							</td>
							<td>{$today.stock_code}</td>
							<td class="text-left">
								<i class="material-icons-outlined">more_time</i> 
								{$today.start_date}
							</td>
							<td class="text-left">
								<i class="material-icons-outlined">more_time</i> 
								{$today.end_date}
							</td>
							<td class="text-left">
								{$today.user_name}
							</td>
							<td class="text-left">
								<i class="material-icons-outlined">more_time</i> 
								{$today.reg_date}
							</td>
							<td class="text-center">
								<label class="switch">
								  <input type="checkbox" onchange="$Core.today.status(this, event)" data-field="is_online" today_id="{$today.today_id}" {if $today.is_online eq 1}checked{/if} value="1">
								  <span class="slider round"></span>
								</label>
							</td>
							<td class="text-center">
								<label class="switch">
								  <input type="checkbox" onchange="$Core.today.status(this, event)" data-field="is_repeat" today_id="{$today.today_id}" {if $today.is_repeat eq 1}checked{/if} value="1">
								  <span class="slider round"></span>
								</label>
							</td>
							<td class="text-center">
								{$today.status}
							</td>
							<td class="text-center">
								<div class="dropdown">
									<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>
									</button>
									<div class="dropdown-menu" style="">
										<a class="dropdown-item" onclick="$Core.today.edit_stock_today(this,event)" data-action="_edit" data-today_id="{$today.today_id}" href="javascript:void(0);" data-update_to="tr_{$today.today_id}"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
										<a class="dropdown-item" onclick="$Core.today.edit_stock_today(this,event)" data-action="_dupplicate" data-today_id="{$today.today_id}" href="javascript:void(0);"><i class='bx bx-copy me-1'></i> Nhân bản</a> 
										<a class="dropdown-item" onclick="$Core.today.delete_today(this,event)" data-today_id="{$today.today_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
									</div>
								</div>
							</td>

						</tr>
					{/foreach} 
				{else}
					<tr class="tr">
						{if $clsISO->checkPermission("create_today")}
						<td colspan="6" class="text-center">Danh sách trống</td>
						{else}
						<td colspan="5" class="text-center">Danh sách trống</td>
						{/if}
					</tr>
				{/if}
				</tbody>
			</table>
		</div>
	</div>
</div>
{else}
	{if !empty($list_today)}
		{foreach from=$list_today name=i item=today}
		<div class="card mb-3">
			<div class="card-header">
				<h5 class="fw-semibold mb-0 lh-base"><a class="fs-16 fw-semibold" onclick="$Core.today.edit_stock_today(this,event)" data-action="_detail" data-today_id="{$today.today_id}" href="javascript:void(0);">{$today.title}</a></h5>
			</div>
			<div class="card-body">
				<div class="d-flex flex-column">
					<p class="mb-1"><span>Mã căn: {$today.stock_code}</span></p>
					<p class="mb-1"><span class="text-main fst-italic fs-12">Bắt đầu: {$today.start_date} - Kết thúc: {$today.end_date}</span></p>
					<p class="mb-1"><span class="fs-12">Người tạo: {$today.user_name} - Ngày tạo: {$today.reg_date}</span></p>
					<p class="mb-1"><span class="fs-12">Tình trạng: {$today.status}</span></p>
					<div class="d-flex justify-content-between">
						<div class="d-flex flex-wrap">
							<p class="d-flex align-items-center mb-1 mr-2"><span class="fs-12 mr-2">ON/OFF: </span><label class="switch">
							  <input type="checkbox" onchange="$Core.today.status(this, event)" data-field="is_online" today_id="{$today.today_id}" {if $today.is_online eq 1}checked{/if} value="1">
							  <span class="slider round"></span>
							</label></p>
							<p class="d-flex align-items-center mb-1"><span class="fs-12 mr-2">Lặp lại: </span><label class="switch">
							  <input type="checkbox" onchange="$Core.today.status(this, event)" data-field="is_repeat" today_id="{$today.today_id}" {if $today.is_repeat eq 1}checked{/if} value="1">
							  <span class="slider round"></span>
							</label></p>	
						</div>
						<div class="dropdown d-flex justify-content-end">
							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>
							</button>
							<div class="dropdown-menu" style="">
								<a class="dropdown-item" onclick="$Core.today.edit_stock_today(this,event)" data-action="_edit" data-today_id="{$today.today_id}" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
								<a class="dropdown-item" onclick="$Core.today.edit_stock_today(this,event)" data-action="_dupplicate" data-today_id="{$today.today_id}" href="javascript:void(0);"><i class='bx bx-copy me-1'></i> Nhân bản</a> 
								<a class="dropdown-item" onclick="$Core.today.delete_today(this,event)" data-today_id="{$today.today_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
							</div>
						</div>
					</div>
					
				</div>
				
			</div>
		</div>
		{/foreach}
	{/if}
{/if}