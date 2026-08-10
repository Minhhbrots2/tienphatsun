<div class="modal-dialog modal-standard">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>Bài học</strong></h3>
		</div>
		{assign var = toId value = $clsISO->getUniqid()}
		<form class="d-none" enctype="multipart/form-data">
			<input id="{$toId}" class="select_file_{$toId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" charset="UTF-8" onChange="$Core.training.upload_file(this, event)" name="upload_file" />
		</form>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-row">
					<div class="col-xl-12 col-md-6">
						<label class="col-form-label">Tên bài học*</label>
						<div class="form-group">
							<input type="text" class="form-control form_field required" placeholder="Nhập tên bài học" name="title" value="{$oneItem.title}" data-text="Tên bài học">
						</div>
					</div>
					<div class="col-xl-12 col-md-6">
						<label class="col-form-label">Điểm bài học*</label>
						<div class="form-group">
							<input type="text" class="form-control form_field numberonly required" placeholder="Nhập điểm bài học" name="point" value="{$oneItem.point}"  data-text="Điểm bài học">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label">Nội dung</label>
					<textarea id="{$clsISO->getUniqid()}" class="form-control edit_profile_field_about" name="content" cols="255" rows="5">{$oneItem.content}</textarea>
				</div>
				<div class="form-group">
					<label class="col-form-label">Video bài học</label>
					<div class="input-group w-100">
						<input type="text" id="content_video_{$toId}" class="form-control" value="{$oneItem.video}" name="video" placeholder="Dán link youtube" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label">Tài liệu đính kèm</label>
					<div class="input-group">
						<input type="text" id="content_file_{$toId}" class="form-control" value="{$oneItem.file}"
							   name="file" placeholder="Tài liệu đính kèm" />
						<div class="input-group-btn">
							<button type="button" toId="{$toId}" onClick="$Core.training.select_file(this, event)" data-type="file"  
							class="btn btn-default"><i class="fa fa-upload"></i><span>Chọn</span></button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="lesson_id" value="{$lesson_id}">
				<input type="hidden" name="training_id" value="{$training_id}">
				<button type="button" class="btn btn btn-primary" onClick="$Core.training.save_lesson(this,event)" data-training_id='{$training_id}' data-lesson_id='{$lesson_id}'>
					Tạo
				</button>
			</div>
		</form>
	</div>
</div>
