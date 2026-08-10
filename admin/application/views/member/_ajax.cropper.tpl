<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">{$core->get_Lang('Crop image')}</h4>
		</div>
		<form method="post" id="frmCropper" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-md-8">
						<div class="cropper-wrap">
							<img id="cropper" class="img-responsive" src="{$objectUrl}" />
						</div>
					</div>
					<div class="col-xs-12 col-md-4">
						<ul class="ui-cropper-tools">
							<li><a class="ui-cropper-tool ui-rotate-left-right" data-method="scaleX" data-option="-1" href="javascript:void(0)">
								<span class="ico"></span> 
								{$core->get_Lang('Rotate left and right')}
							</a></li>
							<li><a class="ui-cropper-tool ui-rotate-up-bottom" data-method="scaleX" data-option="-1" href="javascript:void(0)">
								<span class="ico"></span> 
								{$core->get_Lang('Rotate up bottom')}
							</a></li>
							<li><a class="ui-cropper-tool ui-rotate-left-side" data-method="move" data-option="-10" data-second-option="0" href="javascript:void(0)">
								<span class="ico"></span> 
								{$core->get_Lang('Left side')}
							</a></li>
							<li><a class="ui-cropper-tool ui-rotate-right-side" data-method="move" data-option="10" data-second-option="0" href="javascript:void(0)">
								<span class="ico"></span> 
								{$core->get_Lang('Right side')}
							</a></li>
							<li>
								<div class="form-row">
									<div class="col-md-6">
										<label class="col-form-label">Width</label>
										<input type="text" class="form-control numberonly" id="cropper-width" placeholder="width" value="0" />
									</div>
									<div class="col-md-6">
										<label class="col-form-label">Height</label>
										<input type="text" class="form-control numberonly" id="cropper-height" placeholder="height" value="0" />
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-warning" data-dismiss="modal">
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