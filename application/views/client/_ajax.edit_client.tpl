<div class="modal-dialog modal-dialog-centered modal-ipad">
	<form class="d-none" enctype="multipart/form-data">
		<input id="select_image_{$uid}" accept="image/jpeg,image/jpg,image/png,application/pdf" 
		type="file" tp="gd" p_id="{$p_id}" p_field="{$p_field}" onchange="$Core.billing.upload_image(this,event)" name="image" />
	</form>
	<form class="modal-content" method="POST" >
		<div class="modal-header">
			<h5 class="modal-title">Sửa thông tin</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row form-group mb-2">
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Họ tên</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-user"></i></span>
						<input type="text" name="full_name" class="form-control required" placeholder="Họ tên" value="{$oneClient.full_name}">
					</div>					
				</div>
				<div class="col-6 col-md-4 mb-3 mb-lg-0">
					<label class="form-label mb-1">Ngày sinh</label>
					<input type="datetime" class="form-control isodatepicker" name="birthday" value="{$oneClient.birthday}" placeholder="dd/mm/yyyy" autocomplete="off" id="bd{$uid}">			
				</div>
				<div class="col-6 col-md-2 mb-3 mb-lg-0">
					<label class="form-label mb-1">Giới tính</label>
					<select name="gender" id="" class="form-control iso-select2" placeholder="Chọn giới tính">
						<option value="0">--Chọn--</option>
						{section loop=$lstGender name=i}
							<option value="{$lstGender[i].property_id}" {if $oneClient.gender eq $lstGender[i].property_id}selected{/if}>{$lstGender[i].title}</option>
						{/section}
					</select>
				</div>
			</div>
			<div class="form-row form-group mb-2">
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Số điện thoại</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-phone-call"></i></span>
						<input type="text" name="phone" class="form-control required" placeholder="+84" value="{$oneClient.phone}">
					</div>					
				</div>
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Email</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-envelope"></i></span>
						<input type="text" name="email" class="form-control required" placeholder="example@gmail.com" value="{$oneClient.email}">
					</div>					
				</div>
			</div>
			<div class="form-row form-group mb-2">
				<div class="col-6 col-md-3 mb-3 mb-lg-0">
					<label class="form-label mb-1">CMT/CCID</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-barcode-reader"></i></span>
						<input type="text" class="form-control" name="identity_card" value="{$oneClient.identity_card}" placeholder="CMT/CCID">
					</div>
				</div>
				<div class="col-6 col-md-3 mb-3 mb-lg-0">
					<label class="form-label mb-1">Ngày cấp</label>
					<input type="datetime" class="form-control isodatepicker" name="issuance_date" value="{$oneClient.issuance_date}" autocomplete="off" placeholder="dd/mm/yyyy" id="dp{$uid}">
				</div>
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Nơi cấp</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-map"></i></span>
						<input type="text" class="form-control" name="issuance_location" value="{$oneClient.issuance_location}" placeholder="Nơi cấp...">
					</div>
				</div>
			</div>
			<div class="form-row form-group mb-2">
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Địa chỉ thường trú</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-map"></i></span>
						<input type="text" class="form-control" name="address" value="{$oneClient.address}" placeholder="Nhập địa chỉ">
					</div>
				</div>
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Địa chỉ liên hệ</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-map"></i></span>
						<input type="text" class="form-control" name="contact_address" value="{$oneClient.contact_address}" placeholder="Nhập địa chỉ">
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<div class="w-100 d-flex justify-content-end">
				<div class="p__right">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
					<button type="button" onClick="$Core.client.edit_client(this, event)" data-action="edit" toId="{$toId}" client_id="{$oneClient.client_id}" class="btn btn-primary">Cập nhật</button>
				</div>
			</div>
		</div>
	</form>
</div>