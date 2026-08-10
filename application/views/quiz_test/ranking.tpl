<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><i class='bx bx-trophy text-warning'></i> Bảng xếp hạng</h4>
			<span class="text-muted">Qualification Score (QS) và Phân hạng</span>
		</div>
		<div class="p__right d-flex">
			<a href="/trac-nghiem-test/me" class="btn btn-outline-secondary">Bài test của tôi</a>
		</div>
	</div>

	<div class="card no-shadow">
		<div class="card-body p-0">
			<div class="table-container no-shadow overflow-x-auto">
				<table class="table table-hover mb-0" width="100%" cellpadding="0" cellspacing="0">
					<thead class="table-light">
						<tr>
							<th width="60" class="text-center">Hạng</th>
							<th>Nhân viên</th>
							<th width="120" class="text-center">QS</th>
							<th width="120" class="text-center">Phân hạng (Tier)</th>
						</tr>
					</thead>
					<tbody>
					{if !empty($lstRanking)}
						{foreach from=$lstRanking item=_oItem name=i}
							<tr>
								<td class="text-center">
									{if $smarty.foreach.i.iteration eq 1}
										<span class="badge bg-label-warning px-2 py-1 fs-6"><i class='bx bxs-medal'></i> 1</span>
									{elseif $smarty.foreach.i.iteration eq 2}
										<span class="badge bg-label-secondary px-2 py-1 fs-6"><i class='bx bxs-medal'></i> 2</span>
									{elseif $smarty.foreach.i.iteration eq 3}
										<span class="badge bg-label-danger px-2 py-1 fs-6"><i class='bx bxs-medal'></i> 3</span>
									{else}
										<span class="fw-semibold text-muted">{$smarty.foreach.i.iteration}</span>
									{/if}
								</td>
								<td class="text-left fw-semibold">{$_oItem.full_name}</td>
								<td class="text-center fs-5 fw-bold text-primary">{$_oItem.qualification_score}</td>
								<td class="text-center">
									{if $_oItem.tier eq 'S'}<span class="badge bg-danger fs-6 px-3">S</span>
									{elseif $_oItem.tier eq 'A'}<span class="badge bg-warning fs-6 px-3">A</span>
									{elseif $_oItem.tier eq 'B'}<span class="badge bg-info fs-6 px-3">B</span>
									{elseif $_oItem.tier eq 'C'}<span class="badge bg-success fs-6 px-3">C</span>
									{else}<span class="badge bg-secondary fs-6 px-3">D</span>{/if}
								</td>
							</tr>
						{/foreach}
					{else}
						<tr><td colspan="4" class="text-center py-4">Chưa có dữ liệu xếp hạng.</td></tr>
					{/if}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
