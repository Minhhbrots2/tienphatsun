<header class="ui-title-bar-container">
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
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$core->get_Lang('Meta Tags')}</h1>
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
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
					<div class="next-tab__container">
						<ul class="next-tab__list filter-tab-list">
							<li class="filter-tab-item" data-tab-index="1">
								<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('All Meta Tags')}</a>
							</li>
						</ul>
					</div>
					<div class="ui-card__section has-bulk-actions pages">
						<form method="post">
							<div class="form-search form-inline">
								<div class="form-group">
									<div class="input-group">
										<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
									</div>
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
							</div>
							<div class="hastable">
								<table class="table table-vertical table-striped" cellspacing="0" cellpadding="0" width="100%">
									<thead><tr>
										<th class="text-center" width="3%">No.</th>
										<th class="text-left">{$core->get_Lang('Link')}</th>
										<th class="text-right">{$core->get_Lang('Length')}</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{if $allItem}
									{section name=i loop=$allItem}
									<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
										<td class="index">{$smarty.section.i.index+1}</td>
										<td class="text-left">							
											<a title="Edit" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&meta_id={$core->encryptID($allItem[i].meta_id)}{$pUrl}">
											   <strong>{$clsClassTable->getOneField('config_link',$allItem[i].meta_id)}</strong>
											</a>
											{if $allItem[i].is_trash eq '1'}<span class="fr" style="color:#CCC">[In Trash]</span>{/if}
										</td>
										<td class="text-right">
											Title: {$clsClassTable->getStatus('title',$allItem[i].meta_id)}&nbsp;|&nbsp;Description: {$clsClassTable->getStatus('description',$allItem[i].meta_id)}&nbsp;|&nbsp;Keyword: {$clsClassTable->getStatus('keyword',$allItem[i].meta_id)}
										</td>
										<td class="text-center" style="white-space:nowrap;">
											<div class="btn-group">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													{if $allItem[i].is_trash eq '0'}
													<li><a href="{$DOMAIN_NAME}{$clsClassTable->getConfigLink($allItem[i].meta_id)}{$pUrl}" target="_blank" title="{$core->get_Lang('View')}"><i class="icon-eye-open"></i> <span>{$core->get_Lang('view')}</span></a></li>
													<li><a title="{$core->get_Lang('Edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&meta_id={$core->encryptID($allItem[i].meta_id)}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
													<li><a title="{$core->get_Lang('Trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&meta_id={$core->encryptID($allItem[i].meta_id)}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
													{else}
													<li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&meta_id={$core->encryptID($allItem[i].meta_id)}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
													<li><a title="{$core->get_Lang('Delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&meta_id={$core->encryptID($allItem[i].meta_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
													{/if}
												</ul>
											</div>
										</td>
									</tr>	
									{/section}
									{else}
										<tr>
											<td colspan="10" class="text-center">
												{$core->get_Lang('No Data')} !
											</td>
										</tr>
									{/if}
								</table>
								<div class="clearfix"></div>
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