<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Thêm mới dự án</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Tên dự án
						<span class="text-red">*</span>
					</label>
					<input type="text" class="form-control required" placeholder="Nhập tên dự án" name="title" />
				</div>
				<div class="form-group">
					<label class="col-form-label">Mô tả</label>
					<textarea id="adsIntro" class="form-control" name="content" rows="4"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="pop_create_project(this, event)" project_id="{$project_id}">Tạo dự án</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>