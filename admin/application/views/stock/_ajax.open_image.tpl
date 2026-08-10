<div class="modal-dialog modal-md" style="width:375px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Import File</strong></h3>
		</div>
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">File đính kèm<span class="text-red">*</span></label>
					<input type="file" name="images[]" class="form-control" multiple="true"/>
				</div>
				<div class="form-group ">
					<label class="col-form-label">Đại lý<span class="text-red">*</span></label>
					<select name="agency_id" class="form-control otherwise required">
						<option value="0">Chọn đại lý</option>
						{foreach name=i from=$list_agents item = _oAgent}
							<option value="{$_oAgent.property_id}">{$_oAgent.title}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="$Core.stock.start_get_data_image(this,event)" 
				tp="{$tp}" stock_type="{$stock_type}" project_id="{$project_id}">Tiếp tục</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">Đóng</button>
			</div>
		</form>
	</div>
</div>