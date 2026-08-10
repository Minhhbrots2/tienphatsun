<div class="modal-dialog modal-ipad-xl{if $deviceType eq 'phone'} modal-dialog-centered{/if}">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">{$titlePage} <br />
				<span class="text-muted fs-13">
					{$clsISO->makeIcon('bx-user-plus')}
					{if $action eq '_add'}
						{$clsProfile->getFullName($profile_id,$oneProfile)}
					{else}
						{$clsProfile->getFullName($oneFund.user_id_update, $oProfile.oProfile)}
					{/if}
					- {$clsISO->makeIcon('bx-time')} {$clsISO->convertTimeToText($oneFund.upd_date, true)}
				</span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-2">
				<div class="col-12 col-md-4">
					<label for="code" class="form-label mb-1">Mã phiếu</label>
					<input type="text" autocomplete="off" name="code" value="{if $action eq '_edit'}{$oneFund.code}{else}{$clsFund->genCode($gr)}{/if}" class="form-control required" placeholder="Mã phiếu">
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label for="email" class="form-label mb-1">Ngày {if $gr eq 'THUCTHU'}thu{else}chi{/if}</label>
					<input type="datetime-local" autocomplete="off" name="payment_date" value="{$oneFund.payment_date}" class="form-control required" placeholder="dd/mm/yyyy">
				</div>
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label for="email" class="form-label mb-1">Ngày hạch toán</label>
					<input type="date" autocomplete="off" name="account_date" value="{$oneFund.account_date}" class="form-control required" placeholder="dd/mm/yyyy">
				</div>
				<div class="col-6 col-md-4">
					<label for="phone" class="form-label mb-1">Loại khoản {if $gr eq 'THUCTHU'}thu{else}chi{/if}</label>
					<select name="type_id" class="form-control form-select required">
						<option value="0">Chọn khoản {if $gr eq 'THUCTHU'}thu{else}chi{/if}</option>
						{$clsProperty->getListOption($gr,$oneFund.type_id)}
					</select>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label for="amout" class="form-label mb-1">Số tiền</label>
					<div class="input-group input-group-merge">
						<input type="text" autocomplete="off" value="{if $action eq '_edit'}{$oneFund.amount}{/if}" name="amount" class="form-control numberonly price-In required" placeholder="Số tiền" onClick="this.select();">
						<span class="input-group-text">.đ</span>
					</div>
				</div>
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Tài khoản</label>
					<div class="clearfix"></div>
					<select name="bank_account_id" data-width="100%" class="form-control iso-select2 required">
						{$clsProperty->getSelectByProperty('BANK_ACCOUNT',$oneFund.bank_account_id)}
					</select>
				</div>
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Phương thức {if $gr eq 'THUCTHU'}nộp{else}nhận{/if}</label>
					<div class="clearfix"></div>
					<select name="payment_method" data-width="100%" class="form-control iso-select2 required">
						{$clsProperty->getSelectByProperty('_PAYMENT_METHOD',$oneFund.payment_method)}
					</select>
				</div>
			</div>
			{if $gr eq 'THUCCHI' && 1==2}
			<div class="bg-lighter form-row p-3 mb-2">
				<label for="email" class="col-form-label col-4 col-md-3">Chọn giao dịch</label>
				<div class="col-8 col-md-9">
					<input type="hidden" name="billing_id" value="{$oneFund.billing_id}" />
					<select uid="{$uid}" prompt="Giao dịch" style="width:100%" class="cboProduct" id="cboProduct_{$uid}" 
						data-options="panelWidth:800,
						url:'{$PCMS_URL}/index.php?mod=transaction&act=search_billing&tp=_all',
						mode:'remote',
						method:'post',
						height:'36px',
						multiple:false, 
						value:'',
						columns: [[
							{ldelim}field:'billing_code',title:'Mã GD',width:70,align:'left'{rdelim},
							{ldelim}field:'deposit_date',title:'Ngày cọc',width:85,align:'left'{rdelim},
							{ldelim}field:'staff_name',title:'Nhân viên',width:120,align:'left'{rdelim},
							{ldelim}field:'stock_code',title:'Mã căn',width:80{rdelim},
							{ldelim}field:'project_name',title:'Dự án',width:120,align:'left'{rdelim},
							{ldelim}field:'totalgrand',title:'Tồng tiền',width:80,align:'left'{rdelim}
						]],
						idField:'billing_code',
						textField:'billing_code',
						fitColumns:true,
						showFooter:true,
						pagination:true,
						pageSize: 20">
					</select>
				</div>
			</div>
			{/if}
			<div class="form-row mb-2">
				<div class="col-6 col-md-4">
					<label for="email" class="form-label mb-1">Người {if $gr eq 'THUCTHU'}nộp{else}nhận{/if}</label>
					{assign var = _uid value = $clsISO->getUniqid()}
					<input type="text" uid="{$_uid}" autocomplete="off" name="person" id="person_{$_uid}" class="form-control autocomplete" value="{if $action eq '_edit'}{$oneFund.person}{/if}" data-source="{$PCMS_URL}/index.php?mod={$mod}&act=list_person" placeholder="Người {if $gr eq 'THUCTHU'}nộp{else}nhận{/if}">
				</div>
				<div class="col-6 col-md-8">
					<label for="email" class="form-label mb-1">Địa chỉ</label>
					<input type="text" autocomplete="off" id="person_address_{$_uid}" name="person_address" value="{if $action eq '_edit'}{$oneFund.person_address}{/if}" class="form-control" placeholder="Địa chỉ">
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-12 col-md-12">
					<label class="form-label mb-1">Lý do</label>
					<textarea name="content" class="form-control autosize" placeholder="Lý do" cols="255" rows="2">{if $action eq '_edit'}{$oneFund.content}{/if}</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
					{if !empty($list_attachments)}
						{foreach name=i from = $list_attachments item = _oFile}
						<div class="MultiFile-label">
							<a class="MultiFile-remove" href="javascript:void(0)" onClick="$Core.upload.delete(this, event)" src="{$_oFile}">x</a> 
							<span class="MultiFile-label" title="{$_oFile}">
								<a href="{$_oFile}" data-fancybox="{$fund_id}" data-type="iframe" class="MultiFile-title">{$clsISO->formatFileName($_oFile,15)}</a>
							</span>
						</div>
						{/foreach}
					{/if}
				</div>
				<div class="clearfix"></div>
				<input name="attachments[]" type="file" multiple="multiple" class="maxsize-10240" id="attachments_{$uid}" />
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			{if $gr eq 'THUCCHI'}
			<div class="w-100 d-flex flex-wrap align-items-center justify-content-between">
				<div class="checkbox-block d-flex align-items-center gap-1 mb-2 mb-lg-0">
					<label class="switch">
						<input type="checkbox" name="is_person_tax"{if $oneFund.is_person_tax eq '1'} checked{/if} value="1">
						<span class="slider round"></span>
					</label>
					<span class="text-muted mr-1">Không bao gồm Thuế thu nhập cá nhân</span>
				</div>
				<div class="buttons xs:w-100 d-flex gap-2">
					<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
					<button type="button" uid="{$uid}" gr="{$gr}" fund_id="{$fund_id}" class="btn flex-fill btn-primary" 
					onClick="$Core.global.fund.save(this, event)">Cập nhật</button>
				</div>
			</div>
			{else}
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" uid="{$uid}" gr="{$gr}" fund_id="{$fund_id}" class="btn btn-primary" 
			onClick="$Core.global.fund.save(this, event)" >Cập nhật</button>
			{/if}
		</div>
	</form>
</div>
