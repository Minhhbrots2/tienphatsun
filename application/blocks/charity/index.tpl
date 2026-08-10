{if $deviceType eq 'phone'}
<div class="w-100 d-flex align-items-start mb-2">
	<a class="text-white">Tổng Quỹ MXCE {$smarty.now|date_format:"%Y"}</a>
</div>
<div class="d-flex align-items-center gap-2">
	<span>{$total_charity}</span>
	<a class="text-white text-fs-12 text-decoration-underline" href="https://docs.google.com/spreadsheets/d/1LcAcrbJRY9uWfBSwzZ_lsCVbxBgT7gwiE_5t7kK2J_4/edit?gid=0#gid=0" target="_blank">Chi tiết <i class="bx bx-chevron-right"></i></a>
</div>	
{else}
<div class="card bg-success h-100 box">
	<div class="card-body d-flex justify-content-center align-items-center">
		<div class="marquee flex-fill overflow-hidden {if $deviceType eq 'phone'}w-100{/if}">
			<div class="marquee__inner flex-fill d-flex align-items-center gap-4 justify-content-center">
				<div class="item d-flex flex-column align-items-center gap-1">
					<p class="text-nowrap mb-0 text-fs-13 text-white">Cập nhật quỹ Xây cầu Sơn La - MXCE 2025</p>
					<div class="d-flex align-items-center gap-2 mt-n1">
						<a class="text-nowrap fs-26 text-main  position-relative fw-bold" target="_blank" href="https://docs.google.com/spreadsheets/d/1LcAcrbJRY9uWfBSwzZ_lsCVbxBgT7gwiE_5t7kK2J_4/edit?gid=0#gid=0" rel="nofollow noindex" style="background: linear-gradient(90deg, #f78200, #ffffff, #f78200);-webkit-background-clip: text;-webkit-text-fill-color: transparent">{$total_charity}</a>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{/if}