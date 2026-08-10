<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable modal-ipad-xl">
		<form class="d-none" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="hid" value="upload">
			<input type="file" toid="{$toId}" class="upload_file_{$toId}" onchange="$Core.billing.upload_sp_file(this, event)" name="banner" billing_id="1059" to_field="banner">
		</form>
		<form method="POST" enctype="multipart/form-data" class="modal-content">
			<div class="modal-content">
				<div class="modal-header d-flex align-items-center justify-content-between">
					<div class="modal-header__left">
						<h5 class="modal-title">Danh sách bài nộp</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
					</div>
				</div>
				<div class="modal-body modal-body-scrollable">	
					<div class="table-container no-shadow overflow-x-auto holder_courses" id="holder_courses">			
						<table class="table mb-0" width="100%" cellpadding="0" cellspacing="0">
							<thead><tr>
								<th class="align-center text-left h-px-40 bg-lighter" width="25%">Người tham gia</th>
								<th class="align-center text-left h-px-40 bg-lighter" width="100px">Thời gian nộp</th>
								<th class="align-center text-left h-px-40 bg-lighter" width="">Câu đã chọn</th>
								<th class="align-center text-left h-px-40 bg-lighter" width="10%">Câu đúng</th>
								<th class="align-center text-left h-px-40 bg-lighter" width="10%">Điểm</th>
								<th class="align-center text-left h-px-40 bg-lighter" width="10%">Xem</th>
							</tr></thead>
							{foreach from=$lstItem item=_oItem name=i}
								<tr class="cursor-pointer">
									<td class="text-left">
										<a href="javascript:void(0);" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oItem.user_id}" data-toggle="webui-popover" data-trigger="hover" class="text-nowrap" ><img class="avatar avatar-xxs mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oItem.user_id,$_oItem.profile)}">{$clsProfile->getFullName($_oItem.user_id,$_oItem.profile)}</a>
									</td>
									<td class="text-center">{$clsISO->formatDate($_oItem.time_completed,4)}</td>
									<td class="text-center">{$_oItem.total_answered} câu</td>
									<td class="text-center txt_number_correct_{$_oItem.id}">{if !empty($_oItem.is_scored)}{$_oItem.number_correct} câu{else}Chờ chấm{/if}</td>
									<td class="text-center text-nowrap">{if !empty($_oItem.is_scored)}{$_oItem.score} điểm{elseif $clsISO->checkPermission('edit_quiz')}<button type="button" class="btn btn-primary fs-11 px-1 btn_quiz_answer_{$_oItem.id}" onclick="$Core.quiz.gradeExam(this,event)" action="_ONE" quiz_id="{$quiz_id}" quiz_answers_id="{$_oItem.id}" >Chấm điểm</button>{else}Chờ chấm{/if}</td>		
									<td class="text-center"><button type="button" class="btn btn-outline-none btn-icon btn-sm" onclick="$Core.quiz.grading_answers(this,event)" action="grading" quiz_id="{$_oItem.quiz_id}" quiz_answer_id="{$_oItem.id}" ><i class="material-icons-outlined fs-14 me-1">visibility</i></button></td>
								</tr>
							{/foreach}
						</table>
					</div>
				</div>
				<div class="modal-footer border-top">
					{if !empty($is_gradeExam_all)}
						<button type="button" class="btn btn-primary" onclick="$Core.quiz.gradeExam(this,event)" action="_ALL" quiz_id="{$quiz_id}" quiz_answers_id="0" >Chấm tất cả</button>
					{/if}
					<button type="button" class="btn btn-default" data-bs-dismiss="modal" >Đóng</button>
				</div>
			</div>
		</form>
	</div>
</div>