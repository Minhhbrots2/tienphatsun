<div class="container-xxl flex-grow-1 pt-2 container-p-y booking_page">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between py-1 mb-2">
		<div class="vRHjwnrkGa d-flex align-items-center gap-2">
			<a href="{$clsISO->getLink('booking')}" class="back goToPage">
				<img src="{$smarty.const.ICON_BACK}" /></a>
			<div class="ysXwpeiJqL">
				<h4 class="fw-bold mb-1">Thống kê booking</h4>
				<small class="text-muted fs-13">
					Danh sách <strong class="total_record text-danger">0</strong> chuyển quỹ thu chi nội bộ
				</small>
			</div>
		</div>
		<div class="mUWwbDvWBi xs:w-100 overflow-x-auto mt-2 mt-lg-0">
			{if !empty($arr_projects)}
			<div class="btn-group">
				{if $deviceType eq 'phone'}
				<button type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" 
					data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-filter-alt"></i></button>
				{else}
				<button type="button" class="btn btn-outline-default dropdown-toggle" data-bs-toggle="dropdown" 
					aria-expanded="false"><i class='bx bx-link-external'></i> Chọn dự án</button>
				{/if}
				<ul class="dropdown-menu dropdown-menu-end w-px-300">
					{foreach from=$arr_projects item=_oItem key=key name=i}
					<li><a class="dropdown-item active" href="javascript:void(0)">
						<div class="d-flex align-items-center justify-content-between">
							<span> {$_oItem.block_name}</span>
							<i class='bx bx-chevron-right'></i>
						</div>
					</a></li>
					{/foreach}
				</ul>
			</div>
			{/if}
		
		
			<nav class="nav nav-pills gap-1 ">
			{if !empty($arr_projects)}
				{foreach name=i from=$arr_projects item = _oProject}
				<li class="nav-item">
					<a data-toggle="ripple" onClick="$Core.booking.select_project(this, event)" project_id="{$_oProject.project_id}" block_id="{$_oProject.block_id}" class="nav-link {if $smarty.foreach.i.first} active{/if} py-1 tab_project border rounded-pill cursor-pointer">{$_oProject.block_name}</a>
				</li>
				{/foreach}
			{/if}
			</ul>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="holder_report">
				<div class="p-lg-5">
					<div class="p-lg-5 d-flex flex-column gap-2 align-items-center justify-content-center">
						<img src="{$URL_IMAGES}/loading.gif" class="w-px-100" />
						<p class="text-muted text-center">Đang tải dữ liệu thống kê <br />
							<i class="text-fs-10">Làm ơn chờ trong giây lát...</i>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function() {
		$Core.booking.load_report({});
	});
</script>
<style type="text/css">
	.table-report td,
	.table-report th{
		color:var(--bs-dark);
		border:1px solid #333;
	}
	.table-report .bg-header{
		background:rgb(255,169,176);
	}
	.table-report th.bg-head,
	.table-report td.bg-head{
		background:#FFF2CC
	}
	.table-report th.bg-pink,
	.table-report td.bg-pink{
		background:#FFDFE5
	}
</style>
{/literal}