<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Lộ trình học</h1>
				<p class="type--subdued">Tạo và quản lý các lộ trình học (gồm danh sách khoá học có thứ tự) cho thành viên MyFuture</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0)" onClick="$Core.learning_path.addPath(this,event)" data-type="_OPEN" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
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
							<div class="form-search form-inline">
								<div class="d-flex">
									<div class="form-group w-400px d-flex gap-1">
										<div class="input-group w-200px double-input">
											<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" style="width: 100% !important"/>
										</div>
									</div>
									<input type="hidden" name="filter" value="filter" />
									<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
								</div>
								<div class="form-group pull-right">
									<a href="{$PCMS_URL}/?mod={$mod}" class="btn text-white btn-warning">
										<i class="icon-folder-open icon-white"></i>
										<span>{$core->get_Lang('all')} ({$number_all})</span>
									</a>
									<a href="{$PCMS_URL}/?mod={$mod}&type_list=Trash" class="btn text-white btn-danger">
										<i class="icon-warning-sign icon-white"></i>
										<span>{$core->get_Lang('trash')} ({$number_trash})</span>
									</a>
									<a href="javascript:void(0)" class="btn btn-danger text-white btn-delete-all" style="display: none" clsTable="LearningPath">
										<i class="icon-remove icon-white"></i>
										<span>{$core->get_Lang('Delete')}</span>
									</a>
								</div>
							</div>
							<div class="hastable table-wrapper">
								<input type="hidden" id="list_selected_chkitem" value="" />
								<table cellspacing="0" class="table table-vertical table-striped" width="100%">
									<thead><tr>
										<th class="text-center" width="3%">
											<div class="checkbox">
												<input type="checkbox" id="check_all" class="check_all styled" value="1">
												<label></label>
											</div>
										</th>
										<th class="text-left">{$core->get_Lang('Title')}</th>
										<th class="text-center" style="width:6%">{$core->get_Lang('Status')}</th>
										<th class="text-center" style="width:8%">Số khoá</th>
										<th class="text-center" style="width:10%">Thời lượng</th>
										<th class="text-center" style="width:12%;">{$core->get_Lang('update')}</th>
										<th class="text-left" width="100px">{$core->get_Lang('Action')}</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].path_id}">
												<label></label>
											</div>
										</td>
										<td class="text-left">
											<a href="{$PCMS_URL}/?mod={$mod}&act=edit&path_id={$core->encryptID($allItem[i].path_id)}">{$clsClassTable->getTitle($allItem[i].path_id, $allItem[i])}</a>
											{if $allItem[i].is_trash eq '1'}<span class="fr text-right">{$core->get_Lang('intrash')}</span>{/if}
										</td>
										<td class="text-center">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="LearningPath" pkey="path_id" sourse_id="{$allItem[i].path_id}" rel="{$allItem[i].is_online}" title="{$core->get_Lang('Click to change status')}">
												{if $allItem[i].is_online eq '1'}<i class="fa fa-check-circle green"></i>{else}<i class="fa fa-minus-circle red"></i>{/if}
											</a>
										</td>
										<td class="text-center">{$allItem[i].total_course}</td>
										<td class="text-center">{if $allItem[i].est_minutes > 0}{$allItem[i].est_minutes} phút{else}--{/if}</td>
										<td class="text-center">{$allItem[i].reg_date|date_format:"%d/%m/%Y %H:%M"}</td>
										<td class="text-center" style="white-space:nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left: auto">
													{if $allItem[i].is_trash eq '0'}
													<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&path_id={$core->encryptID($allItem[i].path_id)}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
													<li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&path_id={$core->encryptID($allItem[i].path_id)}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
													{else}
													<li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&path_id={$core->encryptID($allItem[i].path_id)}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
													<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&path_id={$core->encryptID($allItem[i].path_id)}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
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
