<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Page')}</h1>
				<p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="$Core.report.open(this, event)" tp="_add" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="ui-layout">
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
										<th width="5%" class="text-center">STT</th>
										<th width="5%" class="text-center"></th>
										<th class="text-left">Tiêu đề</th>
										<th width="80px" class="text-left">Công cụ</th>
									</tr></thead>
									<tbody>
									{if !empty($report_configs)}
										{foreach from=$report_configs key=group_id item = _oG name=i}
										{assign var = list_items value = $_oG.list_items}
										<tr>
											<td class="text-center">{$smarty.foreach.i.iteration}</td>
											<td class="text-center">
												<button group_id="{$group_id}" tp="_add" field_id="" onClick="$Core.report.open(this, event)" class="btn btn-icon btn-xs btn-default"><i class="fa fa-plus"></i></button>
											</td>
											<td class="fw-bold">{$_oG.title}</td>
											<td class="text-center">
												<button onClick="$Core.report.open(this, event)" tp="_edit" title="Sửa" group_id="{$group_id}" class="btn btn-icon btn-xs btn-default"><i class="fa fa-pencil"></i></button>
												<button onClick="$Core.report.delete(this, event)" title="Xóa" group_id="{$group_id}" class="btn btn-icon btn-xs btn-default"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
										{if !empty($list_items)}
											{foreach from=$list_items key=field_id item = _oI name=k}
											<tr>
												<td class="text-center" colspan="2">|_________</td>
												<td class="fw-bold">{$_oI.title}</td>
												<td class="text-center">
													<button onClick="$Core.report.open(this, event)" tp="_edit" title="Sửa" field_id="{$field_id}" group_id="{$group_id}" class="btn btn-icon btn-xs btn-default"><i class="fa fa-pencil"></i></button>
													<button onClick="$Core.report.delete(this, event)" title="Xóa" field_id="{$field_id}" group_id="{$group_id}" class="btn btn-icon btn-xs btn-default"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
											{/foreach}
										{/if}
										{/foreach}
									{/if}
									</tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>