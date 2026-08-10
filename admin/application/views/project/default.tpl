<header class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Dự án</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0)" onClick="$Core.project.sync_price_sheet(this, event)" project_id="0" class="ui-button mr-2 ui-button--transparent ui-title-bar__action" title="{$core->get_Lang('Sync Block')}">{$core->get_Lang('Sync Block')}</a>
					<a href="javascript:void(0)" onClick="sync_search(this, event)" project_id="0" class="ui-button mr-2 ui-button--transparent ui-title-bar__action" title="{$core->get_Lang('Addnew')}">{$core->get_Lang('Sync search')}</a>
					<a href="javascript:void(0)" onClick="open_project(this, event)" project_id="0" class="ui-button ui-button--primary ui-title-bar__action" title="{$core->get_Lang('Addnew')}">{$core->get_Lang('Addnew')}</a>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<form method="post" action="" enctype="multipart/form-data">
	<div class="ui-layout ui-layout--full-width">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<div class="ui-layout__item">
					<div class="ui-card">
						<div class="next-tab__container">
							<ul class="next-tab__list filter-tab-list">
								<li class="filter-tab-item">
									<a href="{$PCMS_URL}/?mod={$mod}" class="filter-tab next-tab{if $type_list ne 'Trash'} filter-tab-active next-tab--is-active{/if}">Danh sách dự án</a>
								</li>
								<li class="filter-tab-item">
									<a href="{$PCMS_URL}/?mod={$mod}&type_list=Trash" class="filter-tab next-tab{if $type_list eq 'Trash'} filter-tab-active next-tab--is-active{/if}">Thùng rác</a>
								</li>
							</ul>
						</div>
						<div class="clearfix"></div>
						<div id="project" class="ui-card__section has-bulk-actions">
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
								<table cellspacing="0" cellpadding="0" class="table table-striped table-vertical table_project" style="width:100%">
									<thead><tr>
										<th class="text-center" width="5%">No.</th>
										<th class="text-left">Tên dự án</th>
										<th class="text-left" width="160px">{$core->get_Lang('update')}</th>
										<th class="text-center" width="220px">{$core->get_Lang('Action')}</th>
									</tr></thead>
									<tbody>
										{section name=i loop=$allItem}
										{assign var = project_id value = $allItem[i].project_id}
										<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
											<td class="text-center">{$smarty.section.i.index+1}</td>
											<td class="text-left">
												<a href="{$PCMS_URL}/?mod={$mod}&act=overview&project_id={$project_id}"><strong class="font16">{$allItem[i].title}</strong></a>
											</td>
											<td class="text-left">{$core->makeIcon('clock-o',$allItem[i].reg_date|date_format:"%d/%m/%Y %H:%M")}</td>
											<td class="text-center">
												<div class="fh-actions">
												<a href="{$PCMS_URL}/?mod={$mod}&act=overview&project_id={$project_id}" class="btn btn-sm btn-primary" style="color:#fff !important"><i class="fa fa-th-large"></i> Tổng quan</a>
												<div class="btn-group">
													<button class="btn btn-sm iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"><i class="icon-cog"></i> <span class="caret"></span></button>
													<ul class="dropdown-menu" style="right:0px !important; left: auto">
														{if $allItem[i].is_trash eq '0'}
														<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&project_id={$project_id}"><i class="icon-edit"></i> <span>{$core->get_Lang('Edit')}</span></a></li>
														<li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&project_id={$project_id}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('Trash')}</span></a></li>
														<li><a title="Xóa vĩnh viễn dự án và dữ liệu liên quan" class="js_delete_project" data-title="{$allItem[i].title|escape}" href="{$PCMS_URL}/?mod={$mod}&act=delete&project_id={$project_id}{$pUrl}"><i class="icon-remove"></i> <span>Xóa vĩnh viễn</span></a></li>
														{else}
														<li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&project_id={$project_id}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('Restore')}</span></a></li>
														<li><a title="{$core->get_Lang('delete')}" class="js_delete_project" data-title="{$allItem[i].title|escape}" href="{$PCMS_URL}/?mod={$mod}&act=delete&project_id={$project_id}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('Delete')}</span></a></li>
														{/if}
													</ul>
												</div>
											</div>
											</td>
										</tr>
										{/section}
									</tbody>
								</table>
								{if $totalPage > 1}
								<div class="statistical">
									<table width="100%" border="0" cellpadding="2" cellspacing="0">
										<tr>
											<td width="50%" align="left">
												{$core->get_Lang('statistical')} <strong>{$totalRecord}</strong> {$core->get_Lang('records')}/<strong>{$totalPage}</strong> {$core->get_Lang('page')}. {$core->get_Lang('youareonpagenumber')} <strong>{$currentPage}</strong>
											</td>
											<td width="50%" class="text-right">
												<div class="d-inline-flex align-items-center">
													<span class="mr-2">{$core->get_Lang('gotopage')}:</span>
													<select name="page" class="form-control w-40" onchange="window.location = this.options[this.selectedIndex].value">
														{section name=p loop=$listPageNumber}
														<option {if $listPageNumber[p] eq $currentPage}selected="selected"{/if} value="{$PCMS_URL}/{$link_page_current}&page={$listPageNumber[p]}">{$listPageNumber[p]}</option>
														{/section}
													</select>
												</div>
											</td>
										</tr>
									</table>
								</div>
								{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
