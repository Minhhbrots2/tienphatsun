<div class="modal-dialog modal-dialog-centered modal-md">
	<form class="modal-content">
		<div class="modal-header">				
			{if !empty($id)}
				<h5 class="modal-title fs-{if $deviceType eq 'phone'}5{else}4{/if} text-main">Chỉnh sửa</h5>
			{else}
				<h5 class="modal-title fs-{if $deviceType eq 'phone'}5{else}4{/if} text-main">Thêm mới</h5>
			{/if}
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body pt-0">	
			<div class="form-row">
				<div class="col-12 col-md-8">
					<div class="form-group mb-2">
						<label class="w-100 form-label mb-1">Tiêu đề</label>
						<input type="text" placeholder="Nhập tiêu đề" name="title" maxlength="255" charet="UTF-8" class="form_field form-control required no-focus" value="{$oneItem.title}">	
					</div>
				</div>
				<div class="col-12 col-md-4">					
					<div class="form-group mb-2">
						<label class="w-100  form-label mb-1">Áp dụng cho</label>
						<select name="apply_to" id="" class="form-select form-control" onChange="$Core.automation.loadObject(this,event)" toId="toObject_{$uid}" to_value="{$oneItem.to_value}">
							<option value="all">Tất cả</option>
							<option value="department">Phòng ban</option>
							<option value="group">Nhóm</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group mb-2 d-none" id="toObject_{$uid}">
				<label class="w-100  form-label mb-1">Phòng</label>
				<select name="to_value" class="form-select form-control">

				</select>
			</div>
			<hr>
			<div class="mb-2">
				<button class="btn btn-outline-primary w-100" type="button" onClick="$Core.automation.addSchedule(this,event)" >Thêm lịch trình</button>
				<div class="lst_schedule mt-3">
					{assign var=gId value=$clsISO->getUniqid()}
					<div class="item_schedule border p-3 rounded-1 mt-2">
						<div class="form-row">
							<div class="col-12 col-md-4">
								<div class="form-group mb-2">
									<label class="w-100  form-label mb-1">Mẫu mail</label>
									<select name="schedule[{$gId}][template_id]" id="" class="form-select form-control">

									</select>
								</div>
							</div>
							<div class="col-12 col-md-8">
								<div class="form-group mb-2">
									<label class="w-100  form-label mb-1">Thời gian gửi</label>
									<div class="input-group">
										<span class="input-group-text">Sau</span>
										<input type="text" aria-label="First name" class="form-control numberonly" name="schedule[{$gId}][number_time]" value="" placeholder="1">
										<select name="schedule[{$gId}][date_type]" class="form-select">
											<option value="day">Ngày</option>
											<option value="month">Tháng</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>
		<div class="modal-footer justify-content-between">	
			<div class="d-flex align-items-center">
				<div class="d-flex gap-1 align-items-center">
					<label class="switch">
						<input type="checkbox" name="is_share" value="1" {if $oneItem.is_share eq 1} checked{/if}>
						<span class="slider round"></span>
					</label>
					<label class="col-form-label mr-2">Chia sẻ mẫu</label>
				</div>
			</div>
			<div class="d-flex align-items-center">
				<input type="hidden" name="submit" value="Update" />
				<input type="hidden" name="template_id" value="{$template_id}" />
				<button type="button" onClick="$Core.template.saveTemplate(this, event)" class="btn btn-primary">
					{if !empty($template_id)}
						Cập nhật
					{else}
						Thêm
					{/if}
				</button>
			</div>
		</div>
	</form>
</div>