<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Settings')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Thông báo</h1>
			</div>
		</div> 
		{if $clsISO->_DEV() || 1 eq 1}
		<div class="action-bar" style="margin-top: -5px">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="open_message(this)" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
				</div>
			</div>
		</div>
		{/if}
	</div>
</header>
<div class="clearfix"></div>
<form action="" method="post" enctype="multipart/form-data">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				{section name=i loop=$listMessage}
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">{$core->get_Lang($listMessage[i].setting)}</h2>
								</div>
								<div class="ui-annotated-section__description">
									{assign var = DescriptionField value = $listMessage[i].setting|cat:"_Description"}
									{$core->get_Lang($DescriptionField)}
								</div>
								{if $clsISO->_DEV()}
								<button class="btn btn-default" onClick="delete_message('{$listMessage[i].setting}')">{$core->get_Lang('Delete')}</button>
								{/if}
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section">
												<div class="p-md-3">
													{if $listMessage[i].setting eq 'SiteMsg_FAN'}
													<div class="form-group">
														<label class="col-form-label">Tiêu đề</label>
														<input class="form-control" name="iso-PageFAN_NAME" value="{$clsConfiguration->getValue('PageFAN_NAME')}" />
													</div>
													<div class="form-group">
														<label class="col-form-label">Tiêu đề URL</label>
														<input class="form-control" name="iso-PageFAN_URL_TITLE" value="{$clsConfiguration->getValue('PageFAN_URL_TITLE')}" />
													</div>
													<div class="form-group">
														<label class="col-form-label">URL</label>
														<input class="form-control" name="iso-PageFAN_URL" value="{$clsConfiguration->getValue('PageFAN_URL')}" />
													</div>
													<div class="form-group">
														<label class="col-form-label">Image</label>
														<div class="input-group">
															<input class="form-control" id="isoman_hidden_image" name="iso-PageFAN_IMAGE" value="{$clsConfiguration->getValue('PageFAN_IMAGE')}" />
															<div class="input-group-btn">
																<button type="button" class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="{$clsConfiguration->getValue('PageFAN_IMAGE')}" isoman_name="image">{$core->get_Lang('Change')}</button>
															</div>
														</div>
													</div>
													{/if}
													<textarea id="textarea_{$listMessage[i].setting}_editor{$now}" class="textarea_intro_editor" name="iso-{$listMessage[i].setting}" style="width:100%">{$clsConfiguration->getValue($listMessage[i].setting)|html_entity_decode}</textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				{/section}
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="UpdateConfiguration" name="submit" type="hidden">
				<div class="ui-page-actions__button-group">{$saveBtn}</div>
			</div>
		</div>
	</div>
</form>