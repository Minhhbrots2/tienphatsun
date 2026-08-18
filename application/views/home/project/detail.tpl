<div class="container-xxl flex-grow-1 pt-2 container-p-y"> 
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item">
				<a href="{$PCMS_URL}/thong-tin/">Thông tin dự án</a>
			</li>
			<li class="breadcrumb-item">
				<a href="{$clsProject->getLinkDetail($project_id,0,0,$oneProject)}">{$clsProject->getCode($project_id,$oneProject)}</a>
			</li>
			{if $show eq 'block'}
				<li class="breadcrumb-item active">{$oneBlock.title}</li>
			{elseif $show eq 'building' || $show eq 'map'}
				{if !empty($block_id)}
					<li class="breadcrumb-item">
						<a href="{$clsProject->getLinkDetail($project_id,$oneBlock.property_id,0,$oneProject)}">{$oneBlock.title}</a>
					</li>
				{/if}
				{if !empty($building_id)}
				<li class="breadcrumb-item active">{$oneBuilding.title}</li>
				{/if}
			{/if}
		</ol>
	</nav>
	<div class="clearfix"></div>
	{$core->getBlock("banner_stock", ['total_stock' => $total_stock])}
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
	 {if $show eq 'map'}
		<!-- Map -->
			{include file="./../../map/project.tpl" infoLocationAround=$infoLocationAround location=$location project=$project}
		<!-- end Map -->
	{else}
	<div class="row justify-content-center">
		<div class="col-12 col-lg-8 col-xxl-9">
			{if $cat_id eq $smarty.const._PROJECT_PROGRESS_CATID}
				<div class="tien-do-wrap p-3 card no-shadow">
					{if !empty($list_progress)}
					<div class="pt-wrap">
						<!-- Cột trái: timeline tháng (mở ra ngày nếu mốc tiến độ có ngày d/m/Y) -->
						<div class="pt-timeline">
							<div class="pt-timeline-head"><i class="bx bx-loader-circle"></i> {if $show eq 'project'}TIẾN ĐỘ DỰ ÁN{else}TIẾN ĐỘ{/if}</div>
							<ul class="pt-list">
								{foreach from=$list_progress item=_m name=mo}
								{assign var=_days value=$_m.days}
								<li class="pt-group">
									<div class="pt-item{if $smarty.foreach.mo.first} active{if $_days|@count > 1} open{/if}{/if}" data-pi="{$_days[0].pi}">
										<span class="pt-item-label">{$_m.month_label}</span>
										{if $_days|@count > 1}<span class="pt-item-meta">{$_days|@count} đợt cập nhật</span><i class="bx pt-chev {if $smarty.foreach.mo.first}bx-chevron-up{else}bx-chevron-down{/if}"></i>{elseif $_days[0].media|@count > 1}<span class="pt-item-meta">{$_days[0].media|@count} hình ảnh/video</span>{/if}
									</div>
									{if $_days|@count > 1}
									<div class="pt-subwrap{if !$smarty.foreach.mo.first} d-none{/if}">
										{foreach from=$_days item=_d name=dy}
										<div class="pt-sub{if $smarty.foreach.mo.first && $smarty.foreach.dy.first} active{/if}" data-pi="{$_d.pi}"><i class="bx bx-calendar-check"></i><span class="pt-sub-t">{if $_d.day_label}{$_d.day_label}{else}Trong tháng{/if}</span></div>
										{/foreach}
									</div>
									{/if}
								</li>
								{/foreach}
							</ul>
						</div>
						<!-- Cột phải: viewer + lưới ảnh (mỗi ngày 1 panel) -->
						<div class="pt-main">
							{foreach from=$list_progress item=_m name=mo}
							{foreach from=$_m.days item=_d name=dy}
							<div class="td-panel{if !($smarty.foreach.mo.first && $smarty.foreach.dy.first)} d-none{/if}" data-pi="{$_d.pi}">
								<div class="pt-viewer-caption mb-3">
									<strong class="title_timeline position-relative pb-2">Tiến độ {$_m.month_label|lower}</strong>
									{if $_d.day_label}<span class="pt-date"><i class="bx bx-time-five"></i> Cập nhật {$_d.day_label}</span>{/if}
								</div>
								{if !empty($_d.video)}
								<div class="pt-viewer"><iframe src="{$_d.video.embed_url}" frameborder="0" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe></div>
								{elseif empty($_d.media)}
								<div class="pt-viewer pt-viewer-empty"><i class="bx bx-image-alt"></i><span>Chưa có hình ảnh/video</span></div>
								{/if}
								{if !empty($_d.media)}
								<div class="pt-grid-head">Hình ảnh tiến độ</div>
								<div class="pt-grid">
									{foreach from=$_d.media item=_md}
									<div class="pt-cell{if $_md.kind eq 'video'} pt-cell-video{elseif $_md.kind eq 'document'} pt-cell-doc{/if}">
										<a data-fancybox="td{$_d.pi}" data-type="{$_md.fb_type}" href="{$_md.fb_src}">
											{if $_md.thumb}<img src="{$_md.thumb}" alt="{$_md.name}" onerror="this.src='{$URL_IMAGES}/no-image.png'">{else}<span class="pt-noimg"><i class="bx {if $_md.kind eq 'document'}bxs-file-pdf{else}bxs-image{/if}"></i></span>{/if}
											{if $_md.kind eq 'video'}<span class="pt-play"><i class="bx bx-play"></i></span>{/if}
											{if $_md.kind eq 'document' && $_md.thumb}<span class="pt-doc"><i class="bx bxs-file-pdf"></i></span>{/if}
										</a>
									</div>
									{/foreach}
								</div>
								{/if}
							</div>
							{/foreach}
							{/foreach}
						</div>
					</div>
					{else}
					<div class="d-flex justify-content-center"><div class="text-center p-4"><img src="{$URL_IMAGES}/no-data.png" height="200"><p class="text-muted mt-n2">Chưa có dữ liệu tiến độ.</p></div></div>
					{/if}
				</div>
			{elseif $cat_id eq $smarty.const._PROJECT_DOCS_UTILITY_CATID}
				{if !empty($list_utilities)}
					<div id="box_tab" class="box_tab mb-4">
						<div class="nav-align-top nav-tabs-shadow">
							<div class="w-100 overflow-x-auto mb-4">
								<ul class="nav nav-tabs" id="project_tab" role="tablist">
									{foreach from=$list_utilities item=_oCat name=name_cat}	
										<li class="nav-item" role="presentation">
											<a onClick="$Core.project.tab_click(this, event)" href="#{$core->replaceSpace($_oCat.title)}" uri="{$curl}" slug="{$core->replaceSpace($_oCat.title)}" id="tabclick_{$_oCat.property_id}" class="btn btn_tab rounded-pill{if $smarty.foreach.name_cat.first} active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#utilities_{$_oCat.property_id}" aria-controls="tab-pane_{$_oCat.property_id}" aria-selected="true">{$_oCat.title}</a>
										</li>
									{/foreach}
								</ul>
							</div>
						</div>
						<div class="tab-content p-0">
							{foreach from=$list_utilities item=_oCat name=name_cat}	
								{assign var = _utilities value = $_oCat.utilities}
								<div id="utilities_{$_oCat.property_id}" class="tab-pane fade{if $smarty.foreach.name_cat.first} active show{/if}">
									<div class="tab_box mb-3">
										<div class="tab_body">
											<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4">
												{if !empty($_utilities)} 
													{foreach from=$_utilities item=_oUtilities name=i}
														{if !empty($_oUtilities.image)}
															<div class="col mb-2 awe__doc-item">
																<a class="link awe__doc-link overflow-hidden rounded-2 position-relative" data-fancybox="utilities" data-src="{$_oUtilities.image}" data-caption="<h3 class='fs-20 mb-2'>{$_oUtilities.title}</h3> {$_oUtilities.content|html_entity_decode}">
																	<div class="awe__doc-img position-relative">
																		<div class="img-background" style="background-image:url('{$_oUtilities.image}'), url('{$URL_IMAGES}/no-image.png')"></div>
																	</div>
																	<div class="position-absolute content_utilities text-white w-100 h-100">
																		<h4 class="fs-6 fw-bold line-clamp-2  lh-sm mb-2"><span class="limit_2line">{$_oUtilities.title}</span></h4>
																		<div class="awe__utilities-text limit_4line">{$_oUtilities.content|html_entity_decode}</div>
																	</div>
																</a>
															</div>
														{/if}
													{/foreach}
												{else}
													<div class="d-flex justify-content-center">
														<div class="text-center p-4">
															<img src="{$URL_IMAGES}/no-data.png" height="200">
															<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
														</div>
													</div>
												{/if}
											</div>
										</div>
									</div>
								</div>
							{/foreach}
						</div>					
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
						<div class="w-100 overflow-x-auto mb-3">
							<ul class="nav nav-tabs" id="project_tab" role="tablist">
								{foreach from=$list_childs item=_oChild key=key name=i}
								{if !empty($_oChild.list_docs) || ($_oChild.property_id eq $smarty.const._PROJECT_DOCS_PR_CATID and !empty($_oChild.list_posts))}
								<li class="nav-item" role="presentation">
									<a onClick="$Core.project.tab_click(this, event)" href="#{$_oChild.slug}" uri="{$curl}" slug="{$_oChild.slug}" id="tabclick_{$_oChild.slug}" class="btn btn_tab rounded-pill{if $_oChild.is_active eq '1'} active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#{$_oChild.slug}" aria-controls="tab-pane_{$_oChild.property_id}" aria-selected="true">{$_oChild.title}</a>
								</li>
								{/if}
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
									<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4">
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
										<div class="col mb-2">
											{assign var = oneItem value = $_oDoc}
											{$core->getBlock('item_doc', ['_type'=>"detail",'oneItem' => $oneItem])}
										</div>
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
								</div></div>
							</div>
							{/foreach}
						</div>
					</div>
				{else}
				<!-- Không có danh mục -->
				<!-- Layout -->
				{if $cat_id eq $smarty.const._PROJECT_DOCS_LAYOUT_CATID}
				<div class="tab_box mb-4">
					<div class="nav-align-top nav-tabs-shadow">
						{if $show eq 'project' }
							<div class="w-100 overflow-x-auto hide-scroll-thumb mb-3">
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
							{elseif $more_information.is_tiles eq '1'}
 								{$core->getBlock('project_map', ['more_information' => $more_information])}
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
					</div>
				</div>
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
							{if $view_doc eq "grid"}
							<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4">
								{if !empty($list_docs)}
									{foreach from=$list_docs item=_oDoc key=k_doc name=n_doc}
									<div class="col mb-2">
										{assign var = oneItem value = $_oDoc}
										{$core->getBlock('item_doc', ['_type'=>"detail",'oneItem' => $oneItem])}
									</div>
									{/foreach}
								{/if}
								{if !empty($list_policy_docs)}
									{foreach from=$list_policy_docs item=_oDoc name=n_doc}
									{assign var = _more_information value = $_oDoc.more_information}
									<div class="col mb-2 awe__doc-item">
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
							{else}
								{if !empty($list_docs)}
									<div class="d-flex align-items-center justify-content-between py-3 mb-2">
										<h3 class="mb-0 fs-5 text-dark">{$oneCat.title} {$oneBlock.title}</h3>
									</div>
									<div class="lst_docs form-row">
										{foreach from=$list_docs item=_oDoc key=k_doc name=n_doc}
											{assign var = _list_images value = $_oDoc.list_images}
											{assign var = _more_information value = $_oDoc.more_information}
											<div class="col-12 col-xxl-6 mb-2">
												<div class="border rounded-2 bg-white item_doc d-flex h-100 {if $deviceType eq 'phone'}py-2{else}p-3{/if}" >
													<div class="img_item_doc p-2 position-relative">
														<a href="{$_oDoc.content}" target="_blank"><img src="{$_oDoc.image}" alt="" onerror="this.src='{$URL_IMAGES}/no-image.png'" width="100" height="120" class="w-100 h-100 object-fit-cover position-relative zindex-1 rounded-2"></a>
													</div>
													<div class="content_item_doc flex-fill {if $deviceType ne 'phone'}py-2{/if} px-3 d-flex {if $deviceType eq 'phone'} gap-1 {else} gap-3{/if} position-relative flex-column align-items-end justify-content-center">
														<div class="content1 w-100">
															<h3 class="title_item_doc mb-2 {if $deviceType eq 'phone'}fs-16{/if}"><a href="{$_oDoc.content}" target="_blank" class="limit_2line text-dark">{$_oDoc.title}</a></h3>
															<div class="intro {if $deviceType eq 'phone'}fs-13 limit_2line{else}limit_3line{/if}">{$_more_information.intro|html_entity_decode}</div>
														</div>
														<a href="{$_oDoc.content}" target="_blank" class="text-link text-decoration-underline text-nowrap">Xem tài liệu</a>
													</div>
												</div>
											</div>
										{/foreach}
									</div>
								{/if}
							{/if}
						{else}
							{if $cat_id eq $smarty.const._PROJECT_DOCS_LAYOUT_CATID 
								&& (empty($list_layouts) && !empty($more_information.layout_ns))}
							<div class="d-flex justify-content-center">
								<div class="text-center p-3">
									<img src="{$URL_IMAGES}/no-data.png" height="200">
									<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
								</div>
							</div>
							{/if}
							{if $cat_id ne $smarty.const._PROJECT_DOCS_LAYOUT_CATID}
							<div class="d-flex justify-content-center">
								<div class="text-center p-3">
									<img src="{$URL_IMAGES}/no-data.png" height="200">
									<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
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
									<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4">
										{foreach from=$list_policy_docs item=_oDoc name=n_doc}
										{assign var = _more_information value = $_oDoc.more_information}
										<div class="col mb-2 awe__doc-item">
											<a class="link awe__doc-link overflow-hidden rounded-2" target="_blank" href="{$_oDoc.link_ns}">
												<div class="awe__doc-img">
													<div class="img-background" style="background-image:url({$_more_information.image}), url({$URL_IMAGES}/no-image.png)"></div>
												</div>
												<h4 class="fs-13 line-clamp-2 text-white text-center lh-sm mb-0">{$_oDoc.title} (Áp dụng {$clsISO->convertTimeToText($_oDoc.ms_date)})</h4>
											</a>
										</div>	
										{/foreach}
									</div>
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
		</div>
		<div class="col-12 col-lg-4 col-xxl-3">			
			{if ($show eq "project" || ($stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE && $show eq 'block')) && !empty($list_blocks)}
			<div class="box_block no-shadow position-sticky " style="top:60px">
				<div class="d-flex align-items-center justify-content-between py-3">
					<h3 class="mb-0 fs-5 text-dark">Phân khu</h3>
				</div>
				<div class="list_item">
					{foreach name=i from=$list_blocks item = _oBlock}
					{assign var = _more_information value = $_oBlock.more_information}
						<a href="{$clsProject->getLinkDetail($project_id,$_oBlock.property_id,0,$oneProject)}" title="{$_oBlock.title}" class="d-flex align-items-center gap-2 text-dark mb-2 border rounded-2 p-2 item_sidebar">
							<div class="box_image rounded-2 overflow-hidden">
								<img src="{$clsISO->getImageWH($_oBlock.image,40,40)}" class="w-100 h-100 img-cover" width="40" height="40" onerror="this.src='{$URL_IMAGES}/no-image.png'">
							</div>
							<div class="box_content w-60 px-2 flex-fill">
								<h3 class="title_item mb-0 fs-18">{$_oBlock.title}</h3>
								<div class="d-flex align-items-center gap-1 fs-12">
									<span class="text-muted">Loại hình:</span> <span class="">{if $_oBlock.parent_id eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}Cao tầng{else}Thấp tầng{/if}</span>
								</div>
							</div>
						</a>
					{/foreach}
				</div>
			</div>
			{else if $show eq 'block' || $show eq 'building'}
				{if !empty($list_buildings)}
				<div class="box_building no-shadow position-sticky p-3 card no-shadow" style="top:60px">
					<div class="d-flex align-items-center justify-content-between py-3">
						<h3 class="mb-0 fs-5 text-dark">Tòa nhà ({$oneBlock.title})</h3>
					</div>
					<div class="list_item">
						{foreach name=i from=$list_buildings item = _oBuilding}
							<a href="{$clsProject->getLinkInfo($project_id,$block_id, $_oBuilding.property_id,$cat_id)}" title="{$_oBuilding.title}" class="d-flex align-items-center gap-2 text-dark mb-2 border rounded-2 p-2 item_sidebar">
								<div class="box_image rounded-2 overflow-hidden">
									<img src="{$clsISO->getImageWH($_oBuilding.image,40,40)}" class="w-100 h-100 img-cover" width="40" height="40" onerror="this.src='{$URL_IMAGES}/no-image.png'">
								</div>
								<div class="box_content w-60 px-2 flex-fill">
									<h3 class="title_item mb-0 fs-18">Tòa {$_oBuilding.title}</h3>
								</div>
							</a>
						{/foreach}
					</div>
					{if !empty($list_blocks)}
						<div class="d-flex align-items-center justify-content-between py-3">
							<h3 class="mb-0 fs-5 text-dark">Phân khu khác</h3>
						</div>
						<div class="list_item">
							{foreach name=i from=$list_blocks item = _oBlock}
								<a href="{$clsProject->getLinkDetail($project_id,$_oBlock.property_id,0,$oneProject)}" title="{$_oBlock.title}" class="d-flex align-items-center gap-2 text-dark mb-2 border rounded-2 p-2 item_sidebar">
									<div class="box_image rounded-2 overflow-hidden">
										<img src="{$clsISO->getImageWH($_oBlock.image,40,40)}" class="w-100 h-100 img-cover" width="40" height="40" onerror="this.src='{$URL_IMAGES}/no-image.png'">
									</div>
									<div class="box_content w-60 px-2 flex-fill">
										<h3 class="title_item mb-0 fs-18">{$_oBlock.title}</h3>
										<div class="d-flex align-items-center gap-1 fs-12">
											<span class="text-muted">Loại hình:</span> <span class="">{if $_oBlock.parent_id eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}Cao tầng{else}Thấp tầng{/if}</span>
										</div>
									</div>
								</a>
							{/foreach}
						</div>
					{/if}
				</div>
				{/if}
				
			{/if}
			<!-- End Block -->
		</div>
	</div>
	{/if}
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
				/*$_document.on('touchstart mousedown', '.fancybox-image', (ev) => {
					 if (ev.type === "touchstart" && ev.touches && ev.touches.length > 1) {
						return; 
					}
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
				});*/
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