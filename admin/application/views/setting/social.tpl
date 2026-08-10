<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Setting')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$core->get_Lang('Social')}</h1>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<form method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Facebook</h2>
								</div>
								<div class="ui-annotated-section__description">
									Nhập đường link liên kết với Mạng xã hội Facebook.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">Facebook Link</label>
															<input type="text" class="form-control" placeholder="Nhập liên kết facebook tại đây" name="iso-facebook_link" value="{$clsConfiguration->getValue('facebook_link')}" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Twitter</h2>
								</div>
								<div class="ui-annotated-section__description">
									Nhập đường liên kết với Mạng xã hội Twitter.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">Twitter</label>
															<input type="text" class="form-control" name="iso-twitter_link" value="{$clsConfiguration->getValue('twitter_link')}" placeholder="Nhập liên kết Twitter tại đây" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">LinkedIn</h2>
								</div>
								<div class="ui-annotated-section__description">
									Nhập đường liên kết với Mạng xã hội LinkedIn.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">LinkedIn</label>
															<input type="text" class="form-control" name="iso-linkedin_link" value="{$clsConfiguration->getValue('linkedin_link')}" placeholder="Nhập đương dẫn LinkedIn tại đây" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Google</h2>
								</div>
								<div class="ui-annotated-section__description">
									Nhập đường liên kết với Mạng xã hội Google.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">{$core->get_Lang('Google Plus')}</label>
															<input type="text" class="form-control" name="iso-google_link" value="{$clsConfiguration->getValue('google_link')}" placeholder="Nhập liên kết với Google Plus tại đây" />
														</div>
													</div>
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">{$core->get_Lang('Youtube')}</label>
															<input type="text" class="form-control" name="iso-youtube_link" value="{$clsConfiguration->getValue('youtube_link')}" placeholder="Nhập liên kết với Youtube tại đây" />
														</div>
													</div>
													{if $clsConfig->get('video_homepage',0) eq '1'}
													<div class="form-group">
														<div class="col-md-12">
															<label class="col-form-label">Video giới thiệu(Video giới thiệu công ty, tổ chức....)</label>
															<input type="text" class="form-control" name="iso-video_homepage" value="{$clsConfiguration->getValue('video_homepage')}" placeholder="Nhập đường dẫn video  Youtube tại đây" />
														</div>
													</div>
													{/if}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<!-- End section -->
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group">{$saveBtn}</div>
			</div>
		</div>
	</div>
</form>