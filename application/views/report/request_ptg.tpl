<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Thống kê yêu cầu phiếu tính giá</span></h4>
			<p class="text-muted mb-0">Hệ thống nội bộ CA - {$smarty.const.BRAND_NAME}</p>
		</div>
	</div>
	<div class="form-row">
		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
			<div class="box_item_statistic h-100 p-3 bg-danger text-white rounded-2"> 
				<p class="title_statistic fs-6 mb-2">Tổng số yêu cầu</p>
				<div class="number_total fs-3">{$total_record}<span class="fs-14 ml-1">yêu cầu</span></div>
			</div>
		</div>
		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
			<div class="box_item_statistic h-100 p-3 text-white rounded-2" style="background:#eba000"> 
				<p class="title_statistic fs-6 mb-2">Đang chờ</p>
				<div class="number_total fs-3">{$total_pendding}<span class="fs-14 ml-1">yêu cầu</span></div>
			</div>
		</div>
		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
			<div class="box_item_statistic h-100 p-3 text-white rounded-2" style="background:#1d6a01"> 
				<p class="title_statistic fs-6 mb-2">Đã phản hồi</p>
				<div class="number_total fs-3">{$total_success}<span class="fs-14 ml-1">yêu cầu</span></div>
			</div>
		</div>
		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
			<div class="box_item_statistic h-100 p-3 text-white rounded-2 bg-primary"> 
				{if $deviceType eq 'phone'}
					<p class="title_statistic fs-6 mb-2">T.Gian trung bình</p>
				{else}
					<p class="title_statistic fs-6 mb-2">Thời gian trung bình</p>
				{/if}
				<div class="number_total fs-3">{$avg_time_feedback}</div>
			</div>
		</div>
	</div>
	<div class="dashboard-panel-item dashboard-panel-item--full mb-2">
		<div class="panel border-0 no-shadow panel-default mb-0">
			<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center">
				<h3 class="panel-title">Biểu đồ thống kê thời gian phản hồi admin</h3>
			</div>
			<div class="panel-body px-0">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_requestchart" data-options='{ldelim}{rdelim}'>
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