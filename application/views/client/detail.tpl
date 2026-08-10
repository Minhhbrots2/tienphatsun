<div class="container-xxl flex-grow-1 pt-2 container-p-y" >
	<div class="row">
		<div class="col-xxl-9 mx-auto">
			<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
				<div class="p__left mb-2 mb-lg-0">
					<h4 class="fw-bold mb-1"><a class="text-black" href="/quan-ly-khach-hang.html"><i class='bx bx-chevron-left me-2'></i>Danh sách khách hàng</a></h4>
				</div>
			</div>
			<div class="card mb-4">
				<div class="card-header bg-lightest d-flex flex-wrap justify-content-between align-items-center">
					<div class="d-flex flex-column align-items-center w-100">
						<div class="avatar avatar-xl mb-2">
							<span class="avatar-initial rounded-circle bg-info">{$clsClient->getCharFirst($oneItem.full_name)}</span>
						</div>
						<h5 class="text-main mb-2 fs-3">{$oneItem.full_name}</h5>
						{if $deviceType ne "phone"}
							<div class="d-flex justify-content-center align-items-center">
								<span class="me-2 fs-5"><a href="mailto:{$oneItem.email}"><i class="bx bx-envelope me-1"></i>{$oneItem.email}</a></span>
								<span class="fs-5"><a href="tel:{$oneItem.phone}"><i class="bx bx-phone-call me-1"></i>{$oneItem.phone}</a></span>
							</div>
						{/if}
					</div>
				</div>
				<div class="card-body">
					<h5 class="mb-2 mt-4 fs-4">Thông tin cá nhân</h5>
					{if $deviceType eq 'phone'}
						<div class="lst_info d-flex flex-wrap">
							<div class="item w-100 mb-2">
								<span class="fw-semibold mr-1">Email:</span>
								{if $oneItem.email}
									{$oneItem.email}
								{else}
									Chưa cập nhật
								{/if}
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Điện thoại:</span>
								{if $oneItem.phone}
									{$oneItem.phone}
								{else}
									Chưa cập nhật
								{/if}
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Giới tính:</span>
								{if $oneItem.gender}
									{$oneItem.gender}
								{else}
									Chưa cập nhật
								{/if}
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Ngày sinh:</span>
								{if $oneItem.birthday}
									{$oneItem.birthday}
								{else}
									Chưa cập nhật
								{/if}
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">CMT/CCID:</span>
								{if $oneItem.identity_card}
									{$oneItem.identity_card}
								{else}
									Chưa cập nhật
								{/if}
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Ngày cấp:</span>
								{if $oneItem.issuance_date}
									{$oneItem.issuance_date}
								{else}
									Chưa cập nhật
								{/if}
							</div>
							<div class="item w-50 d-flex flex-column mb-2">
								<span class="fw-semibold mr-1">Nơi cấp:</span>
								{if $oneItem.issuance_location}
									{$oneItem.issuance_location}
								{else}
									Chưa cập nhật
								{/if}
							</div>
							<div class="item w-100 mb-2">
								<span class="fw-semibold mr-1">Địa chỉ thường trú:</span>
								{if $oneItem.address}
									{$oneItem.address}
								{else}
									Chưa cập nhật
								{/if}
							</div>
							<div class="item w-100 mb-2">
								<span class="fw-semibold mr-1">Địa chỉ hiện tại:</span>
								{if $oneItem.contact_address}
									{$oneItem.contact_address}
								{else}
									Chưa cập nhật
								{/if}
							</div>
						</div>
					{else}
						<div class="highlights">
							<div class="highlight-panel">
								<div class="highlight-list">
									<div class="highlight-item">
										<div class="highlight-label">Email</div>
										<div class="metadata-row-viewer">
											{if $oneItem.email}
												{$oneItem.email}
											{else}
												Chưa cập nhật
											{/if}
										</div>
									</div>
									<div class="highlight-item">
										<div class="highlight-label">Điện thoại</div>
										<div class="metadata-row-viewer text-bold">
											{if $oneItem.phone}
												{$oneItem.phone}
											{else}
												Chưa cập nhật
											{/if}
										</div>
									</div>
									<div class="highlight-item">
										<div class="highlight-label">Giới tính</div>
										<div class="metadata-row-viewer text-bold">
											{if $oneItem.gender}
												{$oneItem.gender}
											{else}
												Chưa cập nhật
											{/if}
										</div>
									</div>
									<div class="highlight-item">
										<div class="highlight-label">Ngày sinh</div>
										<div class="metadata-row-viewer text-bold">
											{if $oneItem.birthday}
												{$oneItem.birthday}
											{else}
												Chưa cập nhật
											{/if}
										</div>
									</div>
								</div>
								<div class="highlight-list">
									<div class="highlight-item">
										<div class="highlight-label">CMT/CCID</div>
										<div class="metadata-row-viewer">
											{if $oneItem.identity_card}
												{$oneItem.identity_card}
											{else}
												Chưa cập nhật
											{/if}
										</div>
									</div>
									<div class="highlight-item">
										<div class="highlight-label">Ngày cấp</div>
										<div class="metadata-row-viewer text-bold">
											{if $oneItem.issuance_date}
												{$oneItem.issuance_date}
											{else}
												Chưa cập nhật
											{/if}
										</div>
									</div>
									<div class="highlight-item">
										<div class="highlight-label">Nơi cấp</div>
										<div class="metadata-row-viewer text-bold">
											{if $oneItem.issuance_location}
												{$oneItem.issuance_location}
											{else}
												Chưa cập nhật
											{/if}
										</div>
									</div>
									<div class="highlight-item">
									</div>
								</div>
								<div class="highlight-list">						
									<div class="highlight-item flex-fill">
										<div class="highlight-label">Địa chỉ thường trú</div>
										<div class="metadata-row-viewer text-bold">
											{if $oneItem.address gt '0'}
												{$oneItem.address}
											{else}
												Chưa cập nhật
											{/if}
										</div>
									</div>
									<div class="highlight-item flex-fill">
										<div class="highlight-label">Địa chỉ hiện tại</div>
										<div class="metadata-row-viewer text-bold">
											{if $oneItem.contact_address gt '0'}
												{$oneItem.contact_address}
											{else}
												Chưa cập nhật
											{/if}
										</div>
									</div>
								</div>
							</div>
						</div>
					{/if}
					<h5 class="mt-4 mb-2 fs-4">Danh sách giao dịch</h5>
					<div class="table-responsive dragscroll text-nowrap">
						<table class="table table-striped mb-2" width="100%">
							<thead><tr>
								<th class="align-center" width="90px">Mã GD</th>
								<th class="align-center" width="100px">Mã căn</th>
								<th class="align-center" width="100px">Ngày cọc</th>
								<th class="align-center" width="100px">Ngày ký HĐMB</th>
								<th class="align-center" width="200px">Sale bán</th>
								<th class="align-center text-right" width="130px">Doanh số</th>
								<th class="align-center text-right" width="130px">Ủy nhiệm chi</th> 
							</tr> </thead>
							<tbody class="table-border-bottom-0">
								{if !empty($listBilling)}
									{foreach from=$listBilling item=oneBilling name=i}
										<tr class="trBilling" ondblclick="view_billing(this, event)" billing_id="{$oneBilling.billing_id}">
											<td data-label="Mã GD">{$oneBilling.billing_code}</td>
											<td data-label="Mã căn">
												<a target="_blank" href="/{$oneBilling.stock_code}.html">
													{$clsISO->makeIcon('bx-link-external', $oneBilling.stock_code)}
												<a/>
											</td>
											<td data-label="Ngày cọc">
												{if !empty($oneBilling.deposit_date)}
													{$clsISO->convertTimeToText($oneBilling.deposit_date)}
												{else}
													--
												{/if}
											</td>
											<td data-label="Ngày ký HĐMB">
												{if $oneBilling.estimate_date gt '0'}
													{$clsISO->convertTimeToText($oneBilling.estimate_date)}
												{else}
													-- 
												{/if}
											</td>
											<td data-label="Nhân viên" class="text-wrap">{$clsProfile->getIndentity($oneBilling.staff_id)}</td>
											<td data-label="Tổng tiền GD" class="text-right">
												{if !empty($oneBilling.totalgrand)}
													{$clsISO->formatNumberToEasyRead($oneBilling.totalgrand)} {$clsISO->getRate()}
												{else}
													--
												{/if}
											</td>
											<td data-label="Uỷ nhiệm chi" class="text-right">
												{if !empty($more_information.payment_order)}
													<ul class="list-unstyled mb-1">	
														{foreach from = $more_information.payment_order item = _olink}
														<li><a class="download" target="_blank" data-fancybox="true" href="{$clsISO->getGoogleUrl($_olink.image)}">{$_olink.title}</a></li>
														{/foreach}
													</ul>
												{/if}
											</td>
										</tr>
									{/foreach}
								{else}
									<tr>
										<td colspan="7" class="text-center">Danh sách trống!</td>
									</tr>
								{/if}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>