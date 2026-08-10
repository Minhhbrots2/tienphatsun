<header class="ui-title-bar-container">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{if $program.id > 0}Sửa chương trình{else}Thêm chương trình{/if} · Thi đua định danh</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__actions">
				<a href="{$PCMS_URL}/?mod={$mod}" class="btn btn-default">&larr; Danh sách</a>
				<button type="submit" form="frmCompetition" name="submit" value="1" class="btn btn-success">Lưu chương trình</button>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<form method="post" action="{$PCMS_URL}/?mod={$mod}&act=save" id="frmCompetition">
<input type="hidden" name="id" value="{$program.id}" />
<div class="ui-layout"><div class="ui-layout__sections"><div class="ui-layout__section"><div class="ui-layout__item">

	<div class="ui-card p-3 mb-3">
		<h4 class="mt-0">Thông tin chung</h4>
		<div class="row">
			<div class="col-md-6 form-group">
				<label>Tên chương trình</label>
				<input type="text" name="name" class="form-control" value="{$program.name|escape}" placeholder="Thi đua định danh 2026" />
			</div>
			<div class="col-md-3 form-group">
				<label>Trạng thái</label>
				<select name="status" class="form-control">
					<option value="1"{if $program.status eq 1} selected{/if}>Đang chạy</option>
					<option value="0"{if $program.status eq 0} selected{/if}>Tạm dừng</option>
				</select>
			</div>
			<div class="col-md-3 form-group">
				<label>Kỳ áp dụng</label>
				<select name="period_type" class="form-control">
					{foreach from=$clsComp->periodTypes() key=k item=lbl}
					<option value="{$k}"{if $program.period_type eq $k} selected{/if}>{$lbl}</option>
					{/foreach}
				</select>
			</div>
			<div class="col-md-3 form-group">
				<label>Từ ngày</label>
				<input type="date" name="start_date_txt" class="form-control" value="{if $program.start_date > 0}{$program.start_date|date_format:'%Y-%m-%d'}{/if}" />
			</div>
			<div class="col-md-3 form-group">
				<label>Đến ngày</label>
				<input type="date" name="end_date_txt" class="form-control" value="{if $program.end_date > 0}{$program.end_date|date_format:'%Y-%m-%d'}{/if}" />
			</div>
			<div class="col-md-3 form-group">
				<label>Thứ tự hiển thị</label>
				<input type="number" name="order_no" class="form-control" value="{$program.order_no}" />
			</div>
			<div class="col-md-3 form-group">
				<label>Icon (mã boxicon, vd bx-trophy)</label>
				<input type="text" name="icon" class="form-control" value="{$program.icon|escape}" placeholder="bx-trophy" />
			</div>
			<div class="col-md-3 form-group">
				<label>Phạm vi hiển thị</label>
				<select name="visibility" class="form-control">
					<option value="all"{if $program.visibility eq 'all'} selected{/if}>Tất cả mọi người</option>
					<option value="department"{if $program.visibility eq 'department'} selected{/if}>Chỉ thành viên phòng ban tham gia</option>
				</select>
			</div>
		</div>
		<hr />
		<h5 class="mt-0">Màu nền board (gradient động)</h5>
		<div class="row">
			<div class="col-md-2 form-group">
				<label>Màu 1</label>
				<input type="color" id="cpColor1" name="color" class="form-control" value="{if $program.color}{$program.color}{else}#c0392b{/if}" style="height:38px;padding:4px" oninput="cpUpdateGrad()" />
			</div>
			<div class="col-md-2 form-group">
				<label>Màu 2</label>
				<input type="color" id="cpColor2" name="color2" class="form-control" value="{if $program.color2}{$program.color2}{else}#7b1f13{/if}" style="height:38px;padding:4px" oninput="cpUpdateGrad()" />
			</div>
			<div class="col-md-2 form-group">
				<label>Góc (độ)</label>
				<input type="number" id="cpAngle" name="gradient_angle" class="form-control" min="0" max="360" value="{if $program.gradient_angle ne ''}{$program.gradient_angle}{else}135{/if}" oninput="cpUpdateGrad()" />
			</div>
			<div class="col-md-2 form-group">
				<label class="d-block">Hiệu ứng</label>
				<label class="checkbox-inline" style="margin-top:8px">
					<input type="checkbox" name="gradient_animate" value="1"{if $program.gradient_animate eq 1} checked{/if} /> Chạy động
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
				{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT', $program.department_ids, '')}
			</select>
		</div>
		<label class="checkbox-inline">
			<input type="checkbox" name="include_children" value="1"{if $program.include_children eq 1} checked{/if} /> Tính luôn nhân sự các phòng con bên dưới
		</label>
	</div>

	<div class="ui-card p-3 mb-3">
		<h4 class="mt-0">Căn cứ tính điểm</h4>
		<div class="row">
			<div class="col-md-6 form-group">
				<label>Giá trị dùng để dò bậc</label>
				<select name="value_field" class="form-control">
					{foreach from=$clsComp->valueFields() key=k item=lbl}
					<option value="{$k}"{if $program.value_field eq $k} selected{/if}>{$lbl}</option>
					{/foreach}
				</select>
			</div>
			<div class="col-md-6 form-group">
				<label>Điều kiện ghi nhận doanh số</label>
				<select name="revenue_condition" class="form-control">
					{foreach from=$clsComp->revenueConditions() key=k item=lbl}
					<option value="{$k}"{if $clsComp->conditionOf($program) eq $k} selected{/if}>{$lbl}</option>
					{/foreach}
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
				{foreach from=$program.tiers item=t}
				<tr>
					<td><input type="number" step="0.01" name="tier_from[]" class="form-control input-sm" value="{$t.from}" /></td>
					<td><input type="number" step="0.01" name="tier_to[]" class="form-control input-sm" value="{$t.to}" /></td>
					<td><input type="number" step="0.01" name="tier_excl[]" class="form-control input-sm" value="{$t.exclusive}" /></td>
					<td><input type="number" step="0.01" name="tier_cross[]" class="form-control input-sm" value="{$t.cross}" /></td>
					<td><input type="text" name="tier_note[]" class="form-control input-sm" value="{$t.note|escape}" /></td>
					<td class="text-center"><a href="javascript:void(0)" class="text-danger" onclick="cpDelRow(this)">&times;</a></td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		<button type="button" class="btn btn-xs btn-default" onclick="cpAddTier()">+ Thêm bậc</button>
	</div>

	<div class="ui-card p-3 mb-3">
		<h4 class="mt-0">Hạng danh hiệu (điểm → hạng → thưởng)</h4>
		<table class="table table-bordered" id="tblRanks">
			<thead><tr><th width="120">Từ điểm</th><th>Tên hạng</th><th>Phần thưởng</th><th width="40"></th></tr></thead>
			<tbody>
				{foreach from=$program.ranks item=r}
				<tr>
					<td><input type="number" step="0.01" name="rank_from[]" class="form-control input-sm" value="{$r.from}" /></td>
					<td><input type="text" name="rank_name[]" class="form-control input-sm" value="{$r.name|escape}" /></td>
					<td><input type="text" name="rank_reward[]" class="form-control input-sm" value="{$r.reward|escape}" /></td>
					<td class="text-center"><a href="javascript:void(0)" class="text-danger" onclick="cpDelRow(this)">&times;</a></td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		<button type="button" class="btn btn-xs btn-default" onclick="cpAddRank()">+ Thêm hạng</button>
	</div>

</div></div></div></div>
</form>
{literal}
<script type="text/javascript">
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
</script>
{/literal}
