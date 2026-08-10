<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Nhập số tầng</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Tên phân khu<span class="text-red">*</span></label>
					<input type="text" class="form-control required" placeholder="Nhập tên dự án" name="title" value="{$oneBlock.title}" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="add_template(this, event)" block_id="{$block_id}" project_id="{$project_id}" _openFrom="{$_openFrom}"{if $_openFrom eq '_stock'} toId="{$toId}"{/if}>Cập nhật</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>