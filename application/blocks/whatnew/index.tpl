{if !empty($one_notify)}
<div id="{$clsISO->getUniqid()}" class="modal right modal-auto-open" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<span class="fs-13 text-danger">What's New</span><br />
					{$one_notify.title}
				</h5>
			</div>
			<div class="modal-body">
				<div class="img-banner mb-3">
					{if !empty($one_notify)}
					<img src="{$one_notify.image}" class="img-fluid" />
					{else}
					<img src="{$URL_IMAGES}/backgrounds/whatnew.jpg" class="img-fluid" />
					{/if}
				</div>
				<div class="tinyContennt o-text__content">{$one_notify.content}</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-block btn-primary closeEv" 
					data-bs-dismiss="modal" aria-label="Close">Đóng lại</button>
			</div>
		</div>
	</div>
</div>
{/if}
<style>
	.img-banner{
		margin-top:-15px;
		margin-left:-15px;
		margin-right:-15px;
	}
	.o-text__content{
		font-size: 16px;
		line-height: 26px;
	}
</style>