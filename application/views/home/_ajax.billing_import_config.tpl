<div class="modal-dialog modal-lg">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title mb-0"><i class="bx bx-cog me-1"></i> Map cột Google Sheet → trường giao dịch</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body" style="max-height:65vh;overflow:auto">
			{if empty($header)}
				<div class="alert alert-warning py-2 mb-0">
					Không đọc được dòng tiêu đề. Kiểm tra Spreadsheet ID, tên sheet và quyền chia sẻ.
				</div>
			{else}
			<p class="text-muted mb-2">
				<small>Chọn trường đích cho từng cột. Bỏ trống nếu không dùng. Mỗi trường (*) là bắt buộc và chỉ gán cho 1 cột.</small>
			</p>
			<table class="table table-sm table-bordered align-middle mb-0">
				<thead>
					<tr class="bg-lighter">
						<th width="55" class="text-center">Cột</th>
						<th>Tiêu đề</th>
						<th>Dữ liệu mẫu</th>
						<th width="230">Trường đích</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$header item=colTitle key=colIdx}
					<tr>
						<td class="text-center fw-semibold">{$col_letters[$colIdx]}</td>
						<td>{$colTitle|escape}</td>
						<td class="text-muted"><small>
							{foreach from=$samples item=srow name=s}{if isset($srow[$colIdx]) && $srow[$colIdx] ne ''}{$srow[$colIdx]|escape}{if !$smarty.foreach.s.last} · {/if}{/if}{/foreach}
						</small></td>
						<td>
							<select name="columns[{$colIdx}]" class="form-select form-select-sm iso-select2" data-width="100%">
								<option value="">— Bỏ qua —</option>
								{foreach from=$import_fields item=fld key=fkey}
								<option value="{$fkey}"{if isset($saved_config[$colIdx]) && $saved_config[$colIdx] eq $fkey} selected{/if}>{$fld.label}{if $fld.required} *{/if}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
			{/if}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary" uid="{$uid}" onClick="$Core.billingImport.saveConfig(this,event)">
				<i class="bx bx-save me-1"></i> Lưu cấu hình
			</button>
		</div>
	</form>
</div>
