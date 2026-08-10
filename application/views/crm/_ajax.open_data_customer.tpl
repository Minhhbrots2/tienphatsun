<div class="modal-dialog">
	<form action="#" method="POST" onsubmit="return false;" class="modal-content">
		<div class="modal-header pb-3 border-bottom">
			<h5 class="modal-title">Phân khách hàng cho Sales</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="d-flex flex-wrap align-items-center justify-content-between bg-grayter p-2 rounded-2 mb-2">
				<div class="d-flex gap-1 align-items-center xs:w-100 mb-2 mb-lg-0">
					<div class="select2-sm w-px-150 flex-fill">
						<select onChange="$Core.crm.do_share_search(this, event)" data-field="status_id" 
							class="form-control iso-select2 search_field" holderG="_customer" data-width="100%">
							{$clsProperty->getSelectSingleProperty('CUSTOMER_STATUS',0,0,"Tình trạng")}
						</select>
					</div>
					<div class="select2-sm w-px-150 flex-fill">
						<select onChange="$Core.crm.do_share_search(this, event)" data-field="resource_id" 
							class="form-control iso-select2 search_field" holderG="_customer" data-width="100%">
							{$clsProperty->getSelectSingleProperty('_CUSTOMER_RESOURCES',0,0,"Phòng ban")}
						</select>
					</div>
					<div class="dropdown">
						<button type="button" class="btn btn-sm btn-icon dropdown-toggle btn-outline-default hide-arrow" 
							data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
						<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-250" data-popper-placement="top-end">
							<div class="dropdown-header">Tìm kiếm</div>
							<div class="px-3 pb-3">
								<div class="form-group mb-2">
									<label class="form-label mb-1">Chiến dịch</label>
									<select onChange="$Core.crm.do_share_search(this, event)" data-field="campaign_id" 
									class="form-control form-control-sm form-select search_field" holderG="_customer" data-width="100%">
										<option value="0">Chiến dịch</option>
										{if !empty($list_campaigns)}
											{foreach from=$list_campaigns item = _oC}
											<option value="{$_oC.campaign_id}">{$_oC.title}</option>
											{/foreach}
										{/if}
									</select>
								</div>
								<div class="form-group">
									<label class="form-label mb-1">Sắp xếp</label>
									<div class="clearfix"></div>
									<div class="btn-group d-flex" role="group" >
										<input type="radio" holderG="_customer" class="btn-check js__-list" id="{$uid}_owner" value="owner" checked>
										<label data-toggle="ripple" class="btn btn-sm btn-outline-default" for="{$uid}_owner">Gần nhất</label>
										<input type="radio" holderG="_customer" class="btn-check js__-list" id="{$uid}_following" value="following">
										<label data-toggle="ripple" class="btn btn-sm btn-outline-default" for="{$uid}_following">Xa nhất</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="d-flex xs:w-100 align-items-center gap-1">
					<button type="button" class="btn flex-fill btn-sm btn-outline-primary">
						Đã chọn <span uid="{$uid}" class="badge js__selected_customer">0</span>
					</button>
					<button type="button" onClick="$Core.crm.remove_selected_cus(this, event)" uid="{$uid}" 
					class="btn btn-icon btn-sm btn-outline-default js__remove_selected_customer d-none">
						<i class="bx bx-x"></i>
					</button>
				</div>
			</div>
			<div class="d-inline-flex gap-1 align-items-center mb-2">
				<a href="javascript:void(0);" class="text-muted" holderG="_customer" data-bs-toggle="tooltip" title="Chọn tất cả" onClick="$Core.crm.check_all(this, event)">Chọn tất cả</a>
				<span>/</span>
				<a href="javascript:void(0);" class="text-muted" data-bs-toggle="tooltip" title="Bỏ chọn tất cả"  onClick="$Core.crm.uncheck_all(this, event)" holderG="_customer">Bỏ chọn tất cả</a>
			</div>
			<div class="holder_share_customers webkit-scrollbar overflow-y-auto" 
				onscroll="$Core.crm.scroll_share_customers(this, event)" holderG="_customer" style="max-height: calc(100vh - 300px); min-height: 200px">
				{section name=i loop=$list_preloaders max=10}
				<div class="d-flex rounded-2 p-2 mb-1">
					<label class="d-flex align-items-center gap-2 ant-checkbox">
						<div class="animate-bg avatar-xs avatar rounded-pill"></div>
						<div class="d-flex gap-2 align-items-center">
							<div class="d-flex flex-column gap-1">
								<div class="animate-bg w-px-250 rounded-2 h-px-15"></div>
								<div class="animate-bg w-px-100 rounded-2 h-px-15"></div>
							</div>
						</div>
					</label>
				</div>
				{/section}
			</div>
			<div class="js__message-container"></div>
		</div>
		<div class="modal-footer border-top">
			<input type="hidden" name="hid" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" disabled onClick="$Core.crm.do_share_customer(this, event)" 
				class="btn btn-primary js__start_share_customer">Thực hiện</button>
		</div>
	</form>
</div>