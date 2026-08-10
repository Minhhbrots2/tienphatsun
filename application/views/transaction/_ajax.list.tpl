<div class="freeze-table overflow-x-auto dragscroll text-nowrap">
	<table class="table table-striped mb-4" width="100%">
		<thead><tr>
			{if $deviceType ne 'phone'}
			<th class="align-center" width="3%">STT</th>
			{/if}
			<th class="align-center" width="5">Mã GD</th>
			<th class="align-center">Tháng</th>
			<th class="align-center">Dự án</th>
			<th class="align-center">Nhân viên</th>
			<th class="align-center">Loại hình</th>
			<th class="align-center">Tiến độ xử lý</th>
			<th class="align-center">Tình trạng</th>
			<th class="align-center" width="12%">Ngày gửi</th>
			<th width="45px"></th>
		</tr></thead>
		{if empty($list_transactions)}
			{foreach from=$list_transactions item= _oTrans name=i}
			<tr>
				{if $deviceType ne 'phone'}
				<td class="text-center">{$smarty.foreach.i.iteration}</td>
				{/if}
				<td class="text-left"><a class="mb-1 d-block" href="javascript:void(0)" onClick="$Core.transaction.view(this, event)" transaction_id="{$_oTrans.transaction_id}">{$_oTrans.code}</a></td>
				<td class="text-left">{$_oTrans.send_date|date_format:'%m/%Y'}</td>
				<td class="text-left">{$_oTrans.project_name}</td>
				<td class="text-left">{$clsProfile->getIndentityV3($_oTrans.staff_id, true)}</td>
				<td class="text-left">{$_oTrans.transaction_name}</td>
				<td class="text-left">
					<div class="pipeline-small flat">
						<a title="" href="#" class="active noselect tipped-top">&nbsp;</a>
						<a title="" href="#" class="noselect tipped-top">&nbsp;</a>
						<a title="" href="#" class="noselect tipped-top">&nbsp;</a>
						<a title="" href="#" class="noselect tipped-top">&nbsp;</a>
						<a title="" href="#" class="noselect tipped-top">&nbsp;</a>
					</div>
				</td>
				<td class="text-left">{$clsProperty->getTitle($_oTrans.status_id)}</td>
				<td class="text-left">{$clsISO->convertTimeToText($_oTrans.reg_date, true)}</td>
				<td class="text-center">
					<div class="dropdown">
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
							data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" onClick="$Core.transaction.view(this,event)" transaction_id="{$_oTrans.transaction_id}" 
							href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>
							<a class="dropdown-item" onClick="$Core.transaction.print(this,event)" transaction_id="{$_oTrans.transaction_id}" 
							href="javascript:void(0);"><i class="bx bx-printer me-1"></i> Bản in</a>
							<a class="dropdown-item" onClick="$Core.transaction.open(this,event)" transaction_id="{$_oTrans.transaction_id}" 
							href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
						</div>
					</div>
				</td>
			</tr>
			{/foreach}
		{else}
			<tr>
				<td class="text-center" colspan="10">
					<img src="{$URL_IMAGES}/table-no-data.png" class="w-px-100 mb-2" />
					<p class="text-muted m-0">Chưa có xác nhận hoa hồng nào</p>
				</td>
			</tr>
		{/if}
	</table>
</div>