<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="py-2 mb-2">
		<h4 class="fw-bold mb-1"><span>Báo cáo vắng mặt</span></h4>
		<p class="text-muted mb-0">Phòng ban kinh doanh {$smarty.const.BRAND_NAME}</p>
	</div>
	<div class="card">
		<div class="card-header position-relative border-bottom d-flex justify-content-center mb-2">
			<div class="d-flex justify-content-between">
				{assign var = uid value = $clsISO->getUniqid()}
				<button type="button" uid="{$uid}" onClick="$Core.report.set_month(this,event)" 
				tp="prev" class="btn btn-outline-default mr-1" title="Tháng trước">{$clsISO->makeIcon('bx-chevron-left')}</button>
				<div class="input-group-date mr-1">
					<input type="text" id="{$uid}" class="form-control w-px-125 isodatepicker" readonly 
					value="{$smarty.now|date_format:'%m/%Y'}" onchange="$Core.report.select_month(this,event)" maxlength="255" />
				</div>
				<button type="button" uid="{$uid}" onClick="$Core.report.set_month(this,event)" 
				tp="next" class="btn btn-outline-default" title="Tháng tiếp theo">{$clsISO->makeIcon('bx-chevron-right')}</button>
			</div>
		</div>
		<div id="holder_worktime" class="card-body{if $deviceType eq 'phone'} p-2{/if}">
			<div class="p-5 text-center">
				<p>Loading...</p>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		setTimeout(() => {
			$Core.report.load_content_worktime({});
		}, 100);
	});
</script>
{/literal}

