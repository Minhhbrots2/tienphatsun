<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap align-items-start justify-content-between mb-2">
		<div class="yvBvmnviXh mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1 {if $deviceType eq 'phone'}fs-5{/if}">Bảng lương lãnh đạo khối Kinh doanh</h4>
			<p class="text-muted mb-0">Có <span class="total_results text-main">0</span> lãnh đạo khối Kinh doanh</p>
		</div>
		<div class="yvBvmnviXg d-flex xs:w-100 gap-1">
			<select onchange="$Core.salary.do_search(this, event)" class="form-control xs:flex-fill xs:w-50 w-px-100 form-select search_field" 
				data-field="month" name="month">
				{foreach name=i from=$list_months item = _month}
				<option{if $_month eq $smarty.now|date_format:"%m"} selected{/if} value="{$_month}">Tháng {$_month}</option>
				{/foreach}
			</select>
			<select onchange="$Core.salary.do_search(this, event)" class="form-control xs:flex-fill xs:w-50 w-px-100 form-select search_field" 
				data-field="year" name="year">
				{foreach name=i from=$list_years item = _year}
				<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">Năm {$_year}</option>
				{/foreach}
			</select>
		</div>
	</div>
    <!-- Basic Bootstrap Table -->
	<div class="card">
		<div class="card-body">
			<div id="tableStock" class="table-container no-shadow overflow-x-auto text-nowrap">
				<table cellpadding="0" cellspacing="0" class="table dragable table-bordered">
					<thead><tr>
						{if $deviceType ne 'phone'}
						<th class="align-center text-center bg-lighter w-px-50 h-px-35" rowspan="2">STT</th>
						{/if}
						<th class="align-center bg-lighter h-px-35" rowspan="2">Họ và tên</th>
						<th class="align-center bg-lighter h-px-35" rowspan="2">Phòng ban</th>
						<th class="align-center bg-lighter h-px-35" rowspan="2">Chức vụ</th>
						<th class="align-center bg-primary text-white text-center h-px-35" colspan="2">Chỉ tiêu được giao</th>
						<th class="align-center bg-xmax text-white text-center h-px-35" colspan="4">Hoàn thành</th>
						<th class="align-center bg-success text-white text-center h-px-35 w-px-100" rowspan="2">TỔNG<br />ĐIỂM</th>
						<th class="align-center bg-success text-white text-center h-px-35 w-px-100" rowspan="2">% HOÀN<br />THÀNH</th>
						<th class="align-center text-center h-px-35 w-px-150 bg-lighter" rowspan="2">LƯƠNG NHẬN</th>
					</tr>
					<tr>
						<th class="align-center bg-primary text-white text-center no-sticky h-px-35 w-px-100">Giao dịch</th>
						<th class="align-center bg-primary text-white text-center border-end no-sticky h-px-35 w-px-100">Nhân viên</th>
						<th class="align-center bg-xmax text-white text-center h-px-35 w-px-100">Độc quyền</th>
						<th class="align-center bg-xmax text-white text-center h-px-35 w-px-100">Quỹ chéo</th>
						<th class="align-center bg-xmax text-white text-center h-px-35 w-px-100">C. Nhượng</th>
						<th class="align-center bg-xmax text-white h-px-35 border-end w-px-100">Nhân viên</th>
					</tr>
					</thead>
					<tbody class="holder_salary">
						{section name=i loop=$list_preloaders}
						<tr>
							{if $deviceType ne 'phone'}
							<td><div class="animate-bg rounded-pill w-100 h-px-15"></td>{/if}
							<td><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td class="bg-primary"><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td class="bg-primary"><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td class="bg-xmax"><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td class="bg-xmax"><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td class="bg-xmax"><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td class="bg-xmax"><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td class="bg-success"><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td class="bg-success"><div class="animate-bg rounded-pill w-100 h-px-15"></td>
							<td class="text-center"><div class="animate-bg rounded-pill w-100 h-px-15"></td>
						</tr>
						{/section}
					
					</tbody>
				</table>
			</div>	
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	@media screen and (min-width: 575px) and (max-width:1400px){
		.table-container tr th:not(.no-sticky):nth-child(2),
		.table-container tr td:not(.no-sticky):nth-child(2){
			position:sticky;
			top:0; left:46px;
			z-index:1;
		}
		.table-container tr td:not(.no-sticky):nth-child(2){
			background:var(--bs-white);
		}
	}
</style>
<script type="text/javascript">
	$(function(){
		$Core.salary.list({});
	});
</script>
{/literal}

