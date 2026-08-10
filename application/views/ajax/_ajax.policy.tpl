{if $tp eq '_update'}
<div class="modal-dialog modal-xl">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cập nhật chính sách bán hàng</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group form-row mb-2">
				<label class="col-12 col-md-3 col-form-label">Cho phép hiển thị</label>
				<div class="col-12 col-md-9">
					<label class="switch">
						<input type="checkbox" name="status"{if $sale_policy.status eq '1'} checked{/if} value="1" />
						<span class="slider round"></span>
					</label>
				</div>
			</div>
			<div class="form-group mb-3">
				<label class="form-label">Nội dung</label>
				<div class="clearfix"></div>
				<textarea id="tinyMCE{$uid}" class="form-control isoTextArea" data-name="policy" rows="2">{$sale_policy.policy}</textarea>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" onClick="$Core.helper.update_policy(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
{else}
<div class="modal-dialog modal-standard">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Chính sách bán hàng <br />
				<span class="text-muted fs-12">
					<i style="transform:translateY(5px);" class="material-icons-outlined">update</i>
					{$clsISO->convertTimeToText($sale_policy.upd_date, true)}
				</span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body mr-1 p-0">
			<div class="tinyContent p-3">
				{$sale_policy.policy}
			</div>
		</div>
	</div>
</div>
{/if}
