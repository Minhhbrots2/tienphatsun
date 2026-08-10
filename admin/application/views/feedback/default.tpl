<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Feedback')}</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
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
								<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('All')}</a>
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
								<div class="form-group pull-right">
									<a href="{$PCMS_URL}/index.php?mod={$mod}&type_list=Approved{$pUrl}" class="btn text-white btn-warning">
										<i class="icon-folder-open icon-white"></i> 
										<span>{$core->get_Lang('Offered')} ({$number_process})</span>
									</a>
									<a href="{$PCMS_URL}/index.php?mod={$mod}&type_list=Pendding{$pUrl}" class="btn text-white btn-danger">
										<i class="icon-warning-sign icon-white"></i> 
										<span>{$core->get_Lang('Reminding')} ({$number_unprocess})</span>
									</a>
									<a href="javascript:void(0)" clsTable="News" class="btn btn-danger text-white btn-delete-all" style="display:none"> 
                           				<i class="icon-remove icon-white"></i> 
                           				<span>{$core->get_Lang('Delete')}</span> 
                           			</a>
								</div>
							</div>
						</form>
						<div class="hastable table_wrapper">
							<table width="100%" cellspacing="0" class="table table-vertical table-striped">
								<thead><tr>
									<th class="text-center" width="5%">No.</th>
									<th class="text-left"><strong>{$core->get_Lang('Full Name')}</strong></th>
									<th class="text-left"><strong>{$core->get_Lang('phone')}</strong></th>
									<th class="text-left"><strong>{$core->get_Lang('email')}</strong></th>
									<th class="text-right"><strong>{$core->get_Lang('Datetime')}</strong></th>
									<th class="text-left" style="width:10%"><strong>{$core->get_Lang('status')}</strong></th>
									<th class="text-center" style="width:6%"><strong>{$core->get_Lang('action')}</strong></th>
								</tr></thead>
								{if $listItem[0].feedback_id ne ''}
									{section name=i loop=$listItem}
									{assign var=FEEDBACKVALUE value = $clsISO->getArrayFromString($listItem[i].feedback_store)}
									<tr>
										<td class="text-center">{$smarty.section.i.iteration}</td>
										<td class="text-left">{$listItem[i].full_name}</td>
										<td class="text-left">{$listItem[i].phone}</td> 
										<td class="text-left">{$listItem[i].email}</td>
										<td class="text-right">{$clsISO->convertTimeToText($listItem[i].reg_date, true)}</td>
										<td class="text-center">
											{if $listItem[i].is_done eq '0'}
											<span class="label label-info">{$core->get_Lang('Reminding')}</span>
											{elseif $listItem[i].is_done eq '2'}
											<span class="label">{$core->get_Lang('Reviewed')}</span>
											{else}
											<span class="label label-success">{$core->get_Lang('Offered')}</span>
											{/if}
										</td>
										<td  class="text-center" style="white-space: nowrap;">
											<div class="btn-group">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													<li><a title="{$core->get_Lang('view')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&feedback_id={$core->encryptID($listItem[i].feedback_id)}"><i class="icon-edit"></i> {$core->get_Lang('view')}</a></li>
													<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&feedback_id={$core->encryptID($listItem[i].feedback_id)}{$pUrl}"><i class="icon-remove"></i> {$core->get_Lang('delete')}</a></li>
												</ul>
											</div>
										</td>
									</tr>	
									{/section}
								{else}
									<tr>
										<td colspan="20" style="text-align:center">{$core->get_Lang('nodata')}</td>
									</tr>
								{/if}
							</table>
							<div class="t-grid-pager-boder">
								<div class="t-pager t-reset fix-margin-pager">
									{$html_pager}
								</div>
							</div>
						</div>
					</div>
				<div>
			</div>
		</div>
	</div>
</div>
