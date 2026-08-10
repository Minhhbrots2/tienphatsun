<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Thanh toán gói dịch vụ My Ocean City</h1>
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
									<a href="{$PCMS_URL}/?mod={$mod}&status=1{$pUrl2}" class="btn text-white btn-success">
										<i class="icon-folder-open icon-white"></i> 
										<span>Đã thanh toán ({$total_active})</span>
									</a>
									<a href="{$PCMS_URL}/?mod={$mod}&status=0{$pUrl2}" class="btn text-white btn-warning">
										<i class="icon-folder-open icon-white"></i> 
										<span>Chưa thanh toán ({$total_noactive})</span>
									</a>
									<a href="{$PCMS_URL}/?mod={$mod}&status=2{$pUrl2}" class="btn text-white btn-danger">
										<i class="icon-folder-open icon-white"></i> 
										<span>Đã huỷ ({$total_cancel})</span>
									</a>
									<a href="{$PCMS_URL}/?mod={$mod}{$pUrl2}" class="btn btn-default">
										<i class="icon-folder-open"></i> 
										<span>{$core->get_Lang('all')} ({$total_all})</span>
									</a>
									<a href="javascript:void(0)" clsTable="Order" class="btn btn-danger text-white btn-delete-all" style="display:none"> 
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
										<th class="text-left" width="15%">Mã thanh toán</th> 
										<th class="text-left">Tài khoản</th>
										<th class="text-left" width="200">Gói dịch vụ</th> 
										<th class="text-left" width="150">Tiền</th> 
										<th class="text-left" width="150">Tình trạng</th> 
										<th class="text-left" width="6%">Trạng thái</th>
										<th class="text-left" width="150">Thời gian tạo</th>
										<th class="text-left" width="150">Thời gian thanh toán</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{section name=i loop=$allItem}
									<tr class="{cycle values="row1,row2"}">
										<td class="text-center">
											<div class="checkbox">
												<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$allItem[i].order_id}" />
												<label></label>
											</div>
										</td>
										<td class="text-left">{$allItem[i].order_code}</a>
                                        </td>
										<td class="text-left">
											<div class="">
												<p class="mb-1">(ID: {$allItem[i].profile_id}) <strong>{$allItem[i].member_name}</strong></p>
												<p class="mb-1"><i class="fa fa-envelope-o mr-2" aria-hidden="true"></i>{$allItem[i].member_email}</p>
												<p class="mb-0"><i class="fa fa-phone mr-2" aria-hidden="true"></i>{$allItem[i].member_phone}</p>
											</div>
										</td>
										<td class="text-left">{$allItem[i].package_name} - {$allItem[i].time_package}</a>
										<td class="text-left"><span class="text-danger">{$clsISO->formatPrice($allItem[i].amount)}đ</span></td>
										<td class="text-left txt_status">
											{if $allItem[i].is_cancel}
											<span class="text-danger">Đã hủy</span>
											{else if $allItem[i].status eq 0}
											<span class="text-warning">Đang chờ</span>
											{else}											
											<span class="text-success">Đã thanh toán</span>
											{/if}
										</td>
										<td class="text-center bg-gray">
											{if $allItem[i].is_cancel}
											--
											{else}
												<label class="switch">
												  <input type="checkbox" onchange="$Core.order.updateStatus(this, event)" order_id="{$allItem[i].order_id}" {if $allItem[i].status eq 1}checked{/if} value="1" {if $allItem[i].status eq 1}disabled {/if}>
												  <span class="slider round"></span>
												</label>
											{/if}
											
											
										</td>
										<td class="text-left">{$allItem[i].time_order}</td>
										<td class="text-left txt_status_date">{$allItem[i].time_payment}</td>
										<td class="text-center" style="white-space: nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
													<i class="icon-cog"></i> 
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													<li><a href="{$smarty.const.MYOCEAN_URL}{$clsClassTable->getLinkOrder($allItem[i].order_id,$allItem[i])}" target="_blank"><i class="icon-eye-open"></i> <span>{$core->get_Lang('view')}</span></a></li>
													<li><a class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&order_id={$core->encryptID($allItem[i].order_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
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