{literal}
<style>
.it-toolbar{display:flex;gap:8px;align-items:center;margin-bottom:14px;flex-wrap:wrap}
.it-note{background:#fff7e6;border:1px solid #ffe0a3;color:#8a6d3b;padding:8px 12px;border-radius:6px;margin-bottom:12px;font-size:13px}
.interior-type-card{border:1px solid #e6e6e6;border-radius:8px;margin-bottom:14px;background:#fff}
.interior-type-card .it-head{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:8px 12px;background:#f7f7f8;border-bottom:1px solid #eee;border-radius:8px 8px 0 0}
.interior-type-card .it-head-fields{display:flex;gap:8px;flex:1;flex-wrap:wrap}
.interior-type-card .it-bedroom{max-width:160px}
.interior-type-card .it-type{max-width:280px}
.interior-type-card .it-body{padding:12px}
.interior-type-card .it-cat{margin-bottom:6px}
.interior-type-card .it-cat .pm-label{font-weight:600;display:block;margin-bottom:6px}
.it-empty{padding:24px;text-align:center;border:1px dashed #ddd;border-radius:8px}
</style>
{/literal}
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>Ảnh căn hộ theo loại — {$level_label}{if $parent_title}: {$parent_title}{/if}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				{if $inherit_note}<div class="it-note">{$inherit_note}</div>{/if}
				<div class="it-toolbar">
					<button type="button" class="btn btn-default" onClick="add_interior_type(this, event)">+ Thêm Type</button>
					<small class="text-muted">Mỗi Type = 1 loại căn + nhãn Type. Dán link Google Drive folder để đọc ảnh tự động, hoặc Upload / dán link lẻ.</small>
				</div>
				<div class="it-holder" id="it_holder">
					{if !empty($sections)}
						{foreach from=$sections item=sec}
						{$core->build('_ajax.interior_type.tpl', ['s'=>$sec, 'arrBedrooms'=>$arrBedrooms, 'core'=>$core])}
						{/foreach}
					{else}
						<div class="it-empty text-muted">Chưa có Type nào. Bấm "+ Thêm Type" để thêm.</div>
					{/if}
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="save_interior_ns(this, event)" project_id="{$project_id}" building_id="{$building_id}" block_id="{$block_id}">Lưu lại</button>
				<button type="button" class="btn btn-default mr-2 pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>
