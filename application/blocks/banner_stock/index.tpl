<link rel="stylesheet" type="text/css" href="{$URL_CSS}/stock-header.css?v={$upd_version}" />
<section class="sh-hero mb-3 position-relative zindex-3">
	<div class="sh-hero__top">
		<div class="sh-hero__thumb" style="background-image:url('{$oneProject.image}')"></div>
		<div class="sh-hero__main">
			{if !empty($oneBuilding)}
				<h1 class="sh-hero__titleline"><span class="sh-hero__title">{if !empty($ms_code)}{$ms_code|escape}{else}{$oneBuilding.title|escape}{/if}</span>{if $act eq 'stock' || $act eq 'map'} <span class="sh-hero__count"><span class="total_stock">{if !empty($total_stocks)}{$total_stocks}{else}0{/if}</span> <small>căn</small></span>{/if}</h1>
			{elseif !empty($block_is_project)}
				<h1 class="sh-hero__titleline"><span class="sh-hero__title">{$oneBlock.title|escape}</span>{if $act eq 'stock' || $act eq 'layout'} <span class="sh-hero__count"><span class="total_stock">{if !empty($total_stocks)}{$total_stocks}{else}0{/if}</span> <small>căn</small></span>{/if}</h1>
			{else}
				<h1 class="sh-hero__titleline"><span class="sh-hero__title">{$oneProject.title|escape}</span>{if $act eq 'stock' || $act eq 'layout'} <span class="sh-hero__count"><span class="total_stock">{if !empty($total_stocks)}{$total_stocks}{else}0{/if}</span> <small>căn</small></span>{/if}</h1>
			{/if}
			<p class="sh-hero__sub">
				{if !empty($oneBlock)}<a href="{$clsProject->getLinkDetail($project_id,$block_id,0,$oneProject)}">Phân khu {$oneBlock.title|escape}</a>, {/if}<a href="{$clsProject->getLinkDetail($project_id,0,0,$oneProject)}" class="text-body">{$oneProject.title|escape}</a>
			</p>
			{* chip thong so: chip nao thieu du lieu tu an *}
			{if !empty($investor_name) || !empty($oneBuilding) || !empty($sh_floor) || !empty($sh_house) || !empty($sh_elevator)}
			<div class="sh-meta">
				{if !empty($investor_name)}
					<span class="sh-chip"><i class="bx bx-briefcase"></i> CĐT: <b>{$investor_name|escape}</b></span>
				{/if}
				{if !empty($oneBuilding)}
					<span class="sh-chip"><i class="bx bx-buildings"></i> Tòa <b>{$oneBuilding.title|escape}</b></span>
				{/if}
				{if !empty($sh_floor)}
					<span class="sh-chip"><i class="bx bx-layer"></i> <b>{$sh_floor}</b> tầng</span>
				{/if}
				{if !empty($sh_total_units)}
					<span class="sh-chip"><i class="bx bx-grid-alt"></i> <b>{$sh_total_units}</b> căn</span>
				{/if}
				{if !empty($sh_house)}
					<span class="sh-chip"><i class="bx bx-door-open"></i> <b>{$sh_house}</b> căn/sàn</span>
				{/if}
				{if !empty($sh_elevator)}
					<span class="sh-chip"><i class="bx bx-move-vertical"></i> <b>{$sh_elevator}</b> thang máy</span>
				{/if}
			</div>
			{/if}
		</div>
		<div class="sh-hero__side">
			<div class="eblWgTAbDi">
				{if !empty($lstBuildingBl)}
					<div class="dropdown">
						<button data-toggle="ripple" class="btn btn-outline-default rounded-pill dropdown-toggle{if $deviceType eq 'phone'} btn-sm{/if}" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" data-popper-placement="top-start" aria-haspopup="true" aria-expanded="false">Bảng hàng</button>
						<div class="dropdown-menu dropdown-menu-stock dropdown-menu-stock_project" style="max-width: 350px">
							<div class="p-3">
								<div class="block-one mt-0 mt-lg-2">
									<div class="divider my-2">
										<div class="divider-text">Phân khu {$oneBlock.title|escape}</div>
									</div>
									<div class="d-flex gap-2 align-items-center justify-content-center">
										<img src="{$clsISO->getUrlImageFH($oneProject.logo,0,30)}" class="h-px-30" />
										<h3 class="fs-6 mb-0 text-upper">{$oneProject.title|escape}</h3>
									</div>
									<ul class="mb-0 list-unstyled d-flex flex-wrap gap-2 mt-2">
										{foreach from=$lstBuildingBl item=_oBuilding key=k_block name=n_building}
										<li class="flex-fill">
											<a data-toggle="ripple" href="{$_oBuilding.link}" title="{$_oBuilding.title|escape}" class="btn btn-sm btn-outline-primary building-name w-100" data-color="{$block_information.bgcolor}" style="border-color: {$block_information.bgcolor} !important; background-color: {$block_information.bgcolor} !important; color: {$block_information.textcolor} !important;">{$_oBuilding.title|escape}</a>
										</li>
										{/foreach}
									</ul>
								</div>
							</div>
						</div>
					</div>
				{else}
					<a class="btn btn-outline-default rounded-pill{if $deviceType eq 'phone'} btn-sm{/if} js__dropdown-stock" href="{$clsISO->getLink('stock')}">Bảng hàng</a>
				{/if}
			</div>
			{if !empty($is_model)}
				<button class="btn btn-info text-white pulse position-relative rounded-pill{if $deviceType eq 'phone'} btn-sm px-1{/if}" type="button" onClick="$Core.project.open_model(this,event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" _type="is_model">Nhà mẫu</button>
			{/if}
			{if !empty($is_handoverSpecs)}
				<button class="btn btn-success text-white pulse position-relative rounded-pill{if $deviceType eq 'phone'} btn-sm px-1{/if}" type="button" onClick="$Core.project.open_model(this,event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" _type="is_handoverSpecs">TC Bàn giao</button>
			{/if}
			{if $show eq 'project' || $stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
				{if !empty($oneProject.is_menu)}
					<span class="sh-badge sh-badge--open">Mở bán</span>
				{else}
					<span class="sh-badge sh-badge--closed">Chưa mở bán</span>
				{/if}
			{else}
				{if !empty($more_information.on_sale)}
					<span class="sh-badge sh-badge--open">Mở bán</span>
				{/if}
			{/if}
		</div>
	</div>
	<div class="sh-tabs">
		{if $show eq 'building' || (!empty($block_is_project)) || (($show eq 'project' || $show eq 'map' || $act eq 'stock' || $act eq 'layout') && !empty($is_lowfloor))}
			{if $clsISO->checkItemInArray($smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE,$arr_block_type) && ($show eq 'project' || ($show eq 'map' && empty($building_id)) || $act eq 'stock' || $act eq 'layout')}
				<a data-toggle="ripple" href="javascript:void()" class="sh-tab{if $act eq 'stock' || $act eq 'layout'} active{/if}" onClick="$Core.project.chooseListStock(this,event)">
					<i class="bx bx-table"></i>
					<span class="txt_option">Bảng hàng</span>
				</a>
			{else}
				<a data-toggle="ripple" href="{$link_stock}" class="sh-tab{if $act eq 'stock' || $act eq 'layout'} active{/if}">
					<i class="bx bx-table"></i>
					<span class="txt_option">Bảng hàng</span>
				</a>
			{/if}
		{elseif $act eq 'stock' || $act eq 'layout'}
			{* du an chi cao tang: van hien tab Bang hang active khi dang o man bang hang *}
			<a data-toggle="ripple" href="{$link_stock}" class="sh-tab active">
				<i class="bx bx-table"></i>
				<span class="txt_option">Bảng hàng</span>
			</a>
		{/if}
		{foreach from=$list_category_docs item=_oCatDocs key=key name=i}
		<a data-toggle="ripple" class="sh-tab{if $root_id eq $_oCatDocs.property_id && $show ne 'map'} active{/if}" href="{$clsProject->getLinkInfo($project_id, $block_id, $building_id, $_oCatDocs.property_id)}">
			<i class="{$_oCatDocs.image}"></i>
			<span class="txt_option">{$_oCatDocs.title|escape}</span>
		</a>
		{/foreach}
		<a data-toggle="ripple" href="{$clsProject->getLinkInfo($project_id, $block_id, $building_id, '_utility')}" class="sh-tab{if $cat_id eq $smarty.const._PROJECT_DOCS_UTILITY_CATID} active{/if}">
			<i class="bx bx-category"></i>
			<span class="txt_option">Tiện ích</span>
		</a>
		{if $deviceType ne 'phone' && !empty($vr_link)}
		<a data-toggle="ripple" href="{$vr_link}" data-fancybox data-caption="{$vr_source}" data-type="iframe" data-preload="true" class="sh-tab">
			<i class='bx bx-analyse'></i>
			<span class="txt_option">VR360</span>
		</a>
		{/if}
		{if !empty($map_la) && !empty($map_lo)}
			<a data-toggle="ripple" href="{$clsProject->getLinkInfo($project_id, $block_id, $building_id, '_map')}" class="sh-tab{if $show eq 'map'} active{/if}">
				<i class="bx bx-map"></i>
				<span class="txt_option">Bản đồ</span>
			</a>
		{/if}
	</div>
</section>
{if $clsISO->checkItemInArray($smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE,$arr_block_type) && ($show eq 'project' || $show eq 'map' || $act eq 'stock' || $act eq 'layout')}
	<div class="modal fade modal_stock_picker" id="{$clsISO->getUniqid()}" tabindex="-1" aria-modal="true" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
		<div class="modal-dialog modal-sm modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title text-center text-dark">Bảng hàng</h3>
					<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="d-flex flex-column justify-content-center">
						<div class="d-flex flex-column gap-2">
							<a class="d-flex align-items-center justify-content-between mb-2 btn fs-5 btn-lg btn-outline-default" href="{$clsISO->getLink('tool')}?project_id={$project_id}">
								<div class="d-flex align-items-center gap-1">
									<img src="{$URL_IMAGES}/icons/icon_building.png" width="28" height="28">
									<span class="text-dark">Cao tầng</span>
								</div>
								<span class="icon fs-4"><i class='bx bx-chevron-right'></i></span>
							</a>
							<a class="d-flex align-items-center justify-content-between mb-2 btn fs-5 btn-lg btn-outline-default{if $act eq 'stock'} active{/if}" href="{$clsProject->getLink($project_id)}">
								<div class="d-flex align-items-center gap-1">
									<img src="{$URL_IMAGES}/icons/icon_house.png" width="28" height="28">
									<span class="text-dark">Thấp tầng</span>
								</div>
								<span class="icon fs-4"><i class='bx bx-chevron-right'></i></span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{/if}
