<?php
/* Smarty version 3.1.33, created on 2026-08-07 16:01:09
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.profile_journey.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a759ed5814145_08792802',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '40e72d457bbe61dc4059970283afab17f774d40c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.profile_journey.tpl',
      1 => 1784299673,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a759ed5814145_08792802 (Smarty_Internal_Template $_smarty_tpl) {
?> 
<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content pj-modal border-0 pb-4">
		<div class="pj-top">
			<button type="button" class="pj-back" onclick="$Core.report_checkin.back_to_list()" title="Quay lại danh sách check-in"><i class="bx bx-chevron-left"></i></button>
			<div class="pj-top-tt">
				<div class="pj-top-t">Timeline Check-in</div>
				<div class="pj-top-s">Xem hành trình hoạt động trong ngày</div>
			</div>
			<button type="button" class="pj-back" data-bs-dismiss="modal" title="Đóng"><i class="bx bx-x"></i></button>
		</div>
		<div class="pj-scroll">
		<?php if (!$_smarty_tpl->tpl_vars['pj_allowed']->value) {?>
			<div class="text-center text-muted py-5 px-3">Không có quyền xem hoặc không tìm thấy nhân sự.</div>
		<?php } else { ?>
			<div class="pj-profile">
				<img class="pj-av" src="<?php echo $_smarty_tpl->tpl_vars['pj_profile']->value['avatar'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" alt="">
				<div class="pj-pi overflow-hidden">
					<div class="pj-nm text-truncate"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pj_profile']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</div>
					<?php if ($_smarty_tpl->tpl_vars['pj_profile']->value['role']) {?><div class="pj-role"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pj_profile']->value['role'], ENT_QUOTES, 'UTF-8', true);?>
</div><?php }?>
					<?php if ($_smarty_tpl->tpl_vars['pj_profile']->value['dept']) {?><div class="pj-dept">Phòng ban: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pj_profile']->value['dept'], ENT_QUOTES, 'UTF-8', true);?>
</div><?php }?>
				</div>
			</div>
			<div class="pj-datebar">
				<button type="button" class="pj-nav" onclick="$Core.report_checkin.journey_day(-1)" title="Ngày trước"><i class="bx bx-chevron-left"></i></button>
				<div class="pj-date"><?php echo $_smarty_tpl->tpl_vars['pj_day_label']->value;?>
</div>
				<button type="button" class="pj-nav" onclick="$Core.report_checkin.journey_day(1)" <?php if ($_smarty_tpl->tpl_vars['pj_is_today']->value) {?>disabled<?php }?> title="Ngày sau"><i class="bx bx-chevron-right"></i></button>
			</div>
			<div class="pj-ov">
				<div class="pj-ovh">Tổng quan hoạt động trong ngày</div>
				<div class="pj-stats">
					<div class="pj-stat"><span class="pj-sic" style="color:#16a34a;background:rgba(22,163,74,.12)"><i class="bx bx-map-pin"></i></span><b><?php echo $_smarty_tpl->tpl_vars['pj_stats']->value['count'];?>
</b><span class="pj-statl">Lần check-in</span></div>
					<div class="pj-stat"><span class="pj-sic" style="color:#d97706;background:rgba(217,119,6,.12)"><i class="bx bx-been-here"></i></span><b><?php echo $_smarty_tpl->tpl_vars['pj_stats']->value['places'];?>
</b><span class="pj-statl">Địa điểm</span></div>
					<div class="pj-stat"><span class="pj-sic" style="color:#7c3aed;background:rgba(124,58,237,.12)"><i class="bx bx-camera"></i></span><b><?php echo $_smarty_tpl->tpl_vars['pj_stats']->value['photos'];?>
</b><span class="pj-statl">Ảnh đã gửi</span></div>
				</div>
			</div>
			<div class="pj-tlh">Timeline check-in trong ngày</div>
			<?php if (empty($_smarty_tpl->tpl_vars['pj_items']->value)) {?>
			<div class="text-center text-muted py-4">Không có check-in trong ngày này.</div>
			<?php } else { ?>
			<div class="pj-timeline">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['pj_items']->value, '_it');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_it']->value) {
?>
				<div class="pj-item">
					<div class="pj-side"><span class="pj-time"><?php echo $_smarty_tpl->tpl_vars['_it']->value['time'];?>
</span><span class="pj-dot" style="background:<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_it']->value['dot_color'], ENT_QUOTES, 'UTF-8', true);?>
"></span></div>
					<div class="pj-card">
						<div class="pj-cardtop justify-content-between">
							<div class="d-flex flex-column gap-2">
								<div class="pj-cardmain">
									<div class="pj-place"><i class="bx bx-map-pin" style="color:<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_it']->value['dot_color'], ENT_QUOTES, 'UTF-8', true);?>
"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_it']->value['place'], ENT_QUOTES, 'UTF-8', true);?>
</div>
									<?php if ($_smarty_tpl->tpl_vars['_it']->value['tags']) {?><div class="pj-tags"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_it']->value['tags'], '_tg');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_tg']->value) {
?><span class="pj-tag" style="color:<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_tg']->value['color'], ENT_QUOTES, 'UTF-8', true);?>
;background:<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_tg']->value['color'], ENT_QUOTES, 'UTF-8', true);?>
22"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_tg']->value['label'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></div><?php }?>
								</div>
								<?php if ($_smarty_tpl->tpl_vars['_it']->value['note']) {?><div class="pj-note"><?php echo nl2br(htmlspecialchars($_smarty_tpl->tpl_vars['_it']->value['note'], ENT_QUOTES, 'UTF-8', true));?>
</div><?php }?>
								<?php if ($_smarty_tpl->tpl_vars['_it']->value['address']) {?><div class="pj-addr" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_it']->value['address'], ENT_QUOTES, 'UTF-8', true);?>
"><i class="bx bx-map"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_it']->value['address'], ENT_QUOTES, 'UTF-8', true);?>
</div><?php }?>
							</div>
							<?php if ($_smarty_tpl->tpl_vars['_it']->value['photo']) {?><img class="pj-photo" src="<?php echo $_smarty_tpl->tpl_vars['_it']->value['photo'];?>
" onerror="this.style.display='none'" data-fancybox="pj_photos" href="<?php echo $_smarty_tpl->tpl_vars['_it']->value['photo'];?>
" data-caption="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_it']->value['place'], ENT_QUOTES, 'UTF-8', true);?>
 · <?php echo $_smarty_tpl->tpl_vars['_it']->value['time'];?>
" alt=""><?php }?>
						</div>
					</div>
				</div>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>
			<?php }?>
			<div class="pj-foot"><i class="bx bx-info-circle"></i> Dữ liệu check-in được cập nhật tự động theo thời gian thực</div>
		<?php }?>
		</div>
	</div>
</div>
<?php }
}
