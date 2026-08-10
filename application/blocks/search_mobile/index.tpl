{assign var=gId value=$clsISO->getUniqid()}
<div class="position-relative d-flex align-items-center box_search_header">						
	<button data-toggle="ripple" type="button" class="btn btn-icon icon_menu btn_search_header rounded-pill {if $mod eq 'home' && $sub eq 'default' && $act eq 'default'}text-white{/if}" onclick="$Core.search_top.show_search(this,event)" toId="{$gId}" ><i class='bx bx-search {if !($mod eq 'home' && $sub eq "default" && $act eq "default")}fs-24{/if}'></i></button>
	<div class="search_header search_header_home_mb input-group flex-nowrap rounded-pill px-2 py-1 " id="{$gId}">
		<button type="button" data-toggle="ripple" class="btn_dropdown btn-sm fs-12 px-1 d-flex align-items-center btn dropdown-toggle justify-content-between text-black fw-semibold" data-bs-auto-close="outside" data-text_def="Loại căn" data-bs-toggle="dropdown" aria-expanded="true"><div class="material-ink animate" ></div>
			<span class="select-text-content fs-12 select-check-bedroom ng-binding" id="label_type_{$gId}" >{if $get_stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}Cao tầng{elseif $get_stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}Thấp tầng{else}Thông tin{/if}</span>
		</button>
		<ul class="dropdown-menu position-absolute" style="">
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_highfloor">
					<input gid="type_all" type="radio" name="stock_type_{$gId}" class="form-check-input" id="chk_type_highfloor" title="Cao tầng" value="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}" {if $get_stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE} checked{/if} onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_{$gId}" >
					<span class="form-check-label ml-1">Cao tầng</span>
				</label>
			</li>
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_lowfloor">
					<input gid="type_all" type="radio" name="stock_type_{$gId}" class="form-check-input" id="chk_type_lowfloor" title="Thấp tầng" value="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}"{if $get_stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE} checked{/if} onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_{$gId}"  >
					<span class="form-check-label ml-1">Thấp tầng</span>
				</label>
			</li>
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_info">
					<input gid="type_all" type="radio" name="stock_type_{$gId}" class="form-check-input" id="chk_type_info" title="Thông tin" value="1" onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_{$gId}" {if $get_stock_type eq 1} checked{/if}  >
					<span class="form-check-label ml-1">Thông tin</span>
				</label>
			</li>
		</ul>
		<div class="d-flex align-items-center pl-2 border-left ml-2 flex-fill gap-1">
			<input type="text" class="form-control form-control-sm border-0 p-0" value="{$keyword}" placeholder="Tìm bất cứ thứ gì..." onkeyup="$Core.helper.search_all(this, event)" onfocus="$Core.helper.search_suggest_focus(this, event)"  onblur="$Core.helper.search_suggest_blur(this, event)">
			<i class="bx bx-search fs-20 text-main"></i>
		</div>
		<div class="search_suggest rounded-3" style="display: none;">
			<div class="ss-empty">Đang tải gợi ý...</div>
		</div>
	</div>
</div>
<!--<div class="position-relative d-flex align-items-center box_search_header">						
	<button data-toggle="ripple" type="button" class="btn btn-icon icon_menu btn_search_header rounded-pill text-white" onclick="$Core.search_top.show_search(this,event)" toId="{$gId}" ><i class='bx bx-search'></i></button>
	<div class="search_header search_header_home_mb input-group flex-nowrap rounded-pill px-2 py-1 " id="{$gId}">
		<button type="button" data-toggle="ripple" class="btn_dropdown btn-sm fs-12 px-1 d-flex align-items-center btn dropdown-toggle justify-content-between text-black fw-semibold" data-bs-auto-close="outside" data-text_def="Loại căn" data-bs-toggle="dropdown" aria-expanded="true"><div class="material-ink animate" ></div>
			<span class="select-text-content fs-12 select-check-bedroom ng-binding" id="label_type_{$gId}" >{if $get_stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}Cao tầng{elseif $get_stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}Thấp tầng{else}Thông tin{/if}</span>
		</button>
		<ul class="dropdown-menu" style="">
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_highfloor">
					<input gid="type_all" type="radio" name="stock_type_{$gId}" class="form-check-input" id="chk_type_highfloor" title="Cao tầng" value="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}" {if $get_stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE} checked{/if} onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_{$gId}" >
					<span class="form-check-label ml-1">Cao tầng</span>
				</label>
			</li>
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_lowfloor">
					<input gid="type_all" type="radio" name="stock_type_{$gId}" class="form-check-input" id="chk_type_lowfloor" title="Thấp tầng" value="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}"{if $get_stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE} checked{/if} onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_{$gId}"  >
					<span class="form-check-label ml-1">Thấp tầng</span>
				</label>
			</li>
			<li class="dropdown-item px-3">
				<label class="form-check mb-0 cursor-pointer fs-14" for="chk_type_info">
					<input gid="type_all" type="radio" name="stock_type_{$gId}" class="form-check-input" id="chk_type_info" title="Thông tin" value="1" onChange="$Core.helper.handle_stock_type(this, event);$Core.search_top.change_stock_type(this,event)" toId="label_type_{$gId}" {if $get_stock_type eq 1} checked{/if}  >
					<span class="form-check-label ml-1">Thông tin</span>
				</label>
			</li>
		</ul>
		<div class="d-flex align-items-center pl-2 border-left ml-2 flex-fill gap-1">
			<input type="text" class="form-control form-control-sm border-0 p-0" value="{$keyword}" placeholder="Tìm bất cứ thứ gì..." onkeyup="$Core.helper.search_all(this, event)" onfocus="$Core.helper.search_suggest_focus(this, event)"  onblur="$Core.helper.search_suggest_blur(this, event)">
			<i class="bx bx-search fs-20 text-main"></i>
		</div>
		<div class="search_suggest rounded-3" style="top: 93px; display: none;">
			<div class="ss-empty">Đang tải gợi ý...</div>
		</div>
	</div>
</div>-->