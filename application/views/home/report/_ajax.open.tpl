<div class="modal-dialog modal-dialog-centered">
	<form class="modal-content" id="frmIssue" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title">
				{if $action eq '_add'}Thêm{else}Chỉnh sửa{/if} báo cáo ngày<br />
				<span class="text-danger fs-13">
					{$clsISO->makeIcon('bx-user-plus', $clsProfile->getFullName($profile_id,$oneProfile))}
					- 
					{$clsISO->makeIcon('bx-timer', $clsISO->convertTimeToText($smarty.now, true))}
				</span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="alert alert-info mb-2">
				<span>Báo cáo công việc hàng ngày từ 18:00 hôm trước - 09:00 sáng ngày hôm sau</span>
			</div>
			<div class="form-row mb-2">
				<div class="col-6 col-md-4 mb-2 mb-lg-0">
					<label for="report_code" class="form-label">Mã báo cáo</label>
					<input type="text" id="report_code" name="report_code" class="form-control required" placeholder="Mã GD" value="{if $action eq '_add'}{$clsReport->genCode()}{else}{$oneReport.report_code}{/if}">
				</div>
				<div class="col-6 col-md-4">
					<label for="report_date" class="form-label">Ngày báo cáo</label>
					<input type="date" id="report_date" readonly name="report_date" class="form-control required" 
					placeholder="dd/mm/yy" value="{$oneReport.report_date|date_format:'%Y-%m-%d'}">
				</div>
			</div>
			{if !empty($list_cols)}
			<div class="form-group mb-2">
				<label class="form-label mb-1">Nội dung</label>
				<div class="clearfix"></div>
				<div class="table-wrapper">
					<table class="table table-bordered">
						<thead><tr>
							{if $deviceType ne 'phone'}
							<th width="3%" class="align-center bg-lighter text-center">No.</th>{/if}
							<th class="align-center bg-lighter text-left">Tiêu chí</th>
							<th width="{$column_width}%" class="align-center bg-lighter text-center">Kết quả</th>
							{if $deviceType eq 'computer'}
							<th class="align-center bg-lighter text-left">Đơn vị</th>
							{/if}
						</tr></thead>
						{foreach from=$list_cols name=i item = _oF}
						{assign var = _prop_id value = $_oF.property_id}
						<tr>
							{if $deviceType ne 'phone'}
							<td class="text-center">{$smarty.foreach.i.iteration}</td>{/if}
							<td class="text-left">{$_oF.$title_field|strip_tags}</td>
							<td><input type="text" name="report_store[{$_prop_id}]" data-min="0" data-max="1000" data-step="1" 
								class="form-control js__input-field-{$_prop_id} form-control-sm{if $_prop_id eq $smarty.const._REPORT_COLUMN_ADS_ID} price-In{/if} spinner required numberonly" value="{if $action eq '_edit'}{$clsReport->getValue($_prop_id, $oneReport.report_store,0)}{else}0{/if}" onClick="this.select()" />
							</td>
							{if $deviceType eq 'computer'}
							<td class="text-left">{$_oF.unit_name}</td>
							{/if}
						</tr>
						{/foreach}
					</table>
				</div>
			</div>
			{/if}
			{if $deviceType eq 'computer'}
			<div class="form-group mb-2">
				<label class="form-label mb-1">Ghi chú</label>
				<div class="clearfix"></div>
				<textarea placeholder="Nhập nội dung" name="content_notes" class="form-control" rows="1"></textarea>
			</div>
			{/if}
			<div class="form-group">
				<label class="form-label mb-1">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
					{if !empty($oneReport.attachments)}
						{foreach name=i from = $oneReport.attachments item = _oFile}
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
			<input type="hidden" name="submit" value="Update" />
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" tp="{$tp}" holderG="save" report_id="{$report_id}" 
			onClick="$Core.global.report.pop_save_report(this, event)" class="btn flex-fill btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
