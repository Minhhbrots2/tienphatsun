<header class="ui-title-bar-container">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Gói tài khoản</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0)" onClick="$Core.package.open(this, event)" property_id="0" class="ui-button ui-button--primary ui-title-bar__action"><i class="fa fa-plus"></i> Thêm gói</a>
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
									<a href="javascript:void(0);" class="filter-tab filter-tab-active next-tab next-tab--is-active">Danh sách gói</a>
								</li>
							</ul>
						</div>
						<div class="clearfix"></div>
						<div id="package" class="ui-card__section has-bulk-actions">
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
										<th class="text-left">Tên gói</th>
										<th class="text-left" width="10%">Mã</th>
										<th class="text-right" width="13%">3 tháng</th>
										<th class="text-right" width="11%">6 tháng</th>
										<th class="text-right" width="11%">12 tháng</th>
										<th class="text-center" width="8%">Dùng thử</th>
										<th class="text-center" width="90px">{$core->get_Lang('Action')}</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
										<td class="text-center">{$smarty.section.i.iteration}</td>
										<td class="text-left"><a href="javascript:void(0);" class="bold" onClick="$Core.package.open(this, event)" property_id="{$allItem[i].property_id}">{$allItem[i].title}</a></td>
										<td class="text-left">{$allItem[i].property_code}</td>
										<td class="text-right">{$allItem[i].mi.price_3month_f}</td>
										<td class="text-right">{$allItem[i].mi.price_6month_f}</td>
										<td class="text-right">{$allItem[i].mi.price_year_f}</td>
										<td class="text-center">{if $allItem[i].mi.day_trial}{$allItem[i].mi.day_trial} ngày{else}-{/if}</td>
										<td class="text-center" style="white-space:nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"><i class="icon-cog"></i> <span class="caret"></span></button>
												<ul class="dropdown-menu" style="right:0px !important; left: auto">
													<li><a href="javascript:void(0);" title="Chỉnh sửa" onClick="$Core.package.open(this, event)" property_id="{$allItem[i].property_id}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
													<li><a href="javascript:void(0);" title="Phân quyền" onClick="$Core.permiss.open(this, event)" profile_type="MF" for_id="{$allItem[i].property_id}"><i class="fa fa-key"></i> <span>Phân quyền</span></a></li>
													<li><a href="{$PCMS_URL}/?mod={$mod}&act=delete&property_id={$allItem[i].property_id}" title="Xóa" class="confirm_delete"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
												</ul>
											</div>
										</td>
									</tr>
									{/section}
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
