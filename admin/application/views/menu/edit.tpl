<header class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Menu')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{if $pvalTable gt '0'}Cập nhật{else}Thêm{/if} Menu #{$pvalTable}</h1>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<form method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout"><div class="ui-layout__sections">
		<div class="ui-layout__section">
			<section class="ui-annotated-section-container"><div class="ui-annotated-section">
				<div class="row"><div class="col-md-8">
					<div class="ui-annotated-section__content mb-5">
						<div class="next-card ">
							<div class="next-card__section">
								<div class="ui-form__section">
									<div class="form-group">	
										<label class="col-form-label">{$core->get_Lang('Name')}</label>
										<input type="hidden" name="iso-parent_id" value="{$parent_id}" />
										<input type="text" name="iso-title"{if $parent_id eq '0'} onKeyUp="gen_alias(this)"{/if} placeholder="Nhập vào Tên của menu" value="{$oneItem.title}" class="form-control require" required="true" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="box menus__menu-structure light">
						<div class="box-title bold">{$core->get_Lang(Links)}</div>
						<div class="ui-card__section ui-card__section--type-subdued">
							<div class="ui-type-container">
								<div class="ui-empty-state text-center{if $lstLinks} hidden{/if}">
									<section class="ui-empty-state__section">
										<div class="ui-empty-state__subsection__foreground">
											<div class="ui-empty-state__subsection">
												<p class="ui-empty-state__details">Menu này chưa có liên kết nào.</p>
											</div>
										</div>
									</section>
								</div>
								<div class="link-list-sortable">
									<ul class="clear-ul link-list-group ui-sortable--next menu__list-items ui-sortable">
										{section name=i loop=$lstLinks}
										{assign var = uid value = $clsISO->getUniqid()}
										{assign var = order_no value = $smarty.section.i.index}
										<li class="link-list-group-item ui-sortable-handle" uid="{$uid}">
											<div class="ui-stack--spacing-none sortable-menu-item">
												<input type="hidden" id="{$uid}_id" name="links[{$order_no}][id]" value="{$lstLinks[i].id}" />
												<input type="hidden" id="{$uid}_order_no" name="links[{$order_no}][order_no]" value="{$lstLinks[i].order_no}" />
												<div class="row w-100">
													<div class="col-md-1">
														<span class="ui-sortable__handle mt">
															<svg class="next-icon next-icon--color-slate-lighter next-icon--size-12 drag-handle"> 
																<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-drag-handle">
																	<svg id="next-drag-handle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Drag-Handle</title><path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path></svg></svg>
																</use> 
															</svg>
														</span>
													</div>
													<div class="col-md-9">
														<div class="menu-item-name form-horizontal menu-item__form-wrapper">
															<div class="form-group">
																<div class="col-md-12">
																	<input id="{$uid}_title" name="links[{$order_no}][title]" value="{$lstLinks[i].title}" required="true" class="form-control require" placeholder="Nhập tên liên kết" type="text">
																</div>
															</div>
															<div class="form-group mb-0">
																<div class="col-md-5">
																	<select id="{$uid}_type" class="form-control{if !$clsISO->checkItemInArray($lstLinks[i].type,$lstNotIn)} Links___Type{/if}" uid="{$uid}" onChange="handler_linktype_change(this);" order_no="{$order_no}" uid="{$uid}" name="links[{$order_no}][type]">
																		{foreach from=$lstType key = _type item = text}
																		<option{if $lstLinks[i].type eq $_type} selected{/if} value="{$_type}">{$text}</option>
																		{/foreach}
																	</select>
																</div>
																<div class="col-md-7" id="{$uid}">
																	{if $lstLinks[i].type eq 'http'}
																	<input type="text" id="{$uid}_url" class="form-control require" required="true" name="links[{$order_no}][url]" value="{$lstLinks[i].url}" />
																	{elseif !$clsISO->checkItemInArray($lstLinks[i].type,$lstNotIn)}
																	{assign var = _url value = $clsClassTable->getUrl($lstLinks[i].type, $mod)}
																	<div class="ui-select__wrapper">
																		<div id="{$uid}_dropdown" class="dropdown mega-dropdown">
																			<button class="ui-select dropdown-toggle fixed-width btn-filter btn-choose-product" data-toggle="dropdown">
																				<span class="choosed-single">{$clsClassTable->getTitleLink($lstLinks[i].id,$lstLinks[i].type)}</span>
																				<svg class="next-icon next-icon--size-16">
																					<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron">
																						<svg id="select-chevron"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 16l-4-4h8l-4 4zm0-12L6 8h8l-4-4z"></path></svg></svg>
																					</use>
																				</svg>
																			</button>
																			<div class="dropdown-menu mega-dropdown-menu">
																				<div class="dropdown-panel-body">
																					<div class="form-group">
																						<div class="col-md-12">
																							<div class="input-group">
																								<span class="input-group-addon">{$core->makeIcon('search')}</span>
																								<input type="text" data-uid="{$uid}" data-url="{$_url}" placeholder="Tìm kiếm" class="form-control input-search" />
																							</div>
																						</div>
																					</div>
																					<div url="{$_url}" uid="{$uid}" class="build {$uid}">
																						Loading...
																					</div>
																				</div>
																				<div class="dropdown-panel-footer">
																					<div class="button-group pull-right">
																						<button type="button" data-url="{$_url}" disabled data-uid="{$uid}" class="btn btn-default">{$core->makeIcon('arrow-left')}</button>
																						<button type="button" data-url="{$_url}" data-uid="{$uid}" class="btn btn-default">{$core->makeIcon('arrow-right')}</button>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	{/if}
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-2 text-center">
														<button class="btn btn-default" onClick="remove_link(this)" type="button" name="button">
															{$core->makeIcon('trash')}
														</button>
													</div>
												</div>
											</div>
										</li>
										{/section}
									</ul>
								</div>
							</div>
						</div>
						<div class="box-end">
							<button onClick="add_menu(this)" type="button" menu_id="0" class="btn btn-default">
								{$core->makeIcon('plus-circle', $core->get_Lang('Addlink'))}
							</button>
						</div>
					</div></div>
					<div class="col-md-4">
						<div class="ui-annotated-section__content">
							<div class="next-card">
								<div class="next-card__section">
									<div class="ui-form__section ">
										<div class="form-group">	
											<div class="input__help-text mb-4">Alias là định danh làm đường dẫn hiển thị trên thanh địa chỉ trình duyệt và có thể được dùng để truy cập các thuộc tính của Menu trong Liquid.</div>
											{if $parent_id gt '0'}
											<input type="text" class="form-control" name="iso-alias" readonly value="{if $alias ne ''}{$alias}{else}{$oneItem.alias}{/if}" maxlength="255" />
											{else}
											<input type="text" class="form-control ipn__alias-menu" name="iso-alias"{if $pvalTable gt '0' && $oneItem.parent_id eq '0'} readonly="readonly"{/if} value="{$oneItem.alias}" maxlength="255" />
											{/if}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div></div>
			</section>
			<!-- End section -->
		</div></div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary">
				{if $parent_id gt '0' && $pvalTable gt '0'}
				<a class="btn btn-warning" onClick="delete_globe(this)" clsTable="Menu" pval_id="{$pvalTable}" pkey="menu_id" return_url="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Delete')}</a>
				{/if}
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group">{$saveBtn}</div>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	$(function(){
		if($("ul.link-list-group li.link-list-group-item").length){
			$("ul.link-list-group").sortable({
				connectWith: "ul.link-list-group",
				handle: ".ui-sortable__handle",
				update: function (event, ui) {
					$('li.link-list-group-item').each(function(i){
						var __this = $(this), uid = __this.attr('uid');
						$('#'+uid+'_id', __this).attr('name','links['+i+'][id]');
						$('#'+uid+'_order_no', __this).attr('name','links['+i+'][order_no]').val(i);
						$('#'+uid+'_title', __this).attr('name','links['+i+'][title]');
						$('#'+uid+'_type', __this).attr('name','links['+i+'][type]');
						if($('#'+uid+'_url', __this).length){
							$('#'+uid+'_url', __this).attr('name','links['+i+'][url]');
						}
					});
				}
			}).disableSelection();
		}
	});
</script>