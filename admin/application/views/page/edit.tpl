<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Page')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar__main-group">
		<div class="ui-title-bar__heading-group">
			{if $pvalTable gt '0'}
			<h1 class="ui-title-bar__title w-100">{$pvalTable}#{$clsClassTable->getTitle($pvalTable)}</h1>
			<div class="action-bar__item action-bar__item--link-container">
				<div class="action-bar__top-links">
					<a href="{$DOMAIN_NAME}{$clsClassTable->getLink($pvalTable)}" class="ui-button ui-button--transparent action-bar__link"  target="_blank">{$core->makeIcon('eye', $core->get_Lang('ViewOnWeb'))}</a>
				</div>
			</div>
			{else}
			<h1 class="ui-title-bar__title">{$core->get_Lang('Addnew News')}
			{/if}
		</div>
	</div>
</div>
<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="row">
			<div class="col-md-8 col-xs-12">
				<div class="ui-layout__item">
					<div class="ui-card">
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Title')}</label>
									<input type="text" class="form-control required" name="iso-title" value="{if $pvalTable gt '0'}{$clsClassTable->getTitle($pvalTable)}{/if}" required maxlength="255" />
								</div>
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('ShortIntro')}</label>
									 {$clsForm->showInput('intro')}
								</div>
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Content')}</label>
									 {$clsForm->showInput('content')}
								</div>
							</div>
						</div>
					</div>
					{if $pvalTable eq 1 }
						<div class="box light">
							<div class="box-title d-flex flex-wrap align-items-center">
								<div class="caption">
									<span class="bold">Liên hệ</span>
								</div>
							</div>	
							<div class="box-body">
								<div id="box_property">	
									{if !empty($more_information.contacts)}
									{foreach from=$more_information.contacts key=key item=oneContact name=i}
										{assign var="lstContact" value=$oneContact.contact}
										<div class="form-group item_property">
											<div class="border p-3">
												<div class="row" id="{$key}">
													<div class="col-12 col-md-6 mb-2">
														<label>Tên dự án</label>
														<input type="text" class="form-control property_keys" name="contacts[{$key}][project_name]" value="{$oneContact.project_name}" placeholder="Nhập tên dự án">
													</div>
													{if !empty($lstContact)}
														{foreach from=$lstContact item=contact name=i_c}
															{if $smarty.foreach.i_c.first}
																<div class="col-10 col-md-6 mb-2">
																	<label>Liên hệ</label>												
																	<input type="text" class="form-control property_keys" name="contacts[{$key}][contact][]" value="{$contact}" placeholder="Nhập liên hệ">
																</div>
															{else}
																<div class="col-10 col-md-6 mb-2 contact_more">
																	<div class="d-flex align-items-end">
																		<div class="flex-fill">
																			<label>Liên hệ</label>												
																			<input type="text" class="form-control property_keys" name="contacts[{$key}][contact][]" value="{$contact}" placeholder="Nhập liên hệ">
																		</div>
																		<a class="btn btn-icon text-danger" href="javascript:void(0)" onClick="$Core.page.deleteContact(this,event)" toId="{$key}"><i class="fa fa-trash mr-2" aria-hidden="true"></i></a>
																	</div>
																</div>
															{/if}
														{/foreach}
													{/if}
												</div>
												<div class="d-flex justify-content-between">
													<a class="btn" href="javascript:void(0)" onClick="$Core.page.addContact(this,event)" toId="{$key}"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Thêm liên hệ</a>
													<a class="btn btn-icon text-danger" href="javascript:void(0)" onclick="$Core.page.deleteItem(this,event)"><i class="fa fa-trash mr-2" aria-hidden="true"></i> Xóa liên hệ</a>
												</div>
													
											</div>
										</div>
									{/foreach}
									{else}
										{assign var=key value=$clsISO->getUniqid()}
										<div class="form-group item_property">
											<div class="border p-3">
												<div class="row" id="{$key}">
													<div class="col-12 col-md-6 mb-2">
														<label>Tên dự án</label>
														<input type="text" class="form-control property_keys" name="contacts[{$key}][project_name]" value="" placeholder="Nhập tên dự án">
													</div>
													<div class="col-10 col-md-6 mb-2">
														<label>Liên hệ</label>												
														<input type="text" class="form-control property_keys" name="contacts[{$key}][contact][]" value="" placeholder="Nhập liên hệ">
													</div>
												</div>
												<a class="btn" href="javascript:void(0)" onClick="$Core.page.addContact(this,event)" toId="{$key}"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Thêm liên hệ</a>	
											</div>
										</div>
									{/if}
									<a href="javascript:void(0)" id="addProperty" onClick="$Core.page.addProperty(this,event)"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Thêm thông tin</a>					
								</div>
							</div>
						</div>
					{/if}
					<div class="ui-card mt-half d-none">
						<div class="ui-card__section seo-section">
							<div class="ui-type-container">
								<div class="form-group">
									<div class="ui-form__label-wrapper">
										<label class="col-form-label">{$core->get_Lang('TitlePage')}</label>
										<p class="type--subdued">{$core->get_Lang('NumberOfCharactersUsed')}: <span data-bind="titleCharsRemainingText()" class="title-counter__charactor" >0</span>/70</p>
									</div>
									<input type="text" class="form-control input-bind__counter" clsTable="News" pvalTable="{$pvalTable}" name="config_value_title"{if $pvalTable gt '0'} value="{$clsISO->getPageTitle($pvalTable,'News')}"{/if} data-length-max="70" onKeyUp="titleCharsRemainingText(this)" />
								</div>
								<div class="form-group">	
									<div class="ui-form__label-wrapper">
										<label class="col-form-label">{$core->get_Lang('DescriptionPage')}</label>
										<p class="type--subdued">{$core->get_Lang('NumberOfCharactersUsed')}: <span class="description-counter__charactor">0</span>/320</p>
									</div>
									<textarea class="form-control input-bind__counter" clsTable="News" pvalTable="{$pvalTable}" onKeyUp="descriptionCharsRemainingText(this)" rows="4" data-length-max="320"  name="config_value_intro">{if $pvalTable gt '0'}{$clsISO->getPageDescription($pvalTable,'News')}{/if}</textarea>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="ui-layout__item">
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading">{$core->get_Lang('Status')}</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input {if $oneItem.is_online eq '1' || $pvalTable eq '0'} checked="checked"{/if} name="is_online" value="1" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> {$core->get_Lang('Show')}
									</div>
								</div>
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input{if $oneItem.is_online ne '1' and $pvalTable gt '0'} checked="checked"{/if} name="is_online" value="0" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> {$core->get_Lang('Hide')}
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading">Trang giới thiệu</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input {if $oneItem.is_about_us eq '1' || $pvalTable eq '0'} checked="checked"{/if} name="is_about_us" value="1" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> Có
									</div>
								</div>
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input{if $oneItem.is_about_us ne '1' and $pvalTable gt '0'} checked="checked"{/if} name="is_about_us" value="0" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> Không
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card mt-half">
						<header class="ui-card__header">
							<h2 class="ui-heading">{$core->get_Lang('Image')}</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div id="article-image-drop" class="article-image-drop">
									<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive">
										<input type="hidden" id="isoman_hidden_image" name="isoman_url_image" value="{$oneItem.image}" />
										<img class="aspect-ratio__content" id="isoman_show_image" src="{$oneItem.image}">
									</div>
									<div class="clearfix"></div>
									<div class="ui-stack ui-stack--wrap">
										<div class="ui-stack-item ui-stack-item--fill">
											<button type="button" class="ui-button btn--link ajOpenDialog" isoman_for_id="image" isoman_val="{$oneItem.image}" isoman_name="image">{$core->get_Lang('Change')}</button>
										</div>
										<div class="ui-stack-item{if $pvalTable gt '0' && $oneItem.image}{else} hidden{/if}">
											<button type="button" pvalTable="{$pvalTable}" clsTable="News" g="imgItem" class="ui-button btn--link deleteItemImage">Xóa</button>
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
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<input value="Update" name="submit" type="hidden">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary">
				<div class="ui-page-actions__button-group">
					{if $pvalTable gt '0'}
					<a class="btn btn-warning" onClick="delete_globe(this)" clsTable="News" pval_id="{$pvalTable}" pkey="{$pkeyTable}" return_url="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Delete')}</a>
					{/if}
				</div>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<div class="ui-page-actions__button-group">
					<a class="btn btn-default" href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Calcel')}</a>
					{$saveBtn} {$saveList}
				</div>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	var $type = '_NEWS';
	var $news_id = '{$pvalTable}';
</script>