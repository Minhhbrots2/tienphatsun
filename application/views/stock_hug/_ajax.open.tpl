<div class="modal-dialog modal-ipad">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Chỉnh sửa{/if} quỹ ôm MWF</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-3">
				<div class="col-6 col-lg-4 mb-2 mb-lg-0">
					<label for="deposit_date" class="form-label mb-1">Mã căn</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-code"></i></span>
						<input type="text" class="form-control required" onChange="$Core.stock_hug.hanlde_stock_code(this, event)" name="stock_code" autocomplete="off" placeholder="S1.01XXXX" value="{if $action eq '_edit'}{$oneStockHug.stock_code}{/if}">
					</div>
				</div>
				<div class="col-6 col-lg-4 mb-2 mb-lg-0">
					<label for="deposit_date" class="form-label mb-1">Loại căn</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-bed"></i></span>
						<select class="form-control form-select required" name="bedroom_type">
							<option value="0">Loại căn</option>
							{$clsProperty->getSelectByProperty('_BEDROOM',$oneStockHug.bedroom_type)}
						</select>
					</div>
				</div>
				<div class="col-12 col-lg-4">
					<label for="trans_code" class="form-label mb-1">Họ tên khách theo UNC</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-user"></i></span>
						<input type="text" class="form-control" name="customer_name" placeholder="Tên khách..." value="{if $action eq '_edit'}{$oneStockHug.customer_name}{/if}">
					</div>
				</div>
			</div>
			<div class="widget-block mb-3">
				<div class="widget-header mb-2">Đại lý đi tiền</div>
				<div class="widget-content">
					<div class="form-row">
						<div class="col-6 col-lg-4 mb-2 mb-lg-0">
							<label for="deposit_date" class="form-label mb-1">Đại lý</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class="bx bx-user"></i></span>
								<select class="form-control form-select required" name="agency_id">
									{$clsProperty->getSelectByProperty('_AGENCY',$oneStockHug.agency_id)}
								</select>
							</div>
						</div>
						<div class="col-6 col-lg-4 mb-2 mb-lg-0">
							<label for="trans_code" class="form-label mb-1">Số tiền cọc CĐT</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class="bx bx-money"></i></span>
								<input type="text" name="deposit_price" value="{if $action eq '_edit'}{$oneStockHug.deposit_price}{else}50000000{/if}" onClick="this.select()" class="form-control price-In" placeholder="0.00{$clsISO->getRate()}">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="widget-block d-none mb-3">
				<div class="widget-header mb-2">Tiền Đóng Góp</div>
				<div class="widget-content">
					<div class="form-row">
						<div class="col-6 col-lg-4 mb-2 mb-lg-0">
							<label for="trans_code" class="form-label mb-1">{$smarty.const.BRAND_NAME}</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class="bx bx-money"></i></span>
								<input type="text" class="form-control price-In" value="{if $action eq '_edit'}{$oneStockHug.deposit_fh}{else}50000000{/if}" onClick="this.select()" name="deposit_fh" placeholder="0.00{$clsISO->getRate()}">
							</div>
						</div>
						<div class="col-6 col-lg-4">
							<label for="trans_code" class="form-label mb-1">ATD & EH</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class="bx bx-money"></i></span>
								<input type="text" class="form-control price-In" value="{if $action eq '_edit'}{$oneStockHug.deposit_ns}{else}0{/if}" onClick="this.select()" name="deposit_ns" placeholder="0.00{$clsISO->getRate()}">
							</div>
						</div>
					</div>
					<div class="form-group mb-3">
						<div class="form-check custom-option custom-option-basic">
							<label class="form-check-label custom-option-content" >
								<input class="form-check-input" type="checkbox" name="is_contributed"{if $oneStockHug.is_contributed eq '1'} checked{/if} value="1">
								<span class="custom-option-header">
									<span class="h6 mb-0">Xác nhận</span>
								</span>
								<span class="custom-option-body">
									<small class="option-text">Đã góp đủ tiền 2 bên</small>
								</span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="widget-block mb-3">
				<div class="widget-header mb-2">Ký cọc</div>
				<div class="widget-content">
					<div class="form-row">
						<div class="col-4 mb-2 mb-lg-0">
							<label for="trans_code" class="form-label mb-1">Ngày cọc</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class="bx bx-calendar"></i></span>
								<input type="date" class="form-control" name="deposit_date"{if !empty($oneStockHug.deposit_date)} value="{$oneStockHug.deposit_date|date_format:'%Y-%m-%d'}"{/if} placeholder="Search...">
							</div>
						</div>
						<div class="col-4">
							<label for="trans_code" class="form-label mb-1">Ngày ký OTP</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class="bx bx-calendar"></i></span>
								<input type="date" class="form-control" {if !empty($oneStockHug.otp_date)} value="{$oneStockHug.otp_date|date_format:'%Y-%m-%d'}"{/if} name="otp_date" placeholder="Search...">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="bg-lighter p-3 rounded-2 mb-3">
				<div class="form-row form-group">
					<div class="col-6 col-lg-4">
						<label for="trans_code" class="form-label mb-1">Ngày tra soát</label>
						<div class="input-group input-group-merge">
							<span class="input-group-text"><i class="bx bx-calendar"></i></span>
							<input type="date" class="form-control"{if !empty($oneStockHug.check_date)} value="{$oneStockHug.check_date|date_format:'%Y-%m-%d'}"{/if} name="sale_date" placeholder="dd/mm/yyyy">
						</div>
					</div>
					<div class="col-6 col-lg-4">
						<label for="trans_code" class="form-label mb-1">Tình trạng</label>
						<div class="input-group input-group-merge">
							<span class="input-group-text"><i class="bx bx-sushi"></i></span>
							<select class="form-control form-select required" name="status_id">
								{$clsProperty->getSelectByProperty('_STATUS_STOCK_HUG',$oneStockHug.status_id)}
							</select>
						</div>
					</div>
				</div>	
			</div>
			<div class="widget-block mb-2">
				<div class="widget-header mb-2">Thông tin bên bán</div>
				<div class="widget-content">
					<div class="form-row mb-2">
						<div class="col-4 mb-2 mb-lg-0">
							<label for="trans_code" class="form-label mb-1">Đại lý bán</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class="bx bx-group"></i></span>
								<select class="form-control form-select" name="sale_agency_id">
									<option value="0">Lựa chọn đại lý bán</option>
									{$clsProperty->getSelectByProperty('_AGENCY',$oneStockHug.sale_agency_id)}
								</select>
							</div>
						</div>
						<div class="col-4 mb-2 mb-lg-0">
							<label for="trans_code" class="form-label mb-1">Ngày bán</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class="bx bx-calendar"></i></span>
								<input type="date" class="form-control"{if !empty($oneStockHug.sale_date)} value="{$oneStockHug.sale_date|date_format:'%Y-%m-%d'}"{/if} name="sale_date" placeholder="dd/mm/yyyy">
							</div>
						</div>
						<div class="col-4 mb-2 mb-lg-0">
							<label for="trans_code" class="form-label mb-1">Tiền cọc về</label>
							<div class="input-group input-group-merge">
								<span class="input-group-text"><i class="bx bx-money"></i></span>
								<input type="text" class="form-control price-In" value="{if $action eq '_edit'}{$oneStockHug.deposit_refund}{else}0{/if}" onClick="this.select()" name="deposit_refund" placeholder="0.00 {$clsISO->getRate()}">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-check custom-option custom-option-basic d-none">
				<label class="form-check-label custom-option-content" >
					<input class="form-check-input" type="checkbox" value="1" 
						name="refund_2_sides"{if $oneStockHug.refund_2_sides eq '1'} checked{/if}>
					<span class="custom-option-header">
						<span class="h6 mb-0">Xác nhận</span>
					</span>
					<span class="custom-option-body">
						<small class="option-text">Đã hoàn đủ tiền 2 bên</small>
					</span>
				</label>
			</div>
			<div class="form-group">
				<label class="col-form-label">Ghi chú</label>
				<textarea class="form-control" name="notes" cols="255" rows="2" placeholder="Ghi chú">{if !empty($more_information.notes)}{$more_information.notes}{/if}</textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" holderG="save" stock_hug_id="{$stock_hug_id}" 
			onClick="$Core.stock_hug.save(this, event)" class="btn btn-primary">Lưu lại</button>
			<button type="button" holderG="continue" stock_hug_id="{$stock_hug_id}" 
			onClick="$Core.stock_hug.save(this, event)" class="btn btn-success">Lưu & thêm mới</button>	
		</div>
	</form>
</div>
