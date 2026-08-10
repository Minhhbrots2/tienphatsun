<header class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">
					<a href="{$PCMS_URL}/?mod={$mod}" class="text-muted" style="font-size:14px"><i class="fa fa-angle-left"></i> Dự án</a>
					&nbsp;/&nbsp;{$oneItem.title}
				</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/?mod={$mod}&act=edit&project_id={$project_id}" class="ui-button ui-button--primary ui-title-bar__action"><i class="fa fa-edit"></i> Sửa thông tin</a>
					<a href="{$PCMS_URL}/?mod={$mod}&act=trash&project_id={$project_id}" class="ui-button ui-button--transparent ui-title-bar__action confirm_delete" title="Chuyển vào thùng rác"><i class="fa fa-trash"></i> Xóa</a>
					<a href="{$PCMS_URL}/?mod={$mod}&act=delete&project_id={$project_id}" data-title="{$oneItem.title|escape}" class="ui-button ui-button--transparent ui-title-bar__action js_delete_project" title="Xóa vĩnh viễn dự án và dữ liệu liên quan"><i class="fa fa-times-circle"></i> Xóa vĩnh viễn</a>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">

			{* ===== SECTION A — Info card ===== *}
			<div class="ui-layout__item">
				<div class="ui-card">
					<div class="ui-card__section">
						<div class="form-row" style="align-items:center">
							<div class="col-md-7">
								<h2 class="ui-heading" style="margin:0 0 6px">{$oneItem.title}</h2>
								<p class="text-muted" style="margin:0">
									<span class="label label-primary">{$stat_blocks}</span> phân khu &nbsp;·&nbsp;
									<span class="label label-default">{$stat_buildings}</span> tòa
									{if $oneItem.reg_date}&nbsp;·&nbsp; Tạo {$oneItem.reg_date|date_format:"%d/%m/%Y"}{/if}
								</p>
							</div>
							<div class="col-md-5 text-right">
								<label class="switch-inline" style="margin-right:18px">
									<span class="text-muted" style="margin-right:6px">Khóa</span>
									<label class="switch">
										<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_lock" tp="_project" value="1" for_id="{$project_id}"{if $oneItem.is_lock eq '1'} checked{/if}>
										<span class="slider round"></span>
									</label>
								</label>
								<label class="switch-inline">
									<span class="text-muted" style="margin-right:6px">Quick Menu</span>
									<label class="switch">
										<input type="checkbox" onchange="set_quick_menu(this, event)" to_field="is_menu" tp="_project" value="1" for_id="{$project_id}"{if $oneItem.is_menu eq '1'} checked{/if}>
										<span class="slider round"></span>
									</label>
								</label>
							</div>
						</div>
					</div>

					{* ===== SECTION B — Lưới nút chức năng ===== *}
					<div class="ui-card__section">
						<div class="form-row proj-actions">
							<div class="col-md-4 proj-group proj-group--struct">
								<h4 class="proj-group-title"><i class="fa fa-sitemap"></i> Cấu trúc</h4>
								<a href="#blocks" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-th-large"></i></span><span class="proj-tile__tx">Phân khu &amp; Tòa</span></a>
								{if !empty($block_type_arrs)}
									{foreach from=$block_type_arrs item=_bt}
									<a href="{$PCMS_URL}/?mod={$mod}&act=map&project_id={$project_id}&stock_type={$_bt}" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-map-marker"></i></span><span class="proj-tile__tx">Bản đồ {if $_bt eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}(Cao tầng){elseif $_bt eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}(Thấp tầng){/if}</span></a>
									{/foreach}
								{else}
								<a href="{$PCMS_URL}/?mod={$mod}&act=map&project_id={$project_id}" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-map-marker"></i></span><span class="proj-tile__tx">Bản đồ dự án</span></a>
								{/if}
								<a href="{$PCMS_URL}/?mod={$mod}&act=draw_map&project_id={$project_id}" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-pencil-square-o"></i></span><span class="proj-tile__tx">Vẽ bản đồ</span></a>
							</div>
							<div class="col-md-4 proj-group proj-group--content">
								<h4 class="proj-group-title"><i class="fa fa-file-text-o"></i> Nội dung</h4>
								<a href="javascript:void(0);" onClick="open_progress(this, event)" block_id="0" project_id="{$project_id}" _openFrom="_project" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-tasks"></i></span><span class="proj-tile__tx">Tiến độ dự án</span></a>
								<button type="button" onClick="$Core.project.open_sop(this, event)" sop_id="0" project_id="{$project_id}" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-clipboard"></i></span><span class="proj-tile__tx">SOP / Tiêu chuẩn</span></button>
								<button type="button" onClick="$Core.utilities.open(this, event)" project_id="{$project_id}" utilities_id="" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-cubes"></i></span><span class="proj-tile__tx">Tiện ích dự án</span></button>
							</div>
							<div class="col-md-4 proj-group proj-group--config">
								<h4 class="proj-group-title"><i class="fa fa-cog"></i> Thông tin &amp; cấu hình</h4>
								<a href="{$PCMS_URL}/?mod={$mod}&act=edit&project_id={$project_id}" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-edit"></i></span><span class="proj-tile__tx">Sửa thông tin dự án</span></a>
								{if !empty($block_type_arrs)}
									{foreach from=$block_type_arrs item=_bt}
									<button type="button" onClick="$Core.project.open_config_column(this, event)" block_type="{$_bt}" project_id="{$project_id}" class="proj-tile"><span class="proj-tile__ic"><i class="fa fa-table"></i></span><span class="proj-tile__tx">Cấu hình {if $_bt eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}(Cao tầng){elseif $_bt eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}(Thấp tầng){/if}</span></button>
									{/foreach}
								{/if}
							</div>
						</div>
					</div>
				</div>
			</div>

			{* ===== SECTION C — Cây Phân khu -> Tòa ===== *}
			<div class="ui-layout__item" id="blocks">
				<div class="ui-card">
					<div class="ui-card__section">
						<div class="d-flex" style="justify-content:space-between; align-items:center; margin-bottom:10px">
							<h3 class="ui-heading" style="margin:0">Phân khu &amp; Tòa</h3>
							<a href="javascript:void(0);" onClick="open_block(this, event)" block_id="0" project_id="{$project_id}" _openFrom="_project" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Thêm phân khu</a>
						</div>
						<div class="holderBlock">
							{$blocks_html}
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
{literal}
<script>window.__projectView = 'overview';</script>
{/literal}
