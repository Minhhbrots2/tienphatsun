<div class="table-container overflow-x-auto text-nowrap no-shadow bg-white">
	{if !empty($arr_compare)}
		<table cellpadding="0" cellspacing="0" width="100%" class="table table-billing table-bordered dragable mb-0">
			<thead>
				<tr>
					<th class="align-center bg-lighter h-px-40" width="150px">Tiêu chí</th>
					{if !empty($arr_compare)}
						{foreach from=$arr_compare item=_oItem key=key name=i}
							<th class="align-center bg-lighter h-px-40">
								<div class="card h-100 no-shadow border">
									<div class="apt-card p-2">			
										<button class="btn btn-icon btn-sm position-absolute right-0 top-0" type="button" onclick="$Core.global.compare.add_stock_compare(this,event)" _tp="modal" action="delete" stock_id="{$key}" toId="{$toId}"><i class="bx bx-x" ></i></button>							
										<a class="apt-code" href="{$clsStock->getLink($_oItem.ms_code)}" target="_blank" >{$_oItem.stock_code} <i class="fas fa-external-link-alt" style="font-size:10px"></i></a>
										<div class="apt-price text-main fw-bold fs-5">{$_oItem.total_price_vat}</div>
										<div class="apt-promo text-warning fs-12" style="text-transform: initial">Giá gồm VAT &amp; KPBT</div>
										{if !empty($_oItem.csbh)}<div class="apt-promo2">CSBH: <span class="fw-bold">{$_oItem.csbh}</span></div>{/if}
									</div>
								</div>
							</th>
						{/foreach}
					{/if}
				</tr>
			</thead>
			<tbody class="table-border-bottom-0">
				{if !empty($arr_aciteria)}
					{foreach name=i from=$arr_aciteria item = _oItem key=key}
						<tr class="trBilling nohover" {if !empty($_oItem.bgcolor)} style="background-color:{$_oItem.bgcolor}"{/if} >
							<td class="align-center">{$_oItem.title}</td>
							{foreach from=$arr_compare item=_oCompare key=k_compare name=i_compare}
								<td class="align-center text-center {if !empty($_oItem.stock_min) && $_oItem.stock_min eq $k_compare}text-success fw-bold{elseif !empty($_oItem.stock_min) || $_oItem.stock_max eq $k_compare}text-danger fw-bold{/if}">{$_oCompare.$key} {$_oItem.unit}</td>
							{/foreach}
						</tr>
					{/foreach}
				{else}
					<tr class=" nohover">
						<td class="text-center" colspan="3">
							Chưa có tiêu chí
						</td>
					</tr>
				{/if}
			</tbody>
		</table>
	{else}
		<table cellpadding="0" cellspacing="0" width="100%" class="table table-billing table-bordered dragable mb-0">
			<thead>
				<tr>
					<th class="align-center bg-lighter h-px-40" width="150px">Tiêu chí</th>
					<th class="align-center bg-lighter h-px-40"><div class="animate-bg w-100 rounded-2 h-px-15"></div></th>
					<th class="align-center bg-lighter h-px-40"><div class="animate-bg w-100 rounded-2 h-px-15"></div></th>
				</tr> 
			</thead>
			<tbody class="table-border-bottom-0">
				{if !empty($arr_aciteria)}
					{foreach name=i from=$arr_aciteria item = _oItem key=key}
						<tr class="trBilling ">
							<td class="align-center">{$_oItem.title}</td>
							<td class="align-center text-center"><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td class="align-center text-center"><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						</tr>
					{/foreach}
				{else}
					<tr>
						<td class="text-center" colspan="3">
							Chưa có tiêu chí
						</td>
					</tr>
				{/if}
			</tbody>
		</table>
	{/if}
</div>