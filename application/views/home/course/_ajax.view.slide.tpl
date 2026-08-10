<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollabe">
		<div class="modal-content overflow-y">
			<div class="modal-header border-bottom">
				<h5 class="modal-title" id="modalTopTitle">{$oneSlide.title}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body overflow-y">
				{if !empty($list_images)}
				<div class="slideshow owl-carousel mb-3">
					{foreach name=i from=$list_images item = image}
						{if !empty($image)}
						<div class="slideshow-item d-flex align-items-center justify-content-center">
							<img src="{$image}" class="img-responsive img-fluid" />
						</div>
						{/if}
					{/foreach}
				</div>
				{/if}
				<div class="mb-3">
					{$clsProfile->getIndentityV4($oneSlide.user_id, $oneSlide.oneProfile)}
				</div>
				<div class="p-4 mb-3 bg-lighter rounded-2">
					<h6 class="mb-2">Nội dung</h6>
					<div class="tinyContennt">
						{$oneSlide.content}
					</div>
				</div>
				{$core->getBlock('comment', ['table_id' => $slide_id, 'clsTable' => 'Slide'])}
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.slideshow{width:100%;background:#DDD;border-radius:4px;-moz-border-radius:4px;
	-webkit-border-radius:4px;-khtml-border-radius:4px;overflow:hidden;}
	.slideshow-item > img{ max-height:500px;}
	.owl-dots{display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;
	display:flex;flex-wrap:wrap;position:absolute;left:0;width:100%;bottom:12px;justify-content:center}
	.owl-dot{width:12px;height:12px;border-radius:100%;background:#ddd;margin:6px}
	.owl-dot.active{background:#ffeb3b}
</style>
{/literal}