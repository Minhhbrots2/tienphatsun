<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-3">
		<div class="p__left">
			<h4 class="fw-bold mb-1">Quản lý bài test</h4>
			<span class="text-muted">Đánh giá nhân sự bằng Video / Module / Dự án</span>
		</div>
		<div class="p__right d-flex gap-2">
			<a href="/trac-nghiem-test/ranking" class="btn btn-outline-warning"><i class='bx bx-trophy'></i> Bảng xếp hạng</a>
			<a href="/trac-nghiem-test/add" class="btn btn-primary"><i class='bx bx-plus'></i> Tạo bài test mới</a>
		</div>
	</div>

	<div class="card no-shadow border-0">
		<div class="card-body p-0">
			<div class="table-container no-shadow overflow-x-auto">
				<table class="table table-hover mb-0" width="100%" cellpadding="0" cellspacing="0">
					<thead class="table-light">
						<tr>
							<th width="40" class="text-center">#</th>
							<th>Tên bài test</th>
							<th width="120" class="text-center">Loại</th>
							<th width="160">Gắn với (Chủ đề)</th>
							<th width="100" class="text-center">Số câu</th>
							<th width="100" class="text-center">Pass</th>
							<th width="120" class="text-center">Thời gian</th>
							<th width="80" class="text-center">Tác vụ</th>
						</tr>
					</thead>
					<tbody>
					{if !empty($lstTest)}
						{foreach from=$lstTest item=_oItem name=i}
							<tr>
								<td class="text-center text-muted">{$smarty.foreach.i.iteration}</td>
								<td class="text-left fw-semibold">{$_oItem.title}</td>
								<td class="text-center">
									{if $_oItem.test_type eq 'video'}<span class="badge bg-label-info"><i class='bx bx-video'></i> Video</span>
									{elseif $_oItem.test_type eq 'module'}<span class="badge bg-label-warning"><i class='bx bx-book-content'></i> Module</span>
									{elseif $_oItem.test_type eq 'training'}<span class="badge bg-label-primary"><i class='bx bx-play-circle'></i> Đào tạo</span>
									{else}<span class="badge bg-label-success"><i class='bx bx-building'></i> Dự án</span>{/if}
								</td>
								<td class="text-left text-nowrap"><span class="text-muted">{$_oItem.ref_name}</span></td>
								<td class="text-center fw-semibold">{$_oItem.total_question}</td>
								<td class="text-center text-success fw-semibold">{$_oItem.pass_score}%</td>
								<td class="text-center">
									{if $_oItem.duration gt 0}
										<span class="badge bg-label-secondary">{$_oItem.duration} phút</span>
									{else}
										<span class="text-muted"><i class='bx bx-infinite'></i></span>
									{/if}
								</td>
								<td class="text-center">
									<div class="dropdown dropstart">
										<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="bx bx-dots-vertical-rounded fs-4 text-muted"></i>
										</button>
										<div class="dropdown-menu">
											<a class="dropdown-item" href="{$clsQuizTest->getLinkEdit($_oItem.test_id)}"><i class="bx bx-edit-alt me-1 text-primary"></i> Sửa bài test</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteTest({$_oItem.test_id})"><i class="bx bx-trash me-1"></i> Xóa</a>
										</div>
									</div>
								</td>
							</tr>
						{/foreach}
					{else}
						<tr>
							<td colspan="8" class="text-center py-5">
								<i class='bx bx-ghost fs-1 text-muted mb-2'></i>
								<div class="text-muted">Danh sách trống!</div>
							</td>
						</tr>
					{/if}
					</tbody>
				</table>
			</div>
			{if !empty($html_pager)}
				<div class="p-3 border-top">{$html_pager}</div>
			{/if}
		</div>
	</div>
</div>

<script>
function deleteTest(test_id) {
	if(confirm('Bạn có chắc chắn muốn xóa bài test này không?')) {
		$.post('/index.php?mod=quiz_test&act=deleteTest', { test_id: test_id }, function(res) {
			var d = typeof res === 'string' ? JSON.parse(res) : res;
			if(d.result) {
				location.reload();
			} else {
				alert('Lỗi xóa bài test!');
			}
		});
	}
}
</script>
