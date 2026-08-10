{if $type eq "overview"}
<section class="section_banner banner_page d-flex justify-content-center mb-2 position-relative" style="background-image:url('{$oneProject.image}')">
	<div class="container_project mx-auto d-flex flex-column justify-content-between"> 
		<div class="top_content_banner d-flex justify-content-between align-items-center w-100  zindex-1">
			<a href="/thong-tin/" class="btn btn_back rounded-pill text-white fs-24"></a>
			<a class="btn btn-info rounded-pill py-1 px-3 {if $deviceType eq 'phone'}fs-11{else}fs-16{/if}" href="{$clsProject->getLinkDetail($project_id,0,0,'detail',$oneProject)}">Chi tiết</a>
		</div>
		<div class="content_banner d-flex flex-column justify-content-between align-items-start  w-100 text-white zindex-1">
			<div class="mb-3">
				<h1 class="label_title fw-semibold mb-2 fs-2">Toàn dự án</h1>
				<h2 class="title_project fw-normal mb-0  {if $deviceType eq 'phone'}fs-16{else}fs-24{/if}">{$oneProject.title}</h1>
			</div>
			<div class="d-flex w-100 {if $deviceType eq 'phone'}gap-2{else}gap-4{/if}">
				<a class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-3 rounded-3 flex-fill text-white" href="/hoc-tap.html">
					<i class='bx bx-book-open mb-2'></i>
					<span class="txt_option">Tài liệu</span>
				</a>
				{if $project_id eq $smarty.const._PROJECT_DEF_ID}
					<a class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-3 rounded-3 flex-fill text-white" href="/tool.html">
						<i class='bx bx-grid-alt mb-2'></i>
						<span class="txt_option">Quỹ căn</span>
					</a>
				{else}
					<a class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-3 rounded-3 flex-fill text-white" href="/project/p{$project_id}.html">
						<i class='bx bx-grid-alt mb-2'></i>
						<span class="txt_option">Quỹ căn</span>
					</a>
				{/if}
				<a class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-3 rounded-3 flex-fill text-white" href="">
					<i class='bx bx-square mb-2'></i>
					<span class="txt_option">Mặt bằng</span>
				</a>
				{if !empty($information_project.vr_link)}
					<a class="item_option d-flex flex-column align-items-center {if $deviceType eq 'phone'}py-2{else}py-4{/if} px-3 rounded-3 flex-fill text-white"  data-fancybox="vr360" data-src="{$information_project.vr_link}" data-type="iframe">
						<i class='bx bx-analyse mb-2'></i>
						<span class="txt_option">360 độ</span>
					</a>
				{/if}
			</div>
		</div>
	</div>
</section>
{else if $type eq 'detail'}
<section class="section_banner detail banner_page d-flex justify-content-center mb-2 position-relative" style="background-image:url('{$oneProject.image}')">
	<div class="container_project mx-auto d-flex flex-column justify-content-between"> 
		<div class="top_content_banner d-flex justify-content-between align-items-center zindex-1">
			{if $show eq "block"}
				<a href="{$clsProject->getLinkDetail($project_id,0,0,'detail',$oneProject)}" class="btn btn_back rounded-pill text-white fs-24"></a>
			{else if $show eq "building"}
				<a href="{$clsProject->getLinkDetail($project_id,$block_id,0,'detail',$oneProject)}" class="btn btn_back rounded-pill text-white fs-24"></a>
			{else}
				<a href="{$clsProject->getLinkDetail($project_id,0,0,'overview',$oneProject)}" class="btn btn_back rounded-pill text-white fs-24"></a>
			{/if}
		</div>
		<div class="content_banner d-flex justify-content-between align-items-center text-white zindex-1">
			<div class="">
				{if $show eq "block"}
					<h1 class="label_title fw-semibold mb-2 fs-2">Thông tin phân khu</h1>
					<h2 class="title_project fw-normal text-upper mb-0 {if $deviceType eq 'phone'}fs-16{else}fs-24{/if}">{$oneBlock.title}</h1>
				{else if $show eq "building"}
					<h1 class="label_title fw-semibold mb-2 fs-2">Thông tin tòa</h1>
					<h2 class="title_project fw-normal text-upper mb-0 fs-16">{$oneBuilding.title}</h1>
				{else}
					<h1 class="label_title fw-semibold mb-2 fs-2">Toàn dự án</h1>
					<h2 class="title_project fw-normal text-upper mb-0 fs-16">{$oneProject.title}</h1>
				{/if}
			</div>
			<span class="btn btn-info rounded-pill py-1 px-4 {if $deviceType eq 'phone'}fs-11{else}fs-16{/if}">Mở bán</span>
		</div>
	</div>
</section>
{/if}
<div class="container-xxl flex-grow-1 pt-2 container-p-y container_project"> 
	<div class="row">
			{if $type eq "overview"}
			<div class="col-12">
				<div class="box_info {if $deviceType eq 'phone'}mb-3{else}mb-4{/if}">
					<div class="info_detail d-flex justify-content-between align-items-center text-dark py-2 mb-1">
						<h3 class="title_box mb-0 fs-6 fw-bold text-blue">Kênh truyền thông</h3>
					</div>
					{if $deviceType eq "phone"}
						<div class="box_socical d-flex flex-wrap gap-2">
							<a href="https://www.facebook.com/futurehomesvn/?ref=embed_page" class="item_socical item_facebook btn p-2 rounded-3 text-dark d-flex justify-content-start align-items-center" target="_blank">
								<i class='bx bxl-facebook-circle me-1' ></i>
								<span class="fs-14 ">Fanpage</span>
							</a>
							<a href="https://futurehomes.vn/" class="item_socical btn p-2 rounded-3 text-dark d-flex justify-content-start align-items-center" target="_blank">
								<i class='bx bx-world me-1 text-muted'></i>
								<span class="fs-14 ">Website</span>
							</a>
							<a href="https://www.youtube.com/@FutureWay." class="item_socical item_youtube btn p-2 rounded-3 text-dark d-flex justify-content-start align-items-center" target="_blank">
								<span class="icon_youtube me-1"><i class='bx bxl-youtube fs-10 text-white' ></i></span>
								<span class="fs-14 ">Youtube</span>
							</a>
						</div>
					{else}
						<div class="box_socical d-flex flex-wrap gap-3">
							<a href="https://www.facebook.com/futurehomesvn/?ref=embed_page" class="item_socical item_facebook btn rounded-3 text-dark d-flex justify-content-start align-items-center btn-lg" target="_blank">
								<i class='bx bxl-facebook-circle me-1 fs-24' ></i>
								<span class="">Fanpage</span>
							</a>
							<a href="https://futurehomes.vn/" class="item_socical btn rounded-3 text-dark d-flex justify-content-start align-items-center" target="_blank">
								<i class='bx bx-world me-1 text-muted fs-24'></i>
								<span class=" ">Website</span>
							</a>
							<a href="https://www.youtube.com/@FutureWay." class="item_socical item_youtube btn rounded-3 text-dark d-flex justify-content-start align-items-center" target="_blank">
								<span class="icon_youtube me-1 fs-24"><i class='bx bxl-youtube fs-14 text-white' ></i></span>
								<span class=" ">Youtube</span>
							</a>
						</div>
					{/if}
				</div>
				<div class="box_block mb-4">
					<div class="d-flex align-items-center justify-content-between mb-3">
						<h3 class="title_box mb-0 fs-16 text-dark fw-semibold">Phân khu</h3>
					</div>
					{if $deviceType eq "phone"}
					<div class="overflow-x-auto">
						<div class="d-flex gap-2">
							{foreach name=i from=$list_blocks item = _oBlock}
								<a class="item_block d-flex flex-column align-items-center gap-1" href="{$clsProject->getLinkDetail($project_id, $_oBlock.property_id,0,'detail',$oneProject)}">
									<img src="https://drive.google.com/thumbnail?id=1T4N_7FZtb_2mHQoJwIewBVA42Um7GuMr&sz=w1000" alt="" class="rounded-2" width="200" height="150">
									<span class="fs-14 fw-bold text-dark text-upper text-center">{$_oBlock.title}</span>
								</a>
							{/foreach}
						</div>
					</div>
					{else}
						<div class="lst_carousel owl_block owl-carousel border rounded-2 overflow-hidden">
							{foreach name=i from=$list_blocks item = _oBlock}
								<div class="item_block {cycle values='bg-white,bg-lighter'}">
									<div class="card-body">
										<div class="item_top d-flex flex-column mb-2">
											<span class="text-muted fs-11">Phân khu</span>
											<a href="{$clsProject->getLinkDetail($project_id, $_oBlock.property_id,0,'detail',$oneProject)}" class="fs-16 fw-semibold text-dark">{$_oBlock.title} <i class='bx bx-link-external'></i></a>
										</div>
										<div class="box_scale rounded-2 overflow-hidden mb-2">
											<img src="https://drive.google.com/thumbnail?id=1T4N_7FZtb_2mHQoJwIewBVA42Um7GuMr&sz=w1000" alt="" class=" w-100" width="200" height="120">
										</div>
										<a href="{$clsProject->getLinkDetail($project_id, $_oBlock.property_id,0,'detail',$oneProject)}" class="d-block">
											<div class="item_top d-flex flex-column mb-2">
												<span class="text-muted fs-11">Dự án</span>
												<span class="fs-14 text-dark">{$oneProject.title}</span>
											</div>
											<div class="item_top d-flex flex-column mb-2">
												<span class="text-muted fs-11">Loại hình</span>
												<span class="fs-14 text-dark">{$_oBlock.block_type}</span>
											</div>
										</a>
									</div>
								</div>
							{/foreach}
						</div>
					{/if}
				</div>
				<div class="box_info {if $deviceType eq 'phone'}mb-3{else}mb-4{/if}">
					<div class="d-flex align-items-center justify-content-between mb-3">
						<h3 class="title_box mb-0 fs-6 text-dark fw-bold">Tài liệu truyền thông</h3>
						<a href="{$clsProject->getLinkDetail($project_id,$block_id,$building_id,'detail',$oneProject)}" class="txt_view_all text-dark d-none">Tất cả<i class='bx bx-chevron-right me-2' ></i></a>
					</div>
					<div class="owl_document owl-carousel">
						{if $deviceType eq "phone"}
							{foreach from=$publication_docs item=_itemPublication}
								{if !empty($_itemPublication.lst_image)}
									<div class="">
										{assign var=gid value=$clsISO->getUniqid()}
										<div class="text-dark d-flex flex-column align-items-center" onClick="$Core.projects.viewAllDocs(this,event)" toId="{$gid}">
											<img class="rounded-3 mb-2" src="{$_itemPublication.image}" alt="{$_itemPublication.title}" width="50" height="50">
											<span class="fs-12 text-upper text-center fw-semibold">{$_itemPublication.title}</span>
										</div>
										<div class="d-none">
											{foreach from=$_itemPublication.lst_image item=image name=i_img}
												<span class="item_fancy" data-fancybox="gallery_{$_itemPublication.property_id}" data-src="{$image}" {if $smarty.foreach.i_img.first}id="{$gid}"{/if}>
													<img src="{$image}" alt="">
												</span>
											{/foreach}
										</div>	
									</div>									
								{/if}
							{/foreach}
						{else}
							{foreach from=$publication_docs item=_itemPublication}
								<a href="" class="text-dark d-flex flex-column align-items-center">
									<img class="rounded-3 mb-2" src="{$_itemPublication.image}" alt="{$_itemPublication.title}" width="80" height="80">
									<span class="fs-16 text-upper text-center fw-semibold">{$_itemPublication.title}</span>
								</a>
							{/foreach}
						{/if}
					</div>
				</div>
				<div class="box_info {if $deviceType eq 'phone'}mb-3{else}mb-4{/if}">
					<div class="d-flex align-items-center justify-content-between mb-3">
						<h3 class="title_box mb-0 fs-6 text-dark fw-bold">Hình ảnh & Video</h3>
						<a href="{$clsProject->getLinkDetail($project_id,$block_id,$building_id,'detail',$oneProject)}" class=" text-dark txt_view_all d-none">Tất cả<i class='bx bx-chevron-right me-2' ></i></a>
					</div>
					<a href="" class="text-dark d-flex flex-column align-items-center" data-fancybox="gallery_image" data-src="{$image_docs.image}">
						{if $deviceType eq 'phone'}
							<img class="rounded-3 w-100" src="{$image_docs.image}" alt="" width="300" height="200" style="object-fit: cover">
						{else}
							<img class="rounded-3 w-100" src="{$image_docs.image}" alt="" width="300" height="500" style="object-fit: cover">
						{/if}
					</a>
					<div class="d-none">
						{foreach from=$image_docs.lst_image item=image name=i_img}
							<span class="item_fancy" data-fancybox="gallery_image" data-src="{$image}">
								<img src="{$image}" alt="">
							</span>
						{/foreach}
						{if !empty($image_docs.link_video)}
							<span class="item_fancy" data-fancybox="gallery_image" data-src="{$image_docs.link_video}">
								<img src="{$image_docs.image_video}" alt="">
							</span>
						{/if}
					</div>
				</div>
				<div class="box_info {if $deviceType eq 'phone'}mb-3{else}mb-4{/if}">
					<div class="d-flex align-items-center justify-content-between mb-3">
						<h3 class="title_box mb-0 fs-6 text-dark fw-bold">Tài liệu chung</h3>
						<a href="{$clsProject->getLinkDetail($project_id,$block_id,$building_id,'detail',$oneProject)}" class=" text-dark txt_view_all d-none">Tất cả<i class='bx bx-chevron-right me-2' ></i></a>
					</div>
					<div class="box_tab mb-4">
						<div class="nav-align-top nav-tabs-shadow">
							<div class="w-100 overflow-x-auto mb-4">
								<ul class="nav nav-tabs" role="tablist">
									{foreach from=$arrCategoryDocs item=_oCatDocs key=key name=i}
									<li class="nav-item" role="presentation">
										<button type="button" class="btn btn_tab rounded-pill {if $smarty.foreach.i.first}active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#nav_tab_{$_oCatDocs.property_id}" aria-controls="nav_tab_{$_oCatDocs.property_id}" aria-selected="true">{$_oCatDocs.title}</button>
									</li>
									{/foreach}
								</ul>
							</div>
							<div class="tab-content p-0">
								{foreach from=$arrCategoryDocs item=_oCatDocs key=key name=i}
									<div class="tab_items tab-pane fade {if $smarty.foreach.i.first}active show{/if}" id="nav_tab_{$_oCatDocs.property_id}" role="tabpanel">
										{if !empty($_oCatDocs.lst_child)}
											<div class="tab_body">
												<div class="{if $deviceType eq 'phone'}form-row{else}row{/if}">
													{assign var=checkEmpty value=1}
													{foreach from=$_oCatDocs.lst_child item=_oChild key=key}
														{assign var=gid value=$clsISO->getUniqid()}
														{assign var=lstDocs value=$_oChild.lstDocs}
														{if !empty($lstDocs)}
															{assign var=checkEmpty value=0}
															{foreach from=$lstDocs item=_oDoc key=k_doc name=n_doc}
																{assign var=list_image value=$_oDoc.list_image}
																{if !empty($list_image)}
																	<div class="col-6 col-lg-3 mb-2">
																		{foreach from=$list_image item=image key=k_img name=n_img}
																			<div class="item_tab {if !$smarty.foreach.n_img.first}d-none{/if}" {if $smarty.foreach.n_img.first}id="{$gid}"{/if} data-fancybox="gallery-image_docs_{$_oDoc.id}" data-src="{$image}" data-caption="{$_oDoc.title}">
																				<img src="{$image}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
																			</div>
																		{/foreach}
																	</div>
																{/if}															
																{if !empty($_oDoc.link_video)}
																	<div class="col-6 col-lg-3 mb-2">
																		<span class="item_tab box_video" data-fancybox="gallery-image_docs_{$_oDoc.id}" data-src="{$_oDoc.link_video}" data-caption="{$_oDoc.title}">
																			<img src="{$_oDoc.image_video}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
																		</span>
																	</div>
																{/if}
															{/foreach}
														{/if}
													{/foreach}													
												</div>
											</div>
											{if !empty($checkEmpty)}
												<div class="d-flex justify-content-center">
													<img src="{$URL_IMAGES}/empty.svg" alt="" width="190" height="140">
												</div>
											{/if}
										{else}
											{assign var=gid value=$clsISO->getUniqid()}
											{assign var=lstDocs value=$_oCatDocs.lstDocs}
											{if !empty($lstDocs)}
												<div class="tab_body">
													<div class="{if $deviceType eq 'phone'}form-row{else}row{/if}">
														{foreach from=$lstDocs item=_oDoc key=k_doc name=n_doc}
															{assign var=list_image value=$_oDoc.list_image}
															{if !empty($list_image)}
																<div class="col-6 col-lg-3 mb-2">
																	{foreach from=$list_image item=image key=k_img name=n_img}
																		<div class="item_tab {if !$smarty.foreach.n_img.first}d-none{/if}" {if $smarty.foreach.n_img.first}id="{$gid}"{/if} data-fancybox="gallery-image_docs_{$_oDoc.id}" data-src="{$image}" data-caption="{$_oDoc.title}">
																			<img src="{$image}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
																		</div>
																	{/foreach}
																</div>
															{/if}														
															{if !empty($_oDoc.link_video)}
																<div class="col-6 col-lg-3 mb-2">
																	<span class="item_tab box_video" data-fancybox="gallery-image_docs_{$_oDoc.id}" data-src="{$_oDoc.link_video}" data-caption="{$_oDoc.title}">
																		<img src="{$_oDoc.image_video}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
																	</span>
																</div>
															{/if}
														{/foreach}
													</div>
												</div>
											{else}
												<div class="d-flex justify-content-center">
													<img src="{$URL_IMAGES}/empty.svg" alt="" width="190" height="140">
												</div>
											{/if}
										{/if}
									</div>
								{/foreach}

							</div>
						</div>
					</div>
				</div>
			</div>
			{else  if $type eq 'detail'}
			<div class="col-12">
				<div class="box_info {if $deviceType eq 'phone'}mb-3{else}mb-4{/if}">
					<div class="info_detail d-flex justify-content-between align-items-center text-dark py-2">
						<h3 class="title_box mb-0 fs-5">Thông tin dự án</h3>
					</div>
					<div class="" id="lst_info" style="">
						<div class="tinyContentx tinyContentProject">
							<div class="item_info mb-1">
								<label for="" class="lbl_item">Tên dự án</label>
								{if $show eq "block"}
									<span class="content_item text-dark fw-semibold">{$oneBlock.title}, {$oneProject.title}</span>
								{else if $show eq "building"}
									<span class="content_item text-dark fw-semibold">Tòa {$oneBuilding.title}, {$oneBlock.title}, {$oneProject.title}</span>
								{else}
									<span class="content_item text-dark fw-semibold">{$oneProject.title}</span>
								{/if}
							</div>
							<div class="item_info mb-1">
								<label for="" class="lbl_item">Địa chỉ</label>
								<span class="content_item text-dark fw-semibold">{$more_information.address}</span>
							</div>
							<div class="item_info mb-1">
								<label for="" class="lbl_item">Khu vực</label>
								<span class="content_item text-dark fw-semibold">Hà Nội</span>
							</div>
							{foreach from=$more_information.attrs item = _oAttr}
								<div class="item_info mb-1">
									<label for="" class="lbl_item">{$_oAttr.title}</label>
									<span class="content_item text-dark fw-semibold">{$_oAttr.content}</span>
								</div>
							{/foreach}
						</div>
					</div>
				</div>
				{if $show eq "project"}
					<div class="box_block mb-4">
						<div class="d-flex align-items-center justify-content-between mb-3">
							<h3 class="title_box mb-0 fs-16 text-dark fw-semibold">Phân khu</h3>
						</div>
						{if $deviceType eq "phone"}
						<div class="overflow-x-auto">
							<div class="d-flex gap-2">
								{foreach name=i from=$list_blocks item = _oBlock}
									<a class="item_block d-flex flex-column align-items-center gap-1" href="{$clsProject->getLinkDetail($project_id, $_oBlock.property_id,0,'detail',$oneProject)}">
										<img src="https://drive.google.com/thumbnail?id=1T4N_7FZtb_2mHQoJwIewBVA42Um7GuMr&sz=w1000" alt="" class="rounded-2" width="200" height="150">
										<span class="fs-14 fw-bold text-dark text-upper text-center">{$_oBlock.title}</span>
									</a>
								{/foreach}
							</div>
						</div>
						{else}
							<div class="lst_carousel owl_block owl-carousel border rounded-2 overflow-hidden">
								{foreach name=i from=$list_blocks item = _oBlock}
									<div class="item_block {cycle values='bg-white,bg-lighter'}">
										<div class="card-body">
											<div class="item_top d-flex flex-column mb-2">
												<span class="text-muted fs-11">Phân khu</span>
												<a href="{$clsProject->getLinkDetail($project_id, $_oBlock.property_id,0,'detail',$oneProject)}" class="fs-16 fw-semibold text-dark">{$_oBlock.title} <i class='bx bx-link-external'></i></a>
											</div>
											<div class="box_scale rounded-2 overflow-hidden mb-2">
												<img src="https://drive.google.com/thumbnail?id=1T4N_7FZtb_2mHQoJwIewBVA42Um7GuMr&sz=w1000" alt="" class=" w-100" width="200" height="120">
											</div>
											<a href="{$clsProject->getLinkDetail($project_id, $_oBlock.property_id,0,'detail',$oneProject)}" class="d-block">
												<div class="item_top d-flex flex-column mb-2">
													<span class="text-muted fs-11">Dự án</span>
													<span class="fs-14 text-dark">{$oneProject.title}</span>
												</div>
												<div class="item_top d-flex flex-column mb-2">
													<span class="text-muted fs-11">Loại hình</span>
													<span class="fs-14 text-dark">{$_oBlock.block_type}</span>
												</div>
											</a>
										</div>
									</div>
								{/foreach}
							</div>
						{/if}
					</div>
				{else if $show eq 'block' && $oneBlock.parent_id eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE && !empty($list_buildings)}
					<div class="box_building mb-4">
						<div class="d-flex align-items-center justify-content-between mb-3">
							<h3 class="title_box mb-0 fs-16 text-dark fw-semibold">Tòa</h3>
						</div>
						{if $deviceType eq "phone"}
							<div class="overflow-x-auto">
								<div class="d-flex gap-2">
									{foreach name=i from=$list_buildings item = _oBuilding}
										<a class="item_block d-flex flex-column align-items-center gap-1" href="{$clsProject->getLinkDetail($project_id, $block_id,$_oBuilding.property_id,'detail',$oneProject)}">
											<img src="/images/BDS/vinhomes-ocean-park-1.jpg" alt="" class="rounded-2" width="200" height="150">
											<span class="fs-14 fw-bold text-dark text-upper text-center">{$_oBuilding.title}</span>
										</a>
									{/foreach}
								</div>
							</div>
						{else}
							<div class="lst_carousel owl_building owl-carousel border rounded-2 overflow-hidden">
								{foreach name=i from=$list_buildings item = _oBuilding}
									<div class="item_block {cycle values='bg-white,bg-lighter'}">
										<div class="card-body">
											<div class="item_top d-flex flex-column mb-2">
												<span class="text-muted fs-11">Tòa</span>
												<a href="{$clsProject->getLinkDetail($project_id, $_oBuilding.property_id,0,'detail',$oneProject)}" class="fs-16 fw-semibold text-dark">{$_oBuilding.title} <i class='bx bx-link-external'></i></a>
											</div>
											<div class="box_scale rounded-2 overflow-hidden mb-2">
												<img src="https://drive.google.com/thumbnail?id=1T4N_7FZtb_2mHQoJwIewBVA42Um7GuMr&sz=w1000" alt="" class=" w-100" width="200" height="120">
											</div>
											<a href="{$clsProject->getLinkDetail($project_id, $_oBuilding.property_id,0,'detail',$oneProject)}" class="d-block">
												<div class="item_top d-flex flex-column mb-2">
													<span class="text-muted fs-11">Dự án</span>
													<span class="fs-14 text-dark">{$oneProject.title}</span>
												</div>
												<div class="item_top d-flex flex-column mb-2">
													<span class="text-muted fs-11">Phân khu</span>
													<span class="fs-14 text-dark">{$oneBlock.title}</span>
												</div>
												<div class="item_top d-flex flex-column mb-2">
													<span class="text-muted fs-11">Tầng</span>
													<span class="fs-14 text-dark">{$_oBuilding.number_floor}</span>
												</div>
												<div class="item_top d-flex flex-column mb-2">
													<span class="text-muted fs-11">Căn hộ/tầng</span>
													<span class="fs-14 text-dark">{$_oBuilding.number_house}</span>
												</div>
											</a>
										</div>
									</div>
								{/foreach}
							</div>
						{/if}
					</div>
				{/if}
				<div class="box_tab mb-4" id="box_tab">
					<div class="nav-align-top nav-tabs-shadow">
						<div class="w-100 overflow-x-auto mb-4">
							<ul class="nav nav-tabs" role="tablist">
								{foreach from=$arrCategoryDocs item=_oCatDocs key=key name=i}
									<li class="nav-item" role="presentation">
										<button type="button" class="btn btn_tab rounded-pill {if $smarty.foreach.i.first}active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#nav_tab_{$_oCatDocs.property_id}" aria-controls="nav_tab_{$_oCatDocs.property_id}" aria-selected="true">{$_oCatDocs.title}</button>
									</li>
								{/foreach}
							</ul>
						</div>
						<div class="tab-content p-0">
							{foreach from=$arrCategoryDocs item=_oCatDocs key=key name=i}
								<div class="tab_items tab-pane fade {if $smarty.foreach.i.first}active show{/if}" id="nav_tab_{$_oCatDocs.property_id}" role="tabpanel">
									{if !empty($_oCatDocs.lst_child)}									
										{assign var=checkEmpty value=1}
										{foreach from=$_oCatDocs.lst_child item=_oChild key=key}
											{assign var=gid value=$clsISO->getUniqid()}
											{assign var=lstDocs value=$_oChild.lstDocs}
											{if !empty($lstDocs)}									
												{assign var=checkEmpty value=0}
												<div class="tab_header d-flex align-items-center justify-content-between mb-3">
													<h3 class="title_box mb-0 fs-16 text-dark fw-semibold">{$_oChild.title}</h3>
													<a href="javascript:void(0)" class=" text-dark txt_view_all" onClick="$Core.projects.viewAllDocs(this,event)" toId="{$gid}">Tất cả<i class='bx bx-chevron-right me-2' ></i></a>
												</div>
												<div class="tab_body mb-2">
													<div class="{if $deviceType eq 'phone'}form-row{else}row{/if}">
														{foreach from=$lstDocs item=_oDoc key=k_doc name=n_doc}
															{assign var=list_image value=$_oDoc.list_image}
															{if !empty($list_image)}
																<div class="col-6 col-lg-3 mb-2">
																	{foreach from=$list_image item=image key=k_img name=n_img}
																		<div class="item_tab {if !$smarty.foreach.n_img.first}d-none{/if}" {if $smarty.foreach.n_img.first}id="{$gid}"{/if} data-fancybox="gallery-{$_oDoc.id}" data-src="{$image}">
																			<img src="{$image}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
																		</div>
																	{/foreach}
																</div>
															{/if}
															{if !empty($_oDoc.link_video)}
																<div class="col-6 col-lg-3 mb-2">
																	<span class="item_tab box_video" data-fancybox="gallery-{$_oDoc.id}" data-src="{$_oDoc.link_video}">
																		<img src="{$_oDoc.image_video}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
																	</span>
																</div>
															{/if}
														{/foreach}
													</div>
												</div>
											{/if}
										{/foreach}
										{if !empty($checkEmpty)}
											<div class="d-flex justify-content-center">
												<img src="{$URL_IMAGES}/empty.svg" alt="" width="190" height="140">
											</div>
										{/if}
									{else}
										{assign var=gid value=$clsISO->getUniqid()}
										{assign var=lstDocs value=$_oCatDocs.lstDocs}
										{if !empty($lstDocs)}
											<div class="tab_header d-flex align-items-center justify-content-between mb-3">
												<h3 class="title_box mb-0 fs-16 text-dark fw-semibold">{$_oCatDocs.title}</h3>
												<a href="javascript:void(0)" class=" text-dark txt_view_all" onClick="$Core.projects.viewAllDocs(this,event)" toId="{$gid}">Tất cả<i class='bx bx-chevron-right me-2' ></i></a>
											</div>
											<div class="tab_body">
												<div class="{if $deviceType eq 'phone'}form-row{else}row{/if}">
													{foreach from=$lstDocs item=_oDoc key=k_doc name=n_doc}
														{assign var=list_image value=$_oDoc.list_image}
														{if !empty($list_image)}
															<div class="col-6 col-lg-3 mb-2">
																{foreach from=$list_image item=image key=k_img name=n_img}
																	<div class="item_tab {if !$smarty.foreach.n_img.first}d-none{/if}" {if $smarty.foreach.n_img.first}id="{$gid}"{/if} data-fancybox="gallery-{$_oDoc.id}" data-src="{$image}">
																		<img src="{$image}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
																	</div>
																{/foreach}
															</div>
														{/if}
														{if !empty($_oDoc.link_video)}
															<div class="col-6 col-lg-3 mb-2">
																<span class="item_tab box_video" data-fancybox="gallery_image" data-src="{$_oDoc.link_video}">
																	<img src="{$_oDoc.image_video}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
																</span>
															</div>
														{/if}
													{/foreach}
													
												</div>
											</div>
										{else}
											<div class="d-flex justify-content-center">
												<img src="{$URL_IMAGES}/empty.svg" alt="" width="190" height="140">
											</div>
										{/if}
									{/if}
								</div>
							{/foreach}
						</div>
					</div>
				</div>
			{/if}
		</div>
	</div>
</div>