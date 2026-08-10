<script type="text/javascript">var crm_date_format = "dd/mm/yy", crm_datepicker_format = { changeMonth: true, changeYear: true, showButtonPanel: true, dateFormat: crm_date_format, yearRange: "1900:2050" };</script>
{assign var = uid value = $clsISO->getUniqid()}{assign var = gId value = $clsISO->getUniqid()}{assign var = pId value = $clsISO->getUniqid()}
<div class="container-xxl flex-grow-1 container-p-y pt-2 crm_page">
	<!-- ===== Header (Lăng kính Sale) ===== -->
	<div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
		<h4 class="fw-bold mb-0 crm-page-title">Quản lý khách hàng</h4>
		<!-- <div class="text-muted crm-page-desc">Quản lý khách hàng trung tâm.</div> -->
		<div class="d-flex gap-1 gap-lg-2 align-items-center flex-wrap">
			{if $clsISO->checkPermission('import_crm') && $deviceType eq 'computer'}
			<button class="btn btn-outline-default" onclick="$Core.crm.open_import(this, event)" data-toggle="ripple" uid="{$uid}" route="/import" 
				class="btn btn-outline-secondary text-nowrap"><i class="bx bx-upload me-1"></i> Nhập Excel
			</button>
			{/if}
			{if $clsISO->checkPermission('customer_new')}
			<button onclick="$Core.global.crm.add_customer(this, event)" uid="{$uid}" customer_id="0" openfrom="_crm" 
				class="btn btn-primary goLink js__add-customer text-nowrap" data-toggle="ripple" route="/customer/create/0"><i class="bx bx-plus me-1"></i> Thêm khách</button>
			{/if}
			<div class="btn-group">
				<button id="{$gId}" type="button" class="btn btn-icon btn-outline-default hide-arrow {$gId} dropdown-crm-search dropdown-toggle" 
					data-bs-toggle="dropdown" data-toggle="ripple" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">{$clsISO->makeIcon('bx-cog')}</button>
				<div class="dropdown-menu dropdown-menu-end" data-popper-placement="top-end">
					{if $clsISO->checkPermission('import_crm') && $deviceType eq 'computer'}
					<a class="dropdown-item text-danger cursor-pointer" data-toggle="ripple" onclick="$Core.crm.open_import(this, event)">{$clsISO->makeIcon('bx bx-upload','Nhập Excel')}</a>
					{/if}
					<a class="dropdown-item text-danger cursor-pointer" data-toggle="ripple" onclick="$Core.crm.open_req_customer(this, event)">{$clsISO->makeIcon('bx bx-user','Yêu cầu cấp data')}</a>
					<a class="dropdown-item cursor-pointer" data-toggle="ripple" onclick="$Core.crm.manager_campaign(this, event)">{$clsISO->makeIcon('bx bx-target-lock','Quản lý chiến dịch')}</a>
					{if $clsCustomer->isFullPermiss()}<a class="dropdown-item cursor-pointer" data-toggle="ripple" onclick="$Core.crm.open_task_setting(this, event)">{$clsISO->makeIcon('bx bx-slider-alt','Thiết lập tác nghiệp')}</a>{/if}
					<a class="dropdown-item cursor-pointer" href="/crm/assigned/" data-toggle="ripple">{$clsISO->makeIcon('bx bx-paper-plane','Khách tôi giao')}</a>
					{if ($clsCustomer->isTeamManager() || $clsCustomer->isFullPermiss() || $clsISO->checkPermissionGroup('SALE_DIRECTOR_ONLY') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')) && !$clsCustomer->isMarketing()}
					<div class="dropdown-divider"></div>
					<a class="dropdown-item cursor-pointer" href="/crm/team/" data-toggle="ripple">{$clsISO->makeIcon('bx bx-group','Thống kê phòng ban')}</a>
					{/if}
				</div>
			</div>
			<button title="Hướng dẫn sử dụng" onclick="$Core.crm.open_help(this, event)" data-toggle="ripple" 
				class="btn btn-icon btn-outline-default d-none d-lg-inline-flex">{$clsISO->makeIcon('bx-help-circle')}</button>
		</div>
	</div>
	<!-- ===== Dashboard: KPI + Next-best-action + bảng SLA ===== -->
	<!-- <div id="box_sale_dashboard" class="mb-3"></div> -->
	<!-- ===== Móc JS ẩn (đếm phụ) ===== -->
	<div class="d-none"><span class="total_converted">0</span></div>
	<!-- ===== Boxes cho menu Quản lý (Marketing/Tăng trưởng/Sức khỏe nhóm) + warning + worklist ===== -->
	<div id="box_marketing" class="mb-3 d-none"></div>
	<div id="box_growth" class="mb-3 d-none"></div>
	<div id="box_team" class="mb-3 d-none"></div>
	<div id="box_worklist" class="d-none"></div>
	<div id="box_warning" class="d-none"></div>
	<!-- ===== Card: Khách của tôi (chip lọc + bảng) ===== -->
	<div class="card">
		<div class="card-header crm-list-head d-flex align-items-center justify-content-between flex-wrap gap-2 pt-3 pb-0 border-0">
			<h5 class="mb-0 d-flex align-items-center gap-2">
				<i class="bx bx-group text-primary"></i> Khách của tôi 
				<span class="text-muted fw-normal" style="font-size:13px">· <span class="total_manage">0</span> khách</span>
			</h5>
			<div class="d-flex align-items-center gap-2 flex-wrap crm-toolbar">
				<div class="btn-group btn-group-sm crm-role-tabs" role="group" aria-label="Phạm vi khách">
					<input type="radio" onChange="$Core.crm.do_search(this, event)" holderG="{$holderG}" class="btn-check js_crm-filter-list" 
						name="tab" id="{$uid}_owner" value="owner"{if $tab eq 'owner' || $tab eq ''} checked{/if}>
					<label class="btn btn-outline-secondary" for="{$uid}_owner">Phụ trách <span class="total_manage badge bg-label-secondary ms-1">0</span></label>
					<input type="radio" onChange="$Core.crm.do_search(this, event)" holderG="{$holderG}" class="btn-check js_crm-filter-list" name="tab" 
						id="{$uid}_following" value="following"{if $tab eq 'following'} checked{/if}>
					<label class="btn btn-outline-secondary" for="{$uid}_following">Theo dõi <span class="total_assign badge bg-label-secondary ms-1">0</span></label>
					{if ($clsCustomer->isTeamManager() || $clsCustomer->isFullPermiss()) && !$clsCustomer->isMarketing()}
					<input type="radio" onChange="$Core.crm.do_search(this, event)" holderG="{$holderG}" class="btn-check js_crm-filter-list"
						name="tab" id="{$uid}_team" value="team"{if $tab eq 'team'} checked{/if}>
					<label class="btn btn-outline-secondary" for="{$uid}_team">{$clsCustomer->getTeamLabel()|escape} <span class="total_team badge bg-label-secondary ms-1">0</span></label>
					{/if}
				</div>
				<input type="text" class="form-control form-control-sm search_crm_field search_field crm-mb-search d-md-none"
					name="keysearch" data-field="keysearch" holderG="_desktop" onClick="this.select();" placeholder="Tìm tên hoặc SĐT khách hàng">
				<div class="dropdown crm-mb-filterwrap">
					<button id="{$gId}" type="button" title="Tìm kiếm / Bộ lọc" aria-label="Tìm kiếm" class="btn btn-sm btn-outline-default hide-arrow {$gId} dropdown-crm-search dropdown-toggle crm-btn-icon crm-mb-filterbtn"
						data-bs-toggle="dropdown" data-toggle="ripple" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
						<i class="bx bx-search d-none d-md-inline"></i>
						<i class="bx bx-slider-alt d-md-none"></i>
						<span class="d-md-none ms-1">Bộ lọc</span>
					</button>
					<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-350" data-popper-placement="bottom-end">
						{$core->getBlock('crm_search',['gId'=>$gId, 'holderG'=>'_desktop'])}
					</div>
				</div>
				{if $clsCustomer->isRootProfile()}
				<button type="button" disabled="disabled" onClick="$Core.crm.un_share(this,event)" class="btn btn-sm btn-outline-secondary btn_unshare{if $tab ne 'following'} d-none{/if}">
					<i class="bx bx-share"></i> Thu hồi
				</button>
				{/if}
				<button type="button" disabled data-toggle="webui-popover" data-trigger="click" data-type="async" data-closeable="false" data-width="300px" 
					data-url="/index.php?mod={$mod}&act=load_pop_action" class="btn btn-sm btn-primary btn-crm-action">
					<i class="bx bx-bolt-circle me-1"></i> Hành động <i class="bx bx-chevron-down ms-1"></i>
				</button>
			</div>
		</div>
		<div class="card-body pt-3 pb-1">
			<input type="hidden" class="search_field js__crm-task-filter" data-field="task_id" value="0">
			<input type="hidden" class="search_field js__crm-hot-filter" data-field="hot" value="{$get_hot|default:0}">
			<div class="crm-chip-row">
				<div class="crm-chip-mode xs:w-100 btn-group btn-group-sm flex-shrink-0" role="group" aria-label="Chế độ lọc nhanh">
					<button type="button" class="btn btn-outline-secondary active js__crm-chip-mode" data-mode="status"><i class="bx bx-purchase-tag-alt"></i> Tình trạng</button>
					<button type="button" class="btn btn-outline-secondary js__crm-chip-mode" data-mode="task"><i class="bx bx-list-check"></i> Tác nghiệp</button>
				</div>
				<div class="crm-top-quick-filter__chips-wrap js__crm-chips js__crm-chips-status">
					<button type="button" class="crm-quick-chip-nav js__crm-quick-chip-prev"><i class="bx bx-chevron-left"></i></button>
					<div class="crm-top-quick-filter__chips crm-quick-chip-group js__crm-quick-chip-carousel">
						<div class="crm-quick-chip-item py-2">
							<button type="button" class="crm-quick-chip py-2{if !$get_status_id} active{/if} js__crm-quick-chip" data-status-id="0" style="--c:#696cff; --c-soft:rgba(105,108,255,.1); --c-border:rgba(105,108,255,.6)">
								<span class="cqc-top">
									<span class="crm-chip-dot"></span>
									<span class="cqc-label">Tất cả</span>
								</span>
								<span class="cqc-num">{$total_crm_status_chips}</span>
							</button>
						</div>
						{foreach from=$list_crm_status_chips item=_chip}
						<div class="crm-quick-chip-item py-2">
							<button type="button" class="crm-quick-chip py-2{if $get_status_id == $_chip.status_id} active{/if} js__crm-quick-chip" data-status-id="{$_chip.status_id}"
								style="--c:{$_chip.bgcolor|default:'#696cff'}; --c-soft:{$clsISO->hexToRgba($_chip.bgcolor, 0.1)}; --c-border:{$clsISO->hexToRgba($_chip.bgcolor, 0.6)}">
								<span class="cqc-top">
									<span class="crm-chip-dot"></span>
									<span class="cqc-label">{$_chip.title|escape}</span>
								</span>
								<span class="cqc-num">{$_chip.count}</span>
							</button>
						</div>
						{/foreach}
					</div>
					<button type="button" class="crm-quick-chip-nav js__crm-quick-chip-next"><i class="bx bx-chevron-right"></i></button>
				</div>
				<div class="crm-top-quick-filter__chips-wrap js__crm-chips js__crm-chips-task d-none">
					<button type="button" class="crm-quick-chip-nav js__crm-quick-chip-prev"><i class="bx bx-chevron-left"></i></button>
					<div class="crm-top-quick-filter__chips crm-quick-chip-group js__crm-quick-chip-carousel">
						<div class="crm-quick-chip-item py-2">
							<button type="button" class="crm-quick-chip py-2 active js__crm-quick-chip" data-task-id="0" style="--c:#696cff; --c-soft:rgba(105,108,255,.1); --c-border:rgba(105,108,255,.6)">
								<span class="cqc-top">
									<span class="crm-chip-dot"></span>
									<span class="cqc-label">Tất cả</span>
								</span>
								<span class="cqc-num">{$total_crm_task_chips}</span>
							</button>
						</div>
						{foreach from=$list_crm_task_chips item=_chip}
						<div class="crm-quick-chip-item py-2">
							<button type="button" class="crm-quick-chip py-2 js__crm-quick-chip" data-task-id="{$_chip.task_id}" style="--c:#696cff; --c-soft:rgba(105,108,255,.1); --c-border:rgba(105,108,255,.6)">
								<span class="cqc-top">
									<span class="crm-chip-dot"></span>
									<span class="cqc-label">{$_chip.title|escape}</span>
								</span>
								<span class="cqc-num">{$_chip.count}</span>
							</button>
						</div>
						{/foreach}
					</div>
					<button type="button" class="crm-quick-chip-nav js__crm-quick-chip-next"><i class="bx bx-chevron-right"></i></button>
				</div>
			</div>
		</div>
		<div class="holder_customer">
			<table class="table">
				{section name=i loop=$list_preloaders max=30}
				<tr>
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					{if $deviceType ne 'phone'}
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					{/if}
				</tr>
				{/section}
			</table>
		</div>
		<input type="hidden" class="PageCustomer_Page" value="{$current_page}" />
		<input type="hidden" class="PageCustomer_Length" value="{$per_page}" />
		<div class="d-flex flex-wrap justify-content-between align-items-center p-3 gap-2 border-top crm-list-foot{if $deviceType eq 'phone'} crm-list-foot--mb{/if}">
			<div class="d-flex align-items-center gap-2">
				<span class="text-muted">Hiển thị</span>
				<select class="form-select form-select-sm w-auto PageCustomer_Length_Select" holderG="desktop" 
					onChange="$Core.crm.customer_per_page_change(this, event)">
					<option value="10"{if $per_page eq 10} selected{/if}>10</option>
					<option value="20"{if $per_page eq 20} selected{/if}>20</option>
					<option value="30"{if $per_page eq 30} selected{/if}>30</option>
					<option value="50"{if $per_page eq 50} selected{/if}>50</option>
				</select>
				<span class="text-muted">bản ghi/trang</span>
			</div>
			<div class="d-flex align-items-center gap-3">
				<div class="text-muted small js__crm-paging-summary"></div>
				<div id="pager_desktop" class="d-none"></div>
			</div>
		</div>
	</div>
	<!-- End Root Render -->
</div>{$scriptJs}{literal}
<script>
	$(function() {
		setTimeout(() => {
			// $Core.crm.load_converted_rates({});
			// $Core.crm.load_desktop_followups();
			// $Core.crm.load_sale_dashboard();
			$Core.crm.load_customers("_desktop"{/literal}{if $get_status_id || $get_admin_id || $get_overdue || $get_untouched}, {ldelim}status_id: {$get_status_id|default:0}, admin_id: {$get_admin_id|default:0}, overdue: {$get_overdue|default:0}, untouched: {$get_untouched|default:0}{rdelim}{/if}{literal});
			if ($('.autoload:not(.loaded)').length) {
				$('.autoload:not(.loaded)').each((_i, _elem) => {
					$(_elem).addClass('loaded');
					var _url = $(_elem).data('url'),
						_options = $(_elem).data('options') || {};
					$.post(_url, _options, function(html) {
						$(_elem).html(html);
					});
				});
			}
		}, 500);
	});
</script>{/literal}
