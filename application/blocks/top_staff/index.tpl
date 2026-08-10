<div class="dbx-card h-100">
	<div class="dbx-card__head">
		<span class="dbx-card__ic"><i class="bx bx-star"></i></span>
		<h5 class="dbx-card__title">Nhân viên xuất sắc</h5>
		<span class="dbx-card__chip">Tháng {$smarty.now|date_format:"%m/%Y"}</span>
	</div>
	<div class="dbx-card__body dbx-card__body--flush ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=top_staff"
	data-options='{ldelim}"skin":"dbx"{rdelim}'>
		<div class="loader text-center py-8">
			<img src="{$URL_IMAGES}/loading.gif" />
			<p>Loading...</p>
		</div>
	</div>
</div>