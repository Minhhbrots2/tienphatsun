<div class="modal-dialog modal-dialog-centered">
	<form class="modal-content" id="frmIssue" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title">Cập nhật PTG {$oneBlock.title}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{foreach from=$arr_resource key = _oK item = _OT}
			<div class="form-group mb-2">
				<label class="form-label mb-1">{$_OT}</label>
				<div class="input-group">
					<input type="text" class="form-control" name="crawl_configs[{$_oK}][url]" value="{if !empty($crawl_configs.$_oK.url)}{$crawl_configs.$_oK.url}{/if}" placeholder="Nhập URL {$_OT}" />
					<button data-toggle="ripple" type="button" block_id="{$block_id}" resource="{$_oK}" onClick="$Core.stock.do_crawl(this, event)" 
						class="btn btn-outline-default"><i class="bx bx-play"></i> Lấy PTG</button>
				</div>
			</div>
			{/foreach}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" block_id="{$block_id}" onClick="$Core.stock.do_config_block(this, event)" 
				class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
