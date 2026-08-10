<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Báo cáo TOP 20 căn hộ<br />
					<span class="text-muted fs-12">được tra cứu nhiều nhất</span>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="btn-group d-flex mb-2" role="group" aria-label="Sắp xếp">
					<input onChange="$Core.log.do_reload(this, event)" type="radio" class="btn-check" name="search_type" 
						id="search_type_0_{$uid}" value="all" uid="{$uid}" autocomplete="off" checked>
					<label class="btn btn-outline-default" for="search_type_0_{$uid}">Tất cả</label>
					<input onChange="$Core.log.do_reload(this, event)" type="radio" class="btn-check" name="search_type" 
						id="search_type_1_{$uid}" value="sold" uid="{$uid}" autocomplete="off">
					<label class="btn btn-outline-default" for="search_type_1_{$uid}">Đã bán</label>
					<input onChange="$Core.log.do_reload(this, event)" type="radio" class="btn-check" name="search_type" 
						id="search_type_2_{$uid}" value="not_sold" uid="{$uid}" autocomplete="off">
					<label class="btn btn-outline-default" for="search_type_2_{$uid}">Chưa bán</label>
				</div>
				<table width="100%" class="table" cellpadding="0" cellspacing="0">
					<thead><tr>
						<th width="10%" class="align-center text-center">No.</th>
						<th width="30%" class="align-center">Mã căn</th>
						<th class="align-center">Lượt check</th>
					</tr></thead>
					<tbody id="tbody_{$uid}">
						<tr>
							<td colspan="3">Loading...</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>