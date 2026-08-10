<div class="modal right fade modal_setting_field" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<form method="post" action="#" class="modal-content" onsubmit="return false;">
			<div class="modal-header border-bottom">
				<h5 class="modal-title">Tùy chỉnh cột</h5>
				<button type="button" class="btn btn-outline-none px-0 fs-4" data-bs-dismiss="modal">
					<i class='bx bx-x fs-4'></i>
				</button>
			</div>
			<div class="modal-body pt-1 px-0 overflow-hidden pb-0">
				<div class="form-row h-100">
					<div class="col-6 h-100">
						<div class="modal_left h-100">
							<div class="input-group input-group-merge px-3 mb-2">
								<span class="input-group-text">
									<i class="icon-base bx bx-search"></i>
								</span>
								<input type="text" class="form-control" placeholder="Tìm kiếm cột" 
									onKeyUp="$Core.data_central.search_field(this, event)" name="keyword">
							</div>
							<div class="d-flex flex-column scroll-y-auto lst_field_data" style="height:calc(100% - 40px)">
								<p class="mb-2 fs-14 px-3 text-dark">Chọn trường</p>
								<ul class="list-checkbox-item list-unstyled">
									{foreach from=$lstFieldData item=_oItem key=key}
									<li class="item-menu-settings-column crm-flex crm-align-items-center px-3 py-1" title="{$_oItem.title}">
										<label class="form-check mb-0" for="field_{$uid}_{$key}">
											<input class="form-check-input" type="checkbox" value="{$key}" id="field_{$uid}_{$key}" onChange="$Core.data_central.add_setting_field(this,event)"  toId="item_{$uid}_{$key}" data-title="{$_oItem.title}"  data-key="{$key}" {if $clsISO->checkItemInArray($key,$fieldData)}checked{/if} >
											<span class="form-check-label">{$_oItem.title}</span>
										</label>
									</li>
									{/foreach}
								</ul>
							</div>
						</div>
					</div>
					<div class="col-6 h-100">
						<div class="modal_right h-100">
							<div class="d-flex justify-content-between align-items-center p-2">
								<span class="column_count text-dark">Đã chọn (<span class="text_number">0</span>)</span>
								<span class="cursor-pointer text-main" onClick="$Core.data_central.delete_all_field_selected(this,event)">Xóa tất cả</span>
							</div>
							<div class="d-flex flex-column scroll-y-auto px-2" style="height: calc(100% - 40px)">
								<ul class="list_field-selected list-unstyled">
								{if !empty($lstFieldSelected)}
									{foreach from=$lstFieldSelected item=_oItem key=key}
									<li class="item_field_selected d-flex justify-content-between align-items-center p-2 bg-lighter rounded-1 mb-2 text-black cursor-pointer" title="{$_oItem.title}" id="item_{$uid}_{$key}" key="{$key}">
										<div class="crm-flex filed-select">
											<i class='bx bx-grid-vertical'></i>
											<span class="title-ellipsis text misa-label">{$_oItem.title}</span>
										</div>
										<button class="btn btn-sm text-main p-0" type="button" onClick="$Core.data_central.delete_setting_field(this,event)" toId="field_{$uid}_{$key}"><i class='bx bx-x'></i></button>
									</li>
									{/foreach}
								{else}
									<li class="text-center">Không có trường nào được chọn</li>
								{/if}
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer py-2 bg-lighter box-shadow">
				<input type="hidden" name="gid" value="{$uid}">
				<input type="hidden" name="view_by" value="{$view_by}">
				<input type="hidden" name="field_name" value="{$field_name}">
				<button type="button" class="btn btn-link text-black" 
					onClick="$Core.data_central.load_setting_default_field(this,event)">Mặc định</button>
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
				<button type="button" {$props} onClick="$Core.data_central.save_setting_field(this, event)" class="btn btn-primary">Lưu</button>
			</div>
		</form>
	</div>
</div>