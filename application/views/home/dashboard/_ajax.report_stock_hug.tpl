<div class="modal-dialog modal-ipad">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Bảng thống kê quỹ ôm</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="table-wrapper">
				<table class="table" width="100%">
					<thead><tr>
						<th class="align-center"></th>
						<th class="align-center text-center">Tổng</th>
						<th class="align-center text-center">Tổng tiền</th>
					</tr></thead>
					{foreach from = $list_patterns item = _oI}
					<tr>
						<td class="border-end">{$_oI.title}</td>
						<td class="border-end text-center" width="20%">{$_oI.total_stock}</td>
						<td class="text-center" width="20%">{$_oI.total_price}</td>
					</tr>
					{/foreach}
				</table>
			</div>
		</div>
	</form>
</div>
