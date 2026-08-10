<div class="modal-dialog modal-xxs">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$core->get_Lang('Crop image')}</h5>
		</div>
		<form method="post" id="frmCropper" enctype="multipart/form-data">
			<div class="modal-body">
				<img id="cropper_{$uid}" class="w-100" style="min-height:300px" src="{$objectUrl}" />
				<input type="hidden" id="cropper-width-{$uid}" value="0" />
				<input type="hidden" id="cropper-height-{$uid}" value="0" />
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-bs-dismiss="modal">
					<i class="icon-retweet icon-white"></i> <span> {$core->get_Lang('Close')}</span>
				</button>
				<button type="button" class="btn btn-primary ui-cropper-tool" tour_id="{$tour_id}" data-method="getCroppedCanvas">
					<i class="icon-ok icon-white"></i> <span> {$core->get_Lang('Save')}</span>
				</button>
			</div>
		</form>
	</div>
</div>
{literal}
<style type="text/css">
	.cropper-view-box{
		border-radius:50%;
		-moz-border-radius:50%;
		-webkit-border-radius:50%;
		-khtml-border-radius:50%;
	}
</style>
{/literal}