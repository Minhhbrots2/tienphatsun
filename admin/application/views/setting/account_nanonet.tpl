<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Quản lý tài khoản</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0)" onClick="$Core.account.open(this,event)" data-key="" data-action="_add" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
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
							<div class="hastable">
								<table class="table table-vertical table-striped" cellspacing="0" cellpadding="0" width="100%">
									<thead><tr>
										<th width="5%" class="text-center">
											STT
										</th>
										<th class="text-left" >API key</th>
										<th class="text-left">Model ID</th>
										<th class="text-left">Sử dụng</th>
										<th class="text-left">Ngày cập nhật cuối</th>
										<th class="text-left">Ngày reset</th>
										<th class="text-left">Lần sử dụng cuối</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{foreach from=$lst_account item=_oItem key=key name=i}
										{assign var=model_ids value=$_oItem.model_ids}
										<tr class="{cycle values="row1,row2"}">
											<td class="text-center">
												{$smarty.foreach.i.iteration}
											</td>
											<td>{$_oItem.api_key}</td>
											<td>{if $_oItem.model_id.key}{$_oItem.model_id.key}{else}--{/if}</td>
											<td>{$_oItem.model_id.number} lượt</td>
											<td>{$_oItem.model_id.date|date_format:"%d/%m/%Y"}</td>
											<td>{$_oItem.model_id.upd_date|date_format:"%d/%m/%Y"}</td>
											<td>{if !empty($_oItem.time_upd_last)}{$_oItem.time_upd_last|date_format:"%d/%m/%Y"}{else}--{/if}</td>
											<td class="text-center" style="white-space: nowrap;">
												<div class="btn-group dropdown">
													<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
													<ul class="dropdown-menu" style="right:0px !important; left:auto">
														<li><a title="{$core->get_Lang('edit')}" href="javascript:void(0)" onClick="$Core.account.open(this,event)" data-action="_edit" data-key="{$key}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
														<li><a title="{$core->get_Lang('delete')}" href="javascript:void(0)" onClick="$Core.account.delete(this,event)" data-key="{$key}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
													</ul>
												</div>
											</td>
										</tr>									
									{/foreach}
								</table>
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