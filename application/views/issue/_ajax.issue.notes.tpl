{assign var = gid value = $clsISO->getUniqid()}
{assign var = upload_id value = $clsISO->getUniqid()}
<div class="modal-dialog">
	<form class="d-none" action="" method="POST" enctype="multipart/form-data">
	    <input id="issue_upload_file_{$upload_id}" uid="{$upload_id}" type="file" class="issue_upload_file" 
		name="attachments[]" multiple="multiple" />
	</form>
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">
				{if $tp eq 'edit'}Chỉnh sửa{else}Phản hồi{/if}
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if $tp eq 'quote'}
			<div class="p-3 mb-2 bg-lighter rounded-2">
				<h4 class="fs-14 mb-2">Nội dung phàn hồi</h4>
				{if !empty($oneIssueNotes.html_change)}
				<ul class="pl-3">{$oneIssueNotes.html_change}</ul>
				{/if}
				{$oneIssueNotes.content}
			</div>
			{/if}
			<div class="textarea">
				<textarea id="{$clsISO->getUniqid()}" name="content" data-height="50" rows="4" cols="255" class="hasIsoRedactor" data-placeholder="Nhập nội dung bình luận...">{if $tp eq 'edit'}{$oneIssueNotes.content}{/if}</textarea>
			</div>
			<div class="d-flex align-items-center justify-content-between mt-2">
				<div class="toolbar d-flex align-items-center">
					<div class="mr-2">
						<a data-bs-toggle="tooltip" uid="{$upload_id}" onClick="$Core.issue.issue_notes_upload(this, event)" issue_id="{$issue_id}" issue_note_id="{$issue_note_id}" data-bs-offset="0,4" data-bs-placement="top" title="Đính kèm file" href="javascript:void(0)"><img src="{$smarty.const.ICON_ATTACHMENT}" /></a>
					</div>
					<div class="mr-2">
						
						<a data-bs-toggle="tooltip" gid="{$gid}" onClick="$Core.issue.set_mention_list(this,event)" data-bs-offset="0,4" data-bs-placement="top" title="Gắn thẻ" href="javascript:void(0)"><img src="{$smarty.const.ICON_MENTION}" /></a>
					</div>
				</div>
			</div>
			<div id="{$gid}" class="form-group{if !empty($mention_list_arrs)}{else} d-none{/if} pt-2">
				<select multiple="true" class="iso-selectizeNotSearch" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" name="mention_list[]" data-placeholder="Người giao" data-field="user_id" data-allow-clear="true" data-optgroup="false">
					{if !empty($mention_list_arrs)}
						{foreach from=$mention_list_arrs item = user_id}
						<option value="{$user_id}" selected>{$clsProfile->getIndentity($user_id, false)}</option>
						{/foreach}
					{/if}
				</select>		
			</div>
			<div id="issue_attachments_file_{$upload_id}" class=" pt-2 MultiFile-preview">
				{$html_attachments}
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" tp="{$tp}" issue_id="{$issue_id}" issue_note_id="{$issue_note_id}"
			onClick="$Core.issue.pop_save_issue_notes(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	.select2-container--open{
		z-index:9999 !important;
	}
</style>
{/literal}