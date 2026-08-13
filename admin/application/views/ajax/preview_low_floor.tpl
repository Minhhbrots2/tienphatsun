<div class="modal-dialog modal-xl">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a>
			<h3 class="modal-title"><strong>Xem trước bảng hàng thấp tầng</strong></h3>
		</div>
		<div class="modal-body">
			<div class="form-row align-items-center mb-2">
				<div class="col-md-7">
					<span class="badge badge-info">Tổng: {$preview_total}</span>
					<span class="badge badge-success">Tạo mới: {$total_insert}</span>
					<span class="badge badge-warning">Cập nhật: {$total_update}</span>
					{if $total_error > 0}
						<span class="badge badge-important">Lỗi: {$total_error}</span>
					{/if}
				</div>
				<div class="col-md-5">
					<input type="text" class="form-control js__filter_preview" placeholder="Lọc theo mã căn, phân khu, loại căn..." onkeyup="$Core.project.filter_preview_low_floor(this, event)">
				</div>
			</div>
			{if $total_error > 0}
				<div class="alert alert-danger">
					<strong>{$total_error}</strong> căn có dữ liệu chưa khớp (các dòng nền hồng) nên chưa tạo được bảng hàng.
					Vui lòng sửa lại sheet hoặc khai báo bổ sung phân khu / tòa-dãy / loại căn / hướng rồi xem trước lại.
					<label class="d-block mt-2 mb-0">
						<input type="checkbox" class="js__filter_error" onchange="$Core.project.filter_preview_low_floor(this, event)"> Chỉ hiện các dòng lỗi
					</label>
				</div>
			{/if}
			{if $preview_total <= 0}
				<div class="alert alert-danger mb-0">Không dựng được căn nào từ sheet. Vui lòng kiểm tra lại cấu trúc mã căn hộ và dữ liệu trên sheet.</div>
			{else}
				<div class="preview-lowfloor-scroll">
					<table class="table table-bordered mb-0 js__preview_low_floor">
						<thead>
							<tr>
								<th width="45">#</th>
								<th width="90">Thao tác</th>
								<th>Mã căn</th>
								<th>Số căn</th>
								<th>Phân khu</th>
								<th>Tòa/Dãy</th>
								<th>Loại căn</th>
								<th>Hướng</th>
								<th width="80">DT TT</th>
								<th width="80">DT Tim</th>
								<th>Cảnh báo</th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$preview_rows item=_oRow name=preview}
							<tr{if $_oRow.errors} class="danger js__row_error"{/if}>
								<td class="text-center">{$smarty.foreach.preview.iteration}</td>
								<td class="text-center">
									{if $_oRow.act == 'insert'}
										<span class="badge badge-success">Tạo mới</span>
									{else}
										<span class="badge badge-warning">Cập nhật</span>
									{/if}
								</td>
								<td class="text-nowrap"><strong>{$_oRow.ms_code|escape}</strong></td>
								<td>{$_oRow.code|escape}</td>
								<td>{$_oRow.block|escape}</td>
								<td>{$_oRow.building|escape}</td>
								<td>{$_oRow.type|escape}</td>
								<td>{$_oRow.direction|escape}</td>
								<td class="text-right">{$_oRow.DT_TT|escape}</td>
								<td class="text-right">{$_oRow.DT_Tim|escape}</td>
								<td>
									{foreach from=$_oRow.errors item=_oError}
										<span class="badge badge-important">{$_oError|escape}</span>
									{/foreach}
									{foreach from=$_oRow.notes item=_oNote}
										<span class="badge badge-info">{$_oNote|escape}</span>
									{/foreach}
								</td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
				<div class="d-flex justify-content-between align-items-center mt-2">
					<small class="text-muted js__preview_range"></small>
					<ul class="pagination mb-0 js__preview_pager"></ul>
				</div>
			{/if}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
			{if $preview_total > 0}
				{if $total_error > 0}
					<button type="button" class="btn btn-primary" disabled="disabled" title="Còn {$total_error} căn chưa khớp dữ liệu, sửa xong rồi xem trước lại">
						<i class="fa fa-check"></i> Xác nhận tạo bảng hàng
					</button>
				{else}
					<button type="button" class="btn btn-primary" onclick="$Core.project.create_low_floor(this, event)">
						<i class="fa fa-check"></i> Xác nhận tạo bảng hàng
					</button>
				{/if}
			{/if}
		</div>
	</div>
</div>
