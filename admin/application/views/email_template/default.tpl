<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a class="btn btn-default ui-breadcrumb" href="{$PCMS_URL}/index.php?mod=setting" title="{$core->get_Lang('Setting')}">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Setting')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$core->get_Lang('emailtemplate')}</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/?mod={$mod}&act=edit" class="ui-button ui-button--primary ui-title-bar__action" title="{$core->get_Lang('Addnew')}">{$core->get_Lang('Addnew')}</a>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<div class="ui-layout"><div class="ui-layout__sections">
	<div class="ui-layout__section">
		<div class="ui-annotated-section__content"><div class="ui-form__section form-horizontal ui-card__section">
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="row">
						<div class="col-md-4">
							<div class="ui-annotated-section__title">
								<h2 class="ui-heading">Nội dung email</h2>
							</div>
							<div class="ui-annotated-section__description">
								Những email này được gửi tự động tới bạn hoặc khách hàng. Click vào tên mẫu email để chỉnh sửa
							</div>
						</div>
						<div class="col-md-8">
							<div class="ui-annotated-section__content" >
								<div class="next-card">
									<div class="next-card__header">
										<h2 class="next-heading">{$core->get_Lang('All Email Template')}</h2>
									</div>
									<div class="section-content">
										<div class="next-card__section">
											<div class="has-bulk-actions pages">
												<div class="hastable">
													<table class="table table-vertical table-striped" cellspacing="0" cellpadding="0" width="100%">
														{if $allItem}
															{section name=i loop=$allItem}
															<tr class="{cycle values="row1,row2"}">
																<td class="text-left">{$clsClassTable->getTitle($allItem[i].email_template_id)}</td>
																<td class="text-center" width="60px" style="white-space: nowrap;">
																	<div class="btn-group">
																		<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
																			<i class="icon-cog"></i> 
																			<span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu" style="right:0px !important; left:auto">
																			<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&email_template_id={$core->encryptId($allItem[i].email_template_id)}">{$core->makeIcon('pencil',$core->get_Lang('edit'))}</a></li>
																			{if $clsISO->_DEV()}
																			<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/index.php?mod={$mod}&act=delete&email_template_id={$core->encryptId($allItem[i].email_template_id)}">{$core->makeIcon('times',$core->get_Lang('delete'))}</a></li>
																			{/if}
																		</ul>
																	</div>
																</td>
															</tr>	
															{/section}
														{else}
															<tr><td colspan="10" class="text-center">{$core->get_Lang('No Data')} !</td></tr>
														{/if}
													</table>
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
		</div></div>
	</div></div>
</div>