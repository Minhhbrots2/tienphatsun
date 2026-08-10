<div class="modal-dialog modal-ipad">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		{assign var = toId value = $clsISO->getUniqid()}
		<form class="d-none" enctype="multipart/form-data">
			<input id="{$toId}" class="select_file_{$toId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" charset="UTF-8" 
				   onChange="$Core.docs.upload_file(this, event)" name="upload_file" />
		</form>
		<form method="post" action="">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Tên tài liệu</label>
					<input type="text" id="title_field_{$toId}" class="form-control required" name="title" value="{if $action eq '_edit'}{$oneItem.title}{/if}" placeholder="Tên tài liệu" />
				</div>
				<div class="form-group">
					<label class="col-form-label">Tên tài liệu đầy đủ</label>
					<input type="text" id="title_search_field_{$toId}" class="form-control required" name="title_search" value="{if $action eq '_edit'}{$oneItem.title_search}{/if}" placeholder="Tên tài liệu" />
				</div>
				<div class="form-group">
					<label class="col-form-label">Tài liệu đính kèm</label>
					<div class="input-group">
						<input type="text" id="content_file_{$toId}" class="form-control required" value="{if $action eq '_edit'}{$oneItem.content}{/if}" 
							   name="content" placeholder="Tài liệu đính kèm" />
						<div class="input-group-btn">
							<button type="button" toId="{$toId}" onClick="$Core.docs.select_file(this, event)" 
							class="btn btn-default">{$core->makeIcon('upload','Chọn')}</button>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label mb-1">Từ khóa tìm kiếm:</label>
					<input type="text" id="input-tags" class="input-tags" name="tags" placeholder="Nhập keyword" value="{if $action eq '_edit'}{$oneItem.tags}{/if}" />
				</div>
				{if $action eq '_add'}
				<div class="form-group form-row">
					<div class="col-xs-12 col-md-4">
						<label class="col-form-label">Dự án</label>
						<select name="project_id" onChange="$Core.docs.select_block(this, event)" toId="slb_Block_Id" class="form-control">
							<option value="0">Chọn dự án</option>
							{foreach name=i from=$list_projects item = _project}
							<option value="{$_project.project_id}">{$_project.title}</option>
							{/foreach}
						</select>
					</div>
					<div class="col-xs-12 col-md-8">
						<label class="col-form-label">Phân khu</label>
						<select name="block_ids[]" id="slb_Block_Id" multiple onChange="$Core.docs.select_building(this, event)" 
						toId="slb_Building_Id" class="form-control iso-select2">
							<option>Chọn phân khu</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label">Tòa nhà</label>
					<select name="building_ids[]" id="slb_Building_Id" multiple class="form-control iso-select2">
						<option>Chọn tòa nhà</option>
					</select>
				</div>
				{/if}
				<div class="form-group">
					<label class="col-form-label">Nội dung</label>
					<textarea class="isoTextArea form-control" style="width:100%" data-name="intro" id="{$clsISO->getUniqid()}" 
							  rows="5" cols="255">{if $action eq '_edit'}{$more_information.intro}{/if}</textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.docs.save(this, event)" project_meta_id="{$project_meta_id}" 
				class="btn btn-primary continue_add pull-right">Lưu + Thêm</button>
				<button type="button" onClick="$Core.docs.save(this, event)" project_meta_id="{$project_meta_id}" 
				class="btn btn-success pull-right mr-2">Lưu lại</button>
				<button type="button" class="btn btn-default pull-right mr-2" data-dismiss="modal">
					{$core->get_Lang('Close')}
				</button>
			</div>
		</form>
	</div>
</div>