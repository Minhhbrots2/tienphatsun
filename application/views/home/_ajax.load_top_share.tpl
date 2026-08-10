{if !empty($list_shares)}
<div class="owl-carousel owl-share-slider">
	{foreach from=$list_shares item = _oShare}
	<div class="box_img bg-fill radius-3 overflow-hidden position-relative" data-caption="{$_oShare.title}" data-fancybox="gallery" data-src="{$_oShare.image}" style="background-image:url({$clsISO->getUrlImageFH($_oShare.image,320,480)});">
		{if $deviceType eq 'phone'}
		<div class="position-absolute bottom-0 w-100 p-1 figure">
			<h5 class="fs-11 text-white mb-0">{$_oShare.full_name}</h5>
			<span class="text-white fs-10">
				<i class='bx bx-time'></i>
				{$clsISO->getTimeAgo($_oShare.reg_date)}
			</span>
		</div>
		{else}
		<div class="w-100 position-absolute bottom-0 p-2 figure">
			<h5 class="fs-11 text-white mb-1">{$_oShare.full_name}</h5>
			<span class="text-muted fs-10">
				<i class='bx bx-time fs-10'></i>
				{$clsISO->getTimeAgo($_oShare.reg_date)}
			</span>			
		</div>
		{/if}
	</div>
	{/foreach}
	{if !empty($list_blanks)}
		{foreach from=$list_blanks item = _oBlank}
		<div class="box_img d-flex flex-column align-items-center justify-content-center w-100 h-px-{if $deviceType eq 'phone'}90{else}125{/if} text-center rounded-1 border p-2 cursor-pointer" onclick="$Core.share.open(this, event)" holderg="share" share_id="0" action="_add">
			<div class="text-center">
				<i class="bx bx-image fs-30"></i>
			</div>
			<span class="text-muted lh-base fs-10">Thêm ảnh tiếp khách</span>
		</div>
		{/foreach}
	{/if}
</div>
{else}
<div class="owl-carousel owl-share-slider">
	{foreach from=$list_blanks item = _oBlank}
	<div class="box_img d-flex flex-column align-items-center justify-content-center w-100 text-center rounded-1 border p-2 cursor-pointer" onclick="$Core.share.open(this, event)" 
	holderg="share" share_id="0" action="_add">
		<div class="text-center">
			<i class="bx bx-image-add fs-30"></i>
		</div>
		<span class="text-muted lh-base fs-10">Thêm ảnh tiếp khách</span>
	</div>
	{/foreach}
</div>
{/if}
<script type="text/javascript">
	var number_item = '{$number_item}';
</script>
{literal}
<style type="text/css">
	.box_img{ height:180px; }
	@media screen and (max-width:1600px){
		.box_img{ height:125px; }
	}
	.bg-fill{
		background-size: cover;
		background-position: center; 
		background-repeat:no-repeat;
	}
	.figure{ 
		background: linear-gradient(0deg,rgba(0,0,0,0.60) 0%, rgba(0,0,0,0.00) 100%); 
		pointer-events: none;
	}
</style>
{/literal}