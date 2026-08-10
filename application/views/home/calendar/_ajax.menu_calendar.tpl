{if !empty($arr_billing_type)}
	<div class="card mb-2 sticky sssssss">
		<h5 class="card-header">Loại hình ký</h5>
		<div class="card-body">
			<div class="d-flex flex-wrap gap-2">
				{foreach from=$arr_billing_type item=billing_type key=key}
					{assign var=lst_number_billing value=$billing_type.lst_number_billing}
					{if !empty($lst_number_billing)}
						<div class="gbox today flex-fill p-3 w-40">
							<h5 class="mb-2 fs-14">{$billing_type.title}</h5>
							<div class="d-flex align-items-center gap-1 justify-content-between">
								{if $type_of_date ne '_text'}<span class="text-main fw-semibold">{$lst_number_billing.total_contract} HĐMB</span>{/if}
								{if $type_of_date ne '_contract'}<span class="text-primary fw-semibold">{$lst_number_billing.total_agree_date} VBTT</span>{/if}
							</div>
						</div>
					{/if}
				{/foreach}
			</div>			
		</div>
	</div>
{/if}