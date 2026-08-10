<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="row">
		<div class="col-12 col-lg-10 offset-lg-1">
			<div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
				<div class="oOGXbGhDZt mb-2 mb-lg-0">
					<div class="d-flex align-items-center gap-2">
						<a href="{$clsISO->getLink('marketing')}" class="back mr-2 goToPage" title="Quay lại">
							<img src="{$smarty.const.ICON_BACK}" />
						</a>
						<div class="d-flex flex-column">
							<h4 class="fw-bold mb-0"><span>Ngân sách Marketing</span></h4>
							<p class="text-muted mb-0">Danh sách đăng ký chạy Marketing {$smarty.const.BRAND_NAME}</p>
						</div>
					</div>
				</div>
				<div class="xaQwJlqAyc">
					<div class="search-top d-flex{if $deviceType eq 'phone'} w-100{/if} gap-1 align-items-center">
						<input type="month" class="js__date-field form-control search_field" value="{$current_month}" data-field="month" 
							onChange="$Core.marketing.do_search(this, event);" />
					</div>
				</div>
			</div>	
			<div class="card no-shadow">
				
				<div class="card-body">
					<div class="table-container no-shadow dragscroll overflow-x-auto text-nowrap">
						<table cellpadding="0" cellspacing="0" class="table table-bordered table-striped text-nowrap" width="100%">
							<thead><tr>
								{if $deviceType ne 'phone'}
								<th rowspan="2"  width="3%" class="align-center bg-body text-center">STT</th>
								{/if}
								<th rowspan="2" class="align-center bg-lighter h-px-35 text-left">Tên Sale</th>
								<th rowspan="2" class="align-center bg-lighter h-px-35 text-left">Phòng ban</th>
								<th rowspan="2" class="align-center bg-lighter h-px-35 text-left">Dự án</th>
								<th colspan="4" class="align-center text-center bg-lighter h-px-35 text-right">Ngân sách</th>
								<th rowspan="2" class="align-center bg-lighter h-px-35 w-px-150 text-center">Thời gian</th>
							</tr>
							<tr>
								<th class="align-center no-sticky text-center bg-lighter h-px-35">FB ADS</th>
								<th class="align-center text-center bg-lighter h-px-35">GG ADS</th>
								<th class="align-center text-center bg-lighter h-px-35">Zalo ADS</th>
								<th class="align-center text-center bg-lighter h-px-35" style="border-right-width:1px !important">Tiktok ADS</th>
							</tr></thead>
							<tbody class="holder_regis">
								{section name=i loop=$list_preloaders max=20}
								<tr>
									{if $deviceType ne 'phone'}
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									{/if}
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
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
	$(function(){ $Core.marketing.load_regis({}); });
</script>
{/literal}