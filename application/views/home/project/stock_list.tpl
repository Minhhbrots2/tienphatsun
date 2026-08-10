<div class="content-wrapper">
	<div class="container-sm flex-grow-1 container-p-y pt-1">
		{if !empty($lstArea)}
			<div class="row">
				<div class="col-12 col-xxl-10 mx-auto">
					{foreach from=$lstArea item=_oItem key=key name=i}
						{if !empty($_oItem.list_blocks) || !empty($_oItem.arr_projects) || !empty($_oItem.arr_menu_blocks)}
							{assign var=list_blocks value=$_oItem.list_blocks}
							{assign var=arr_projects value=$_oItem.arr_projects}
							{assign var=arr_menu_blocks value=$_oItem.arr_menu_blocks}
							<div class="divider my-2">
								<div class="divider-text text-upper fs-3 fw-semibold text-main">
									{$_oItem.title}
								</div>
							</div>
							{if !empty($list_blocks)}
								<div class="card mb-2 project_highfloor">
									<div class="card-header d-flex justify-content-between align-items-center">
										<h3 class="card-title mb-0 fs-5">1 - Chung cư cao tầng</h3>
										<button type="button" class="btn btn-icon bg-white btn-default {if $deviceType eq 'phone'}btn-sm{/if}" data-bs-toggle="tooltip" onclick="$Core.project.dragBlock(this,event)" title="Chỉnh sửa"><i class="bx bx-pencil"><i></i></i></button>
									</div>
									<div class="card-body " data-profile="{$profile_id}">
										<div class="row drag_project" area_id="{$_oItem.setting_id}">
											{foreach from=$list_blocks item=_oBlock key=k_block name=n_block}
												{if !empty($_oBlock.list_menu_buildings)}
												<div class="col-12 col-md-6 col-lg-4 mb-2 project-item position-relative" id="{$_oBlock.property_id}">
													<span class="btn btn-icon btn-default position-absolute top-0 right-0 zindex-1 bg-white btn_drag d-none" title="Giữ vào kéo thả" type="button" style="right:calc(var(--bs-gutter-x) * 0.5)"><i class='bx bx-transfer-alt' ></i></span>
													{assign var = more_information_block value=$_oBlock.more_information}
													{assign var = _oProject value=$_oBlock.project_info}
													<div class="block-one mt-0 mt-lg-2">
														<div class="divider my-2">
															<div class="divider-text text-upper fs-6 fw-semibold" style="color:{$more_information_block.bgcolor}">
																{if $clsISO->checkDEV()}
																<a class="btn btn-xs btn-icon btn-link rounded-pill" onClick="$Core.stock.config_block(this, event)" 
																	title="Cấu hình" block_id="{$_oBlock.property_id}" data-bs-toggle="tooltip"><i class="bx bx-cog"></i></a>
																{/if}
																PK {$_oBlock.title} 
																{if !empty($more_information_block.is_hot)}
																<span class="hot-dot">HOT</span>
																{/if}
															</div>
														</div>
														<div class="d-flex gap-2 align-items-center justify-content-center">
															<img src="{$clsISO->getImageWH($_oProject.logo,0,30)}" class="h-px-30" />
															<h3 class="fs-12 mb-0 text-upper">{$_oProject.title}</h3>
														</div>
														<ul class="mb-0 list-unstyled d-flex flex-wrap gap-2 mt-2">
															{assign var=lstBuilding value=$_oBlock.list_menu_buildings}
															{foreach from=$lstBuilding item=_oBuilding key=k_block name=n_building}
															<li class="flex-fill ">
																<a data-toggle="ripple" href="{$_oBuilding.link}" title="{$_oBuilding.title}" {$more_information_block.bgcolor} class="btn {if $deviceType eq 'phone'} btn-sm{/if} btn-outline-primary building-name w-100" data-color="{$more_information_block.bgcolor}" style="border-color: {$more_information_block.bgcolor} !important; background-color: {$more_information_block.bgcolor} !important; color: {$more_information_block.textcolor} !important;">{$_oBuilding.title}</a>
															</li>
															{/foreach}
														</ul>
													</div>
													</div>
												{/if}
											{/foreach}
										</div>
									</div>
								</div>
							{/if}
							{if !empty($arr_projects) || !empty($arr_menu_blocks) }
								<div class="card">
									<div class="card-header">
										<h3 class="card-title mb-0 fs-5">2 - Biệt thự thấp tầng</h3>
									</div>
									<div class="card-body">
										<div class="form-row row-cols-2 rows-col-lg-3 row-cols-lg-4 row-cols-xxl-5">
											{if !empty($arr_projects)}
												{foreach from=$arr_projects item=_oProject key=key}
													{if $clsProject->isLowFloor($_oProject.project_id, $_oProject.list_block_type)}
														<div class="col mb-2">
															<a data-toggle="ripple" href="{$_oProject.link}" class="btn h-100 btn-outline-primary w-100" style="border-color: {$_oProject.bgcolor} !important; background-color:{$_oProject.bgcolor} !important; color:{$_oProject.textcolor} !important;order:{$_oProject.order_no}">
																<img src="{$clsISO->getImageWH($_oProject.logo,0,30)}" class="h-px-30" style="filter:brightness(0) invert(1);">
																<div class="clearfix my-1"></div>
																<span >{$_oProject.code}</span>
															</a>
														</div>
													{/if}
												{/foreach}
											{/if}
											{if !empty($arr_menu_blocks)}
												{foreach from=$arr_menu_blocks item = _oBlock}
												{assign var = _more_information value = $_oBlock.more_information}
												<div class="col mb-2">
													<a data-toggle="ripple" href="{$_oBlock.link}" class="btn h-100 btn-outline-primary w-100" style="border-color: {$_more_information.bgcolor} !important; background-color:{$_more_information.bgcolor} !important; color:{$_more_information.textcolor} !important;">
														<img src="{$clsISO->getImageWH($_oBlock.image,0,30)}" class="h-px-30" style="filter:brightness(0) invert(1);">
														<div class="clearfix my-1"></div>
														<span >{$_oBlock.title}</span>
													</a>
												</div>
												{/foreach}
											{/if}
										</div>
									</div>
								</div>
							{/if}
						{/if}
					{/foreach}								
				</div>
			</div>
		{/if}
	</div>
</div>