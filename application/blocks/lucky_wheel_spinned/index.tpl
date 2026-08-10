<div class="ranking-dept rounded-2">
	<div class="ranking-dept-header mb-3">
		<div class="d-flex align-items-center justify-content-center">
			<div class="ranking-title ext text-yellow">🎁 Danh sách nhận quà</div>
		</div>
	</div>
	<div class="ranking-dept-body">
		<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=wheel_spin" 
		data-options='{ldelim}"wheel_id":{$wheel_id}{rdelim}'>
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
					<div class="d-flex justify-content-center text-center w-px-90">
						<div class="animate-bg w-px-50 rounded-pill h-px-15"></div>
					</div>
				</div>
			</div>
			{/section}
		</div>
	</div>
</div>
<style>
	.ranking-dept {
		width: 100%;
		background: linear-gradient(-45deg, #20040a, #f5d3e1, #ad0246);
		background-size: 400% 400%;
		-webkit-animation: gradient 15s ease infinite;
		animation: gradient 15s ease infinite;
		position: relative;
		padding: 30px 30px 20px 30px;
	}
	.ranking-title.ext {
		font-size: 20px;
		line-height: 24px;
		text-transform: uppercase;
	}
	@media screen and (max-width:1400px){
		.ranking-dept{
			padding: 20px 1.05rem 20px 1.05rem !important;
		}
		.ranking-title.ext{
			font-size:16px;
		}
	}
	@media screen and (max-width:575px){
		.ranking-dept{ 
			padding:20px 1.05rem !important;
		}
		.ranking-title.ext{
			font-size:16px;
		}
	}
</style>