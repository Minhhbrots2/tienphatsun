<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<form class="modal-content" enctype="multipart/form-data">
			<div class="modal-header">
				<h5 class="modal-title">Gửi email tới khách hàng</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-row mb-3">
					<div class="col-12 box_select">
						<div class="d-flex justify-content-between align-items-center mb-1">
							<label class="form-label mb-0">Người nhận</label>
							<button class="btn btn-default" type="button" onClick="$Core.client.selectAll(this,event)" value="0">Chọn/bỏ chọn tất cả</button>
						</div>
						<div class="w-100">
							<select name="client_ids[]" id="" class="form-control required iso-select2 w-100" multiple data-placeholder="Chọn khách hàng">
								{foreach from=$lstClient item=client}
									<option value="{$client.client_id}" {if $clsISO->checkItemInArray($client.client_id,$arr_client)}selected{/if} >{$client.full_name}</option>
								{/foreach}
							</select>
						</div>
					</div>
				</div>
				<div class="body-form">
					<div class="form-group">
						<h4>Email</h4>
					</div>
					<div class="form-group mb-2">
						<label class="form-label mb-1">Tiêu đề</label>
						<input type="text" name="title" class="form-control required" placeholder="Tiêu đề" value="">
					</div>
					<div class="form-group mb-2">
						<label class="form-label mb-1">Nội dung</label>
						<textarea name="content" id="" cols="30" rows="10" class="form-control isoTextArea required"></textarea>
					</div>
					<div class="form-group">
						<label for="formFileMultiple" class="form-label">File đính kèm</label>
						<input class="form-control" name="files[]" type="file" id="formFileMultiple" multiple="">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" onClick="$Core.client.sendEmailClient(this,event)">Gửi mail</button>
			</div>
		</form>
	</div>
</div>