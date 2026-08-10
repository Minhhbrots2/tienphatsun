<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Meta Tags')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{if $pvalTable gt '0'}Cập nhật{else}Thêm{/if} Meta Tags</h1>
			</div>
		</div>
	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>
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
									<h2 class="ui-heading">Meta Tags</h2>
								</div>
								<div class="ui-annotated-section__description">
									Chỉnh sửa dữ liệu meta tags giúp tối ưu nội dung của bạn hiển thị trên công cụ tìm kiếm như Google, Bing...
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<header class="ui-card__header">
											<div class="ui-stack ui-stack--wrap">
												<div class="ui-stack-item ui-stack-item--fill">
													<h2 class="ui-heading">{$core->get_Lang('PreviewSearchResult')}</h2>
												</div>
											</div>
										</header>
										<div class="ui-card__section">
											<div class="ui-type-container">
												<div class="holderPrevSeo">
													{$clsClassTable->getPreviewSEO($pvalTable)}
												</div>
											</div>
										</div>
										<div class="next-card__section">
											<div class="ui-form__section ">
												<div class="form-group">	
													<label class="col-form-label">{$core->get_Lang('Link')}</label>
													<div class="input-group">
														<span class="input-group-addon">{$DOMAIN_NAME}</span>
														<input class="form-control" name="config_link" value="{$oneItem.config_link}" maxlength="255" type="text" >
													</div>
												</div>
												<div class="form-group">
													<div class="ui-form__label-wrapper">
														<label class="col-form-label">{$core->get_Lang('TitlePage')}</label>
														<p class="type--subdued">{$core->get_Lang('NumberOfCharactersUsed')}: <span data-bind="titleCharsRemainingText()" class="title-counter__charactor">0</span>/70</p>
													</div>
													<input type="text" class="form-control" clsTable="Meta" pvalTable="{$pvalTable}" name="config_value_title" value="{$oneItem.config_value_title}" />
												</div>
												<div class="form-group">	
													<div class="ui-form__label-wrapper">
														<label class="col-form-label">{$core->get_Lang('Meta Description')}</label>
														<p class="type--subdued">{$core->get_Lang('NumberOfCharactersUsed')}: <span class="description-counter__charactor">0</span>/320</p>
													</div>
													<textarea class="form-control" clsTable="Meta" pvalTable="{$pvalTable}" rows="4" name="config_value_intro">{$oneItem.config_value_intro}</textarea>
												</div>
												<div class="form-group">	
													<label class="col-form-label">{$core->get_Lang('Meta Keyword')}</label>
													<textarea class="form-control" rows="4" data-length-max="320" name="config_value_keyword">{$oneItem.config_value_keyword}</textarea>
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
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default">
					{$core->makeIcon('angle-left', $core->get_Lang('Meta Tags'))}
				</a>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group">{$saveBtn}</div>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript"> 
	var pvalTable = '{$pvalTable}',
		domain_name="{$DOMAIN_NAME}";
</script>
{literal}
<script type="text/javascript">
	$().ready(function(){
		$('input[name=config_link]').on('keyup', $Core.util.delay(function(){
			var regex = domain_name,
				config_link = $(this).val();
			if(config_link.match(regex)){
				$(this).val(config_link.replace(regex,''));
			}
			load_preview_search('Meta', pvalTable);
		},100));
	});
</script>
{/literal}