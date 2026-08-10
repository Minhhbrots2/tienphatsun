<!--<header class="ui-title-bar-container ui-title-bar-container--full-width">-->
<header class="ui-title-bar-container">

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
				<h1 class="ui-title-bar__title">{$core->get_Lang('Recharges')}</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$link_page_current_2}&type_list=Success" class="btn mr-2 btn-success">{$core->makeIcon('history')} {$core->get_Lang('Success')} ({$numberSuccess})</a>
					<a href="{$link_page_current_2}&type_list=Failed" class="btn mr-2 btn-warning">{$core->makeIcon('area-chart')} {$core->get_Lang('Failed')} ({$numberFailed})</a>
					<!--<a href="{$PCMS_URL}/?mod={$mod}&act=edit{$pUrl}" class="btn btn-success ui-title-bar__action" title="{$core->get_Lang('Addnew')}">{$core->makeIcon('plus', $core->get_Lang('Addnew'))}</a>-->
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<!--<div class="ui-layout ui-layout--full-width">-->
<div class="ui-layout">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
			<div class="next-tab__container">
				<ul class="next-tab__list filter-tab-list">
					<li class="filter-tab-item" data-tab-index="1">
						<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('All Recharges')}</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post">
					<div class="form-search form-inline">
						<div class="form-group">
							<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
						</div>
						<div class="form-group">
							<div class="input-group-picker">
								<input class="form-control datepicker" name="start_date" value="{$clsISO->convertTimeToText($start_date)}" readonly placeholder="{$core->get_Lang('StartDate')}" />
								<input class="form-control datepicker" name="end_date" value="{$clsISO->convertTimeToText($end_date)}" readonly placeholder="{$core->get_Lang('EndDate')}" />
								{$core->makeIcon('calendar')}
							</div>
						</div>
						<div class="form-group w-30">
							<select name="profile_id" placeholder="Tìm kiếm member" class="iso-selectizeLiveSearch" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=aj_search_members">
								{if $profile_id gt '0'}
								<option value="{$profile_id}" selected="selected">{$clsMember->getIndentity($profile_id)}</option>
								{/if}
							</select>
						</div>
						<input type="hidden" name="filter" value="filter" />
						<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
						<div class="pull-right">
							<div class="fr d-flex group_buttons">
								
								<!-- <div class="dropdown">
								  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"> {$core->get_Lang('Exportto')}
								  <span class="caret"></span></button>
								  <ul class="dropdown-menu w-100">
									<li><a href="{$PCMS_URL}/index.php?mod={$mod}&act=export&file_type=xls">{$core->makeIcon('file-excel-o', $core->get_Lang('Excel'))}</a></li>
								  </ul>
								</div> -->
							</div>
						</div>
					</div>
					<div class="hastable">
						<table cellspacing="0" class="table table-ilooca" cellpadding="0" width="100%">
							<thead><tr>
								<th class="gridheader" width="5%">No.</th>
								<th class="gridheader">Email</th>
								<th class="gridheader">{$core->get_Lang('Type')}</th>
								<th class="text-right" width="150px">{$core->get_Lang('Money')}</th>
								<th class="text-center">{$core->get_Lang('Status')}</th>
								<th class="text-right" width="200px">{$core->get_Lang('Date')}</th>
								<th class="gridheader" width="80px"></th>
							</tr></thead>
							{section name=i loop=$list_history}
							<tr>
								<td class="index">{$smarty.section.i.index+1}</td>
								<td>{$list_history[i].buyer_email}</td>
								<td>{$list_history[i].type}</td>
								<td bgcolor="#F9F9F9" class="text-right">
									{$clsISO->getRate()}
									{$clsISO->formatPrice($list_history[i].money)} 
									{if $list_history[i].type eq 'plus'}
										{$core->makeIcon('long-arrow-up text-success')}
									{else}
										{$core->makeIcon('long-arrow-down text-danger')}
									{/if}
								</td>
								<td class="text-center">{$clsHistory->getStatus($list_history[i].is_payment)}</td>
								<td class="text-right">{$clsISO->convertTimeToText($list_history[i].reg_date, true)}</td>
								<td class="text-center" style="white-space: nowrap;">
									{if $list_history[i].is_payment eq '1'}
									<button class="btn btn-xs btn-default" disabled>{$core->makeIcon('trash')}</button>
									{else}
									<button class="btn btn-xs btn-default">{$core->makeIcon('trash')}</button>
									{/if}
								</td>
							</tr>
							{/section}
						</table>
					</div>
					<div class="clearfix"></div>
					<div class="t-grid-pager-boder">
						<div class="t-pager t-reset fix-margin-pager">
							{$html_pager}
						</div>
					</div>
				</form>
			</div></div>
		</div></div>
	</div>
</div>
{literal}
<style type="text/css">
	.btn-xs{ padding:5px 6px;}
	.btn[disabled]{ opacity:0.5;}
</style>
{/literal}