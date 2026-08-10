<div class="modal-dialog modal-dialog-centered modal-md">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">.
			<h5 class="modal-title" id="modalTopTitle">Tri ân </h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller form-row">
			{if !empty($lstDepartment)}
				<div class="col-md-12 col-sm-12 col-12 flex-fill">
					<label  class="form-label">Phòng ban</label>
					<table class="table">
						<thead>
							<tr>
								<th class="text-center" scope="col" width="50px">STT</th>
									<th scope="col">Tên phòng</th>
								<th class="text-right" scope="col" width="100px">Điểm/1 người</th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$lstDepartment item=oneDepartment key=key name=i}
								<tr>
									<td class="text-center">{$smarty.foreach.i.iteration}</td>
									<td>{$oneDepartment.title} ({$oneDepartment.total_profile})</td>
									<td class="text-right">
										<input type="text" class="form-control text-center price-In px-1" name="scoreDep[{$oneDepartment.property_id}]" min="1" value="1" style="width:50px;float: right" onChange="$Core.gratitude.checkMin(this)">
									</td>
								</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
			{/if}
			{if !empty($lstStaff)}
				<div class="col-md-12 col-sm-12 col-12 flex-fill">
					<label  class="form-label">Nhân viên</label>
					<table class="table">
						<thead>
							<tr>
								<th class="text-center" scope="col" width="50px">STT</th>
								<th scope="col">Họ tên</th>
								<th class="text-right" scope="col" width="100px">Điểm</th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$lstStaff item=oneProfile key=key name=i}
								<tr>
									<td class="text-center">{$smarty.foreach.i.iteration}</td>
									<td>{$oneProfile.full_name}</td>
									<td class="text-right">
										<input type="text" class="form-control text-center price-In px-1" name="scoreEmp[{$oneProfile.profile_id}]" min="1" value="1" style="width:50px;float: right" onChange="$Core.gratitude.checkMin(this)">
									</td>
								</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
			{/if}
		</div>
		<div class="modal-footer">
			<input type="hidden" name="news_id" value="{$news_id}">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" onClick="$Core.gratitude.save_gratitude(this, event)" action="_save" news_id="{$news_id}"  data-is_singer="0" data-type="staff" class="btn btn-primary">Tri ân</button>
		</div>
	</form>
</div>