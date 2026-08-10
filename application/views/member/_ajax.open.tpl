<div class="modal-dialog modal-ipad">
	<form method="POST" class="modal-content">
		<div class="modal-header border-bottom d-flex align-items-center justify-content-between">
			<h5 class="modal-title">Thêm mới nhân viên</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="widget-block mb-2">
				<div onClick="$Core.helper.toggle_block(this,event)" class="widget-header">Thông tin đăng nhập</div>
				<div class="widget-content">
					<div class="form-group form-row mb-2">
						<div class="col-6 col-md-6">
							<label class="form-label mb-1">Tên đăng nhập</label>
							<input type="text" class="form-control required" name="user_name" data-val-required="Bạn chưa nhập vào tên đăng nhập" autocomplet="off" onClick="this.select()" value="" maxlength="255" placeholder="Tên đăng nhập" />
						</div>
						<div class="col-6 col-md-6">
							<label class="form-label mb-1">E-mail</label>
							<input type="text" class="form-control required" name="user_email" data-val-required="Bạn chưa nhập vào e-mail nhân viên" autocomplet="off" onClick="this.select()" value="" maxlength="255" placeholder="example@gmail.com" />
						</div>
					</div>
					<div class="form-group form-row mb-2">
						<div class="col-6 col-md-6 form-password-toggle">
							<label class="form-label mb-1">Mật khẩu</label>
							{assign var = ref_id value = $clsISO->getUniqid()}
							<div class="input-group input-group-merge">
								<input type="password" class="form-control required" name="user_pass" data-val-required="Bạn chưa nhập vào mật khẩu" placeholder="············" aria-describedby="{$ref_id}" maxlength="255">
								<span class="input-group-text cursor-pointer" id="{$ref_id}">
									<i class="bx bx-hide"></i>
								</span>
							</div>
						</div>
						<div class="col-6 col-md-6 form-password-toggle">
							<label class="form-label mb-1">Xác nhận mật khẩu</label>
							{assign var = ref_id value = $clsISO->getUniqid()}
							<div class="input-group input-group-merge">
								<input type="password" class="form-control required" name="user_cpass" data-val-required="Bạn chưa nhập vào xác nhận mật khẩu" placeholder="············" aria-describedby="{$ref_id}" maxlength="255">
								<span class="input-group-text cursor-pointer" id="{$ref_id}">
									<i class="bx bx-hide"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="widget-block mb-2">
				<div onClick="$Core.helper.toggle_block(this,event)" class="widget-header">Thông tin chi tiết</div>
				<div class="widget-content">
					<div class="form-group form-row mb-2">
						<div class="col-12 col-md-3 mb-2 mb-lg-0">
							<label class="form-label mb-1">Mã nhân viên</label>
							<input type="text" class="form-control required" name="code" data-val-required="Bạn chưa nhập vào tên nhóm nhân viên" value="{$clsProfile->genCode()}" onClick="this.select()" maxlength="255" placeholder="FH0XXX" />
						</div>
						<div class="col-12 col-md-6">
							<label class="form-label mb-1">Họ và tên</label>
							<div class="input-group">
								<input type="text" class="form-control required" name="first_name" data-val-required="Bạn chưa nhập vào tên nhóm nhân viên" value="" maxlength="255" placeholder="Họ" />
								<input type="text" class="form-control required" name="last_name" data-val-required="Bạn chưa nhập vào tên nhóm nhân viên" value="" maxlength="255" placeholder="Tên" />
							</div>
						</div>
						<div class="col-12 col-md-3">
							<label class="form-label mb-1">Giới tính</label>
							<div class="clearfix"></div>
							<select class="form-control form-select required" name="gender_id">
								<option value="0">Chọn</option>
								<option value="1">Nam</option>
								<option value="2">Nữ</option>
								<option value="3">Khác</option>
							</select>
						</div>
					</div>
					<div class="form-group form-row mb-2">
						<div class="col-6 col-md-6 mb-2 mb-lg-0">
							<label class="form-label mb-1">Phòng ban</label>
							<select onChange="$Core.member.handle_dept_changed(this, event)" name="department_id" 
							class="form-control required form-select" title="Phòng ban" data-val-required="Bạn chưa chọn phòng ban">
								{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$oneItem.department_id,'Phòng ban')}
							</select>
						</div>
						<div class="col-6 col-md-6">
							<label class="form-label mb-1">Vai trò</label>
							<select class="form-control required form-select" name="role_id" 
								data-val-required="Bạn chưa chọn vài trò/quyền hạn">
								<option value="0">Chọn vài trò</option>
							</select>
						</div>
					</div>
					<div class="form-group form-row mb-2">
						<div class="col-12 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Điện thoại</label>
							<input type="text" class="form-control" name="phone" maxlength="255" placeholder="(+84) 0000.00.000" />
						</div>
						<div class="col-12 col-md-8">
							<label class="form-label mb-1">Địa chỉ</label>
							<input type="text" class="form-control" name="address" maxlength="255" placeholder="Địa chỉ" />
						</div>
					</div>
					<div class="form-group form-row mb-2">
						<div class="col-6 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Ngày sinh</label>
							<input type="date" class="form-control" name="birthday" maxlength="255" placeholder="dd/mm/yyyy" />
						</div>
						<div class="col-6 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">CCID</label>
							<input type="text" class="form-control" name="CCID" maxlength="255" placeholder="CCID" />
						</div>
						<div class="col-12 col-md-4">
							<label class="form-label mb-1">Ngày bắt đầu</label>
							<input type="date" class="form-control required" name="start_date" data-val-required="Bạn chưa nhập vào ngày bắt đầu" value="" maxlength="255" placeholder="dd/mm/yyyy" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer align-items-center justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<label class="switch">
					<input type="checkbox" checked="checked" name="is_sendemail" value="1">
					<span class="slider round"></span>
				</label>
				<span>Gửi e-mail</span>
			</div>
			<input type="hidden" name="profile_id" value="0" />
			<button type="button" data-group_id="{$group_id}" data-type="save" class="btn btn-primary" 
			title="Lưu lại" onClick="$Core.member.add_new(this, event)">Lưu lại</button>
		</div>
	</form>
</div>