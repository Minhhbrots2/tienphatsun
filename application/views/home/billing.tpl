<link rel="stylesheet" type="text/css" href="{$URL_CSS}/billing.css?v={$upd_version}" media="all" />
{assign var=_uid value=$clsISO->getUniqid()}
<div class="container-xxl flex-grow-1 pt-2 container-p-y biiling_page">
	<form action="#" method="POST">
		<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
			<div class="p__left mb-2 mb-lg-0">
				<h4 class="fw-bold mb-1">Giao dịch chốt</h4>
				<span class="text-muted">Tổng cộng <strong class="text-main">{$total_record}</strong> giao dịch chốt {$smarty.const.BRAND_NAME}</span>
			</div>
			<div class="d-flex gap-1 xs:w-100 align-items-center">
				<a class="btn btn-icon btn-outline-default text-muted cursor-pointer" onclick="$Core.util.toggle_block(this, event)" 
					callback="_autoload()" toId="toggle_block_{$_uid}"><i class="bx bx-chevron-down"></i></a>
				{if $billing_configs.is_action_visible eq '1'}
				<div class="d-none d-md-block dropdown btn-group">
					<button data-bs-toggle="dropdown" disabled class="btn btn-outline-default dropdown-toggle js__dropdown-action">
						<i class="bx bx-play"></i> Hành động
					</button>
					<ul class="dropdown-menu dropdown-menu-start">
						<li><a onClick="$Core.billing.do_action(this, event)" action="contract_signed" 
							class="dropdown-item cursor-pointer">Đã ký HĐMB</a></li>
					</ul>
				</div>
				{/if}
				<div class="search-block w-full d-flex align-item-center">
					<div class="input-group">
						<div class="input-group input-group-merge">
							<span class="input-group-text"><i class="bx bx-search"></i></span>
							<input type="text" name="keyword" value="{$keyword}" class="form-control no-radius-right" 
							placeholder="Nhập từ khoá & nhấn Enter..." />
						</div>
					</div>
					<div class="btn-group dropdown">
						<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow no-radius-left no-border-left" 
						data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
							<i class="bx bx-filter-alt"></i>
						</button>
						<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-350 desktop:w-px-500" data-popper-placement="top-end">
							{$core->getBlock('billing_search')}
						</div> 
					</div>
				</div>
				{if $permiss_add eq '1' || $clsISO->checkPermissionGroup('PROJECT_DIRECTOR')}
				<button type="button" title="Thêm nhanh" onClick="$Core.global.billing.open_billing(this, event)" billing_id="0" 
					class="d-flex align-items-center btn text-nowrap{if $deviceType eq 'phone'} btn-icon{/if} btn-outline-primary create_billing">
					<i class="bx bx-plus"></i>
					<span class="d-none d-lg-block">Thêm mới</span>
				</button>
				{/if}
				{if $permiss_export eq '1' || $permiss_add eq '1' || $clsISO->checkPermissionGroup('PROJECT_DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR')}
				<div class="dropdown">
					<button type="button" class="btn btn-icon btn-outline-default hide-arrow dropdown-toggle"
						data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-cog"></i></button>
					<ul class="dropdown-menu w-px-250">
						{if $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR')}
						<li><a class="dropdown-item cursor-pointer" onClick="$Core.commissionTier.open(this,event)">
							{$clsISO->makeIcon('bx-line-chart','Quản lý commission')}</a></li>
						<li><a class="dropdown-item cursor-pointer" onClick="$Core.backofficeRate.open(this,event)">
							{$clsISO->makeIcon('bx-buildings','Tỷ lệ chi phí công ty')}</a></li>
						<li><hr class="dropdown-divider m-0"></li>
						{/if}
						{if $permiss_add eq '1'}
						<li><a class="dropdown-item cursor-pointer" onClick="$Core.billingImport.open(this,event)">
							{$clsISO->makeIcon('bx-spreadsheet','Import Google Sheet')}</a></li>
						<li><hr class="dropdown-divider m-0"></li>
						<li><a href="{$PCMS_URL}/billing/export.html{$pUrl}" class="dropdown-item">
							{$clsISO->makeIcon('bx-upload','Xuất Excel')}</a></li>
						<li><a href="/lich-ky.html" class="dropdown-item">{$clsISO->makeIcon('bx-calendar','Lịch ký HĐMB')}</a></li>
						<!-- <li><a class="dropdown-item" href="{$PCMS_URL}/billing/report.html">
							{$clsISO->makeIcon('bx-bar-chart-alt-2','Báo cáo Quỹ')}</a></li>-->
						<!-- <li><a class="dropdown-item" href="{$PCMS_URL}/billing/report/score.html">
							{$clsISO->makeIcon('bx bx-terminal','Báo cáo nhập thông tin')}</a></li>-->
						{/if}
						{if $clsISO->checkDEV()}
						<!-- <li><a class="dropdown-item" href="{$clsISO->getLink('template')}">
							<i class="menu-icon tf-icons bx bx-file"></i>Mẫu chúc mừng</a>
						</li>-->
						{/if}
					</ul>
				</div>
				{/if}
			</div>
		</div>
		{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->_DEV()}
		<div id="toggle_block_{$_uid}" class="d-none">
			<div class="report_billing ajax" data-url="/index.php?mod={$mod}&act=load_report_billing">
				<div class="form-row">
					<div class="col-12 col-md-6 col-xxl-3 mb-2">
						<div class="card">
							<div class="card-body">
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_total_billing"></span>
										<span class="">Tổng giao dịch</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_total_grand"></span>
										<span class="">Tổng doanh số</span>
									</div>
									<span class="fs-8 fw-semibold">0đ</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_primary"></span>
										<span class="">Sơ cấp</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_transfer"></span>
										<span class="">Thứ cấp, CN</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_contract"></span>
										<span class="">Đã ký HĐMB</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_calendar"></span>
										<span class="">Có lịch ký HĐMB</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_no_calendar"></span>
										<span class="">Chưa có lịch ký</span>
									</div>
									<span class="fs-8 fw-semibold">0 GD</span>
								</div>
								<div class="d-flex align-items-center justify-content-between ">
									<div class="d-flex gap-1 align-items-center">
										<span class="icon_bill icon_ratio"></span>
										<span class="">Tỷ lệ ký</span>
									</div>
									<span class="fs-8 fw-semibold">0%</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 col-xxl-9 mb-2">
						<div class="lst_area_billing form-row h-100" data-md-slide="3" data-sm-slide="2" 
							data-lg-slide="4" data-dots="1" data-nav="0" data-loop="0" data-margin="10">
							{section loop=4 name=i start=0 step=1}
							<div class="col-12 col-md-4 col-lg-3">
								<div class="item_area_billing card h-100">
									<div class="card-header item_top pb-2" style="background-color: green;color: white">
										<h4 class="mb-2">Vùng KD</h4>
										<p class="mb-0 fs-20">0 GD</p>
									</div>
									<div class="card-body item_body p-2 pb-3">
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_total_grand"></span>
												<span class="">Tổng doanh số</span>
											</div>
											<span class="fs-8 fw-semibold">0đ</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_primary"></span>
												<span class="">Sơ cấp</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_transfer"></span>
												<span class="">Thứ cấp, CN</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_contract"></span>
												<span class="">Đã ký HĐMB</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_calendar"></span>
												<span class="">Có lịch ký HĐMB</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_no_calendar"></span>
												<span class="">Chưa có lịch ký</span>
											</div>
											<span class="fs-8 fw-semibold">0 GD</span>
										</div>
										<div class="d-flex align-items-center justify-content-between ">
											<div class="d-flex gap-1 align-items-center">
												<span class="icon_bill icon_ratio"></span>
												<span class="">Tỷ lệ ký</span>
											</div>
											<span class="fs-8 fw-semibold">0%</span>
										</div>
									</div>
								</div>
							</div>
							{/section}
						</div>
					</div>
				</div>
			</div>		
			<!-- <div class="form-row">
				<div class="col-12 col-md-6 col-lg-4 mb-2">
					{assign var=gId value=$clsISO->getUniqid()}
					<div class="card h-100" id="{$gId}">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Hiệu suất phòng kinh doanh</h5>
							<div class="w-px-100">
								<select name="department_id" id="" class="form-control form-control-sm form-select field_item" 
									onChange="$Core.dashboard.reload(this,event)" gId="{$gId}">
									{foreach from=$lstDepChild item=_oItem key=key name=i}
									<option value="{$_oItem.property_id}">{$_oItem.title}</option>
									{/foreach}
								</select>
							</div>
						</div>
						<div class="card-body search_group ajax" toId="{$gId}" data-url="/index.php?mod={$mod}&act=load_report_billing_dep" gId="{$gId}">
							<div class="d-flex gap-2 align-items-center w-100 mb-2">
								<span class="fs-14 text-right w-px-50 text-nowrap">PKD</span>
								<div class="d-flex flex-column" style="width:calc(100% - 125px)">
									<div class="progress w-100 h-px-15" >
									  <div class="progress-bar bg-info" role="progressbar" style="width:14.583333333333%;"></div>
									</div>
								</div>
								<span class="fs-12 text-nowrap w-px-75 text-right">
								<strong class="text-fs-15 text-main">120 GD</strong> 15%</span>
							</div>
							<div class="d-flex gap-2 align-items-center w-100">
								<span class="fs-14 text-right w-px-50 text-nowrap">PKD</span>
								<div class="d-flex flex-column" style="width:calc(100% - 125px)">
									<div class="progress w-100 h-px-15" >
										<div class="progress-bar bg-info" role="progressbar" style="width:14.583333333333%;"></div>
									</div>
								</div>
								<span class="fs-12 text-nowrap w-px-75 text-right">
								<strong class="text-fs-15 text-main">120 GD</strong> 15%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-4 mb-2">
					{assign var=gId value=$clsISO->getUniqid()}
					<div class="ajax h-100" data-url="/index.php?mod={$mod}&act=load_report_warning_dep">
						<div class="card h-100">
							<div class="card-body">
								<div class="alert py-2 mb-0">
									<i class='bx bx-info-circle text-danger me-2'></i> Cảnh báo điều hành
								</div>
								<div class="alert alert-warning py-2 mb-2">
									<i class='bx bx-info-circle me-2'></i>3 vùng có tỷ lệ ký &lt; 40%
								</div>
								<div class="alert alert-warning py-2 mb-2">
									<i class='bx bx-info-circle me-2'></i>6 vùng kinh doanh tồn &gt; 40 giao dịch chưa ký
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>
		{/if}
		<!-- Basic Bootstrap Table -->
		<div class="card">
			<div class="card-body">
				<div class="briefs mb-2 gap-2 gap-lg-3 d-flex flex-wrap">
					<div class="brief-item a1a bg-orange clickable">
						<p class="text-fs-14 mb-0">Tổng giao dịch</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">{$total_billings} GD</h3>
					</div>
					<div class="brief-item a2a brief-item-clickable bg-azure">
						<p class="text-fs-14 mb-0">Tổng doanh số</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">{$clsISO->shortNumber($total_grand,3)}</h3>
					</div>
					<div class="brief-item a6a brief-item-clickable bg-solid">
						<p class="text-fs-14 mb-0">Thứ cấp, CN</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">{$total_trans_billings} GD</h3>
					</div>
					<div class="brief-item a3a brief-item-clickable bg-cyan">
						<p class="text-fs-14 mb-0">Đã ký HĐMB</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">{$total_registed_hdmb} GD</h3>
					</div>
					<div class="brief-item a4a brief-item-clickable bg-green">
						<p class="text-fs-14 mb-0">Có lịch ký HĐMB </p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">{$total_unregisted_hdmb} GD</h3>
					</div>
					<div class="brief-item a5a brief-item-clickable bg-purple">
						<p class="text-fs-14 mb-0">Chưa có lịch ký</p>
						<hr class="w-px-50 my-2" />
						<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">{$total_not_schedule_hdmb} GD</h3>
					</div>
				</div>
				{if $billing_configs.is_action_visible eq '1'}
				<div class="d-md-none mb-2">
					<div class="dropdown w-100 btn-group">
						<button data-bs-toggle="dropdown" class="btn  btn-outline-default dropdown-toggle">
							<i class="bx bx-play"></i> Hành động
						</button>
						<ul class="dropdown-menu dropdown-menu-start w-100">
							<li><a class="dropdown-item cursor-pointer">Đã ký hợp đồng mua bán</a></li>
						</ul>
					</div>
				</div>
				{/if}
				<div class="table-container overflow-x-auto text-nowrap no-shadow">
					<table cellpadding="0" cellspacing="0" width="100%" class="table table-billing table-bordered dragable mb-0">
						<thead><tr>
							{if $billing_configs.is_action_visible eq '1'}
							<th class="align-center bg-lighter h-px-40 overflow-visible">
								<input type="checkbox" onChange="$Core.billing.do_checked(this, event)" 
									class="form-check-input chk_all" value="1" style="font-size:0.8675rem;">
							</th>
							{/if}
							<th class="align-center bg-lighter h-px-40" width="150px">Ngày tạo</th>
							<th class="align-center bg-lighter h-px-40">Mã căn</th>
							<th class="align-center bg-lighter h-px-40" width="150px">Ngày cọc</th>
							{if $permiss_edit eq '1'}<th class="align-center bg-lighter h-px-40 text-center">Q/E</th>{/if}
							<th class="align-center bg-lighter h-px-40">Bán</th>
							<th class="align-center bg-lighter h-px-40">Ký VBTT</th>
							<th class="align-center bg-lighter h-px-40">Ký HĐMB</th>
							<th class="align-center bg-lighter h-px-40">Sale bán</th>
							<th class="align-center bg-lighter h-px-40">Admin</th>
							<th class="align-center bg-lighter h-px-40">Loại hình</th>
							<th class="align-center bg-lighter h-px-40">Dự án</th>
							<th class="align-center bg-lighter h-px-40">Phân khu</th>
							<th class="align-center bg-lighter h-px-40 text-right">Doanh số</th>
							<th class="align-center bg-lighter h-px-40 text-center">Điểm</th>
							<th class="align-center bg-lighter h-px-40 text-center" width="45px"></th>
						</tr> </thead>
						<tbody class="table-border-bottom-0">
							{if !empty($list_billings)}
							{foreach name=i from=$list_billings item = _oBilling}
							{assign var = billing_id value = $_oBilling.billing_id}
							{assign var = _oProfile value = $_oBilling.oProfile}
							{assign var = more_information value = $_oBilling.more_information}
							<tr class="trBilling {$_oBilling.class}"{if $permiss_view eq '1'} 
								ondblclick="view_billing(this, event)"{/if} billing_id="{$billing_id}">
								{if $billing_configs.is_action_visible eq '1'}
								<td class="align-center text-center">
									<input type="checkbox" value="{$billing_id}" name="list_ids[]" 
										class="form-check-input chk_item" onChange="$Core.billing.do_checked(this, event)">
								</td>
								{/if}
								<td class="align-center">
									{if !empty($_oBilling.is_commission)}
									<i class='bx bxs-check-circle text-success me-1 text-fs-12'></i>
									{/if} 
									{$clsISO->formatDate($_oBilling.reg_date,4)}
								</td>
								<td class="align-center">
									{if $_oBilling.is_cancel eq '1'}
									<span class="label bg-purple">Hủy</span>
									{/if}
									{if $_oBilling.is_deposit_paid eq '1'}
									<span class="badge bg-label-danger">10%</span>
									{/if}
									{if $_oBilling.is_interest_accrued eq '1'}
									<span class="badge bg-label-info">Lãi</span>
									{/if}
									<strong>{$_oBilling.stock_code}</strong>
									{if $permiss_edit eq '1'}{/if}
									{$_oBilling.billing_source}
									{if $_oBilling.is_alliance eq '1'}
									<span class="label d-inline-block bg-success" style="font-size:65%; transform:translateY(-2px)">LM</span>
									{/if}
								</td>
								<td class="align-center">
									{if $permiss_edit eq '1'}
									<a class="btn btn-icon btn-sm btn-outline-default{if $_oBilling.is_cancel eq '1'} disabled{/if}" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Bổ xung thông tin giao dịch" onClick="$Core.billing.add_info(this,event)" billing_id="{$_oBilling.billing_id}">{$clsISO->makeIcon('bx-edit-alt')}</a> {/if}{$clsISO->formatDate($_oBilling.deposit_date,3)}
								</td>
								{if $permiss_edit eq '1'}
								<td class="align-center text-center bg-lighter px-1 py-0">
									<button class="btn btn-sm btn-icon btn-outline-default"{if $deviceType eq 'phone'} onClick="$Core.global.billing.open_billing_done(this, event)"{else} data-toggle="webui-popover" data-trigger="click" data-type="async" data-placement="left-bottom" data-closeable="false" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_billing_done&billing_id={$billing_id}"{/if} billing_id="{$billing_id}"><i class="bx bx-pencil"></i></button>
								</td>
								{/if}
								<td class="align-center text-center">{$_oBilling.sold_to_type}</td>
								<td class="align-center">
									<span class="agree_date_{$billing_id}">
										{if !empty($_oBilling.agree_date)}
											{$clsISO->formatDate($_oBilling.agree_date,3)}
										{else}
											<span class="text-muted">
												<i class='bx bx-error text-muted'></i> Chưa có
											</span>
										{/if}
									</span>
								</td>
								<td class="align-center">
									<span class="contract_date_{$billing_id}">
									{if !empty($_oBilling.contract_date)}
										{$clsISO->formatDate($_oBilling.contract_date,3)}
									{else}
										<span class="text-muted">
											<i class='bx bx-error text-muted'></i> Chưa ký
										</span>
									{/if}
									</span>
								</td>
								<td class="align-center">
									{if $_oBilling.staff_id > 0}
										<span class="badge bg-label-purple">{$_oProfile.more_information.department_name}</span>
										{$clsProfile->getIndentityV2($_oBilling.staff_id, $_oProfile)}
									{elseif !empty($more_information.seller_name)}
										<span class="badge bg-label-secondary">Ngoài HT</span> {$more_information.seller_name|escape}
									{elseif !empty($more_information.sale_agency_id)}
										{* Bán cho F2 thì không có sale nội bộ — người bán CHÍNH LÀ đại lý.
										   Không có nhánh này thì ô để trắng, nhìn như giao dịch thiếu dữ liệu. *}
										<span class="badge bg-label-warning">Đại lý</span> {$clsProperty->getTitle($more_information.sale_agency_id)|escape}
									{/if}
									{if !empty($_oBilling.co_sellers)}
									<div class="mt-1 text-fs-11">
										<span class="text-muted">Sale phụ:</span>
										{foreach from=$_oBilling.co_sellers item=_cs}
										<span class="badge bg-label-info fw-normal">{$_cs.seller_name|escape} · {$_cs.share_ratio}%</span>
										{/foreach}
									</div>
									{/if}
								</td>
								<td class="align-center text-center">
									<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oBilling.admin_id}" 
										data-toggle="webui-popover" data-trigger="hover" data-width="350" data-target="webuiPopover10">
										<img class="avaatr avatar-xs rounded-pill" src="{$clsProfile->getAvatar($_oBilling.admin_id, $oAdminProfile, 40, 40)}" />
										<span class="cre"><i class="fa fa-plus"></i></span>
									</a>
								</td>
								<td class="align-center">{$_oBilling.billing_type}</td>
								<td class="align-center">{$_oBilling.project_name}</td>
								<td class="align-center">{$_oBilling.block_name}</td>
								<td class="align-center text-right">
									{$clsISO->formatNumberToEasyRead($_oBilling.totalgrand)} 
									{$clsISO->getRate()}
								</td>
								<td class="align-center text-center">{$_oBilling.score}</td>
								<td class="align-center text-center">
									<div class="dropdown">
										<button type="button" class="btn p-0 dropdown-toggle dropdown-button hide-arrow">
											<i class="bx bx-dots-vertical-rounded"></i>
										</button>
										<div class="dropdown-menu">
											<a class="dropdown-item cursor-pointer" onClick="view_billing(this,event)" billing_id="{$_oBilling.billing_id}">
												<i class="bx bx-bullseye me-1"></i> Xem</a>
											<hr size="0" class="dropdown-divider" />
											<!-- {if $clsISO->checkDEV()}
											<a class="dropdown-item text-danger" onClick="$Core.global.transaction.open(this,event)" openFrom="_billing" billing_id="{$_oBilling.billing_id}" transaction_id="0" href="javascript:void(0);"><i class="bx bx-plus-circle me-1"></i> Xác nhận hoa hồng</a>
											{if isset($more_information.commission_id) && !empty($more_information.commission_id)}
											<a class="dropdown-item text-primary" onClick="$Core.global.commission.open(this,event)" openFrom="_billing" commission_id="{$more_information.commission_id}" href="javascript:void(0);"><i class="bx bx-plus-circle me-1"></i> Cập nhật hoa hồng</a>
											{/if}
											<a class="dropdown-item text-warning" onClick="$Core.billing.sync_commission(this,event)" openFrom="_billing" billing_id="{$_oBilling.billing_id}" transaction_id="0" href="javascript:void(0);"><i class="bx bx-plus-circle me-1"></i> Tạo hoa hồng</a>
											{/if} -->
											<!-- <a class="dropdown-item cursor-pointer" tp="quick" onClick="open_confirm_deposit(this,event)" billing_id="{$_oBilling.billing_id}">
												<i class='bx bx-mail-send me-1'></i>Mẫu mail xác nhận</a> -->
											{if $permiss_edit eq '1' || $_oBilling.admin_id eq $profile_id}
											<a class="dropdown-item cursor-pointer" onClick="$Core.global.billing.open_billing(this,event)" 
												billing_id="{$_oBilling.billing_id}"><i class="bx bx-edit-alt me-1"></i> Cập nhật</a>
											{if $permiss_settlement eq '1'}
											<a class="dropdown-item cursor-pointer text-primary" onClick="$Core.billingSettlement.open(this,event)" billing_id="{$_oBilling.billing_id}">
												<i class="bx bx-calculator me-1"></i> Quyết toán</a>
											{/if}
											<a class="dropdown-item cursor-pointer" onClick="$Core.billing.add_info(this,event)" billing_id="{$_oBilling.billing_id}">
												<i class="bx bx-plus-circle me-1"></i> Thêm thông tin</a>
											<a class="dropdown-item text-danger cursor-pointer" onClick="$Core.global.billing.open_activity(this,event)" 
												billing_id="{$_oBilling.billing_id}" holderG="deposit_paid"><i class="bx bx-plus-circle me-1"></i> 
												{if $_oBilling.is_deposit_paid eq '1'}Sửa{else}Thêm{/if} 10% vào CTY</a>
											<a class="dropdown-item cursor-pointer text-warning" onClick="$Core.global.billing.open_activity(this,event)" 
												billing_id="{$_oBilling.billing_id}" holderG="interest_accrued"><i class="bx bx-plus-circle me-1"></i> Cập nhật lãi phát sinh</a>
											{/if}
											{if $permiss_cancel eq '1' || ($_oBilling.admin_id eq $profile_id || $clsISO->checkDEV())}
											<a class="dropdown-item cursor-pointer{if $_oBilling.is_cancel eq '1'} disabled{/if}" onClick="cancel_billing(this,event)" 
												billing_id="{$_oBilling.billing_id}"><i class="bx bx-no-entry me-1"></i> Hủy</a>
											{/if}
											{if $permiss_delete eq '1' || ($_oBilling.admin_id eq $profile_id || $clsISO->checkDEV())}
											<a class="dropdown-item cursor-pointer" onClick="delete_billing(this,event)" billing_id="{$_oBilling.billing_id}">
												<i class="bx bx-trash me-1"></i> Xóa</a>
											{/if}
										</div>
									</div>
								</td>
							</tr>
							{/foreach}
							{else}
								<tr>
									<td class="text-center" colspan="11">
										Không có giao dịch nào !
									</td>
								</tr>
							{/if}
						</tbody>
					</table>
				</div>
				{if $total_page gt '1'}
				<div id="pager" class="d-flex justify-content-center mt-3">
					<ul class="pagination">{$html_pager}</ul>
				</div>
				{/if}
			</div>
		</div>
	</form>
</div>
<script type="text/javascript" src="{$URL_JS}/billing_import.js?v={$upd_version}"></script>
{literal}
<script type="text/javascript">
	$().ready(() => {
		$Core.billing.init();
	});
</script>
<style type="text/css">
	tr.bg-cancel td{
		background:#fbe9e9 !important;
		--bs-table-accent-bg:#fbe9e9 !important;
	}
	.no-radius-right .selectize-input{
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		border-right: 0px !important;
	}
	.selectize-input,
	.selectize-control.single .selectize-input.focus{
		padding:7px !important;
		min-height:36.5px !important;
	}
	@media screen and (min-width:648px){
	{/literal}{if $billing_configs.is_action_visible eq '1'}{literal}
		.table-billing tr td:nth-child(2),
		.table-billing tr th:nth-child(2){
			position:sticky;
			left:41px; top:0;
			background:var(--bs-white);
		}
	{/literal}{else}{literal}
		.table-billing tr td:nth-child(2),
		.table-billing tr th:nth-child(2){
			position:sticky;
			left:136.8px; top:0;
			background:var(--bs-white);
		}
	{/literal}{/if}{literal}
	}
	.jconfirm.jconfirm-light .jconfirm-box .jconfirm-buttons{
		width:100%;
		display: -webkit-box!important;
		display: -ms-flexbox!important;
		display: flex!important;
		-webkit-box-pack: end!important;
		-ms-flex-pack: end!important;
		justify-content: flex-end!important;
	}
</style>
{/literal}