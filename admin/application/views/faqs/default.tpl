<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('faqs')}</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/index.php?mod=setting&act=property&group=website" class="btn btn-primary text-white mr-2 ui-title-bar__action">{$core->get_Lang('Category')}</a>
					<a href="{$PCMS_URL}/index.php?mod={$mod}&act=edit{$pUrl}" class="btn btn-success ui-title-bar__action">{$core->makeIcon('plus', $core->get_Lang('Addnew'))}</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
					<div class="next-tab__container">
						<ul class="next-tab__list filter-tab-list">
							<li class="filter-tab-item" data-tab-index="1">
								<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('AllPages')}</a>
							</li>
						</ul>
					</div>
					<div class="ui-card__section has-bulk-actions pages">
						<form method="post">
							<div class="form-search d-flex align-items-center justify-content-between">
								<div class="d-flex form-inline w-60">
									<select name="domain" class="iso-selectize mr-2 required" style="width:30%">
										<option value="0">--{$core->get_Lang('Domain')}</option>
										{if !empty($list_domains)}
											{foreach from=$list_domains item = _oDomain}
											<option{if $domain eq {$_oDomain.domain}} selected{/if} value="{$_oDomain.domain}">{$_oDomain.title}</option>
											{/foreach}
										{/if}
									</select>
									<select name="cat_id" class="iso-selectize mr-2 required" style="width:30%">
										<option value="0">--{$core->get_Lang('All')}</option>
										{$clsProperty->getListOption('_CATEGORYFAQS', $cat_id)}
									</select>
									<input type="text" class="form-control mr-2" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
									<input type="hidden" name="filter" value="filter" />
									<button type="submit" class="btn btn-success">{$core->makeIcon('search')}</button>
								</div>
								<div class="form-group pull-right d-flex">
									<a href="{$PCMS_URL}/?mod={$mod}" class="btn text-white btn-warning mr-2">
										<i class="icon-folder-open icon-white"></i> 
										<span>{$core->get_Lang('all')} ({$number_all})</span>
									</a>
									<a href="{$PCMS_URL}/?mod={$mod}&type_list=Trash" class="btn text-white btn-danger mr-2">
										<i class="icon-warning-sign icon-white"></i> 
										<span>{$core->get_Lang('trash')} ({$number_trash})</span>
									</a>
									<a href="javascript:void(0)" class="btn btn-danger text-white btn-delete-all" 
									style="display: none" clsTable="FAQ"> 
                           				<i class="icon-remove icon-white"></i> 
                           				<span>{$core->get_Lang('Delete')}</span> 
                           			</a>
								</div>
							</div>
							<div class="hastable table-wrapper">
								<input type="hidden" id="list_selected_chkitem" value="" />
								<table cellspacing="0" class="table table-ilooca table-striped" width="100%">
									<thead><tr>
										<th class="text-center" width="3%">
											<div class="checkbox">
												<input type="checkbox" id="check_all" class="check_all styled" value="1">
												<label></label>
											</div>
										</th>
										<th class="text-left">{$core->get_Lang('Title')}</th>
										<th class="text-left" width="6%">{$core->get_Lang('Status')}</th>
										<th class="text-right" width="15%">{$core->get_Lang('update')}</th>
										<th class="text-center" colspan="4" width="4%">{$core->get_Lang('move')}</th>
										<th class="text-left" width="100px">{$core->get_Lang('Action')}</th>
									</tr></thead>
									{section name=i loop=$allItem}
									{assign var = faq_id value = $allItem[i].faq_id}
									{assign var = list_tags value = $allItem[i].list_tags}
									<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].faq_id}">
												<label></label>
											</div>
										</td>
										<td class="text-left">
											{$clsClassTable->getTitle($faq_id)}
											{if $allItem[i].is_trash eq '1'}
											<span class="pull-right text-muted">{$core->get_Lang('Trash')}</span>
											{/if}
											{if $allItem[i].cat_id gt '0'}
											<div class="text-muted">{$core->get_Lang('Category')}: {$clsProperty->getTitle($allItem[i].cat_id)}</div>
											{/if}
											{if !empty($list_tags)}
												<div class="mt-2">
													{foreach from=$list_tags item = tag}
														<span class="label label-primary p-1 fs-tiny">{$tag}</span>
													{/foreach}												
												</div>
											{/if}
										</td>
										<td class="text-center">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="FAQ" pkey="faq_id" sourse_id="{$allItem[i].faq_id}" rel="{$clsClassTable->getOneField('is_online',$allItem[i].faq_id)}" title="{$core->get_Lang('Click to change status')}">
												{if $clsClassTable->getOneField('is_online',$allItem[i].faq_id) eq '1'}
												<i class="fa fa-check-circle green"></i>
												{else}
												<i class="fa fa-minus-circle red"></i>
												{/if}
											</a>
										</td>
										<td class="text-center">{$allItem[i].reg_date|date_format:"%d/%m/%Y %H:%M"}</td>
										<td class="text-center">
											{if !$smarty.section.i.first}
											<a title="{$core->get_Lang('movetop')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movetop&faq_id={$faq_id}{$pUrl}"><i class="icon-circle-arrow-up"></i></a>
											{/if}
										</td>
										<td class="text-center">
											{if !$smarty.section.i.last}
											<a title="{$core->get_Lang('movebottom')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movebottom&faq_id={$faq_id}{$pUrl}"><i class="icon-circle-arrow-down"></i></a>
											{/if}
										</td>
										<td class="text-center">
											{if !$smarty.section.i.first}
											<a title="{$core->get_Lang('moveup')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=moveup&faq_id={$faq_id}{$pUrl}"><i class="icon-arrow-up"></i></a>
											{/if}
										</td>
										<td class="text-center">
											{if !$smarty.section.i.last}
											<a title="{$core->get_Lang('movedown')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movedown&faq_id={$faq_id}{$pUrl}"><i class="icon-arrow-down"></i></a>
											{/if}
										</td>
										<td class="text-center" style="white-space:nowrap;">
											<div class="btn-group dropdown">
												<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
													{$core->get_Lang('Action')}
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													{if $allItem[i].is_trash eq '0'}
													<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&faq_id={$faq_id}{$pUrl}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
													<li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&faq_id={$faq_id}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
													{else}
													<li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&faq_id={$faq_id}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
													<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&faq_id={$faq_id}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
													{/if}
												</ul>
											</div>
										</td>
									</tr>
									{/section}
								</table>
								<div class="statistical mt5">
									<table width="100%" border="0" cellpadding="3" cellspacing="0">
										<tr>
											<td width="50%" align="left">
												{$core->get_Lang('statistical')} <strong>{$totalRecord}</strong> {$core->get_Lang('records')}/<strong>{$totalPage}</strong> {$core->get_Lang('page')}. {$core->get_Lang('youareonpagenumber')} <strong>{$currentPage}</strong>
											</td>
											<td width="50%" align="right">
												{$core->get_Lang('gotopage')}:
												<select name="page" onchange="window.location = this.options[this.selectedIndex].value">
													{section name=i loop=$listPageNumber}
													<option {if $listPageNumber[i] eq $currentPage}selected="selected"{/if} value="{$PCMS_URL}/{$link_page_current}&page={$listPageNumber[i]}">{$listPageNumber[i]}</option>
													{/section}
												</select>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>