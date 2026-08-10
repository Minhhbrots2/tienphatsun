<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Quản lý khách hàng')}</h1>
				<p class="type--subdued">{$core->get_Lang('Quản lý toàn bộ khách hàng có trong Hệ Thống')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/index.php?mod={$mod}&act=new" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
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
								<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('Tất cả khách hàng')}</a>
							</li>
						</ul>
					</div>
					<div class="ui-card__section has-bulk-actions pages">
						<form method="post">
							<div class="form-search form-inline">
								<div class="form-group">
									<div class="input-group w-400px double-input">
										<select class="form-control" name="group_id">
											{$clsISO->getSelectByPropertyTypeTitle('_PROFILE_GROUP',$group_id,"Nhóm khách hàng")}
										</select>
										<input  type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
									</div>
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
							</div>
							<div class="hastable">
								<table class="table table-vertical table-striped" cellspacing="0" cellpadding="0" width="100%">
									<thead><tr>
										<th class="text-center" width="3%">
											<div class="checkbox">
												<input type="checkbox" id="check_all" class="check_all styled" value="1" />
												<label></label>
											</div>
										</th>
										<th class="text-left">{$core->get_Lang('CustomerName')}</th>
										<th class="text-left">{$core->get_Lang('Email')}</th>
										<th class="text-left" width="15%">{$core->get_Lang('Phone')}</th>
										<th class="text-center" width="10%">{$core->get_Lang('Đơn hàng')}</th>
										<th class="text-right" width="15%">{$core->get_Lang('Doanh thu')}</th>
										<th class="text-center" width="60px">{$core->get_Lang('Status')}</th>
										<th class="text-center" width="60px">Action</th>
									</tr></thead>
									{section name=i loop=$allItem}
									{assign var = profile_id value = $allItem[i].profile_id}
									<tr>
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].profile_id}" />
												<label></label>
											</div>
										</td>
										<td><a title="Edit" href="{$PCMS_URL}/index.php?mod={$mod}&act=view&profile_id={$allItem[i].profile_id}">{$clsClassTable->getName($profile_id, $allItem[i])}</a></td>
										<td>{$clsClassTable->getEmail($profile_id, $allItem[i])}</td>
										<td>{$clsClassTable->getPhone($profile_id, $allItem[i])}</td>
										<td class="text-right" style="white-space:nowrap">{$clsClassTable->getTotalOrderInOne($profile_id)}</td>
										<td class="text-right" style="white-space:nowrap">{$clsClassTable->getTotalMoneyInOne($profile_id)}</td>
										<td class="text-center">
											{if $allItem[i].is_active eq '1'}
												<label class="label label-success">{$core->get_lang('Actived')}</label>
											{else}
												<label class="label label-danger">{$core->get_lang('InActive')}</label>
											{/if}
										</td>
										<td class="text-center" style="white-space: nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i>
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													<li><a title='View' href="{$PCMS_URL}/?mod={$mod}&act=view&profile_id={$profile_id}">{$core->makeIcon('eye',$core->get_Lang('view'))}</a></li>
													<li><a title="Edit" href="{$PCMS_URL}/?mod={$mod}&act=edit&profile_id={$profile_id}">{$core->makeIcon('pencil',$core->get_Lang('edit'))}</a></li>
													<li><a title="Delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&profile_id={$profile_id}">{$core->makeIcon('times',$core->get_Lang('Delete'))}</a></li>
												</ul>
											</div>
										</td>
									</tr>
									{/section}
								</table>
								<div class="d-flex justify-content-center">
									<ul class="pagination">
										{$html_pager}
									</ul>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>