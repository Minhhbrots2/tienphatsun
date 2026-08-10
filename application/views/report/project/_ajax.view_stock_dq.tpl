<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<h3 class="modal-title"><strong>{$title_page}</strong></h3>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="table-container no-shadow overflow-y-auto" style="max-height: calc(100vh - 200px)">
				<table class="table table-striped dragable table-bordered installed mb-0" width="100%"  cellspacing="0" cellpadding="0">
					<thead class="position-sticky top-0 bg-lighter zindex-3"><tr>
						<th width="5%">No.</th>
						<th width="">Mã căn</th>
						<th>Tổng giá VAT</th>
					</tr></thead>
					<tbody>
						{if !empty($lstStock)}
							{foreach name=i from=$lstStock item = _oItem}
							<tr>
								<td class="text-center">{$smarty.foreach.i.iteration}</td>
								<td class="text-left"><a href="javascript:void(0);" data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="350">{$_oItem.ms_code}</a></td>
								<td class="text-left">{if $deviceType eq 'phone'}{$clsISO->shortNumberV2($_oItem.total_price_vat,2)}{else}{$clsISO->priceFormat($_oItem.total_price_vat)} VNĐ{/if}</td>
							</tr>
							{/foreach}
						{else}
							<tr>
								<td colspan="3" class="text-center">
									<p>Danh sách trống!</p>
								</td>
							</tr>
						{/if}
					</tbody>
					<tfoot class="position-sticky bottom-0 bg-lighter zindex-2">
						<tr class="nohover">
							<td class="text-center text-upper fw-bold text-main" colspan="2" >Tổng</td>
							<td class="text-left text-upper fw-bold text-main">{if $deviceType eq 'phone'}{$clsISO->shortNumberV2($total_price,2)}{else}{$clsISO->priceFormat($total_price)} VNĐ{/if}</td>
						</tr>
					</tfoot>
				</table>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
		</div>
	</div>
</div>