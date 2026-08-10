<div class="modal bottom fade stock-menu-more" tabindex="-1" aria-modal="true" role="dialog" > 
	<div class="modal-dialog modal-dialog-scrollable modal-bottom">
		<div class="modal-content">
			<div class="modal-body overflow-y-auto" id="box_parent" style="max-height: 90vh">				
				<div class="item-collapse mb-2">
					<div class="text-upper mb-1 fs-14 p-2 border-bottom d-flex justify-content-between align-items-center header_collapse" data-bs-toggle="collapse" href="#stock_high_floor" role="button" aria-expanded="false" aria-controls="stock_high_floor">
						<span class="">Bảng hàng cao tầng</span>
						<i class='bx bx-chevron-down'></i>
					</div>
					<div class="collapse p-2 show" id="stock_high_floor" data-bs-parent="#box_parent">
						<div class="px-2">
							{foreach from=$lstBlockHighFloor item=_oBlock key=key name=i}
								{assign var=lstBuilding value=$_oBlock.building}
								<div class="py-1 border-bottom">
									<div class="text-upper mb-1 fs-12 d-flex justify-content-between align-items-center p-1 header_collapse {if !$smarty.foreach.i.first}collapsed{/if}" data-bs-toggle="collapse" href="#block_high_floor_{$_oBlock.property_id}" role="button" aria-expanded="false" aria-controls="block_high_floor_{$_oBlock.property_id}">
										<span class="">{$_oBlock.title}</span>
										<i class='bx bx-chevron-down'></i>
									</div>
									<div class="py-1 collapse {if $smarty.foreach.i.first}show{/if}" id="block_high_floor_{$_oBlock.property_id}" data-bs-parent="#stock_high_floor">
										<div class="d-flex flex-wrap gap-2">
											{foreach from=$lstBuilding item=_oBuilding key=k name=k}
												<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm" href="{$_oBuilding.link}">
													<span class="icon"><i class="menu-icon tf-icons bx bx-building-house me-1"></i></span>
													<span class="">{$_oBuilding.title}</span>
												</a>
											{/foreach}
										</div>
									</div>
								</div>
							{/foreach}
						</div>					
					</div>	
				</div>	
				<div class="item-collapse mb-2">
					<div class="text-upper mb-1 fs-14 p-2 border-bottom d-flex justify-content-between align-items-center header_collapse collapsed" data-bs-toggle="collapse" href="#stock_low_floor" role="button" aria-expanded="false" aria-controls="stock_low_floor">
						<span class="">Bảng hàng thấp tầng</span>
						<i class='bx bx-chevron-down'></i>
					</div>
					<div class="collapse p-2" id="stock_low_floor" data-bs-parent="#box_parent">
						<div class="d-flex flex-wrap gap-2 mb-3">
							<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p2.html">
								<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
								<span class="">VHOP2</span>
							</a>
							<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p3.html">
								<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
								<span class="">VHOP3</span>
							</a>
							<a class="item_ultilities d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/project/p9.html">
								<span class="icon"><i class="menu-icon tf-icons bx bx-home me-1"></i></span>
								<span class="">VHGG</span>
							</a>
						</div>				
					</div>	
				</div>	
			</div>	
		</div>
	</div>
</div>