{assign var = gid value = $clsISO->getUniqid()}
<div class="modal-dialog modal-dialog-centered">
	<form class="modal-content">
		<div class="modal-header">
			<div class="d-flex w-100 align-items-center justify-content-between">
				<h5 class="modal-title"> yêu cầu phiếu tính giá</h5>
				<div class="d-flex gap-2 align-items-center{if $deviceType eq 'phone'} mr-3{/if}">
					<label class="switch">
						<input type="checkbox" name="is_urgent" value="1" />
						<span class="slider round"></span>
					</label>
					<span class="text-main">Cần gấp</span>
				</div>
			</div>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="textarea">
				<textarea id="{$clsISO->getUniqid()}" name="content" data-height="50" rows="10" cols="255" class="hasIsoRedactor" data-placeholder="Nhập nội dung yêu cầu..."></textarea>
			</div>
			<div class="alert alert-warning">Không cần ghi nội dung nếu không có yêu cầu đặc biệt!</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" stock_id="{$stock_id}" onclick="$Core.helper.requestPTG(this,event)" action="_SAVE" class="btn btn-primary flex-fill">Gửi yêu cầu</button>
		</div>
	</form>
</div>