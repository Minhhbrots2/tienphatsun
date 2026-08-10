<?php
/* Smarty version 3.1.33, created on 2026-08-08 18:33:28
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/competition/edit.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7714081e09c8_75585181',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '053042e8c85db9f481e877273951b1b2ec34b57c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/competition/edit.tpl',
      1 => 1786188299,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7714081e09c8_75585181 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<header class="ui-title-bar-container">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title"><?php if ($_smarty_tpl->tpl_vars['program']->value['id'] > 0) {?>Sửa chương trình<?php } else { ?>Thêm chương trình<?php }?> · Thi đua định danh</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__actions">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default">&larr; Danh sách</a>
				<button type="submit" form="frmCompetition" name="submit" value="1" class="btn btn-success">Lưu chương trình</button>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<form method="post" action="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=save" id="frmCompetition">
<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['program']->value['id'];?>
" />
<div class="ui-layout"><div class="ui-layout__sections"><div class="ui-layout__section"><div class="ui-layout__item">

	<div class="ui-card p-3 mb-3">
		<h4 class="mt-0">Thông tin chung</h4>
		<div class="row">
			<div class="col-md-6 form-group">
				<label>Tên chương trình</label>
				<input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['program']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" placeholder="Thi đua định danh 2026" />
			</div>
			<div class="col-md-3 form-group">
				<label>Trạng thái</label>
				<select name="status" class="form-control">
					<option value="1"<?php if ($_smarty_tpl->tpl_vars['program']->value['status'] == 1) {?> selected<?php }?>>Đang chạy</option>
					<option value="0"<?php if ($_smarty_tpl->tpl_vars['program']->value['status'] == 0) {?> selected<?php }?>>Tạm dừng</option>
				</select>
			</div>
			<div class="col-md-3 form-group">
				<label>Kỳ áp dụng</label>
				<select name="period_type" class="form-control">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['clsComp']->value->periodTypes(), 'lbl', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['lbl']->value) {
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['program']->value['period_type'] == $_smarty_tpl->tpl_vars['k']->value) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['lbl']->value;?>
</option>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</select>
			</div>
			<div class="col-md-3 form-group">
				<label>Từ ngày</label>
				<input type="date" name="start_date_txt" class="form-control" value="<?php if ($_smarty_tpl->tpl_vars['program']->value['start_date'] > 0) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['program']->value['start_date'],'%Y-%m-%d');
}?>" />
			</div>
			<div class="col-md-3 form-group">
				<label>Đến ngày</label>
				<input type="date" name="end_date_txt" class="form-control" value="<?php if ($_smarty_tpl->tpl_vars['program']->value['end_date'] > 0) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['program']->value['end_date'],'%Y-%m-%d');
}?>" />
			</div>
			<div class="col-md-3 form-group">
				<label>Thứ tự hiển thị</label>
				<input type="number" name="order_no" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['program']->value['order_no'];?>
" />
			</div>
			<div class="col-md-3 form-group">
				<label>Icon (mã boxicon, vd bx-trophy)</label>
				<input type="text" name="icon" class="form-control" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['program']->value['icon'], ENT_QUOTES, 'UTF-8', true);?>
" placeholder="bx-trophy" />
			</div>
			<div class="col-md-3 form-group">
				<label>Phạm vi hiển thị</label>
				<select name="visibility" class="form-control">
					<option value="all"<?php if ($_smarty_tpl->tpl_vars['program']->value['visibility'] == 'all') {?> selected<?php }?>>Tất cả mọi người</option>
					<option value="department"<?php if ($_smarty_tpl->tpl_vars['program']->value['visibility'] == 'department') {?> selected<?php }?>>Chỉ thành viên phòng ban tham gia</option>
				</select>
			</div>
		</div>
		<hr />
		<h5 class="mt-0">Màu nền board (gradient động)</h5>
		<div class="row">
			<div class="col-md-2 form-group">
				<label>Màu 1</label>
				<input type="color" id="cpColor1" name="color" class="form-control" value="<?php if ($_smarty_tpl->tpl_vars['program']->value['color']) {
echo $_smarty_tpl->tpl_vars['program']->value['color'];
} else { ?>#c0392b<?php }?>" style="height:38px;padding:4px" oninput="cpUpdateGrad()" />
			</div>
			<div class="col-md-2 form-group">
				<label>Màu 2</label>
				<input type="color" id="cpColor2" name="color2" class="form-control" value="<?php if ($_smarty_tpl->tpl_vars['program']->value['color2']) {
echo $_smarty_tpl->tpl_vars['program']->value['color2'];
} else { ?>#7b1f13<?php }?>" style="height:38px;padding:4px" oninput="cpUpdateGrad()" />
			</div>
			<div class="col-md-2 form-group">
				<label>Góc (độ)</label>
				<input type="number" id="cpAngle" name="gradient_angle" class="form-control" min="0" max="360" value="<?php if ($_smarty_tpl->tpl_vars['program']->value['gradient_angle'] != '') {
echo $_smarty_tpl->tpl_vars['program']->value['gradient_angle'];
} else { ?>135<?php }?>" oninput="cpUpdateGrad()" />
			</div>
			<div class="col-md-2 form-group">
				<label class="d-block">Hiệu ứng</label>
				<label class="checkbox-inline" style="margin-top:8px">
					<input type="checkbox" name="gradient_animate" value="1"<?php if ($_smarty_tpl->tpl_vars['program']->value['gradient_animate'] == 1) {?> checked<?php }?> /> Chạy động
				</label>
			</div>
			<div class="col-md-4 form-group">
				<label>Xem trước</label>
				<div id="cpGradPreview" style="height:38px;border-radius:6px;border:1px solid #ddd"></div>
			</div>
		</div>
	</div>

	<div class="ui-card p-3 mb-3">
		<h4 class="mt-0">Đối tượng tham gia</h4>
		<div class="form-group">
			<label>Phòng ban (chọn nhiều)</label>
			<select name="department_ids[]" class="form-control iso-select2" multiple data-placeholder="Chọn phòng ban" style="width:100%">
				<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_DEPARTMENT',$_smarty_tpl->tpl_vars['program']->value['department_ids'],'');?>

			</select>
		</div>
		<label class="checkbox-inline">
			<input type="checkbox" name="include_children" value="1"<?php if ($_smarty_tpl->tpl_vars['program']->value['include_children'] == 1) {?> checked<?php }?> /> Tính luôn nhân sự các phòng con bên dưới
		</label>
	</div>

	<div class="ui-card p-3 mb-3">
		<h4 class="mt-0">Căn cứ tính điểm</h4>
		<div class="row">
			<div class="col-md-6 form-group">
				<label>Giá trị dùng để dò bậc</label>
				<select name="value_field" class="form-control">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['clsComp']->value->valueFields(), 'lbl', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['lbl']->value) {
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['program']->value['value_field'] == $_smarty_tpl->tpl_vars['k']->value) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['lbl']->value;?>
</option>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</select>
			</div>
			<div class="col-md-6 form-group">
				<label>Điều kiện ghi nhận doanh số</label>
				<select name="revenue_condition" class="form-control">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['clsComp']->value->revenueConditions(), 'lbl', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['lbl']->value) {
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['clsComp']->value->conditionOf($_smarty_tpl->tpl_vars['program']->value) == $_smarty_tpl->tpl_vars['k']->value) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['lbl']->value;?>
</option>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</select>
			</div>
		</div>
		<p class="text-muted mt-1 mb-0"><i class="bx bx-info-circle"></i> Chọn điều kiện để GD được tính doanh số + đưa vào kỳ theo ngày tương ứng: <b>Ký VBTT hoặc HĐMB</b> (dự án chỉ có HĐMB vẫn tính) · <b>Chỉ HĐMB</b> · <b>Chỉ VBTT</b> · <b>Chỉ cần chốt</b> (theo ngày cọc, không cần ký).</p>
	</div>

	<div class="ui-card p-3 mb-3">
		<h4 class="mt-0">Thang điểm quy đổi (giá trị → điểm, đơn vị tỷ VND)</h4>
		<table class="table table-bordered" id="tblTiers">
			<thead><tr><th>Từ (tỷ)</th><th>Đến (tỷ, 0 = trở lên)</th><th>Độc Quyền</th><th>Quỹ Chéo</th><th>Ghi chú</th><th width="40"></th></tr></thead>
			<tbody>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['program']->value['tiers'], 't');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['t']->value) {
?>
				<tr>
					<td><input type="number" step="0.01" name="tier_from[]" class="form-control input-sm" value="<?php echo $_smarty_tpl->tpl_vars['t']->value['from'];?>
" /></td>
					<td><input type="number" step="0.01" name="tier_to[]" class="form-control input-sm" value="<?php echo $_smarty_tpl->tpl_vars['t']->value['to'];?>
" /></td>
					<td><input type="number" step="0.01" name="tier_excl[]" class="form-control input-sm" value="<?php echo $_smarty_tpl->tpl_vars['t']->value['exclusive'];?>
" /></td>
					<td><input type="number" step="0.01" name="tier_cross[]" class="form-control input-sm" value="<?php echo $_smarty_tpl->tpl_vars['t']->value['cross'];?>
" /></td>
					<td><input type="text" name="tier_note[]" class="form-control input-sm" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['t']->value['note'], ENT_QUOTES, 'UTF-8', true);?>
" /></td>
					<td class="text-center"><a href="javascript:void(0)" class="text-danger" onclick="cpDelRow(this)">&times;</a></td>
				</tr>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</tbody>
		</table>
		<button type="button" class="btn btn-xs btn-default" onclick="cpAddTier()">+ Thêm bậc</button>
	</div>

	<div class="ui-card p-3 mb-3">
		<h4 class="mt-0">Hạng danh hiệu (điểm → hạng → thưởng)</h4>
		<table class="table table-bordered" id="tblRanks">
			<thead><tr><th width="120">Từ điểm</th><th>Tên hạng</th><th>Phần thưởng</th><th width="40"></th></tr></thead>
			<tbody>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['program']->value['ranks'], 'r');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['r']->value) {
?>
				<tr>
					<td><input type="number" step="0.01" name="rank_from[]" class="form-control input-sm" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['from'];?>
" /></td>
					<td><input type="text" name="rank_name[]" class="form-control input-sm" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" /></td>
					<td><input type="text" name="rank_reward[]" class="form-control input-sm" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['r']->value['reward'], ENT_QUOTES, 'UTF-8', true);?>
" /></td>
					<td class="text-center"><a href="javascript:void(0)" class="text-danger" onclick="cpDelRow(this)">&times;</a></td>
				</tr>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</tbody>
		</table>
		<button type="button" class="btn btn-xs btn-default" onclick="cpAddRank()">+ Thêm hạng</button>
	</div>

</div></div></div></div>
</form>

<?php echo '<script'; ?>
 type="text/javascript">
	function cpUpdateGrad(){
		var c1 = document.getElementById('cpColor1'), c2 = document.getElementById('cpColor2'),
			an = document.getElementById('cpAngle'), pv = document.getElementById('cpGradPreview');
		if(!c1 || !c2 || !an || !pv) return;
		var ang = parseInt(an.value, 10); if(isNaN(ang)) ang = 135;
		pv.style.background = 'linear-gradient(' + ang + 'deg, ' + c1.value + ', ' + c2.value + ')';
	}
	document.addEventListener('DOMContentLoaded', cpUpdateGrad);
	function cpDelRow(a){ var tr = a.closest('tr'); if(tr) tr.parentNode.removeChild(tr); }
	function cpAddTier(){
		var tb = document.querySelector('#tblTiers tbody');
		var tr = document.createElement('tr');
		tr.innerHTML = '<td><input type="number" step="0.01" name="tier_from[]" class="form-control input-sm" value="0"></td>'
			+ '<td><input type="number" step="0.01" name="tier_to[]" class="form-control input-sm" value="0"></td>'
			+ '<td><input type="number" step="0.01" name="tier_excl[]" class="form-control input-sm" value="0"></td>'
			+ '<td><input type="number" step="0.01" name="tier_cross[]" class="form-control input-sm" value="0"></td>'
			+ '<td><input type="text" name="tier_note[]" class="form-control input-sm" value=""></td>'
			+ '<td class="text-center"><a href="javascript:void(0)" class="text-danger" onclick="cpDelRow(this)">&times;</a></td>';
		tb.appendChild(tr);
	}
	function cpAddRank(){
		var tb = document.querySelector('#tblRanks tbody');
		var tr = document.createElement('tr');
		tr.innerHTML = '<td><input type="number" step="0.01" name="rank_from[]" class="form-control input-sm" value="0"></td>'
			+ '<td><input type="text" name="rank_name[]" class="form-control input-sm" value=""></td>'
			+ '<td><input type="text" name="rank_reward[]" class="form-control input-sm" value=""></td>'
			+ '<td class="text-center"><a href="javascript:void(0)" class="text-danger" onclick="cpDelRow(this)">&times;</a></td>';
		tb.appendChild(tr);
	}
<?php echo '</script'; ?>
>

<?php }
}
