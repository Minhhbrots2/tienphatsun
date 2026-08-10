<div class="modal-dialog modal-sm">
	<form action="#" method="POST" onsubmit="return false;" class="modal-content">
		<div class="modal-header pb-3 border-bottom">
			<h5 class="modal-title">Chiến dịch</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-12 mb-2">
					<div class="form-group">
						<label class="form-label mb-1">Tiêu đề</label>
						<input type="text" class="form-control required" name="title" value="{$oneItem.title}" >
					</div>
				</div>
				<div class="col-12 mb-2">
					<div class="form-group">
						<label class="form-label mb-1">Thời gian</label>						
						<div class="input-group">
							<input class="form-control required" type="datetime-local" name="start_date" value="{$oneItem.start_date|date_format:'%Y-%m-%dT%H:%M'}" min="{$date_min}" >
							<input class="form-control required" type="datetime-local" name="end_date" value="{$oneItem.end_date|date_format:'%Y-%m-%dT%H:%M'}" min="{$date_min}" >
						</div>
					</div>
				</div>
				<div class="col-12 mb-2">
					<div class="form-group">
						<label class="col-form-label">Nội dung</label>
						<textarea class="form-control form_field" name="intro" cols="255" rows="5" id="{$clsISO->getUniqid()}">{$oneItem.intro}</textarea>
					</div>	
				</div>
				
			</div>
			<div class="js__message-container"></div>
		</div>
		<div class="modal-footer border-top">
			<input type="hidden" name="hid" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" onClick="$Core.data_central.add_campaign(this, event)" 
				class="btn btn-primary js__start_share_customer">Tạo</button>
		</div>
	</form>
</div>