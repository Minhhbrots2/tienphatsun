<div class="modal-dialog modal-sm" style="max-width: 600px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label">Tiêu đề</label>
							<input class="form-control required" placeholder="Tiêu đề" name="title" value="{$oneItem.title}" />
						</div>
					</div>			
					<div class="col-md-12">						
						<div class="form-group">
							<label class="form-label">Code</label>
							<input class="form-control required" placeholder="Code" name="code" value="{$oneItem.code}" />
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-3 col-form-label">Mặc định</label>
					<div class="col-md-6">
						<label class="switch">
							<input type="checkbox" name="is_default" value="1" {if $oneItem.is_default eq 1}checked{/if}>
							<span class="slider round"></span>
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.property.save_field_data_center(this, event)" field_data_center_id="{$field_data_center_id}" class="btn btn-success" data-action="save">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>Đóng</span>
				</button>
			</div>
		</form>
	</div>
</div>
