<div class="modal-dialog  modal-md">
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
			<div class="form-group mb-2">
				<label class="w-100 form-label mb-1">Nội dung trích dẫn</label>
				<textarea name="content" id="" cols="30" rows="5" class="form-control required">{$oneItem.content}</textarea>	
			</div>
			<div class="form-row">
				<div class="col-12 col-md-4 flex-fill">
					<div class="form-group mb-2">
						<label class="w-100  form-label mb-1">Tác giả</label>
						<input type="text" name="author" class="form-control" value="{$oneItem.author}">
					</div>
				</div>
				<div class="col-12 col-md-4 flex-fill">
					<div class="form-group mb-2">
						<label class="w-100  form-label mb-1">Áp dụng cho</label>
						<select name="apply_to" id="" class="form-select form-control" onChange="$Core.quote.loadObject(this,event)" toId="toObject_{$uid}" share_ids="{$oneItem.share_ids}">
							<option value="all" {if $oneItem.apply_to eq 'all' || empty($oneItem.apply_to)}selected{/if}>Tất cả</option>
							<option value="department" {if $oneItem.apply_to eq 'department'}selected{/if}>Phòng ban</option>
							<option value="group" {if $oneItem.apply_to eq 'group'}selected{/if}>Nhóm</option>
							<option value="profile" {if $oneItem.apply_to eq 'profile'}selected{/if}>Nhân viên</option>
						</select>
					</div>
				</div>
				<div class="col-12 col-md-4 flex-fill {if $oneItem.apply_to eq 'all'}d-none{/if}" id="toObject_{$uid}">												
					<div class="form-group mb-2">
						<label class="w-100  form-label mb-1">Phòng</label>
						<select name="share_ids[]" class="form-select form-control">

						</select>
					</div>
				</div>
			</div>			
		</div>
		<div class="modal-footer justify-content-end">	
			<div class="d-flex align-items-center">
				<input type="hidden" name="submit" value="Update" />
				<input type="hidden" name="table_id" value="{$table_id}" />
				<button type="button" onClick="$Core.quote.save(this, event)" class="btn btn-primary">
					{if !empty($table_id)}
						Cập nhật
					{else}
						Thêm
					{/if}
				</button>
			</div>
		</div>
	</form>
</div>