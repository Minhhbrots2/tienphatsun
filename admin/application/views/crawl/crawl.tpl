<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Danh sách đại lý cập nhật cao tầng</h1>
				<p class="type--subdued mb-0">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
			</div>
		</div>
		<button title="Hướng dẫn sử dụng" onclick="$Core.crawl.open_help(this, event)" data-type="highfloor" data-toggle="ripple" class="btn btn-icon btn-outline-default"><i class="fa fa-info-circle" style="font-size:24px"></i></button>
	</div>
</div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
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
				<div class="freeze-table dragscroll" style="overflow-x: scroll; width:100%;">
					<table id="tableCall" cellspacing="0" class="table table-vertical table-striped no-maxwidth" width="100%">
						<thead><tr>
							<th class="align-center text-right align-center" width="60px" rowspan="2">{$core->get_Lang('Actions')}</th>
							<th class="align-center text-left" rowspan="2">Tiêu đề</th>
							<th class="align-center text-center" width="" colspan="{$list_blocks|@count}">Phân khu</th>
						</tr>
						<tr>
							{foreach from=$list_blocks item = _block_name}
							<th class="text-center" width="">{$_block_name}</th>
							{/foreach}
						</tr></thead>
						<tbody>
						{if !empty($list_agency) }
							{foreach from=$list_agency item=_oItem name=i }
								{assign var = block_crawl value = $_oItem.block_crawl}
								{assign var = more_information value = $_oItem.more_information}
								{if !empty($more_information.spreadsheetId)}
								<tr class="tr_agency tr_agency_{$_oItem.property_id}">
									<td class="text-center" data-label="{$core->get_Lang('Actions')}">
										<button class="btn btn-icon btn-default" onClick="$Core.crawl.open_agency(this,event)" stock_type="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}" class="btn btn-default" agency_id="{$_oItem.property_id}">{$core->makeIcon('pencil')}</button>
									</td>
									<td class="text-nowrap" data-label="Tiêu đề">{$_oItem.title}</td>
									{foreach from=$list_blocks item=_block_name key=key}
									<td class="text-center">
										<label class="switch">
											<input type="checkbox" onchange="$Core.crawl.handle_status(this, event)" stock_type="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}" project_id="" block_id="{$key}" agency_id="{$_oItem.property_id}" value="1" class="switch_{$clsISO->getUniqid()}" name="is_crawl" {if !empty($block_crawl[$key].is_crawl)}checked{/if}>
											<span class="slider round"></span>
										</label>
									</td>
									{/foreach}
								</tr>
								{/if}
							{/foreach}
						{else}
							<tr><td class="text-center" colspan="4">Danh sách trống</td></tr>
						{/if}
						</tbody>
					</table>
				</div>
			</div>
		</div></div>
	</div></div>
</div>
{literal}
<style type="text/css">
	.w-100px{width:100px !important;}
	.w-120px{width:120px !important;}
	.w-250px{width:250px !important;}
	.table{margin-bottom:0;max-width:18000px;}
	.input-group-suffix .suffix{ right:10px;}
	.freeze-table {
        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
	}
	tr.stock_mask > td:first-child{
		position:relative;
	} 
	tr.stock_mask > td:first-child:before{
		content: "";
		position: absolute;
		left: -8px; top: -3px;
		border-bottom: 10px solid #C00000;
		border-left: 10px solid transparent;
		border-right: 10px solid transparent;
		transform: rotate(-45deg);
		-moz-transform: rotate(-45deg);
		-webkit-transform: rotate(-45deg);
	}
	.mega-dropdown-menu{
		min-width:300px;
	}
	.dropdown-menu > li > a.disabled{
		color:gray;
		opacity:0.2l
		filter:alpha(opacity=20);
	}
</style>
<script type="text/javascript">
	$(function(){
		setTimeout(() => {
			$('.freeze-table').freezeTable({
				'columnNum': 2,
				'scrollable': true
			});
		},1000);
	});
</script>
{/literal}