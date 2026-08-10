<div class="modal-dialog modal-sm">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Tên nhóm<span class="text-red">*</span></label>
					<input type="text" class="form-control required" placeholder="Tên nhóm" name="title" value="{$oneGroup.title}" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="pop_save_group(this, event)" project_id="{$project_id}" group_id="{$group_id}">Lưu nhóm</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>