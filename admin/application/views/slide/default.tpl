{assign var= toId value = $clsISO->getUniqid()}
<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Slider</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="$Core.slide.open(this, event)" {$pkeyTable}="0" class="ui-button ui-button--primary ui-title-bar__action mr-2">{$core->get_Lang('Addnew')}</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
			<div class="next-tab__container">
				<ul class="next-tab__list filter-tab-list">
					<li class="filter-tab-item" data-tab-index="1">
						<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('AllPage')}</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post">
					<div class="form-search form-inline">
						<input type="hidden" name="filter" value="filter" />
						<button type="submit" onClick="$Core.docs.do_search(this, event)" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
						<div class="form-group pull-right">
							<a href="javascript:void(0)" clsTable="{$classTable}" class="btn btn-danger text-white btn-delete-all" style="display:none"> 
								<i class="icon-remove icon-white"></i> 
								<span>{$core->get_Lang('Delete')}</span> 
							</a>
						</div>
					</div>
					<div class="hastable">
						<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">
							<thead><tr>
								<th width="5%" class="text-center">
									<div class="checkbox">
										<input type="checkbox" id="check_all" class="check_all styled" value="1" />
										<label></label>
									</div>
								</th>
								<th class="text-left" style="width: 100px">Hình ảnh</th>
								<th class="text-left">Tiêu đề</th>
								<th class="text-left" width="12%">Website</th>
								<th class="text-left" width="15%">Link</th>
								<th class="text-left" width="15%">Nội dung</th>
								<th class="text-center" width="5%">{$core->get_Lang('status')}</th>
								<th class="text-right" width="12%">{$core->get_Lang('update')}</th>
								<th class="text-center" colspan="4" width="4%">{$core->get_Lang('move')}</th>
								<th class="text-center" width="40px">Action</th>
							</tr></thead>
							{foreach from=$allItem item=_oItem}
								<tr class="{cycle values="row1,row2"}">
									<td class="text-center">
										<div class="checkbox">
											<input type="checkbox" name="p_key[]" class="chkitem styled" value="{$_oItem.{$pkeyTable}}" />
											<label></label>
										</div>
									</td>
									<td class="text-left"><img src="{$clsISO->getUrlImageFH($_oItem.image,100)}" alt=""></td>
									<td class="text-left"><a href="javascript:void(0);" onClick="$Core.shop.open(this, event)" shop_id="{$_oItem.$pkeyTable}">
										<strong class="fs-16">{$_oItem.title}</strong></a>
										{if $_oItem.is_trash eq '1'}<span class="fr text-red">{$core->get_Lang('intrash')}</span>{/if}
									</td>
									<td style="text-align:left;font-weight:700">{if !empty($_oItem._site)} <a href="{$arr_domain[$_oItem._site].link}" target="_blank" >{$_oItem._site} <i class="bx bx-link-external text-fs-14"></i></a>{else}--{/if}</td>
									<td class="text-left"><a href="{$_oItem.link}">Link</a></td>
									<td class="text-left">{$clsISO->truncateWord($_oItem.content|strip_tags,10)}</td>
									<td class="text-center bg-gray">
										<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Slide" pkey="{$pkeyTable}" sourse_id="{$_oItem.$pkeyTable}" rel="{$clsClassTable->getOneField('is_online',$_oItem.$pkeyTable)}" title="{$core->get_Lang('Click to change status')}">
											{if $clsClassTable->getOneField('is_online',$_oItem.$pkeyTable) eq '1'}
											<i class="fa fa-check-circle green"></i>
											{else}
											<i class="fa fa-minus-circle red"></i>
											{/if}
										</a>
									</td>
									<td style="text-align:right">{$_oItem.reg_date|date_format:"%d/%m/%Y %H:%M"}</td>
									<td class="text-center">
										{if !$smarty.section.i.first}
										<a title="{$core->get_Lang('movetop')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movetop&{$pkeyTable}={$core->encryptID($_oItem.$pkeyTable)}{$pUrl}"><i class="icon-circle-arrow-up"></i></a>
										{/if}
									</td>
									<td class="text-center">
										{if !$smarty.section.i.last}
										<a title="{$core->get_Lang('movebottom')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movebottom&{$pkeyTable}={$core->encryptID($_oItem.$pkeyTable)}{$pUrl}"><i class="icon-circle-arrow-down"></i></a>
										{/if}
									</td>
									<td class="text-center">
										{if !$smarty.section.i.first}
										<a title="{$core->get_Lang('moveup')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=moveup&{$pkeyTable}={$core->encryptID($_oItem.$pkeyTable)}{$pUrl}"><i class="icon-arrow-up"></i></a>
										{/if}
									</td>
									<td class="text-center">
										{if !$smarty.section.i.last}
										<a title="{$core->get_Lang('movedown')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movedown&{$pkeyTable}={$core->encryptID($_oItem.$pkeyTable)}{$pUrl}"><i class="icon-arrow-down"></i></a>
										{/if}
									</td>
									<td class="text-center" style="white-space: nowrap;">
										<div class="btn-group dropdown">
											<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
												<i class="icon-cog"></i> 
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu" style="right:0px !important; left:auto">
												{if $_oItem.is_trash eq '0'}
												<li><a href="javascript:void(0);" onClick="$Core.slide.open(this, event)" {$pkeyTable}="{$_oItem.$pkeyTable}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span>
												</a></li>
												<li><a href="{$PCMS_URL}/?mod={$mod}&act=trash&{$pkeyTable}={$core->encryptID($_oItem.$pkeyTable)}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
												{else}
												<li><a href="{$PCMS_URL}/?mod={$mod}&act=restore&{$pkeyTable}={$core->encryptID($_oItem.$pkeyTable)}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
												<li><a class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&{$pkeyTable}={$core->encryptID($_oItem.$pkeyTable)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
												{/if}
											</ul>
										</div>
									</td>
								</tr>
							{/foreach}
						</table>
						<div class="d-flex justify-content-center">
							<ul class="pagination">
								{$html_pager}
							</ul>
						</div>
					</div>
				</form>
			</div>
		</div></div>
	</div></div>
</div>
<script>
	var _type=`{$type}`;
</script>