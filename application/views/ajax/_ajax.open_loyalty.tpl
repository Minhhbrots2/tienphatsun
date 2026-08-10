<div class="modal-dialog modal-md modal-dialog-centered">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $holderG eq '_move'}Chuyển{else}Trừ{/if} điểm Loyalty</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="alert alert-warning">
				<strong>Ghi chú:</strong><br />
				Bạn chỉ phép được chuyển số điểm của &lt;= số diển của người chuyển được phép.
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-lg-{if $holderG eq '_move'}5{else}10{/if} mb-2 mb-lg-0">
					<label class="form-label mb-1">Chuyển từ</label>
					<select class="iso-selectizeNotSearch required" onChange="$Core.global.get_loyalty_user(this, event)" name="from_user" data-placeholder="Lựa chọn người" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff"></select>
				</div>
				<div class="col-12 col-lg-2 mb-2 mb-lg-0">
					<label class="form-label mb-1">Số điểm</label>
					<input type="number" onChange="$Core.global.handle_loyalty(this, event)" class="form-control numberonly" value="0" placeholder="Số điểm" name="score" onClick="this.select()" />
				</div>
				{if $holderG eq '_move'}
				<div class="col-12 col-lg-5">
					<label class="form-label mb-1">Chuyển tới</label>
					<select class="iso-selectizeNotSearch required" name="to_user" data-placeholder="Lựa chọn người" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff"></select>
				</div>
				{/if}
			</div>
			<div class="form-group mb-0">
				<label class="form-label mb-1">Nội dung</label>
				<textarea class="form-control reuqired" rows="2" placeholder="Nội dung" name="content"></textarea>
			</div>
		</div>
		<div class="modal-footer border-top">
			<input type="hidden" name="holderG" value="{$holderG}" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" fpoint_id="{$fpoint_id}" onClick="$Core.global.save_loyalty(this, event)" class="btn btn-primary">Thực hiện</button>
		</div>
	</form>
</div>
