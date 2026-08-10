{literal}
<script type="text/javascript">
	function active_comment(_this){
		var status = $(_this).attr('status'),
			comment_id = $(_this).attr('comment_id'),
			$_adata = {'status':status, 'comment_id':comment_id};
		
		$Core.alert.confirm("Xác nhận?", "Bạn có chắc chắn muốn thực hiện thao tác này?", function(){
			$.post(path_ajax_script+"/index.php?mod=comment&act=active_comment", $_adata, function(html){
				if(html.indexOf('_success') >= 0){
					window.location.reload();
				}
			});
		});
	}
</script>
{/literal}
<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Reviews')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$core->get_Lang('ReviewProduct')} "{$clsProduct->getTitle($oneItem.for_id)}"</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<button class="btn btn-default mr-half">{$core->get_Lang('Active All')}</button>
					<button class="btn btn-default">{$core->get_Lang('UnActive All')}</button>
				</div>
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
													<h2 class="ui-heading">{$core->get_Lang('PreviewReview')}</h2>
												</div>
											</div>
										</header>
										<div class="ui-card__section">
											<div class="ui-type-container">
												<div class="form-group">
													<div class="col-md-9 col-md-offset-3">
														<div class="gravatar pull-left mr-half gravatar--size-60">
															<img src="{$clsProfile->getAvatar($oneItem.profile_id)}" width="60px" />
														</div>	
														<div class="next-grid__cell">
															<h3 class="next-heading mb-2 next-heading--half-margin">
																<a target="_blank" href="{$PCMS_URL}/index.php?mod=customer&act=view&profile_id={$oneItem.profile_id}">{$clsProfile->getFullName($oneItem.profile_id)}</a>
															</h3>
															<p class="mb-0">{$clsProfile->getEmail($oneItem.profile_id)}</p>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-form-label text-right col-md-3">{$core->get_Lang('Review')}</label>
													<div class="col-md-9">
														<div class="rating-star">
															{$clsISO->generateHTMLStar($oneItem.number_star)}
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-form-label text-right col-md-3">{$core->get_Lang('Title')}</label>
													<div class="col-md-9 col-form-label">{$oneItem.title}</div>
												</div>
												<div class="form-group">
													<label class="col-form-label text-right col-md-3">{$core->get_Lang('Content')}</label>
													<div class="col-md-9 col-form-label">
														{$oneItem.content|html_entity_decode}
													</div>
												</div>
											</div>
										</div>
										<div class="ui-card__section">
											<div class="ui-type-container clearfix">
												<div class="pull-left">
													Vào lúc: {$core->makeIcon('clock-o', $clsISO->convertTimeToText($oneItem.reg_date, true))}
												</div>
												<div class="pull-right">
													{if $oneItem.is_active eq '1'}
													<button type="button" class="btn btn-success" onclick="active_comment(this)" status="0" comment_id="{$pvalTable}">{$core->makeIcon('times', $core->get_Lang('UnActive'))}</button>
													{else}
													<button type="button" class="btn btn-default" onclick="active_comment(this)" status="1" comment_id="{$pvalTable}">{$core->makeIcon('check', $core->get_Lang('Active'))}</button>
													{/if}
												</div>
											</div>
										</div>
									</div>
								</div>
								{if $lstReply[0].comment_id ne ''}
								<div class="ui-annotated-section__content mt-half">
									<div class="next-card">
										<header class="ui-card__header">
											<div class="ui-stack ui-stack--wrap">
												<div class="ui-stack-item ui-stack-item--fill">
													<h2 class="ui-heading">{$core->get_Lang('Reply')}</h2>
												</div>
											</div>
										</header>
										{section name=i loop=$lstReply}
										<div class="ui-card__section">
											<div class="ui-type-container">
												<div class="form-group">
													<div class="col-md-9 col-md-offset-3">
														<div class="gravatar pull-left mr-half gravatar--size-60">
															<img src="{$clsProfile->getAvatar($lstReply[i].profile_id)}" width="60px" />
														</div>	
														<div class="next-grid__cell">
															<h3 class="next-heading mb-2 next-heading--half-margin">
																<a target="_blank" href="{$PCMS_URL}/index.php?mod=customer&act=view&profile_id={$lstReply[i].profile_id}">{$clsProfile->getFullName($lstReply[i].profile_id)}</a>
															</h3>
															<p class="mb-0">{$clsProfile->getEmail($lstReply[i].profile_id)}</p>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-form-label text-right col-md-3">{$core->get_Lang('Content')}</label>
													<div class="col-md-9 col-form-label">
														{$lstReply[i].content|html_entity_decode}
													</div>
												</div>
											</div>
										</div>
										<div class="ui-card__section">
											<div class="ui-type-container clearfix">
												<div class="pull-left">
													Vào lúc: {$core->makeIcon('clock-o', $clsISO->convertTimeToText($lstReply[i].reg_date, true))}
												</div>
												<div class="pull-right">
													{if $lstReply[i].is_active eq '1'}
													<button type="button" class="btn btn-success" onclick="active_comment(this)" status="0" comment_id="{$lstReply[i].comment_id}">{$core->makeIcon('times', $core->get_Lang('UnActive'))}</button>
													{else}
													<button type="button" class="btn btn-default" onclick="active_comment(this)" status="1" comment_id="{$lstReply[i].comment_id}">{$core->makeIcon('check', $core->get_Lang('Active'))}</button>
													{/if}
												</div>
											</div>
										</div>
										{/section}
									</div>
								</div>
								{/if}
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</form>
