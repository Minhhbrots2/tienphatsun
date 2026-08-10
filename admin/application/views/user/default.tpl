<header class="ui-title-bar-container ">
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
				<h1 class="ui-title-bar__title">{$core->get_Lang('Administrators')}</h1>
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
								<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('AllAdministrators')}</a>
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
										<th class="text-left">{$core->get_Lang('First Name')}</th>
										<th class="text-left">{$core->get_Lang('First Name')}</th>
										<th class="text-left">{$core->get_Lang('Username/Email')}</th>
										<th class="text-center" width="10%">{$core->get_Lang('Status')}</th>
										<th class="text-center" width="10%">Update bảng hàng</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
										<td class="text-center">{$smarty.section.i.iteration}</td>
										<td class="text-left">{$allItem[i].first_name}</td>
										<td class="text-left">{$allItem[i].last_name}</td>
										<td class="text-left"><a title="Edit" href="{$PCMS_URL}/index.php?admin&mod={$mod}&act=edit&user_id={$core->encryptID($allItem[i].user_id)}" class="row-title"><strong>{$allItem[i].user_name}</strong></a></td>
										<td class="text-center">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="User" pkey="{$pkeyTable}" toField="is_active" sourse_id="{$allItem[i].$pkeyTable}" rel="{$allItem[i].is_active}" title="{$core->get_Lang('Click to change status')}">
												{if $allItem[i].is_active eq '1'}
												<i class="fa fa-check-circle green"></i>
												{else}
												<i class="fa fa-minus-circle red"></i>
												{/if}
											</a>
										</td>
										<td class="text-center"><button class="btn btn-default" type="button" onClick="$Core.user.open_permiss_stock(this,event)" data-id="{$allItem[i].user_id}">Chọn</button></td>
										<td class="text-center" style="white-space:nowrap;">
											<div class="btn-group">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> 
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left: auto">
													<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?admin&mod={$mod}&act=edit&user_id={$core->encryptID($allItem[i].user_id)}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
													<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?admin&mod={$mod}&act=delete&user_id={$core->encryptID($allItem[i].user_id)}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
												</ul>
											</div>
										</td>
									</tr>	
									{/section}
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