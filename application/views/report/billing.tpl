<script type="text/javascript">
	var report_type = '{$report_type}';
</script>
<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="card no-shaddow">
		<div class="card-header">
			<div class="d-flex flex-wrap w-100 justify-content-between align-items-center">
				<div class="mb-2 mb-lg-0">
					<div class="d-flex align-items-center gap-2">
						<a href="{$PCMS_URL}/giao-dich.html" class="back" title="Quay lại"><img src="{$smarty.const.ICON_BACK}" /></a>
						<span class="text-upper fw-bold">Thống kê giao dịch chốt{if $report_type eq 'mwf'} quỹ F1{/if}</span>
					</div>
					<p class="mb-0 text-muted">Tổng cộng <strong class="text-main total_record">0</strong> giao dịch chốt</p>
				</div>
				<div class="sum mb-2 mb-lg-0"></div>
				<div class="search-top d-flex{if $deviceType eq 'phone'} w-100{/if} gap-1 align-items-center">
					<label class="text-nowrap d-none d-lg-block">Lọc theo:</label> 
					{assign var = gId value = $clsISO->getUniqid()}
					{if $report_type eq 'mwf'}
					<input type="hidden" class="search_field" name="report_type" value="{$report_type}" />
					<input type="hidden" class="search_field" name="billing_type" value="{$smarty.const._BILLING_TYPE_MWF_ID}" />
					{/if}
					<div class="input-group d-flex ox:w-100{if $deviceType ne 'phone'} w-px-{$box_width}{/if}" role="group">
						<select class="form-control search_field form-select" gId="{$gId}" name="month" 
							onChange="$Core.report.autosearch(this, event);"> 
							<option value="">Tháng</option>			
							{foreach from=$list_months item = _month}
							<option{if $_month eq $smarty.now|date_format:"%m"} selected{/if} value="{$_month}">Tháng {$_month}</option>
							{/foreach}
						</select>
						<select class="form-control search_field form-select" gId="{$gId}" name="year" 
							onChange="$Core.report.load_month(this,event)">
							{foreach from=$list_years item = _year}
							<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
							{/foreach}
						</select>
						{if $report_type eq 'common'}
						<select class="form-control search_field form-select" name="group_product" 
							onChange="$Core.report.autosearch(this, event);">
							<option value="">Giao dịch</option>
							<option value="CAO_TANG">Cao tầng</option>
							<option value="THAP_TANG">Thấp tầng</option>
							<option value="CHO_THUE">Cho thuê</option>
						</select>
						{/if}
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
			<!-- <div class="mb-2 rounded-3" style="background:#bff6ffab">
				<div class="row">
					<div class="col-12 col-md-4">
						<table class="table table-bordered">
							<tr>
								<td width="50%">Tổng số căn bán</td>
								<td></td>
							</tr>
							<tr>
								<td>Tổng doanh số</td>
								<td></td>
							</tr>
							<tr>
								<td>MWF F1</td>
								<td></td>
							</tr>
							<tr>
								<td>MWF Chéo</td>
								<td></td>
							</tr>
							<tr>
								<td>Cao tầng Vin F1</td>
								<td></td>
							</tr>
							<tr>
								<td>Cao tầng Vin chéo</td>
								<td></td>
							</tr>
						</table>
					</div>
				</div>
			</div> -->
			<div id="tableReport" class="table-wrapper freeze-table dragscroll text-nowrap">
				<table  class="table table-bordered table-ilooca text-nowrap" width="100%">
					<thead><tr>
						<td colspan="15" class="text-upper h-px-30 text-dark fw-bold text-center">
							Báo cáo bán hàng{if $report_type eq 'mwf'} quỹ F1{/if}</td>
					</tr>
					<tr>
						<th width="3%" class="align-center text-dark bg-body text-center">STT</th>
						<th class="align-center bg-body text-dark text-left">Dự án</th>
						<th class="align-center bg-body text-dark text-center">Ngày cọc CĐT</th>
						<th class="align-center bg-body text-dark text-center">Toà</th>
						<!--<th class="align-center bg-body text-left">Họ tên Sales</th> -->
						<th class="align-center bg-body text-dark text-left">Mã căn</th>
						<th class="align-center bg-body text-dark text-center">Loại hình</th>
						<th class="align-center bg-body text-center">Giá bán</th>
						<th class="align-center bg-body text-dark text-center">Ngày ký OTP</th>
						<th class="align-center bg-body text-dark text-center lh-xs">Ngày khách<br />cọc</th>
						<th class="align-center bg-body text-dark text-center lh-xs">Ngày ký<br />HĐMB</th>
						<th class="align-center bg-body text-dark text-center lh-xs">Ngày ký<br />dự kiến</th>
						<th class="align-center bg-body text-dark text-center">Tình trạng</th>
						<th class="align-center bg-body text-dark text-left">Đại lý bán</th>
						<th class="align-center bg-body text-dark text-left">Đại lý nguồn</th>
					</tr></thead>
					<tbody id="holder_report_billings">
						{section name=i loop=$list_preloaders max=20}
							<tr>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
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
	.freeze-table {
        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
	}
	.freeze-table .table th{
		line-height:35px;
		vertical-align:middle;
	}
	@media screen and (max-width:648px){
		.freeze-table .table tr>th:nth-child(1),
		.freeze-table .trBilling td:nth-child(1){
			border-right:1px solid #DDD;
		}
	}
	@media screen and (min-width:648px){
		.freeze-table .table{
			margin-bottom:0;
			min-width:1200px;
			max-width:16000px;
		}
		.freeze-table .table tr>th:nth-child(3),
		.freeze-table .trBilling td:nth-child(3){
			border-right:1px solid #DDD;
		}
	}
	.tr_canceled td{ background:#ffd0d0;}
</style>
<script type="text/javascript">
	$(function(){
		setTimeout(() => {
			$('#tableReport').freezeTable({
				'columnNum': {/literal}{$columnNum}{literal},
				'scrollable': false,
				'columnKeep': true,
			});
		},500);
	});
</script>
{/literal}