<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('NewsPage')}</span>
				</a>
			</div>
		</div>
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$clsClassTable->getNameType($type)}</h1>
				<p class="type--subdued">{$core->get_Lang('System Category Management')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void();" class="ui-button ui-button--primary ui-title-bar__action btnCreateCategory" cat_id="0">
						<i class="icon-plus icon-white"></i> <span>{$core->get_Lang('Addnew')}</span> 
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
					<div class="next-tab__container">
						<ul class="next-tab__list filter-tab-list">
							<li class="filter-tab-item" data-tab-index="1">
								<a href="{$PCMS_URL}/index.php?mod={$mod}&act={$act}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('AllCategories')}</a>
							</li>
						</ul>
					</div>
					<div class="ui-card__section has-bulk-actions pages">
						<div class="form-search form-inline">
							<div class="form-group">
								<div class="input-group">
									<input type="text" class="form-control" id="keyword" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
									<div class="input-group-btn">
										<button type="button" class="btn btn-success">{$core->makeIcon('search')}</button>
									</div>
								</div>
							</div>
						</div>
						<table class="table table-vertical table-striped" width="100%">
							<thead><tr>
								<th class="text-center" width="5%">No.</th>
								<th class="text-left">{$core->get_Lang('Title')}</th>
								<th class="text-center" width="6%">{$core->get_Lang('status')}</th>
								<th class="text-center" colspan="4" width="4%">{$core->get_Lang('move')}</th>
								<th class="text-center" width="4%">{$core->get_Lang('action')}</th>
							</tr></thead>
							<tbody id="tblHolderCategory">
								<!-- Code here-->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"> var type = '{$type}'; </script>
<script type="text/javascript" src="{$URL_JS}/assets/jquery.category.js?v={$upd_version}"></script>