<div class="modal-dialog modal-sm">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePgae}</strong></h3>
		</div>
		{assign var = toId value = $clsISO->getUniqid()}
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group mb-2">
					<label class="form-label mb-1">Trục căn:</label>
					<select class="form-control" name="code">
						{$html_code}
					</select>
				</div>
				<div class="form-row">
					<div class="col-xs-6">
						<div class="form-group">
							<label class="form-label mb-1">Top</label>
							<input class="form-control numberonly" type="text" name="top" value="0">
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label class="form-label mb-1">Left</label>
							<input class="form-control numberonly" type="text" name="left" value="0">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="$Core.project.map_FH.save_map(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" _type="delete" shape_id={$shape_id}>Xóa</button>
				<button type="button" class="btn btn-success pull-right" onClick="$Core.project.map_FH.save_map(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" _type="save" shape_id={$shape_id} >Lưu</button>
			</div>
		</form>
	</div>
</div>
