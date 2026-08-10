<header class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a class="btn btn-default ui-breadcrumb" href="{$PCMS_URL}/index.php?mod=home" title="{$core->get_Lang('Setting')}">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Home')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Bảng hàng cho thuê</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0)" onClick="$Core.global.open_setting(this, event)" mod_page="{$mod}" class="ui-button ui-button--transparent ui-title-bar__action">Cài đặt</a>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
			<div class="next-tab__container">
				<ul class="next-tab__list filter-tab-list">
					<li class="filter-tab-item" data-tab-index="1">
						<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">Bảng hàng cho thuê</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post">
					<div class="form-search d-flex justify-content-between align-items-center">
						<div class="form-inline d-flex align-items-end">
							<div class="form-group d-flex flex-column mr-2 w-px-250">
								<label class="col-form-label mr-2">Từ khóa</label>
								<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
							</div>
							<div class="form-group mr-2">
								<label class="col-form-label mr-2">Người đăng</label>
								<select name="user_id" class="form-control iso-select2">
									<option value="">Người đăng</option>
									{foreach from=$lstMember item=_oMember}
										<option value="{$_oMember.user_id}" {if $_oMember.user_id eq $user_id}selected{/if}>{$_oMember.full_name}</option>
									{/foreach}
								</select>
							</div>
							<div class="form-group mr-2">
								<label class="col-form-label mr-2">Tình trạng</label>
								<select name="type_list" class="form-control iso-select2">
									<option value="">Tình trạng</option>
									<option value="0" {if $type_list eq "0"}selected{/if}>Chờ duyệt</option>
									<option value="1" {if $type_list eq "1"}selected{/if}>Đã duyệt</option>
									<option value="2" {if $type_list eq "2"}selected{/if}>Không duyệt</option>
								</select>
							</div>
							<div class="form-group mr-2">
								<label class="col-form-label mr-2">Cho thuê HOT</label>
								<select name="is_hot" class="form-control iso-select2">
									<option value="">Chọn</option>
									<option value="1" {if $is_hot eq "1"}selected{/if}>Cho thuê HOT</option>
									<option value="0" {if $is_hot eq "0"}selected{/if}>Không là cho thuê HOT</option>
								</select>
							</div>
							<input type="hidden" name="filter" value="filter" />
							<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
						</div>
						<div class="btn-group d-flex">
							<button type="button" class="btn btn-primary do_action d-none" data-action="approve_multi" onClick="$Core.leasing.approve_leasing(this, event);"><i class="fa fa-check" aria-hidden="true"></i> Duyệt</button>
							<button type="button" class="btn btn-danger do_action d-none" data-action="noapprove_multi" onClick="$Core.leasing.show_notes(this,event)"><i class="fa fa-times" aria-hidden="true"></i> Không duyệt</button>
						</div>
					</div>
					<div class="hastable">
						<div class="freeze-table dragscroll">
							<table id="tableCall" cellspacing="0" class="table table-vertical table-striped" cellpadding="0" width="100%">
								<thead><tr>
									<th class="text-left">
										<div class="checkbox">
											<input type="checkbox" class="checkAll" />
											<label></label>
										</div>
									</th>
									<th class="text-left" width="3%">No.</th>
									<th class="text-left border-end" width="10%">Mã căn</th>
									<th class="text-left" width="10%">Giá full VAT</th>
									<th class="text-left" width="10%">Loại căn</th>
									<th class="text-left">Người liên hệ</th>
									<th class="text-left">Tình trạng</th>
									<th class="text-left" width="3%">HOT</th>
									<th class="text-left" width="6%">Trạng thái</th>
									<th class="text-left" width="6%">Xác minh</th>
									<th class="text-left" width="10%">Ngày tạo</th>
									<th width="45px"></th>
								</tr></thead>
								{section name=i loop=$allItem}
									{assign var=more_information value=$allItem[i].more_information}
								<tr{if $allItem[i].status_id eq $smarty.const._STATUS_STAFF_OFF_ID} class="tr_Off"{/if}>
									<td width="40px">
										<div class="checkbox">
											<input type="checkbox" class="checkitem leasing_item" value="{$allItem[i].leasing_id}" />
											<label></label>
										</div>
									</td>
									<td>{$smarty.section.i.iteration}</td> 
									<td class="border-end"><a class="fw-bold" href="{$clsClassTable->getLink($allItem[i].leasing_id,$more_information.stock_code)}" target="_blank" stock_id="{$allItem[i].stock_id}" leasing_id="{$allItem[i].leasing_id}">{$allItem[i].stock_code}{if $more_information.having_dq eq 1} <span class="bg-danger text-danger px-2 rounded-2">ĐQ</span>{/if}</a></td>
									<td>{$clsISO->priceFormat($allItem[i].price)}</td>
									<td>
										{if !empty($allItem[i].bedroom) || !empty($more_information.bedroom_num)}
											{if $allItem[i].bedroom}{$allItem[i].bedroom}{else}{$more_information.bedroom_num}PN{/if}
										{else}
											-
										{/if}
										{if !empty($more_information.DT_TT)}
											/{$more_information.DT_TT}<span class="text-muted">m<sup>2</sup></span>
										{else}
											-
										{/if}
									</td>
									<td>{$allItem[i].contact_name} / <span class="d-inline-block px-2 py-1 border radius-half">{$allItem[i].contact_phone}</span></td>
									<td>{if $allItem[i].is_locked eq 0 && $allItem[i].is_solded eq 0}
											Đang mở cho thuê
										{else}
											{if $allItem[i].is_solded eq 1}Đã cho thuê 
											{else}Đã khoá{/if}
										{/if}
									</td>
									<td class="text-center bg-gray">
										<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Leasing" pkey="{$pkeyTable}" 
										   sourse_id="{$allItem[i].$pkeyTable}" rel="{$allItem[i].is_hot}" toField="is_hot">
											{if $allItem[i].is_hot eq '1'}
											<i class="fa fa-check-circle green"></i>
											{else}
											<i class="fa fa-minus-circle red"></i>
											{/if}
										</a>
									</td>
									<td>{if $allItem[i].is_online eq 2}
											<span style="color: #BC2A4D">Không duyệt</span>
										{else if $allItem[i].is_online eq 1}
											<span style="color:#368AD2">Đã duyệt</span>
										{else}
											<span style="color:#f0ad4e">Chờ duyệt</span>
										{/if}
									</td>
									<td class="text-center">
										<label class="switch">
										  <input type="checkbox" onChange="$Core.leasing.verified(this, event)" 
											leasing_id="{$allItem[i].leasing_id}"{if $allItem[i].is_verified eq '1'} checked="checked"{/if} value="1" />
										  <span class="slider round"></span>
										</label>
									</td>
									<td class="text-left">
										{$clsISO->convertTimeToText($allItem[i].reg_date, true)}
									</td>
									<td class="text-center" style="white-space: nowrap;">
										<div class="btn-group{if $smarty.section.i.last} dropup{/if}">
											<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown">
												{$core->makeIcon('cog')} <span class="caret"></span>
											</button>
											<ul class="dropdown-menu" style="right:0;left: unset;min-width: 100px;">
												<li {if $allItem[i].is_online eq 1} style="display: none"{/if}><a title="Duyệt" data-action="approve" href="javascript:void(0)" data-leasing_id="{$allItem[i].leasing_id}" onClick="$Core.leasing.approve_leasing(this,event)">
													{$core->makeIcon('check', 'Duyệt')}</a>
												</li>
												<li {if $allItem[i].is_online eq 2} style="display: none"{/if}><a title="Huỷ duyệt" data-action="noapprove" data-leasing_id="{$allItem[i].leasing_id}" href="javascript:void(0)" onClick="$Core.leasing.show_notes(this,event)">
													{$core->makeIcon('close', 'Không duyệt')}</a>
												</li>
												<li {if $allItem[i].is_online eq 0 || $allItem[i].is_online eq 2} style="display: none"{/if}><a title="Huỷ duyệt" data-action="unapprove" data-leasing_id="{$allItem[i].leasing_id}" href="javascript:void(0)" onClick="$Core.leasing.approve_leasing(this,event)">
													{$core->makeIcon('refresh', 'Huỷ duyệt')}</a>
												</li>
												<li><a title="Xoá" data-action="unapprove" data-leasing_id="{$allItem[i].leasing_id}" href="javascript:void(0)" onClick="delete_leasing(this,event)"> 
													{$core->makeIcon('minus', 'Xoá')}</a>
												</li>
											</ul>
										</div>
									</td>
								</tr>
								{/section}
							</table>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="d-flex justify-content-center">
						<ul class="pagination">
							{$html_pager}
						</ul>
					</div>
				</form>
			</div></div>
		</div></div>
	</div>
</div>
<script type="text/javascript" src="{$URL_VIEWS}/leasing/js/jquery.leasing.js?v={$upd_version}"></script>
