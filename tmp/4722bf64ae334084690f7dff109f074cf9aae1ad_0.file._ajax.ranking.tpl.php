<?php
/* Smarty version 3.1.33, created on 2026-08-08 20:30:25
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/competition/_ajax.ranking.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a772f718544e7_09186154',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4722bf64ae334084690f7dff109f074cf9aae1ad' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/competition/_ajax.ranking.tpl',
      1 => 1786195823,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a772f718544e7_09186154 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="top-ranker mt-2 rounded-2<?php if ($_smarty_tpl->tpl_vars['clsComp']->value->isGradientAnimated($_smarty_tpl->tpl_vars['program']->value)) {?> comp-anim<?php }?>" style="background:<?php echo $_smarty_tpl->tpl_vars['clsComp']->value->gradientCss($_smarty_tpl->tpl_vars['program']->value);?>
;color:#fff">
	<div class="top-ranker-header d-flex align-items-center gap-2 gap-lg-3 mb-3">
		<?php if ($_smarty_tpl->tpl_vars['program']->value['icon']) {?><i class="bx <?php echo $_smarty_tpl->tpl_vars['program']->value['icon'];?>
 text-white" style="font-size:32px"></i><?php } else { ?><img class="w-px-40" src="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['LogoWhite'];?>
" /><?php }?>
		<div class="ranking-title ext text-white fs-18">
			<span class="text-upper"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['program']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span><br />
			<?php if (!empty($_smarty_tpl->tpl_vars['start_txt']->value)) {?><small>( <?php echo $_smarty_tpl->tpl_vars['start_txt']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['end_txt']->value;?>
 )</small><?php }?>
		</div>
	</div>
	<div class="top-ranker-body position-relative zindex-2">
		<table class="table table-ranker" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th class="align-center w-px-50 h-px-40 text-center">STT</th>
					<th class="align-center h-px-40">Họ và tên</th>
					<th class="align-center h-px-40 text-center">Điểm</th>
					<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?><th class="align-center h-px-40 text-center">Xếp hạng</th><?php }?>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($_smarty_tpl->tpl_vars['ranking']->value)) {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ranking']->value, 'r');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['r']->value) {
?>
					<tr class="nohover" style="cursor:pointer" onclick="$Core.competition.detail(<?php echo $_smarty_tpl->tpl_vars['program']->value['id'];?>
, <?php echo $_smarty_tpl->tpl_vars['r']->value['profile_id'];?>
)">
						<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['r']->value['stt'] == 1) {?><span class="text-warning">&#127942;</span><?php } elseif ($_smarty_tpl->tpl_vars['r']->value['stt'] == 2) {?>&#129352;<?php } elseif ($_smarty_tpl->tpl_vars['r']->value['stt'] == 3) {?>&#129353;<?php } else {
echo $_smarty_tpl->tpl_vars['r']->value['stt'];
}?></td>
						<td>
							<div class="d-flex align-items-center gap-2">
								<img src="<?php echo $_smarty_tpl->tpl_vars['r']->value['avatar'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" class="rounded-circle flex-shrink-0" width="30" height="30" style="object-fit:cover" />
								<div>
									<div class="fw-semibold"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value['name'], ENT_QUOTES, 'UTF-8', true);
if ($_smarty_tpl->tpl_vars['r']->value['profile_id'] == $_smarty_tpl->tpl_vars['me_id']->value) {?> <span class="badge bg-warning text-dark">Bạn</span><?php }?></div>
									<?php if ($_smarty_tpl->tpl_vars['r']->value['dept']) {?><small class="text-white-50"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value['dept'], ENT_QUOTES, 'UTF-8', true);?>
</small><?php }?>
								</div>
							</div>
						</td>
						<td class="text-center fw-bold"><?php echo $_smarty_tpl->tpl_vars['r']->value['score'];?>
</td>
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?><td class="text-center"><?php if ($_smarty_tpl->tpl_vars['r']->value['rank_name']) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value['rank_name'], ENT_QUOTES, 'UTF-8', true);
} else { ?>--<?php }?></td><?php }?>
					</tr>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php } else { ?>
					<tr><td colspan="4" class="text-center text-white-50 py-3">Chưa có dữ liệu xếp hạng cho chương trình này.</td></tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	<?php if (!empty($_smarty_tpl->tpl_vars['program']->value['tiers']) || !empty($_smarty_tpl->tpl_vars['program']->value['ranks'])) {?>
	<div class="top-ranker-note position-relative zindex-2 mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.25)">
		<div class="text-white fw-semibold mb-2" style="font-size:13px"><i class="bx bx-info-circle me-1"></i>Cơ chế tính điểm</div>
		<?php if (!empty($_smarty_tpl->tpl_vars['program']->value['tiers'])) {?>
		<div class="text-white-50 mb-2" style="font-size:12px;line-height:1.9">
			Mỗi giao dịch quy đổi theo giá trị căn (tỷ) — <span class="text-white">ĐQ</span> độc quyền / <span class="text-white">QC</span> quỹ chéo:<br />
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['program']->value['tiers'], 't', false, NULL, 'tf', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['t']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_tf']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_tf']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_tf']->value['index'];
if ($_smarty_tpl->tpl_vars['t']->value['from'] || $_smarty_tpl->tpl_vars['t']->value['to']) {
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_tf']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_tf']->value['first'] : null)) {?> &middot; <?php }?><span class="text-white"><?php echo $_smarty_tpl->tpl_vars['t']->value['from'];
if ($_smarty_tpl->tpl_vars['t']->value['to']) {?>–<?php echo $_smarty_tpl->tpl_vars['t']->value['to'];
} else { ?>+<?php }?> tỷ</span>: <?php echo $_smarty_tpl->tpl_vars['t']->value['exclusive'];?>
/<?php echo $_smarty_tpl->tpl_vars['t']->value['cross'];?>
đ<?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</div>
		<?php }?>
		<?php if (!empty($_smarty_tpl->tpl_vars['program']->value['ranks'])) {?>
		<div class="text-white-50" style="font-size:12px;line-height:2">
			Danh hiệu:
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['program']->value['ranks'], 'rk');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['rk']->value) {
?><span class="me-2 d-inline-block"><span class="badge bg-warning text-dark"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['rk']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span> &ge; <?php echo $_smarty_tpl->tpl_vars['rk']->value['from'];?>
đ<?php if ($_smarty_tpl->tpl_vars['rk']->value['reward']) {?> &middot; <span class="text-white"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['rk']->value['reward'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php }?></span><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</div>
		<?php }?>
	</div>
	<?php }?>
</div>
<?php }
}
