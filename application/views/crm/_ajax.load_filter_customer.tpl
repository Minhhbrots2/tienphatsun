<form class="p-2">
	<div class="form-group mb-2">
		<label class="form-label mb-1 text-nowrap">Tình trạng</label>
		<select name="status_follow_id" data-field="status_follow_id" class="form-control search_field form-select">
			<option value="0">Tình trạng</option>
			{$clsProperty->getSelectByProperty('FOLLOWUP_STATUS', 0)}
		</select>
	</div>
	<div class="form-group mb-2">
		<label class="form-label mb-1 text-nowrap">Thời gian</label>
		<div class="input-group-date w-100 mr-2">
			<input type="text" class="form-control search_field isodaterangepicker" name="date_range_follow" data-field="date_range_follow" />
		</div>
	</div>
	<button type="button" onClick="$Core.crm.filter_customer(this, event)" action="_SEARCH_ACTION" class="btn btn-block btn-primary">
		<span>Tìm kiếm</span>
	</button>
</form>
{literal}
<script type="text/javascript">
	$(function(){
		$('.isodaterangepicker').daterangepicker({
			timePicker: false,
			"drops": "auto",
			"autoApply": true,
			alwaysShowCalendars: true,
			startDate: moment().startOf('year'),
			endDate: moment().endOf('year'),
			ranges: {
			   'Hôm nay': [moment(), moment()],
			   'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   '7 ngày qua': [moment().subtract(6, 'days'), moment()],
			   '30 ngày qua': [moment().subtract(29, 'days'), moment()],
			   'Tháng này': [moment().startOf('month'), moment().endOf('month')],
			   'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			opens: (deviceType=='phone'?'center':'right'),
			locale: {format: 'DD/MM/YYYY'}
		});
	});
</script>
{/literal}