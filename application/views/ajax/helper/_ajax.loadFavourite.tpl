<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-bottom d-flex align-items-center justify-content-between">
				<h5 class="modal-title" id="modalTopTitle">Danh sách yêu thích</h5>
				<a href="{$clsISO->getLink('favourite')}" class="btn btn-outline-primary ml-2">Xem tất cả</a>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body scroller">
				<ul class="list-unstyled" id="list_favourite" data-bs-popper="static">
					{if !empty($list_stocks)}
						{foreach from=$list_stocks item=_oItem name=i}
							{assign var=lstImage value=$_oItem.images}
							{assign var=moreInformation value=$_oItem.more_information}
							<li class="py-1 item_pop_favourite {if !$smarty.foreach.i.last}border-bottom{/if} cursor-pointer" >
								<div class="d-flex justify-content-between align-items-center user-name">
									<div class="d-flex flex-column pl-2" {if $deviceType eq 'phone'}onclick="$Core.helper.open_stock({$_oItem.stock_id})"{else} data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="600" data-target="webuiPopover{$_oItem.stock_id}_{$uid}" {/if}>
										<a href="javscript:void(0)" class="text-heading text-truncate">
											<span class="fw-medium">{$_oItem.ms_code}</span>
										</a>
										<div class="d-flex flex-wrap gap-1 align-items-center fs-12 awe__sop-location py-1">
											{if !empty($_oItem.location)}
												<div class="mr-2 text_ellipsis d-flex align-items-center">
													<i class="re__icon-location--sm mr-1"></i>
													<span class="text-main">{$_oItem.location}</span>
												</div>													
											{/if}
											{if $_oItem.bedroom}
												<div class="mr-2 text_ellipsis d-flex align-items-center">
													<i class="re__icon-bedroom--sm mr-1"></i>
													<span class="text-main">{$_oItem.bedroom}</span>
												</div>
											{/if}
											{if $moreInformation.DT_TT}
												<div class="mr-2 text_ellipsis d-flex align-items-center">
													<i class="re__icon-size--sm mr-1"></i>
													<span class="text-main">{$moreInformation.DT_TT}m<sup>2</sup></span>
												</div>
											{/if}
											{if $_oItem.home_direction}
												<div class="mr-2 text_ellipsis d-flex align-items-center">
													<i class="re__icon-ying-yang--xl mr-1"></i>
													<span class="text-main">{$_oItem.home_direction}</span>
												</div>
											{/if}
										</div>

									</div>
									<a onClick="$Core.helper.toggle_wishlist(this,event)" data-bs-toggle="tooltip" title="Loại bỏ" stock_id="{$_oItem.stock_id}" class="btn saved p-1 pop_favourite">{$clsISO->makeIcon('bx-heart fs-11')}</a>
								</div>
							</li>
						{/foreach}							
					{else}
						<div class="d-flex flex-column align-items-center justify-content-center p-3">
							<img src="{$URL_IMAGES}/listing-empty.svg" width="90px">
							<p>Chưa có căn hộ nào trong danh mục yêu thích..</p>
						</div>
					{/if}
				</ul>
			</div>
		</div>
	</div>
</div>