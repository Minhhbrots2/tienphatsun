<div class="modal-dialog modal-dialog-centered register_mwf modal-sm">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title text-white">Đăng ký xem nhà mẫu MWF</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				{assign var = uid value = $clsISO->getUniqid()}
				<div class="form-floating">
					<select class="form-control form-select required" name="type_id">
						{$clsProperty->getSelectOptimizePropertyNotTile('_REGISTER_TYPE_MWF',0,$arr_register_type_mwf)}
					</select>
					<label for="{$uid}">Loại hình</label>
				</div>
			</div>
			<div class="form-group mb-2">
				{assign var = uid value = $clsISO->getUniqid()}
				<div class="form-floating">
					<input type="text" class="form-control required" id="{$uid}" name="full_name" maxlength="255" 
					placeholder="Họ và tên" value="{$clsProfile->getFullName($profile_id, $oneProfile)}" />
					<label for="{$uid}">Họ và tên</label>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="form-floating">
						<input type="text" class="form-control required" id="{$uid}" name="phone" maxlength="255" 
						placeholder="Nhập 4 số cuối của điện thoại" value="{$clsProfile->getValueField($profile_id, $oneProfile, 'phone')}" />
						<label for="{$uid}">Điện thoại</label>
					</div>
				</div>
				<div class="col-6">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="form-floating">
						<input type="text" class="form-control required" id="{$uid}" name="CCID" maxlength="255" 
						placeholder="Nhập 4 số cuối của CCID" value="{$clsProfile->getValueField($profile_id, $oneProfile, 'CCID')}" />
						<label for="{$uid}">CCID</label>
					</div>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="form-floating">
						<input type="text" class="form-control" id="{$uid}" name="stock_code" maxlength="255" 
						placeholder="Mã căn" />
						<label for="{$uid}">Mã căn</label>
					</div>
				</div>
				<div class="col-6">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="form-floating">
						<input type="datetime-local" class="form-control required" id="{$uid}" name="time" maxlength="255" 
						placeholder="Thời gian" />
						<label for="{$uid}">Thời gian</label>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			<button type="button" class="btn bg-white btn-outline-default" data-bs-dismiss="modal">Đóng</button>
			<button type="button" onClick="$Core.helper.save_register_mwf(this, event)" class="btn btn-primary">Đăng ký</button>
		</div>
	</form>
</div>