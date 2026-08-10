<div class="modal-dialog modal-ipad{if $deviceType eq 'phone'} modal-dialog-centered{/if}">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage} <br />
				<span class="text-muted fs-13">
					{$clsISO->makeIcon('bx-user-plus')}
					{if $action eq '_add'}
						{$clsProfile->getFullName($profile_id, $oneProfile)}
					{else}
						{$clsProfile->getFullName($oneVAT.user_id_update)}
					{/if}
					- {$clsISO->makeIcon('bx-time')} {$clsISO->convertTimeToText($oneVAT.upd_date, true)}
				</span>
			</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-3">
				<div class="col-6 col-md-4 mb-3 mb-lg-0">
					<label for="code" class="form-label mb-1">Ký hiệu</label>
					<input type="text" autocomplete="off" name="symbol" value="{$oneVAT.symbol}" 
					class="form-control required" placeholder="Ký hiệu">
				</div>
				<div class="col-6 col-md-4 mb-3 mb-lg-0">
					<label for="code" class="form-label mb-1">Số HĐ</label>
					<input type="text" autocomplete="off" name="contract_code" value="{$oneVAT.contract_code}" class="form-control required" placeholder="Số HĐ">
				</div>
				<div class="col-12 col-md-4">
					<label for="code" class="form-label mb-1">Ngày HĐ</label>
					<input type="date" autocomplete="off" name="contract_date" value="{$oneVAT.contract_date|date_format:'%Y-%m-%d'}" class="form-control required" placeholder="Ngày HĐ">
				</div>
			</div>
			<div class="form-group form-row mb-3">
				<div class="col-12 col-md-8 mb-3 mb-lg-0">
					<label class="form-label mb-1">Đối tác</label>
					{assign var = uid value = $clsISO->getUniqid()}
					<input type="hidden" name="more_information[partner_id]" class="{$uid}_id" value="{$more_information.partner_id}" />
					<input type="text" uid="{$uid}" class="form-control required autocomplete" name="more_information[partner_name]" data-source="/index.php?mod={$mod}&act=get_property&property_type=_AGENCY" placeholder="Đối tác" value="{$more_information.partner_name}" />
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label mb-1">Loại VAT</label>
					<select name="vat_type" class="form-control required form-select">
						<option value="0">Loại VAT</option>
						{$clsProperty->getSelectByProperty('VAT_TYPE',$oneVAT.vat_type)}
					</select>
				</div>
			</div>
			<hr class="my-3" />
			<div class="p-3 bg-lighter rounded-2">
				<div class="table-wrapper">
					{assign var = gId value = $clsISO->getUniqid()}
					<table class="table table-bordered">
						<thead><tr>
							<th width="5%" class="align-center text-center">No.</th>
							<th width="30%" class="align-center">Mã căn</th>
							<th class="align-center">Sale bán</th>
							<th width="10%"></th>
						</tr></thead>
						{if !empty($more_information.billing)}
							{foreach from=$more_information.billing name=i key = uid item = _oB}
							<tr gId="{$gId}" class="{$gId}">
								<td class="text-center">{$smarty.foreach.i.iteration}</td>
								<td><input type="text" name="more_information[billing][{$uid}][stock_code]" placeholder="Mã căn" 
									class="form-control" value="{$_oB.stock_code}" /></td>
								<td class="text-left">
									<input type="hidden" name="more_information[billing][{$uid}][staff_id]" class="{$uid}_id" value="{$_oB.staff_id}" />
									<input type="text" uid="{$uid}" onChange="$Core.vat._handle_change(this, event)" tp="staff_name" name="more_information[billing][{$uid}][staff_name]" placeholder="Sale bán" class="form-control autocomplete" data-source="/index.php?mod={$mod}&act=get_json_staffs" value="{$_oB.staff_name}" />
								</td>
								<td class="text-center">
									{if $smarty.foreach.i.last}
									<button gId="{$gId}" type="button" onClick="$Core.vat.add_line(this, event)" class="btn btn-icon btn-outline-primary"><i class="bx bx-plus"></i></button>
									{else}
									<button gId="{$gId}" type="button" onClick="$Core.vat.delete_line(this, event)" class="btn btn-icon btn-outline-danger"><i class="bx bx-x"></i></button>
									{/if}
								</td>
							</tr>
							{/foreach}
						{else}
							<tr gId="{$gId}" class="{$gId}">
								<td class="text-center">1</td>
								<td><input type="text" name="more_information[billing][{$gId}][stock_code]" 
									placeholder="Mã căn" class="form-control" /></td>
								<td class="text-left">
									<input type="hidden" name="more_information[billing][{$gId}][staff_id]" class="{$gId}_id" value="0" />
									<input type="text" uid="{$gId}" onChange="$Core.vat._handle_change(this, event)" tp="staff_name" name="more_information[billing][{$gId}][staff_name]" placeholder="Sale bán" class="form-control autocomplete" data-source="/index.php?mod={$mod}&act=get_json_staffs" />
								</td>
								<td class="text-center"><button gId="{$gId}" type="button" onClick="$Core.vat.add_line(this, event)" class="btn btn-icon btn-outline-primary"><i class="bx bx-plus"></i></button></td>
							</tr>
						{/if}
					</table>
				</div>
			</div>
			<hr class="my-3" />
			<div class="form-row mb-3">
				<div class="col-6 col-md-4 mb-3 mb-lg-0">
					<label for="amout" class="form-label mb-1">Số tiền</label>
					<div class="input-group input-group-merge">
						<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$oneVAT.amount}{/if}" name="amount" class="form-control numberonly price-In required" placeholder="0.00" onClick="this.select();">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
				<div class="col-6 col-md-4 mb-3 mb-lg-0">
					<label for="amout" class="form-label mb-1">Tạm ứng</label>
					<div class="input-group input-group-merge">
						<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$oneVAT.deposit_price}{/if}" name="deposit_price" class="form-control numberonly price-In" onChange="$Core.vat._handle_change(this, event)" tp="deposit_price" placeholder="0.00" onClick="this.select();">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
				<div class="col-12 col-md-4">
					<label for="amout" class="form-label mb-1">Tất toán</label>
					<div class="input-group input-group-merge">
						<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$oneVAT.final_price}{/if}" onChange="$Core.vat._handle_change(this, event)" tp="final_price" name="final_price" class="form-control numberonly price-In" placeholder="0.00" onClick="this.select();">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
			</div>
			<div class="mb-3 p-3 bg-lighter rounded-2">
				<div class="form-row">
					<div class="col-6 col-md-4 mb-3 mb-lg-0">
						<label for="amout" class="form-label text-main mb-1">Tình trạng</label>
						<select name="status_id" class="form-control form-select">
							<option value="0">Tình trạng</option>
							{$clsProperty->getSelectByProperty('VAT_STATUS',$oneVAT.status_id)}
						</select>
					</div>
					<div class="col-6 col-md-4">
						<label for="amout" class="form-label mb-1">Ngày thanh toán</label>
						<input type="date" name="final_date" class="form-control required" placeholder="0.00" 
						value="{if !empty($oneVAT.final_date)}{$oneVAT.final_date|date_format:'%Y-%m-%d'}{/if}"  >
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Ghi chú</label>
				<textarea class="form-control" cols="255" rows="2" name="notes" placeholder="Ghi chú">{if $action eq '_edit'}{$oneVAT.notes}{/if}</textarea>
			</div>
		</div>
		<input type="hidden" name="submit" value="Update" />
		<div class="modal-footer">
			<button type="button" data-toggle="ripple" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" data-toggle="ripple" vat_id="{$vat_id}" class="btn btn-primary" 
				onClick="$Core.vat.save_VAT(this, event)">{if $action eq '_add'}Thêm mới{else}Cập nhật{/if}</button>
		</div>
	</form>
</div>
