<div class="modal-dialog modal-ipad-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Thông tin giao dịch {$oneBilling.billing_code}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="highlights">
			<div class="highlight-panel">
				<div class="highlight-list">
					<div class="highlight-item">
						<div class="highlight-label">Nhân viên</div>
						<div class="metadata-row-viewer text-nowrap">
							{$clsProfile->getIndentity($oneBilling.staff_id)}
						</div>
					</div>
					<div class="highlight-item">
						<div class="highlight-label">Mã căn</div>
						<div class="metadata-row-viewer text-bold">
							{if !empty($oneBilling.stock_code)}
								{$oneBilling.stock_code}
							{else}
								--
							{/if}
						</div>
					</div>
					<div class="highlight-item">
						<div class="highlight-label">Ngày cọc</div>
						<div class="metadata-row-viewer text-bold">
							{if !empty($oneBilling.deposit_date)}
								{$clsISO->convertTimeToText($oneBilling.deposit_date)}
							{else}
								--
							{/if}
						</div>
					</div>
					<div class="highlight-item">
						<div class="highlight-label">Số tiền ({$clsISO->getRate()})</div>
						<div class="metadata-row-viewer text-bold">
							{if !empty($oneBilling.totalgrand)}
								{$clsISO->formatNumberToEasyRead($oneBilling.totalgrand)}
							{else}
								--
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-body scroller">
			<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
				{assign var = tabid value = $clsISO->getUniqid()}
				<li class="nav-item"><button type="button" class="nav-link{if $tabfocus eq '1'} active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#tabpanel-home-{$tabid}"><i class="material-icons-outlined">account_balance_wallet</i> Chi tiết</button></li>
				<li class="nav-item"><button type="button" class="nav-link{if $tabfocus eq '2'} active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#tabpanel-notes-{$tabid}"><i class="material-icons-outlined">speaker_notes</i> Ghi chú</button></li>
				<li class="nav-item"><button type="button" class="nav-link{if $tabfocus eq '3'} active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#tabpanel-file-{$tabid}"><i class="material-icons-outlined">cloud_upload</i> File đính kèm</button></li>
			</ul>
			<div class="tab-content py-3 px-0">
				<div class="tab-pane fade{if $tabfocus eq '1'} show active{/if}" id="tabpanel-home-{$tabid}" role="tabpanel">
					<div class="widget-block">
						<div class="widget-header">Thông tin giao dịch</div>
						<div class="widget-content mb-3">
							<table class="clientssummarystats" width="100%">
								<tr>
									<td class="text-right" width="{if $deviceType eq 'phone'}40{else}25{/if}%">Mã GD</td>
									<td colspan="3">{$oneBilling.billing_code}</td>
								</tr>
								<tr>
									<td class="text-right">Ngày cọc</td>
									<td colspan="3">
										{$clsISO->convertTimeToText($oneBilling.deposit_date)}
									</td>
								</tr>
								<!-- <tr>
									<td class="text-right">CSBH căn bán</td>
									<td colspan="3">
										{if !empty($more_information.sale_policy_date)}
											{$more_information.sale_policy_date}
										{/if}
									</td>
								</tr> -->
								<tr>
									<td class="text-right">File CSBH</td>
									<td colspan="3">
										{if !empty($more_information.sale_policy_file)}
										<a class="download" target="_blank" href="{$more_information.sale_policy_file}">{$more_information.sale_policy_file}<a/>
										{else}
										<span class="text-muted d-flex align-items-center">
											<i class="bx bx-error text-muted"></i> Chưa có
										</span>
										{/if}
									</td>
								</tr>
								<tr>
									<td class="text-right">Loại hình</td>
									<td colspan="3">
										{if $oneBilling.billing_type gt '0'}
											{$clsProperty->getTitle($oneBilling.billing_type)}
										{else}
											<span class="text-muted d-flex align-items-center">
												<i class="bx bx-error text-muted"></i> Chưa có
											</span>
										{/if}
									</td>
								</tr>
								<tr>
									<td class="text-right">Dự án</td>
									<td colspan="3">{$clsProject->getTitle($oneBilling.project_id)}</td>
								</tr>
								<tr>
									<td class="text-right">Mã căn</td>
									<td colspan="3">
										{$oneBilling.stock_code}
									</td>
								</tr>
								<tr>
									<td class="text-right">Khách hàng</td>
									<td colspan="3">
										{if isset($more_information.customer_name) && !empty($more_information.customer_name)}
											{$more_information.customer_name}
										{else}
											<span class="text-muted d-flex align-items-center">
												<i class="bx bx-error text-muted"></i> Chưa có
											</span>
										{/if}
									</td>
								</tr>
								<tr>
									<td class="text-right">Số tiền</td>
									<td colspan="3">
										{if !empty($oneBilling.totalgrand)}
											{$clsISO->formatNumberToEasyRead($oneBilling.totalgrand)} {$clsISO->getRate()}
										{else}
											<span class="text-muted d-flex align-items-center">
												<i class="bx bx-error text-muted"></i> Chưa có
											</span>
										{/if}
									</td>
								</tr>
								<tr>
									<td class="text-right">File PTG</td>
									<td colspan="3">
										{if !empty($more_information.price_sheet_file)}
											{foreach from = $more_information.price_sheet_file item = _olink}
											<a class="download" target="_blank" data-fancybox="true" href="{$clsISO->getGoogleUrl($_olink.image)}">{$_olink.title}<a/>
											{/foreach}
										{else}
											<span class="text-muted d-flex align-items-center">
												<i class="bx bx-error text-muted"></i> Chưa có
											</span>
										{/if}
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="tab-pane fade{if $tabfocus eq '2'} show active{/if}" id="tabpanel-notes-{$tabid}" role="tabpanel">
					<div class="widget-block mb-2">
						<div class="widget-header">Thêm ghi chú</div>
						<div class="widget-content">
							<form class="frmIssue" name="" action="">
								<textarea class="form-control required" name="content" rows="2" placeholder="Nhập ghi chú"></textarea>
								<div class="clearfix mt-2">
									<button type="button" tp="_create" class="btn btn-outline-primary" 
									for_id="{$billing_id}" clsTable="Billing" note_id="" billing_id="{$billing_id}" customer_id="{$customer_id}" onClick="$Core.crm.save_notes(this,event)">Thêm</button>
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
				<div class="tab-pane fade{if $tabfocus eq '3'} show active{/if}" id="tabpanel-file-{$tabid}" role="tabpanel">
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
									billing_id="{$billing_id}" customer_id="{$customer_id}" onClick="$Core.crm.ms_save_file(this,event)">Thêm</button>
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
		</div>
	</div>
</div>
