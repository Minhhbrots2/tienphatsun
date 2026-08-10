<div class="modal right fade show" id="{$uid}" role="dialog">
  <div class="modal-dialog">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Đăng ký tham gia</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="regis_customer">
				{if !empty($participants)}
					{foreach name=i from=$participants key = regis_id item = _oReg}
					<div class="widget-block {$uid} mb-3">
						<div class="widget-header">Khách hàng {$smarty.foreach.i.iteration}</div>
						<div class="widget-content">
							<div class="form-group mb-3">
								<div class="form-floating">
									<input type="text" class="form-control required" id="full_name_{$regis_id}" name="participants[{$regis_id}][full_name]" maxlength="255" placeholder="Họ và tên" value="{$_oReg.full_name}" />
									<label for="full_name_{$regis_id}">Họ và tên</label>
								</div>
							</div>
							<div class="form-group mb-3">
								<div class="form-floating">
									<input type="text" class="form-control required" id="phone_{$regis_id}" name="participants[{$regis_id}][phone]" maxlength="255" placeholder="Điện thoại" value="{$_oReg.phone}" />
									<label for="phone_{$regis_id}">Điện thoại</label>
								</div>
							</div>
						</div>
					</div>
					{/foreach}
				{else}
					<div class="widget-block {$uid} mb-3">
						<div class="widget-header">Khách hàng 1</div>
						<div class="widget-content">
							{assign var = regis_id value = $clsISO->getUniqid()}
							<div class="form-group mb-3">
								<div class="form-floating">
									<input type="text" class="form-control required" id="full_name_{$regis_id}" name="participants[{$regis_id}][full_name]" maxlength="255" placeholder="Họ và tên" />
									<label for="full_name_{$regis_id}">Họ và tên</label>
								</div>
							</div>
							<div class="form-group mb-3">
								<div class="form-floating">
									<input type="text" class="form-control required" id="phone_{$regis_id}" name="participants[{$regis_id}][phone]" maxlength="255" placeholder="Điện thoại" />
									<label for="phone_{$regis_id}">Điện thoại</label>
								</div>
							</div>
						</div>
					</div>
				{/if}
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" uid="{$uid}" onClick="add_customer_line(this, event)" class="btn btn-outline-danger">
				<i class="bx bx-plus"></i>
				<span>Thêm khách</span>
			</button>
			<button type="button" onClick="pop_event_register(this, event)" news_id="{$news_id}" class="btn btn-primary">
				Lưu lại
			</button>
		</div>
	</form>
  </div>               
</div>