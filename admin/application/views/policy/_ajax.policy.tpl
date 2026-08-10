<div class="modal-dialog modal-ipad">
	<form class="modal-content" method="post" action="" enctype="multipart/form-data">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{if $action eq '_add'}Thêm mới{else}Chỉnh sửa{/if} CSBH {$clsProperty->getTitle($block_type)}</strong></h3>
		</div>
		<div class="modal-body">
			<div class="form-group form-row">
				<div class="col-md-9">
					<label class="col-form-label">Tên CSBH <span class="text-red">*</span></label>
					<input type="text" class="form-control required" placeholder="Nhập tên..." name="title" value="{if $action eq '_edit'}{$oneItem.title}{/if}" />
				</div>
				<div class="col-md-3">
					<label class="col-form-label">Ngày áp dụng <span class="text-red">*</span></label>
					<input type="text" class="form-control datepicker required" placeholder="dd/mm/yy" name="ms_date" value="{$clsISO->convertTimeToText($oneItem.ms_date)}" />
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-md-4">
					<label class="col-form-label">Link CSBH <span class="text-red">*</span></label>
					<input type="text" class="form-control" placeholder="Nhập link CSBH" name="link_ns" value="{if $action eq '_edit'}{$oneItem.link_ns}{/if}" />
				</div>
				<div class="col-md-4">
					<label class="col-form-label">Link PTG <span class="text-red">*</span></label>
					<input type="text" class="form-control" placeholder="Nhập link PTG" name="link_ms" value="{if $action eq '_edit'}{$oneItem.link_ms}{/if}" />
				</div>
				<div class="col-md-4">
					<label class="col-form-label">Loại quỹ <span class="text-red">*</span></label>
					<select name="applicable_fund_type" id="" class="form-control form-select required">
						<option value="0" {if $action eq '_edit' && $oneItem.applicable_fund_type eq '0'}selected{/if}>Sơ cấp</option>
						<option value="1" {if $action eq '_edit' && $oneItem.applicable_fund_type eq '1'}selected{/if}>Thứ cấp</option>
					</select>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-md-8">
					<label class="col-form-label">ID phiếu thính giá<span class="text-red">*</span></label>
					<div class="input-group">
						{assign var = toId value = $clsISO->getUniqid()}
						<input type="text" class="form-control price_sheet_file_{$toId}" 
							value="{if $action eq '_edit'}{$more_information.price_sheet_id}{/if}" name="price_sheet_id" placeholder="ID bảng tính" />
						<input type="file" name="upload_file" onChange="upload_price_sheet(this, event)" 
							class="d-none select_price_sheet_{$toId}" id="{$toId}" />
						<div class="input-group-btn">
							<button type="button" toId="{$toId}" onClick="select_price_sheet(this, event)" 
								class="btn btn-default">{$core->makeIcon('upload', 'Tải Excel')}</button>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<label class="col-form-label">Ô điền mã căn<span class="text-red">*</span></label>
					<input type="text" class="form-control" 
						value="{if $action eq '_edit'}{$more_information.spreadsheet_cell_stock}{/if}" 
						placeholder="BG!C1" name="spreadsheet_cell_stock" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label">Mô tả</label>
				<textarea class="form-control" placeholder="Mô tả" name="intro" rows="2" cols="255">{if $action eq '_edit'}{$oneItem.intro}{/if}</textarea>
			</div>
			<fieldset>
				<legend>Áp dụng</legend>
				<div class="group_scopes">
				{if !empty($list_scopes)}
					{foreach name=k from=$list_scopes key=uid item = _oScope}
					{assign var = list_blocks value = $_oScope.list_blocks}
					{assign var = list_buildings value = $_oScope.list_buildings}
					<div class="scope_item scope_item_{$uid}">
						<div class="form-group form-row">
							<div class="col-md-6">
								<label class="col-form-label">Chọn dự án</label>
								<select uid="{$uid}" onchange="load_option_block(this,event)" name="scope[{$uid}][project_id]" toId="block_{$uid}" class="form-control iso-select2 required">
									<option>Chọn dự án</option>
									{foreach name=i from=$list_projects item = project}
									<option{if $project.project_id eq $_oScope.project_id} selected{/if} value="{$project.project_id}">{$project.title}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-md-6">
								<label class="col-form-label">Chọn phân khu</label>
								{if $block_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
								<select uid="{$uid}" id="block_{$uid}" name="scope[{$uid}][block_id][]" multiple="multiple" class="form-control required iso-select2">
									<option>Chọn phân khu</option>
									{if !empty($list_blocks)}
										{foreach name=i from=$list_blocks item = _oBlock}
										<option{if $_oBlock.selected eq '1'} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
										{/foreach}
									{/if}
								</select>
								{else}
								<select uid="{$uid}" id="block_{$uid}" onchange="load_option_building(this,event)" toId="building_{$uid}" name="scope[{$uid}][block_id]" class="form-control required iso-select2">
									<option>Chọn phân khu</option>
									{if !empty($list_blocks)}
										{foreach name=i from=$list_blocks item = _oBlock}
										<option{if $_oScope.block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">({$_oBlock.property_code}) {$_oBlock.title}</option>
										{/foreach}
									{/if}
								</select>
								{/if}
							</div>
						</div>
						{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
							<div id="building_group_{$uid}" class="form-group">
								<label class="col-form-label">Chọn tòa áp dụng</label>
								<select uid="{$uid}" multiple="multiple" id="building_{$uid}" data-placeholder="Chọn tòa" 
								name="scope[{$uid}][building_id][]" class="form-control required iso-select2">
									{if !empty($list_buildings)}
										{foreach name=i from=$list_buildings item = _oBuilding}
										<option{if $_oBuilding.selected} selected{/if} value="{$_oBuilding.property_id}">{$_oBuilding.title}</option>
										{/foreach}
									{/if}
								</select>
							</div>
						{/if}
						{if !$smarty.foreach.k.first}
						<div class="d-flex">
							<button type="button" uid="{$uid}" onClick="delete_scope(this,event)" 
							class="btn btn-sm btn-default">{$core->makeIcon('trash','Xóa')}</button>
						</div>
						{/if}
					</div>
					{/foreach}
				{else}
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="scope_item scope_item_{$uid}">
						<div class="form-group form-row">
							<div class="col-md-6">
								<label class="col-form-label">Chọn dự án</label> {$project_id}
								<select uid="{$uid}" onchange="load_option_block(this,event)" name="scope[{$uid}][project_id]" toId="block_{$uid}" class="form-control iso-select2 required" data-error="Chưa chọn dự án">
									<option value="0">Chọn dự án</option>
									{foreach name=i from=$list_projects item = project}
									<option {if $project_id eq $project.project_id} selected{/if} value="{$project.project_id}">{$project.title}</option>
									{/foreach}
								</select>
							</div>
							<div class="col-md-6">
								<label class="col-form-label">Chọn phân khu</label>
								{if $block_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
								<select uid="{$uid}" id="block_{$uid}" toId="building_{$uid}" name="scope[{$uid}][block_id][]" multiple="multiple" class="form-control required iso-select2" data-error="Chưa chọn phân khu">
									<option value="0">Chọn phân khu</option>
									{if !empty($list_blocks)}
										{foreach name=i from=$list_blocks item = _oBlock}
										<option{if $block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
										{/foreach}
									{/if}
								</select>
								{else}
								<select uid="{$uid}" id="block_{$uid}" onchange="load_option_building(this,event)" toId="building_{$uid}" name="scope[{$uid}][block_id]" class="form-control required iso-select2" data-error="Chưa chọn phân khu">
									<option value="0">Chọn phân khu</option>
									{if !empty($list_blocks)}
										{foreach name=i from=$list_blocks item = _oBlock}
										<option{if $block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
										{/foreach}
									{/if}
								</select>
								{/if}
							</div>
						</div>
						{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
						<div id="building_group_{$uid}" class="form-group {if $action eq '_add' && empty($building_id)}d-none{/if}">
							<label class="col-form-label">Chọn tòa áp dụng</label>
							<select uid="{$uid}" multiple="multiple" id="building_{$uid}" data-placeholder="Chọn tòa nhà" 
							name="scope[{$uid}][building_id][]" class="form-control iso-select2" data-error="Chưa chọn tòa nhà">
								{if !empty($list_buildings)}
									{foreach name=i from=$list_buildings item = _oBuilding}
									<option {if $building_id eq $_oBuilding.property_id} selected{/if} value="{$_oBuilding.property_id}" >{$_oBuilding.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						{/if}
					</div>
				{/if}
				</div>
			</fieldset>
			<button type="button" onClick="add_scope(this, event)" block_type="{$block_type}" class="btn btn-default text-danger">Thêm áp dụng</button>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success pull-right" block_type="{$block_type}" onClick="pop_save_policy(this, event)" 
				policy_id="{$policy_id}">Cập nhật</button>
			<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
				{$core->get_Lang('Close')}
			</button>
		</div>
	</form>
</div>
<style type="text/css">
	.datepicker{ max-width:100%}
	.form-group{ margin-bottom:10px !important;}
	.scope_item{ padding:10px; margin-bottom:5px; border:1px solid #DDD; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; -khtml-border-radius:3px; }
</style>