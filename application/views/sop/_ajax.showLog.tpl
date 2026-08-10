<div class="modal-dialog right modal-dialog-centered modal-ipad" style="width:500px;max-width:100%">
	<div class="modal-content">
		<div class="modal-header position-relative d-block">
			<div class="d-flex justify-content-between">
				<h5 class="modal-title fs-4 text-main mr-2 text-upper">Thống kê lượt click</h5>
				<a type="button" class="btn btn-close" onClick="$Core.sop.close_pop(this, event)" data-bs-dismiss="modal"></a>
			</div>
		</div>
		<div class="modal-body">
			<div class="overflow-x-auto">
				<table class="table table-iloocal table-computer table-bordered">
					<thead>
						<tr>
							<th width="10%" class="align-center bg-lighter">STT</th>
							<th class="align-center bg-lighter">Người xem</th>
							<th class="align-center text-left bg-lighter">Loại</th>
							<th class="align-center bg-lighter">Thời gian</th>
						</tr>
					</thead>
					<tbody>	
						{foreach from=$logs name=i item=log key=k}
							<tr class="awe__sop-item awe__sop-item-58 text-nowrap">
								<td class="text-left">{$smarty.foreach.i.iteration}</td>
								<td class="text-left">{$log.user_name}</td>
								<td class="text-left">{$log.type}</td>
								<td class="text-left">{$item.reg_date|date_format:"%d/%m/%Y %H:%m"}</td>
							</tr>
						{/foreach}

					</tbody>
				</table>
			</div>
			
		</div>
	</form>
</div>