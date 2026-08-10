<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Khóa học đào tạo</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0)" onClick="$Core.training.addTraining(this,event)" data-type="_OPEN" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
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
										<div class="input-group w-200px">
											<select class="gl-reload form-control w-100 custom-select required" name="cat_id">
												{$clsISO->getSelectByPropertyTypeTitle('_TRAINING_CAT',$cat_id,'Danh mục')}
											</select>
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
									<a href="javascript:void(0)" class="btn btn-danger text-white btn-delete-all" style="display: none" clsTable="Training"> 
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
										<th class="text-left" style="width:6%">{$core->get_Lang('Status')}</th>
										<th class="text-left" style="width:6%">Show MOC</th>
										<th class="text-right" style="width:20%;" align="center">{$core->get_Lang('Category')}</th>
										<th class="text-right" style="width:12%;" align="center">{$core->get_Lang('update')}</th>
										<th class="text-left" width="100px">{$core->get_Lang('Action')}</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].training_id}">
												<label></label>
											</div>
										</td>
										<td class="text-left">
											<a href="{$PCMS_URL}/?mod={$mod}&act=edit&training_id={$core->encryptID($allItem[i].training_id)}">{$clsClassTable->getTitle($allItem[i].training_id)}</a>
											{if $allItem[i].is_trash eq '1'}
											<span class="fr text-right">{$core->get_Lang('intrash')}</span>
											{/if}
										</td>
										<td class="text-center">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Training" pkey="training_id" sourse_id="{$allItem[i].training_id}" rel="{$clsClassTable->getOneField('is_online',$allItem[i].training_id)}" title="{$core->get_Lang('Click to change status')}">
												{if $clsClassTable->getOneField('is_online',$allItem[i].training_id) eq '1'}
												<i class="fa fa-check-circle green"></i>
												{else}
												<i class="fa fa-minus-circle red"></i>
												{/if}
											</a>
										</td>
										<td class="text-center">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Training" pkey="training_id" toField="show_MOC" sourse_id="{$allItem[i].training_id}" rel="{$clsClassTable->getOneField('show_MOC',$allItem[i].training_id)}" title="{$core->get_Lang('Click to change status')}">
												{if $clsClassTable->getOneField('show_MOC',$allItem[i].training_id) eq '1'}
												<i class="fa fa-check-circle green"></i>
												{else}
												<i class="fa fa-minus-circle red"></i>
												{/if}
											</a>
										</td>
										<td class="text-center">{if !empty($allItem[i].cat_id)}{$clsProperty->getTitle($allItem[i].cat_id)}{else}--{/if}</td>
										<td class="text-center">{$allItem[i].reg_date|date_format:"%d/%m/%Y %H:%M"}</td>
										<td class="text-center" style="white-space:nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> 
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left: auto">
													{if $allItem[i].is_trash eq '0'}
													<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&training_id={$core->encryptID($allItem[i].training_id)}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
													<li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&training_id={$core->encryptID($allItem[i].training_id)}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
													{else}
													<li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&training_id={$core->encryptID($allItem[i].training_id)}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
													<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&training_id={$core->encryptID($allItem[i].training_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
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