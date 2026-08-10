<form method="POST"{if $deviceType eq 'phone'} class="w-100"{/if}>
	<div class="d-flex search">
		<div class="input-group dropdown">
			<input type="text" class="form-control search_field" data-field="keysearch" 
			placeholder="Search..." aria-label="Search...">
			<button type="button" class="btn btn-icon btn-outline-default bg-white hide-arrow dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
			<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-350" data-popper-placement="top-end">
				<div class="p-3">
					<div class="form-group mb-2">
						{assign var = toId value = $clsISO->getUniqid()}
						<div class="btn-group w-100" role="group" aria-label="Hiển thị">
							<input type="radio" onchange="$Core.issue.set_view(this,event);" class="btn-check js__rdo-view" 
								name="view" id="{$toId}_list" value="grid"{if $_ss_view eq 'grid'} checked{/if}>
							<label class="btn btn-outline-default" for="{$toId}_list"><i class="bx bx-table"></i> Bảng</label>
							<input type="radio" onchange="$Core.issue.set_view(this,event);" class="btn-check js__rdo-view" 
								name="view" id="{$toId}_grid" value="kaban"{if $_ss_view eq 'kaban'} checked{/if}>
							<label class="btn btn-outline-default" for="{$toId}_grid"><i class="bx bx-grid"></i> Kaban</label>
						</div>
					</div>
					<div class="form-group mb-2">
						<div class="form-label mb-1">Ngày tạo</div>
						<div class="input-group mb-2 input-date-picker w-100">
							<i class="ico ico-calendar"></i>
							<input type="text" value="{$start_date}" class="form-control from_date w-px-100 search_field" 
							placeholder="Từ ngày" data-field="start_date">
							<input type="text" value="{$end_date}" class="form-control to_date w-px-100 search_field" placeholder="Đến ngày" data-field="end_date">
						</div>
					</div>
					{if !$clsISO->checkSale()}
					<div class="form-group mb-2">
						<div class="form-label mb-1">Người giao</div>
						<select class="iso-selectizeNotSearch search_field" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" data-placeholder="Người giao" data-field="user_id" data-allow-clear="true" data-optgroup="false">
						</select>				
					</div>
					<div class="form-group mb-2">
						<div class="form-label mb-1">Người nhận</div>
						<select class="iso-selectizeNotSearch search_field" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" data-placeholder="Người nhận" data-field="assign_to_id" data-allow-clear="true" data-optgroup="false"></select>				
					</div>
					{/if}
					<div class="form-row mb-2">
						<div class="col-6 col-md-6">
							<div class="form-floating">
								<select id="_ISSUE_PRIORITY" class="form-control search_field" data-field="priority_id">
									<option value="0">Ưu tiên</option>
									{$clsProperty->getSelectByProperty('_ISSUE_PRIORITY',0)}
								</select>
								<label for="_ISSUE_PRIORITY">Tình trạng</label>
							</div>
						</div>
						<div class="col-6 col-md-6">
							<div class="form-floating">
								<select id="_ISSUE_STATUS" class="form-control search_field" data-field="status_id">
									<option value="0">Tình trạng</option>
									{$clsProperty->getSelectByProperty('_ISSUE_STATUS',0)}
								</select>
								<label for="_ISSUE_STATUS">Tình trạng</label>
							</div>
						</div>
					</div>
					<hr class="my-2" />
					<div class="form-check form-switch cursor-pointer">
						<input type="checkbox" class="form-check-input search_field" id="issue_all" value="1" data-field="is_all">
						<label class="form-check-label" for="issue_all">Tất cả công việc</label>
					</div>
					<hr class="my-2" />
					<div class="form-row mb-2">
						<div class="col-6 col-md-6">
							<div class="form-floating">
								<select id="STATUS" class="form-control search_field" data-field="sort_by">
									<option{if $_ss_sort_by eq 'status'} selected{/if} value="status">Tình trạng</option>
									<option{if $_ss_sort_by eq 'reg_date'} selected{/if} value="reg_date">Ngày tạo</option>
								</select>
								<label for="STATUS">Sắp sếp theo</label>
							</div>
						</div>
						<div class="col-6 col-md-6">
							<div class="form-floating">
								<select id="PER_PAGE" class="form-control search_field" data-field="per_page">
									{foreach name=i from=$list_record_pages item = per_page}
									<option{if $_ss_per_page eq $per_page} selected{/if} value="{$per_page}">{$per_page} record</option>
									{/foreach}
								</select>
								<label for="PER_PAGE">Số record/page</label>
							</div>
						</div>
					</div>
					<div class="form-row">
						<button type="button" onClick="$Core.issue.do_search(this, event);" class="btn btn-primary no-wrap">
							<span>{$clsISO->makeIcon('bx-search', 'Tìm kiếm')}</span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>