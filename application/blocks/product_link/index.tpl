<section class="section section-xxs">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-10 col-xs-offset-0 col-md-offset-2">
				<img class="img-responsive" width="100%" src="{$clsConfiguration->getValue('PageFAN_IMAGE')}" />
			</div>
			<div class="text-box-container text-box-left">
				<div class="text-box col-xs-22 col-xs-offset-1 col-sm-offset-0 bg-overlay-white col-md-6 col-sm-12">
					<h2 class="headline fs-28 title">{$clsConfiguration->getValue('PageFAN_NAME')}</h2>
					<div class="text">
						{$clsConfiguration->getValue('SiteMsg_FAN')}
					</div>
					<div class="wrap mt-5">
						<a class="btn btn-square" href="{$clsConfiguration->getValue('PageFAN_URL')}">{$clsConfiguration->getValue('PageFAN_URL_TITLE')}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>