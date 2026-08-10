<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Công ty thiết kế nội thất</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="$Core.company.open(this, event)" company_id="0" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
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
								<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">
                                    {$core->get_Lang('AllPages')}
                                </a>
							</li>
						</ul>
					</div>
					<div class="ui-card__section has-bulk-actions pages">
						<form method="post">
							<div class="form-search radius-4 form-inline">
								<div class="form-group">
									<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
								<div class="form-group pull-right">
									<a href="{$PCMS_URL}/?mod={$mod}{$pUrl}" class="btn text-white btn-warning">
										<i class="icon-folder-open icon-white"></i> 
										<span>{$core->get_Lang('all')} ({$total_record})</span>
									</a>
									<a href="javascript:void(0)" clsTable="Company" class="btn btn-danger text-white btn-delete-all" style="display:none"> 
                           				<i class="icon-remove icon-white"></i> 
                           				<span>{$core->get_Lang('Delete')}</span> 
                           			</a>
								</div>
							</div>
							<div class="hastable">
								<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">
									<thead><tr>
										<th width="3%" class="text-center">
											<div class="checkbox">
												<input type="checkbox" id="check_all" class="check_all styled" value="1" />
												<label></label>
											</div>
										</th>
										<th class="text-left">Tên công ty</th>
										<th class="text-left" width="10%">Điện thoại</th>
										<th class="text-left" width="10%">Zalo</th>
										<th class="text-left">Địa chỉ</th>
										<th class="text-center">Tình trạng</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{cycle values="row1,row2"}">
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].company_id}" />
												<label></label>
											</div>
										</td>
										<td class="text-left"><a href="javascript:void(0);" onClick="$Core.company.open(this, event)" company_id="{$allItem[i].$pkeyTable}">
											<strong class="fs-16">{$allItem[i].title_vn} [{$allItem[i].title}]</strong></a>
                                        </td>
										<td class="text-left">{$allItem[i].phone}</td>
										<td class="text-left">{$allItem[i].zalo}</td>
										<td class="text-left">{$allItem[i].address}</td>
										<td class="text-center">
											<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Company" pkey="{$pkeyTable}" 
											   sourse_id="{$allItem[i].$pkeyTable}" rel="{$allItem[i].is_online}" toField="is_online">
												{if $allItem[i].is_online eq '1'}
												<i class="fa fa-check-circle green"></i>
												{else}
												<i class="fa fa-minus-circle red"></i>
												{/if}
											</a>
										</td>
										<td class="text-center" style="white-space:nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> 
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													<li><a href="javascript:void(0);" onClick="$Core.company.open(this, event)" company_id="{$allItem[i].$pkeyTable}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span>
													</a></li>
													<li><a class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&company_id={$core->encryptID($allItem[i].company_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
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