<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
	<form class="modal-content" {if $deviceType eq 'phone'}style="border-radius:0"{/if} >
		<div class="modal-header">
			<h5 class="modal-title text-main fw-bold text-upper fs-26">Gói khách hàng</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body text-dark">	
			<div class="form-row row-cols-1 row-cols-md-3 justify-content-center" style="row-gap: .5rem">
				{if !empty($lstPackage)}
					{foreach from=$lstPackage item=_oItem}
						{assign var=more_information value=$_oItem.more_information}
						<div class="col">
							<div class="item_package border rounded-3 w-100 p-3 text-center">
								<h3 class="title_item text-upper mb-3 fs-24">Gói {$_oItem.title}</h3>
								<div class="body_item">
									<p class="mb-1"><strong class="text-main fs-20">{$more_information.total_data}</strong> khách hàng</p>
									<p class="">Chỉ với <strong class="text-main fs-24">{$clsISO->shortNumber($more_information.price_data,0)}</strong></p>
								</div>
								<div class="package_intro text-left">{$more_information.intro}</div>
								<button class="btn btn-lg btn-danger border-0 bg-main text-white w-100" type="button" onClick="$Core.market_data.open_package(this,event)" package_id="{$_oItem.setting_id}">Mua gói</button>
							</div>
						</div>
					{/foreach}
				{/if}
			</div>
		</div>
	</form>
</div>