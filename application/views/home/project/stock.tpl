<script type="text/javascript">
	var project_id = '{$project_id}',
		block_type = '{$block_type}',
		block_id = '{$block_id}',
		is_project_block = '{$is_project_block}',
		_BLOCK_TYPE_LOWFLOOR_SALE = '{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}',
		_BLOCK_TYPE_HIGHLEVEL_SALE = '{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}';
</script>
<link rel="stylesheet" href="{$URL_CSS}/leaflet/leaflet.min.css?v={$upd_version}"/>
<link rel="stylesheet" href="{$URL_CSS}/leaflet/leaflet.draw.css?v={$upd_version}"/>
<link rel="stylesheet" href="{$URL_CSS}/leaflet/Control.MiniMap.css?v={$upd_version}" />
<script src="{$URL_JS}/leaflet/store.min.js?v={$upd_version}"></script>
<script src="{$URL_JS}/connectingLine/jquery.connectingLine.js?v={$upd_version}"></script>
<!-- Zoom bảng hàng (zoom/cuộn lăn/pinch/kéo/fullscreen/chụp ảnh) -->
<link rel="stylesheet" href="{$URL_CSS}/stock-zoom.css?v={$upd_version}"/>
<script src="{$URL_JS}/html2canvas.min.js?v={$upd_version}"></script>
<script src="{$URL_JS}/stock-zoom.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<nav aria-label="breadcrumb" class=" d-flex justify-content-between align-items-center gap-2">
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item">
				<a href="{$PCMS_URL}/bang-hang/">Bảng hàng</a>
			</li>
			<li class="breadcrumb-item">
				<a href="{$clsProject->getLinkDetail($project_id,0,0,$oneProject)}">{$clsProject->getCode($project_id,$oneProject)}</a>
			</li>
			{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
			<li class="breadcrumb-item">
				<a href="{$clsProject->getLinkDetail($project_id,$oneBuilding.for_id,0,$oneProject)}">{$clsProperty->getTitle($oneBuilding.for_id)}</a>
			</li>			
			<li class="breadcrumb-item active">{$oneBuilding.title}</li>
			{elseif !empty($block_is_project)}
				<li class="breadcrumb-item active">{$oneBlock.title}</li>
			{/if}
		</ol>
		{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE &&  $deviceType eq 'phone'}
			<div class="d-flex justify-content-end">
				<div class="btn-group mb-2 justify-content-center gap-1">
					<button class="btn btn-outline-default btn-icon rounded-pill btn_bg_sold light btn-sm {if $stock_bg_sold eq 'light'}active{/if}" onClick="$Core.stock.setBgSold(this,event)" data-type="light" style="background: {$bg_sold} !important"  ></button>
					<button class="btn btn-outline-default btn-icon rounded-pill btn_bg_sold dark btn-sm {if $stock_bg_sold eq 'dark'}active{/if}" onClick="$Core.stock.setBgSold(this,event)" data-type="dark"></button>
				</div>
			</div>
		{/if}
	</nav>
	{$core->getBlock("banner_stock", ['oneBuilding' => $oneBuilding])}
	<div class="clearfix"></div>
	<div class="card">
		<div class="card-body">
			{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
				{if $deviceType eq 'phone'}
				<div class="d-flex justify-content-between">
					<div class="input-group justify-content-center mb-2 no-shadow flex-fill">
						{if !empty($vr_link)}
						<a data-toggle="ripple" class="btn text-white{if $deviceType eq 'phone'} btn-sm d-flex align-items-center justify-content-center px-1 flex-fill fs-10{/if}" title="Xem VR360" href="{$vr_link}" data-fancybox data-type="iframe" style="background: #5a5a5a"><img src="{$URL_IMAGES}/vr360.png" class="w-px-20" style="filter: invert(1);">{if $deviceType ne 'phone'}VR360{/if}</a>{/if}
						{if $is_map eq '1'}
						<a data-toggle="ripple" href="/project/p{$project_id}/b{$building_id}/map.html" title="Xem dạng layout" class="text-white btn{if $deviceType eq 'phone'} btn-sm px-1 flex-fill fs-10{/if}" style="background: #583400"><i class='bx bx-map-alt {if $deviceType eq "phone"}fs-14{/if}'></i> Map</a>{/if}
						{if $is_map_dq eq '1'}
						<a data-toggle="ripple" href="javascript:void(0)" class="btn bg-main text-white{if $deviceType eq 'phone'} btn-sm px-1 flex-fill fs-10{/if}" title="Xem dạng layout banner" onClick="$Core.project.open_map(this, event)" block_id="{$block_id}" building_id="{$building_id}"><img src="{$clsConfiguration->getValue('LogoWhite')}" width="{if $deviceType eq 'phone'}14px{else}18px{/if}" alt="{$header_configs.CompanyName}" /> Map ĐQ</a>{/if}
						<!-- <a data-toggle="ripple" href="javascript:void(0);" class="btn text-white{if $deviceType eq 'phone'} btn-sm px-1 flex-fill{/if}" title="Chọn tòa" onClick="$Core.project.toggle_dropdown(this, event)" style="background: #ffab00"><i class='bx bx-buildings {if $deviceType eq "phone"}fs-14{/if}'></i> Chọn tòa</a> -->
						<a data-toggle="ripple" href="javascript:void(0);" building_id="{$building_id}" class="btn text-white{if $deviceType eq 'phone'} btn-sm px-1 flex-fill fs-10{/if}" onClick="$Core.stock.toggle_floor_empty(this, event)" title="Ẩn tầng trống" style="background:#136b32"><i class='fa fa-eye'></i> Ẩn tầng trống</a> <!-- data-bind="click" -->
						<a data-toggle="ripple" href="javascript:void(0);" building_id="{$building_id}" class="btn text-white{if $deviceType eq 'phone'} btn-sm px-1 flex-fill fs-10{/if}" onClick="$Core.stock.hide_stock_cross(this, event)" title="Ẩn/hiện quỹ chéo" style="background:#23008d"> {if $is_hide_stock_cross eq '1'}<i class='fa fa-eye-slash'></i> Hiện{else}<i class='fa fa-eye'></i> Ẩn{/if} {if $deviceType eq 'phone'}Q/Chéo{else}quỹ chéo{/if}</a>
						{if $deviceType eq 'phone'}
							<div class="dropdown dropdown_price">
								<button data-toggle="ripple" class="btn bg-warning text-white dropdown-toggle btn-sm px-1 no-radius-left flex-fill fs-10" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" data-popper-placement="top-start" aria-haspopup="true" aria-expanded="false">Giá VAT</button>
								<ul class="dropdown-menu" style="">
									<li style="background: #fff8ff">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom active" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_vat" data-text="Giá VAT" 
										   style="background: #05009c;color: #FFF !important;"
										><span>Giá VAT</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #fff8ff">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_m2" data-text="VAT/m2" 
										   style="background: #05009c;color: #FFF !important;"
										><span>Giá VAT/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
									<li style="background: #edfff7">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_early" data-text="Giá TTS" 
										   style="background: #650202;color: #FFF !important;"
									   ><span>Giá TTS</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #edfff7">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_tts_m2" data-text="TTS/m2" 
										   style="background: #650202;color: #FFF !important;"
									   ><span>Giá TTS/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
									<li style="background: #d2fff7">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_progress" data-text="Giá TTTĐ" 
										   style="background: #4c0057;color: #FFF !important;"
									   ><span>Giá TTTĐ</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #d2fff7">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_tttd_m2" data-text="TTTĐ/m2" 
										   style="background: #4c0057;color: #FFF !important;"
									   ><span>Giá TTTĐ/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
									<li style="background: #e3ebff">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_bank" data-text="Giá Vay" 
										   style="background: #8e7803;color: #FFF !important;"
									   ><span>Giá Vay</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #e3ebff">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_vay_m2" data-text="Vay /m2" 
										   style="background: #8e7803;color: #FFF !important;"
									   ><span>Giá Vay/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
									<li style="background: #fde2d2">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_full" data-text="3PA" 
										   style="background: #00624e;color: #FFF !important;"
									   ><span>Giá 3PA thanh toán</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #fde2d2">
										<a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_full_m2" data-text="3PA/m2" 
										   style="background: #00624e;color: #FFF !important;"
									   ><span>Giá 3PA/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
								</ul>
							</div>
						{/if}
					</div>
				</div>
				{else}
				<div class="sh-toolbar mb-2">
					<a data-toggle="ripple" href="javascript:void(0);" building_id="{$building_id}" class="sh-tbtn" onClick="$Core.stock.toggle_floor_empty(this, event)" title="Ẩn tầng trống"><i class='fa fa-eye'></i> Ẩn tầng trống</a>
					<a data-toggle="ripple" href="javascript:void(0);" building_id="{$building_id}" class="sh-tbtn" onClick="$Core.stock.hide_stock_cross(this, event)" title="Ẩn/hiện quỹ chéo">{if $is_hide_stock_cross eq '1'}<i class='fa fa-eye-slash'></i> Hiện{else}<i class='fa fa-eye'></i> Ẩn{/if} quỹ chéo</a>
					{if $is_map eq '1'}
						<a data-toggle="ripple" href="/project/p{$project_id}/b{$building_id}/map.html" title="Xem dạng layout" class="sh-tbtn"><i class='bx bx-map-alt'></i> Map</a>
					{/if}
					{if $is_map_dq eq '1'}
						<a data-toggle="ripple" href="javascript:void(0)" class="sh-tbtn" title="Xem dạng layout banner" onClick="$Core.project.open_map(this, event)" block_id="{$block_id}" building_id="{$building_id}"><img src="{$clsConfiguration->getValue('LogoWhite')}" width="18px" alt="{$header_configs.CompanyName}" /> Map ĐQ</a>
					{/if}
					{* phu luc minh hoa: dung sau cum button, khong xen ke *}
					{if !empty($list_status)}
						<div class="sh-legend">
							{foreach name=shlg from=$list_status item=_oProp}
								{if $clsISO->checkItemInArray($_oProp.property_id,$arr_status_id_show)}
									<span class="sh-legend__item">
										<span style="background:{$_oProp.bgcolor}" class="d-block border rounded-pill w-px-15 h-px-15"></span>
										<span>{$_oProp.title|escape}</span>
									</span>
								{/if}
							{/foreach}
							<span class="sh-legend__item">
								<span class="status_fund_type" title="Thứ cấp"></span>
								<span>Quỹ thứ cấp</span>
							</span>
						</div>
					{/if}
					<span class="sh-toolbar__spacer"></span>
					<span class="tgl-02__scene" title="Đổi nền căn đã bán (sáng / tối)">
						<input type="checkbox" class="tgl-02__sw" role="switch"{if $stock_bg_sold eq 'dark'} checked="checked"{/if} onChange="$(this).data('type', this.checked ? 'dark' : 'light');$Core.stock.setBgSold(this, event)">
						<span class="tgl-02__stars" aria-hidden="true"></span>
						<span class="tgl-02__cloud" aria-hidden="true"></span>
					</span>
					<div class="dropdown dropdown_price">
							<button data-toggle="ripple" class="btn bg-warning text-white dropdown-toggle w-px-140" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" data-popper-placement="top-start" aria-haspopup="true" aria-expanded="false">Giá full VAT</button>
							<ul class="dropdown-menu" style="">
								<li style="background: #fff8ff">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom active" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_vat" data-text="Giá VAT" 
									   style="background: #05009c;color: #FFF !important;"
								   ><span>Giá VAT</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #fff8ff">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_m2" data-text="Giá VAT/m2" 
									   style="background: #05009c;color: #FFF !important;"
								  ><span>Giá VAT/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
								<li style="background: #edfff7">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_early" data-text="Giá TTS" 
									   style="background: #650202;color: #FFF !important;"
								   ><span>Giá TTS</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #edfff7">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_tts_m2" data-text="Giá TTS/m2" 
									   style="background: #650202;color: #FFF !important;"
								   ><span>Giá TTS/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
								<li style="background: #d2fff7">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_progress" data-text="Giá TTTĐ" 
									   style="background: #4c0057;color: #FFF !important;"
								   ><span>Giá TTTĐ</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #d2fff7">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_tttd_m2" data-text="Giá TTTĐ/m2" 
									   style="background: #4c0057;color: #FFF !important;"
								   ><span>Giá TTTĐ/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
								<li style="background: #e3ebff">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_bank" data-text="Giá Vay" 
									   style="background: #8e7803;color: #FFF !important;"
								   ><span>Giá Vay</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #e3ebff">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_vay_m2" data-text="Giá Vay /m2" 
									   style="background: #8e7803;color: #FFF !important;"
								   ><span>Giá Vay/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
								<li style="background: #fde2d2">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_full" data-text="Giá 3PA" 
									   style="background: #00624e;color: #FFF !important;"
								   ><span>Giá 3PA thanh toán</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #fde2d2">
									<a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_full_m2" data-text="Giá 3PA /m2" 
									   style="background: #00624e;color: #FFF !important;"
								   ><span>Giá 3PA/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
						  	</ul>
					</div>
				</div>
				{/if}
				<div id="tblStock" class="freeze-table text-nowrap table-container" data-building="{$oneBuilding.property_code}">
					<table class="table fixedTable table-stock table-bordered dragable installed nohover" cellpadding="0" cellspacing="0" >
						<thead><tr>
							<th class="align-center bg-white text-center"  colspan="2" style="min-width:60px">
								<strong class="fs-18">{$oneBuilding.title_vn} - {$oneBuilding.property_code}</strong>
							</th>
							{* desktop: phu luc da chuyen len toolbar -> bo th nay, th note phia sau
							   tu phu them 7 cot vi total_rowspan khong bi tru; phone giu nguyen *}
							{if $deviceType eq 'phone'}
							{assign var = total_rowspan value = $total_rowspan-7}
							<th class="align-center text-center" colspan="7" style="height:30px">
								{if !empty($list_status)}
									<div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
										{foreach name=i from=$list_status item = _oProp}
											{if $clsISO->checkItemInArray($_oProp.property_id,$arr_status_id_show)}
												{if $smarty.const._STOCK_STATUS_GENERAL_ID eq $_oProp.property_id}
													{assign var = text_color value = 'danger'}
												{else}
													{assign var = text_color value = 'white'}
												{/if}
												<div class="d-flex align-items-center gap-1 text-center" >
													<span style="background:{$_oProp.bgcolor}" class="d-block border rounded-pill w-px-15 h-px-15" ></span>
													<span class="fs-11">{$_oProp.title}</span>
												</div>
											{/if}
										{/foreach}
										<div class="d-flex align-items-center gap-1 text-center" >
											<span class="status_fund_type" title="Thứ cấp"></span>
											<span class="fs-11">Quỹ thứ cấp</span>
										</div>
									</div>
								{/if}
							</th>
							{/if}
							<th class="text-center" colspan="{$total_rowspan-1}">
								{if !empty($more_information.title_ts)}
									{$more_information.title_ts}
								{else}
									Note: Giá đã bao gồm VAT & KPBT
								{/if}
							</th>
						</tr> 
						{if !empty($group_cols_template)}
							<tr>
								<th class=""></th>
								{foreach name=k from=$group_cols_template key=_symbol item = _col}
									<th class="text-center fs-16" colspan="{$_col}" style="height:30px">{$_symbol}</th>
								{/foreach}
							</tr>
						{/if}
						<tr>
							<th class="cell" width="60px">T/C</th>
							{foreach name=k from=$arr_cols key=_code item = oneCode}
								{assign var = bedroom_id value = $oneCode.bedroom_id}								
								{assign var = layout value = $oneCode.layout}
								{if !empty($layout)}
									<th width="60px" class="text-center js__stock-cell-focus text-white cursor-pointer" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$bedroom_id}" data-fancybox="layout_{$_code}" data-fancybox="488" data-src="{$clsISO->getGoogleUrl($layout)}">{$oneCode.code}<i class='bx bx-layout fs-12' ></i></th>
								{else}
									<th width="60px" class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$bedroom_id}">{$oneCode.code}</th>
								{/if}
							{/foreach}
						</tr>
						<tr>
							<th class="cell">DT.Tim</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_Tim')}</th>
							{/foreach}
						</tr>
						<tr>
							<th class="cell">DT.TT</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_TT')}</th>
							{/foreach}
						</tr>
						<tr>
							<th class="cell">H.BC</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'home_direction_id')} </th>
							{/foreach}
						</tr>
						<tr>
							<th class="cell">L. Căn</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'bedroom_id')}</th>
							{/foreach}
						</tr>
						<tr>
							<th class="cell">View</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code,$arr_cols,'view_id',true)}</th>
							{/foreach}
						</tr></thead>
						<tbody>
							{foreach name=i from=$arr_floors item = _floor}
								{if !empty($floor_merge_arrs)}				
									{assign var=num_merge value=0}
									{foreach from = $floor_merge_arrs key = _sfloor item = _dfloor}		
										{assign var=cell_col_index value=$_dfloor.cell_col_index}
										{assign var=arr_cols value=$_dfloor.arr_cols}
										{if $_floor eq strval($_sfloor) && !$clsISO->checkItemInArray($_floor,$floor_service_arrs)}
											{if !empty($_dfloor.is_out_order)}
												{assign var=is_out_order value= 1}
												{assign var=_arr_stock value= $_dfloor.arr_stocks}
												{math equation="x-y" x=$arr_stocks|@count y=$_dfloor.arr_stocks|@count assign="num_merge"}
											{else}
												{assign var=is_out_order value= 0}
												{assign var=_arr_stock value= $arr_stocks}
											{/if}
											{if $_floor eq $clsISO->getFirstItemInArray($arr_floors)}
												<tr class="nohover">
													<td colspan="{$more_information.number_house+1}" class="h-px-20"></td>
												</tr>
											{/if}
											<tr class="nohover">							
												<td class="cell">
													<div class="d-flex justify-content-between">
														T/C <button class="btn btn-icon btn-xs text-white btn-default hide" toCls="{$_sfloor}" type="button" onClick="$Core.project.toggle_row_stock(this,event)"><i class='bx bx-chevron-down'></i></button>
													</div>
												</td>
												{assign var=check value=1}			
												{assign var=rowspan value=1}													
												{if $_dfloor.cell_merge_arrs|@count gt 1}
													{foreach name=k from=$_arr_stock item = _code key=key}
														{assign var=index_merge value=$smarty.foreach.k.iteration}
														{if !empty($_dfloor.cell_merge_arrs) && !$clsISO->checkItemInArray($_code,$_dfloor.arr_stocks) }
															{assign var=check value=0}
															{foreach from=$_dfloor.cell_merge_arrs item = _cell_merge}
																{if $clsISO->checkItemInArray($index_merge,$_cell_merge.arr_cols_merge) && $index_merge eq $_cell_merge.arr_cols_merge[0]}
																	<th colspan="{$_cell_merge.collspan}" rowspan="{$_cell_merge.rowspan}" class="td_hidden_row_{$_sfloor}" floor="{$_sfloor}" style="background:#ffe59a;border-width:0 !important"></th>
																	{assign var=rowspan value=$_cell_merge.rowspan}	
																{/if}
															{/foreach}
														{elseif $clsISO->checkItemInArray($_code,$_dfloor.arr_stocks)}
															{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$_dfloor.arr_cols,'bedroom_id')}	
															<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$_dfloor.arr_cols[$_code].code}</th>
														{/if}
													{/foreach}
												{else}
													{foreach name=k from=$_arr_stock item = _code key=key}														
														{if !empty($_dfloor.cell_merge_arrs) && !$clsISO->checkItemInArray($_code,$_dfloor.arr_stocks) && !empty($check)}
															{assign var=check value=0}
															{foreach from=$_dfloor.cell_merge_arrs item = _cell_merge}
																<th colspan="{$_cell_merge.collspan}" rowspan="{$_cell_merge.rowspan}" class="td_hidden_row_{$_sfloor}" floor="{$_sfloor}" style="background:#ffe59a;border-width:0 !important"></th>
																{assign var=rowspan value=$_cell_merge.rowspan}						
																{assign var=floor_flag value=$_sfloor}
															{/foreach}
														{elseif $clsISO->checkItemInArray($_code,$_dfloor.arr_stocks)}
															{assign var=rowspan value=$_dfloor.cell_merge_arrs[0].rowspan}	
															{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$_dfloor.arr_cols,'bedroom_id')}	
															<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$_dfloor.arr_cols[$_code].code}</th>
														{/if}
													{/foreach}
												{/if}
												{if !empty($num_merge)}
													<th colspan="{$num_merge}" rowspan="{$rowspan}" class="td_hidden_row_{$_sfloor}" floor="{$_sfloor}" style="background:#ffe59a;border-width:0 !important"></th>																		
													{assign var=floor_flag value=$_sfloor}
												{/if}
											</tr>
											<tr class="tr_hidden tr_hidden_{$_sfloor} nohover d-none">
												<td class="cell">DT.TT</td>
												{assign var=check_merge value=0}
												{assign var=col_plus value=1}
												{foreach name=k from=$_dfloor.arr_stocks item = _code key=key}	
													{if $clsISO->checkItemInArray($_code,$_dfloor.arr_stocks)}	
														{assign var=index value=$key}	
														{if ((!empty($cell_col_index[$key+1]) || (empty($cell_col_index[$key+1]) && !empty($check_merge)))) } 
															{if !empty($_dfloor.cell_merge_arrs)}
																{foreach from=$_dfloor.cell_merge_arrs item=cell}
																	{if ($cell.start_cell == $key+1)}
																		{assign var=col_plus value=$cell.collspan}
																	{/if}
																{/foreach}
															{/if}
															{math equation="x+y" x=$index y=$col_plus assign="index"}
															{assign var=check_merge value=1}
														{/if}	
														{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$_dfloor.arr_cols,'bedroom_id')}	
														<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white"  code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $_dfloor.arr_cols, 'DT_TT')}</th>
													{/if}
												{/foreach}
											</tr>
											<tr class="tr_hidden tr_hidden_{$_sfloor} nohover d-none">
												<td class="cell">H.BC</td>
												{assign var=check_merge value=0}
												{assign var=col_plus value=1}
												{foreach name=k from=$_dfloor.arr_stocks item = _code key=key}
													{if $clsISO->checkItemInArray($_code,$_dfloor.arr_stocks)}
														{assign var=index value=$key}
														{if ((!empty($cell_col_index[$key+1]) || (empty($cell_col_index[$key+1]) && !empty($check_merge)))) }
															{if !empty($_dfloor.cell_merge_arrs)}
																{foreach from=$_dfloor.cell_merge_arrs item=cell}
																	{if ($cell.start_cell == $key+1)}
																		{assign var=col_plus value=$cell.collspan}
																	{/if}
																{/foreach}
															{/if}
															{math equation="x+y" x=$index y=$col_plus assign="index"}
															{assign var=check_merge value=1}
														{/if}
														{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$_dfloor.arr_cols,'bedroom_id')}	
														<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $_dfloor.arr_cols, 'home_direction_id')}</th>
													{/if}
												{/foreach}
											</tr>
											<tr class="tr_hidden tr_hidden_{$_sfloor} nohover d-none">
												<td class="cell">L. Căn</td>
												{assign var=check_merge value=0}
												{assign var=col_plus value=1}
												{foreach name=k from=$_dfloor.arr_stocks item = _code key=key}
													{if $clsISO->checkItemInArray($_code,$_dfloor.arr_stocks)}
														{assign var=index value=$key}
														{if ((!empty($cell_col_index[$key+1]) || (empty($cell_col_index[$key+1]) && !empty($check_merge)))) }
															{if !empty($_dfloor.cell_merge_arrs)}
																{foreach from=$_dfloor.cell_merge_arrs item=cell}
																	{if ($cell.start_cell == $key+1)}
																		{assign var=col_plus value=$cell.collspan}
																	{/if}
																{/foreach}
															{/if}
															{math equation="x+y" x=$index y=$col_plus assign="index"}
															{assign var=check_merge value=1}
														{/if}
														{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$_dfloor.arr_cols,'bedroom_id')}	
														<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $_dfloor.arr_cols, 'bedroom_id')}</th>
													{/if}
												{/foreach}
											</tr>
											{break}
										{/if}
									{/foreach}
								{/if}
								{if $clsISO->checkItemInArray($_floor,$floor_service_arrs)}
									<tr class="nohover">
										<td style="background:#ffe59a" class="text-center">{$_floor}</td>
										<td class="text-center" colspan="{$more_information.number_house}">Tầng dịch vụ hoặc lánh nạn</td>
									</tr>
								{elseif !empty($floor_merge_service_arrs[$_floor])}
									{assign var=_dfloor value=$floor_merge_service_arrs[$_floor]}	
									{assign var=arr_merge_cols value=$_dfloor.arr_merge_cols}
									<tr class="nohover">
										<td style="background:#ffe59a" class="text-center py-3" >{$_dfloor.name_floor}</td>
										{foreach from=$arr_merge_cols item=_oItem}
											{assign var = _stock_id value = $_oItem.stock_id}
											{assign var = _agency_id value = $_oItem.agency_id}
											{assign var = _status_id value = $_oItem.status_id}											
											{assign var = _total_price_vat value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'total_price_vat')}{assign var = _total_price_early value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_early')}
											{assign var = _total_price_progress value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_progress')}
											{assign var = _total_price_bank value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_bank')}
											{assign var = _price_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_m2')}
											{assign var = _price_tts_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_tts_m2')}
											{assign var = _price_tttd_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_tttd_m2')}
											{assign var = _price_vay_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_vay_m2')}
											{assign var = _is_dq value = $_oItem.is_dq}
											{assign var = index value = $key}
											{if $_oItem.stock_id gt '0'}
												{if (!empty($_oItem.agency_id) && ($list_agency_cached_VIN[$_oItem.agency_id] eq '1' || $list_agency_cached_MAS[$_oItem.agency_id] eq '1')) || ($_status_id eq $smarty.const._STOCK_STATUS_HIDDEN_ID && !$clsISO->checkPermission('view_stock_hidden'))}
													{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
													<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{$_oItem.code}" floor="{$_floor}" class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
												{else}
													{if !empty($_status_id) && $_status_id gt '0'}
														{if $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}
															<!-- Non -->
														{else}
															{if $is_hide_stock_cross eq '1' && $_agency_id ne $smarty.const._AGENCY_FH_ID}
																{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
															{/if}
															{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}
																<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{$_code}" floor="{$_floor}" 
																class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
															{else}
																{assign var = _textcolor value = $status_textcolor_arrs.$_status_id}
																<td floor="{$_floor}" code="{$_oItem.code}" class="stock_cell stock_cell_{$_oItem.stock_id} text-center js__stock-cell-focus cursor-pointer position-relative " style="background:{$status_bgcolor_arrs.$_status_id}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID || $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}{else} {if $deviceType eq 'phone'}onClick="$Core.helper.open_stock('{$_oItem.stock_id}');" {else}data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto"{/if} data-width="350"{/if}><a href="javascript:void(0)" style="color:{$_textcolor}" class="stock_show {$_is_dq}" data-total_price_vat="{$_total_price_vat}" data-total_price_early="{$_total_price_early}" data-total_price_progress="{$_total_price_progress}" data-total_price_bank="{$_total_price_bank}" data-price_m2="{$_price_m2}" data-price_tts_m2="{$_price_tts_m2}" data-price_tttd_m2="{$_price_tttd_m2}" data-price_vay_m2="{$_price_vay_m2}" >
																	{if !empty($_total_price_vat)}	
																		{if $clsStock->checkShow($_oItem.show_website, 'MOC')}
																			<span class="price_show">{$_total_price_vat}</span>
																		{else}
																			<span class="text-decoration-line-through price_show">{$_total_price_vat}</span>
																		{/if}
																		{if $clsISO->checkItemInArray($_oItem.stock_id,$arr_stock_lock)}
																			<span class="lock_stock"><i class='bx bx-lock-alt' ></i></span>
																		{/if}
																	{else}
																		<!-- Empty -->
																	{/if}
																</a></td>
															{/if}
														{/if}
													{else}
														{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
														{assign var = _bgcolor value = $status_bgcolor_arrs.$_status_id}
														<td floor="{$_floor}" code="{$_oItem.code}" style="cursor:pointer; background:{$_bgcolor}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}{else} data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-trigger="click" data-width="350"{/if} class="text-center js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
													{/if}
												{/if}
											{/if}
										{/foreach}
									</tr>
								{else}
								<tr class="{$floor_flag}">
									{assign var=layout_floor value=$clsProperty->getLayout($building_id,$_floor,"",'floor',$oneBuilding)}
									{if !empty($layout_floor)}
										<td class="text-center cursor-pointer" style="background:#ffe59a" data-fancybox="layout_floor_{$_floor}" data-fancybox="488" data-src="{$clsISO->getGoogleUrl($layout_floor)}">{$_floor} <i class='bx bx-layout fs-12' ></i></td>
									{else}
										<td class="text-center" style="background:#ffe59a">{$_floor}</td>
									{/if}
									{if !empty($floor_merge_arrs[$_floor])}											
										{assign var=check_merge value=0}
										{assign var=col_plus value=0}
										{assign var=_dfloor value=$floor_merge_arrs[$_floor]}																				
										{assign var=cell_col_index value=$_dfloor.cell_col_index}
										{if !empty($_dfloor.is_out_order)}
											{assign var=is_out_order value= 1}
											{assign var=_arr_stock value= $_dfloor.arr_stocks}
										{else}
											{assign var=is_out_order value= 0}
											{assign var=_arr_stock value= $arr_stocks}
										{/if}
										{foreach name=k from=$_dfloor.arr_stocks item = _code key=key}													
											{assign var = _stock_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'stock_id')}
											{assign var = _agency_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'agency_id')}
											{assign var = _status_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'status_id')}
											{assign var = _show_website value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'show_website')}
											{assign var = _total_price_vat value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'total_price_vat')}{assign var = _total_price_early value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_early')}
											{assign var = _total_price_progress value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_progress')}
											{assign var = _total_price_bank value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_bank')}
											{assign var = _price_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_m2')}
											{assign var = _price_tts_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_tts_m2')}
											{assign var = _price_tttd_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_tttd_m2')}
											{assign var = _price_vay_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_vay_m2')}
											{assign var = _is_fund_type value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'is_fund_type')}
											{assign var = _is_dq value = $clsProject->getFieldValue($_floor, $_code, $arr_cells,'is_dq')}
											{assign var = index value = $key}
									
											{if ((!empty($cell_col_index[$key+1]) || (empty($cell_col_index[$key+1]) && !empty($check_merge)))) }
												{if !empty($_dfloor.cell_merge_arrs)}
													{foreach from=$_dfloor.cell_merge_arrs item=cell}
														{if ($cell.start_cell == $key+1)}
															{assign var=col_plus value=$cell.collspan}
														{/if}
													{/foreach}
												{/if}
												{math equation="x+y" x=$index y=$col_plus assign="index"}
												{assign var=check_merge value=1}
											{/if}
											{if $clsISO->checkItemInArray($_code,$_dfloor.arr_stocks)}	
												{if $_stock_id gt '0'}
													{if (!empty($_agency_id) && ($list_agency_cached_VIN[$_agency_id] eq '1' || $list_agency_cached_MAS[$_agency_id] eq '1')) || ($_status_id eq $smarty.const._STOCK_STATUS_HIDDEN_ID && !$clsISO->checkPermission('view_stock_hidden'))}
														{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
														<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" floor="{$_floor}" class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
													{else}
														{if !empty($_status_id) && $_status_id gt '0'}
															{if $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}
																<!-- Non -->xxx
															{else}
																{if $is_hide_stock_cross eq '1' && $_agency_id ne $smarty.const._AGENCY_FH_ID}
																	{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
																{/if}
																{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}
																	<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{$_code}" floor="{$_floor}" 
																	class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
																{else}
																	{assign var = _textcolor value = $status_textcolor_arrs.$_status_id}
																	<td floor="{$_floor}" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" class="stock_cell stock_cell_{$_stock_id} text-center js__stock-cell-focus cursor-pointer position-relative" style="background:{$status_bgcolor_arrs.$_status_id}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID || $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}{else} {if $deviceType eq 'phone'}onClick="$Core.helper.open_stock('{$_stock_id}');" {else}data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto"{/if} data-width="350"{/if}><a href="javascript:void(0)" style="color:{$_textcolor}" class="stock_show {$_is_dq}" data-total_price_vat="{$_total_price_vat}" data-total_price_early="{$_total_price_early}" data-total_price_progress="{$_total_price_progress}" data-total_price_bank="{$_total_price_bank}" data-price_m2="{$_price_m2}" data-price_tts_m2="{$_price_tts_m2}" data-price_tttd_m2="{$_price_tttd_m2}" data-price_vay_m2="{$_price_vay_m2}" >
																		{if !empty($_total_price_vat)}	
																			{if $_is_fund_type eq 1}
																				<span class="text_fund_type {if $_is_dq eq 1}classDQ{/if}" title="Thứ cấp"></span>
																			{/if}
																			{if $clsStock->checkShow($_show_website, 'MOC')}
																				<span class="price_show">{$_total_price_vat}</span>
																			{else}
																				<span class="text-decoration-line-through price_show">{$_total_price_vat}</span>
																			{/if}
																			{if $clsISO->checkItemInArray($_stock_id,$arr_stock_lock)}
																				<span class="lock_stock"><i class='bx bx-lock-alt' ></i></span>
																			{/if}
																		{else}
																			<!-- Empty -->
																		{/if}
																	</a></td>
																{/if}
															{/if}
														{else}
															{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
															{assign var = _bgcolor value = $status_bgcolor_arrs.$_status_id}
															<td floor="{$_floor}" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" style="cursor:pointer; background:{$_bgcolor}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}{else} data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-width="350"{/if} class="text-center js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
														{/if}
													{/if}
												{elseif !empty($arr_stocks[$index])}
													{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
													<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" floor="{$_floor}" class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
												{/if}
											{/if}
										{/foreach}											
									{elseif !empty($arr_floor_merge_not_config[$_floor]) && $profile_id eq 289}											
										{assign var=check_merge value=0}
										{assign var=col_plus value=0}
										{assign var=floor_not_config value=$arr_floor_merge_not_config[$_floor]}										
										{assign var=_dfloor value=$floor_merge_arrs[$floor_not_config]}																				
										{assign var=cell_col_index value=$_dfloor.cell_col_index}
										{if !empty($_dfloor.is_out_order)}
											{assign var=is_out_order value= 1}
											{assign var=_arr_stock value= $_dfloor.arr_stocks}
										{else}
											{assign var=is_out_order value= 0}
											{assign var=_arr_stock value= $arr_stocks}
										{/if}
										{foreach name=k from=$_dfloor.arr_stocks item = _code key=key}													
											{assign var = _stock_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'stock_id')}
											{assign var = _agency_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'agency_id')}
											{assign var = _status_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'status_id')}
											{assign var = _show_website value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'show_website')}
											{assign var = _total_price_vat value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'total_price_vat')}{assign var = _total_price_early value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_early')}
											{assign var = _total_price_progress value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_progress')}
											{assign var = _total_price_bank value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_bank')}
											{assign var = _price_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_m2')}
											{assign var = _price_tts_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_tts_m2')}
											{assign var = _price_tttd_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_tttd_m2')}
											{assign var = _price_vay_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_vay_m2')}
											{assign var = _is_fund_type value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'is_fund_type')}
											{assign var = _is_dq value = $clsProject->getFieldValue($_floor, $_code, $arr_cells,'is_dq')}
											{assign var = index value = $key}
											{if ((!empty($cell_col_index[$key+1]) || (empty($cell_col_index[$key+1]) && !empty($check_merge)))) }
												{if !empty($_dfloor.cell_merge_arrs)}
													{foreach from=$_dfloor.cell_merge_arrs item=cell}
														{if ($cell.start_cell == $key+1)}
															{assign var=col_plus value=$cell.collspan}
														{/if}
													{/foreach}
												{/if}
												{math equation="x+y" x=$index y=$col_plus assign="index"}
												{assign var=check_merge value=1}
											{/if}
											{if $clsISO->checkItemInArray($_code,$_dfloor.arr_stocks)}
												{if $_stock_id gt '0'}
													{if (!empty($_agency_id) && ($list_agency_cached_VIN[$_agency_id] eq '1' || $list_agency_cached_MAS[$_agency_id] eq '1')) || ($_status_id eq $smarty.const._STOCK_STATUS_HIDDEN_ID && !$clsISO->checkPermission('view_stock_hidden'))}
														{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
														<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" floor="{$_floor}" class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
													{else}
														{if !empty($_status_id) && $_status_id gt '0'}
															{if $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}
																<!-- Non -->
															{else}
																{if $is_hide_stock_cross eq '1' && $_agency_id ne $smarty.const._AGENCY_FH_ID}
																	{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
																{/if}
																{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}
																	<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{$_code}" floor="{$_floor}" 
																	class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
																{else}
																{assign var = _textcolor value = $status_textcolor_arrs.$_status_id}
																<td floor="{$_floor}" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" class="stock_cell stock_cell_{$_stock_id} text-center js__stock-cell-focus cursor-pointer position-relative" style="background:{$status_bgcolor_arrs.$_status_id}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID || $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}{else} {if $deviceType eq 'phone'}onClick="$Core.helper.open_stock('{$_stock_id}');" {else}data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto"{/if} data-width="350"{/if}><a href="javascript:void(0)" style="color:{$_textcolor}" class="stock_show {$_is_dq}" data-total_price_vat="{$_total_price_vat}" data-total_price_early="{$_total_price_early}" data-total_price_progress="{$_total_price_progress}" data-total_price_bank="{$_total_price_bank}" data-price_m2="{$_price_m2}" data-price_tts_m2="{$_price_tts_m2}" data-price_tttd_m2="{$_price_tttd_m2}" data-price_vay_m2="{$_price_vay_m2}" >
																	{if !empty($_total_price_vat)}	
																		{if $_is_fund_type eq 1}
																			<span class="text_fund_type {if $_is_dq eq 1}classDQ{/if}" title="Thứ cấp"></span>
																		{/if}
																		{if $clsStock->checkShow($_show_website, 'MOC')}
																			<span class="price_show">{$_total_price_vat}</span>
																		{else}
																			<span class="text-decoration-line-through price_show">{$_total_price_vat}</span>
																		{/if}
																		{if $clsISO->checkItemInArray($_stock_id,$arr_stock_lock)}
																			<span class="lock_stock"><i class='bx bx-lock-alt' ></i></span>
																		{/if}
																	{else}
																		<!-- Empty -->
																	{/if}
																</a></td>
																{/if}
															{/if}
														{else}
															{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
															{assign var = _bgcolor value = $status_bgcolor_arrs.$_status_id}
															<td floor="{$_floor}" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" style="cursor:pointer; background:{$_bgcolor}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}{else} data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-width="350"{/if} class="text-center js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
														{/if}
													{/if}
												{elseif !empty($arr_stocks[$index])}
													{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
													<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{if !empty($is_out_order)}{$arr_stocks[$key]}{else}{$_code}{/if}" floor="{$_floor}" class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
												{/if}
											{/if}
										{/foreach}											
									{else}
										{foreach name=k from=$arr_stocks item = _code}
										{assign var = _stock_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'stock_id')}
										{assign var = _agency_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'agency_id')}
										{assign var = _status_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'status_id')}
										{assign var = _show_website value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'show_website')}
										{assign var = _total_price_vat value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'total_price_vat')}
										{assign var = _total_price_early value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_early')}
										{assign var = _total_price_progress value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_progress')}
										{assign var = _total_price_bank value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'total_price_bank')}
										{assign var = _price_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_m2')}
										{assign var = _price_tts_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_tts_m2')}
										{assign var = _price_tttd_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_tttd_m2')}
										{assign var = _price_vay_m2 value = $clsProject->getPriceFieldValue($_floor, $_code, $arr_cells, 'price_vay_m2')}
										{assign var = _is_fund_type value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'is_fund_type')}
										{assign var = _is_dq value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'is_dq')}
										{if !$clsISO->checkItemInArray($smarty.foreach.k.iteration,$config_floor[$_floor])}
											{if $_stock_id gt '0'}
												{if (!empty($_agency_id) && ($list_agency_cached_VIN[$_agency_id] eq '1' || $list_agency_cached_MAS[$_agency_id] eq '1')) || ($_status_id eq $smarty.const._STOCK_STATUS_HIDDEN_ID && !$clsISO->checkPermission('view_stock_hidden'))}
													{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
													<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{$_code}" floor="{$_floor}" 
													class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
												{else}
													{if !empty($_status_id) && $_status_id gt '0'}
														{if $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}
															<!-- Non -->
														{else}
															{if $is_hide_stock_cross eq '1' && $_agency_id ne $smarty.const._AGENCY_FH_ID}
																{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
															{/if}
															{assign var = _textcolor value = $status_textcolor_arrs.$_status_id}
															{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}
																<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{$_code}" floor="{$_floor}" 
																class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
															{else}
															<td floor="{$_floor}" code="{$_code}" class="text-center js__stock-cell-focus cursor-pointer position-relative" style="background:{$status_bgcolor_arrs.$_status_id}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID || $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}{else} {if $deviceType eq 'phone'}onClick="$Core.helper.open_stock('{$_stock_id}');" {else}data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto"{/if} data-width="350"{/if}><a href="javascript:void(0)" style="color:{$_textcolor}" class="stock_show {$_is_dq}" data-total_price_vat="{$_total_price_vat}" data-total_price_early="{$_total_price_early}" data-total_price_progress="{$_total_price_progress}" data-total_price_bank="{$_total_price_bank}" data-price_m2="{$_price_m2}" data-price_tts_m2="{$_price_tts_m2}" data-price_tttd_m2="{$_price_tttd_m2}" data-price_vay_m2="{$_price_vay_m2}" >
																{if !empty($_total_price_vat)}													
																	{if $_is_fund_type eq 1}
																		<span class="text_fund_type {if $_is_dq eq 1}classDQ{/if}" title="Thứ cấp"></span>
																	{/if}
																	{if $clsStock->checkShow($_show_website, 'MOC')}
																		<span class="price_show">{$_total_price_vat}</span>
																	{else}
																		<span class="text-decoration-line-through price_show">{$_total_price_vat}</span>
																	{/if}
																	{if $clsISO->checkItemInArray($_stock_id,$arr_stock_lock)}
																		<span class="lock_stock"><i class='bx bx-lock-alt' ></i></span>
																	{/if}
																{else}
																	<!-- Empty -->
																{/if}
															</a></td>
															{/if}
														{/if}
													{else}
														{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
														{assign var = _bgcolor value = $status_bgcolor_arrs.$_status_id}
														<td floor="{$_floor}" code="{$_code}" style="cursor:pointer; background:{$_bgcolor}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}{else} data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-width="350"{/if} class="text-center js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
													{/if}
												{/if}
											{else}
												{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
												<td style="background:{$status_bgcolor_arrs.$_status_id};" code="{$_code}" 
													floor="{$_floor}" class="js__stock-cell-focus bg_sold {$stock_bg_sold}"></td>
											{/if}
										{/if}
										{/foreach}	
									{/if}
								</tr>
								{/if}
							{/foreach}
						</tbody>
					</table>
				</div>
			{else}
			<div class="d-flex justify-content-center mb-2">
				<div class="btn-group">
					<a href="javascript:void(0);" class="btn flex-fill btn-primary">
						<i class="bx bx-table"></i> 
						{if $deviceType ne 'phone'}Xem dạng bảng{else}Bảng{/if}
					</a>
					{if $is_project_block eq '1'}
					<a href="{$clsProperty->getLink('stock_layout', [
						'project_id' => $project_id,
						'block_id' => $block_id
					])}" class="btn flex-fill btn-outline-default">
						<i class="bx bx-map-pin"></i> 
						{if $deviceType ne 'phone'}Xem dạng mặt bằng{else}Mặt bằng{/if}
					</a>
					{else}
					<a href="{$PCMS_URL}/project/p{$project_id}/layout.html" class="btn flex-fill btn-outline-default">
						<i class="bx bx-map-pin"></i> 
						{if $deviceType ne 'phone'}Xem dạng mặt bằng{else}Mặt bằng{/if}
					</a>
					{/if}
				</div>
			</div>
			<hr />
			<form method="POST">
				{$core->getBlock('stock_search', [
					'project_id' => $project_id,
					'block_id' => $block_id, 
					'is_project_block' => $is_project_block]
				)}
			</form>
			<hr />
			{if $clsISO->_DEV() || 1==1}
				<div class="clearfix"></div>
				<div class="tableStock table-container no-shadow mt-3 freeze-table text-nowrap">
					<table class="table no-bootstrap dragable table-grid table-bordered nohover" 
						width="100%" cellpadding="0" cellspacing="0">
						<thead><tr>
							{foreach from=$arr_field item=_label key=_field name=i}
								<th class="align-center text-upper h-px-30 text-center" width="100px">{$_label}</th>
							{/foreach}
						</tr></thead>
						<tbody class="holder_stock_{$project_id}">
							{section name=i loop=$list_preloaders max=30}
							<tr>
								{foreach from=$arr_field item=_label key=_field name=i}
								<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
								{/foreach}
							</tr>
							{/section}
						</tbody>
					</table>
				</div>
				<div class="d-flex justify-content-between py-2 text-center">
					<button type="button" class="showmorethisresult d-none" title="Xem thêm" 
						onClick="$Core.stock.load_more(this, event)" page="2"> 
						<span>Xem thêm</span> 
						<i class="bx bx-chevrons-down translate-px-2"></i>
					</button> 
				</div>
				{literal}
				<script type="text/javascript">
					$(function(){ 
						setTimeout(() => {
							$Core.stock.load_stock(project_id, {
								'block_id':block_id,
								'is_project_block':is_project_block
							}); 
						}, 500);
					});
				</script>
				{/literal}
			{else}
			<div class="clearfix"></div>
			<div class="tableStock table-container no-shadow mt-3 freeze-table text-nowrap">
				<table class="table no-bootstrap dragable table-grid table-bordered nohover" 
					width="100%" cellpadding="0" cellspacing="0">
					<thead><tr>
						<th class="align-center text-upper h-px-30 text-center" width="100px">Mã căn</th>
						<th class="align-center text-upper h-px-30 text-center" width="100px">Phân khu</th>
						<th class="align-center text-upper h-px-30 text-left">Dãy</th>
						<th class="align-center text-upper h-px-30 text-left" width="80px">Loại hình</th>
						<th class="align-center text-upper h-px-30 text-center" width="80px">DT Đất</th>
						<th class="align-center text-upper h-px-30 text-center" width="80px">DT XD</th>
						<th class="align-center text-upper h-px-30 text-left" width="150px">TCBG</th>
						<th class="align-center text-upper h-px-30 text-center">Hướng</th>
						<th class="align-center text-upper h-px-30 text-left" width="120px">Giá VAT&KPBT</th>
						<!-- <th class="align-center text-upper text-center">Giá Vay 24T</th>
						<th class="align-center text-upper text-center">Giá Vay 36T</th>
						<th class="align-center text-upper text-center">Giá TTTĐ</th>
						<th class="align-center text-upper text-center">Giá TTS</th> -->
						<th class="align-center text-upper h-px-30 text-left">CSBH</th>
						<th class="align-center text-upper h-px-30 text-left">Ngày ký cọc</th>
						<!--<th class="align-center text-upper text-center">Chính sách</th>-->
						<th class="align-center text-upper h-px-30 text-center">PTG</th>
						<th class="align-center text-upper h-px-30 text-center">Loại hình ký</th>
						<th class="align-center text-upper h-px-30 text-left">Quỹ đầu tư</th>
						<th class="align-center text-upper h-px-30 text-left">Giỏ Bank</th>
						<th class="align-center text-upper h-px-30 text-left">Ghi chú</th>
						<!-- <th class="align-center">Ngày ký cọc</th> -->
						<!-- <th class="align-center text-center">CT ký HĐ</th>
						<th class="align-center text-center">Giỏ Bank TC</th>
						<th class="align-center">Tình trạng</th>
						<th class="align-center text-center">TT bán</th>
						<th class="align-center text-center">ĐL lock</th>
						<th class="align-center text-center">ĐL cọc</th> -->
					</tr></thead>
					<tbody class="holder_stock_{$project_id}">
						{section name=i loop=$list_preloaders max=30}
						<tr>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div class="d-flex justify-content-between py-2 text-center">
				<button type="button" class="showmorethisresult d-none" title="Xem thêm" 
					onClick="$Core.stock.load_more(this, event)" page="2"> 
					<span>Xem thêm</span> 
					<i class="bx bx-chevrons-down translate-px-2"></i>
				</button> 
			</div>
			{literal}
			<script type="text/javascript">
				$(function(){ 
					setTimeout(() => {
						$Core.stock.load_stock(project_id, {
							'block_id':block_id,
							'is_project_block':is_project_block
						}); 
					}, 500);
				});
			</script>
			{/literal}
			{/if}
			{/if}
			<!-- End bảng hàng -->
			<div class="py-2 d-flex flex-wrap gap-2 align-items-center justify-content-center">
				{if !empty($vr_link)}
				<a class="btn btn-outline-default text-main fw-bold{if $deviceType eq 'phone'} flex-fill{/if}" href="{$vr_link}" data-fancybox data-type="iframe" data-caption="{if !empty($vr_source)}{$vr_source}{else}Bản quyền thuộc về masterihomes.com{/if}"><i class="material-icons-outlined text-main">360</i> Xem sa bàn ảo 360<sup>o</sup></a>
				{/if}
				{if !empty($onePolicy)}
					<a href="{$onePolicy.link_ns}" class="btn btn-outline-default{if $deviceType eq 'phone'} flex-fill{/if}" target="_blank">{$clsISO->makeIcon('bx-check-shield','Chính sách bán hàng')} {$oneBuilding.title}</a> 
					<a href="{$onePolicy.link_ms}" class="btn btn-outline-default{if $deviceType eq 'phone'} flex-fill{/if}" target="_blank">{$clsISO->makeIcon('bx-spreadsheet','Phiếu tính giá')} {$oneBuilding.title}</a>
				{/if}
			</div>
		</div>
		{if !empty($oneBuilding.intro)}
		<div class="p-4 mt-4 bg-lighter rounded-2">
			<div class="tinyContent js__readmore-content">
				{$oneBuilding.intro}
			</div>
		</div>
		{/if}
	</div>
</div>
{literal}
<style type="text/css">
	.freeze-table{
		user-select:none;
		-moz-user-select:none;
		-khtml-user-select:none;
		-webkit-user-select:none;
		-o-user-select:none;
		overflow-x:auto;
		-webkit-overflow-scrolling:touch
	}
	.table-stock .cell{
		position:relative;
		background:#287e3f!important;
		color:rgba(255,255,255,1)!important
	}
	.table-container .table thead tr th.cell:not(.no-sticky):first-child, .table-container .table tfoot tr td:not(.no-sticky):first-child, .table-container .table tfoot tr th:not(.no-sticky):first-child {
		background:#287e3f!important;
		color:rgba(255,255,255,1)!important
	}
	.table-container:not(.no-shadow) .table thead tr th:not(.cell):not(.no-sticky):nth-child(1) {
		background: #FFF !important;
		box-shadow: 0 0;
	}
	.table-tooltip th,.table-tooltip td{
		padding:.325rem .625rem
	}
	.table-grid td{
		padding: 0.325rem 0.325rem
	}
	.table-grid th{
		line-height:16px; 
		background:rgb(245 247 248);
		text-overflow: inherit
	}
	.table-stock th{
		text-overflow: inherit
	}
	.dropdown_price .dropdown-item:not(.active) {
		opacity: 0.8;
	}
	@media screen and (min-width:1400px) {
/*		.table-stock{ table-layout:fixed;}*/
		.table-stock th,
		.table-stock td{
			font-size:10px;
			padding:.125rem .325rem; 
			height:16px; 
			line-height:16px;
		}
		.table-grid th{
			padding:0.625rem 0.325rem
		}
	}
	@media screen and (max-width:1400px) {
		.table-stock th,
		.table-stock td{font-size:8px; padding:.125rem .2rem}
	}
	@media screen and (max-width:575px) {
		.om-xs\:mb-1{ margin-bottom:10px;}
	}
	.box_tool_tip {
		font-weight: bold;
		font-size: 11px;
		color:var(--bs-white);
		background: #FFF0;
		border: none !important;
		box-shadow: none !important;
		border-radius:10px 0 10px 0;
	}
	.box_tool_tip:before,
	.box_tool_tip .leaflet-tooltip-arrow {
		/* display: none;*/
	}
	.item_tooltip {
		width: 130px;
		text-align: center;
		background: #eadcc1;
		border: 1px solid #eadcc1;
		border-radius: 10px;
		overflow: hidden;
	}
	.item_tooltip .box_code {
		padding: 3px 5px;
		font-weight: bold;
		font-size: 16px;
		background: #966334;
		color: #f5fac0;
		text-shadow: 2px 1px black;
	}
	.item_tooltip .body_tooltip {
		padding: 5px;
	}
	.item_tooltip .box_text {
		font-size: 9px;
		margin-bottom: 3px;
		color: #342828;
	}
	.item_tooltip .text-value {
		font-weight: 700;
		font-size: 11px;
	}
	.item_tooltip .text-price {
		padding: 3px;
		font-size: 14px;
		background: #966334;
		color: #f5fac0;
		text-shadow: 2px 1px #51341b;
		margin-top: 3px;
		border-radius: 5px;
	}
</style>
{/literal}