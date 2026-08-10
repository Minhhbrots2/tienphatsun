<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header border-bottom pb-3"> 
			<h5 class="modal-title">Nội thất, trang thiết bị <br />
				<span class="text-muted fs-12">Hiện có <strong class="text-main">{$total_utilities}</strong> nội thất, trang thiết bị</span>
			</h5>
		</div>
		<div class="modal-body border-bottom">
			{if !empty($list_devices)}
				<div class="mt-3">
					<div class="tinyContentx overflow-y-auto" style="max-height: 250px">
						{foreach from=$list_devices key=key name=k item=device_sop}
							{assign var = list_child value = $device_sop.list_child}
							{if !empty($device_sop)}
							<div class="mb-2">
								<h5 class="mb-2 fs-6">{$device_sop.title}</h5>
								<div class="form-row">
									{foreach name=i from=$list_child item=item key=k}
										<div class="col-12 col-lg-4">
											<div class="d-flex align-items-center py-1">
												<img class="mr-2" src="{$item.image}" width="24px" height="24" />
												<span style="font-size:13.88px">{$item.title}</span>
											</div>
										</div>
									{/foreach}
								</div>
							</div>
							{/if}
						{/foreach}
					</div>
				</div>
			{/if}
		</div>
		<div class="modal-footer justify-content-center">
			<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Đóng</button>
		</div>
	</div>
</div>