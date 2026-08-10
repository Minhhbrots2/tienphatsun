<div class="modal-dialog modal-ipad-xl">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header border-bottom d-flex align-items-center justify-content-between">
			<h5 class="modal-title" id="modalTopTitle">Thêm mới mục tiêu</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Tên mục tiêu</label>
				<input type="text" class="form-control fw-bold autofocus required" data-val-required="Bạn chưa nhập vào tên công việc" name="title" maxlength="255" placeholder="Tên mục tiêu" value="{$oneItem.title}" />
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label class="form-label mb-1">Phòng ban</label>
					<select name="department_id" class="form-control form-select required">
						{if $clsISO->checkPermissionGroup('DIRECTOR')}
							{$clsISO->getSelectByPropertyTypeNotTitle('_DEPARTMENT', $oneItem.department_id)}
						{else}
						<option value="{$department_id}" selected>{$clsProperty->getTitle($department_id)}</option>
						{/if}
					</select>
				</div>
				<div class="col-6 col-md-3">
					<label class="form-label mb-1">Độ ưu tiên</label>
					{assign var = toId value = $clsISO->getUniqid()}
					<select id="{$toId}" class="form-control form-select required" name="priority_id">
						{$clsProperty->getSelectByProperty('_ISSUE_PRIORITY',$oneItem.priority_id)}
					</select>
				</div>
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label class="form-label mb-1">Thời gian thực hiện</label>
					<input type="datetime-local" class="form-control required" name="start_date" placeholder="Từ ngày" value="{$oneItem.start_date|date_format:'%Y-%m-%dT%H:%M'}" />
				</div>
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label class="form-label mb-1">tới</label>
					<input type="datetime-local" class="form-control required" name="end_date" placeholder="Tới ngày" value="{$oneItem.end_date|date_format:'%Y-%m-%dT%H:%M'}" />
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Nội dung</label>
				<textarea id="{$clsISO->getUniqid()}" class="form-control isoTextArea" rows="2" height="150px" 
					cols="255" data-name="content">{$oneItem.content}</textarea>
			</div>
			<div class="form-group attachments">
				<label class="form-label mb-1">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
				{if !empty($oneNews.attachments)}
					{foreach name=i from = $oneNews.attachments item = _oFile}
					<div class="MultiFile-label">
						<a class="MultiFile-remove" href="javascript:void(0)" onclick="$Core.global.news.removeFile(this,event)" news_id="{$oneNews.news_id}" data-url="{$_oFile}">x</a> 
						<span><span class="MultiFile-label" title="{$_oFile}">
							<span class="MultiFile-title">{$_oFile.url}</span></span>
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
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Đóng</button>
			<button type="button" issue_target_id="{$issue_target_id}" class="btn btn-primary" 
			title="Lưu lại" onClick="$Core.global.issue.save_issue_target(this, event)"><i class="bx bx-check"></i> Lưu lại</button>
		</div>
	</form>
</div>
