<div class="modal fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center justify-content-between">
				<h5 class="modal-title">{$oneLesson.title}<span class="text-success ml-1 view_lesson_{$lesson_id} {if !$clsISO->checkItemInArray($lesson_id,$lesson_complete)}d-none{/if}" data-bs-toggle="tooltip" title="Đã hoàn thành"><i class='bx bx-check-double'></i></span></h5>
				<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body scroller">
				<div class="">
					{if $deviceType eq "phone"}
						{$clsISO->getEmbedVideo($oneLesson.video,'100%','250px',1)}
					{else}
						{$clsISO->getEmbedVideo($oneLesson.video,"100%","500px",1)}
					{/if}
				</div>
			</div>
			<div class="modal-footer justify-content-between align-items-center">
				<div class="fs-14">
					<span class="text-muted">Số lượt xem:</span> {$number_view}
				</div>
				<div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
					{if !empty($oneLesson.file)}
					<a href="{$oneLesson.file}" class="btn btn-outline-default" download target="_blank">
						<i class='bx bx-download me-1'></i> Download tài liệu</a>
					{/if}
					{if $clsTraining->checkComplete($training_id,$lesson_id,$oneItem)}
					<button class="btn btn-success" type="button">Đã hoàn thành</button>						
					{else}
					<button class="btn btn-primary" type="button" onclick="$Core.training.completed(this,event)" data-training_id="{$training_id}" data-lesson_id="{$lesson_id}">Hoàn thành</button>
					{/if}
				</div>
			</div>
		</div>
	</div>
</div>