<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="w-100 d-flex algin-items-center justify-content-between mb-2">
		<div class="lycYJcfXJY">
			<h4 class="fw-bold mb-1">Tài chính & Tài sản Future Group</h4>
			<span class="text-muted">Tổng quan tài chính & tài sản Future Group</span>
		</div>
		<div class="">
			<select class="form-control form-select">
				{foreach from=$list_years item=_year}
				<option{if $_year eq $current_year} selected{/if} value="{$_year}">Năm {$_year}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="card mb-2">
		<div class="card-header">
			<h5 class="card-title mb-0">Tổng tài sản</h5>
		</div>
		<div class="card-body">
			<h5 class="mb-2 fs-4 fw-bold">
				{$clsISO->formatPrice($total_assets)} {$clsISO->getRate()}
				<i title="{$clsISO->formatPrice($total_assets)}" class="bx bx-help-circle"></i>
			</h5>
			<div class="d-flex gap-2 align-items-center">
				<span class="text-muted"><strong class="text-success">1.2%</strong> so với tháng trước</span>
				<span class="text-muted">|</span>
				<span class="text-muted">Cập nhật: {$smarty.now|date_format:"%d/%m/%Y %H:%I:%S"}</span>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-12 col-md-6 mb-2 mb-lg-0">
			<div class="card h-100">
				<div class="card-header">
					<h5 class="card-title mb-0">Thanh khoản</h5>
				</div>
				<div class="card-body">
					<div class="form-row row-cols-lg-{$list_total_blocks|@count} mb-2" gId="{$gId}">
						{foreach from=$list_total_blocks item = _OT key = _OK}
						<div class="col mb-2 mb-lg-0">
							<div class="p-3 relative {$_OK} border rounded-2 bg-lighter">
								<h5 class="mb-1 fs-5 fw-bold">
									{$clsISO->shortNumber($_OT.money)}
									<i title="{$clsISO->priceFormat($_OT.money)} {$clsISO->getRate()}" class='bx bx-help-circle' ></i>
								</h5>
								<hr class="w-px-50 my-2" />
								<span class="text-nowrap">{$_OT.title}</span>
							</div>
						</div>
						{/foreach}
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="card h-100">
				<div class="card-header">
					<h5 class="card-title mb-0">Công nợ & nghĩa vụ thuế</h5>
				</div>
				<div class="card-body">
					<div class="form-row row-cols-lg-{$list_total_2_blocks|@count} mb-2" gId="{$gId}">
						{foreach from=$list_total_2_blocks item = _OT key = _OK}
						<div class="col mb-2 mb-lg-0">
							<div class="p-3 relative {$_OK} border rounded-2 bg-lighter">
								<h5 class="mb-1 fs-5 fw-bold">
									{$clsISO->shortNumber($_OT.money)}
									<i title="{$clsISO->priceFormat($_OT.money)} {$clsISO->getRate()}" class='bx bx-help-circle' ></i>
								</h5>
								<hr class="w-px-50 my-2" />
								<span class="text-nowrap">{$_OT.title}</span>
							</div>
						</div>
						{/foreach}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-12 col-md-6">
			<!-- Tiền về -->
			{assign var = gId value = $clsISO->getUniqid()}
			{$core->getBlock('money_in', ['gid' => $gId])}
			<!-- End -->
		</div>
		<div class="col-12 col-md-6">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="card h-100">
				<div class="card-header d-flex mb-0 justify-content-between align-items-center">
					<h5 class="card-title mb-0">Chi phí vận hành </h5>
					<a href="/chi-van-hanh.html" data-toggle="ripple" class="btn btn-sm btn-link btn-icon rounded-pill text-muted" title="Chi vận hành">
						<i class="bx bx-link-external text-fs-12"></i>
					</a>
				</div>
				<div class="card-body">
					<div class="form-row autoload" data-url="{$PCMS_URL}/index.php?mod=fund&act=get_opscost_total" gId="{$gId}" data-options='{ldelim}"call_from":"dashboard"{rdelim}'>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-12 col-xxl-8 order-2 order-lg-1">
			<!-- TK ngân hàng -->
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="card-title mb-0">Tiền trong tài khoản</h5>
					<small class="text-muted">Cập nhật lần cuối: <span>{$smarty.now|date_format:"%d/%m/%Y %H:%I:%S"}</span></small>
				</div>
				<div class="card-body">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="form-row autoload mb-2" data-url="{$PCMS_URL}/index.php?mod=home&sub=dashboard&act=load_desktop_bank_accounts" 
						data-options='{ldelim}{rdelim}' gId="{$gId}">
						{$html_bank_account_preloader}
					</div>
				</div>
			</div>
			<!-- End -->
		</div>
		<div class="col-12 col-xxl-4 order-1 order-lg-2 mb-2 mb-lg-0">
			<div class="sticky top-px-90">
				<div class="card">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
						<h5 class="card-title mb-2 mb-lg-0 me-2">Tạm ứng hoa hồng {$current_year}</h5>
					</div>
					<div class="card-body">
						<div class="form-row mb-3">
							<div class="col-6">
								<div class="d-flex align-items-center">
									<div class="avatar">
										<div class="avatar-initial bg-label-success rounded">
											<i class="bx bx-chart"></i>
										</div>
									</div>
									<div class="ms-3 d-flex flex-column">
										<h6 class="mb-0 fs-5 text-primary">{$clsISO->shortNumber($total_advance_amount)}</h6>
										<p class="mb-0 text-muted">Tổng tạm ứng</p>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="d-flex align-items-center">
									<div class="avatar">
										<div class="avatar-initial bg-label-danger rounded">
											<i class="bx bx-chart"></i>
										</div>
									</div>
									<div class="ms-3 d-flex flex-column">
										<h6 class="mb-0 fs-5 text-main">{$clsISO->shortNumber($total_investment)}</h6>
										<p class="mb-0 text-muted">Tiền đã đầu tư</p>
									</div>
								</div>
							</div>
						</div>
						<div class="table-container no-shadow overflow-x-auto text-nowrap">
							<table class="table table-bordered w-100" cellpadding="0" cellspacing="0">
								<thead><tr>
									{if $deviceType ne 'phone'}
									<th class="align-center bg-lighter h-px-35">STT</th>
									{/if}
									<th class="align-center bg-lighter h-px-35">Tên dự án</th>
									<th class="align-center bg-lighter h-px-35">Tạm ứng</th>
									<th class="align-center bg-lighter h-px-35">Ghi chú</th>
								</tr></thead>
								{foreach from=$prepaid_arrs name=i item = _oI}
								<tr>
									{if $deviceType ne 'phone'}
									<td class="text-center">{$smarty.foreach.i.iteration}</td>
									{/if}
									<td>{$_oI.project_name}</td>
									<td>{$_oI.amount}</td>
									<td>{$_oI.notes}</td>
								</tr>
								{/foreach}
								<tfoot><tr>
									<td class="align-center text-center" colspan="2">TỔNG TẠM ỨNG</td>
									<td class="align-center text-center bg-lighter" colspan="2">
										<strong class="fs-5">{$clsISO->formatPrice($total_prepaid)}</strong>
									</td>
								</tr></tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>