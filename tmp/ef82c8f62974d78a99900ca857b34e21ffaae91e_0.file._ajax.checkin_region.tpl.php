<?php
/* Smarty version 3.1.33, created on 2026-08-07 17:38:01
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.checkin_region.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75b5893b3910_22740038',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ef82c8f62974d78a99900ca857b34e21ffaae91e' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.checkin_region.tpl',
      1 => 1785984279,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75b5893b3910_22740038 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('RC_COLOR', array('success'=>'#71dd37','warning'=>'#ffab00','orange'=>'#ff6b1a','danger'=>'#ff3e1d'));
$_smarty_tpl->_assignInScope('RC_SOFT', array('success'=>'#e9fae0','warning'=>'#fff3d6','orange'=>'#ffe8dc','danger'=>'#ffe1dc'));?>
<div class="card" id="card_checkin_region">
	<div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
		<h5 class="mb-0"><i class="bx bx-map-alt text-primary me-1"></i>Theo dõi Check-in theo Vùng</h5>
	</div>
	<div class="card-body">
	<?php if ($_smarty_tpl->tpl_vars['rc_mode']->value == 'person') {?>
				<?php if (empty($_smarty_tpl->tpl_vars['rc_people']->value)) {?>
		<div class="text-center text-muted py-4">Không có nhân sự trong đơn vị</div>
		<?php } else { ?>
		<div class="table-container overflow-x-auto no-shadow text-nowrap">
			<table cellpadding="0" cellspacing="0" class="table table-sort table-bordered table_sortable rc-table">
				<thead>
					<tr>
						<th class="align-center h-px-35 bg-lighter">Nhân viên</th>
						<th class="align-center h-px-35 bg-lighter text-center">Giờ vào</th>
						<th class="align-center h-px-35 bg-lighter text-center">Giờ ra</th>
						<th class="align-center h-px-35 bg-lighter text-center">Trạng thái</th>
					</tr>
				</thead>
				<tbody>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rc_people']->value, '_p');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_p']->value) {
?>
					<tr>
						<td>
							<a href="javascript:void(0);" onclick="$Core.member.view_profile(this, event)" profile_id="<?php echo $_smarty_tpl->tpl_vars['_p']->value['profile_id'];?>
" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
								<img class="rounded-pill object-fit-cover" src="<?php echo $_smarty_tpl->tpl_vars['_p']->value['avatar'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" width="34" height="34" alt="">
								<span><span class="fw-semibold d-block text-truncate" style="max-width:200px" title="<?php echo $_smarty_tpl->tpl_vars['_p']->value['full_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['_p']->value['full_name'];?>
</span><small class="text-muted"><?php echo $_smarty_tpl->tpl_vars['_p']->value['department_name'];?>
</small></span>
							</a>
						</td>
						<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['_p']->value['time']) {?><span class="fw-semibold"><?php echo $_smarty_tpl->tpl_vars['_p']->value['time'];?>
</span><?php } else { ?><span class="text-muted">--</span><?php }?></td>
							<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['_p']->value['time_out'] == 'Chưa check-out') {?><span class="text-warning fw-semibold">Chưa check-out</span><?php } elseif ($_smarty_tpl->tpl_vars['_p']->value['time_out'] && $_smarty_tpl->tpl_vars['_p']->value['time_out'] != '--') {?><span class="fw-semibold"><?php echo $_smarty_tpl->tpl_vars['_p']->value['time_out'];?>
</span><?php } else { ?><span class="text-muted">--</span><?php }?></td>
						<td class="text-center">
							<?php if ($_smarty_tpl->tpl_vars['_p']->value['status'] == 'dung') {?><span class="badge bg-label-success">Đúng giờ</span>
							<?php } elseif ($_smarty_tpl->tpl_vars['_p']->value['status'] == 'muon') {?><span class="badge bg-label-warning">Đi muộn</span>
							<?php } else { ?><span class="badge bg-label-danger">Chưa check-in</span><?php }?>
						</td>
					</tr>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</tbody>
			</table>
		</div>
		<?php }?>
	<?php } else { ?>
				<?php if (empty($_smarty_tpl->tpl_vars['rc_units']->value)) {?>
		<div class="text-center text-muted py-4">Không có đơn vị</div>
		<?php } else { ?>
		<div class="table-container overflow-x-auto no-shadow text-nowrap">
			<table cellpadding="0" cellspacing="0" class="table table-sort table-bordered table_sortable rc-table">
				<thead>
					<tr>
						<th class="align-center h-px-35 bg-lighter">Vùng kinh doanh</th>
						<th class="align-center h-px-35 bg-lighter text-center">Tổng nhân sự</th>
						<th class="align-center h-px-35 bg-lighter text-center">Check-In (Vào)</th>
						<th class="align-center h-px-35 bg-lighter text-center">Đúng giờ</th>
						<th class="align-center h-px-35 bg-lighter text-center">Đi muộn</th>
						<th class="align-center h-px-35 bg-lighter text-center">Chưa check-in</th>
						<th class="align-center h-px-35 bg-lighter text-center">Check-out (Ra)</th>
						<th class="align-center h-px-35 bg-lighter text-center">Về sớm</th>
						<th class="align-center h-px-35 bg-lighter text-center">Tỷ lệ</th>
						<th class="align-center h-px-35 bg-lighter">Leader</th>
						<th class="align-center h-px-35 bg-lighter text-center">Chi tiết</th>
					</tr>
				</thead>
				<tbody>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['rc_units']->value, '_u');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_u']->value) {
?>
					<tr>
						<td><span class="rc-dot" style="background:<?php echo $_smarty_tpl->tpl_vars['RC_COLOR']->value[$_smarty_tpl->tpl_vars['_u']->value['tier']];?>
"></span> <span class="fw-semibold"><?php echo $_smarty_tpl->tpl_vars['_u']->value['title'];?>
</span></td>
						<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['_u']->value['total'] > 0) {?><a href="javascript:void(0);" class="rc-lnk fw-semibold text-dark" title="Danh sách nhân sự" data-type="all" data-department="<?php echo $_smarty_tpl->tpl_vars['_u']->value['id'];?>
" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)"><?php echo $_smarty_tpl->tpl_vars['_u']->value['total'];?>
<i class="bx bx-link-external rc-ic"></i></a><?php } else { ?><span class="text-muted">0</span><?php }?></td>
						<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['_u']->value['dacI'] > 0) {?><a href="javascript:void(0);" class="rc-lnk fw-semibold text-success" title="Danh sách đã check-in" data-type="has_checkin" data-department="<?php echo $_smarty_tpl->tpl_vars['_u']->value['id'];?>
" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)"><?php echo $_smarty_tpl->tpl_vars['_u']->value['dacI'];?>
<i class="bx bx-link-external rc-ic"></i></a><?php } else { ?><span class="text-muted">0</span><?php }?></td>
						<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['_u']->value['dung'] > 0) {?><a href="javascript:void(0);" class="rc-lnk fw-semibold text-success" title="Danh sách đúng giờ" data-type="on_time" data-department="<?php echo $_smarty_tpl->tpl_vars['_u']->value['id'];?>
" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)"><?php echo $_smarty_tpl->tpl_vars['_u']->value['dung'];?>
<i class="bx bx-link-external rc-ic"></i></a><?php } else { ?><span class="text-muted">0</span><?php }?></td>
						<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['_u']->value['muon'] > 0) {?><a href="javascript:void(0);" class="rc-lnk fw-semibold" style="color:#ff9f43" title="Danh sách đi muộn" data-type="late" data-department="<?php echo $_smarty_tpl->tpl_vars['_u']->value['id'];?>
" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)"><?php echo $_smarty_tpl->tpl_vars['_u']->value['muon'];?>
<i class="bx bx-link-external rc-ic"></i></a><?php } else { ?><span class="text-muted">0</span><?php }?></td>
						<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['_u']->value['chua'] > 0) {?><a href="javascript:void(0);" class="rc-lnk fw-semibold text-danger" title="Danh sách chưa check-in" data-type="not_checkin" data-department="<?php echo $_smarty_tpl->tpl_vars['_u']->value['id'];?>
" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)"><?php echo $_smarty_tpl->tpl_vars['_u']->value['chua'];?>
<i class="bx bx-link-external rc-ic"></i></a><?php } else { ?><span class="text-muted">0</span><?php }?></td>
						<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['_u']->value['cout'] > 0) {?><a href="javascript:void(0);" class="rc-lnk fw-semibold text-info" title="Danh sách đã check-out" data-type="checked_out" data-department="<?php echo $_smarty_tpl->tpl_vars['_u']->value['id'];?>
" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)"><?php echo $_smarty_tpl->tpl_vars['_u']->value['cout'];?>
<i class="bx bx-link-external rc-ic"></i></a><?php } else { ?><span class="text-muted">0</span><?php }?></td>
						<td class="text-center"><?php if ($_smarty_tpl->tpl_vars['_u']->value['early'] > 0) {?><a href="javascript:void(0);" class="rc-lnk fw-semibold" style="color:#ff6b1a" title="Danh sách về sớm" data-type="early_leave" data-department="<?php echo $_smarty_tpl->tpl_vars['_u']->value['id'];?>
" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)"><?php echo $_smarty_tpl->tpl_vars['_u']->value['early'];?>
<i class="bx bx-link-external rc-ic"></i></a><?php } else { ?><span class="text-muted">0</span><?php }?></td>
						<td class="text-center"><span class="badge" style="color:<?php echo $_smarty_tpl->tpl_vars['RC_COLOR']->value[$_smarty_tpl->tpl_vars['_u']->value['tier']];?>
;background:<?php echo $_smarty_tpl->tpl_vars['RC_SOFT']->value[$_smarty_tpl->tpl_vars['_u']->value['tier']];?>
"><?php echo $_smarty_tpl->tpl_vars['_u']->value['rate'];?>
%</span></td>
						<td><?php if ($_smarty_tpl->tpl_vars['_u']->value['leader_name']) {?><a href="javascript:void(0);" onclick="$Core.member.view_profile(this, event)" profile_id="<?php echo $_smarty_tpl->tpl_vars['_u']->value['head'];?>
" class="d-flex align-items-center gap-2 text-decoration-none text-dark"><img class="rounded-pill object-fit-cover" src="<?php echo $_smarty_tpl->tpl_vars['_u']->value['leader_avatar'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" width="30" height="30" alt=""><span class="fw-semibold text-truncate" style="max-width:130px" title="<?php echo $_smarty_tpl->tpl_vars['_u']->value['leader_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['_u']->value['leader_name'];?>
</span></a><?php } else { ?><span class="text-muted">--</span><?php }?></td>
						<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-secondary" title="Danh sách nhân sự" data-type="all" data-department="<?php echo $_smarty_tpl->tpl_vars['_u']->value['id'];?>
" onclick="$Core.report_checkin.load_list_profile_checkin(this, event)"><i class="bx bx-chevron-right"></i></a></td>
					</tr>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</tbody>
				<?php if ($_smarty_tpl->tpl_vars['rc_total']->value) {?>
				<tfoot>
					<tr class="fw-bold border-top">
						<td class="text-primary">Tổng</td>
						<td class="text-center text-primary"><?php echo $_smarty_tpl->tpl_vars['rc_total']->value['total'];?>
</td>
						<td class="text-center text-primary"><?php echo $_smarty_tpl->tpl_vars['rc_total']->value['dacI'];?>
</td>
						<td class="text-center text-primary"><?php echo $_smarty_tpl->tpl_vars['rc_total']->value['dung'];?>
</td>
						<td class="text-center" style="color:#ff9f43"><?php echo $_smarty_tpl->tpl_vars['rc_total']->value['muon'];?>
</td>
						<td class="text-center text-danger"><?php echo $_smarty_tpl->tpl_vars['rc_total']->value['chua'];?>
</td>
						<td class="text-center text-info"><?php echo $_smarty_tpl->tpl_vars['rc_total']->value['cout'];?>
</td>
						<td class="text-center" style="color:#ff6b1a"><?php echo $_smarty_tpl->tpl_vars['rc_total']->value['early'];?>
</td>
						<td class="text-center"><span class="fw-bold" style="color:<?php echo $_smarty_tpl->tpl_vars['RC_COLOR']->value[$_smarty_tpl->tpl_vars['rc_total']->value['tier']];?>
"><?php echo $_smarty_tpl->tpl_vars['rc_total']->value['rate'];?>
%</span></td>
						<td colspan="2"></td>
					</tr>
				</tfoot>
				<?php }?>
			</table>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-3 mt-3 fs-12 text-muted">
			<span class="fw-semibold">Chú thích:</span>
			<span><span class="rc-dot" style="background:#71dd37"></span> &gt; 95%</span>
			<span><span class="rc-dot" style="background:#ffab00"></span> 85% - 95%</span>
			<span><span class="rc-dot" style="background:#ff6b1a"></span> 70% - 85%</span>
			<span><span class="rc-dot" style="background:#ff3e1d"></span> &lt; 70%</span>
		</div>
		<?php }?>
	<?php }?>
	</div>
</div>

<style>
#card_checkin_region .rc-dot{display:inline-block;width:10px;height:10px;border-radius:50%;vertical-align:middle;margin-right:3px}
#card_checkin_region .rc-table th{font-size:12px;font-weight:600;color:#6f7b8a;white-space:nowrap}
#card_checkin_region .rc-table td{font-size:13px}
#card_checkin_region .rc-lnk{text-decoration:none;cursor:pointer}
#card_checkin_region .rc-lnk:hover{text-decoration:underline}
#card_checkin_region .rc-ic{font-size:11px;opacity:.5;margin-left:3px;vertical-align:middle}
</style>

<?php }
}
