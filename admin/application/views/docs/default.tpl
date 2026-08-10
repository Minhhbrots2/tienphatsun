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

					   <a href="javascript:void(0);" onClick="$Core.docs.cleanup(this, event)" class="ui-button ui-button--transparent ui-title-bar__action ml-2" title="Làm sạch dữ liệu"><i class="fa fa-magic"></i> Làm sạch</a>

					   <span class="cleanup_progress text-muted ml-2" style="display:none"></span>

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

						<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab show-all-items next-tab{if $type_list ne 'trash'} filter-tab-active next-tab--is-active{/if}">{$core->get_Lang('Qũy Căn Hộ Dự Án')}</a>

					</li>

					<li class="filter-tab-item" data-tab-index="2">

						<a href="{$PCMS_URL}/index.php?mod={$mod}&type_list=trash" class="filter-tab next-tab{if $type_list eq 'trash'} filter-tab-active next-tab--is-active{/if}">Thùng rác</a>

					</li>

				</ul>

			</div>

			<div class="ui-card__section has-bulk-actions pages">

				<form method="post">

					<div class="form-search form-inline">

						{assign var = gId value = $clsISO->getUniqid() }

						<div class="form-group" style="width: 200px">

							<select data-field="project_id" name="project_id" onChange="$Core.docs.select_block(this, event)" toId="slb_block_{$gId}" class="form-control search_field iso-select2" data-width="100%">

								<option value="0">Chọn dự án</option>

								{foreach name=i from=$list_projects item = _project}

								<option value="{$_project.project_id}" {if $project_id eq $_project.project_id}selected{/if}>{$_project.title}</option>

								{/foreach}

							</select>

						</div>

						<div class="form-group w-150px">

							<select class="form-control search_field mr-2 iso-select2" id="slb_block_{$gId}" name="block_id" onChange="$Core.docs.select_building(this, event)" 

									toId="slb_building_{$gId}" data-field="block_id">

								<option value="0">Chọn phân khu</option>

								{if !empty($project_id) && !empty($list_blocks)}

									{foreach from=$list_blocks item=_oItem key=key name=i}

										<option value="{$_oItem.property_id}" {if $block_id eq $_oItem.property_id}selected{/if}>{$_oItem.title}</option>

									{/foreach}

								{/if}

							</select>

						</div>

						<div class="form-group w-150px">

							<select class="form-control search_field mr-2 iso-select2" id="slb_building_{$gId}" name="building_id" data-field="building_id">

								<option value="0">Chọn toà nhà</option>								

								{if !empty($block_id) && !empty($list_buildings)}

									{foreach from=$list_buildings item=_oItem key=key name=i}

										<option value="{$_oItem.property_id}" {if $building_id eq $_oItem.property_id}selected{/if}>{$_oItem.title}</option>

									{/foreach}

								{/if}

							</select>

						</div>

						<div class="form-group w-150px">

							<select class="form-control search_field mr-2 w-150px iso-select2" data-field="type" name="type">

								<option value="">Chọn loại</option>

								<option value="project">Dự án</option>

								<option value="block">Phân khu</option>

								<option value="building">Tòa</option>

								

							</select>

						</div>

						<div class="form-group w-150px">

							<select class="form-control search_field mr-2 w-150px iso-select2" data-field="cat_id" name="cat_id">

								<option value="0">Chọn danh mục</option>

								{$clsProperty->getListOption('_CATEGORY_DOCS',$cat_id)}

							</select>

						</div>

						<div class="form-group">

							<div class="input-group">

								<input type="text" class="form-control search_field" data-field="keyword" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />

							</div>

						</div>

						<input type="hidden" name="type_list" value="{$type_list}" />

						<input type="hidden" name="filter" value="filter" />

						<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>

						<div class="form-group pull-right">

							<a href="javascript:void(0)" clsTable="{$classTable}" class="btn btn-danger text-white btn-delete-all" style="display:none" onClick="$Core.docs.delete_all(this, event)" type_list="{$type_list}"> 

								<i class="icon-remove icon-white"></i> 

								<span>{$core->get_Lang('Delete')}</span> 

							</a>

						</div>

					</div>

					<div class="hastable">

						<table class="table" width="100%" cellpadding="0" cellspacing="0">

							<thead><tr>

								<th width="5%" class="text-center">

									<div class="checkbox">

										<input type="checkbox" id="check_all" 

										class="check_all styled" value="1" />

										<label></label>

									</div>

								</th>

								<th width="50px" class="text-center">Ảnh</th>

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

								{if !empty($list_docs)}

									{foreach name=i from=$list_docs item = _odocs}

									{assign var = list_tags value = $_odocs.list_tags}

									<tr>

										<td class="text-center">

											<div class="checkbox">

												<input type="checkbox" name="p_key[]" class="chkitem styled" 

												value="{$_odocs.id}" />

												<label></label>

											</div>

										</td>

										<td class="text-center"><img src="{$_odocs.image}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'" class="border radius-3" style="width:40px;height:40px;object-fit:cover" /></td>

										<td><strong class="font-bold">{$_odocs.title}</strong>{if $_odocs.file_type} <span class="label label-info fs-tiny">{$_odocs.file_type}</span>{/if}<br />

											{$_odocs.title_search}

											{if !empty($list_tags)}

											<div class="mt-2">

												{foreach from=$list_tags item = tag}

													<span class="label label-primary p-1 fs-tiny">{$tag}</span>

													{/foreach}

												{/if}

											</div>

										</td>

										<td><a href="{$_odocs.content}" title="{$_odocs.content}" data-toggle="tooltip" target="_blank">Link</a></td>

										<td class="text-left">{$_odocs.type}</td>

										<td class="text-left">{$_odocs.project_name}</td>

										<td class="text-left">{$_odocs.block_name}</td>

										<td class="text-left">{$_odocs.building_name}</td>

										<td class="text-left">{$_odocs.cat_name}</td>

										<td class="text-left text-nowrap">{$clsISO->formatDate($_odocs.upd_date,4)}</td>

										<td class="text-center">

											<div class="d-flex gap-2 align-items-center">

												{if $type_list eq 'trash'}

												<button onClick="$Core.docs.restore(this, event)" project_meta_id="{$_odocs.id}" class="btn btn-icon btn-sm btn-default" title="Khôi phục">{$core->makeIcon('undo')}</button>

												<button onClick="$Core.docs.force_delete(this, event)" project_meta_id="{$_odocs.id}" class="btn btn-icon btn-sm btn-danger" title="Xoá vĩnh viễn">{$core->makeIcon('trash')}</button>

												{else}

												<button onClick="$Core.docs.open(this, event)" project_meta_id="{$_odocs.id}" class="btn btn-icon btn-sm btn-default">{$core->makeIcon('pencil')}</button>

												<button onClick="$Core.docs.delete(this, event)" project_meta_id="{$_odocs.id}" class="btn btn-icon btn-sm btn-default">{$core->makeIcon('trash')}</button>

												{/if}

											</div>



										</td>

									</tr>

									{/foreach}

								{/if}

							</tbody>

						</table>

					</div>

					<div class="t-grid-pager-boder d-flex justify-content-center">

						<ul class="t-pager pagination fix-margin-pager">

							{$html_pager}

						</ul>

					</div>

				</form>

			</div>

		</div></div>

	</div></div>

</div>

{$script}