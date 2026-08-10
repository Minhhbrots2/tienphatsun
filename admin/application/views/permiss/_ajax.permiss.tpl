<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{if $action eq '_add'}Thêm{else}Sửa{/if} nhóm</strong></h3>
		</div>
		<form method="post" action="">
			<div class="modal-body">
				<div class="form-group mb-2">
					<label class="col-form-label">Mã nhóm<span class="text-red">*</span></label>
					<input type="text" class="form-control required" placeholder="Mã phân khu" name="code" value="{if $action eq '_edit'}{$onePermiss.code}{/if}" />
				</div>
				<div class="form-group mb-2">
					<label class="col-form-label">Tên nhóm<span class="text-red">*</span></label>
					<input type="text" class="form-control required" placeholder="Tên nhóm" name="title" value="{if $action eq '_edit'}{$onePermiss.title}{/if}" />
				</div>
				<div class="form-group mb-2">
					<label class="col-form-label">Miêu tả</label>
					<textarea class="form-control" placeholder="Miêu tả" name="description" cols="255" rows="3">{if $action eq '_edit'}{$onePermiss.description}{/if}</textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.permiss.save_permiss(this, event)" permiss_id="{$permiss_id}" 
						profile_type="{$profile_type}" tp="{$tp}" parent_id="{$parent_id}" class="btn btn-success">Lưu lại</button>
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Đóng</button>
			</div>
		</form>
	</div>
</div>