<div class="modal-dialog modal-dialog-centered">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Sửa{/if} File đính kèm</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-3">
				<label class="form-label mb-1">Mô tả</label>
				<div class="input-group input-group-merge">
					<span class="input-group-text"><i class="bx bx-comment"></i></span>
					<input type="text" name="description" class="form-control required" value="{if $action eq '_edit'}{$oneFile.description}{/if}" placeholder="Mô tả">
				</div>
			</div>
			<div class="form-group mb-3">
				<label class="form-label mb-1">Loại file</label>
				<div class="input-group">
					{assign var = toId value = $clsISO->getUniqid()}
					<select id="{$toId}" class="form-control required" name="group_id">
						{$clsProperty->getSelectByProperty('_FILE_TYPE',$oneFile.billing_type)}
					</select>
					<button type="button" toId="{$toId}" property_type="_FILE_TYPE" onClick="open_property(this, event)" class="btn btn-icon btn-outline-primary"><i class="bx bx-plus"></i></button>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
					{if !empty($oneFile.attachments)}
						{foreach name=i from = $oneFile.attachments item = _oFile}
						<div class="MultiFile-label">
							<a class="MultiFile-remove" href="javascript:void(0)" data-url="{$_oFile}">x</a> 
							<span><span class="MultiFile-label" title="{$_oFile}">
								<span class="MultiFile-title">{$_oFile}</span></span>
							</span>
						</div>
						{/foreach}
					{/if}
				</div>
				<div class="clearfix"></div>
				<input name="attachments[]" type="file" multiple="multiple" class="maxsize-10240" id="attachments_{$uid}" />
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="hid" value="upload" />
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" file_id="{$file_id}" customer_id="{$customer_id}" onClick="$Core.crm.save_file(this, event)" 
			class="btn flex-fill btn-primary">Lưu lại</button>
		</div>
	</form>
</div>