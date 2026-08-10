<header class="ui-title-bar-container">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Chính sách bán hàng</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<div class="btn-group">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
							+ {$core->get_Lang('Addnew')} <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach name=i from=$list_block_type item = _oA}
							<li><a href="javascript:void(0)" onClick="open_policy(this, event)" block_type="{$_oA.property_id}" policy_id="0" class="policy_{$_oA.property_id}" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" >+ {$_oA.title}</a></li>
							{/foreach}
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<form method="post" action="" enctype="multipart/form-data">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<div class="ui-layout__item">
					<div class="ui-card">
						<div class="next-tab__container">
							<ul class="next-tab__list filter-tab-list">
								<li class="filter-tab-item" data-tab-index="1">
									<a href="javascript:void(0);" target="ads" class="filter-tab filter-tab-active next-tab next-tab--is-active">Danh sách</a>
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
								<div class="pull-right">
									<a href="javascript:void(0)" class="btn text-white btn-danger btn-delete-all" clsTable="Policy" style="display: none">{$core->makeIcon('times', $core->get_Lang('Delete Options'))} </a>
								</div>	
							</div>
							<div class="hastable">
								<table cellspacing="0" class="table table-vertical table-striped" width="100%">
									<thead><tr>
										<th class="text-center" width="5%">No.</th>
										<th class="text-left">{$core->get_Lang('Title')}</th>
										<th class="text-left" width="15%">Ngày áp dụng</th>
										<th class="text-left" width="15%">Phạm vi áp dụng</th>
										<th>Loại hình</th>
										<th>Loại quỹ</th>
										<th class="text-left" width="15%">{$core->get_Lang('update')}</th>
										<th class="text-left" width="100px">{$core->get_Lang('Action')}</th>
									</tr></thead>
									{section name=i loop=$allItem}
									{assign var = block_type value = $allItem[i].block_type}
									<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
										<td class="text-center">{$smarty.section.i.iteration}</td>
										<td class="text-left"><a href="javascript:void(0);" class="bold" onClick="open_policy(this, event)" policy_id="{$allItem[i].policy_id}">{$clsClassTable->getTitle($allItem[i].policy_id)}</a></td>
										<td class="text-left">{$core->makeIcon('clock-o',$allItem[i].ms_date|date_format:"%d/%m/%Y")}</td>
										<td class="text-left">---</td>
										<td>{$arr_property_cached.$block_type}</td>
										<td>{if $allItem[i].applicable_fund_type eq '0'}Sơ cấp{else}Thứ cấp{/if}</td>
										<td class="text-left">{$core->makeIcon('clock-o',$allItem[i].upd_date|date_format:"%d/%m/%Y %H:%M")}</td>
										<td class="text-center" style="white-space:nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> 
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left: auto">
													<li><a href="javascript:void(0);" title="Chỉnh sửa" onClick="open_policy(this, event)" block_type="{$allItem[i].block_type}" policy_id="{$allItem[i].policy_id}">
														<i class="icon-edit"></i> 
														<span>{$core->get_Lang('edit')}</span>
													</a></li>
													<li><a href="{$PCMS_URL}/?mod={$mod}&act=delete&policy_id={$allItem[i].policy_id}" title="Xóa" class="confirm_delete">
														<i class="icon-remove"></i> 
														<span>{$core->get_Lang('delete')}</span>
													</a></li>
												</ul>
											</div>
										</td>
									</tr>
									{/section}
								</table>
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
														{section name=i loop=$listPageNumber}
														<option {if $listPageNumber[i] eq $currentPage}selected="selected"{/if} value="{$PCMS_URL}/{$link_page_current}&page={$listPageNumber[i]}">{$listPageNumber[i]}</option>
														{/section}
													</select>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
{$script}