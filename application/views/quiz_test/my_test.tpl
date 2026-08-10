<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	{if $cmd eq '_detail'}
		<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-3">
			<div class="p__left">
				<h4 class="fw-bold mb-1">{$oneItem.title}</h4>
				<div class="text-muted">
					{if $oneItem.test_type eq 'video'}<span class="badge bg-label-info me-1"><i class='bx bx-video'></i> Video</span>
					{elseif $oneItem.test_type eq 'module'}<span class="badge bg-label-warning me-1"><i class='bx bx-book-content'></i> Module</span>
					{else}<span class="badge bg-label-success me-1"><i class='bx bx-building'></i> Dự án</span>{/if}
					· {$oneItem.total_question} câu · Pass {$oneItem.pass_score}%
				</div>
			</div>
			<div class="p__right d-flex gap-2">
				<a href="/trac-nghiem-test/me" class="btn btn-outline-secondary">Danh sách</a>
				<a href="/trac-nghiem-test/take/{$oneItem.test_id}" class="btn btn-primary"><i class='bx bx-play-circle me-1'></i> Bắt đầu làm bài</a>
			</div>
		</div>

		<div class="card no-shadow">
			<div class="card-body">
				<div class="mb-4">
					<h6 class="fw-bold text-uppercase text-muted mb-2">Mô tả bài test</h6>
					{if $oneItem.intro}{$oneItem.intro}{else}<span class="text-muted font-italic">Không có mô tả.</span>{/if}
				</div>

				{if $oneItem.duration gt 0}
				<div class="d-flex align-items-center gap-2 mb-3">
					<i class='bx bx-time text-primary'></i>
					<span class="text-muted">Thời gian làm bài: <strong>{$oneItem.duration} phút</strong></span>
				</div>
				{/if}

				{if $oneItem.end_date gt 0}
				<div class="d-flex align-items-center gap-2 mb-3">
					<i class='bx bx-calendar-event text-danger'></i>
					<span class="text-muted">Hạn cuối: <strong>{$clsISO->formatDateTime($oneItem.end_date)}</strong></span>
				</div>
				{/if}

				{if !empty($lastAttempt)}
					<div class="p-3 border rounded-1 d-flex align-items-center gap-3 bg-light">
						<div class="flex-shrink-0">
							{if $lastAttempt.is_pass eq 1}
								<div class="avatar avatar-md bg-success text-white d-flex align-items-center justify-content-center rounded-circle"><i class='bx bx-check fs-4'></i></div>
							{else}
								<div class="avatar avatar-md bg-danger text-white d-flex align-items-center justify-content-center rounded-circle"><i class='bx bx-x fs-4'></i></div>
							{/if}
						</div>
						<div class="flex-grow-1">
							<h6 class="mb-0 fw-bold">Kết quả gần nhất</h6>
							<div class="text-muted small">Thời gian: {$clsISO->formatDateTime($lastAttempt.finished_at)}</div>
						</div>
						<div class="text-end">
							<div class="fs-4 fw-bold {if $lastAttempt.is_pass eq 1}text-success{else}text-danger{/if}">{$lastAttempt.score_percent}%</div>
							{if $lastAttempt.is_pass eq 1}<span class="badge bg-success">PASS</span>{else}<span class="badge bg-danger">FAIL</span>{/if}
						</div>
						<div class="ms-3 border-start ps-3">
							<a href="{$clsQuizTest->getLinkResult($lastAttempt.attempt_id)}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
						</div>
					</div>
				{/if}
			</div>
		</div>
	{else}
		<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-3">
			<div class="p__left">
				<h4 class="fw-bold mb-1">Trắc nghiệm</h4>
				<span class="text-muted">Các bài trắc nghiệm tại {$smarty.const.BRAND_NAME}</span>
			</div>
		</div>

		<div class="row g-4">
			{if !empty($lstTest)}
				{foreach from=$lstTest item=_oItem name=i}
					{assign var=attempt value=$attemptMap[$_oItem.test_id]}
					{assign var=isDone value=false}
					{if !empty($attempt) && $attempt.is_pass eq 1}{assign var=isDone value=true}{/if}

					<div class="col-12 col-md-6 col-lg-4">
						<div class="card h-100 rounded-2 shadow-sm border qt-test-card{if $isDone} qt-test-done{/if}">
							<div class="card-body d-flex flex-column">
								{* Header: title + dropdown *}
								<div class="d-flex justify-content-between align-items-start mb-3">
									<h5 class="fw-bold mb-0 text-dark pe-2" style="line-height:1.4;">{$_oItem.title}</h5>
									<div class="dropdown flex-shrink-0">
										<button class="btn btn-sm btn-icon btn-outline-none p-0" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="bx bx-dots-vertical-rounded fs-5 text-muted"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-end">
											<li><a class="dropdown-item" href="{$clsQuizTest->getLinkDetail($_oItem.test_id, $_oItem)}"><i class="bx bx-show me-1"></i> Xem bài kiểm tra</a></li>
											{if !empty($attempt)}
											<li><a class="dropdown-item" href="{$clsQuizTest->getLinkResult($attempt.attempt_id)}"><i class="bx bx-bar-chart me-1"></i> Xem kết quả</a></li>
											{/if}
										</ul>
									</div>
								</div>

								{* Info rows *}
								<div class="qt-test-info flex-grow-1">
									<div class="d-flex align-items-center gap-2 mb-2">
										<i class='bx bx-calendar-check text-danger' style="font-size:18px;"></i>
										<span>Bắt đầu: <strong>{if $_oItem.start_date gt 0}{$clsISO->formatDateTime($_oItem.start_date)}{else}Không giới hạn{/if}</strong></span>
									</div>
									<div class="d-flex align-items-center gap-2 mb-2">
										<i class='bx bx-calendar-x text-danger' style="font-size:18px;"></i>
										<span>Kết thúc: <strong>{if $_oItem.end_date gt 0}{$clsISO->formatDateTime($_oItem.end_date)}{else}Không giới hạn{/if}</strong></span>
									</div>
									<div class="d-flex align-items-center gap-2 mb-2">
										<i class='bx bx-time-five text-primary' style="font-size:18px;"></i>
										<span>Thời gian làm bài: <strong>{if $_oItem.duration gt 0}{$_oItem.duration} phút{else}Không giới hạn{/if}</strong></span>
									</div>
									<div class="d-flex align-items-center gap-2 mb-2">
										<i class='bx bx-help-circle text-warning' style="font-size:18px;"></i>
										<span>Số câu hỏi: <strong>{$_oItem.total_question} câu</strong></span>
									</div>
									<div class="d-flex align-items-center gap-2">
										<i class='bx bx-group text-secondary' style="font-size:18px;"></i>
										<span><strong>{$_oItem.total_profile}</strong> người</span>
									</div>
								</div>

								{* CTA Button *}
								<div class="mt-3 pt-2">
									{if $isDone}
										<a href="{$clsQuizTest->getLinkResult($attempt.attempt_id)}" class="btn w-100 py-2 fw-bold qt-btn-done">
											Đã làm bài
										</a>
									{else}
										<a href="/trac-nghiem-test/take/{$_oItem.test_id}" class="btn w-100 py-2 fw-bold qt-btn-start">
											Bắt đầu kiểm tra
										</a>
									{/if}
								</div>
							</div>
						</div>
					</div>
				{/foreach}
			{else}
				<div class="col-12">
					<div class="card border-0 shadow-sm">
						<div class="card-body text-center py-5">
							<i class='bx bx-check-double text-success fs-1 mb-2'></i>
							<div class="text-muted">Bạn không có bài test nào cần làm!</div>
						</div>
					</div>
				</div>
			{/if}
		</div>
	{/if}
</div>

{literal}
<style>
/* ── Quiz Test Card Grid ── */
.qt-test-card {
	transition: transform 0.2s ease, box-shadow 0.2s ease;
	border: 1px solid #e7e7e8;
}
.qt-test-card:hover {
	transform: translateY(-3px);
	box-shadow: 0 6px 20px rgba(0,0,0,.08) !important;
}
.qt-test-info {
	font-size: 13.5px;
	color: #566a7f;
}
.qt-test-info strong {
	color: #384551;
}

/* CTA buttons */
.qt-btn-start {
	background: linear-gradient(135deg, #696cff 0%, #7b61ff 100%);
	color: #fff;
	border: none;
	border-radius: 8px;
	font-size: 14px;
	letter-spacing: .3px;
}
.qt-btn-start:hover {
	background: linear-gradient(135deg, #5f62e6 0%, #6c55e6 100%);
	color: #fff;
}
.qt-btn-done {
	background: linear-gradient(135deg, #71dd37 0%, #86e84d 100%);
	color: #fff;
	border: none;
	border-radius: 8px;
	font-size: 14px;
	letter-spacing: .3px;
}
.qt-btn-done:hover {
	background: linear-gradient(135deg, #64c830 0%, #79d843 100%);
	color: #fff;
}
</style>
{/literal}
