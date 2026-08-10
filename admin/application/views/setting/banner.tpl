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
				<h1 class="ui-title-bar__title">Cấu hình</h1>
			</div>
		</div>
	</div><div class="collapsible-header">
		<div class="collapsible-header__heading"></div>
	</div>
</header>
<form method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				{foreach name=i from=$list_configs item = block_id}
				{assign var = uid value = $clsISO->getUniqid()}
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">{$core->get_Lang($block_id)}</h2>
								</div>
								<div class="ui-annotated-section__description">
									Cài đặt nội dung cho block.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="p-md-3">
													<div class="form-group">
														<label class="col-form-label">Label <span class="text-red">*</span></label>
														<input type="text" class="form-control" required="true" placeholder="Tên kinh doanh" name="banners_info[{$block_id}][label]" value="{$banners_info.$block_id.label}" />
													</div>
													<div class="form-group">
														<label class="col-form-label">Title <span class="text-red">*</span></label>
														<input type="text" class="form-control" required="true" placeholder="Nhập điện thoại liên hệ" name="banners_info[{$block_id}][title]" value="{$banners_info.$block_id.title}" />
													</div>
													<div class="form-group">
														<label class="col-form-label">Intro <span class="text-red">*</span></label>
														<textarea type="text" class="form-control" rows="4" placeholder="Nhập số hotline" name="banners_info[{$block_id}][intro]">{$banners_info.$block_id.intro}</textarea>
													</div>
													<div class="form-group">
														<label class="col-form-label">Image <span class="text-red">*</span></label>
														<div id="article-image-drop" class="article-image-drop">
															<div class="aspect-ratio aspect-ratio--banner aspect-ratio--interactive">
																<input type="hidden" id="isoman_hidden_{$uid}" name="banners_info[{$block_id}][image]" value="{$banners_info.$block_id.image}" /><img class="aspect-ratio__content" id="isoman_show_{$uid}" src="{$banners_info.$block_id.image}">
															</div>
															<div class="clearfix"></div>
															<div class="ui-stack ui-stack--wrap">
																<div class="ui-stack-item ui-stack-item--fill">
																	<button type="button" class="ui-button btn--link ajOpenDialog" isoman_for_id="{$uid}" isoman_val="{$banners_info.$block_id.image}" isoman_name="{$uid}">{$core->get_Lang('Change')}</button>
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
						</div>
					</div>
				</section>
				{/foreach}
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