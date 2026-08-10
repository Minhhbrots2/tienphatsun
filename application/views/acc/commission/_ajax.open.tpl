<div class="modal-dialog modal-ipad{if $deviceType eq 'phone'} modal-dialog-centered{/if}">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage} <br />
				<span class="text-muted fs-13">
					{$clsISO->makeIcon('bx-user-plus')}
					{if $action eq '_add'}
						{$clsProfile->getFullName($profile_id, $oneProfile)}
					{else}
						{$clsProfile->getFullName($oneCommission.user_id_update)}
					{/if}
					- {$clsISO->makeIcon('bx-time')} {$clsISO->convertTimeToText($oneCommission.upd_date, true)}
				</span>
			</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="bg-lighter rounded-2 p-3 mb-2"><div class="form-row">
				<label class="col-form-label text-left col-3 col-md-2">Giao dịch</label>
				<div class="col-9 col-md-10">
					<input type="hidden" name="billing_id" value="{$oneCommission.billing_id}" />
					<select uid="{$uid}" prompt="Giao dịch" style="width:100%" class="form-control form-select" id="cboBilling_{$uid}" 
						data-options="panelWidth:800,
						url:'{$PCMS_URL}/index.php?mod=transaction&act=search_billing&tp=_all',
						mode:'remote',
						method:'post',
						height:'36px',
						multiple:false, 
						value:'{$oneCommission.billing_code}',
						columns: [[
							{ldelim}field:'billing_code',title:'Mã GD',width:70,align:'left'{rdelim},
							{ldelim}field:'deposit_date',title:'Ngày cọc',width:85,align:'left'{rdelim},
							{ldelim}field:'staff_name',title:'Nhân viên',width:120,align:'left'{rdelim},
							{ldelim}field:'stock_code',title:'Mã căn',width:80{rdelim},
							{ldelim}field:'project_name',title:'Dự án',width:80,align:'left'{rdelim},
							{ldelim}field:'totalgrand',title:'Tồng tiền',width:80,align:'left'{rdelim}
						]],
						idField:'billing_code',
						textField:'billing_code',
						fitColumns:true,
						showHeader: true,
						nowrap: false,
						showFooter:true,
						pagination:true,
						pageSize: 20">
					</select>
				</div>
			</div></div>
			<hr class="my-2" />
			<div class="p-3 mb-2 bg-lighter rounded-2">
				<div class="form-row mb-3">
					<div class="col-6 col-md-3 mb-3 mb-lg-0">
						<label for="amout" class="form-label mb-1">% HHMG</label>
						<div class="input-group input-group-merge">
							<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.commission}{else}{$smarty.const._COMMISSION_AGENCY_DEF}{/if}" name="commission" class="form-control numberonly required" placeholder="0.00" onClick="this.select();">
							<span class="input-group-text">.%</span>
						</div>
					</div>
					<div class="col-6 col-md-3 mb-3 mb-lg-0">
						<label for="amout" class="form-label mb-1">% HH PKD</label>
						<div class="input-group input-group-merge">
							<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.sale_department_commission}{else}{$smarty.const._COMMISSION_SALE_DEPARTMENT_DEF}{/if}" name="sale_department_commission" class="form-control numberonly required" placeholder="0.00" onClick="this.select();">
							<span class="input-group-text">.%</span>
						</div>
					</div>
					<div class="col-6 col-md-3 mb-3 mb-lg-0">
						<label for="amout" class="form-label mb-1">% HH Sale bán</label>
						<div class="input-group input-group-merge">
							<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.seller_commission}{else}{$smarty.const._COMMISSION_SALE_DEF}{/if}" name="seller_commission" class="form-control numberonly required" placeholder="0.00" onClick="this.select();">
							<span class="input-group-text">.%</span>
						</div>
					</div>
					<div class="col-6 col-md-3 mb-3 mb-lg-0">
						<label for="amout" class="form-label mb-1">% HH GĐKD</label>
						<div class="input-group input-group-merge">
							<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.project_director_commission}{else}{$smarty.const._COMMISSION_PROJECT_DIRECTOR_DEF}{/if}" name="project_director_commission" class="form-control numberonly required" placeholder="0.00" onClick="this.select();">
							<span class="input-group-text">.%</span>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-6 col-md-3 mb-3 mb-lg-0">
						<label for="amout" class="form-label mb-1">Thưởng sale</label>
						<div class="input-group input-group-merge">
							<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.seller_bonus}{else}0{/if}" name="seller_bonus" class="form-control numberonly price-In" placeholder="0.00" onClick="this.select();">
							<span class="input-group-text">.{$clsISO->getRate()}</span>
						</div>
					</div>
					<div class="col-6 col-md-3 mb-3 mb-lg-0">
						<label for="amout" class="form-label mb-1">Thưởng đại lý</label>
						<div class="input-group input-group-merge">
							<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.agency_bonus}{else}0{/if}" name="agency_bonus" class="form-control numberonly price-In" placeholder="0.00" onClick="this.select();">
							<span class="input-group-text">.{$clsISO->getRate()}</span>
						</div>
					</div>
					<div class="col-6 col-md-3 mb-3 mb-lg-0">
						<label for="amout" class="form-label mb-1">Thưởng Marketing</label>
						<div class="input-group input-group-merge">
							<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.marketing_bonus}{else}0{/if}" name="marketing_bonus" class="form-control numberonly price-In" placeholder="0.00" onClick="this.select();">
							<span class="input-group-text">.{$clsISO->getRate()}</span>
						</div>
					</div>
					<div class="col-6 col-md-3 mb-3 mb-lg-0">
						<label for="amout" class="form-label mb-1">Hỗ trợ PKD</label>
						<div class="input-group input-group-merge">
							<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.sale_department_support_money}{else}0{/if}" name="sale_department_support_money" class="form-control numberonly price-In" placeholder="0.00" onClick="this.select();">
							<span class="input-group-text">.{$clsISO->getRate()}</span>
						</div>
					</div>
				</div>
			</div>
			<hr class="my-2" />
			<div class="widget-block mb-3">
				<h5 class="widget-header">Tạm ứng</h5>
				<div class="widget-content">
					<div class="form-row">
						<div class="col-6 col-md-3 mb-3 mb-lg-0">
							<label for="amout" class="form-label mb-1">Công ty nhận về</label>
							<div class="input-group input-group-merge">
								<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.deposit_money_company_received}{else}0{/if}" name="deposit_money_company_received" class="form-control numberonly price-In" placeholder="0.00" onClick="this.select();">
								<span class="input-group-text">.{$clsISO->getRate()}</span>
							</div>
						</div>
						<div class="col-6 col-md-3 mb-3 mb-lg-0">
							<label for="amout" class="form-label mb-1">Sale</label>
							<div class="input-group input-group-merge">
								<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$more_information.deposit_money}{else}0{/if}" name="deposit_money" class="form-control numberonly price-In" placeholder="0.00" onClick="this.select();">
								<span class="input-group-text">.{$clsISO->getRate()}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr class="my-2" />
			<div class="widget-block mb-2">
				<h5 class="widget-header">Tình trạng</h5>
				<div class="widget-content">
					<div class="form-row">
						<div class="col-6 col-md-4 mb-2 mb-lg-0">
							<label for="amout" class="form-label mb-1">Thanh toán từ CĐT</label>
							<select name="status_company_id" class="form-control form-select">
								<option value="0">Tình trạng</option>
								{$clsProperty->getSelectByProperty('COMMISSION_PAYMENT_STATUS',$oneCommission.status_company_id)}
							</select>
						</div>
						<div class="col-6 col-md-4 mb-2 mb-lg-0">
							<label for="amout" class="form-label mb-1">Thanh toán Sale</label>
							<select name="status_sale_id" class="form-control form-select">
								<option value="0">Tình trạng</option>
								{$clsProperty->getSelectByProperty('COMMISSION_PAYMENT_STATUS',$oneCommission.status_sale_id)}
							</select>
						</div>
						<div class="col-6 col-md-4">
							<label for="amout" class="form-label mb-1">Giao dịch</label>
							<select name="status_id" class="form-control form-select">
								<option value="0">Tình trạng</option>
								{$clsProperty->getSelectByProperty('COMMISSION_STATUS',$oneCommission.status_id)}
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Ghi chú</label>
				<textarea class="form-control" cols="255" rows="2" name="notes" placeholder="Ghi chú">{if $action eq '_edit'}{$more_information.notes}{/if}</textarea>
			</div>
		</div>
		<input type="hidden" name="submit" value="Update" />
		<div class="modal-footer">
			<button type="button" data-toggle="ripple" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" data-toggle="ripple" commission_id="{$commission_id}" class="btn btn-primary" 
				onClick="$Core.global.commission.save(this, event)">{if $action eq '_add'}Thêm mới{else}Cập nhật{/if}</button>
		</div>
	</form>
</div>
