<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="card page_list">
		<div class="card-header btn-group d-flex justify-content-center pt-2 pb-0" role="group">
			<form class="d-flex" action="" id="frm_report_ns_club">
				<input type="date" class="form-control w-px-150 me-2" name="start_date" placeholder="dd/mm/yyyy" onChange="$Core.course.load_report_course(this,event)" value="{$start_date|date_format:'%Y-%m-%d'}" max="{$end_date|date_format:'%Y-%m-%d'}">
				<input type="date" class="form-control w-px-150" name="end_date" placeholder="dd/mm/yyyy" onChange="$Core.course.load_report_course(this,event)" value="{$end_date|date_format:'%Y-%m-%d'}" max="{$smarty.now|date_format:'%Y-%m-%d'}" min="{$start_date|date_format:'%Y-%m-%d'}">
			</form>
		</div>
		<div class="card-body pt-2">
			<div class="load_report_course overflow-auto" id="load_report_course">
				<table class="table no-bootstrap table-grid table-bordered" width="100%" cellpadding="0" id="lst_ns_club">
					<thead>
						<tr>
							<th class="bg-main text-center text-white fs-6 border-0" colspan="{$lstDate|@count + 4}">Báo cáo lan tỏa câu lạc bộ ngôi sao</th>
						</tr>
						<tr>
							<th class="bg-warning text-white border sticky-left" width="60" rowspan="3" style="min-width: 60px">STT</th>
							<th class="bg-warning align-center text-left text-white border sticky-left" rowspan="3" width="20%" style="min-width: 150px" >Tên sale</th>
							{foreach from=$lstDate item=_oDate key=key name=i}
								{assign var=lstCourse value=$_oDate.lstCourse}
								{if !empty($lstCourse)}
									<th class="bg-warning text-center text-white border" width="10%" {if !empty($lstCourse)}colspan="{$lstCourse|@count}"{/if}>{$clsISO->formatTimeDate($_oDate.time)}</th>
								{/if}
								
							{/foreach}
							{if $deviceType eq 'phone'}
								<th class="bg-warning align-center text-center text-white border fs-12 sticky-right" rowspan="3" width="10%">Tỷ lệ</th>
							{else}
								<th class="bg-warning align-center text-center text-white border fs-15 sticky-right" rowspan="3" width="10%">Tỷ lệ báo cáo</th>
							{/if}
						</tr>
						<tr>
							{foreach from=$lstDate item=_oDate key=key name=i}
								{assign var=lstCourse value=$_oDate.lstCourse}
								{if !empty($lstCourse)}
									{foreach from=$lstCourse item=_oCourse key=key name=i}
										<th class="bg-warning text-center text-white border"><a href="{$clsCourse->getLink($_oCourse.course_id)}" target="_blank">Link<i class="bx bx-link-external ml-1"></i></a></th>
									{/foreach}
								{/if}
							{/foreach}
						</tr>
					</thead>
					<tbody>
						{section name=i loop=$list_preloaders max=6}
							<tr>
								<td class="text-center border sticky-left" style="min-width: 60px"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								<td class="text-left border sticky-left" width="20%" style="min-width: 150px"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								{assign var=bc value=0}
								{foreach from=$lstDate item=_oDate key=key name=i}
									{assign var=lstCourse value=$_oDate.lstCourse}
									{if !empty($lstCourse)}
										{foreach from=$lstCourse item=_oCourse key=key name=i}
											{assign var=profile_ids value=$_oCourse.profile_ids}
											<td class="text-center border"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										{/foreach}
									{/if}
								{/foreach}
								<td class="text-center border sticky-right"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							</tr>
						{/section}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
{literal}
	<style>
		#lst_ns_club {
			width: fit-content;
			margin: auto;
		}
		.table td{
			padding: 3px 10px !important
		}
	</style>
	<script>
		$(document).ready(function(){
			$Core.course.load_report_course();
		});
	</script>
{/literal}