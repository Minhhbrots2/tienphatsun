<div class="modal-dialog modal-md" style="max-width: 600px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		{assign var = gId value = $clsISO->getUniqid()}
		<form class="d-none" enctype="multipart/form-data">
			<input id="{$gId}" class="select_file_{$gId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" charset="UTF-8" 
				onChange="$Core.service.upload_file(this, event)" name="upload_file" />
			<input id="{$gId}" class="select_heic_file_{$gId}" type="file" charset="UTF-8" 
				onChange="$Core.service.upload_heic_file(this, event)" name="upload_file" />
		</form>
		<form method="post" action="" enctype="multipart/form-data" id="frmAddService">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Tên</label>
					<div class="col-md-4">
						<input class="form-control required" placeholder="Họ và tên" maxlength="255" name="name" 
							   value="{if $action eq '_edit'}{$oneService.name}{/if}" />
					</div>
					<label class="col-md-2 col-form-label text-right">Điện thoại</label>
					{assign var = toId value = $clsISO->getUniqid()}
					<div class="col-md-4">
						<input class="form-control required" name="phone" placeholder="Nhập điện thoại..." value="{if $action eq '_edit'}{$more_information.phone}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Dịch vụ</label>
					<div class="col-md-4">
						<select class="form-control iso-select2" toId="{$toId}" name="cat_id[]" multiple="multiple">
							{$clsProperty->getSelectByPropertyV2('_CATEGORYSERVICES',$cat_ids)}
						</select>
					</div>
					<label class="col-md-2 col-form-label text-right">Tags</label>
					<div class="col-md-4">
						<input type="text" id="input-tags" class="form-control input-tags" name="tags" placeholder="Nhập tags" value="{if $action eq '_edit'}{$oneService.tags}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Dự án</label>
					<div class="col-md-10">
						<select name="project_id" onChange="$Core.service.select_block(this, event)" toId="slb_Block_Id" class="form-control">
							<option value="0">Chọn dự án</option>
							{foreach name=i from=$list_projects item = _project}
							<option{if $oneService.project_id eq $_project.project_id} selected{/if} value="{$_project.project_id}">{$_project.title}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Phân khu</label>
					<div class="col-md-4">
						<select name="block_id" id="slb_Block_Id" onChange="$Core.service.select_building(this, event)" 
						toId="slb_Building_Id" class="form-control iso-select2">
							<option>Chọn phân khu</option>
							{if !empty($list_blocks)}
								{foreach from=$list_blocks item = _oBlock}
								<option{if $oneService.block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
					<label class="col-md-2 col-form-label text-right">Tòa nhà</label>
					<div class="col-md-4">
						<select name="building_id" id="slb_Building_Id" class="form-control iso-select2" onchange="$Core.service.loadAddress()">
							<option>Chọn tòa nhà</option>
							{if !empty($list_buildings)}
								{foreach from=$list_buildings item = _oBuilding}
								<option{if $oneService.building_id eq $_oBuilding.property_id} selected{/if} value="{$_oBuilding.property_id}">{$_oBuilding.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>	
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Địa chỉ</label>
					<div class="col-md-10">
						<input class="form-control" name="address" placeholder="Nhập địa chỉ..." value="{if $action eq '_edit'}{$more_information.address}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Hình ảnh</label>
					<div class="col-md-10">
						<div class="input-group">
							<input type="text" id="content_file_{$gId}" class="form-control" name="image" 
								   value="{$more_information.image}" placeholder="Tài liệu đính kèm" onChange="$Core.service.upload_file(this, event)"/>
							<div class="input-group-btn">
								<button type="button" toId="{$gId}" onClick="$Core.service.select_file(this, event)" 
								class="btn btn-default">{$core->makeIcon('upload','Chọn')}</button>
								<!--<button type="button" toId="{$gId}" onClick="$Core.shop.select_heic_file(this, event)" 
								class="btn btn-default">{$core->makeIcon('upload','Chọn HEIC')}</button> -->
							</div>
						</div>
					</div>
				</div>
				{*<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Dự án</label>
					<div class="col-md-4">
						<select name="project_id" onChange="$Core.service.select_block(this, event)" toId="slb_Block_Id" class="form-control">
							<option value="0">Chọn dự án</option>
							{foreach name=i from=$list_projects item = _project}
							<option{if $oneService.project_id eq $_project.project_id} selected{/if} value="{$_project.project_id}">{$_project.title}</option>
							{/foreach}
						</select>
					</div>
					<label class="col-md-2 col-form-label text-right">Phân khu</label>
					<div class="col-md-4">
						<select name="block_id" id="slb_Block_Id" onChange="$Core.service.select_building(this, event)" 
						toId="slb_Building_Id" class="form-control iso-select2">
							<option>Chọn phân khu</option>
							{if !empty($list_blocks)}
								{foreach from=$list_blocks item = _oBlock}
								<option{if $oneService.block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</div>
				<div class="form-group form-row">					
					<label class="col-md-2 col-form-label text-right">Tòa nhà</label>
					<div class="col-md-4">
						<select name="building_id" id="slb_Building_Id" class="form-control iso-select2">
							<option>Chọn tòa nhà</option>
							{if !empty($list_buildings)}
								{foreach from=$list_buildings item = _oBuilding}
								<option{if $oneService.building_id eq $_oBuilding.property_id} selected{/if} value="{$_oBuilding.property_id}">{$_oBuilding.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>					
					<label class="col-md-2 col-form-label text-right">Mã căn</label>
					<div class="col-md-4">
						<input class="form-control" onchange="$Core.service.check_stock_code(this, event)" placeholder="Mã căn" maxlength="255" name="stock_code" value="{if $action eq '_edit'}{$more_information.stock_code}{/if}" id="stock_code"/>
					</div>
				</div>*}
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Nội dung</label>
					<div class="col-md-10">
						<textarea class="form-control w-100" rows="5" data-field="intro" name="intro" id="{$clsISO->getUniqid()}">{if $action eq '_edit'}{$more_information.intro}{/if}</textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.service.save(this, event)" service_id="{$service_id}" class="btn btn-success">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>{$core->get_Lang('Close')}</span>
				</button>
			</div>
		</form>
	</div>
</div>
