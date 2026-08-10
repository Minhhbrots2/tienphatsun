<div class="modal-dialog modal-md">
	<form action="#" method="POST" onsubmit="return false;" class="modal-content">
		<div class="modal-header pb-3 border-bottom">
			<h5 class="modal-title">Chiến dịch</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="bg-label-warning p-3 rounded-2 mb-2">
				<input type="hidden" name="list_ids" value="{$list_ids|escape:'html'}" >
				Tổng {$total_data} data đã chọn
			</div>
			<div class="row">
				<div class="col-12 mb-2">
					<div class="form-group">
						<label class="form-label mb-1">Tiêu đề</label>
						<input type="text" class="form-control required" name="title" value="{$oneItem.title}" >
					</div>
				</div>
				<div class="col-12 mb-2">
					<div class="form-group">
						<label class="col-form-label">Nội dung</label>
						<textarea class="form-control form_field" name="intro" cols="255" rows="5" id="{$clsISO->getUniqid()}">{$oneItem.intro}</textarea>
					</div>	
				</div>
				<div class="col-12">
					<div class="d-flex flex-wrap align-items-center justify-content-between bg-lighter p-2 rounded-2 mb-2">
						<div class="d-flex align-items-center gap-2 mb-2 mb-lg-0">
							<div class="w-px-125 select2-sm">
								<select onChange="$Core.data_central.do_share_search(this, event)" data-field="department_id" 
									class="form-control iso-select2 search_field" holderG="_staff" data-width="100%">
									{$clsProperty->getSelectSingleProperty('_DEPARTMENT',$smarty.const._DEPARTMENT_SALE_ID,0,"Phòng ban")}
								</select>
							</div>
							<div class="form-group">
								<input type="text" onChange="$Core.data_central.do_share_search(this, event)" onkeyup="$Core.data_central.enter_share_search(this, event)" holderG="_staff" class="form-control form-control-sm search_field" data-field="keysearch" placeholder="Nhập từ khóa & nhấn Enter" />
							</div>
						</div>
						<div class="d-flex align-items-center gap-1 xs:w-100">
							<button type="button" class="btn flex-fill btn-sm btn-outline-primary">
								Đã chọn <span uid="{$uid}" class="badge js__selected_staff">0</span>
							</button>
							<button type="button" onClick="$Core.data_central.remove_selected_staff(this, event)" uid="{$uid}" 
							class="btn btn-icon btn-sm btn-outline-default js__remove_selected_staff d-none">
								<i class="bx bx-x"></i>
							</button>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="d-inline-flex gap-1 align-items-center mb-2">
						<a href="javascript:void(0);" class="text-muted" holderG="_staff" data-bs-toggle="tooltip" 
							title="Chọn tất cả" onClick="$Core.data_central.check_all(this, event)">Chọn tất cả</a>
						<span>/</span>
						<a href="javascript:void(0);" class="text-muted" data-bs-toggle="tooltip" title="Bỏ chọn tất cả" 
							onClick="$Core.data_central.uncheck_all(this, event)" holderG="_staff">Bỏ chọn tất cả</a>
					</div>
					<div class="holder_staff_customers max-height-250 webkit-scrollbar overflow-y-auto">
						<div class="max-height-250 overflow-y-auto webkit-scrollbar">
							{section name=i loop=$list_preloaders max=10}
							<div class="d-flex rounded-2 p-2 mb-1">
								<label class="d-flex align-items-center gap-2 ant-checkbox">
									<div class="animate-bg avatar-xs avatar rounded-pill"></div>
									<div class="d-flex gap-2 align-items-center">
										<div class="animate-bg avatar-xs avatar rounded-pill"></div>
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
				</div>
			</div>
			<div class="js__message-container"></div>
		</div>
		<div class="modal-footer border-top">
			<input type="hidden" name="hid" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" onClick="$Core.data_central.do_add_campaign(this, event)" 
				class="btn btn-primary js__start_share_customer">Tạo</button>
		</div>
	</form>
</div>