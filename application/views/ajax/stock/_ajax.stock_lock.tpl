<div class="modal-dialog">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Khóa căn</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row">
				<div class="col-12 col-md-4">
					<div class="form-group mb-2">
						<label class="form-label">Chọn dự án</label>
						<select class="form-control form-select required" name="project_id" {if !empty($stock_id)}disabled{/if}>
							{foreach from=$lstProjects item = _oProject}
							<option value="{$_oProject.project_id}" {if $project_id eq $_oProject.project_id}selected{/if} >{$_oProject.title}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="col-12 col-md-4">
					<div class="form-group mb-2">
						<label class="form-label">Chọn loại khóa</label>
						<select class="form-control form-select required" name="type_id">
							{$clsSetting->getSelectBySetting("_TYPE_STOCK_LOCK",$type_id,"",0)}
						</select>
					</div>					
				</div>
				<div class="col-12 col-md-4">
					<div class="form-group mb-2">
						<label class="form-label">Nhập mã căn</label>
						<div class="position-relative">
							<div class="input-loading loading position-relative">
								<input uid="{$uid}" type="hidden" name="stock_id" value="{$stock_id}" class="js__input_stock_id">
								<input type="text" autocomplete="off" onkeyup="$Core.helper.stock_lock.do_search(this, event)" uid="{$uid}" class="form-control js__input_stock_code required" name="stock_code" placeholder="Nhập mã căn hộ để tìm kiếm" value="{$oneStock.ms_code}"  {if !empty($stock_id)}disabled{/if}>
								<span class="icon-loader position-absolute text-muted" style="top:10px;right:10px">
									<i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i>
								</span>
							</div>
							<div id="{$uid}" class="autosugget d-none"></div>
						</div>
					</div>
				</div>
				
				<div class="col-12 col-md-6">
					<div class="form-group mb-2">
						<label class="form-label">Loại</label>
						{assign var=gId value=$clsISO->getUniqid()}
						<div class="btn-group d-flex">
							<input type="radio" class="btn-check" id="date_type_1_{$uid}" name="date_type" checked value="1" onChange="$Core.helper.stock_lock.loadTime(this,event)" toId="{$gId}">
							<label class="btn btn-outline-default" for="date_type_1_{$uid}">Chọn giờ</label>
								<input type="radio" class="btn-check" id="date_type_2_{$uid}" name="date_type" value="2" onChange="$Core.helper.stock_lock.loadTime(this,event)" toId="{$gId}">
							<label class="btn btn-outline-default" for="date_type_2_{$uid}">Khoảng thời gian</label>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6" id="{$gId}">
					<div class="form-group mb-2">
						<label class="form-label">Kết thúc</label>
						<div class="w-px-100">
							<input type="time" class="form-control numberonly  required" name="hour" value="">
						</div>
					</div>
				</div>
				<div class="col-12 col-md-12">
					<div class="form-group mb-2">
						<label class="form-label">Ghi chú</label>
						<textarea name="notes" id="" cols="30" rows="5" class="form-control"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			<input type="hidden" name="stock_lock_id" value="{$stock_lock_id}" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" onClick="$Core.helper.stock_lock.save_stock_lock(this, event)" class="btn btn-primary">Cập nhật</button>
		</div>
	</form>
</div>