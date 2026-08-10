<div class="modal-dialog modal-dialog-scrollable modal-md">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">.
			<h5 class="modal-title" id="modalTopTitle">Danh sách tri ân </h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			{if !empty($lstDepartment)}
				<div class="form-group mb-2">
					<label  class="form-label">Phòng ban</label>
					<table class="table">
						<thead>
							<tr>
								<th width="5%" class="text-center">
									<div class="checkbox checkbox_news">
										<input type="checkbox" id="check_all_dep" class="check_all styled" value="1">
										<label></label>
									</div>
								</th>
								<th scope="col">Tên phòng</th>
								<th class="text-right" scope="col" width="100px"></th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$lstDepartment item=department name=i}
								<tr>
									<td class="text-center">
										<div class="checkbox checkbox_news">
											<input type="checkbox" name="key_dep[]" class="chkitem styled" value="{$department.property_id}">
											<label></label>
										</div>
									</td>
									<td>{$department.title}</td>
									<td class="text-right">
										<button class="btn btn-default" type="button" onClick="$Core.gratitude.save_gratitude(this,event)" data-is_singer="1" data-type="dep" data-id="{$department.property_id}" action="_open">Tri ân</button>
									</td>
								</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
			{/if}
			{if !empty($lstStaff)}
				<div class="form-group mb-2">
					<label  class="form-label">Nhân viên</label>
					<table class="table">
						<thead>
							<tr>
								<th width="5%" class="text-center">
									<div class="checkbox checkbox_news">
										<input type="checkbox" id="check_all_staff" class="check_all styled" value="1">
										<label></label>
									</div>
								</th>
								<th scope="col">Họ tên</th>											
								<th class="text-right" scope="col" width="100px"></th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$lstStaff item=staff name=i}
								<tr>
									<td class="text-center">
										<div class="checkbox checkbox_news">
											<input type="checkbox" name="key_staff[]" class="chkitem styled" value="{$staff.profile_id}">
											<label></label>
										</div>
									</td>
									<td>{$staff.full_name}</td>
									<td class="text-right">
										<button class="btn btn-default" type="button" onClick="$Core.gratitude.save_gratitude(this,event)" action="_open" data-is_singer="1" data-type="staff" data-id="{$staff.profile_id}">Tri ân</button>
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
			<button type="button" onClick="$Core.gratitude.save_gratitude(this, event)" action="_open" news_id="{$news_id}"  data-is_singer="0" data-type="staff" class="btn btn-primary">Tri ân</button>
		</div>
	</form>
</div>