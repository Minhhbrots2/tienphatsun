<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Tổng quan giá các phân khu</span></h4>
			<p class="text-muted mb-0">Hệ thống hỗ trợ bán hàng Ocean City</p>
		</div>
	</div>
	<div class="box_statistic">
		<div class="row">
			{foreach from=$arr_type item=_type key=key name=i}
				<div class="col-12 col-md-6 col-xxl-6">
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel border-0 no-shadow panel-default">
							<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center ">
								<h3 class="panel-title">{$_type.title}</h3>
							</div>
							<div class="panel-body px-0">
								{assign var = gId value = $clsISO->getUniqid()}
								<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_building_chart" data-options='{ldelim}"type":"{$_type.type}"{rdelim}'>
									<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
									<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
									<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
									<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
									<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
									<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
									<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
									<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
		
	</div>
</div>
{literal}
<script>
	$(document).ready(function(){
//		$Core.report.load_report_project();
	});
</script>
{/literal}