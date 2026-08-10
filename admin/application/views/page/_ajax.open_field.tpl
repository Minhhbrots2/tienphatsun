<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{if $action eq '_add'}Thêm mới{else}Chỉnh sửa{/if} CSBH</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Tiêu đề <span class="text-red">*</span></label>
					<input type="text" class="form-control required" value="{$oneField.title}" placeholder="Nhập tiêu đề" name="title" value="" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-success" tp="{$tp}" group_id="{$group_id}" 
				field_id="{$field_id}" onClick="$Core.report.save(this, event)" policy_id="{$policy_id}">Cập nhật</button>
			</div>
		</form>
	</div>
</div>
