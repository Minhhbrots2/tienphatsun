<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Page')}</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/index.php?mod={$mod}&act=edit{$pUrl}" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
					<div class="next-tab__container">
						<ul class="next-tab__list filter-tab-list">
							<li class="filter-tab-item" data-tab-index="1">
								<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">
                                    {$core->get_Lang('AllPages')}
                                </a>
							</li>
						</ul>
					</div>
					<div class="ui-card__section has-bulk-actions pages">
						<form method="post">
							<div class="form-search form-inline">
								<div class="form-group">
									<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
								<div class="form-group pull-right">
									<a href="{$PCMS_URL}/?mod={$mod}{$pUrl}" class="btn text-white btn-warning">
										<i class="icon-folder-open icon-white"></i> 
										<span>{$core->get_Lang('all')} ({$number_all})</span>
									</a>
									<a href="{$PCMS_URL}/?mod={$mod}&type_list=Trash{$pUrl}" class="btn text-white btn-danger">
										<i class="icon-warning-sign icon-white"></i> 
										<span>{$core->get_Lang('trash')}({$number_trash})</span>
									</a>
									<a href="javascript:void(0)" clsTable="News" class="btn btn-danger text-white btn-delete-all" style="display:none"> 
                           				<i class="icon-remove icon-white"></i> 
                           				<span>{$core->get_Lang('Delete')}</span> 
                           			</a>
								</div>
							</div>
							<div class="hastable">
								<table class="table table-vertical table-striped" cellspacing="0" cellpadding="0" width="100%">
									<thead><tr>
										<th width="5%" class="text-center">
											<div class="checkbox">
												<input type="checkbox" id="check_all" class="check_all styled" value="1" />
												<label></label>
											</div>
										</th>
										<th class="text-left" width="80px">{$core->get_Lang('Image')}</th>
										<th class="text-left">{$core->get_Lang('titleofarticle')}</th>
										<th class="text-center" width="10%">{$core->get_Lang('status')}</th>
										<th class="text-right" width="12%">{$core->get_Lang('update')}</th>
										<th class="text-center" colspan="4" width="4%">{$core->get_Lang('move')}</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{cycle values="row1,row2"}">
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].page_id}" />
												<label></label>
											</div>
										</td>
										<td class="text-center">
											<a class="aspect-ratio aspect-ratio--square aspect-ratio--square--50 aspect-ratio--interactive" href="{$PCMS_URL}/?mod={$mod}&act=edit&page_id={$core->encryptID($allItem[i].page_id)}">
												<img class="aspect-ratio__content" src="{$allItem[i].image}" width="80px" />
											</a>
										</td>
										<td><a href="{$PCMS_URL}/?mod={$mod}&act=edit&page_id={$core->encryptID($allItem[i].page_id)}">{$clsClassTable->getTitle($allItem[i].page_id)}</a>
											{if $allItem[i].is_trash eq '1'}<span class="fr text-red">{$core->get_Lang('intrash')}</span>{/if}
                                        </td>
										<td class="text-center">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="News" pkey="{$pkeyTable}" sourse_id="{$allItem[i].$pkeyTable}" rel="{$clsClassTable->getOneField('is_online',$allItem[i].$pkeyTable)}" title="{$core->get_Lang('Click to change status')}">
												{if $clsClassTable->getOneField('is_online',$allItem[i].$pkeyTable) eq '1'}
												<i class="fa fa-check-circle green"></i>
												{else}
												<i class="fa fa-minus-circle red"></i>
												{/if}
											</a>
										</td>
										<td style="text-align:right">{$allItem[i].reg_date|date_format:"%d/%m/%Y %H:%M"}</td>
										<td class="text-center">
											{if !$smarty.section.i.first}
											<a title="{$core->get_Lang('movetop')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movetop&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-circle-arrow-up"></i></a>
											{/if}
										</td>
										<td class="text-center">
											{if !$smarty.section.i.last}
											<a title="{$core->get_Lang('movebottom')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movebottom&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-circle-arrow-down"></i></a>
											{/if}
										</td>
										<td class="text-center">
											{if !$smarty.section.i.first}
											<a title="{$core->get_Lang('moveup')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=moveup&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-arrow-up"></i></a>
											{/if}
										</td>
										<td class="text-center">
											{if !$smarty.section.i.last}
											<a title="{$core->get_Lang('movedown')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movedown&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-arrow-down"></i></a>
											{/if}
										</td>
										<td class="text-center" style="white-space: nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													{if $allItem[i].is_trash eq '0'}
													<li><a href="{$DOMAIN_NAME}{$clsClassTable->getLink($allItem[i].page_id)}" target="_blank" title="{$core->get_Lang('view')}"><i class="icon-eye-open"></i> <span>{$core->get_Lang('view')}</span></a></li>
													<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&page_id={$core->encryptID($allItem[i].page_id)}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
													<li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
													{else}
													<li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
													<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
													{/if}
												</ul>
											</div>
										</td>
									</tr>
									{/section}
								</table>
								<div class="t-grid-pager-boder">
									<div class="t-pager t-reset fix-margin-pager">
										{$html_pager}
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>