<div class="modal-dialog right">
	<div class="modal-content">
		<div class="modal-header border-bottom py-4"> 
			<h3 class="modal-title"><strong>Dịch vụ</strong></h3>
		</div>
		<form action="" method="post" id="frmPayOther" encrupt="miltipart/form-data">
			<div class="modal-body border-bottom py-2">
				{if $more_information.device_leasing}
					<div class="box_infomation mb-4">					
						<div class="d-flex flex-wrap">
							{foreach from=$more_information.device_leasing key=key name=k item=device_leasing}
								<div class="list_service {if !$smarty.foreach.k.last} border-bottom{/if} py-2 w-100">
									<span class="title_service mb-2 fs-6 text-upper pb-1 relative d-inline-block">{$array_device_leasing[$key].title}</span>
									<div class="row">
										{foreach name=i from=$device_leasing item=item key=k}
											<div class="col-md-6">	
												{$array_device_leasing[$item].title}
											</div>
										{/foreach}
									</div>									
								</div>								
							{/foreach}
						</div>
					</div>
				{/if}
			</div>
			<div class="modal-footer justify-content-center">
				<a type="button" class="btn btn-outline-default" onClick="$Core.leasing.close_pop(this, event)" data-bs-dismiss="modal">
					Đóng
				</a>
			</div>
		</form>
	</div>
</div>