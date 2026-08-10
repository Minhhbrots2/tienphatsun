<header class="ui-title-bar-container">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Tiến độ thanh toán / PTG</h1>
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
							<li><a href="javascript:void(0)" onClick="$Core.price_sheets.open(this, event)" stock_type="{$_oA.property_id}" price_sheet_id="0">+ {$_oA.title}</a></li>
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
							</div>
							<div class="hastable">
								<table cellspacing="0" class="table table-vertical table-striped" width="100%">
									<thead><tr>
										<th class="text-center" width="5%">No.</th>
										<th class="text-left">{$core->get_Lang('Title')}</th>
										<th class="text-left" width="15%">Ngày áp dụng</th>
										<th class="text-left" width="25%">Phạm vi áp dụng</th>
										<th width="12%">Loại hình</th>
										<th class="text-left" width="15%">{$core->get_Lang('update')}</th>
										<th class="text-left" width="100px">{$core->get_Lang('Action')}</th>
									</tr></thead>
									{section name=i loop=$allItem}
									{assign var = stock_type value = $allItem[i].stock_type}
									<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
										<td class="text-center">{$smarty.section.i.iteration}</td>
										<td class="text-left"><a href="javascript:void(0);" class="bold" onClick="$Core.price_sheets.open(this, event)" stock_type="{$allItem[i].stock_type}" price_sheet_id="{$allItem[i].id}">{$clsClassTable->getTitle($allItem[i].id, $allItem[i])}</a></td>
										<td class="text-left">{$core->makeIcon('clock-o',$allItem[i].apply_date|date_format:"%d/%m/%Y")}</td>
										<td class="text-left">{$clsClassTable->getScopeText($allItem[i].id, $allItem[i])}</td>
										<td>{$arr_property_cached.$stock_type}</td>
										<td class="text-left">{$core->makeIcon('clock-o',$allItem[i].upd_date|date_format:"%d/%m/%Y %H:%M")}</td>
										<td class="text-center" style="white-space:nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i>
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left: auto">
													<li><a href="javascript:void(0);" title="Chỉnh sửa" onClick="$Core.price_sheets.open(this, event)" stock_type="{$allItem[i].stock_type}" price_sheet_id="{$allItem[i].id}">
														<i class="icon-edit"></i>
														<span>{$core->get_Lang('edit')}</span>
													</a></li>
													<li><a href="{$PCMS_URL}/?mod={$mod}&act=clone&id={$allItem[i].id}" title="Nhân bản" onclick="return confirm('Nhân bản tiến độ thanh toán này (gồm phạm vi + phương án + đợt)?')">
												<i class="fa fa-copy"></i>
												<span>Nhân bản</span>
											</a></li>
											<li><a href="{$PCMS_URL}/?mod={$mod}&act=delete&id={$allItem[i].id}" title="Xóa" class="confirm_delete">
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
