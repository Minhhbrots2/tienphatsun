<div class="modal-dialog" style="width:540px">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void(0)" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>Import nhân sự từ Google Sheet</strong></h3>
		</div>
		<form method="POST" action="" id="form_profile_import">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Link Google Sheet <span class="text-red">*</span></label>
					<input type="text" name="sheet_url" class="form-control" placeholder="https://docs.google.com/spreadsheets/d/..../edit#gid=0" />
					<small class="text-muted">Sheet phải chia sẻ công khai (Bất kỳ ai có đường liên kết). Chọn đúng tab qua <b>gid</b> trên link.</small>
				</div>
				<div class="form-group">
					<label class="col-form-label">Mật khẩu mặc định cho tài khoản mới <span class="text-red">*</span></label>
					<input type="text" name="default_pass" class="form-control" value="{$default_pass|escape}" />
					<small class="text-muted">Đặt sẵn tại Cấu hình hệ thống &rsaquo; Nhân sự để lần sau không phải nhập lại.</small>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="read_import(this, event)">Tải &amp; chọn cột</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>
