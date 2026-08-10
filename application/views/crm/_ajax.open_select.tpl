<div class="modal-dialog modal-dialog-centered" style="max-width:34rem">
	<form action="#" method="POST" onsubmit="return false;" class="modal-content">
		<div class="modal-header pb-3 border-bottom">
			<h5 class="modal-title">Tìm kiếm khách hàng</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="d-flex flex-wrap align-items-center justify-content-between bg-grayter p-2 rounded-2 mb-2">
				<div class="d-flex gap-1 align-items-center xs:w-100">
					<div class="select2-sm w-px-150 flex-fill">
						<select onChange="$Core.global.crm.do_ms_search(this, event)" data-field="status_id" 
							class="form-control iso-select2 search_field" holderG="_customer" data-width="100%">
							{$clsProperty->getSelectSingleProperty('CUSTOMER_STATUS',0,0,"Tình trạng")}
						</select>
					</div>
					<div class="select2-sm w-px-150 flex-fill">
						<select onChange="$Core.global.crm.do_ms_search(this, event)" data-field="resource_id" 
							class="form-control iso-select2 search_field" holderG="_customer" data-width="100%">
							{$clsProperty->getSelectSingleProperty('_CUSTOMER_RESOURCES',0,0,"Nguồn khách")}
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
									<select onChange="$Core.global.crm.do_ms_search(this, event)" data-field="campaign_id" 
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
			</div>
			<div class="holder_ms_customers max-height-500 webkit-scrollbar overflow-y-auto" 
				onscroll="$Core.global.crm.scroll_ms_customers(this, event)">
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
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" disabled onClick="$Core.global.crm.do_select_customer(this, event)" 
				class="btn btn-primary js__start_select_customer" call_from="{$call_from}" uid="{$uid}">Lựa chọn</button>
		</div>
	</form>
</div>