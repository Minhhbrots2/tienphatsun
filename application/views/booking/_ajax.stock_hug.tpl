<div class="modal-dialog modal-dialog-centered modal-ipad">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Chỉnh sửa{/if} quỹ ôm</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-2">
				<div class="col-6 col-md-4">
					<label for="deposit_date" class="form-label mb-1">Mã căn</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-code"></i></span>
						<input type="text" class="form-control required" onChange="$Core.stock_hug.hanlde_stock_code(this, event)" name="stock_code" autocomplete="off" placeholder="S1.01XXXX" value="{if $action eq '_edit'}{$oneStockHug.stock_code}{/if}">
					</div>
				</div>
				<div class="col-6 col-md-4">
					<label for="deposit_date" class="form-label mb-1">Loại căn</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-bed"></i></span>
						<select class="form-control form-select required" name="bedroom_id">
							<option value="0">Loại căn</option>
							{$clsProperty->getSelectByProperty('_BEDROOM',$oneStockHug.bedroom_id)}
						</select>
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
			<div class="bg-lighter rounded-2 p-3 mb-2">
				<div class="form-group form-row mb-2">
					<div class="col-12 col-md-5 mb-2 mb-lg-0">
						<label class="form-label mb-1">Dự án</label>
						<div class="clearfix"></div>
						<select class="form-control iso-selectize required w-100" placeholder="Chọn dự án" 
						uid="{$uid}" name="project_id" onChange="$Core.booking.load_block(this, event)" data-optgroup="false">
							
						</select>
					</div>
					<div class="col-6 col-md-4">
						<label id="product_code" class="form-label mb-1">Phân khu</label>
						<div class="clearfix"></div>
						<div class="slb_block_{$uid}">
							<select placeholder="Phân khu" class="iso-selectizeNotSearch w-100" uid="{$uid}" name="block_id" 
							onChange="$Core.booking.load_building(this, event)" data-optgroup="false">
								<option value="0">Phân khu</option>
								{if !empty($list_blocks)}
									{foreach from=$list_blocks item = _oBlock}
									<option value="{$_oBlock.property_id}"{if $_oBlock.property_id eq $oneStockHug.block_id} selected="selected"{/if}>{$_oBlock.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<label for="totalgrand" class="form-label mb-1">Tòa nhà</label>
						<div class="clearfix"></div>
						<div class="slb_building_{$uid}">
							<select placeholder="Tòa nhà" class="iso-selectizeNotSearch w-100" uid="{$uid}" 
							data-optgroup="false" name="building_id">
								<option value="0">Tòa nhà</option>
								{if !empty($list_buildings)}
									{foreach from=$list_buildings item = _oBuilding}
									<option value="{$_oBuilding.property_id}"{if $_oBuilding.property_id eq $oneStockHug.building_id} selected="selected"{/if}>{$_oBuilding.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-row form-group mb-3">
				<div class="col-4 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Tiền cọc</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-money"></i></span>
						<input type="text" class="form-control price-In" value="{if $action eq '_edit'}{$oneStockHug.deposit_price}{else}0{/if}" onClick="this.select()" name="deposit_price" placeholder="0.00 {$clsISO->getRate()}">
					</div>
				</div>
				<div class="col-4 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Giá bán</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-money"></i></span>
						<input type="text" class="form-control price-In" value="{if $action eq '_edit'}{$oneStockHug.totalgrand}{else}0{/if}" onClick="this.select()" name="totalgrand" placeholder="0.00 {$clsISO->getRate()}">
					</div>
				</div>
				<div class="col-4 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Tiền ôm 10%</label>
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-money"></i></span>
						<input type="text" class="form-control price-In" value="{if $action eq '_edit'}{$oneStockHug.deposit_amount}{else}0{/if}" onClick="this.select()" name="deposit_amount" placeholder="0.00 {$clsISO->getRate()}">
					</div>
				</div>
			</div>
			<div class="bg-lighter rounded-2 p-3 mb-2">
				<div class="mb-2 text-muted">Thông tin ký XNĐK</div>
				<div class="form-row mb-2">
					<div class="col-6 col-md-4 mb-2 mb-lg-0">
						<label for="deposit_date" class="form-label mb-1">Người ký</label>
						<input type="text" class="form-control required" autocomplete="off" placeholder="Nguyên Văn A" 
							name="otp_customer" value="{if $action eq '_edit'}{$more_information.otp_customer}{/if}">
					
					</div>
					<div class="col-6 col-lg-3 mb-2 mb-lg-0">
						<label for="deposit_date" class="form-label mb-1">Ngày ký</label>
						<input type="date" class="form-control" name="otp_date" placeholder="Tên khách..." value="{if $action eq '_edit'}{$more_information.otp_date|date_format:'%Y-%m-%d'}{/if}">
					</div>
					<div class="col-12 col-lg-5">
						<label for="trans_code" class="form-label mb-1">Link ký</label>
						<input type="text" class="form-control" name="otp_reg_link" placeholder="https://..." value="{if $action eq '_edit'}{$more_information.otp_reg_link}{/if}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Ghi chú</label>
				<textarea class="form-control" name="notes" cols="255" rows="2" placeholder="Ghi chú">{if !empty($more_information.notes)}{$more_information.notes}{/if}</textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" holderG="save" stock_hug_id="{$stock_hug_id}" 
			onClick="$Core.stock_hug.save(this, event)" class="btn btn-primary">Lưu lại</button>
			<button type="button" holderG="continue" stock_hug_id="{$stock_hug_id}" 
			onClick="$Core.stock_hug.save(this, event)" class="btn btn-success">Lưu & thêm mới</button>	
		</div>
	</form>
</div>
