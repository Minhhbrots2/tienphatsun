<div class="modal-dialog modal-md" style="max-width: 600px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Dự án</label>
							<select name="project_id" class="form-select form-control" onChange="$Core.property.select_block(this,event)" toId="{$uid}">
								{$clsProject->getSelectOptions($project_id)}
							</select>
						</div>
					</div>			
					<div class="col-md-6">						
						<div class="form-group">
							<label class="form-label">Phân khu</label>
							<select name="block_id" class="form-select form-control" id="{$uid}">
								{if !empty($project_id)}
									{$clsProperty->getSelectByPropertyOrigin("_BLOCK",$project_id,$oneItem.block_id,"Phân khu/Block")}
								{else}								
									<option value="0">Phân khu/Block</option>
								{/if}
							</select>
						</div>
					</div>
				</div>
				<div class="form-row">		
					<div class="col-md-4">						
						<div class="form-group">
							<label class="form-label">Loại hình</label>
							<select name="stock_type" class="form-select form-control" id="{$uid}">
								<option value="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}" {if $oneItem.stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}selected{/if}>Cao tầng</option>
								<option value="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}" {if $oneItem.stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}selected{/if}>Thấp tầng</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label">Giá min</label>
							<input type="text" class="form-control price-In" name="min" value="{$oneItem.min}">
						</div>
					</div>			
					<div class="col-md-4">						
						<div class="form-group">
							<label class="form-label">Giá max</label>
							<input type="text" class="form-control price-In" name="max" value="{$oneItem.max}">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.property.save_field_config_price(this, event)" field_config_price_id="{$field_config_price_id}" class="btn btn-success" data-action="save">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>Đóng</span>
				</button>
			</div>
		</form>
	</div>
</div>
