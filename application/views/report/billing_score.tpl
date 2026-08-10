<script type="text/javascript">
	var report_type = '{$report_type}';
</script>
<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="row">
		<div class="col-12 col-xxl-10 offset-xxl-1">
			<div class="card no-shaddow">
				<div class="card-header">
					<div class="d-flex flex-wrap w-100 justify-content-between align-items-center">
						<div class="mb-2 mb-lg-0">
							<div class="d-flex align-items-center gap-2">
								<a href="{$PCMS_URL}/giao-dich.html" class="back" title="Quay lại"><img src="{$smarty.const.ICON_BACK}" /></a>
								<span class="text-upper fw-bold">Thống kê thông tin giao dịch chốt</span>
							</div>
						</div>
						<div class="search-top d-flex{if $deviceType eq 'phone'} w-100{/if} gap-1 align-items-center">
							<label class="text-nowrap d-none d-lg-block">Lọc theo:</label> 
							{assign var = gId value = $clsISO->getUniqid()}
							{if $report_type eq 'mwf'}
							<input type="hidden" class="search_field" name="report_type" value="{$report_type}" />
							<input type="hidden" class="search_field" name="billing_type" value="{$smarty.const._BILLING_TYPE_MWF_ID}" />
							{/if}
							<div class="input-group d-flex ox:w-100{if $deviceType ne 'phone'} w-px-{$box_width}{/if}" role="group">
								<select class="form-control search_field form-select" gId="{$gId}" name="month" 
									onChange="$Core.report.load_report_billings_score();"> 
									<option value="">Tháng</option>			
									{foreach from=$list_months item = _month}
									<option value="{$_month}">Tháng {$_month}</option>
									{/foreach}
								</select>
								<select class="form-control search_field form-select" gId="{$gId}" name="year" 
									onChange="$Core.report.load_month(this,event)" data-type="_report_billing" >
									<option value="">Năm</option>
									{foreach from=$list_years item = _year}
									<option value="{$_year}">{$_year}</option>
									{/foreach}
								</select>
								{if $report_type eq 'common'}
								{*<select class="form-control search_field form-select" name="group_product" 
									onChange="$Core.report.load_report_billings_score();">
									<option value="">Giao dịch</option>
									<option value="CAO_TANG">Cao tầng</option>
									<option value="THAP_TANG">Thấp tầng</option>
									<option value="CHO_THUE">Cho thuê</option>
								</select>*}
								<select class="form-control search_field form-select" name="billing_type" onChange="$Core.report.load_report_billings_score();">
									<option value="0">Loại hình</option>
									{$clsProperty->getSelectByProperty('_BILLING_TYPE',$billing_type)}
								</select>
								{assign var=toId value=$clsISO->getUniqid()}
								<select id="{$uid}" name="project_id" project_id="" block_id="" onChange="$Core.report.load_block(this, event)" toId="{$toId}" class="form-control search_field form-select">
									<option value="0">Dự án</option>
									{if !empty($lstProject)}
										{foreach from=$lstProject item=_oProject key=key name=i}
											<option value="{$_oProject.project_id}" {if $project_id eq $_oProject.project_id}selected{/if} >{$_oProject.title}</option>
										{/foreach}
									{/if}
								</select>
								<select id="{$toId}" data-bind="change" name="block_id" onChange="$Core.report.load_report_billings_score()" class="form-control search_field form-select">
									<option value="0">Phân khu</option>
								</select>
								{/if}
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-container overflow-x-auto no-shadow">
						<table cellpadding="0" cellspacing="0" width="100%" class="table table-billing table-bordered dragable mb-0 installed">
							<thead>
							<tr>
								{if $deviceType ne 'phone'}<th class="align-center text-dark bg-body text-center h-px-40" width="60">STT</th>{/if}
								<th class="align-center text-dark bg-body text-center h-px-40">Thông tin GD</th>
								<th class="align-center bg-body text-dark text-center h-px-40"><span class="text-success">Đã nhập</span></th>
								<th class="align-center bg-body text-dark text-center h-px-40"><span class="text-main">Chưa nhập</span></th>
							</tr></thead>
							<tbody id="holder_report_billings_score">
								{section name=i loop=$list_preloaders max=20}
									<tr>
										{if $deviceType ne 'phone'}<td><div class="animate-bg w-100 h-px-30 rounded-1"></div></td>{/if}
										<td><div class="animate-bg w-100 h-px-30 rounded-1"></div></td>
										<td><div class="animate-bg w-100 h-px-30 rounded-1"></div></td>
										<td><div class="animate-bg w-100 h-px-30 rounded-1"></div></td>
									</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$Core.report.load_report_billings_score({});
	});
</script>
{/literal}