<div class="card">
	<div class="card-header">
		<div class="d-flex align-items-center justify-content-between">
			<h5 class="card-title mb-0">
				{if $is_dir_sale eq '1'}
					Thống kê booking {$department_name}
				{else}
					Thống kê booking
				{/if}
			</h5>
			<div class="btn-group">
				<button class="btn btn-outline-default dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
					<span>Lọc ngày/tháng/năm</span>
				</button>
				<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="top-end">
					<form onsubmit="return false" class="p-3">
						{if $is_dir_project == 1 || $is_dir_sale == 1}
						<div class="form-group mb-2">
							<label class="form-label mb-1">Xem dưới vài trò</label>
							<div class="clearfix"></div>
							<div class="btn-group w-100 text-nowrap" role="group" aria-label="Hiển thị">
								{foreach from=$role_arrs key=_oK item = _oI}
								<input  type="radio" gId="{$gId}" class="btn-check search_field" name="role_type" data-field="role_type" 
									{if $_oK eq $smarty.const._ROLE_GD_SALE} checked{/if} id="{$gId}_{$_oK}" value="{$_oK}" 
									onChange="$Core.dashboard.handle_booking_changed(this, event)" is_dir_project="{$is_dir_project}">
								<label data-toggle="ripple" for="{$gId}_{$_oK}" class="btn btn-outline-default text-fs-13">{$_oI}</label>
								{/foreach}
							</div>
						</div>
						{/if}
						<div class="form-group mb-2">
							<label class="form-label mb-1">Lọc thời gian</label>
							<div class="clearfix"></div>
							<div class="btn-group w-100 text-nowrap" role="group" aria-label="Hiển thị">
								{foreach from=$type_of_date_arrs key=_oK item = _oI}
								<input type="radio" gId="{$gId}" class="btn-check search_field" name="date_type" data-field="date_type" onChange="$Core.dashboard.handle_booking_changed(this, event)" id="{$gId}_{$_oK}" value="{$_oK}"{if $_oK eq 'month'} checked{/if}>
								<label data-toggle="ripple" for="{$gId}_{$_oK}" class="btn btn-outline-default text-fs-13">{$_oI}</label>
								{/foreach}
							</div>
						</div>
						<div class="form-group mb-2">
							<label class="form-label mb-1">Thời gian</label>
							<!-- <input type="week" class="form-control js__booking_date search_field" data-field="week" onChange="$Core.dashboard.handle_booking_changed(this, event)" value="{$current_year}-W{$current_week}" gId="{$gId}" /> -->
							<input type="month" class="form-control search_field js__booking_date" data-field="month" onChange="$Core.dashboard.handle_booking_changed(this, event)" value="{$current_month}" gId="{$gId}" />
						</div>
						<div class="form-group mb-2">
							<label class="form-label mb-1">Dự án</label>
							<select class="form-control iso-selectizeSync search_field" data-field="project_id" placeholder="Lựa chọn dự án" 
								onChange="$Core.dashboard.handle_booking_changed(this, event)" gId="{$gId}" name="project_id">
								<option value=""></option>
								{if !empty($list_projects)}
									{foreach from=$list_projects item = _oProject}
									<option value="{$_oProject.project_id}">{$_oProject.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						<div class="form-group">
							<label class="form-label mb-1">Phân khu</label>
							<select class="form-control iso-selectizeSync search_field" data-field="block_id" 
							placeholder="Lựa chọn phân khu" onChange="$Core.dashboard.handle_booking_changed(this, event)" gId="{$gId}" name="block_id"></select>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod=developer&act=load_booking" 
			data-options='{ldelim}{rdelim}'>
			<div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
			<div class="form-row mb-2">
				<div class="col-6">
					<div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-100 h-px-15 rounded-2"></div>
				</div>
				<div class="col-6">
					<div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-100 h-px-15 rounded-2"></div>
				</div>
			</div>
			<div class="animate-bg w-100 h-px-15 mb-2 rounded-2"></div>
		</div>
	</div>
</div>