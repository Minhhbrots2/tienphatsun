<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header pb-3"> 
			<h3 class="modal-title">Danh sách căn</h3>
		</div>
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="overflow-auto table-container pt-0" style="max-height: calc(100vh - 200px)">
					<table class="table dragable table-grid table-bordered" width="100%" cellpadding="0" cellspacing="0" >
						<thead class="position-sticky top-0 zindex-1"><tr>
							<th class="algin-center bg-lighter text-center h-px-35">Mã căn</th>
							<th class="algin-center bg-lighter text-center h-px-35">DT_TT</th>
							<th class="algin-center bg-lighter text-center h-px-35">Hướng</th>
							<th class="algin-center bg-lighter text-center h-px-35">Giá VAT</th>
							<th class="algin-center bg-lighter text-center h-px-35">TTS</th>
							<th class="algin-center bg-lighter text-center h-px-35">TTTĐ</th>
							<th class="algin-center bg-lighter text-center h-px-35">Vay</th>
							<th class="algin-center bg-lighter text-center h-px-35">Tình trạng</th>
						</tr></thead>
						{if !empty($list_stocks)}
							{foreach name=i from=$list_stocks item = _oItem}
							<tr>
								<td class="algin-center text-center text-nowrap">
									<a href="javascript:void(0);" data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="350" >{$_oItem.ms_code}</a>
								</td>
								<td class="algin-center text-center text-nowrap">
									{$_oItem.DT_TT}m<sup>2</sup>
								</td>
								<td class="algin-center text-center text-nowrap">{$_oItem.home_direction_name}</td>
								<td class="algin-center text-center text-nowrap">{if !empty($_oItem.total_price_vat)}{$clsISO->shortNumber($_oItem.total_price_vat)}{else}--{/if}</td>
								<td class="algin-center text-center text-nowrap text-nowrap">{if !empty($_oItem.total_price_early)}{$clsISO->shortNumber($_oItem.total_price_early)}{else}--{/if}</td>
								<td class="algin-center text-center text-nowrap">{if !empty($_oItem.total_price_progress)}{$clsISO->shortNumber($_oItem.total_price_progress)}{else}--{/if}</td>
								<td class="algin-center text-center text-nowrap">{if !empty($_oItem.total_price_bank)}{$clsISO->shortNumber($_oItem.total_price_bank)}{else}--{/if}</td>
								<td class="algin-center text-center text-nowrap">{$_oItem.status_name}</td>
							</tr>
							{/foreach}
						{else}
							<tr>
								<td colspan="8" class="text-center">
									<p>Chưa có lịch sử cập nhật nào!</p>
								</td>
							</tr>
						{/if}
					</table>				
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
			</div>
		</form>
	</div>
</div>