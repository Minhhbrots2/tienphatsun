{assign var=_uid value=$s.uid}
<div class="interior-type-card" data-uid="{$_uid}">
	<input type="hidden" name="rows[{$_uid}][row_id]" value="{$s.row_id}" />
	<div class="it-head">
		<div class="it-head-fields">
			<select class="form-control it-bedroom" name="rows[{$_uid}][bedroom_id]">
				<option value="0">— Loại căn —</option>
				{foreach from=$arrBedrooms item=b}
				<option value="{$b.property_id}"{if $s.bedroom_id eq $b.property_id} selected{/if}>{$b.title}</option>
				{/foreach}
			</select>
			<input type="text" class="form-control it-type" name="rows[{$_uid}][type_label]" value="{$s.type_label}" placeholder="Type (vd: Type 5) — để trống nếu loại căn chỉ 1 layout" />
		</div>
		<a href="javascript:void(0);" class="it-remove text-red" onClick="remove_interior_type(this, event)" title="Bỏ Type này">{$core->makeIcon('trash')}</a>
	</div>
	<div class="it-body row">
		<div class="col-md-6 it-cat">
			<label class="pm-label"><i class="fa fa-map-o"></i> Bóc mái (layout)</label>
			<input type="hidden" class="pm-json" uid="{$_uid}_bm" name="rows[{$_uid}][media_boc_mai]" value="{if $s.media_boc_mai}{$s.media_boc_mai|escape}{else}[]{/if}" />
			<div class="pm-tools">
				<div class="pm-row pm-folder-wrap">
					<input type="text" class="form-control input-sm pm-folder" uid="{$_uid}_bm" name="rows[{$_uid}][folder_boc_mai]" value="{$s.folder_boc_mai}" placeholder="Link FOLDER Google Drive (chia sẻ: Bất kỳ ai có link – Người xem)..." />
					<button type="button" class="btn btn-sm btn-primary pm-btn" onClick="$Core.project.progress_sync_drive(this, event)" uid="{$_uid}_bm"><i class="fa fa-cloud-download"></i> Đồng bộ</button>
				</div>
				<label class="btn btn-sm btn-default pm-upload-btn"><i class="fa fa-upload"></i> Upload
					<input type="file" accept="image/*" onchange="$Core.project.progress_upload_media(this, event)" uid="{$_uid}_bm" />
				</label>
				<div class="pm-row pm-link-wrap">
					<input type="text" class="form-control input-sm pm-link" uid="{$_uid}_bm" placeholder="Link lẻ (Drive / ảnh / YouTube)..." />
					<button type="button" class="btn btn-sm btn-default pm-btn" onClick="$Core.project.progress_add_link(this, event)" uid="{$_uid}_bm">Thêm</button>
				</div>
			</div>
			<div class="progress-media-grid pm-grid" uid="{$_uid}_bm"></div>
		</div>
		<div class="col-md-6 it-cat">
			<label class="pm-label"><i class="fa fa-bed"></i> Nội thất (căn mẫu)</label>
			<input type="hidden" class="pm-json" uid="{$_uid}_nt" name="rows[{$_uid}][media_noi_that]" value="{if $s.media_noi_that}{$s.media_noi_that|escape}{else}[]{/if}" />
			<div class="pm-tools">
				<div class="pm-row pm-folder-wrap">
					<input type="text" class="form-control input-sm pm-folder" uid="{$_uid}_nt" name="rows[{$_uid}][folder_noi_that]" value="{$s.folder_noi_that}" placeholder="Link FOLDER Google Drive (chia sẻ: Bất kỳ ai có link – Người xem)..." />
					<button type="button" class="btn btn-sm btn-primary pm-btn" onClick="$Core.project.progress_sync_drive(this, event)" uid="{$_uid}_nt"><i class="fa fa-cloud-download"></i> Đồng bộ</button>
				</div>
				<label class="btn btn-sm btn-default pm-upload-btn"><i class="fa fa-upload"></i> Upload
					<input type="file" accept="image/*" onchange="$Core.project.progress_upload_media(this, event)" uid="{$_uid}_nt" />
				</label>
				<div class="pm-row pm-link-wrap">
					<input type="text" class="form-control input-sm pm-link" uid="{$_uid}_nt" placeholder="Link lẻ (Drive / ảnh / YouTube)..." />
					<button type="button" class="btn btn-sm btn-default pm-btn" onClick="$Core.project.progress_add_link(this, event)" uid="{$_uid}_nt">Thêm</button>
				</div>
			</div>
			<div class="progress-media-grid pm-grid" uid="{$_uid}_nt"></div>
		</div>
	</div>
</div>
