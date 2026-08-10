<div class="modal-dialog modal-sm modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$oneItem.title}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					{foreach from=$property item=item name=i}
						<div class="d-flex align-items-center justify-content-between w-100 p-3">
							<label class="mb-0 fs-14" for="property_{$smarty.foreach.i.iteration}">
								{$item.title}
							</label>
							<div style="width:80px">
								<select name="number" class="iso-select2" data-title="{$item.title}" data-price="{$item.price}">
									<option value="">Chọn</option>
									{section name=j loop=10 start=0 step=1}
										<option value="{$smarty.section.j.iteration}">{$smarty.section.j.iteration}</option>
									{/section}
								</select>
							</div>
						</div>
					{/foreach}
				</div>				
			</div>
			<div class="modal-footer">
				<input type="hidden" name="price" value="0">
				<button type="button" onClick="$Core.furniture.proprertyFurniture(this,'load')" data-furniture_id="{$oneItem.furniture_id}" data-cat_id="{$cat_id}" class="btn btn-success">
					<span>Cập nhật</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>Đóng</span>
				</button>
			</div>
		</form>
	</div>
</div>
