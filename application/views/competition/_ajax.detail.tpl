<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title mb-0">
				Chi tiết điểm — {$staff_name|escape}
				{if !empty($program)}<br /><small class="text-muted" style="font-size:11px">{$program.name|escape}</small>{/if}
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<table class="table table-sm align-middle">
				<thead>
					<tr>
						<th>Căn</th>
						<th>Loại</th>
						<th class="text-end">Giá trị</th>
						<th class="text-center">Ngày</th>
						<th class="text-end">Điểm</th>
					</tr>
				</thead>
				<tbody>
					{if !empty($breakdown)}
						{foreach from=$breakdown item=d}
						<tr>
							<td><strong>{$d.stock_code|escape}</strong></td>
							<td><span class="badge {if $d.type eq 'Độc quyền'}bg-label-danger{else}bg-label-warning{/if}">{$d.type}</span></td>
							<td class="text-end">{($d.value/1000000000)|string_format:"%.2f"} tỷ</td>
							<td class="text-center">{$d.date|date_format:"%d/%m/%Y"}</td>
							<td class="text-end fw-bold">{$d.score}</td>
						</tr>
						{/foreach}
					{else}
						<tr><td colspan="5" class="text-center text-muted py-3">Chưa có giao dịch tạo điểm trong kỳ.</td></tr>
					{/if}
				</tbody>
				<tfoot>
					<tr class="table-active">
						<td colspan="4" class="text-end fw-bold">Tổng điểm</td>
						<td class="text-end fw-bold">{$total_score}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
