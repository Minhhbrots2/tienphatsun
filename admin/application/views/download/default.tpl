<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Documents')}</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/index.php?mod={$mod}&act=category" class="btn btn-md btn-danger mr-2">
						{$core->get_Lang('Category')}
					</a>
					<a href="{$PCMS_URL}/index.php?mod={$mod}&act=edit" class="btn btn-md text-white btn-success">
						{$core->makeIcon('plus', $core->get_Lang('Addnew'))}
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
			<div class="next-tab__container">
				<ul class="next-tab__list filter-tab-list">
					<li class="filter-tab-item">
						<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('All Documents')}</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post">
					<div class="form-search form-inline">
						<div class="form-group w-200px">
							<select name="cat_id" data-width="100%" class="form-control w-100 iso-selectize required">
								{$clsCategory->makeSelectboxOption(0,'_DOWNLOAD', $cat_id)}
							</select>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
						</div>
						<input type="hidden" name="filter" value="filter" />
						<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
						<div class="pull-right">
							<a href="{$PCMS_URL}/?mod={$mod}" class="btn btn-warning text-white">
								{$core->makeIcon('folder-o',$core->get_Lang('all'))} ({$number_all})
							</a>
							<a href="{$PCMS_URL}/?mod={$mod}&type_list=Trash" class="btn btn-primary text-white">
								{$core->makeIcon('trash',$core->get_Lang('trash'))} ({$number_trash})
							</a>
						</div>	
					</div>
				</form>
				<div class="hastable">
					<table class="table table-vertical table-striped" cellspacing="0" cellpadding="0" width="100%">
						<thead><tr>
							<th class="gridheader"><strong>STT</strong></th>
							<th class="gridheader" style="text-align:left"><strong>{$core->get_Lang('titleofarticle')}</strong></th>
							<th class="gridheader" style="text-align:left"><strong>{$core->get_Lang('category')}</strong></th>
							<th class="gridheader" style="width:6%;"><strong>{$core->get_Lang('status')}</strong></th>
							<th class="gridheader" style="width:12%; text-align:right"><strong>{$core->get_Lang('update')}</strong></th>
							<th class="gridheader" colspan="4" style="width:4%"><strong>{$core->get_Lang('move')}</strong></th>
							<th class="gridheader"><strong>{$core->get_Lang('func')}</strong></td>
						</tr></thead>
						{section name=i loop=$allItem}
						<tr>
							<td class="text-center">{$smarty.section.i.index+1}</td>
							<td class="text-left">
								<strong>{if $allItem[i].is_online eq 0}<span class="text-danger">[PRIVATE]</span>{else}<span class="text-success">[PUBLISH]</span>{/if}
								{$clsClassTable->getTitle($allItem[i].download_id)}</strong>
								{if $allItem[i].is_trash eq '1'}
								<span class="fr" style="color:#CCC">{$core->get_Lang('intrash')}</span>
								{/if}
							</td>
							<td class="text-left">
								<a href="{$PCMS_URL}/index.php?admin&mod={$mod}&downloadcat_id={$allItem[i].downloadcat_id}">
								<i class="fa fa-folder-open"></i> {$clsCategory->getTitle($allItem[i].cat_id)}</a>
							</td>
							<td class="text-center">
								<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Download" pkey="{$pkeyTable}" sourse_id="{$allItem[i].$pkeyTable}" rel="{$allItem[i].is_online}" title="{$core->get_Lang('Click to change status')}">
									{if $allItem[i].is_online eq '1'}
										<i class="fa fa-check-circle green"></i>
									{else}
										<i class="fa fa-minus-circle red"></i>
									{/if}
								</a>
							</td>
							<td class="text-right">{$allItem[i].reg_date|date_format:"%d/%m/%Y %H:%M"}</td>
							<td class="text-center">
								{if !$smarty.section.i.first}
								<a title="{$core->get_Lang('movetop')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movetop&download_id={$core->encryptID($allItem[i].download_id)}{$pUrl}"><i class="icon-circle-arrow-up"></i></a>
								{/if}
							</td>
							<td class="text-center">
								{if !$smarty.section.i.last}
								<a title="{$core->get_Lang('movebottom')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movebottom&download_id={$core->encryptID($allItem[i].download_id)}{$pUrl}"><i class="icon-circle-arrow-down"></i></a>
								{/if}
							</td>
							<td class="text-center">
								{if !$smarty.section.i.first}
								<a title="{$core->get_Lang('moveup')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=moveup&download_id={$core->encryptID($allItem[i].download_id)}{$pUrl}"><i class="icon-arrow-up"></i></a>
								{/if}
							</td>
							<td class="text-center">
								{if !$smarty.section.i.last}
								<a title="{$core->get_Lang('movedown')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movedown&download_id={$core->encryptID($allItem[i].download_id)}{$pUrl}"><i class="icon-arrow-down"></i></a>
								{/if}
							</td>
							<td class="text-center" style="width:40px; white-space:nowrap;">
								<div class="btn-group">
									<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
										<i class="icon-cog"></i> <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" style="right:0px !important">
										{if $allItem[i].is_trash eq '0'}
										<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&download_id={$core->encryptID($allItem[i].download_id)}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
										<li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&download_id={$core->encryptID($allItem[i].download_id)}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
										{else}
										<li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&download_id={$core->encryptID($allItem[i].download_id)}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
										<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&download_id={$core->encryptID($allItem[i].download_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
										{/if}
										{if $clsConfiguration->getValue('SiteHasDuplicate_News')}
										<li><a title="{$core->get_Lang('duplicate')}" class="ajDuplicateNews" download_id="{$allItem[i].download_id}"><i class="icon-share"></i> <span>{$core->get_Lang('duplicate')}</span></a></li>
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
			</div>
		</div></div>
	</div></div>
</div>