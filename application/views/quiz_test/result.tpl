<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-3">
		<div class="p__left">
			<h4 class="fw-bold mb-1">Kết quả: {$oneItem.title}</h4>
			<div class="text-muted mt-1">
				<i class='bx bx-time'></i> Hoàn thành lúc: {$clsISO->formatDateTime($attempt.finished_at)}
			</div>
		</div>
		<div class="p__right d-flex gap-2">
			<a href="/trac-nghiem-test/me" class="btn btn-outline-secondary">Danh sách</a>
			<a href="/trac-nghiem-test/take/{$oneItem.test_id}" class="btn btn-primary"><i class='bx bx-refresh me-1'></i> Làm lại bài</a>
		</div>
	</div>

	<div class="col-md-10 col-lg-8 mx-auto">
		{* Score summary card *}
		<div class="card mb-4 rounded-1 shadow-sm border-0 {if $attempt.is_pass eq 1}border-top border-success border-3{else}border-top border-danger border-3{/if}">
			<div class="card-body py-4 d-flex justify-content-around text-center align-items-center">
				<div>
					<div class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Điểm số</div>
					<div class="fs-2 fw-bold {if $attempt.is_pass eq 1}text-success{else}text-danger{/if}">{$attempt.score_percent}%</div>
				</div>
				<div class="border-start opacity-25" style="height: 50px;"></div>
				<div>
					<div class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Pass Score</div>
					<div class="fs-3 fw-bold text-dark">{$attempt.pass_score}%</div>
				</div>
				<div class="border-start opacity-25" style="height: 50px;"></div>
				<div>
					<div class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Kết quả</div>
					<div>
						{if $attempt.is_pass eq 1}
							<span class="badge bg-label-success fs-5 px-3 py-2"><i class='bx bx-check-circle me-1'></i> PASS</span>
						{else}
							<span class="badge bg-label-danger fs-5 px-3 py-2"><i class='bx bx-x-circle me-1'></i> FAIL</span>
						{/if}
					</div>
				</div>
			</div>
		</div>

		{* Questions review *}
		<div class="card mb-5 rounded-1 shadow-sm border-0">
			<div class="card-header border-bottom bg-light">
				<h6 class="mb-0 fw-bold text-uppercase text-muted"><i class='bx bx-list-check me-1'></i> Chi tiết câu trả lời</h6>
			</div>
			<div class="card-body p-4">
				{if !empty($questions)}
					{foreach from=$questions item=q key=qi name=i}
						{assign var=opts value=$q.options}
						<div class="border rounded-1 p-4 mb-4 bg-white position-relative">
							<div class="d-flex align-items-start mb-3">
								<div class="badge bg-label-secondary text-dark rounded-circle p-2 me-3 fs-6 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">{$smarty.foreach.i.iteration}</div>
								<div class="fw-semibold fs-5 mt-1" style="line-height: 1.4;">{$q.title}</div>
							</div>
							
							<div class="ps-5">
								{foreach from=$opts item=opt key=oi}
									<div class="d-flex p-2 rounded-1 mb-1 align-items-center {if !empty($opt.is_correct)}bg-label-success border border-success{/if}">
										<div class="me-3 fs-5 d-flex align-items-center">
											{if !empty($opt.is_correct)}
												<i class="bx bxs-check-circle text-success"></i>
											{else}
												<i class="bx bx-circle text-muted"></i>
											{/if}
										</div>
										<span class="{if !empty($opt.is_correct)}text-success fw-bold{else}text-muted{/if}">{$opt.text}</span>
									</div>
								{/foreach}
							</div>
						</div>
					{/foreach}
				{else}
					<div class="text-center py-5 text-muted">
						<i class='bx bx-ghost fs-1 mb-2'></i>
						<div>Không có dữ liệu câu hỏi.</div>
					</div>
				{/if}
			</div>
		</div>
	</div>
</div>
