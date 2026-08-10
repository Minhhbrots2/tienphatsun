<div class="modal-dialog">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cập nhật căn đã bán</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if !empty($list_ad_projects)}
			<div class="form-group mb-2">
				<label class="form-label">Chọn dự án</label>
				<div class="clearfix"></div>
				<select class="form-control multiselect" multiple name="project_arrs[]">
					{foreach from=$list_ad_projects item = _oProject}
					<option value="{$_oProject.project_id}" selected>{$_oProject.title}</option>
					{/foreach}
				</select>
			</div>
			{/if}
			<div class="form-group mb-2">
				<label class="form-label">Danh sách căn đã bán</label>
				<div class="clearfix"></div>
				<textarea id="textarea_{$uid}" name="content" class="form-control mb-2" rows="10" placeholder="Nhập danh sách căn đã bán, mỗi căn đã bán nằm ở một dòng"></textarea>
				<div class="alert alert-warning" role="alert">Chú ý: mỗi căn bán một dòng</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" onClick="$Core.helper.do_update_stock_sold(this, event)" class="btn btn-primary">Cập nhật</button>
		</div>
	</form>
</div>