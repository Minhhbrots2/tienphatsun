<div class="container-sm flex-grow-1 container-p-y pt-2">	
	<div class="form-row">
		<div class="col-xxl-12 mx-auto">
			<div class="d-flex align-items-center justify-content-between mb-3">
				<h4 class="fw-bold mb-0 fs-20">Danh sách dự án</h4>
				<div class="dropdown">
					<a  class="btn bg-warning text-white js__dropdown-stock" href="{$clsISO->getLink('stock')}" >Bảng hàng dự án</a>
					{*{if !empty($list_projects)}
					<button data-toggle="ripple" class="btn btn-outline-default dropdown-toggle" type="button" 
						data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-haspopup="true" aria-expanded="false">Bảng hàng dự án</button>
					<div class="dropdown-menu dropdown-menu-end dropdown-menu-stock">
						<div class="p-3">
							<h3 class="card-title mb-3 fs-5">1 - Chung cư cao tầng</h3>
							<div class="row">
								{foreach from=$list_projects item=_oProject key=key}
								{if $clsISO->checkItemInArray($_oProject.project_id,$list_projects_highfloor)}
									{assign var = list_blocks value = $_oProject.list_blocks}
									<div class="col-12 col-md-6 col-lg-4 mb-3">
										<div class="d-flex gap-2 align-items-center justify-content-center">
											<img src="{$_oProject.logo}" class="h-px-30" onerror="this.src='{$URL_IMAGES}/no-image.png'" loading="lazy"/>
											<h3 class="fs-6 mb-0 text-upper">{$_oProject.title}</h3>
										</div>
										{foreach from=$list_blocks item=_oBlock key=k_block name=n_block}
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
							<h3 class="card-title mb-3 fs-5">2 -Dự án thấp tầng</h3>
							<div class="d-flex gap-2 align-items-center">
							{foreach from=$list_projects item=_oProject key=key}
								{if $clsISO->checkItemInArray($smarty.const._BLOCK_TYPE_LOWFLOOR_SALE,$_oProject.list_block_type)}
								<a data-toggle="ripple" href="/project/p{$_oProject.project_id}.html" class="btn flex-fill btn-outline-primary w-100 px-2">
									<img src="{$_oProject.logo}" class="h-px-30 mw-100" onerror="this.src='{$URL_IMAGES}/no-image.png'" style="object-fit: contain" loading="lazy">
									<div class="clearfix my-1"></div>
									<span class="{if $deviceType eq 'phone'}fs-12{/if}" >{$_oProject.code}</span>
								</a>
								{/if}
							{/foreach}
							</div>
						</div>
					</div>
					{/if}*}
				</div>
			</div>
			{if !empty($lstArea)}
				{foreach from=$lstArea item=_oItem key=key name=i}
					{if !empty($arr_project_area[$_oItem.setting_id])}
						<div class="divider my-2">
							<div class="divider-text text-upper fs-2 fw-semibold text-main">
								{$_oItem.title} <span class="fs-16">({$arr_project_area[$_oItem.setting_id]|@count} dự án)</span>
							</div>
						</div>
						<div class="form-row">
							{foreach from = $arr_project_area[$_oItem.setting_id] item = _oProject}
							<div class="col-12 col-lg-4 col-xl-4 col-xxxl-3 mb-2">
								<div class="card project-card h-100 no-shadow">
									<a class="d-block" href="{$clsProject->getLinkDetail($_oProject.project_id,0,0,'overview',$_oProject)}" title="{$_oProject.title}">
										<div class="position-relative text-white">
											<span class="position-absolute top-px-20 right-px-20 bg-success rounded-pill py-1 px-3 fs-12">Đang mở bán</span>
											<img decoding="async" class="card-img-top img-project img-fluid"  onerror="this.src='{$URL_IMAGES}/no-image.png'" src="{$clsISO->resize_image_url($_oProject.image, 400, 300)}" loading="lazy">
										</div>
										<div class="card-body">
											<div class="d-flex align-items-center justify-content-between gap-2">
												<div class="awe__project-info">
													<h4 class="card-title mb-2" title="{$_oProject.title}" >
														<span class="text-dark text-fs-22 fw-semibold limit_1line">{$_oProject.title}</span>
													</h4>
													<p class="text-dark mb-1 text-fs-13 limit_2line" title="{$_oProject.address}" >
														<i class='bx bx-map'></i> {$_oProject.address}
													</p>
													<div class="mb-1 text-fs-13 text-muted limit_2line" title="{$_oProject.apartment}" >
														<i class='bx bx-home-alt'></i> Quy mô: {$_oProject.apartment}
													</div>
													<div class="text-fs-13 align-items-center text-muted limit_1line" title="{$_oProject.arcreage}" >
														<i class='bx bx-code'></i> Diện tích: {$_oProject.arcreage}
													</div>	
												</div>
												<div class="awe__project-icon d-none d-lg-block">
													<img class="img-fluid h-px-50" src="{$clsISO->resize_image_url($_oProject.logo, 0, 50)}"  onerror="this.src='{$URL_IMAGES}/no-image.png'" loading="lazy" />
												</div>
											</div>	
										</div>
									</a>
								</div>
							</div>
							{/foreach}
						</div>
					{/if}
				{/foreach}
			{/if}
		</div>
	</div>
</div>
