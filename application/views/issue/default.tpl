{$scriptJs}
<script type="text/javascript">
	var issue_type = '{$issue_type}';
</script>
<div class="container-xxl flex-grow-1 pt-0 container-p-y issue_page">
	<div class="issue-head">
		<div class="ih-title">
			<h1>Quản lý công việc</h1>
			<p class="ih-sub">Kế hoạch công việc của bạn và đội nhóm</p>
		</div>
		<div class="issue-head-actions">
			<input type="radio" name="view" class="js__rdo-view" value="grid"{if $_ss_view eq 'grid'} checked{/if} hidden>
			<input type="radio" name="view" class="js__rdo-view" value="kaban"{if $_ss_view eq 'kaban'} checked{/if} hidden>
			<input type="radio" name="view" class="js__rdo-view" value="calendar"{if $_ss_view eq 'calendar'} checked{/if} hidden>
			<input type="radio" name="view" class="js__rdo-view" value="timeline"{if $_ss_view eq 'timeline'} checked{/if} hidden>
			<form class="issue-search" onsubmit="$Core.issue.do_search(this,event);return false;">
				<i class="bx bx-search"></i>
				<input type="text" class="search_field" data-field="keysearch" placeholder="Tìm công việc, mã, người...">
			</form>
			<div class="dropdown">
				<button type="button" class="issue-icon-btn dropdown-toggle hide-arrow" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Bộ lọc"><i class="bx bx-filter"></i></button>
				<div class="dropdown-menu dropdown-menu-end p-3 issue-filter-menu">
					<div class="mb-2">
						<div class="form-label mb-1 fw-semibold">Ngày tạo</div>
						<div class="input-group input-date-picker">
							<input type="text" class="form-control from_date search_field" data-field="start_date" placeholder="Từ ngày">
							<input type="text" class="form-control to_date search_field" data-field="end_date" placeholder="Đến ngày">
						</div>
					</div>
					{if !$clsISO->checkSale()}
					<div class="mb-2">
						<div class="form-label mb-1 fw-semibold">Người giao</div>
						<select class="iso-selectizeNotSearch search_field" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" data-placeholder="Người giao" data-field="user_id" data-allow-clear="true" data-optgroup="false"></select>
					</div>
					<div class="mb-2">
						<div class="form-label mb-1 fw-semibold">Người nhận</div>
						<select class="iso-selectizeNotSearch search_field" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" data-placeholder="Người nhận" data-field="assign_to_id" data-allow-clear="true" data-optgroup="false"></select>
					</div>
					{/if}
					<div class="row g-2 mb-2">
						<div class="col-6">
							<div class="form-label mb-1 fw-semibold">Ưu tiên</div>
							<select class="form-control form-select search_field" data-field="priority_id">
								<option value="0">Tất cả</option>
								{$clsProperty->getSelectByProperty('_ISSUE_PRIORITY',0)}
							</select>
						</div>
						<div class="col-6">
							<div class="form-label mb-1 fw-semibold">Tình trạng</div>
							<select class="form-control form-select search_field" data-field="status_id">
								<option value="0">Tất cả</option>
								{$clsProperty->getSelectByProperty('_ISSUE_STATUS',0)}
							</select>
						</div>
					</div>
					<div class="form-check form-switch mb-3">
						<input type="checkbox" class="form-check-input search_field" id="issue_all" value="1" data-field="is_all">
						<label class="form-check-label" for="issue_all">Tất cả công việc</label>
					</div>
					<button type="button" onClick="$Core.issue.do_search(this, event);" class="btn btn-primary w-100"><i class="bx bx-search me-1"></i> Tìm kiếm</button>
				</div>
			</div>
			<div class="dropdown">
				<button type="button" class="issue-icon-btn dropdown-toggle hide-arrow" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Sắp xếp"><i class="bx bx-sort-alt-2"></i></button>
				<div class="dropdown-menu dropdown-menu-end p-3 issue-sort-menu">
					<div class="mb-2">
						<div class="form-label mb-1 fw-semibold">Sắp xếp theo</div>
						<select class="form-control form-select search_field" data-field="sort_by" onchange="$Core.issue.do_search(this,event);">
							<option value="reg_date"{if $_ss_sort_by eq 'reg_date'} selected{/if}>Ngày tạo</option>
							<option value="status"{if $_ss_sort_by eq 'status'} selected{/if}>Tình trạng</option>
						</select>
					</div>
					<div>
						<div class="form-label mb-1 fw-semibold">Số dòng / trang</div>
						<select class="form-control form-select search_field" data-field="per_page" onchange="$Core.issue.do_search(this,event);">
							{foreach from=$list_record_pages item=_pp}
							<option value="{$_pp}"{if $_ss_per_page eq $_pp} selected{/if}>{$_pp}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
			<button type="button" title="Thêm mới" onClick="$Core.issue.open_issue(this, event)" issue_id="0" parent_id="0" class="issue-btn-add">
				<i class="bx bx-plus"></i>{if $deviceType ne 'phone'} Thêm mới{/if}
			</button>
		</div>
	</div>
	<div class="issue-toolbar">
		<div class="issue-viewtabs">
			<button type="button" class="issue-tab{if $_ss_view eq 'grid'} active{/if}" onClick="$('.js__rdo-view[value=grid]').prop('checked',true); $Core.issue.set_view(this,event);"><i class="bx bx-list-ul"></i> Danh sách</button>
			<button type="button" class="issue-tab{if $_ss_view eq 'kaban'} active{/if}" onClick="$('.js__rdo-view[value=kaban]').prop('checked',true); $Core.issue.set_view(this,event);"><i class="bx bx-grid-alt"></i> Bảng</button>
			<button type="button" class="issue-tab{if $_ss_view eq 'calendar'} active{/if}" onClick="$('.js__rdo-view[value=calendar]').prop('checked',true); $Core.issue.set_view(this,event);"><i class="bx bx-calendar"></i> Lịch</button>
			<button type="button" class="issue-tab{if $_ss_view eq 'timeline'} active{/if}" onClick="$('.js__rdo-view[value=timeline]').prop('checked',true); $Core.issue.set_view(this,event);"><i class="bx bx-git-branch"></i> Timeline</button>
		</div>
		<span class="issue-tb-divider"></span>
		<div class="issue-chips">
			<span class="issue-chip" data-status="203" onClick="$Core.issue.filter_status(this, event)"><span class="dot d-203"></span>Mới</span>
			<span class="issue-chip" data-status="204" onClick="$Core.issue.filter_status(this, event)"><span class="dot d-204"></span>Đang xử lý</span>
			<span class="issue-chip" data-status="205" onClick="$Core.issue.filter_status(this, event)"><span class="dot d-rev"></span>Chờ duyệt</span>
			<span class="issue-chip" data-status="207" onClick="$Core.issue.filter_status(this, event)"><span class="dot d-207"></span>Hoàn thành</span>
		</div>
		<input type="hidden" class="search_field" data-field="mine" value="0">
		<button type="button" class="issue-myday" onClick="$Core.issue.toggle_myday(this, event)"><i class="bx bx-bolt-circle"></i> Việc của tôi</button>
	</div>
	{if $_ss_view eq "grid"}
		{foreach name=i from=$list_issue_blocks key=block item=_oBlock}
		<div class="issue-group">
			<div class="issue-group-head">
				<span class="issue-group-bar bar-{$block|replace:'_':''}"></span>
				<span class="issue-group-name">{$_oBlock.title}</span>
			</div>
			<div class="issue-group-card">
				{if $deviceType ne 'phone'}
				<div class="issue-grid-head">
					<span>TÊN CÔNG VIỆC</span>
					<span>TRẠNG THÁI</span>
					<span>THỜI GIAN</span>
					<span class="tc">GIAO / NHẬN</span>
					<span>ƯU TIÊN</span>
					<span>TIẾN ĐỘ</span>
					<span>BẤM GIỜ</span>
					<span></span>
				</div>
				{/if}
				<div block="{$block}" delay="{$_oBlock.delay}" class="tableIssue">
					<div class="holder_issue_{$block}">
						<div class="ic-empty"><p>Đang tải...</p></div>
					</div>
				</div>
				<input type="hidden" name="hid_current_page_{$block}" value="1" />
				<div id="pager_{$block}" class="px-3 py-2 ui-pagination sample-pagination"></div>
			</div>
		</div>
		{/foreach}
	{elseif $_ss_view eq "calendar"}
		<div id="issue-calendar-holder"><div class="ic-empty"><p>Đang tải...</p></div></div>
	{elseif $_ss_view eq "timeline"}
		<div id="issue-timeline-holder"><div class="ic-empty"><p>Đang tải...</p></div></div>
	{else}
		<div class="issue-kb-board" id="kanban-canvas"></div>
	{/if}
</div>
{literal}
<script type="text/javascript">
	$(function(){
		var current_view = $('.js__rdo-view:checked').val();
		if(current_view == 'grid'){
			$('.tableIssue').each((_i, _elem) => {
				var block = $(_elem).attr('block'),
					delay = $(_elem).attr('delay');
				setTimeout(() => {
					$Core.issue.load_issues(block, {});
				}, delay);
			});
		} else if(current_view == 'calendar'){
			$Core.issue.load_calendar({});
		} else if(current_view == 'timeline'){
			$Core.issue.load_timeline({});
		} else {
			$Core.issue.load_issue_kanban("",{});
		}
	})
</script>
{/literal}
