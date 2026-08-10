<div class="modal-dialog modal-dialog-centered modal-md">
	<form class="modal-content">
		<div class="modal-header position-relative">
			<div class="d-flex w-100 justify-content-between align-items-center">
				<h5 class="modal-title text-upper">
					Lịch sử giá căn {$oneStock.ms_code}
				</h5>
			</div>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="holder_Lpoint_{$uid}">
				<table class="table">
					<thead><tr>
						<th class="align-center text-center">STT</th>
						<th class="align-center" width="150">Thời gian</th>
						<th class="align-center">Nội dung</th>
					</tr></thead>
					{if !empty($list_logs)}
						{foreach from=$list_logs item=_oLog name=i}
							<tr>
								<td class="text-center">{$smarty.foreach.i.iteration}</td>
								<td>{$_oLog.reg_date}</td>
								<td>Từ <strong>{$clsISO->formatPrice($_oLog.from_value)}đ</strong> thành <strong>{$clsISO->formatPrice($_oLog.to_value)}đ</strong></td>
							</tr>
						{/foreach}	
					{else}
						<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}5{else}3{/if}">Dữ liệu trống</td></tr>
					{/if}
				</table>
			</div>
		</div>
	</form>
</div>