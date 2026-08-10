<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog"><div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title text-main">{$oneItem.title}</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="highlights">
			<div class="highlight-panel">
				<div class="highlight-list justify-content-between" style="min-width: unset">
					<div class="highlight_item">
						<div class="highlight-label">Giảng viên</div>
						<div class="metadata-row-viewer d-flex gap-1">
							<div class="issue-link text-muted">{$oneItem.author}</div>
						</div>
					</div>
					{if !empty($oneItem.time_training)}
					<div class="highlight_item">
						<div class="highlight-label">Thời lượng</div>
						<div id="start_date_{$uid}" class="metadata-row-viewer fs-13">
							{$clsISO->convertTimeMinute($oneItem.time_training)}
						</div>
					</div>
					{/if}
					<div class="highlight_item">
						<div class="highlight-label">Hoàn thành</div>
						<div class="metadata-row-viewer mt-2 done_ratio_{$training_id}">
							{$clsTraining->getProgress($training_id, $oneItem)}
						</div>
					</div>
					{if !empty($oneQuiz)}
						<div class="highlight_item">
							<a class="btn btn-outline-primary" href="{$clsQuiz->getLink($oneQuiz.quiz_id,$oneQuiz)}" target="_blank" >Làm bài test</a>
						</div>
					{/if}
				</div>
			</div>
		</div>
			{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkDEV()}
				<div class="modal-body">
					<div class="nav-align-top nav_training">
						<ul class="nav nav-tabs gap-2" role="tablist">
							<li class="nav-item" role="presentation">
								<button type="button" class="nav-link active px-0" role="tab" data-bs-toggle="tab" data-bs-target="#tab_lesson_{$uid}" aria-controls="tab_lesson_{$uid}" aria-selected="true">
								Danh sách bài học
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button type="button" class="nav-link px-0" role="tab" data-bs-toggle="tab" data-bs-target="#tab_profile_{$uid}" aria-controls="tab_profile_{$uid}" aria-selected="true" onclick="$Core.global.training.loadHistory(this,event)" training_id="{$training_id}" >
								Thành viên đã học
								</button>
							</li>
						</ul>
						<div class="tab-content px-0 scroller" style="box-shadow: 0 0">
							<div class="tab-pane fade active show" id="tab_lesson_{$uid}" role="tabpanel">
								<div class="lst_lesson border rounded-1">
									{foreach from=$lstLesson item=_oLesson key=key name=i}
										<div class="item_lesson position-relative p-3{if !$smarty.foreach.i.last} border-bottom{/if}" onClick="$Core.global.training.learning(this,event)" data-training_id="{$training_id}" data-lesson_id="{$key}">						
											<div class="d-flex justify-content-between align-items-center">
												<div class="d-flex align-items-center gap-2 flex-fill">
													<button class="btn rounded-pill btn_{$key} {if !$clsISO->checkItemInArray($key,$lesson_complete)}btn-outline-primary{else}btn-outline-success{/if} btn-sm btn-icon" type="button"  data-bs-toggle="tooltip" title="Xem bài học"><i class='bx bxs-right-arrow fs-12'></i></button><a class="d-flex align-items-start collapsed text-dark fs-16 lh-xs" data-bs-toggle="collapse" href="#lesson_{$key}" role="button" aria-expanded="false">{$_oLesson.title} </a>
												</div>
												{if $deviceType eq "phone"}
													<span class="btn btn-xs btn-success btn_completed btn_completed_{$key} btn-icon {if !$clsISO->checkItemInArray($key,$lesson_complete)}d-none{/if}"><i class='bx bx-check'></i></span>
												{else}
													<span class="btn btn-xs btn-success text-nowrap btn_completed_{$key} {if !$clsISO->checkItemInArray($key,$lesson_complete)}d-none{/if}">Đã hoàn thành</span>
												{/if}
											</div>
										</div>
									{/foreach}
								</div>
							</div>
							<div class="tab-pane fade" id="tab_profile_{$uid}" role="tabpanel">
								<ul class="list-unstyled" id="list_favourite" data-bs-popper="static"><div class="d-flex flex-column align-items-center justify-content-center p-3">
									<img src="{$URL_IMAGES}/listing-empty.svg" width="90px">
									<p>Danh sách trống</p>
								</div></ul>
							</div>
						</div>
					</div>
				</div>
			{else}
				<div class="modal-body scroller">
					<h6 class="mb-2 text-dark fs-18">Danh sách bài học</h6>
					<div class="lst_lesson border rounded-1">
						{foreach from=$lstLesson item=_oLesson key=key name=i}
							<div class="item_lesson position-relative p-3{if !$smarty.foreach.i.last} border-bottom{/if}" onClick="$Core.global.training.learning(this,event)" data-training_id="{$training_id}" data-lesson_id="{$key}">						
								<div class="d-flex justify-content-between align-items-center">
									<div class="d-flex align-items-center gap-2 flex-fill">
										<button class="btn rounded-pill btn_{$key} {if !$clsISO->checkItemInArray($key,$lesson_complete)}btn-outline-primary{else}btn-outline-success{/if} btn-sm btn-icon" type="button"  data-bs-toggle="tooltip" title="Xem bài học"><i class='bx bxs-right-arrow fs-12'></i></button><a class="d-flex align-items-start collapsed text-dark fs-16 lh-xs" data-bs-toggle="collapse" href="#lesson_{$key}" role="button" aria-expanded="false">{$_oLesson.title} </a>
									</div>
									{if $deviceType eq "phone"}
										<span class="btn btn-xs btn-success btn_completed btn_completed_{$key} btn-icon {if !$clsISO->checkItemInArray($key,$lesson_complete)}d-none{/if}"><i class='bx bx-check'></i></span>
									{else}
										<span class="btn btn-xs btn-success text-nowrap btn_completed_{$key} {if !$clsISO->checkItemInArray($key,$lesson_complete)}d-none{/if}">Đã hoàn thành</span>
									{/if}
								</div>
								{*<div class="collapse" id="lesson_{$key}">
									<div class="px-2 pt-2 pb-0">									
										{$_oLesson.content|html_entity_decode}
									</div>
								</div>	*}
							</div>
						{/foreach}
					</div>
				</div>
			{/if}
	</div></div>
</div>