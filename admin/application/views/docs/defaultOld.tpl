{assign var= toId value = $clsISO->getUniqid()}
<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Kho tài liệu dự án')}</h1>
				<p class="type--subdued">{$core->get_Lang('Quản lý toàn bộ tài liệu liên quan tới dự án')}</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="$Core.docs.open(this,event)" project_meta_id="0" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" cat_id="{$cat_id}" title="{$core->get_Lang('Addnew')}" 
					   class="ui-button ui-button--transparent js_create_add ui-title-bar__action mr-2">{$core->makeIcon('plus', $core->get_Lang('Add'))}</a>
					<a href="javascript:void(0);" onClick="$Core.docs.select_file(this, event)" project_meta_id="0" title="{$core->get_Lang('Import')}" 
					   class="ui-button ui-button--transparent js_start_select_file ui-title-bar__action" toId="{$toId}">{$core->makeIcon('upload', $core->get_Lang('Import'))}</a>
				</div>
			</div>
		</div>
	</div>
</div>
<form class="d-none" method="post" enctype="multipart/form-data">
	<input type="file" class="select_file select_file_{$toId}" name="import_file" />
</form>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
			<div class="next-tab__container">
				<ul class="next-tab__list filter-tab-list">
					<li class="filter-tab-item" data-tab-index="1">
						<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('Qũy Căn Hộ Dự Án')}</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post">
					<div class="form-search form-inline">
						{assign var = gId value = $clsISO->getUniqid() }
						<div class="form-group">
							<select data-field="project_id" onChange="$Core.docs.select_block(this, event)" toId="slb_block_{$gId}" class="form-control search_field">
								<option value="0">Chọn dự án</option>
								{foreach name=i from=$list_projects item = _project}
								<option value="{$_project.project_id}">{$_project.title}</option>
								{/foreach}
							</select>
						</div>
						<div class="form-group">
							<select class="form-control search_field mr-2 w-150px" id="slb_block_{$gId}" onChange="$Core.docs.select_building(this, event)" 
									toId="slb_building_{$gId}" data-field="block_id">
								<option value="0">Chọn phân khu</option>
							</select>
						</div>
						<div class="form-group">
							<select class="form-control search_field mr-2 w-150px" id="slb_building_{$gId}" data-field="building_id">
								<option value="0">Chọn toà nhà</option>
							</select>
						</div>
						<div class="form-group">
							<select class="form-control search_field mr-2 w-150px" data-field="type">
								<option value="">Chọn loại</option>
								<option value="project">Dự án</option>
								<option value="block">Phân khu</option>
								<option value="building">Tòa</option>
								
							</select>
						</div>
						<div class="form-group">
							<select class="form-control search_field mr-2 w-150px" data-field="cat_id">
								<option value="0">Chọn danh mục</option>
								{$clsProperty->getListOption('_CATEGORY_DOCS',$cat_id)}
							</select>
						</div>
						<div class="form-group">
							<div class="input-group">
								<input type="text" class="form-control search_field" data-field="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
							</div>
						</div>
						<input type="hidden" name="filter" value="filter" />
						<button type="submit" onClick="$Core.docs.do_search(this, event)" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
						<div class="form-group pull-right">
							<a href="javascript:void(0)" clsTable="{$classTable}" class="btn btn-danger text-white btn-delete-all" style="display:none"> 
								<i class="icon-remove icon-white"></i> 
								<span>{$core->get_Lang('Delete')}</span> 
							</a>
						</div>
					</div>
					<div class="hastable" style="max-height: calc(100vh - 100px);overflow-y: auto">
						<table class="table" width="100%" cellpadding="0" cellspacing="0">
							<thead><tr>
								<th width="5%" class="text-center">
									<div class="checkbox">
										<input type="checkbox" id="check_all" 
										class="check_all styled" value="1" />
										<label></label>
									</div>
								</th>
								<th width="" class="text-left">Tiêu đề</th>	
								<th class="text-left">Nội dung</th>
								<th class="text-left">Loại</th>
								<th class="text-left">Dự án</th>
								<th class="text-left">Phân khu</th>
								<th class="text-left">Tòa</th>
								<th class="text-left">Danh mục</th>
								<th class="text-left">Thời gian</th>
								<th width="120px">H.Động</th>	
							</tr></thead>
							<tbody class="holder_docs">
								<tr>
									<td class="text-center" colspan="10">
										Loading...
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div></div>
	</div></div>
</div>
{$script}