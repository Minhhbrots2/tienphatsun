<div class="progress-card tr_attrs" id="{$uid}">
    <div class="pc-head">
        <span class="pc-no">{if $index}Mốc {$index}{else}Mốc mới{/if}</span>
        <div class="pc-fields">
            <div class="pc-field">
                <label class="pm-label">Thời gian <span class="text-red">*</span></label>
                <input class="form-control" name="attrs[{$uid}][progress_date]" list="progress-date-suggest" placeholder="VD: T4/2025, Quý II/2026" type="text" value="{$_Item.progress_date}" />
            </div>
            <div class="pc-field">
                <label class="pm-label">Tên phụ (tùy chọn)</label>
                <input class="form-control" name="attrs[{$uid}][title]" placeholder="vd: Cầu Thượng Cát" type="text" value="{$_Item.title}" />
            </div>
            <div class="pc-field pc-field--check">
                <label class="pm-label">Hiển thị</label>
                <input type="hidden" name="attrs[{$uid}][is_active]" value="0" />
                <input type="checkbox" name="attrs[{$uid}][is_active]" value="1" {if $_Item.is_active} checked {/if} />
            </div>
        </div>
        <a class="pc-del btn btn-default" href="javascript:void(0);" progress_id="{$uid}" uid="{$uid}" project_id="{$_Item.project_id}" block_id="{$_Item.block_id}" building_id="{$_Item.building_id}" onClick="delete_progress(this, event)" title="Xóa mốc">{$core->makeIcon('trash')}</a>
    </div>
    <div class="pc-body">
        <label class="pm-label">Mô tả mốc tiến độ</label>
        <textarea class="form-control isoTextArea" id="desc_{$uid}" data-name="attrs[{$uid}][description]" rows="4" placeholder="Nội dung cập nhật tiến độ (hiển thị ở website)...">{$_Item.description|default:''}</textarea>
        <input type="hidden" class="pm-json" uid="{$uid}" name="attrs[{$uid}][media]" value="{$media_json|default:'[]'|escape}" />
        <label class="pm-label" style="margin-top:10px">Hình ảnh / Video</label>
        <div class="pm-tools">
            <div class="pm-row pm-folder-wrap">
                <input type="text" class="form-control input-sm pm-folder" uid="{$uid}" placeholder="Dán link FOLDER Google Drive (chia sẻ: Bất kỳ ai có link – Người xem)..." />
                <button type="button" class="btn btn-sm btn-primary pm-btn" onClick="$Core.project.progress_sync_drive(this, event)" uid="{$uid}"><i class="fa fa-cloud-download"></i> Đồng bộ</button>
            </div>
            <label class="btn btn-sm btn-default pm-upload-btn"><i class="fa fa-upload"></i> Upload ảnh
                <input type="file" accept="image/*" onchange="$Core.project.progress_upload_media(this, event)" uid="{$uid}" />
            </label>
            <div class="pm-row pm-link-wrap">
                <input type="text" class="form-control input-sm pm-link" uid="{$uid}" placeholder="Dán link lẻ (YouTube / Drive / ảnh)..." />
                <button type="button" class="btn btn-sm btn-default pm-btn" onClick="$Core.project.progress_add_link(this, event)" uid="{$uid}">Thêm</button>
            </div>
        </div>
        <div class="progress-media-grid pm-grid" uid="{$uid}"></div>
    </div>
</div>
