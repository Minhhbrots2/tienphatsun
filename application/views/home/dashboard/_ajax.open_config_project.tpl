<div class="modal-dialog modal-xs modal-dialog-centered modal-dialog-scrollable">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cài đặt dự án</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="alert alert-warning">
				Việc cài đặt dự án giúp bạn lựa chọn đúng dữ án sau khi trang được tải xong!
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Dự án</label>
				<div class="clearfix"></div>
				<select class="form-control iso-select2" name="project_id" onChange="$Core.course.load_block(this, event)" 
					toId="slb_block_{$uid}" data-placeholder="Lựa chọn dự án" data-width="100%" id="slb_project_{$uid}">
					<option value="0">Lựa chọn dự án</option>
					{if !empty($arr_projects)}
						{foreach from=$arr_projects item = $arr_projects item = _oProject}
						<option value="{$_oProject.project_id}">{$_oProject.project_name}</option>
						{/foreach}
					{/if}
				</select>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Phân khu</label>
				<select class="form-control iso-select2" id="slb_block_{$uid}" name="block_id" 
					data-width="100%" data-placeholder="Lựa chọn phân khu">
					<option value="0">Lựa chọn phân khu</option>
					
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-default flex-fill" data-bs-dismiss="modal">
				<i class="bx bx-x"></i> Đóng lại
			</button>
			<button type="button" uid="{$uid}" gId="{$gId}" onClick="$Core.dashboard.save_config_project(this,event)" 
				class="btn btn-primary flex-fill">Lưu lại</button>
		</div>
	</form>
</div>
