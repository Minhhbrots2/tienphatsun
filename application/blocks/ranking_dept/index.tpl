{assign var = screen_sales value = $clsISO->screenSales()}
{assign var = has_business_area value = $clsConfiguration->getValue('has_business_area','1')}
{if ($type eq 'area' || $type == "") && $has_business_area eq '1'}
	<div class="ranking-gbox ranking-regional rounded-2 mb-2">
		<div class="ranking-dept-header mb-3">
			<div class="d-flex align-items-center gap-3">
				<img class="w-px-50" src="{$header_configs.LogoWhite}" />
				<div class="ranking-title ext text-yellow">
					<span class="text-uppercase">BXH KHỐI KD {$smarty.now|date_format:"%Y"}</span>
					<br /><small class="font-normal text-white text-fs-12">(Cập nhật {$smarty.now|date_format:"%d/%m/%Y"})</small>
				</div>
			</div>
		</div>
		<div class="ranking-dept-body">
			<div class="ranking__dept-menu mb-3">
				<ul class="d-flex justify-content-between ranking__dept-nav ranking__dept-panel">
					<li class="flex-fill"><a href="javascript:void(0);" onClick="$Core.dashboard.handle_ranking_dept(this, event)" 
					date_type="_month" gId="regional_{$gId}" class="text-white text-center ranking__dept-link active">Tháng {$current_month}</a></li>
					<li class="flex-fill"><a href="javascript:void(0);" onClick="$Core.dashboard.handle_ranking_dept(this, event)" 
					date_type="_quarter" gId="regional_{$gId}" class="text-white text-center ranking__dept-link">Quý 0{$current_quarter}</a></li>
					<li class="flex-fill"><a href="javascript:void(0);" onClick="$Core.dashboard.handle_ranking_dept(this, event)" 
					date_type="_year" gId="regional_{$gId}" class="text-white text-center ranking__dept-link">Năm {$current_year}</a></li>
				</ul>
			</div>
			<div class="d-flex align-items-center justify-content-between mb-1">
				<div class="text-upper text-white text-fs-12">Phòng ban</div>
				<div class="d-flex gap-1 text-fs-12 pr-2 text-white align-items-center">
					<div class="text-upper text-center w-px-40">Số GD</div>
					{if $screen_sales}
					<div class="text-upper text-center w-px-80">Doanh số</div>
					{/if}
				</div>
			</div>
			<div class="ajax" gId="regional_{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_department_billing&tp=regional" 
				data-options='{ldelim}"disp":"table"{rdelim}'>
				{section name=i loop=$list_preloaders max=10}
				<div class="d-flex align-items-center justify-content-between py-1/5 px-2 bg-white-100 rounded-2 mb-1">
					<div class="d-flex gap-1 align-items-center">
						<div class="avatar avatar-xs rounded-pill animate-bg"></div>
						<div class="d-flex text-white flex-column gap-0">
							<h4 class="text-fs-13 mb-1">
								<div class="animate-bg w-px-100 rounded-pill h-px-15"></div>
							</h4>
							<span class="text-fs-10">
								<div class="animate-bg w-px-50 rounded-pill h-px-15"></div>
							</span>
						</div>
					</div>
					<div class="d-flex gap-2 text-white text-center align-items-center">
						<div class="d-flex justify-content-center text-center w-px-40">
							<div class="animate-bg w-px-30 rounded-pill h-px-15"></div>
						</div>
						{if $screen_sales}
						<div class="d-flex justify-content-center text-center w-px-90">
							<div class="animate-bg w-px-50 rounded-pill h-px-15"></div>
						</div>
						{/if}
					</div>
				</div>
				{/section}
			</div>
		</div>
	</div>
{/if}
{if $type eq 'department' || $type == ""}
<div class="ranking-gbox ranking-dept rounded-2">
	<div class="ranking-dept-header mb-3">
		<div class="d-flex align-items-center gap-3">
			<img class="w-px-50" src="{$header_configs.LogoWhite}" />
			<div class="ranking-title ext text-yellow">
				<span class="text-uppercase">BXH Phòng KD {$smarty.now|date_format:"%Y"}</span>
				<br /><small class="font-normal text-white text-fs-12">(Cập nhật {$smarty.now|date_format:"%d/%m/%Y"})</small>
			</div>
		</div>
	</div>
	<div class="ranking-dept-body">
		<div class="ranking__dept-menu mb-3">
			<ul class="d-flex justify-content-between ranking__dept-nav ranking__dept-panel">
				<li class="flex-fill"><a href="javascript:void(0);" onClick="$Core.dashboard.handle_ranking_dept(this, event)" 
				date_type="_month" gId="{$gId}" class="text-white text-center ranking__dept-link active">Tháng {$current_month}</a></li>
				<li class="flex-fill"><a href="javascript:void(0);" onClick="$Core.dashboard.handle_ranking_dept(this, event)" 
				date_type="_quarter" gId="{$gId}" class="text-white text-center ranking__dept-link">Quý 0{$current_quarter}</a></li>
				<li class="flex-fill"><a href="javascript:void(0);" onClick="$Core.dashboard.handle_ranking_dept(this, event)" 
				date_type="_year" gId="{$gId}" class="text-white text-center ranking__dept-link">Năm {$current_year}</a></li>
			</ul>
		</div>
		<div class="d-flex align-items-center justify-content-between mb-1">
			<div class="text-upper text-white text-fs-12">Phòng ban</div>
			<div class="d-flex gap-1 text-fs-12 pr-2 text-white align-items-center">
				<div class="text-upper text-center w-px-40">Số GD</div>
				{if $screen_sales}
				<div class="text-upper text-center w-px-80">Doanh số</div>
				{/if}
			</div>
		</div>
		<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_department_billing&tp=department" 
			data-options='{ldelim}"disp":"table"{rdelim}'>
			{section name=i loop=$list_preloaders max=10}
			<div class="d-flex align-items-center justify-content-between py-1/5 px-2 bg-white-100 rounded-2 mb-1">
				<div class="d-flex gap-1 align-items-center">
					<div class="avatar avatar-xs rounded-pill animate-bg"></div>
					<div class="d-flex text-white flex-column gap-0">
						<h4 class="text-fs-13 mb-1">
							<div class="animate-bg w-px-100 rounded-pill h-px-15"></div>
						</h4>
						<span class="text-fs-10">
							<div class="animate-bg w-px-50 rounded-pill h-px-15"></div>
						</span>
					</div>
				</div>
				<div class="d-flex gap-2 text-white text-center align-items-center">
					<div class="d-flex justify-content-center text-center w-px-40">
						<div class="animate-bg w-px-30 rounded-pill h-px-15"></div>
					</div>
					{if $screen_sales}
					<div class="d-flex justify-content-center text-center w-px-90">
						<div class="animate-bg w-px-50 rounded-pill h-px-15"></div>
					</div>
					{/if}
				</div>
			</div>
			{/section}
		</div>
	</div>
</div>
{/if}
<style>
	.ranking-gbox{
		width: 100%;
		position: relative;
		padding: 30px 30px 20px 30px;
	}
	.ranking-regional{
		background:linear-gradient(6deg, var(--rank-1) 44%, var(--rank-2));
		background-size: 400% 400%;
		-webkit-animation: gradient 15s ease infinite;
		animation: gradient 15s ease infinite;
	}
	.ranking-dept {
		background: linear-gradient(6deg, var(--rank-1) 44%, var(--rank-2));
		background-size: 400% 400%;
		-webkit-animation: gradient 15s ease infinite;
		animation: gradient 15s ease infinite;
	}
	.ranking-title.ext {
		font-size: 20px;
		line-height: 22px;
	}
	@media screen and (max-width:1400px){
		.ranking-gbox{
			padding: 20px 1.05rem 20px 1.05rem !important;
		}
		.ranking-title.ext{
			font-size:16px;
		}
	}
	@media screen and (max-width:575px){
		.ranking-gbox{ 
			padding:20px 1.05rem !important;
		}
		.ranking-title.ext{
			font-size:16px;
		}
	}
</style>