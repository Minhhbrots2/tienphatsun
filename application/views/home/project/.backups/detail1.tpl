<div class="container-xxl flex-grow-1 pt-2 container-p-y"> 
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item">
				<a href="{$PCMS_URL}/thong-tin/">Thông tin dự án</a>
			</li>
			<li class="breadcrumb-item">
				<a href="/thong-tin/p{$project_id}.html">{$clsProject->getCode($project_id,$oneProject)}</a>
			</li>
			{if $show eq 'block'}
			<li class="breadcrumb-item active">{$oneBlock.title}</li>
			{else if $show eq 'building'}
			<li class="breadcrumb-item">
				<a href="{$clsProject->getLinkDetail($project_id,$oneBlock.property_id,0,$oneProject)}">{$oneBlock.title}</a>
			</li>
			<li class="breadcrumb-item active">{$oneBuilding.title}</li>
			{/if}
		</ol>
	</nav>
	<div class="clearfix"></div>
	<section class="section_banner banner_page d-flex justify-content-center mb-3 relative" style="background-image:url('{$oneProject.image}')">
		<div class="w-100 mx-auto d-flex flex-column justify-content-between"> 
			<div class="top_content_banner text-white d-flex justify-content-between align-items-center w-100 zindex-2">
				<div class="eblWgTAbDi">
					<div class="dropdown">
						<button data-toggle="ripple" class="btn bg-warning rounded-pill text-white dropdown-toggle{if $deviceType eq 'phone'} btn-sm{/if}" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" data-popper-placement="top-start" aria-haspopup="true" aria-expanded="false">Bảng hàng</button>
						{if !empty($list_projects)}
						<div class="dropdown-menu  dropdown-menu-stock dropdown-menu-stock_project">
							<div class="p-3">
								<div class="overflow-y-auto" style="max-height: 300px">
									{foreach from=$list_projects item=_oProject key=key}
										{if $clsISO->checkItemInArray($smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE,$_oProject.list_block_type)}
											{assign var = lstblocks value = $_oProject.list_blocks}
											<div class="mb-3">
												<div class="d-flex gap-2 align-items-center justify-content-center">
													<img src="{$_oProject.logo}" class="h-px-30" />
													<h3 class="fs-6 mb-0 text-upper">{$_oProject.title}</h3>
												</div>
												{foreach from=$lstblocks item=_oBlock key=k_block name=n_block}
												{assign var = list_menu_buildings value = $_oBlock.list_menu_buildings}
												{if !empty($list_menu_buildings)}
												<div class="block-one mt-3">
													<div class="divider my-2">
														<div class="divider-text">Phân khu {$_oBlock.title}</div>
													</div>
													<ul class="mb-0 list-unstyled d-flex flex-wrap gap-2">
														{foreach from=$list_menu_buildings item=_oBuilding}
														<li class="flex-fill">
															<a data-toggle="ripple" title="{$_oBuilding.title}" class="btn btn-sm btn-outline-primary w-100" href="{$_oBuilding.link}">{$_oBuilding.title}</a>
														</li>
														{/foreach}
													</ul>
												</div>
												{/if}
												{/foreach}
											</div>
										{/if}
									{/foreach}
								</div>
								{if $is_lowfloor eq '1'}
								<div class="clearfix"></div>
								<div class="divider my-2">
									<div class="divider-text">Thấp tầng</div>
								</div>
								<a data-toggle="ripple" class="btn btn-sm btn-outline-primary w-100" href="/project/p{$project_id}.html">Bảng hàng</a>
								{/if}
								<div class="divider my-2">
									<div class="divider-text">Dự án thấp tầng</div>
								</div>
								<div class="d-flex gap-2 align-items-center">
								{foreach from=$list_projects item=_oProject key=key}
									{if $clsISO->checkItemInArray($_oProject.project_id,$arr_project) && $_oProject.project_id ne $project_id}
									<a data-toggle="ripple" href="/project/p{$_oProject.project_id}.html" class="btn flex-fill btn-outline-primary w-100 px-2">
										<img src="{$_oProject.logo}" class="h-px-30 mw-100" style="object-fit: contain">
										<div class="clearfix my-1"></div>
										<span class="fs-12" >{$_oProject.code}</span>
									</a>
									{/if}
								{/foreach}
								</div>
							</div>
						</div>
						{/if}
					</div>
				</div>
				<div class="d-flex justify-content-end align-items-center gap-1">
					<button class="btn btn-info text-white pulse position-relative rounded-pill {if $deviceType eq 'phone'} btn-sm px-1{/if}" type="button" onClick="$Core.project.open_model(this,event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" _type="is_model">Nhà mẫu</button>
					<button class="btn btn-success text-white pulse position-relative rounded-pill {if $deviceType eq 'phone'} btn-sm px-1{/if}" type="button" onClick="$Core.project.open_model(this,event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" _type="is_handoverSpecs">TC Bàn giao</button>
					{if $show eq 'project'}
					<span class="btn{if $deviceType eq 'phone'} btn-sm{/if} bg-success rounded-pill text-white">Mở bán</span>
					{else}
						{if !empty($more_information.on_sale)}
						<span class="btn{if $deviceType eq 'phone'} btn-sm{/if} bg-success rounded-pill text-white">Mở bán</span>		
						{/if}
					{/if}
				</div>
				
			</div>
			{assign var = toId value = $clsISO->getUniqid()}
			<div class="content_banner d-flex flex-column justify-content-between align-items-start w-100 zindex-1">
				<div class="mb-3 text-white">
					{if $show eq "block"}
						<h1 class="title_project fw-semibold mb-1">
							<a class="title_project text-white fs-4" toId="{$toId}" onClick="$Core.project.toggle_info(this,event)">
							<span>{$oneBlock.title}</span>
							<i class="bx bx-chevron-down"></i></a>
						</h1>
						<p class="text-white mb-0 fs-12">
							<a href="{$clsProject->getLinkDetail($project_id,0,0,$oneProject)}" 
								class="text-white">{$oneProject.title}</a>
						</p>
					{else if $show eq "building"}
						<h1 class="title_project fw-semibold mb-1">
							<a class="title_project fs-4 text-white" toId="{$toId}" onClick="$Core.project.toggle_info(this,event)">
							<span>{$oneBuilding.title}</span>
							<i class="bx bx-chevron-down"></i></a>
						</h1>
						<p class="text-white mb-0 fs-12">
							<a href="{$clsProject->getLinkDetail($project_id,$block_id,0,$oneProject)}" class="text-white">Phân khu {$oneBlock.title}</a>, <a href="{$clsProject->getLinkDetail($project_id,0,0,$oneProject)}" class="text-white">{$oneProject.title}</a>
						</p>
					{else}
						<h1 class="title_project fw-semibold mb-1">
							<a class="title_project fs-4 text-white" toId="{$toId}" onClick="$Core.project.toggle_info(this,event)">
								<span>{$oneProject.title}</span>
								<i class="bx bx-chevron-down"></i>
							</a>
						</h1>
						<div class="d-flex gap-1 text-white fs-12 align-items-center">
							<i class='bx bx-map'></i>
							<span>{$more_information.address}</span>
						</div>
					{/if}
				</div>
				<div class="d-flex w-100 {if $deviceType eq 'phone'}gap-1{else}gap-4{/if}">
					{if $show eq 'building'}
					<a data-toggle="ripple" href="{$link_stock}" class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-2 rounded-3 flex-flow text-white">
						<i class="bx bx-table mb-1"></i>
						<span class="txt_option">Bảng hàng</span>
					</a>
					{/if}
					{foreach from=$list_category_docs item=_oCatDocs key=key name=i}
					<a data-toggle="ripple" class="item_option d-flex flex-column text-center align-items-center justify-content-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-2 rounded-3 flex-flow text-white{if $root_id eq $_oCatDocs.property_id} active{/if}" href="{$clsProject->getLinkInfo($project_id, $block_id, $building_id, $_oCatDocs.property_id)}">
						<i class="{$_oCatDocs.image} mb-1"></i>
						<span class="txt_option">{$_oCatDocs.title}</span>
					</a>
					{/foreach}
					{if $show eq 'project'}
					<a data-toggle="ripple" href="javascript:void(0)" onClick="scrollDown(this, event)" toId="box_block" class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-2 rounded-3 flex-flow text-white">
						<i class="bx bx-fullscreen mb-1"></i>
						<span class="txt_option">Phân khu</span>
					</a>
					<a data-toggle="ripple" href="{$clsProject->getLinkInfo($project_id,$block_id,$building_id,'_utility')}" class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-2 rounded-3 flex-flow text-white{if $cat_id eq $smarty.const._PROJECT_DOCS_UTILITY_CATID} active{/if}">
						<i class="bx bx-category mb-1"></i>
						<span class="txt_option">Tiện ích</span>
					</a>
					{elseif $show eq 'block'}
					{if $oneBlock.parent_id eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
					<a data-toggle="ripple" href="javascript:void(0)" onClick="scrollDown(this, event)" toId="box_building" class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-2 rounded-3 flex-flow text-white">
						<i class="bx bx-building mb-1"></i>
						<span class="txt_option">Toà</span>
					</a>
					{/if}
					<a data-toggle="ripple" href="{$clsProject->getLinkInfo($project_id, $block_id, $building_id, '_utility')}" class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-2 rounded-3 flex-flow text-white{if $cat_id eq $smarty.const._PROJECT_DOCS_UTILITY_CATID} active{/if}">
						<i class="bx bx-category mb-1"></i>
						<span class="txt_option">Tiện ích</span>
					</a>
					{elseif $show eq 'building'}
					<a data-toggle="ripple" href="{$clsProject->getLinkInfo($project_id, $block_id, $building_id, '_utility')}" class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-2 rounded-3 flex-flow text-white{if $cat_id eq $smarty.const._PROJECT_DOCS_UTILITY_CATID} active{/if}">
						<i class="bx bx-category mb-1"></i>
						<span class="txt_option">Tiện ích</span>
					</a>
					{/if}
					{if $deviceType ne 'phone' && $show ne 'building'}
					<a data-toggle="ripple"{if !empty($vr_link)} href="{$vr_link}" data-fancybox{else} disabled{/if} data-caption="{$vr_source}" data-type="iframe" data-preload="true" class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-2 rounded-3 flex-flow text-white">
						<i class='bx bx-analyse mb-2'></i>
						<span class="txt_option">VR360</span>
					</a>
					{/if}
				</div>
			</div>
		</div>
	</section>
	<div class="clearfix"></div>
	<div id="{$toId}" class="d-none w-100 mb-3"><div class="box_info">
		<h3 class="fs-5 mb-2 fw-bold">Thông tin {$subfix}</h3>
		{if !empty($more_information.attrs)}
		<div class="dqbGyrlVzN" id="lst_info">
			<div class="tinyContent fs-14" data-height="45px">
				{foreach from=$more_information.attrs item = _oAttr}
				<div class="item_info mb-1">
					<label for="" class="lbl_item">{$_oAttr.title}</label>
					<span class="content_item text-dark fw-semibold">{$_oAttr.content}</span>
				</div>
				{/foreach}
			</div>
		</div>
		{/if}
	</div></div>
	<!-- End thông tin -->
	<div class="clearfix"></div>
	{if $cat_id eq $smarty.const._PROJECT_DOCS_UTILITY_CATID}
		{if !empty($list_utilities)}
			<div id="box_tab" class="box_tab mb-4">
			{foreach from=$list_utilities item=_oCat name=name_cat}	
				{assign var = _utilities value = $_oCat.utilities}
				{if !empty($_utilities)} 
				<div class="position-relative d-flex justify-content-between align-items-start gap-2 py-3{if !$smarty.foreach.name_cat.first} border-top{/if} {if $deviceType eq 'phone'}flex-wrap{/if}">
					<div class="cat_title w-20 fs-5 fw-semibold d-flex align-items-center gap-1 flex-fill{if !$smarty.foreach.name_cat.first} {if $deviceType ne 'phone'}collapsed{/if}{/if}" data-bs-toggle="collapse" href="#CatUtilities_{$_oCat.property_id}" role="button" aria-expanded="false" aria-controls="CatUtilities_{$_oCat.property_id}">{if $_oCat.image}<img src="{$_oCat.image}" width="20" height="20" alt="{$_oCat.title}">{/if}{$_oCat.title} ({$_utilities|@count})</div>
					<a class="btn btn-icon collapse_arrow accordion-close fs-20{if !$smarty.foreach.name_cat.first} {if $deviceType ne 'phone'}collapsed{/if}{/if}" data-bs-toggle="collapse" href="#CatUtilities_{$_oCat.property_id}" role="button" aria-expanded="false" aria-controls="CatUtilities_{$_oCat.property_id}"><i class='bx bxs-chevron-down'></i></a>
					<div class="collapse{if $smarty.foreach.name_cat.first || $deviceType eq 'phone'} show{/if} flex-fill {if $deviceType eq 'phone'}w-100{else}w-70{/if}" id="CatUtilities_{$_oCat.property_id}">
						<div class="awe__utilities-list{if $deviceType ne 'phone'} pe-5{/if}">
							{foreach from=$_utilities item=_oUtilities name=i}
							<div class="awe__utilities-item d-flex align-items-center gap-3{if !$smarty.foreach.i.last} border-bottom pb-3 mb-3{/if}">
								<a class="d-block awe__utilities-img cursor-pointer" data-fancybox="gallery" data-src="{$_oUtilities.image}" data-caption="{$_oUtilities.title}">
									<img class="rounded-2 object-cover" src="{$clsISO->getGoogleUrl($_oUtilities.image)}" data-src="{$clsISO->getGoogleUrl($_oUtilities.image)}" alt="{$_oUtilities.title}" width="100" height="80" onerror="this.src='{$URL_IMAGES}/no-image.png'"></a>
								<div class="awe__utilities-body">
									<h4 class="awe__utilities-title fs-16 fw-semibold mb-2">{$_oUtilities.title|html_entity_decode}</h4>
									<div class="awe__utilities-text limit_2line">{$_oUtilities.content|html_entity_decode}</div>
								</div>
							</div>
							{/foreach}
						</div>
					</div>
				</div>
				{/if}
			{/foreach}
			</div>
		{else}
			<div class="d-flex justify-content-center">
				<div class="text-center p-4">
					<img src="{$URL_IMAGES}/no-data.png" height="200">
					<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
				</div>
			</div>
		{/if}
	{else}
	<div id="box_tab" class="box_tab mb-4">
		{if !empty($list_childs)}
		<div class="nav-align-top nav-tabs-shadow">
			<div class="w-100 overflow-x-auto mb-4">
				<ul class="nav nav-tabs" id="project_tab" role="tablist">
					{foreach from=$list_childs item=_oChild key=key name=i}
					<li class="nav-item" role="presentation">
						<a onClick="$Core.project.tab_click(this, event)" href="#{$_oChild.slug}" uri="{$curl}" slug="{$_oChild.slug}" id="tabclick_{$_oChild.slug}" class="btn btn_tab rounded-pill{if $_oChild.is_active eq '1'} active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#{$_oChild.slug}" aria-controls="tab-pane_{$_oChild.property_id}" aria-selected="true">{$_oChild.title}</a>
					</li>
					{/foreach}
				</ul>
			</div>
			<div class="tab-content p-0">
				<!-- Có danh mục -->
				{foreach from=$list_childs item = _oChild name = i}
				{assign var = list_docs value = $_oChild.list_docs}
				<div id="{$_oChild.slug}" class="tab-pane fade{if $_oChild.is_active eq '1'} active show{/if}">
					<div class="tab_box mb-3"><div class="tab_body">
						{if !empty($list_docs) || ($_oChild.property_id eq $smarty.const._PROJECT_DOCS_PR_CATID and !empty($list_posts))}
						<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxxl-5">
							{if !empty($list_posts) && $_oChild.property_id eq $smarty.const._PROJECT_DOCS_PR_CATID}
								{foreach from=$list_posts item=_oDoc key=k_doc name=n_doc}
								{assign var = _more_information value = $_oDoc.more_information}
								<div class="col mb-2 awe__doc-item">
									<a class="link awe__doc-link overflow-hidden rounded-2" href="{$_oDoc.content}" target="_blank">
										<div class="awe__doc-img position-relative">
											<div class="img-background" style="background-image:url('{$_more_information.image}'), url('{$URL_IMAGES}/no-image.png')"></div>
										</div>
										<h4 class="fs-13 line-clamp-2 text-center text-white lh-sm mb-0"><span class="limit_2line">{$_oDoc.title}</span></h4>
									</a>
								</div>
								{/foreach}
							{/if}
							<!-- End Post -->
							{foreach from=$list_docs item=_oDoc key=k_doc name=n_doc}
							{assign var = _list_images value = $_oDoc.list_images}
							{assign var = _more_information value = $_oDoc.more_information}
							<div class="col mb-2 awe__doc-item position-relative {if $smarty.foreach.n_doc.iteration gt '10'} d-none awe__doc-item-hidden{/if}">
								{if $_oDoc.type ne 'video' && $_oDoc.type ne 'youtu.be'}
								<a href="{$_oDoc.link_download}" target="_blank" class="awe__doc-download rounded-pill">
									<i class="bx bx-download"></i></a>{/if}
								<a class="link awe__doc-link overflow-hidden rounded-3" data-preload="false" {if $_oDoc.type eq 'youtu.be'}data-fancybox href="https://www.youtube.com/watch?v={$_more_information.youtu_id}"{elseif $_oDoc.type eq 'google.file'}data-fancybox="{$_oDoc.id}"{if !empty($_list_images)} data-src="{$clsISO->genGoogleURL($_more_information.gg_id)}"{else} href="{$_oDoc.link}"{/if}{elseif $_oDoc.type eq 'video' || $_oDoc.type eq 'pdf'} data-fancybox="{$_oDoc.id}" href="{$_oDoc.link}" data-type="iframe"{else}href="{$_oDoc.content}" target="_blank"{/if}>
									<div class="awe__doc-img position-relative">
										{if $_oDoc.type eq 'video' || $_oDoc.type eq 'youtu.be'}
										<div class="img-play"><i class="bx bx-play"></i></div>{/if}
										<div class="img-background" style="background-image:url('{$_oDoc.image}'),url('{$URL_IMAGES}/no-image.png')"></div>
									</div>
									<h4 class="fs-13 line-clamp-2 text-center text-white lh-sm mb-0"><span class="limit_2line">{$_oDoc.title}</span></h4>
								</a>
								{if !empty($_list_images)}
									{foreach from=$_list_images item = _oImage}
									<a class="d-none" data-fancybox="{$_oDoc.id}"{if $_oImage.type eq 'video' || $_oImage.type eq 'pdf'} data-type="iframe"{/if} data-src="{$_oImage.image}"></a>
									{/foreach}
								{/if}
							</div>
							{/foreach}
						</div>
						{if $list_docs|@count gt '10'}
						<div class="d-flex justify-content-center">
							<a href="javascript:void(0)" onClick="$Core.home.toggle_item(this, event)" class="btn btn-outline-default px-4 rounded-pill"><i class="bx bx-chevron-down"></i> Xem thêm</a>
						</div>
						{/if}
						{else}
							<div class="d-flex justify-content-center">
								<div class="text-center p-4">
									<img src="{$URL_IMAGES}/no-data.png" height="200">
									<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
								</div>
							</div>
						{/if}
					</div></div>
				</div>
				{/foreach}
			</div>
		</div>
		{else}
		<!-- Không có danh mục -->
		
		<!-- Layout -->
		{if $cat_id eq $smarty.const._PROJECT_DOCS_LAYOUT_CATID}
		<div class="tab_box mb-4"><div class="nav-align-top nav-tabs-shadow">
			{if $show eq 'project'}
				<div class="w-100 overflow-x-auto hide-scroll-thumb mb-4">
					<ul class="nav nav-tabs dragable flex-nowrap" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="btn text-nowrap btn_tab active rounded-pill" role="tab">Tổng thể</a>
						</li>
						{foreach from=$list_blocks item=_oBlock}
						<li class="nav-item" role="presentation">
							<a href="{$clsProject->getLinkInfo($project_id, $_oBlock.property_id,0, $smarty.const._PROJECT_DOCS_LAYOUT_CATID)}" title="Mặt bằng {$_oBlock.title}" class="btn btn_tab rounded-pill text-nowrap">{$_oBlock.title}</a>
						</li>
						{/foreach}
					</ul>
				</div>
				{$core->getBlock('project_map', ['more_information' => $more_information])}
			{elseif $show eq 'block'}
				{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
				<div class="w-100 hide-scroll-thumb overflow-x-auto mb-4">
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="btn btn_tab active rounded-pill" role="tab">Tổng thể</a>
						</li>
						{foreach from=$list_buildings item=_oBuilding}
						<li class="nav-item" role="presentation">
							<a href="{$clsProject->getLinkInfo($project_id, $block_id, $_oBuilding.property_id, $smarty.const._PROJECT_DOCS_LAYOUT_CATID)}" title="Mặt bằng {$_oBuilding.title}" class="btn btn_tab rounded-pill">{$_oBuilding.title}</a>
						</li>
						{/foreach}
					</ul>
				</div>
				{/if}
				<div class="img-container zoom-wrapper relative rounded-1 overflow-hidden">
					<div id="panzoom-container" class="d-block panzoom-container">
						<img class="img-fluid w-100" src="{$clsISO->getGoogleUrl($more_information.layout_ns)}" />
					</div>
					<div class="zoom-cmd d-flex flex-column">
						<a id="zoom-in" title="Zoom in" class="zoom-in"></a>
						<a id="zoom-out" title="Zoom out" class="zoom-out"></a>
					</div>
					<div class="zoom-map">
						<map name="positionMap" class="positionMapClass">
							<area id="topPositionMap" shape="rect" coords="20,0,40,20" title="move up" alt="move up">
							<area id="leftPositionMap" shape="rect" coords="0,20,20,40" title="move left" alt="move left">
							<area id="rightPositionMap" shape="rect" coords="40,20,60,40" title="move right" alt="move right">
							<area id="bottomPositionMap" shape="rect" coords="20,40,40,60" title="move bottom" alt="move bottom">
						</map>
						<img src="https://myoceancity.vn/application/themes/images/SmartZoom/position.png" usemap="#positionMap">  
					</div>
				</div>
			{else}
				<div class="w-100 overflow-x-auto mb-4">
					<ul class="nav nav-tabs" role="tablist">
						{foreach from=$list_layouts item=_oLayout key=key name=i}
						<li class="nav-item" role="presentation">
							<a class="btn btn_tab rounded-pill{if $smarty.foreach.i.first} active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#tab-layout_{$key}" aria-controls="tab-pane_{$key}" aria-selected="true">{$_oLayout.title}</a>
						</li>
						{/foreach}
					</ul>
				</div>
				<div class="tab-content p-0">
					{foreach from=$list_layouts item=_oLayout key=key name=i}
					<div id="tab-layout_{$key}" class="tab-pane fade{if $smarty.foreach.i.first} active show{/if}">
						<a data-fancybox class="d-block img-container rounded-1 overflow-hidden" data-src="{$clsISO->getGoogleUrl($_oLayout.image)}">
							<img class="img-fluid w-100" src="{$clsISO->getGoogleUrl($_oLayout.image)}" />
						</a>
					</div>
					{/foreach}
				</div>
			{/if}
		</div></div>
		{/if}
		<!-- End Layout -->
		<div class="clearfix"></div>
		<div class="tab_box mb-3">
			<div class="tab_body">
				{if !empty($list_policy)}
				<div class="form-row mb-2">
					{foreach from=$list_policy item=_oDoc key=k_doc name=n_doc}
					<div class="col-12 col-lg-6 col-xxxl-4 mb-2 awe__doc-item">
						<a class="link awe__doc-link overflow-hidden rounded-2" data-fancybox href="{$_oDoc.image}">
							<div class="awe__doc-img">
								<div class="img-background h-px-600" style="background-image:url({$_oDoc.image}),url({$URL_IMAGES}/no-image.png)"></div>
							</div>
							<h4 class="fs-13 text-center line-clamp-2 text-white lh-sm mb-0">
								<span class="limit_2line">{$_oDoc.title}</span>
							</h4>
						</a>
					</div>	
					{/foreach}
				</div>
				{/if}
				{if !empty($list_docs) || !empty($list_policy_docs)}
					<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxxl-5">
					{if !empty($list_docs)}
						{foreach from=$list_docs item=_oDoc key=k_doc name=n_doc}
						{assign var = _list_images value = $_oDoc.list_images}
						{assign var = _more_information value = $_oDoc.more_information}
						<div class="col mb-2 awe__doc-item position-relative{if $smarty.foreach.n_doc.iteration gt '12'} d-none awe__doc-item-hidden{/if}">
							{if $_oDoc.type ne 'video' && $_oDoc.type ne 'youtu.be'}
							<a href="{$_oDoc.link_download}" target="_blank" class="awe__doc-download rounded-pill">
								<i class="bx bx-download"></i></a>{/if}
							<a class="link awe__doc-link overflow-hidden rounded-2" data-preload="false" {if $_oDoc.type eq 'youtu.be'}data-fancybox="{$_oDoc.id}" href="https://www.youtube.com/watch?v={$_more_information.youtu_id}"{elseif $_oDoc.type eq 'google.file'}data-fancybox="{$_oDoc.id}" href="{$_oDoc.link}"{elseif $_oDoc.type eq 'video' || $_oDoc.type eq 'pdf' || $_oDoc.type eq 'user.fh'}href="{$_oDoc.link}" data-type="iframe" data-fancybox="{$_oDoc.id}"{else}href="{$_oDoc.content}" target="_blank" {/if}>
								<div class="awe__doc-img w-100">
									{if $_oDoc.type eq 'video' || $_oDoc.type eq 'youtu.be'}
										<div class="img-play"><i class="bx bx-play"></i></div>
									{/if}
									<div class="img-background" style="background-image:url({$_oDoc.image}),url({$URL_IMAGES}/no-image.png)"></div>
								</div>
								<h4 class="fs-13 text-center line-clamp-2 text-white lh-sm mb-0"><span class="limit_2line">{$_oDoc.title}</span></h4>
							</a>
							{if !empty($_list_images)}
								{foreach from=$_list_images item = _oImage}
								<a class="d-none" data-fancybox="{$_oDoc.id}"{if $_oImage.type eq 'video' || $_oImage.type eq 'pdf'} data-type="iframe"{/if} data-src="{$_oImage.image}"></a>
								{/foreach}
							{/if}
						</div>
						{/foreach}
					{/if}
					{if !empty($list_policy_docs)}
						{foreach from=$list_policy_docs item=_oDoc name=n_doc}
						{assign var = _more_information value = $_oDoc.more_information}
						<div class="col mb-2 awe__doc-item{if $smarty.foreach.n_doc.iteration gt '12'} d-none awe__doc-item-hidden{/if}">
							<a class="link awe__doc-link overflow-hidden rounded-2" target="_blank" href="{$_oDoc.link_ns}">
								<div class="awe__doc-img">
									<div class="img-background" style="background-image:url({$_more_information.image}), url({$URL_IMAGES}/no-image.png)"></div>
								</div>
								<h4 class="fs-13 text-white line-clamp-2 text-center lh-sm mb-0"><span class="limit_2line">{$_oDoc.title} (Áp dụng {$clsISO->convertTimeToText($_oDoc.ms_date)})</span></h4>
							</a>
						</div>	
						{/foreach}
					{/if}
					</div>
					{if $list_docs|@count gt '12'}
					<div class="d-flex justify-content-center">
						<a href="javascript:void(0)" onClick="$Core.home.toggle_item(this, event)" class="btn btn-outline-default px-4 rounded-pill"><i class="bx bx-chevron-down"></i> Xem thêm</a>
					</div>
					{/if}
				{else}
					{if $cat_id eq $smarty.const._PROJECT_DOCS_LAYOUT_CATID 
						&& (empty($list_layouts) && !empty($more_information.layout_ns))}
					<div class="d-flex justify-content-center">
						<div class="text-center p-3">
							<img src="{$URL_IMAGES}/no-data.png" height="200">
							<p class="text-muted mt-n2">Xin lỗi.x Chưa có dữ liệu trong thư mục này!</p>
						</div>
					</div>
					{/if}
					{if $cat_id ne $smarty.const._PROJECT_DOCS_LAYOUT_CATID}
					<div class="d-flex justify-content-center">
						<div class="text-center p-3">
							<img src="{$URL_IMAGES}/no-data.png" height="200">
							<p class="text-muted mt-n2">Xin lỗi.x Chưa có dữ liệu trong thư mục này!</p>
						</div>
					</div>
					{/if}
				{/if}
				{if !empty($list_policy_blocks)}
					{foreach from=$list_policy_blocks item = _oBlock}
					{assign var = list_policy_docs value = $_oBlock.list_policy_docs}
					{if !empty($list_policy_docs)}
					<div class="tab_box mb-2">
						<div class="tab_header d-flex align-items-center justify-content-between mb-3">
							<h3 class="title_box mb-0 fw-semibold">{$_oBlock.title}</h3>
						</div>
						<div class="tab_body">
							<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxxl-5">
								{foreach from=$list_policy_docs item=_oDoc name=n_doc}
								{assign var = _more_information value = $_oDoc.more_information}
								<div class="col mb-2 awe__doc-item{if $smarty.foreach.n_doc.iteration gt '10'} d-none awe__doc-item-hidden{/if}">
									<a class="link awe__doc-link overflow-hidden rounded-2" target="_blank" href="{$_oDoc.link_ns}">
										<div class="awe__doc-img">
											<div class="img-background" style="background-image:url({$_more_information.image}), url({$URL_IMAGES}/no-image.png)"></div>
										</div>
										<h4 class="fs-13 line-clamp-2 text-white text-center lh-sm mb-0">{$_oDoc.title} (Áp dụng {$clsISO->convertTimeToText($_oDoc.ms_date)})</h4>
									</a>
								</div>	
								{/foreach}
							</div>
							{if $_oBlock.total_policy_docs gt '10'}
							<div class="d-flex justify-content-center">
								<a href="javascript:void(0)" onClick="$Core.home.toggle_item(this, event)" class="btn btn-outline-default px-4 rounded-pill"><i class="bx bx-chevron-down"></i> Xem thêm</a>
							</div>
							{/if}
						</div>
					</div>
					{/if}
					{/foreach}
				{/if}
			</div>
		</div>
		<!-- End không có danh mục -->
	{/if}
	</div>
	{/if}
	<div class="clearfix">
		<hr class="my-4" />
	</div>
	{if ($show eq "project" || ($stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE && $show eq 'block')) && !empty($list_blocks)}
	
	<div class="box_block">
		<div class="d-flex align-items-center justify-content-between mb-2" style="padding-right:75px">
			<h3 class="mb-0 fs-5">Phân khu</h3>
			<span class="text-muted">Tổng <strong class="text-main">{$list_blocks|@count}</strong> phân khu</span>
		</div>
		<div class="owl_carousel owl_block owl-carousel">
			{foreach name=i from=$list_blocks item = _oBlock}
			{assign var = _more_information value = $_oBlock.more_information}
			<div class="card item_block">
				<div class="card-body bg-white h-100 d-block d-flex flex-column justify-content-between border rounded-2">
					<div class="gtouKtNtBW">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<div class="item_top d-flex flex-column">
								<span class="text-muted fs-11">Phân khu</span>
								<a href="{$clsProject->getLinkDetail($project_id,$_oBlock.property_id,0,$oneProject)}" class="fs-16 fw-semibold text-dark">{$_oBlock.title}</a>
							</div>
							{if !empty($_more_information.on_sale)}
							<span class="on_sale badge bg-success">Mở bán</span>
							{/if}
						</div>
						<div class="box_scale rounded-2 overflow-hidden mb-2">
							<a href="{$clsProject->getLinkDetail($project_id,$_oBlock.property_id,0,$oneProject)}">
								<img src="{$_oBlock.image}" class="w-100" width="200" height="140" onerror="this.src='{$URL_IMAGES}/no-image.png'">
							</a>
						</div>
						<div class="item_top d-flex flex-column mb-2">
							<span class="text-muted fs-11">Phong cách xây dựng</span>
							<span class="fs-14 text-dark">
								{if !empty($_more_information.construction_style)}
									{$_more_information.construction_style}
								{else} Đang cập nhật{/if}
							</span>
						</div>
						<div class="item_top d-flex flex-column mb-2">
							<span class="text-muted fs-11">Giá bán</span>
							<span class="fs-14 text-dark">
								{if !empty($_more_information.price_range)}
									{$_more_information.price_range}
								{else} Đang cập nhật{/if}
							</span>
						</div>
						<div class="item_top d-flex flex-column mb-2">
							<span class="text-muted fs-11">Loại hình</span>
							{if !empty($_more_information.construction_type)}
								{foreach from=$_more_information.construction_type item = _item}
								<span class="fs-14 text-dark">
									<i class="bx bx-check text-muted"></i>{$_item}
								</span>
								{/foreach}
							{else}
								<span class="fs-14 text-dark">Đang cập nhật</span>
							{/if}
						</div>
					</div>
					<div class="d-flex mt-2">
						<a href="{$clsProject->getLinkDetail($project_id, $_oBlock.property_id,0,$oneProject)}" class="btn btn-block btn-outline-default rounded-1 text-dark bg-white">Xem chi tiết</a>
					</div>
				</div>
			</div>
			{/foreach}
		</div>
	</div>
	{else if $show eq 'block' || $show eq 'building'}
		{if !empty($list_buildings)}
		<div class="box_building mb-4">
			<div class="d-flex align-items-center justify-content-between mb-2" style="padding-right:75px">
				<h3 class="mb-0 fs-5">Toà nhà</h3>
				<span class="text-muted">Tổng <strong class="text-main">{$list_buildings|@count}</strong> tòa</span>
			</div>
			<div class="owl_carousel owl_building owl-carousel rounded-2">
				{foreach name=i from=$list_buildings item = _oBuilding}
				{assign var = _more_information value = $_oBuilding.more_information}
				<div class="card item_building">
					<a class="card-body bg-white h-100 d-flex flex-column justify-content-between border rounded-2" 
						href="{$clsProject->getLinkInfo($project_id,$block_id, $_oBuilding.property_id,$smarty.const._PROJECT_DOCS_LAYOUT_CATID)}">
						<div class="gtouKtNtBW">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<div class="item_top d-flex flex-column">
									<span class="text-muted fs-11">Tòa</span>
									<span class="fs-16 fw-semibold text-dark">{$_oBuilding.title}</span>
								</div>
								{if !empty($_more_information.on_sale)}
								<span class="on_sale badge bg-success">Mở bán</span>
								{/if}
							</div>
							<div class="box_scale rounded-2 overflow-hidden mb-2">
								<img src="{$_oBuilding.image}" class="w-100" width="200" height="140" onerror="this.src='{$URL_IMAGES}/no-image.png'">
							</div>
							<div class="item_top d-flex flex-column mb-2">
								<span class="text-muted fs-11">Tầng</span>
								<span class="fs-14 text-dark">{$_more_information.number_floor} tầng</span>
							</div>
							<div class="item_top d-flex flex-column mb-2">
								<span class="text-muted fs-11">Căn hộ/tầng</span>
								<span class="fs-14 text-dark">{$_more_information.number_house} căn hộ</span>
							</div>
							<div class="item_top d-flex flex-column mb-2">
								<span class="text-muted fs-11">Bàn giao</span>
								<span class="fs-14 text-dark">
									{if !empty($_more_information.handover_time)}
										{$_more_information.handover_time}
									{else}
										Đang cập nhật
									{/if}
								</span>
							</div>
							<div class="item_top d-flex flex-column mb-2">
								<span class="text-muted fs-11">Phong cách xây dựng</span>
								<span class="fs-14 text-dark">
									{if !empty($_more_information.construction_style)}
										{$_more_information.construction_style}
									{else}
										Đang cập nhật
									{/if}
								</span>
							</div>
							<div class="item_top d-flex flex-column mb-2">
								<span class="text-muted fs-11">Giá bán</span>
								<span class="fs-14 text-dark">
									{if !empty($_more_information.price_range)}
										{$_more_information.price_range}
									{else}
										Đang cập nhật
									{/if}
								</span>
							</div>
						</div>
						<div class="mt-2">
							<div class="btn btn-block btn-outline-default rounded-1text-dark bg-white">Xem chi tiết</div>
						</div>
					</a>
				</div>
				{/foreach}
			</div>
		</div>
		{/if}
	{/if}
	<!-- End Block -->
</div>
{literal}
<script type="text/javascript">
	const elem = document.getElementById('panzoom-container');
	const zoomInButton = document.getElementById('zoom-in');
	const zoomOutButton = document.getElementById('zoom-out');
	//const resetButton = document.getElementById('reset');
	if(elem && elem.innerHTML.length){
		const panzoom = Panzoom(elem, {
			animate : true,
			canvas : true,
			minScale: 0.75
		});
		const parent = elem.parentElement;
		// No function bind needed
		parent.addEventListener('wheel', panzoom.zoomWithWheel);
		zoomInButton.addEventListener('click', panzoom.zoomIn);
		zoomOutButton.addEventListener('click', panzoom.zoomOut);
		// resetButton.addEventListener('click', panzoom.reset);
	}
	$(function(){
		if(!$Core.util.isEmpty(location.hash)){
			const _hash = location.hash;
			clearTimeout(_timeOut);
			_timeOut = setTimeout(() => {
				// $(`a#tabclick_${_hash}`).trigger('click');
				$(`#project_tab a[href="${_hash}"]`).tab('show');
			}, 100);
		}
		$('[data-fancybox]').fancybox({
			buttons : ['zoom','download','close'],
			afterShow : function(instance, current) {
				var _src = current.src;
				if(_src.indexOf('drive.google.com') >= 0){
					var _gid = _src.split(/id=(.*)\&sz=(.*)/)[1],
						_url = `https://drive.usercontent.google.com/download?id=${_gid}&export=download&authuser=0`;
				} else {
					_url = $.trim(_src);
				}
				$("[data-fancybox-download]").attr('href', _url);
				let _timeout;
				$_document.on('touchstart mousedown', '.fancybox-image', (ev) => {
					_timeout = setTimeout(() => {
						var _link = document.createElement("a"),
							_src = $(this).attr('src');
						if(_src.indexOf('drive.google.com') >= 0){
							var _gid = _src.split(/id=(.*)\&sz=(.*)/)[1],
								_src = `https://drive.usercontent.google.com/download?id=${_gid}&export=download&authuser=0`;
						} else {
							_src = $.trim(_src);
						}
						_link.href = _src;
						_link.download = "";
						_link.click();
					}, 2000);
				});
				$_document.on('touchend mouseup mouseleave', '.fancybox-image', (ev) => {
					clearTimeout(_timeout);
				});
			}, beforeShow: function(instance, current){}
		});
	});
	function scrollDown(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			topM = $('.'+toId).offset().top;
		$('html,body').animate({scrollTop:topM}, 500);
		return false;
	}
</script>
{/literal}
