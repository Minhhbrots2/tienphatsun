<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Tên tài liệu<span class="text-red">*</span></label>
					<input type="text" class="form-control required" placeholder="Nhập tên tài liệu" name="name" value="{$oneDocShare.name}" />
				</div>
				<div class="form-group">
					<label class="col-form-label">Loại tài liệu<span class="text-red">*</span></label>
					<select class="form-control required" name="type_id">
						{$clsISO->getSelectByPropertyTypeTitle('_DOCS_SHARE', $oneDocShare.type_id, 'Loại tài liệu')}
					</select>
				</div>
				<div class="form-group">
					<label class="col-form-label">Mô tả</label>
					<textarea class="form-control" name="content" rows="4">{$oneDocShare.content}</textarea>
				</div>
				<div class="form-group">
					<label class="d-block col-form-label">File đính kèm</label>
					{if $action eq '_edit' && !empty($oneDocShare.file_url)}
					<a class="download" target="_blank" href="{$oneDocShare.file_url}">
						{$core->makeIcon('download', $oneDocShare.file_name)}
					</a>
					{/if}
					<input type="file" name="attachment" class="form-control" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success clickToSaveDocShare pull-right" project_id="{$project_id}" doc_share_id="{$doc_share_id}">Lưu lại</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>



