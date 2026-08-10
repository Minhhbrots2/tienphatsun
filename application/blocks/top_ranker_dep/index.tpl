<div class="top-ranker mt-2 rounded-2 bg-lighter">
	<div class="top-ranker-header mb-3 d-flex justify-content-between align-items-center">
		<div class="d-flex align-items-center gap-2">
			<img class="w-px-40" src="{$header_configs.LogoWhite}" />
			<div class="ranking-title ext text-white {if $deviceType ne 'phone'}fs-18{else}fs-16{/if}">
				TOP 10 THI ĐUA CÁ NHÂN Phòng {$department_name} {$smarty.now|date_format:"%Y"} <br /> 
				<small>( {$start_date} - {$end_date} )</small>
			</div>
		</div>
		<input class="js__handle-department" type="hidden"  name="department_id" gid="{$gId}" value="{$oneProfile.department_id}">
	</div>
	<div class="top-ranker-body position-relative zindex-2">
		<table class="table table-ranker" cellpadding="0" cellspacing="0">
			<thead><tr>
				<th class="align-center w-px-50 lg:d-none h-px-40 text-center">STT</th>
				<th class="align-center h-px-40">Họ và tên</th>
				<th class="align-center h-px-40 text-center">Điểm số</th>
				{if $deviceType ne 'phone'}
				<th class="align-center h-px-40 text-center">Du lịch</th>{/if}
			</tr></thead>
			<tbody class="ajax js__block-report-today" data-url="{$PCMS_URL}/index.php?mod=campaign&act=top_10_ranker">
				{section name=i loop=$list_preloaders max=10}
				<tr class="nohover">
					<td class="lg:d-none">
						<div class="animate-bg w-100 h-px-15 rounded-2"></div>
					</td>
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					{if $deviceType ne 'phone'}
					<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
					{/if}
				<tr>
				{/section}
			</tbody>
		</table>
	</div>
</div>
{literal}
	<style>
		.slt_form_ranker_dep {
			background: #FFF0;
			color: #FFF;
		}
		.slt_form_ranker_dep option {
			color: #333;
		}
		.slt_form_ranker_dep:focus {
			background: #FFF0 !important;
			color: #FFF !important;
			border-color: #FFF !important;
		}
	</style>
{/literal}