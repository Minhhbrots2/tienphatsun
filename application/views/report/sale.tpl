<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Báo cáo kết quả kinh doanh</span></h4>
			<p class="text-muted fs-6 mb-0">Phòng ban kinh doanh {$smarty.const.BRAND_NAME}</p>
		</div>
		<div class="p__right">
			{if $deviceType ne 'phone'}
			<div class="d-flex form-inline">
				<div class="input-group-date w-px-200 mr-2">
					<input type="text" class="form-control search_field isodaterangepicker1" data-field="date_range" style="padding-left: 40px"/>
				</div>
				<div class="form-group w-px-150">
					<select class="form-control search_field iso-select2" data-field="department_id" 
					data-width="100%" data-allow-clear="true">
						<option value="0">Phòng ban</option>
						{foreach from=$list_departments key=department_id item = _text }
						<option value="{$department_id}">{$_text}</option>
						{/foreach}
					</select>
				</div>
			</div>
			{/if}
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="card">
		{if $deviceType eq 'phone'}
		<div class="card-header position-relative border-bottom d-flex justify-content-center mb-2">
			<div class="input-group-date w-px-200 mr-2">
				<input type="text" class="form-control search_field isodaterangepicker1" data-field="date_range" onChange="$Core.report.do_share_search(this, event)" name="date_range" style="padding-left: 40px" />
			</div>
			<select data-field="department_id" class="form-control search_field iso-select2" 
			onChange="$Core.report.do_share_search(this, event)">
				<option value="0">Phòng ban</option>
				{section name=i loop=$list_departments}
				<option value="{$list_departments[i].property_id}">{$list_departments[i].title}</option>
				{/section}
			</select>
		</div>
		{/if}
		<div id="holder_report_sales" class="card-body{if $deviceType eq 'phone'} p-2{/if}">
			<div class="p-5 text-center">
				<p>Loading...</p>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$('.isodaterangepicker1').daterangepicker({
			timePicker: false,
			"drops": "auto",
			"autoApply": true,
			alwaysShowCalendars: true,
			startDate: moment().startOf('year'),
			endDate: moment().endOf('year'),
			autoUpdateInput: true,
			ranges: {
			   'Hôm nay': [moment(), moment()],
			   'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   '7 ngày qua': [moment().subtract(6, 'days'), moment()],
			   '30 ngày qua': [moment().subtract(29, 'days'), moment()],
			   'Tháng này': [moment().startOf('month'), moment().endOf('month')],
			   'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			opens: (deviceType=='phone'?'center':'left'),
			locale: {format: 'DD/MM/YYYY'}
		});
		setTimeout(() => {
			$Core.report.load_report_sales({});
		}, 500);
		$('.search_field').on('change', function(){
			$Core.report.load_report_sales({});
		});
	});
</script>
{/literal}