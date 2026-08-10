<div class="modal-dialog modal-ipad">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Tiêu đề<span class="text-red">*</span></label>
					<input type="text" class="form-control required" placeholder="Nhập tên tài liệu" name="title" value="{$oneItem.title}" />
				</div>
				<div class="form-group form-row">
					<div class="col-md-8">
						<label class="col-form-label">Phân khu</label>
						<div class="input-group d-flex gap-1">
							{assign var = toId value = $clsISO->getUniqid()}
							<select class="form-control w-50" toId="{$toId}" name="block_id" 
								onChange="$Core.project.select_building(this, event)" placeholder="Chọn phân khu">
								<option value="0">Tất cả</option>
								{foreach from=$lstBlock item=_oBlock}
									<option value="{$_oBlock.property_id}" {if $oneItem.block_id eq $_oBlock.property_id}selected{/if}>{$_oBlock.title}</option>
								{/foreach}
							</select>
							<select class="form-control iso-select2 w-50" multiple="multiple" id="{$toId}" name="building_ids[]" data-placeholder="Chọn tòa" placeholder="Chọn tòa">
								{if !empty($list_buildings)}
									{foreach from=$list_buildings item = _oI}
									<option{if $clsISO->checkInArray($oneItem.building_ids,$_oI.property_id)} selected{/if} 
										value="{$_oI.property_id}">{$_oI.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Danh mục</label>
						<select class="form-control" name="cat_id">
							{$clsProperty->getSelectByProperty("_UTILITIES_PROJECT",$oneItem.cat_id,"Chọn danh mục")}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label">Mô tả</label>
					<textarea class="form-control" name="content" rows="6">{$oneItem.content}</textarea>
				</div>
				<div class="form-group">
					<label class="d-block col-form-label">Hình ảnh</label>					
					<div class="input-group">
						<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh đại diện" id="isoman_url_image" value="{$oneItem.image}">
						<div class="input-group-btn"><button class="btn btn-icon btn-default ajOpenDialog" isoman_for_id="image" isoman_val="" isoman_name="image"><i class="fa fa-image"></i></button></div>	
					</div>
					{*{if $action eq '_edit' && !empty($oneItem.image)}
					<a class="download" target="_blank" href="{$oneItem.image}" style="word-break: break-all">
						{$core->makeIcon('image', $oneItem.image)}
					</a>
					{/if}
					<input type="file" name="image" class="form-control" />*}
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" project_id="{$project_id}" utilities_id="{$utilities_id}"  onClick="$Core.utilities.save(this,event)">Lưu lại</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>



