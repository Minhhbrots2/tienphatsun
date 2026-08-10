<div class="modal-dialog modal-ipad-xl">
	<div class="modal-content">
		<div class="modal-header relative">
			<h5 class="modal-title">Xác nhận hoa hồng <br />
				<span class="text-danger fs-13">
					{$clsISO->makeIcon('bx-user-plus', 'Người tạo: ')}
					{$clsProfile->getFullName($oneTransaction.staff_id)}
				</span></h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-3">
				<div class="form-text mt-0">Phê duyệt</div>
				<div class="pipeline mt-1 flat">
					{foreach from=$list_work_progress key = _tp  item = _oProgress}
					<a class="relative text-center noselect tipped-top">
						{$_oProgress.name}
						<div class="position-absolute js__block-action-list">
							<div class="d-flex gap-1 align-items-center">
								<button type="button" tp="{$_tp}" name="{$_oProgress.name}" onclick="$Core.transaction.x_click(this, event)" title="Từ chối" class="btn btn-xs btn-icon btn-outline-default"><i class="bx bx-x"></i></button>
								<button type="button" tp="{$_tp}" name="{$_oProgress.name}" title="Chấp nhận" onclick="$Core.transaction._click(this, event)" class="btn btn-xs btn-icon btn-outline-default"><i class="bx bx-check"></i></button>
							</div>
						</div>
					</a>
					{/foreach}
				</div>
			</div>
			<div class="widget-block">
				<div class="widget-header">Giao dịch</div>
				<div class="widget-content">
					<div class="table-freeze mb-2 overflow-x-auto text-nowrap">
						<table border="0" cellspacing="0" cellspacing="0" style="width:calc(100% + 300px)" class="table mb-0 table-bordered">
							<thead><tr>
								<th class="text-center bg-lighter" rowspan="3" width="40px">STT</th>
								<th class="text-center bg-lighter" rowspan="3">Mã căn</th>
								<th class="text-center bg-lighter" rowspan="3">Dự án</th>
								<th width="120px" class="text-center bg-lighter" rowspan="3">Ngày kí<br />HĐMB</th>
								<th width="120px" class="text-center bg-lighter" rowspan="3">Gía trị<br />HĐMB</th>
								<th class="text-center bg-lightest" colspan="4">Thông tin hoa hồng</th>
								<th width="150px" class="text-center bg-lighter" rowspan="3">Ghi chú</th>
							</tr>
							<tr>
								<th class="text-center bg-lighter" rowspan="2">Tỷ lệ<br />HH(%)</th>
								<th class="text-center bg-lighter" colspan="2">Thưởng</th>
								<th class="text-center bg-lighter border-end" rowspan="2">Hỗ trợ<br />(Nếu có)</th>
							</tr>
							<tr>
								<th class="text-center bg-lighter">Đại lý</th>
								<th class="text-center bg-lighter">CĐT</th>
							</tr></thead>
							{foreach name=i from=$billing_store item=_oBilling}
							<tr>
								<td class="text-center align-center">{$smarty.foreach.i.iteration}</td>
								<td class="text-center align-center">{$_oBilling.stock_code}</td>
								<td class="text-center align-center">VHOP</td>
								<td class="text-center align-center">{$_oBilling.contract_date}</td>
								<td class="text-center align-center">{$_oBilling.total_price}</td>
								<td class="text-center align-center">{$_oBilling.commission}%</td>
								<td class="text-center align-center">
									{if !empty($_oBilling.price_ms)}
										{$_oBilling.price_ms}
									{else}
										0 {$clsISO->getRate()}
									{/if}
								</td>
								<td class="text-center align-center">
									{if !empty($_oBilling.price_ns)}
										{$_oBilling.price_ns}
									{else}
										0 {$clsISO->getRate()}
									{/if}
								</td>
								<td class="text-center align-center">
									{if !empty($_oBilling.price_sp)}
										{$_oBilling.price_sp}
									{else}
										0 {$clsISO->getRate()}
									{/if}
								</td>
								<td class="text-center align-center">{$_oBilling.notes}</td>
							</tr>
							{/foreach}
						</table>
					</div>
				</div>
			</div>
			{if !empty($list_logs)}
			<div class="widget-block">
				<div class="widget-header">Logs</div>
				<div class="widget-content">
					<ul class="logs">
						{foreach from=$list_logs item = _oLog}
						<li>{$clsISO->convertTimeToText($_oLog.reg_date,true)}: {$_oLog.content}</li>
						{/foreach}
					<ul>
				</div>
			</div>
			{/if}
			<div class="widget-block">
				<div class="widget-header">Ghi chú</div>
				<div class="widget-content">
					<form class="frmIssue mb-2" name="" action="">
						<textarea class="form-control" name="content" rows="2" for_id="{$transaction_id}" clsTable="Transaction" note_id="" onkeydown="$Core.helper.enter_notes(this,event)" placeholder="Nhập ghi chú"></textarea>
						<div class="form-text">Nhấn Ctrl+Enter để xuống dòng. Nhấn Enter để lưu lại</div>
					</form>
					<div class="mb-2">
						<span class="badge bg-grayter text-body fs-11">Danh sách ghi chú</span>
					</div>
					<div class="holder_notes_{$transaction_id}">
						<div class="loader p-5 text-center">Loading...</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>