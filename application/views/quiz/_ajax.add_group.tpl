<div class="box_group box_group_{$group_id}" data-group_id="{$group_id}" onClick="$Core.quiz.updateGroupFocus(this,event)">
	<div class="card rounded-1 mb-4" data-group_id="{$group_id}">
		<div class=" border-bottom d-flex align-items-center justify-content-between bg-lighter ">
			<h2 class="card-header title_box fs-5 text-dark mb-0 py-4 flex-fill cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapse_group_{$group_id}" aria-expanded="false" aria-controls="collapse_group_{$group_id}">Phần <span class="group_index">{$group_index}</span></h2>
			<div class="dropdown dropstart">
				<button type="button" class="btn p-0 dropdown-toggle btn-icon hide-arrow" data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
				</button>
				<div class="dropdown-menu">
					<a class="dropdown-item" onclick="$Core.quiz.removeGroupQuestion(this,event)" billing_id="1064" href="javascript:void(0);"><i class="bx bx-trash fs-3 me-1"></i> Xóa phần</a>
				</div>
			</div>
		</div>
		<div class="card-body collapse pt-3 show" id="collapse_group_{$group_id}">
			<div class="form-group mb-3">
				<input type="text" class="form-control form-control-lg no-focus form-field" name="groups[{$group_id}][title]" placeholder="Nhập tiêu đề phần" value="">
			</div>
			<div class="form-group">
				<textarea class="form-control no-focus form-field flex-fill" cols="255" rows="3" placeholder="Nhập mô tả" name="groups[{$group_id}][content]" id="{$clsISO->getUniqid()}">{$oneItem.content}</textarea>
			</div>
		</div>
	</div>
</div>