<div class="modal-dialog modal-dialog-centered modal-md">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<div>
			{if $type eq 'dep'}
				<h5 class="modal-title" id="modalTopTitle">Lịch sử tri ân phòng {$title}</h5>
			{else}
				<h5 class="modal-title" id="modalTopTitle">Lịch sử tri ân {$title}</h5>
			{/if}
				<span class="text-muted">Điểm tri ân: {$total_score_gratitude}</span>
			</div>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="form-group mb-2">
				<table class="table">
					<thead>
						<tr>
							<th class="text-center" scope="col" width="50px">STT</th>
							<th scope="col">Người tri ân</th>
							<th class="text-center" scope="col" width="100px">Điểm</th>
							<th class="text-right" scope="col">Thời gian</th>
						</tr>
					</thead>
					<tbody>
						{if !empty($arr_history)}
							{foreach from=$arr_history item=history name=i}
								<tr>
									<td class="text-center">{$smarty.foreach.i.iteration}</td>
									<td>{$history.full_name}</td>
									<td class="text-center">
										{$history.score}
									</td>
									<td class="text-right">
										{$history.reg_date}
									</td>
								</tr>
							{/foreach}
						{else}
							<tr><td colspan="4" class="text-center">Danh sách trống</td></tr>
						{/if}
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="news_id" value="{$news_id}">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			{if $profile_id ne $id}
				<button type="button" onClick="$Core.gratitude.save_gratitude(this, event)" action="_open" news_id="{$news_id}"  data-is_singer="1" data-type="{$type}" data-id="{$id}" class="btn btn-primary">Tri ân</button>
			{/if}
		</div>
	</form>
</div>