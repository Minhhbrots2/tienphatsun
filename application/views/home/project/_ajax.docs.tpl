<div id="{$uid}" class="modal fade show right" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content" style="background: #00134a;color: #fff">
			<div class="modal-header d-flex align-items-start{if $deviceType eq 'phone'} flex-wrap justify-content-end{else} justify-content-between{/if}">
				<h5 class="modal-title fs-20{if $deviceType eq 'phone'} w-100{/if}">
					{$parent_name}
				</h5>
				<button type="button" class="btn-close ml-2 {if $deviceType eq 'phone'}position-absolute{/if}" data-bs-dismiss="modal" aria-label="Close" {if $deviceType eq 'phone'}style="right:10px;top: 27px"{else}style="margin-top: 0;margin-right: 0"{/if}></button>
			</div>
			<div class="modal-body scroll-y-auto-hover" style="height:calc(100vh - 80px)">
				<div class="nav-align-top nav-tabs-shadow">
					<div class="w-100 overflow-x-auto mb-4">
						<ul class="nav nav-tabs justify-content-center" role="tablist">
							{foreach from=$list_cat_child item=_oChild key=key name=i}
								{if !empty($_oChild.lstDocs)}
									<li class="nav-item" role="presentation">
										<button type="button" class="btn btn_tab text-white rounded-0 {if $_oChild.property_id eq $cat_id}active{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#nav_tab_{$_oChild.property_id}{$uid}" aria-controls="nav_tab_{$_oChild.property_id}{$uid}" aria-selected="true">{$_oChild.title}</button>
									</li>
								{/if}
							{/foreach}
						</ul>
					</div>
					<div class="tab-content p-0 no-shadow" style="background: inherit">
						{if !empty($listDocs)}
							<div class="{if $deviceType eq 'phone'}form-row{else}row{/if}">
								{foreach from=$listDocs item=_oDoc key=k_doc name=n_doc}
									{assign var=list_image value=$_oDoc.list_image}
									{if !empty($list_image)}
										<div class="col-6 col-lg-3 mb-2">
											{foreach from=$list_image item=image key=k_img name=n_img}
												<div class="item_tab {if !$smarty.foreach.n_img.first}d-none{/if}" {if $smarty.foreach.n_img.first}id="{$gid}"{/if} data-fancybox="gallery-{$_oDoc.id}" data-src="{$image}" data-caption="{$_oDoc.title}">
													<img src="{$image}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
												</div>
											{/foreach}
										</div>
									{/if}
									{if !empty($_oDoc.link_video)}
										<div class="col-6 col-lg-3 mb-2">
											<span class="item_tab box_video" data-fancybox="gallery-{$_oDoc.id}" data-src="{$_oDoc.link_video}" data-caption="{$_oDoc.title}">
												<img src="{$_oDoc.image_video}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
											</span>
										</div>
									{/if}
								{/foreach}
							</div>
						{/if}
						{if !empty($list_cat_child)}			
							{foreach from=$list_cat_child item=_oChild key=key name=i}
								{assign var=lstDocs value=$_oChild.lstDocs}
								{if !empty($lstDocs)}
									<div class="tab_items tab-pane fade {if $_oChild.property_id eq $cat_id}active show{/if}" id="nav_tab_{$_oChild.property_id}{$uid}" role="tabpanel">
										<div class="tab_body mb-2">
											<div class="{if $deviceType eq 'phone'}form-row{else}row{/if}">
												{foreach from=$lstDocs item=_oDoc key=k_doc name=n_doc}
													{assign var=list_image value=$_oDoc.list_image}
													{if !empty($list_image)}
														<div class="col-6 col-lg-3 mb-2">
															{foreach from=$list_image item=image key=k_img name=n_img}
																<div class="item_tab {if !$smarty.foreach.n_img.first}d-none{/if}" {if $smarty.foreach.n_img.first}id="{$gid}"{/if} data-fancybox="gallery-{$_oDoc.id}" data-src="{$image}" data-caption="{$_oDoc.title}">
																	<img src="{$image}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
																</div>
															{/foreach}
														</div>
													{/if}
													{if !empty($_oDoc.link_video)}
														<div class="col-6 col-lg-3 mb-2">
															<span class="item_tab box_video" data-fancybox="gallery-{$_oDoc.id}" data-src="{$_oDoc.link_video}" data-caption="{$_oDoc.title}">
																<img src="{$_oDoc.image_video}" class="w-100 rounded-3" width="200" height="140" alt="{$_oDoc.title}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'">
															</span>
														</div>
													{/if}
												{/foreach}
											</div>
										</div>									
									</div>
								{/if}
							{/foreach}
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>