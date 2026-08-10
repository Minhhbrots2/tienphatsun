<div class="modal-dialog">
	<form class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$title_content}</strong></h3>
		</div>
		<div class="modal-body">
			<div class="form-group" style="max-height: calc(100vh - 200px);overflow-y: auto">
				<table width="100%" class="table table-vertical mb-0 table-stripped">
					<thead style="position: sticky;top: 0;background: #FFF;z-index: 2"><tr>
						<th width="5%">No.</th>
						<th width="35%">Thuộc tính</th>
						<th width="5%" class="text-center"><button type="button" class="btn btn-default" onclick="$Core.project.add_field(this,event)" _openfrom="_project" project_id="31" _holderg="_attrs">+ Thêm</button></th>
					</tr></thead>
					<tbody class="tbody_attrs connectedSortable md_sortable">
						{foreach from=$arr_field item=title key=field name=i}
							{assign var=gId value=$clsISO->getUniqid()}
							<tr id="{$gId}" class="tr_attrs">
								<td class="text-center">
									<div class="mySortableHandler"><i class="fa fa-arrows"></i></div>
								</td>
								<td class="text-left">
									<select name="config_column[]" id="" class="form-select form-control required">
										<option value="" >--Chọn--</option>
										{foreach from=$lstField item=label key=key name=i}
											<option value="{$key}" {if $field eq $key}selected{/if}>{$label}</option>
										{/foreach}
									</select>
								</td>
								<td class="text-center">
									<a title="Xóa" href="javascript:void(0);" class="btn btn-default" uid="{$uid}" onClick="$Core.project.delete_field(this,event)"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
			
		</div>
		<div class="modal-footer">
			<input type="hidden" name="block_type" value="{$block_type}">
			<input type="hidden" name="project_id" value="{$project_id}">
			<button type="button" class="btn btn-success pull-right" stock_shape_id="{$stock_shape_id}" 
				onClick="$Core.project.save_config(this, event)" >Cập nhật</button>
			<button type="button" class="btn btn-default mr-2 pull-right" data-dismiss="modal">Đóng</button>
		</div>
		
	</form>
</div>
