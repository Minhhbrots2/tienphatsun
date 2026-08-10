<div class="modal-dialog modal-ipad">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">
				Mẫu email xác nhận đặt cọc</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if !empty($message)}
				<div class="alert alert-warning" role="alert">Thông tin giao dịch còn thiếu: {$message}. <br>Hãy đọc và kiểm tra thông tin chính xác trước khi gửi tới khách hàng</div>
			{/if}
			<div class="position-relative mb-3">
				<label for="trans_code" class="form-label mb-1 w-100 text-main fs-5">Tiêu đề mail</label>
				<a href="javascript:void(0)" onclick="copyContentToClipboard(this, event)" data-bs-toggle="tooltip" data-bs-trigger="click" class="text-dark fs-6 position-absolute top-0 right-0" title="Sao chép tiêu đề email" aria-label="Sao chép tiêu đề email" style="right:0" toId="title_copy_{$uid}"><i class="bx bx-copy"></i></a>
				<h2 class="content_copy mb-0 w-100 fs-5" id="title_copy_{$uid}">{$subject}</h2>
			</div>
			<div class="position-relative">
				<label for="trans_code" class="form-label mb-1 w-100 text-main fs-5">Nội dung mail</label>
				<div class="content_copy" id="content_copy_{$uid}">{$content|html_entity_decode}</div>
				<a href="javascript:void(0)" onclick="copyContentToClipboard(this, event)" data-bs-toggle="tooltip" data-bs-trigger="click" class="text-dark fs-6 position-absolute top-0 right-0" title="Sao chép nội dung email" aria-label="Sao chép nội dung email" style="right:0" toId="content_copy_{$uid}"><i class="bx bx-copy"></i></a>
			</div>
			{if !empty($company_email)}
				<div class="position-relative">
					<label for="trans_code" class="form-label mb-1 w-100 text-main fs-5">Gửi email tới địa chỉ:</label>
					<div class="fs-5" id="email_copy_{$uid}">{$company_email}</div>
					<a href="javascript:void(0)" onclick="copyContentToClipboard(this, event)" data-bs-toggle="tooltip" class="text-dark fs-6 position-absolute top-0 right-0" title="Sao chép địa chỉ email" style="right:0" toId="email_copy_{$uid}"><i class="bx bx-copy"></i></a>
				</div>
			{/if}
		</div>
	</form>
</div>
