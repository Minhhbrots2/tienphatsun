<div class="modal-dialog modal-dialog-centered" role="document">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title"><i class="bx bx-lock-alt me-1"></i> Thay đổi mật khẩu</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="mb-3">
				<label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
				<div class="input-group input-group-merge">
					<input type="password" name="user_pass" id="cp_pass_{$uid}" class="form-control" placeholder="Nhập mật khẩu mới" autocomplete="new-password" />
					<span class="input-group-text cursor-pointer" target="#cp_pass_{$uid}" onclick="$Core.member.toggle_pass(this,event)"><i class="bx bx-show"></i></span>
				</div>
				<div class="text-danger small mt-1 err_pass"></div>
				<div class="text-muted small mt-1">Tối thiểu 6 ký tự.</div>
			</div>
			<div class="mb-1">
				<label class="form-label">Nhắc lại mật khẩu <span class="text-danger">*</span></label>
				<div class="input-group input-group-merge">
					<input type="password" name="user_cpass" id="cp_cpass_{$uid}" class="form-control" placeholder="Nhập lại mật khẩu mới" autocomplete="new-password" />
					<span class="input-group-text cursor-pointer" target="#cp_cpass_{$uid}" onclick="$Core.member.toggle_pass(this,event)"><i class="bx bx-show"></i></span>
				</div>
				<div class="text-danger small mt-1 err_cpass"></div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Hủy</button>
			<button type="button" class="btn btn-primary" onclick="$Core.member.save_change_pass(this,event)"><i class="bx bx-save me-1"></i> Đổi mật khẩu</button>
		</div>
	</form>
</div>
