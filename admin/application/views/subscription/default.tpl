<header class="ui-title-bar-container ">
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
				<h1 class="ui-title-bar__title">{$core->get_Lang('Subscribe')}</h1>
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
								<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('All Subscribe')}</a>
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
								<div class="pull-right">
									<div class="fr group_buttons">
										<a href="{$PCMS_URL}/?mod={$mod}" class="btn btn-warning">{$core->makeIcon('folder-o')} {$core->get_Lang('all')} ({$number_all})</a>
										<a href="{$PCMS_URL}/index.php?mod={$mod}&act=export&file_type=xls" class="btn text-white btn-primary">{$core->makeIcon('file-excel-o', $core->get_Lang('exporttoexcel'))}</a>
										<a href="{$PCMS_URL}/index.php?mod={$mod}&act=export&file_type=csv" class="btn text-white btn-primary">{$core->makeIcon('file-text-o', $core->get_Lang('exporttocsv'))}</a>
									</div>
								</div>
							</div>
							<div class="hastable">
								<table class="table table-vertical table-striped" cellspacing="0" cellpadding="0" width="100%">
									<thead><tr>
										<th class="text-center" width="3%">No.</th>
										<th class="text-left">{$core->get_Lang('Register Email')}</th>
										<th class="text-right" width="20%">{$core->get_Lang('Rigister Date')}</th>
										<th class="text-center" width="50px">Action</th>
									</tr></thead>
									{if $allItem}
										{section name=i loop=$allItem}
										<tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
											<td class="text-center"> {$smarty.section.i.index+1}</td>
											<td class="text-left"><a href="mailto:{$allItem[i].email}">{$allItem[i].email}</a></td>
											<td class="text-right">{$clsISO->convertTimeToText($allItem[i].reg_date, true)}</td>
											<td style="vertical-align: middle; width: 20px; text-align: right; white-space: nowrap;">
												<a title="Xóa hẳn" class="btn btn-xs btn-default confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&subscribe_id={$core->encryptID($allItem[i].subscribe_id)}"><i class="icon-remove"></i></a>
											</td>
										</tr>
										{/section}
									{else}
										<tr>
											<td colspan="10" class="text-center">{$core->get_Lang('No Data')} !</td>
										</tr>
									{/if}
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