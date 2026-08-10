<div class="dashboard-panel-item dashboard-panel-item--full mb-2 h-100">
	<div class="panel border-0 mb-0 panel-default h-100">
		<div class="panel-heading d-flex flex-column w-100">
			<h3 class="panel-title">Danh sách nhân viên</h3>
			<small class="text-muted">Tổng <strong class="text-main fw-bold">{$list_staffs|@count}</strong> nhân viên</small>							
		</div>
		<div class="panel-body scroll-y-auto table-container no-shadow overflow-x-auto" style="max-height:308px; min-height:170px">
			<table class="table text-nowrap" border="0" cellpadding="0" cellspacing="0" width="100%">
				<thead><tr>
					<th class="align-center">Nhân viên</th>
					<th class="align-center">Khách hàng</th>
					<th class="align-center text-center">Giao dịch</th>
					<th class="align-center text-right">FollowUps</th>
				</tr></thead>
				<tbody class="table-border-bottom-0">					
					{if !empty($list_staffs)}
						{foreach from=$list_staffs item=_oItem}
						<tr>
							<td class="align-center">
								{$clsProfile->getFullName($_oItem.profile_id,$_oItem)} 
								<a href="/crm/dashboard/{$core->encryptId($_oItem.profile_id)}.html">
									<i class='bx bx-link-external fs-12'></i></a>
							</td>
							<td class="align-center text-center fw-bold text-main">{$_oItem.total_customers} khách</td>
							<td class="align-center text-center">{$_oItem.total_billings} GD</td>
							<td class="align-center text-right">
								<div class="btn-group">
									<a title="Gọi điện" class="btn btn-xs btn-outline-none px-1 text-success py-1 text-nowrap">
										<i class='bx bx-phone-call fs-14'></i> {$_oItem.total_call}</a>
									<a title="Cuộc gặp" class="btn btn-xs btn-outline-none px-1 text-warning py-1 text-nowrap">
										<i class="bx bx-task fs-14"></i> {$_oItem.total_appointment}</a>
									<a title="Nhắn zalo" class="btn btn-xs btn-outline-none px-1 py-1 text-main text-nowrap">
										<i class="bx bx-chat fs-14"></i>{$_oItem.total_zalo}</a>
								</div>
							</td>
						</tr>
						{/foreach}
					{else}
						<tr>
							<td class="text-center" colspan="4">
								<img src="{$URL_IMAGES}/listing-empty.svg" width="80px" />
								<p>Danh sách nhân viên trống</p>
							</td>
						</tr>
					{/if}
				</tbody>
			</table>
		</div>
	</div>
</div>