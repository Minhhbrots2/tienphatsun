<div class="modal-dialog" style="width:375px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Import File</strong></h3>
		</div>
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">File đính kèm<span class="text-red">*</span></label>
					<input type="file" name="attachment" class="form-control" />
				</div>
				{if $stock_type ne $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
				<div class="form-group ">
					<label class="col-form-label">Dự án<span class="text-red">*</span></label>
					<select name="project_id" class="form-control otherwise required">
						<option value="0">Chọn dự án</option>
						{foreach name=i from=$list_projects item = _project}
						<option value="{$_project.project_id}">{$_project.title}</option>
						{/foreach}
					</select>
				</div>
				{/if}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="start_import_file(this, event)" 
				tp="{$tp}" stock_type="{$stock_type}" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}">{if $tp eq 'blank'}Upload{else}Import{/if}</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>