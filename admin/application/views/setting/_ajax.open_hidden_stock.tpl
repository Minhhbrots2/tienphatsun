<div class="modal-dialog modal-md" style="max-width: 600px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-row">
					<div class="{if !empty($oneItem)}col-md-12{else}col-md-8{/if}">
						<div class="form-group">
							<label class="form-label">Tiêu đề</label>
							<input class="form-control required" placeholder="Tiêu đề" name="title" value="{$oneItem.title}" />
						</div>
					</div>			
					<div class="col-md-4 {if !empty($oneItem)}d-none{/if}">						
						<div class="form-group">
							<label class="form-label">Site</label>
							<select name="site" class="form-control">
								<option value="_FH" {if $oneItem.site eq '_FH'}selected{/if}>CA</option>
								<option value="_MOC" {if $oneItem.site eq '_MOC'}selected{/if}>MOC</option>
							</select>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">						
						<div class="form-group">
							<label class="form-label">Dự án</label>
							<select name="project_id" onchange="$Core.property.select_block(this, event)" toid="slb_Block_Id_{$uid}" class="form-control">
								<option value="0">Chọn dự án</option>
								{$clsProject->getSelectOptions($oneItem.project_id)}
							</select>
						</div>
					</div>
					<div class="col-md-6">						
						<div class="form-group">
							<label class="form-label">Phân khu</label>
							<select class="form-control" name="block_id" id="slb_Block_Id_{$uid}">
								{$clsProperty->getSelectByPropertyOrigin('_BLOCK',$project_id, $oneItem.block_id)}
							</select>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label">Là quỹ Vin</label>
					<div class="col-md-10">
						<label class="switch">
							<input type="checkbox" name="is_vin" value="1" {if $oneItem.is_vin eq 1}checked{/if}>
							<span class="slider round"></span>
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.property.save_hidden_stock(this, event)" agency_hidden_stock_id="{$agency_hidden_stock_id}" class="btn btn-success" data-action="save">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>Đóng</span>
				</button>
			</div>
		</form>
	</div>
</div>
