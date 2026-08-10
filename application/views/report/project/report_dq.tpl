<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-lg-9 col-xxxl-7 mx-auto">
			<div class="card">
				<div class="card-header"><h4 class="fw-bold mb-1"><span>Danh sách quỹ độc quyền dự án</span></h4></div>
				<div class="card-body">
					<div id="list_stock_DQ" class="table-container no-shadow overflow-x-auto mb-3">
						<table class="table table-striped dragable table-bordered installed" width="100%" cellspacing="0" cellpadding="0">
							<thead><tr>
								{if $deviceType ne 'phone'}
								<th width="30px" class="align-center h-px-40 align-center nosort bg-lighter">STT</th>
								{/if}
								<th class="align-center h-px-40 text-left bg-lighter">Quỹ độc quyền</th>
								<th class="align-center h-px-40 text-center bg-lighter">Số căn</th>
								<th class="align-center h-px-40 text-center bg-lighter">Tổng giá VAT</th>
							</tr></thead>
							<tbody>
								{foreach from=$arr_stock item=_oItem key=key name=i}
									<tr>
										{if $deviceType ne 'phone'}
											<td class="text-center">{$smarty.foreach.i.iteration}</td>
										{/if}
										<td><a href="javaxcript:void(0)" onClick="$Core.report.view_stock_dq(this,event)" block_id="{$_oItem.block_id}" class="text-link">{$_oItem.title}</a></td>
										<td  class="text-center">{$_oItem.total}</td>
										<td  class="text-center">{if $deviceType eq 'phone'}{$clsISO->shortNumberV2($_oItem.total_price_vat,2)}{else}{$clsISO->priceFormat($_oItem.total_price_vat)} VNĐ{/if}</td>
									</tr>
								{/foreach}
								
							</tbody>
							<tfooter>
								<tr class="nohover">
									<td class="h-px-40 text-center text-upper fw-bold text-main" colspan="{if $deviceType ne 'phone'}2{else}1{/if}">Tổng cộng</td>
									<td class="h-px-40 text-center text-upper fw-bold text-main">{$total}</td>
									<td class="h-px-40 text-center text-upper fw-bold text-main">{if $deviceType eq 'phone'}{$clsISO->shortNumberV2($total_price,2)}{else}{$clsISO->priceFormat($total_price)} VNĐ{/if}</td>
								</tr>
							</tfooter>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>