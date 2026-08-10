<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="row"><div class="col-12 col-lg-10 offset-lg-1 col-xxxl-8 offset-xxxl-2">
		<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
			<div class="header">
				<h4 class="fw-bold mb-1">Báo cáo vắng mặt {$smarty.const.BRAND_NAME}</span></h4>
				<span class="text-muted">Báo cáo nhân viên vắng mặt hàng ngày</span>
			</div>
			{if $clsISO->checkPermissionGroup('SALE')}
			<button type="button"{if $is_send_report eq '1'} onClick="$Core.worktime.open(this, event)"{/if} data-bs-toggle="tooltip" 
			data-bs-placement="top" data-bs-trigger="hover" title="Thêm báo cáo" worktime_id="0" class="btn btn-outline-primary{if $is_send_report ne '1'} disabled{/if}">
				+ Thêm{if $deviceType ne 'phone'} báo cáo{/if}
			</button>
			{/if}
		</div>
		<div class="card">
			{if $clsISO->checkPermissionGroup('SALE')}
			<div class="card-header d-flex flex-wrap justify-content-between align-items-center">
				<h5 class="mb-0">Có <strong class="total_record text-danger">{$total_record}</strong> báo cáo</h5>
				<form method="POST">
					<div class="search d-flex">
						<div class="input-group input-date-picker mr-2">
							<i class="ico ico-calendar"></i>
							<input type="text" value="{$start_date}" class="form-control from_date search_field w-px-100" placeholder="Từ ngày" data-field="start_date">
							<input type="text" value="" class="form-control to_date search_field w-px-100" 
							placeholder="Đến ngày" data-field="end_date">
						</div>
						<button type="button" class="btn btn-success" onClick="$Core.worktime.do_search(this, event)">
							<i class="bx bx-search"></i>
						</button>
					</div>
				</form>
			</div>
			{else}
			<div class="card-header border-bottom d-flex justify-content-center mb-2">
				<div class="d-flex justify-content-between">
					{assign var = uid value = $clsISO->getUniqid()}
					<button type="button" uid="{$uid}" onClick="$Core.worktime.set_date(this,event)" 
					tp="prev" class="btn btn-icon btn-outline-default mr-1" data-bs-toggle="tooltip" title="Ngày trước">{$clsISO->makeIcon('bx-chevron-left')}</button>
					<div class="input-group-date mr-1">
						<input type="text" id="{$uid}" class="form-control w-px-150 isodatepicker" readonly 
						value="{$clsISO->convertTimeToText($smarty.now)}" onchange="$Core.worktime.select_date(this,event)" maxlength="255" />
					</div>
					<button type="button" uid="{$uid}" onClick="$Core.worktime.set_date(this,event)" 
					tp="next" class="btn btn-icon btn-outline-default" data-bs-toggle="tooltip" title="Ngày tiếp theo">{$clsISO->makeIcon('bx-chevron-right')}</button>
				</div>
			</div>
			{/if}
			<div class="card-body">
				<div class="alert alert-warning">
					<i class='bx bx-error blink'></i> Thời gian gửi báo cáo vắng mặt hợp lệ trước 10h:00 hàng ngày.
				</div>
				<div class="holder_worktimes">
					<table class="table" width="100%">
						{section name=i loop=$list_preloaders max=18}
						<tr>
							<td width="10%"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
						</tr>
						{/section}
					</table>
				</div>
			</div>
		</div>
	</div></div>
</div>
{literal}
<style type="text/css">
	.card-header{ padding:1.25rem 1.25rem;}
	.ui-datepicker{ z-index:9999 !important;}
	.awe__post-item, .awe__post-comment{ padding:15px 0 0;}
</style>
<script type="text/javascript">
	$(function(){
		$Core.worktime.list({});
	});
</script>
{/literal}