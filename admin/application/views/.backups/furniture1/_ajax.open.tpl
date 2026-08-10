<div class="modal-dialog modal-md" style="max-width: 600px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		{assign var = gId value = $clsISO->getUniqid()}
		<form class="d-none" enctype="multipart/form-data">
			<input id="{$gId}" class="select_file_{$gId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" charset="UTF-8" 
				onChange="$Core.furniture.upload_file(this, event)" name="upload_file" />
			<input id="{$gId}" class="select_heic_file_{$gId}" type="file" charset="UTF-8" 
				onChange="$Core.furniture.upload_heic_file(this, event)" name="upload_file" />
		</form>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					{*<label class="col-md-2 col-form-label text-right">Mã nội thất</label>
					<div class="col-md-4">
						<input class="form-control" placeholder="Tiêu đề" maxlength="255" name="furniture_code" 
							   value="{if $action eq '_edit'}{$oneFurniture.furniture_code}{/if}" />
					</div>*}
					<label class="col-md-2 col-form-label text-right"><span class="requiredMask">*</span>Tiêu đề</label>
					<div class="col-md-10">
						<input class="form-control required" placeholder="Tiêu đề" maxlength="255" name="title" 
							   value="{if $action eq '_edit'}{$oneFurniture.title}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right"><span class="requiredMask">*</span>Khối lượng</label>
					<div class="col-md-4">
						<input class="form-control required" name="weight" placeholder="Khối lượng" value="{if $action eq '_edit'}{$oneFurniture.weight}{/if}" />
					</div>					
					<label class="col-md-2 col-form-label text-right"><span class="requiredMask">*</span>Đơn vị</label>
					{assign var = toId value = $clsISO->getUniqid()}
					<div class="col-md-4">
						<select class="form-control required" toId="{$toId}" name="unit_id">
							{$clsProperty->getSelectByProperty('_FURNITUREUNIT',$more_information.unit_id)}
						</select>
					</div>
				</div>
				<div class="form-group form-row">	
					<label class="col-md-2 col-form-label text-right"><span class="requiredMask">*</span>Danh mục</label>
					{assign var = toId value = $clsISO->getUniqid()}
					<div class="col-md-4">
						<select class="form-control required"  toId="{$toId}" name="cat_id">
							{$clsProperty->getSelectByProperty('_CATEGORYSFURNITURE',$oneFurniture.cat_id)}
						</select>
					</div>				
					<label class="col-md-2 col-form-label text-right">Giá (VNĐ)</label>
					<div class="col-md-4">
						<input type="text" class="form-control price-In" name="price" placeholder="10.000.000" value="{if $action eq '_edit'}{$oneFurniture.price}{/if}" />
					</div>	
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Kích thước (mm)</label>
					<div class="col-md-3">
						<input type="number" class="form-control" name="length" placeholder="Dài" value="{if $action eq '_edit'}{$more_information.length}{/if}" />
					</div>
					<div class="col-md-3">
						<input type="number" class="form-control" name="width" placeholder="Rộng" value="{if $action eq '_edit'}{$more_information.width}{/if}" />
					</div>
					<div class="col-md-3">
						<input type="number" class="form-control" name="height" placeholder="Cao" value="{if $action eq '_edit'}{$more_information.height}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">{$core->get_Lang('Image')}</label>
					<div class="col-xs-12 col-md-10">
						<div class="input-group">
							<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh" id="isoman_url_image" value="{$oneFurniture.image}">
							<div class="input-group-btn">
								<button class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="{$oneFurniture.image}" isoman_name="image" style="padding:9px 10px">{$core->makeIcon('image')}</button>
							</div>	
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Mô tả vật liệu</label>
					<div class="col-md-10">
						<textarea class="form-control w-100" rows="5" data-field="intro" name="intro" id="{$clsISO->getUniqid()}">{if $action eq '_edit'}{$oneFurniture.intro}{/if}</textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.furniture.save(this, event)" furniture_id="{$furniture_id}" class="btn btn-success">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>{$core->get_Lang('Close')}</span>
				</button>
			</div>
		</form>
	</div>
</div>
