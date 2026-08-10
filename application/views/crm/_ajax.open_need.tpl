<div class="modal-dialog modal-dialog-centered">
	<form class="modal-content" method="POST" enctype="multipart/form-data">
		<div class="modal-header border-bottom">
			<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Sửa{/if} nhu cầu</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1">Tên nhu cầu</label>
					<input name="title" class="form-control required" placeholder="Tên nhu cầu" value="{$oneItem.title}"/>
				</div>
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Loại hình</label>
					<select name="block_type_id" class="form-control upd_field form-select">
						<option value="0">Loại hình</option>
						{$clsProperty->getSelectByProperty('_BLOCK_TYPE', $oneItem.block_type_id)}
					</select>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Mục đích</label>
					<select name="purpose_id" class="form-control upd_field form-select">
						<option value="0">Mục đích</option>
						{$clsProperty->getSelectByProperty('PURPOSE', $oneItem.purpose_id)}
					</select>
				</div>
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Khoảng giá</label>
					<select name="price_range_id" class="form-control upd_field form-select">
						<option value="0">Khoảng giá</option>
						{$clsProperty->getSelectByProperty('_PRICE_RANGE_SOP', $oneItem.price_range_id)}
					</select>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1">Số tầng</label>
					<input name="floor_range" class="form-control numberonly" placeholder="Số tầng" value="{$oneItem.floor_range}"/>
				</div>
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Hướng</label>
					<select name="direction_id" class="form-control upd_field form-select">
						<option value="0">Hướng</option>
						{$clsProperty->getSelectByProperty('_DIRECTION', $oneItem.direction_id)}
					</select>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1">Pháp lý</label>
					<select name="juridical_id" class="form-control upd_field form-select">
						<option value="0">Pháp lý</option>
						{$clsProperty->getSelectByProperty('_JURIDICAL', $oneItem.juridical_id)}
					</select>
				</div>
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Nội thất</label>
					<select name="interior_type" class="form-control upd_field form-select">
						<option value="0">Nội thất</option>
						{$clsProperty->getSelectByProperty('_INTERIOR_TYPE', $oneItem.interior_type)}
					</select>
				</div>
			</div>
			<div class="form-group mb-2 form-row row-gap-1 box_lowfloor">
				<div class="col-12 col-md-6 flex-fill">
					<label class="col-form-label">Số phòng ngủ</label>
					<div class="d-flex align-items-center gap-2 justify-content-start">
						<div class="d-flex align-items-center gap-1 box_number_bedroom">
							<label class="we-radio" for="bedroom_{$uid}_1">
								<input type="radio" id="bedroom_{$uid}_1" name="bedroom" value="1" 
									   {if $oneItem.bedroom eq 1}checked{/if}>
								<span class="lbl_text {if $oneItem.bedroom_count gt 5}cursor-not-allowed{/if}">1</span>
							</label>
							<label class="we-radio" for="bedroom_{$uid}_2">
								<input type="radio" name="bedroom" value="2" id="bedroom_{$uid}_2" 
									   {if $oneItem.bedroom eq 2}checked{/if}>
								<span class="lbl_text {if $oneItem.bedroom_count gt 5}cursor-not-allowed{/if}">2</span>
							</label>
							<label class="we-radio" for="bedroom_{$uid}_3">
								<input type="radio" name="bedroom" value="3" id="bedroom_{$uid}_3" 
									   {if $oneItem.bedroom eq 3}checked{/if}>
								<span class="lbl_text {if $oneItem.bedroom_count gt 5}cursor-not-allowed{/if}">3</span>
							</label>
							<label class="we-radio" for="bedroom_{$uid}_4">
								<input type="radio" name="bedroom" value="4" id="bedroom_{$uid}_4" 
									   {if $oneItem.bedroom eq 4}checked{/if}>
								<span class="lbl_text {if $oneItem.bedroom_count gt 5}cursor-not-allowed{/if}">4</span>
							</label>
							<label class="we-radio" for="bedroom_{$uid}_5">
								<input type="radio" name="bedroom" value="5" id="bedroom_{$uid}_5" 
									   {if $oneItem.bedroom eq 5}checked{/if}>
								<span class="lbl_text {if $oneItem.bedroom_count gt 5}cursor-not-allowed{/if}">5</span>
							</label>
						</div>
						<div class="input-group flex-fill">
							<input class="form-control no-focus w-100 input_no_outer_spin" type="number" placeholder="Nhập số" onChange="$Core.crm.loadNumberRadio(this,event)" data-field="bedroom" name="bedroom_count" value="{if $oneItem.bedroom_num gt 5}{$oneItem.bedroom_count}{/if}">
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6 flex-fill box_lowfloor {if $oneItem.sop_type eq $smarty.const._TYPE_HIGHLEVEL}d-none{/if}">
					<label class="col-form-label">Số phòng tắm</label>
					<div class="d-flex align-items-center gap-2 justify-content-start">
						<div class="d-flex align-items-center gap-1 box_number_bathroom">
							<label class="we-radio" for="bathroom_{$uid}_1">
								<input class="position-absolute" type="radio" name="bathroom" value="1" id="bathroom_{$uid}_1" 
									   {if $oneItem.bathroom eq 1}checked{/if}>
								<span class="lbl_text {if $oneItem.bathroom_count gt 5}cursor-not-allowed{/if}">1</span>
							</label>
							<label class="we-radio" for="bathroom_{$uid}_2">
								<input type="radio" name="bathroom" value="2" id="bathroom_{$uid}_2" 
									   {if $oneItem.bathroom eq 2}checked{/if}>
								<span class="lbl_text {if $oneItem.bathroom_count gt 5}cursor-not-allowed{/if}">2</span>
							</label>
							<label class="we-radio" for="bathroom_{$uid}_3">
								<input type="radio" name="bathroom" value="3" id="bathroom_{$uid}_3" 
									   {if $oneItem.bathroom eq 3}checked{/if}>
								<span class="lbl_text {if $oneItem.bathroom_count gt 5}cursor-not-allowed{/if}">3</span>
							</label>
							<label class="we-radio" for="bathroom_{$uid}_4">
								<input type="radio" name="bathroom" value="4" id="bathroom_{$uid}_4" 
									   {if $oneItem.bathroom eq 4}checked{/if}>
								<span class="lbl_text {if $oneItem.bathroom_count gt 5}cursor-not-allowed{/if}">4</span>
							</label>
							<label class="we-radio" for="bathroom_{$uid}_5">
								<input type="radio" name="bathroom" value="5" id="bathroom_{$uid}_5" 
									   {if $oneItem.bathroom eq 5}checked{/if}>
								<span class="lbl_text {if $oneItem.bathroom_count gt 5}cursor-not-allowed{/if}">5</span>
							</label>
						</div>
						<div class="input-group flex-fill">
							<input class="form-control no-focus w-100 input_no_outer_spin" type="number" placeholder="Nhập số" onChange="$Core.crm.loadNumberRadio(this,event)" data-field="bathroom" name="bathroom_count" value="{if $oneItem.bathroom_count gt 5}{$oneItem.bathroom_count}{/if}">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="col-form-label">Đặc điểm</label>
				<textarea id="textarea" class="form-control no-focus" maxlength="300" rows="5" 
					name="content" placeholder="Nhập đặc điểm mô tả ... ">{$oneItem.content}</textarea>
				<div class="form-text">Tối đa 300 ký tự</div>
			</div>
		</div>
		<div class="modal-footer justify-content-between">
			<input type="hidden" name="need_id" value="{$need_id}">
			<div class="form-check form-switch">
				<input type="checkbox" class="form-check-input" name="is_hot" value="1" 
					id="is_hot_{$uid}"{if $oneItem.is_hot eq 1} checked{/if}>
				<label class="form-check-label" for="is_hot_{$uid}">Nhu cầu HOT</label>
			</div>
			<div class="buttons">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button class="btn btn-primary" type="button" toId="{$toId}" 
					onClick="$Core.crm.save_need(this, event)" customer_id="{$customer_id}">Lưu lại</button>
			</div>
		</div>
	</form>
</div>