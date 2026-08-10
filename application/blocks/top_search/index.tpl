<div class="navbar-nav align-items-center">
	{if $deviceType eq 'phone'}
		<div class="search_header border search_header_mb input-group flex-nowrap rounded-2 px-2 py-1" id="{$gId}">
			<button type="button" data-toggle="ripple" class="btn_dropdown btn-sm btn-icon fs-12 hide-arrow btn dropdown-toggle text-black fw-semibold" data-bs-auto-close="outside" data-text_def="Loại căn" data-bs-toggle="dropdown" aria-expanded="true">
				<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
				fill="currentColor" viewBox="0 0 24 24" >
				<path d="m12 15.41 5.71-5.7-1.42-1.42-4.29 4.3-4.29-4.3-1.42 1.42z"></path>
				</svg>
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
			<div class="d-flex align-items-center pl-1 border-left ml-1 flex-fill gap-1">
				<input type="text" class="form-control form-control-sm border-0 p-0" value="{$keyword}" placeholder="Tìm bất cứ thứ gì..." onkeyup="$Core.helper.search_all(this, event)" onfocus="$Core.helper.search_suggest_focus(this, event)"  onblur="$Core.helper.search_suggest_blur(this, event)">
			</div>
			<div class="search_suggest rounded-3" style="display: none;">
				<div class="ss-empty">Đang tải gợi ý...</div>
			</div>
		</div>
	{else}
		<div class="d-flex align-items-center nav-item position-relative">
			{if $deviceType eq 'phone'}
				<div class="btn-froup w-px-80">
					<select name="stock_type" class="form-select form-control no-focus form-option-sm" onchange="$Core.helper.handle_stock_type(this, event)">
						<option value="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}"{if $get_stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE} selected{/if}>Cao tầng</option>
						<option value="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}"{if $get_stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE} selected{/if}>Thấp tầng</option>
						<option value="1"{if $get_stock_type eq '1'} selected{/if}>Thông tin</option>
					</select>
				</div>
			{else}
				<div class="btn-group text-nowrap " role="group" aria-label="Hiển thị" {$deviceType}>
					{assign var = gId value = $clsISO->getUniqid()}
					<input type="radio" class="btn-check" name="stock_type" onchange="$Core.helper.handle_stock_type(this, event)" id="{$gId}" value="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}"{if $get_stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE} checked="checked"{/if}>
					<label data-toggle="ripple" title="Cao tầng" class="btn btn-sm js__search-stock-type btn-outline-default{if $get_stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE} active{else}{/if}" for="{$gId}" title="Cao tầng">{if $deviceType eq 'phone'}CT{else}Cao tầng{/if}</label>
					{assign var = gId value = $clsISO->getUniqid()}
					<input type="radio" class="btn-check" name="stock_type" id="{$gId}" onchange="$Core.helper.handle_stock_type(this, event)" value="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}"{if $get_stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE} checked="checked"{/if}>
					<label data-toggle="ripple" title="Thấp tầng" class="btn btn-sm js__search-stock-type btn-outline-default{if $get_stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE} active{/if}" for="{$gId}" title="Thấp tầng">{if $deviceType eq 'phone'}TT{else}Thấp tầng{/if}</label>
					{assign var = gId value = $clsISO->getUniqid()}
					<input type="radio" class="btn-check" name="stock_type" id="{$gId}" onchange="$Core.helper.handle_stock_type(this, event)" value="1"{if $get_stock_type eq '1'} checked="checked"{/if}>
					<label data-toggle="ripple" title="Thông tin" class="btn btn-sm js__search-stock-type btn-outline-default{if $get_stock_type eq '1'}  active{/if}" for="{$gId}" title="Thông tin">Thông tin</label>
				</div>
			{/if}
			<div class="search_header position-relative">
				<input type="text" class="form-control border-0 js__top-search-input top_select_all shadow-none" 
				placeholder="Tìm bất cứ thứ gì..." onkeyup="$Core.helper.search_all(this, event)" value="{$keyword}" onfocus="$Core.helper.search_suggest_focus(this, event)"  onblur="$Core.helper.search_suggest_blur(this, event)" />
				<div class="search_suggest" style="display:none">
					<div class="ss-empty">Đang tải gợi ý...</div>
				</div>
			</div>		
		</div>
	{/if}
</div>
{if $deviceType eq 'phone'}
	{literal}
	<script type="text/javascript">
		$(function(){
			$_document.on('keydown', '.js__top-search-input', $Core.util.delay(function(){
				var _this = $(this), _keyword = _this.val();
				if(!$Core.util.isEmpty(_keyword)){
					$.post(PCMS_URL+'/index.php?mod=ajax&sub=helper&act=search_stock', {
						'ms_code' : _keyword
					}, function(respJson){
						if(respJson.html.indexOf('not_found')>=0){} else {
							_this.val("");
							$Core.popup.open('auto','auto',respJson.html,respJson.uid);
						}
					},'json');
				}
			},1000));
		});
	</script>
	{/literal}
{/if}
