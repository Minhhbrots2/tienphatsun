<div class="ui-title-bar-container">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$core->get_Lang('Menu')}</h1>
			</div>
		</div>
		<div class="action-bar" style="margin-top: -5px">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/index.php?mod={$mod}&act=edit" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="row">
					<div class="col-md-4">
						<div class="ui-annotated-section__annotation">
							<div class="ui-annotated-section__title">
								<h2 class="ui-heading">Menus</h2>
							</div>
							<div class="ui-annotated-section__description" data-tg-refresh="credit-card-gateway-scroll">
								<p>Danh sách menu sẽ giúp khách hàng duyệt web của bạn dễ dàng hơn.</p>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="ui-annotated-section__content">
							{section name=i loop=$allItem}
							{assign var = lstLink value = $clsClassTable->getLinks($allItem[i].menu_id)}
							<section class="ui-card menus__menu-structure">
								<!-- ui_menu_empty -->
								<div class="ui-card__header">
									<div class="ui-stack">
										<div class="ui-stack-item ui-stack-item--fill">
											<h2 class="ui-heading">{$allItem[i].title}</h2>
										</div>
										<div class="ui-stack-item">
											<a class="text-blue" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&menu_id={$core->encryptID($allItem[i].menu_id)}">{$core->get_Lang('Edit')}</a>
											<a class="display-toggle text-blue" href="javascript:void(0);" title="Ẩn/Hiện Menu">{$core->makeIcon('angle-down')}</a>
										</div>
									</div>
								</div>
								<div class="card__section ui-card__section--type-subdued">
									<div class="ui-type-container"> <!-- hide -->
										<div class="linklist-container">
											<ul class="link-list-group js-menu-resources ui-sortable--next menu__list-items">
												{if $lstLink}
													{section name=k loop=$lstLink}
													{assign var = _alias value = $core->replaceSpace($lstLink[k].title)}
													<li class="link-list-group-item js-menu-resource">
														<div class="ui-stack ui-stack--wrap ui-stack--spacing-none sortable-menu-item">
															<div class="ui-stack-item ui-stack-item--fill menu-item ui-sortable__item ui-sortable__helper-visible">
																<span class="ui-sortable__handle">
																	<svg class="next-icon next-icon--color-slate-lighter next-icon--size-12 drag-handle"> 
																		<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-drag-handle">
																			<svg id="next-drag-handle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Drag-Handle</title><path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path></svg></svg>
																		</use> 
																	</svg>
																</span>
																<div class="menu-item-name ui-sortable__item ui-sortable__helper-visible">{$lstLink[k].title}</div>
																{if $clsClassTable->checkSubMenu($allItem[i].menu_id, $_alias)}
																{assign var = _submenu_id value = $clsClassTable->getSubMenuId($allItem[i].menu_id,$_alias)}
																<a class="link-list-button" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&menu_id={$core->encryptID($_submenu_id)}&parent_id={$allItem[i].menu_id}">{$core->get_Lang('Edit')}</a>
																{else}
																<a href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&parent_id={$allItem[i].menu_id}&alias={$_alias}" class="link-list-button">{$core->get_Lang('Create')}</a>
																{/if}
															</div>
														</div>
													</li>
													{/section}	
												{else}
													<li class="menu__list-item-add">
														<a class="ui-button w-100 ui-button--full-width btn add-button menu__add-menu-item" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&menu_id={$core->encryptID($allItem[i].menu_id)}">
															{$core->makeIcon('plus-circle', $core->get_Lang('Addlink'))}
														</a>
													</li>
												{/if}
											</ul>
										</div>
									</div>
								</div>
							</section>
							{assign var = lstChild value = $clsClassTable->getItems($allItem[i].menu_id)}
							{if $lstChild[0].menu_id ne ''}
								{section name=k loop=$lstChild}
								<section class="ui-card menus__menu-structure">
									<div class="ui-card__header ui_menu_empty">
										<div class="ui-stack">
											<div class="ui-stack-item ui-stack-item--fill">
												<h2 class="ui-heading">{$allItem[i].title}{$core->makeIcon('angle-right',$lstChild[k].title)}</h2>
											</div>
											<div class="ui-stack-item">
												<a class="text-blue" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&menu_id={$core->encryptID($lstChild[k].menu_id)}&parent_id={$allItem[i].menu_id}">{$core->get_Lang('Edit')}</a>
												<a class="display-toggle text-blue" href="javascript:void(0);" title="Ẩn/Hiện Menu">{$core->makeIcon('angle-down')}</a>
											</div>
										</div>
									</div>
									<div class="card__section mt-0 ui-card__section--type-subdued">
										<div class="ui-type-container"> <!-- hide -->
											<div class="linklist-container">
												<ul class="link-list-group js-menu-resources ui-sortable--next menu__list-items">
													{assign var = lstLink2 value = $clsClassTable->getLinks($lstChild[k].menu_id)}
													{if $lstLink2}
														{section name=n loop=$lstLink2}
														{assign var = _alias2 value = $core->replaceSpace($lstLink2[n].title)}
														<li class="link-list-group-item js-menu-resource">
															<div class="ui-stack ui-stack--wrap ui-stack--spacing-none sortable-menu-item">
																<div class="ui-stack-item ui-stack-item--fill menu-item ui-sortable__item ui-sortable__helper-visible">
																	<span class="ui-sortable__handle">
																		<svg class="next-icon next-icon--color-slate-lighter next-icon--size-12 drag-handle"> 
																			<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-drag-handle">
																				<svg id="next-drag-handle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Drag-Handle</title><path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path></svg></svg>
																			</use> 
																		</svg>
																	</span>
																	<div class="menu-item-name ui-sortable__item ui-sortable__helper-visible">{$lstLink2[n].title}</div>
																	{if $clsClassTable->checkSubMenu($lstChild[k].menu_id, $_alias2)}
																	{assign var = _submenu_id2 value = $clsClassTable->getSubMenuId($lstChild[k].menu_id,$_alias2)}
																	<a class="link-list-button" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&menu_id={$core->encryptID($_submenu_id2)}&parent_id={$lstChild[k].menu_id}">{$core->get_Lang('Edit')}</a>
																	{else}
																	<a href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&parent_id={$lstChild[k].menu_id}&alias={$_alias2}" class="link-list-button">{$core->get_Lang('Create')}</a>
																	{/if}
																</div>
															</div>
														</li>
														{/section}	
													{else}
														<li class="menu__list-item-add">
															<a class="ui-button w-100 ui-button--full-width btn add-button menu__add-menu-item" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&menu_id={$core->encryptID($lstChild[k].menu_id)}">
																{$core->makeIcon('plus-circle', $core->get_Lang('Addlink'))}
															</a>
														</li>
													{/if}
												</ul>
											</div>
										</div>
									</div>
								</section>
								{assign var = lstChild2 value = $clsClassTable->getItems($lstChild[k].menu_id)}
								{if $lstChild2[0].menu_id ne ''}
									{section name=j loop=$lstChild2}
									<section class="ui-card menus__menu-structure">
										<div class="ui-card__header ui_menu_empty">
											<div class="ui-stack">
												<div class="ui-stack-item ui-stack-item--fill">
													<h2 class="ui-heading">{$allItem[i].title}{$core->makeIcon('angle-right', $lstChild[k].title)}{$core->makeIcon('angle-right',$lstChild2[j].title)}</h2>
												</div>
												<div class="ui-stack-item">
													<a class="text-blue" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&menu_id={$core->encryptID($lstChild2[j].menu_id)}&parent_id={$lstChild[k].menu_id}">{$core->get_Lang('Edit')}</a>
													<a class="display-toggle text-blue" href="javascript:void(0);" title="Ẩn/Hiện Menu">{$core->makeIcon('angle-down')}</a>
												</div>
											</div>
										</div>
										<div class="card__section mt-0 ui-card__section--type-subdued">
											<div class="ui-type-container"> <!-- hide -->
												<div class="linklist-container">
													<ul class="link-list-group js-menu-resources ui-sortable--next menu__list-items">
														{assign var = lstLink3 value = $clsClassTable->getLinks($lstChild2[j].menu_id)}
														{if $lstLink3}
															{section name=m loop=$lstLink3}
															<li class="link-list-group-item js-menu-resource">
																<div class="ui-stack ui-stack--wrap ui-stack--spacing-none sortable-menu-item">
																	<div class="ui-stack-item ui-stack-item--fill menu-item ui-sortable__item ui-sortable__helper-visible">
																		<span class="ui-sortable__handle">
																			<svg class="next-icon next-icon--color-slate-lighter next-icon--size-12 drag-handle"> 
																				<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-drag-handle">
																					<svg id="next-drag-handle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Drag-Handle</title><path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path></svg></svg>
																				</use> 
																			</svg>
																		</span>
																		<div class="menu-item-name ui-sortable__item ui-sortable__helper-visible">{$lstLink3[m].title}</div>
																		<a href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&menu_id={$lstChild2[j].menu_id}" class="link-list-button">{$core->get_Lang('Edit')}</a>
																	</div>
																</div>
															</li>
															{/section}	
														{else}
															<li class="menu__list-item-add">
																<a class="ui-button w-100 ui-button--full-width btn add-button menu__add-menu-item" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&menu_id={$core->encryptID($lstChild2[j].menu_id)}">
																	{$core->makeIcon('plus-circle', $core->get_Lang('Addlink'))}
																</a>
															</li>
														{/if}
													</ul>
												</div>
											</div>
										</div>
									</section>
									{/section}
									<!-- End Level 3 -->
								{/if}
								{/section}
								<!-- End Level 2 -->
								{/if}
							{/section}
							<!-- End Level 1-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
				