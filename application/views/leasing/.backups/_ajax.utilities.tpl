<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header border-bottom pb-3"> 
			<h5 class="modal-title">Nội thất, trang thiết bị <br />
				<span class="text-muted fs-12">Hiện có <strong class="text-main">{$total_utilities}</strong> nội thất, trang thiết bị</span>
			</h5>
		</div>
		<div class="modal-body border-bottom">
			{if $more_information.device_leasing && $more_information.check_utilities eq 1}
				<div class="mt-3">
					<div class="tinyContentx overflow-y-auto" style="max-height: 250px">
						{foreach from=$more_information.device_leasing key=key name=k item=device_leasing}
							{assign var = list_child value = $_oB.list_child}
							{if !empty($device_leasing)}
							<div class="mb-2">
								<h5 class="mb-2 fs-6">{$array_device_leasing[$key].title}</h5>
								<div class="form-row">
									{foreach name=i from=$device_leasing item=item key=k}
									{if !empty($array_device_leasing[$item])}
									<div class="col-12 col-lg-4">
										<div class="d-flex align-items-center py-1">
											<img class="mr-2" src="{$array_device_leasing[$item].image}" width="24px" height="24" />
											<span style="font-size:13.88px">{$array_device_leasing[$item].title}</span>
										</div>
									</div>
									{/if}
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