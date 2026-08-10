{$scriptJs}
<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1">Trắc nghiệm</h4>
			<span class="text-muted">Các bài trắc nghiệm tại {$smarty.const.BRAND_NAME}</span>
		</div>
		{if $clsISO->checkPermission('edit_quiz')}
			<div class="p__right d-flex">			
				<div class="d-flex justify-content-end">
					{if $deviceType eq 'phone'}
						<a href="/trac-nghiem/add" data-type="open" data-quiz_id="0" class="btn btn-outline-primary mr-2 btn-icon" title="Tạo bài trắc nghiệm"><i class='bx bx-plus'></i></a>
					{else}
						<a href="/trac-nghiem/add" data-type="open" data-quiz_id="0" class="btn btn-outline-primary mr-2"><i class='bx bx-plus'></i> Tạo bài trắc nghiệm</a>
					{/if}
				</div>	
			</div>
		{/if}
	</div>
	<div class="card no-shadow">
		<div class="card-body">
			<div class="table-container no-shadow overflow-x-auto holder_courses" id="holder_courses">			
				<table class="table" width="100%" cellpadding="0" cellspacing="0">
					<thead><tr>
						{if $deviceType ne 'phone'}
							<th class="align-center text-left h-px-40" width="40px" style="background-color: #FFF !important">STT</th>
						{/if}
						<th class="align-center text-left h-px-40" width="">Tên bài trắc nghiệm</th>
						<th class="align-center text-left h-px-40" width="120" class="text-center">Loại</th>
						<th class="align-center text-left h-px-40" width="160">Gắn với (Chủ đề)</th>
						<th class="align-center text-left h-px-40" width="100px">Số câu hỏi</th>
						<th class="align-center text-center h-px-40" width="">Tham gia</th>
						<th class="align-center text-center h-px-40" width="10%">Đã tham gia</th>
						<th class="align-center text-center h-px-40" width="10%">Bài nộp</th>
						<th class="align-center text-left h-px-40" width="200px">Bắt đầu</th>
						<th class="align-center text-left h-px-40" width="200px">Kết thúc</th>
						<th class="align-center text-center h-px-40" width="160px">Thời gian làm bài</th>
						{if $clsISO->checkPermission('edit_quiz')}
							<th class="align-center text-left h-px-40" width="45px"></th>
						{/if}
					</tr></thead>
					{if !empty($lstQuiz)}
						{foreach from=$lstQuiz item=_oItem name=i}
							<tr>
								{if $deviceType ne 'phone'}
									<td class="text-center">{$smarty.foreach.i.iteration}</td>
								{/if}
								<td class="text-left">
									{if !empty($_oItem.is_online)}
										{$_oItem.title}
									{else}
										<div class="position-relative">
											{$_oItem.title}
											<span class="position-absolute top-0 right-0 btn btn-xs bg-lighter text-main">Nháp</span>
										</div>
									{/if}
								</td>
								<td class="text-center">
									{if $_oItem.quiz_type eq 'video'}<span class="badge bg-label-info text-nowrap"><i class='bx bx-video'></i> Video</span>
									{elseif $_oItem.quiz_type eq 'module'}<span class="badge bg-label-warning text-nowrap"><i class='bx bx-book-content'></i> Module</span>
									{elseif $_oItem.quiz_type eq 'training'}<span class="badge bg-label-primary text-nowrap"><i class='bx bx-play-circle'></i> Đào tạo</span>
									{else}<span class="badge bg-label-success text-nowrap"><i class='bx bx-building'></i> Dự án</span>{/if}
								</td>
								<td class="text-left text-nowrap"><span class="text-muted">{$_oItem.ref_name}</span></td>
								<td class="text-center">{$_oItem.total_question} câu</td>
								<td class="text-left text-nowrap">
									{if $_oItem.is_all_staff eq 1}
										Tất cả thành viên
									{else if $_oItem.is_all_staff eq 3}
										<div class="d-flex flex-column">
											{if $_oItem.group_profile}<span>{$_oItem.group_profile}</span>{/if}
										</div>
									{else}
										<div class="d-flex flex-column">
											{if $_oItem.department}<span>{$_oItem.department}</span>{/if}
											{if $_oItem.profile}<span>{$_oItem.profile}</span>{/if}
										</div>
									{/if} 
									<p class="mb-0"><i class='bx bx-user mr-1 align-top'></i>{$_oItem.total_profile}</p>
								</td>
								<td class="text-center">{$_oItem.total_answer}</td>
								<td class="text-center">
									<div class="d-flex align-items-center {if !empty($_oItem.total_answer_completed)}justify-content-end{else}justify-content-center{/if} gap-1">
										{$_oItem.total_answer_completed} <i class='bx bx-news'></i>
										{if !empty($_oItem.total_answer_completed)}
										<button class="btn btn-sm btn-outline-default d-flex align-items-center gap-1" type="button" onClick="$Core.quiz.list_question_answer(this,event)" quiz_id="{$_oItem.quiz_id}" >
											Xem
										</button>
										{/if}
									</div>
								</td>
								<td class="text-left">{if !empty($_oItem.start_date)}{$clsISO->formatDate($_oItem.start_date,4)}{else}Không giới hạn{/if}</td>
								<td class="text-left">{if !empty($_oItem.end_date)}{$clsISO->formatDate($_oItem.end_date,4)}{else}Không giới hạn{/if}</td>
								<td class="text-center">{if !empty($_oItem.duration)}{$_oItem.duration} phút{else}Không giới hạn{/if}</td>
								{if $clsISO->checkPermission('edit_quiz')}
									<td class="text-center">
										<div class="dropdown dropstart">
											<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bx bx-dots-vertical-rounded"></i>
											</button>
											{if !empty($_oItem.is_online)}
												<div class="dropdown-menu" style="">										
													<a class="dropdown-item" href="javascript:void(0)" onClick="$Core.quiz.cancelPublicQuiz(this,event)" quiz_id="{$_oItem.quiz_id}"><i class='bx bx-bookmark-alt me-1'></i> Hủy đăng bài</a>
													<a class="dropdown-item" onclick="$Core.quiz.deleteQuiz(this,event)" data-quiz_id="{$_oItem.quiz_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
												</div>
											{else}										
												<div class="dropdown-menu w-px-100" style="">
													<a class="dropdown-item" href="{$clsQuiz->getLinkEdit($_oItem.quiz_id)}"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
													<a class="dropdown-item" onclick="$Core.quiz.deleteQuiz(this,event)" data-quiz_id="{$_oItem.quiz_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
												</div>
											{/if}
										</div>
									</td>
								{/if}
							</tr>
						{/foreach}
					{else}
						<tr><td colspan="10" class="text-center">Danh sách trống!</td></tr>
					{/if}
				</table>
			</div>
		</div>
		<div id="pager" class="simple-pagination"></div>
	</div>
</div>