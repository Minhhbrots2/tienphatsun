<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:57:43
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.progress_item.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a769b27452db3_32319077',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '07a1050072062748d290bcda4f9277c46fc7af75' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.progress_item.tpl',
      1 => 1784691721,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a769b27452db3_32319077 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="progress-card tr_attrs" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
    <div class="pc-head">
        <span class="pc-no"><?php if ($_smarty_tpl->tpl_vars['index']->value) {?>Mốc <?php echo $_smarty_tpl->tpl_vars['index']->value;
} else { ?>Mốc mới<?php }?></span>
        <div class="pc-fields">
            <div class="pc-field">
                <label class="pm-label">Thời gian <span class="text-red">*</span></label>
                <input class="form-control" name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][progress_date]" list="progress-date-suggest" placeholder="VD: T4/2025, Quý II/2026" type="text" value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['progress_date'];?>
" />
            </div>
            <div class="pc-field">
                <label class="pm-label">Tên phụ (tùy chọn)</label>
                <input class="form-control" name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][title]" placeholder="vd: Cầu Thượng Cát" type="text" value="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['title'];?>
" />
            </div>
            <div class="pc-field pc-field--check">
                <label class="pm-label">Hiển thị</label>
                <input type="hidden" name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][is_active]" value="0" />
                <input type="checkbox" name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][is_active]" value="1" <?php if ($_smarty_tpl->tpl_vars['_Item']->value['is_active']) {?> checked <?php }?> />
            </div>
        </div>
        <a class="pc-del btn btn-default" href="javascript:void(0);" progress_id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['project_id'];?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['block_id'];?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['_Item']->value['building_id'];?>
" onClick="delete_progress(this, event)" title="Xóa mốc"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</a>
    </div>
    <div class="pc-body">
        <label class="pm-label">Mô tả mốc tiến độ</label>
        <textarea class="form-control isoTextArea" id="desc_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" data-name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][description]" rows="4" placeholder="Nội dung cập nhật tiến độ (hiển thị ở website)..."><?php echo (($tmp = @$_smarty_tpl->tpl_vars['_Item']->value['description'])===null||$tmp==='' ? '' : $tmp);?>
</textarea>
        <input type="hidden" class="pm-json" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" name="attrs[<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
][media]" value="<?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['media_json']->value)===null||$tmp==='' ? '[]' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" />
        <label class="pm-label" style="margin-top:10px">Hình ảnh / Video</label>
        <div class="pm-tools">
            <div class="pm-row pm-folder-wrap">
                <input type="text" class="form-control input-sm pm-folder" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" placeholder="Dán link FOLDER Google Drive (chia sẻ: Bất kỳ ai có link – Người xem)..." />
                <button type="button" class="btn btn-sm btn-primary pm-btn" onClick="$Core.project.progress_sync_drive(this, event)" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"><i class="fa fa-cloud-download"></i> Đồng bộ</button>
            </div>
            <label class="btn btn-sm btn-default pm-upload-btn"><i class="fa fa-upload"></i> Upload ảnh
                <input type="file" accept="image/*" onchange="$Core.project.progress_upload_media(this, event)" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" />
            </label>
            <div class="pm-row pm-link-wrap">
                <input type="text" class="form-control input-sm pm-link" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" placeholder="Dán link lẻ (YouTube / Drive / ảnh)..." />
                <button type="button" class="btn btn-sm btn-default pm-btn" onClick="$Core.project.progress_add_link(this, event)" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">Thêm</button>
            </div>
        </div>
        <div class="progress-media-grid pm-grid" uid="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"></div>
    </div>
</div>
<?php }
}
