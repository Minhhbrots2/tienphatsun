
<div class="modal-dialog modal-standard modal-sm" style="max-width: 400px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>Thêm điểm</strong></h3>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label">Điểm</label>
					<div class="col-xs-12 col-md-3">
						<input type="number" class="form-control form_field required" placeholder="10" min="1" name="score" value="1">
					</div>
				</div>
				<div class="form-group form-row mb-0">
					<label class="col-md-12 col-form-label">Nội dung</label>
					<div class="col-xs-12 col-md-12">
						<textarea class="form-control form_field" name="content" cols="255" rows="5"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer mt-0">
				<button type="button" class="btn btn btn-primary" onClick="$Core.member.add_point(this,'add')" member_id='{$member_id}' data-field="{$field}">
					Xác nhận
				</button>
			</div>
		</form>
	</div>
</div>
