<div class="modal-dialog modal-ipad">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<style>
			.docs-form .sec{ margin-bottom:18px; }
			.docs-form .sec:last-child{ margin-bottom:0; }
			.docs-form .sec-title{ font-weight:600; font-size:12px; color:#888; text-transform:uppercase; letter-spacing:.04em; margin:0 0 10px; padding-bottom:6px; border-bottom:1px solid #eee; }
		</style>
		{assign var = toId value = $clsISO->getUniqid()}
		<form class="d-none" enctype="multipart/form-data">
			<input id="{$toId}" class="select_file_{$toId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" charset="UTF-8" onChange="$Core.docs.upload_file(this, event)" name="upload_file[]" multiple {if !empty($more_information.folder_id)} folder_id="{$more_information.folder_id}"{/if} />
			<input id="{$toId}" class="select_image_{$toId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" charset="UTF-8" onChange="$Core.docs.upload_image(this, event)" name="upload_image"{if !empty($more_information.folder_id)} folder_id="{$more_information.folder_id}"{/if} />
		</form>
		<form method="post" action="">
			<div class="modal-body docs-form">

				{* ===== Nhóm 1: Tài liệu ===== *}
				<div class="sec">
					<div class="sec-title">Tài liệu</div>
					<div class="form-group">
						<label class="col-form-label">Tên tài liệu <span class="text-red">*</span></label>
						<input type="text" id="title_field_{$toId}" class="form-control required" name="title" value="{if $action eq '_edit'}{$oneItem.title}{/if}" placeholder="Tên tài liệu" />
					</div>
					<div class="form-group">
						<div class="d-flex align-items-center justify-content-between col-form-label">
							<span>Tài liệu đính kèm (link Drive / YouTube / file) <span class="text-red">*</span></span>
							{if !empty($more_information.folder_id)}
							<input type="hidden" name="folder_id" value="{$more_information.folder_id}" />
							<a href="javascript:void(0);" toId="{$toId}" class="js__docs_folder small" onClick="$Core.docs.delete_folder(this, event)" title="Xoá folder">Xoá folder</a>
							{else}
							<a href="javascript:void(0);" toId="{$toId}" class="js__docs_folder small" onClick="$Core.docs.create_folder(this, event)" title="Thêm folder">+ Tạo folder Drive</a>
							{/if}
						</div>
						<div class="input-group">
							<input type="text" id="content_file_{$toId}" class="form-control required" value="{if $action eq '_edit'}{$oneItem.content}{/if}" name="content" placeholder="Dán link hoặc bấm Tải lên" />
							<div class="input-group-btn">
								<button type="button" toId="{$toId}" onClick="$Core.docs.select_file(this, event)" class="btn btn-default">{$core->makeIcon('upload','Tải lên')}</button>
							</div>
						</div>
					</div>
					<div class="form-group mb-0">
						<label class="col-form-label">Mô tả</label>
						<textarea class="form-control" name="intro" rows="3" placeholder="Mô tả ngắn (tuỳ chọn)">{$more_information.intro}</textarea>
					</div>
				</div>

				{* ===== Nhóm 2: Phân loại & Dự án ===== *}
				<div class="sec">
					<div class="sec-title">Phân loại &amp; Dự án</div>
					<div class="form-group form-row">
						<div class="col-md-4">
							<label class="col-form-label">Danh mục</label>
							<select name="cat_id" class="form-control iso-select2">
								<option value="0">Chọn danh mục</option>
								{if $action eq '_add'}
									{$clsProperty->getListOption('_CATEGORY_DOCS',$cat_id)}
								{else}
									{$clsProperty->getListOption('_CATEGORY_DOCS',$oneItem.cat_id)}
								{/if}
							</select>
						</div>
						<div class="col-md-8">
							<label class="col-form-label">Dự án</label>
							<select name="project_id" onChange="$Core.docs.select_block(this, event)" toId="slb_Block_Id" class="form-control iso-select2">
								<option value="0">Chọn dự án</option>
								{foreach name=i from=$list_projects item = _project}
								<option value="{$_project.project_id}" {if $project_id eq $_project.project_id}selected{/if}>{$_project.title}</option>
								{/foreach}
							</select>
						</div>
					</div>
					<div class="form-group form-row mb-0">
						<div class="col-md-6">
							<label class="col-form-label">Phân khu</label>
							<select name="block_ids[]" id="slb_Block_Id" multiple data-placeholder="Chọn phân khu" onChange="$Core.docs.select_building(this, event)" toId="slb_Building_Id" class="form-control iso-select2">
								{if !empty($list_blocks)}
									{foreach from=$list_blocks item=_oBlock}
									<option value="{$_oBlock.property_id}" {if $clsISO->checkItemInArray($_oBlock.property_id,$block_ids)}selected{/if}>{$_oBlock.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						<div class="col-md-6">
							<label class="col-form-label">Tòa nhà</label>
							<select name="building_ids[]" id="slb_Building_Id" multiple data-placeholder="Chọn toà nhà" class="form-control iso-select2">
								{if !empty($list_buildings)}
									{foreach from=$list_buildings item=_oBuilding}
									<option value="{$_oBuilding.property_id}" {if $clsISO->checkItemInArray($_oBuilding.property_id,$building_ids)}selected{/if}>{$_oBuilding.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
					</div>
				</div>

				{* ===== Nhóm 3: Thông tin thêm ===== *}
				<div class="sec">
					<div class="sec-title">Thông tin thêm</div>
					<div class="form-group form-row mb-0">
						<div class="col-md-7">
							<label class="col-form-label">Từ khóa tìm kiếm</label>
							<input type="text" id="input-tags" class="input-tags" name="tags" placeholder="Nhập keyword, cách nhau dấu phẩy" value="{if $action eq '_edit'}{$oneItem.tags}{/if}" />
						</div>
						<div class="col-md-5">
							<label class="col-form-label">Ảnh đại diện</label>
							<div class="input-group">
								<span class="input-group-btn"><img src="{$more_information.image}" id="isoman_show_image" onerror="this.src='{$URL_IMAGES}/no-image.jpg'" class="border" style="width:34px;height:34px;object-fit:cover" /></span>
								<input class="form-control" id="isoman_hidden_image" name="image" value="{$more_information.image}" placeholder="Link ảnh" />
								<span class="input-group-btn"><a class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="{$more_information.image}" isoman_name="image"><i class="fa fa-image"></i></a></span>
							</div>
						</div>
					</div>
				</div>

				{* ===== Nhóm 4: Hiển thị ===== *}
				<div class="sec">
					<div class="sec-title">Hiển thị trên website</div>
					<div class="d-flex flex-wrap gap-3">
						<div class="d-flex align-items-center gap-2">
							<label class="switch mb-0"><input type="checkbox" {if $more_information.is_expanded eq '1'} checked{/if} name="is_expanded" value="1"><span class="slider round"></span></label>
							<span>Mở rộng</span>
						</div>
						<div class="d-flex align-items-center gap-2">
							<label class="switch mb-0"><input type="checkbox" {if $more_information.is_model eq '1'} checked{/if} name="is_model" value="1"><span class="slider round"></span></label>
							<span>Hiển thị nhà mẫu</span>
						</div>
						<div class="d-flex align-items-center gap-2">
							<label class="switch mb-0"><input type="checkbox" {if $more_information.is_handoverSpecs eq '1'} checked{/if} name="is_handoverSpecs" value="1"><span class="slider round"></span></label>
							<span>Hiển thị TCBG</span>
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">{$core->get_Lang('Close')}</button>
				<button type="button" onClick="$Core.docs.save(this, event)" project_meta_id="{$project_meta_id}" class="btn btn-success pull-right">Lưu lại</button>
				<button type="button" onClick="$Core.docs.save(this, event)" project_meta_id="{$project_meta_id}" class="btn btn-default pull-right mr-2 continue_add">Lưu + Thêm</button>
			</div>
		</form>
	</div>
</div>
