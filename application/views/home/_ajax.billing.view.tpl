<div class="modal-dialog modal-ipad-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Thông tin giao dịch {$oneBilling.billing_code}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="highlights px-3 mb-3">
			<div class="highlight-panel border border-gray rounded-3">
				<div class="highlight-list">
					<div class="highlight-item d-flex gap-2 align-items-center">
						<div class="highlight-icon p-2 rounded-2">
							<i class="bx bx-user"></i>
						</div>
						<div class="d-flex flex-column">
							<div class="highlight-label">Sale bán</div>
							<div class="metadata-row-viewer text-nowrap">
								{$clsProfile->getIndentity($oneBilling.staff_id)}
							</div>
						</div>
					</div>
					<div class="highlight-item d-flex gap-2 align-items-center">
						<div class="highlight-icon p-2 rounded-2">
							<i class="bx bx-code"></i>
						</div>
						<div class="d-flex flex-column">
							<div class="highlight-label">Mã căn</div>
							<div class="metadata-row-viewer text-bold">{$oneBilling.stock_code}</div>
						</div>
					</div>
					<div class="highlight-item d-flex gap-2 align-items-center">
						<div class="highlight-icon p-2 rounded-2">
							<i class="bx bx-calendar"></i>
						</div>
						<div class="d-flex flex-column">
							<div class="highlight-label">Ngày cọc</div>
							<div class="metadata-row-viewer text-bold">
								{$clsISO->convertTimeToText($oneBilling.deposit_date)}
							</div>
						</div>
					</div>
					<div class="highlight-item d-flex gap-2 align-items-center">
						<div class="highlight-icon p-2 rounded-2">
							<i class="bx bx-dollar"></i>
						</div>
						<div class="d-flex flex-column">
							<div class="highlight-label">Doanh số</div>
							<div class="metadata-row-viewer text-bold">
								{$clsISO->formatNumberToEasyRead($oneBilling.totalgrand)}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-body border-top border-gray bg-lightest">
			<ul class="nav nav-tabs nav-tabs-bordered mb-3" role="tablist">
				<li class="nav-item">
					<button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tabhome_{$uid}">Chi tiết</button>
				</li>
				<li class="nav-item">
					<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabnotes_{$uid}">Ghi chú</button>
				</li>
				<li class="nav-item">
					<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabfile_{$uid}">File đính kèm</button>
				</li>
			</ul>
			<div class="tab-content p-0">
				<div class="tab-pane fade show active" id="tabhome_{$uid}" role="tabpanel">
					{if $is_content_changing eq '1' && !empty($arr_change_logs)}
					<div class="alert alert-warning">
						<h3 class="mb-2 text-fs-18"><i class="bx bx-bell"></i> Thay đổi đang chờ được chấp nhận</h3>
						<div class="form-row">
							<div class="col-12 col-md-9 mb-2 mb-lg-0">
								<p class="mb-1"><strong>Lý do:</strong> {$arr_change_logs.reason}</p>
								<p class="mb-1"><strong>Thời gian:</strong> {$clsISO->convertTimeToText($arr_change_logs.reg_date, true)}</p>
								<b>Chi tiết: </b>
								<ul class="mb-0">
									{foreach from=$arr_change_logs.content_change key = _oField item = _oValue}
										{if $_oField eq 'totalgrand'}
										<li>{$clsBilling->getFieldName($_oField)} : {$clsISO->formatPrice($_oValue)}</li>
										{elseif $_oField eq 'staff_id'}
										<li>{$clsBilling->getFieldName($_oField)} : {$clsProfile->getFullName($_oValue)}</li>
										{elseif $_oField eq 'stock_code'}
										<li>{$clsBilling->getFieldName($_oField)} : {$_oValue}</li>
										{/if}
									{/foreach}
								</ul>
							</div>
							{if $staff_confirm_id eq $profile_id}
							<div class="col-12 col-md-3">
								<div class="d-flex flex-column xs:flex-row gap-2 align-items-center">
									<button onClick="$Core.global.billing.do_change_confirmed(this, event)" billing_id="{$billing_id}" 
										tp="approved" class="btn xs:flex-fill btn-block btn-outline-success">
										<i class="bx bx-check"></i> Chấp nhận</button>
									<button onClick="$Core.global.billing.do_change_confirmed(this, event)" billing_id="{$billing_id}" 
										tp="rejected" class="btn xs:flex-fill btn-block btn-outline-secondary">
										<i class="bx bx-x"></i> Từ chối
									</button>
								</div>
							</div>
							{/if}
						</div>
					</div>
					{/if}
					<div class="form-row mb-2">
						<div class="col-12 col-md-6 mb-2 mb-lg-0">
							<div class="card no-shadow h-100">
								<div class="card-header">
									<h4 class="card-title mb-0">Thông tin giao dịch</h4>
								</div>
								<div class="card-body">
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-code text-fs-15"></i>
											</div>
											<span class="text-muted">Mã căn</span>
										</div>
										<strong class="text-dark">{$oneBilling.stock_code}</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-calendar text-fs-15"></i>
											</div>
											<span class="text-muted">Ngày cọc</span>
										</div>
										<strong class="text-dark">
											{$clsISO->convertTimeToText($oneBilling.deposit_date)}
										</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-dollar text-fs-15"></i>
											</div>
											<span class="text-muted">Doanh số</span>
										</div>
										<strong class="text-dark">
											{$clsISO->formatPrice($oneBilling.totalgrand)}
										</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-chart text-fs-15"></i>
											</div>
											<span class="text-muted">Loại hình</span>
										</div>
										{if $oneBilling.billing_type gt '0'}
										<strong class="text-dark">
											{$clsProperty->getTitle($oneBilling.billing_type)}
										</strong>
										{else}
										<span class="text-muted d-flex align-items-center">
											<i class="bx bx-error text-muted"></i> Chưa có
										</span>
										{/if}
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-home text-fs-15"></i>
											</div>
											<span class="text-muted">Dự án</span>
										</div>
										<strong class="text-dark">
											{$clsProject->getTitle($oneBilling.project_id)}
										</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-user text-fs-15"></i>
											</div>
											<span class="text-muted">Sale bán</span>
										</div>
										<strong class="text-dark">
											{$clsProfile->getIndentity($oneBilling.staff_id)}
										</strong>
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-user text-fs-15"></i>
											</div>
											<span class="text-muted">Khách hàng</span>
										</div>
										{if isset($more_information.customer_name) && !empty($more_information.customer_name)}
											<strong class="text-dark">
												{$more_information.customer_name}
											</strong>
										{else}
											<span class="badge bg-label-danger rounded-pill">
												<i class="bx bx-x text-fs-12"></i> Chưa có
											</span>
										{/if}
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="card no-shadow h-100">
								<div class="card-header">
									<h4 class="card-title mb-0">Thông tin hồ sơ</h4>
								</div>
								<div class="card-body">
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-calendar text-fs-15"></i>
											</div>
											<span class="text-muted">Ngày ký VBTT</span>
										</div>
										{if $oneBilling.agree_date gt '0'}
											<span>{$clsISO->convertTimeToText($oneBilling.agree_date)}<span>
										{else}
											<span class="badge bg-label-danger rounded-pill">
												<i class="bx bx-x text-fs-12"></i> Chưa có
											</span> 
										{/if}
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-calendar text-fs-15"></i>
											</div>
											<span class="text-muted">Ngày ký HĐMB</span>
										</div>
										{if $oneBilling.contract_date gt '0'}
											<span>{$clsISO->convertTimeToText($oneBilling.contract_date)}<span>
										{else}
											<span class="badge bg-label-danger rounded-pill">
												<i class="bx bx-x text-fs-12"></i> Chưa có
											</span> 
										{/if}
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-calendar text-fs-15"></i>
											</div>
											<span class="text-muted">Phương án thanh toán</span>
										</div>
										{if $more_information.billing_method gt '0'}
											<span>{$clsProperty->getTitle($more_information.billing_method)}<span>
										{else}
											<span class="badge bg-label-danger rounded-pill">
												<i class="bx bx-x text-fs-12"></i> Chưa rõ
											</span> 
										{/if}
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-check-circle text-fs-15"></i>
											</div>
											<span class="text-muted">File CSBH</span>
										</div>
										{if !empty($more_information.sale_policy_file)}
										<a class="badge bg-label-primary rounded-pill" target="_blank" href="{$more_information.sale_policy_file}">
											<i class="bx bx-link-external text-fs-12"></i> Xem CSBH
										</a>
										{else}
										<span class="badge bg-label-danger rounded-pill">
											<i class="bx bx-x text-fs-12"></i> Chưa có
										</span>
										{/if}
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-table text-fs-15"></i>
											</div>
											<span class="text-muted">File PTG:</span>
										</div>
										{if !empty($more_information.price_sheet_file)}
										<div class="d-flex align-items-center gap-1">
											{foreach from = $more_information.price_sheet_file item = _olink}
											<a class="badge bg-label-primary rounded-pill" target="_blank" data-fancybox href="{$clsISO->getGoogleUrl($_olink.image)}">
												<i class="bx bx-link-external text-fs-12"></i> {$_olink.title}
											</a>
											{/foreach}
										</div>
										{else}
										<span class="badge bg-label-danger rounded-pill">
											<i class="bx bx-x text-fs-12"></i> Chưa có
										</span>
										{/if}
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2 border-bottom border-gray">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-wallet-alt text-fs-15"></i>
											</div>
											<span class="text-muted">Ủy nhiệm chi</span>
										</div>
										{if !empty($more_information.payment_order)}
										<div class="d-flex align-items-center gap-1">
											{foreach from = $more_information.payment_order item = _olink}
											<a class="badge bg-label-primary  rounded-pill" data-fancybox target="_blank" href="{$clsISO->getGoogleUrl($_olink.image)}">
												<i class="bx bx-link-external text-fs-12"></i> {$_olink.title}
											</a>
											{/foreach}
										</div>
										{else}
										<span class="badge bg-label-danger rounded-pill">
											<i class="bx bx-x text-fs-12"></i> Chưa có
										</span>
										{/if}
									</div>
									<div class="d-flex align-items-center justify-content-between py-2 gap-2">
										<div class="d-flex align-items-center gap-2">
											<div class="icon p-1 rounded-2 bg-lighter">
												<i class="bx bx-revision text-fs-15"></i>
											</div>
											<span class="text-muted">File HĐMB</span>
										</div>
										{if !empty($more_information.contract_files)}
										<div class="d-flex align-items-center gap-1">
											{foreach from = $more_information.contract_files item = _olink}
											<a class="badge bg-label-primary rounded-pill" target="_blank"  href="{$_olink.image}">
												<i class="bx bx-link-external text-fs-12"></i> {$_olink.title}
											</a>
											{/foreach}
										</div>
										{else}
										<span class="badge bg-label-danger rounded-pill">
											<i class="bx bx-x text-fs-12"></i> Chưa có
										</span>
										{/if}
									</div>
								</div>
							</div>
						</div>
					</div>
					{if $oneBilling.is_interest_accrued eq '1'}
					<div class="box rounded-2 overflow-hidden">
						<div class="box-header bg-lighter px-3 py-2">
							<h5 class="box-title text-fs-16 mb-0">Lãi phát sinh</h5>
						</div>
						<div class="box-body bg-white p-2">
							<div class="form-row">
								{foreach from=$oneBilling.interest_accrued name=ii key= _OK item = _OI}
								<div class="col-12 col-md-6{if $smarty.foreach.ii.first} mb-2 mb-lg-0{/if}">
									<div class="bg-label-{if $_OI.is_completed eq '1'}success{else}info{/if} rounded-2 p-3 mb-2 h-100">
										<h4 class="mb-2 text-fs-16">{if $_OK eq 'first_interest'}Lãi phát sinh L1{else}Lãi phát sinh L2{/if}</h4>
										<div class="p-3 rounded-2 bg-white">
											<div class="d-flex text-fs-28 mb-1">Số tiền: <strong>{$_OI.amount}</strong></div>
											<div class="d-flex align-items-center mb-1">Ngày tính: {$_OI.accrual_date}</div>
											<div class="d-flex align-items-center">Ghi chú: {$_OI.notes}</div>
										</div>
									</div>
								</div>
								{/foreach}
							</div>
						</div>
					</div>
					{/if}
					{if $oneBilling.is_deposit_paid eq '1'}
					<div class="rounded-2 mb-2" style="background:rgb(244,247,244);">
						<div class="d-flex align-items-center gap-2 p-3 pb-2">
							<i class="bx bxs-check-circle text-success text-fs-26"></i>
							<strong class="text-fs-16">Thông tin đóng 10%</strong>
						</div>
						<div class="p-3 pt-0">
							<div class="p-2 rounded-3 bg-white">
								<table class="clientssummarystats rounded-2 overflow-hidden" width="100%">
									<tr>
										<td width="{if $deviceType eq 'phone'}40{else}25{/if}%" class="text-right">Ngày khớp</td>
										<td colspan="3">{$clsISO->convertTimeToText($deposit_paid_to_company.action_date, true)}</td>
									</tr>
									<tr>
										<td class="text-right">Số tiền</td>
										<td colspan="3">{$clsISO->formatPrice($deposit_paid_to_company.deposit_paid_amount)} {$clsISO->getRate()}</td>
									</tr>
									<tr>
										<td  class="text-right">Họ và tên/UNC</td>
										<td colspan="3">{$deposit_paid_to_company.payer_name}</td>
									</tr>
									<tr>
										<td  class="text-right">Mã FT</td>
										<td colspan="3">
											{if !empty($deposit_paid_to_company.trans_code)}
												{$deposit_paid_to_company.trans_code}
											{else}
												---
											{/if}
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					{/if}
					{if $clsISO->checkPermissionGroup('DIRECTOR') 
					|| $clsISO->checkPermissionGroup('PROJECT_DIRECTOR') 
					|| $clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')}
					<div class="box rounded-2 overflow-hidden">
						<div class="box-header bg-lighter px-3 pt-3 pb-2">
							<h5 class="box-title text-fs-16 mb-0">Lịch sử giao dịch</h5>
						</div>
						<div class="box-body bg-white p-2">
							<div class="logs_{$billing_id}">
								<div class="loader p-5 text-center">Loading...</div>
							</div>
						</div>
					</div>
					{/if}
				</div>
				<div class="tab-pane fade" id="tabnotes_{$uid}" role="tabpanel">
					<div class="widget-block mb-2">
						<div class="widget-header">Thêm ghi chú</div>
						<div class="widget-content">
							<form class="frmIssue" name="" action="">
								<textarea class="form-control" name="content" rows="2" placeholder="Nhập ghi chú"></textarea>
								<div class="clearfix mt-2">
									<button type="button" tp="_create" class="btn btn-outline-primary" 
									for_id="{$billing_id}" clsTable="Billing" note_id="" onClick="$Core.helper.save_notes(this,event)">Thêm</button>
								</div>
							</form>
						</div>
					</div>
					<div class="widget-block">
						<div class="widget-header">Ghi chú</div>
						<div class="widget-content">
							<div class="holder_notes_{$billing_id}">
								<div class="loader p-5 text-center">Loading...</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="tabfile_{$uid}" role="tabpanel">
					<div class="widget-block">
						<div class="widget-header">Thêm File đính kèm</div>
						<div class="widget-content">
							<form class="frmIssue" enctype="multipart/form-data" name="" action="">
								<div class="form-group mb-2">
									<textarea class="form-control" name="description" cols="255" rows="2" placeholder="Nhập nội dung"></textarea>
								</div>
								<div class="form-group mb-2">
									<input type="file" class="form-control" name="attachment" />
								</div>
								<div class="clearfix">
									<input type="hidden" name="submit" value="Insert" />
									<button type="button" class="btn btn-outline-primary" 
									for_id="{$billing_id}" clsTable="Billing" onClick="$Core.member.ms_save_file(this,event)">Thêm</button>
								</div>
							</form>
						</div>
					</div>
					<div class="widget-block mt-2">
						<div class="widget-header">Danh sách file đính kèm</div>
						<div class="widget-content">
							<div class="holder_files_{$billing_id}">
								<div class="loader p-5 text-center">Loading...</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			{if $oneBilling.admin_id eq $profile_id || $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkDEV()}
			<div class="d-flex flex-wrap p-3 bg-label-light rounded-2 mt-2 gap-2">
				<button data-toggle="ripple" billing_id="{$billing_id}" onClick="$Core.billing.open_billing(this,event)" 
					class="btn flex-fill btn-sm btn-outline-default"><i class="bx bx-edit"></i> Sửa</button>
				<button data-toggle="ripple" billing_id="{$billing_id}" onClick="$Core.billing.add_info(this,event)" 
					class="btn flex-fill btn-sm btn-outline-default"><i class="bx bx-upload"></i> Thêm thông tin</button>
				<button data-toggle="ripple" billing_id="{$billing_id}" onClick="$Core.billing.open_activity(this,event)" 
					holderG="deposit_paid" class="btn flex-fill btn-sm btn-outline-default"><i class="bx bx-dollar"></i> Thêm 10%</button>
				<button data-toggle="ripple" billing_id="{$billing_id}" onClick="$Core.billing.open_activity(this,event)" 
					holderG="interest_accrued" class="btn flex-fill btn-sm btn-outline-default"><i class="bx bx-money"></i> Cập nhật lãi</button>
			</div>
			{/if}
		</div>
	</div>
</div>
