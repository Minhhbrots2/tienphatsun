<div class="modal-dialog">
	<form class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Cấu hình</strong></h3>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<label class="col-form-label col-md-2">Min Zoom</label>
				<div class="col-md-4">
					<input type="text" class="form-control required" name="min_zoom"
						value="{$map_configs.min_zoom}" />
				</div>
				<label class="col-form-label col-md-2">Max Zoom</label>
				<div class="col-md-4">
					<input type="text" class="form-control required" name="max_zoom" 
						value="{$map_configs.max_zoom}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label col-md-2">Tooltip</label>
				<div class="col-md-4">
					<div class="d-flex gap-2 align-items-center">
						<label class="switch">
							<input type="checkbox" name="show_tooltip" 
								value="1"{if $map_configs.show_tooltip eq '1'} checked{/if}>
							<span class="slider round"></span>
						</label>
						<span class="text-muted">Hiển thị Tooltip</span>
					</div>
				</div>
				<label class="col-form-label col-md-2">Thiết lập</label>
				<div class="col-md-4">
					<div class="d-flex gap-2 align-items-center">
						<label class="switch">
							<input type="checkbox" name="enable_tooltip_position" 
								value="1"{if $map_configs.enable_tooltip_position eq '1'} checked{/if}>
							<span class="slider round"></span>
						</label>
						<span class="text-muted">vị trí Tooltip</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label col-md-2">Cho phép</label>
				<div class="col-md-4">
					<select class="form-control iso-select2" name="list_building_id[]" multiple="multiple">
						{foreach from=$list_buildings item = _oB}
						<option{if $clsISO->checkInArray($list_building_arrs, $_oB.property_id)} selected{/if} value="{$_oB.property_id}">{$_oB.title}</option>
						{/foreach}
					</select>
				</div>
				<label class="col-form-label col-md-2">Thiết lập</label>
				<div class="col-md-4">
					<div class="d-flex gap-2 align-items-center">
						<label class="switch">
							<input type="checkbox" name="is_all_block" 
								value="1"{if $map_configs.is_all_block eq '1'} checked{/if}>
							<span class="slider round"></span>
						</label>
						<span class="text-muted">tất cả block</span>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success pull-right" stock_shape_id="{$stock_shape_id}" 
				onClick="$Core.project.map_save_config(this, event)" >Cập nhật</button>
			<button type="button" class="btn btn-default mr-2 pull-right" data-dismiss="modal">Đóng</button>
		</div>
		
	</form>
</div>
