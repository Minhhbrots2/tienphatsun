<div class="modal-dialog modal-sm">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Trục tọa độ</strong></h3>
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
			<div class="modal-footer d-flex justify-content-end gap-1">
				{if $type eq "_EDIT"}
					<button type="button" class="btn btn-danger" onClick="$Core.project.map_FH.save_map(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" _type="{$type}" action="delete" shape_id={$shape_id}>Xóa</button>
					<button type="button" class="btn btn-success" onClick="$Core.project.map_FH.save_map(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" _type="{$type}" action="edit" shape_id={$shape_id} >Lưu</button>
				{else}
					<button type="button" class="btn btn-success" onClick="$Core.project.map_FH.save_map(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" _type="{$type}" action="add" shape_id={$shape_id} >Thêm</button>
				{/if}
				
			</div>
		</form>
	</div>
</div>
