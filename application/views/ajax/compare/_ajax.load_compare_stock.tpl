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
										<a class="" href="{$clsStock->getLinkDetail($_oItem.stock_id,$_oItem)}" target="_blank" >{$_oItem.stock_code} <i class="fas fa-external-link-alt" style="font-size:10px"></i></a>
										<div class="apt-price text-main fw-bold fs-5">{$_oItem.total_price_vat}</div>
										<div class="apt-promo text-warning fs-12" style="text-transform: initial">Giá gồm VAT &amp; KPBT</div>
										{if !empty($_oItem.csbh)}<div class="apt-promo2">CSBH: <span class="fw-bold">{$_oItem.csbh}</span></div>{/if}
									</div>
								</div>
							</th>
						{/foreach}
						{if $arr_compare|@count eq 1}
							<th class="align-center bg-main h-px-40 text-center">
								<button class="btn-add-can btn btn-sm btn-default text-white" onclick="$Core.global.compare.add_stock_compare(this,event)" _tp="modal" action="open" toid="{$toId}"><i class="bx bx-plus"></i> Thêm căn</button>
							</th>
						{/if}
					{/if}
				</tr>
			</thead>
			<tbody class="table-border-bottom-0">
				{if !empty($arr_aciteria)}
					{foreach name=i from=$arr_aciteria item = _oItem key=key}
						<tr class="trBilling nohover" {if !empty($_oItem.bgcolor)} style="background-color:{$_oItem.bgcolor}"{else} style="background-color:#FFF"{/if} >
							<td class="align-center">{$_oItem.title}</td>
							{foreach from=$arr_compare item=_oCompare key=k_compare name=i_compare}
								<td class="align-center text-center {if !empty($_oItem.stock_min) && $_oItem.stock_min eq $k_compare}text-success fw-bold{elseif !empty($_oItem.stock_min) || $_oItem.stock_max eq $k_compare}text-danger fw-bold{/if}">{$_oCompare.$key} {$_oItem.unit}</td>
							{/foreach}
							{if $arr_compare|@count eq 1 && $smarty.foreach.i.first}
								<td class="align-center text-center border-left" rowspan="{$arr_aciteria|@count}" style="background: #FFF !important">
									<div class="d-flex justify-content-center flex-column">
										<img src="https://myfuture.vn/application/themes/images/no-data.png" height="200">
										<p class="text-muted mt-n2">Chưa chọn căn hộ so sánh!</p>
									</div>
								</td>
							{/if}
						</tr>
					{/foreach}
				{else}
					<tr class=" nohover">
						<td class="text-center" colspan="3">
							<div class="d-flex justify-content-center">
								<div class="text-center p-4">
									<img src="https://myfuture.vn/application/themes/images/no-data.png" height="200">
									<p class="text-muted mt-n2">Chưa có tiêu chí so sánh!</p>
								</div>
							</div>
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
					<th class="align-center bg-main h-px-40 text-center">
						<button class="btn-add-can btn btn-sm btn-default text-white" onclick="$Core.global.compare.add_stock_compare(this,event)" _tp="modal" action="open" toid="{$toId}"><i class="bx bx-plus"></i> Thêm căn</button>
					</th>
					<th class="align-center bg-main h-px-40 text-center">
						<button class="btn-add-can btn btn-sm btn-default text-white" onclick="$Core.global.compare.add_stock_compare(this,event)" _tp="modal" action="open" toid="{$toId}"><i class="bx bx-plus"></i> Thêm căn</button>
					</th>
				</tr> 
			</thead>
			<tbody class="table-border-bottom-0">
				{if !empty($arr_aciteria)}
					{foreach name=i from=$arr_aciteria item = _oItem key=key}
						<tr class="trBilling " style="background: #FFF">
							<td class="align-center">{$_oItem.title}</td>
							{if $smarty.foreach.i.first}
								<td class="align-center text-center" rowspan="{$arr_aciteria|@count}">
									<div class="d-flex justify-content-center">
										<div class="text-center p-4">
											<img src="https://myfuture.vn/application/themes/images/no-data.png" height="200">
											<p class="text-muted mt-n2">Chưa chọn căn hộ so sánh!</p>
										</div>
									</div>
								</td>
								<td class="align-center text-center" rowspan="{$arr_aciteria|@count}">
									<div class="d-flex justify-content-center">
										<div class="text-center p-4">
											<img src="https://myfuture.vn/application/themes/images/no-data.png" height="200">
											<p class="text-muted mt-n2">Chưa chọn căn hộ so sánh!</p>
										</div>
									</div>
								</td>
							{/if}
						</tr>
					{/foreach}
					
					
				{else}
					<tr>
						<td class="text-center" colspan="3">
							<div class="d-flex justify-content-center">
								<div class="text-center p-4">
									<img src="https://myfuture.vn/application/themes/images/no-data.png" height="200">
									<p class="text-muted mt-n2">Chưa có tiêu chí so sánh!</p>
								</div>
							</div>
						</td>
					</tr>
				{/if}
			</tbody>
		</table>
	{/if}
</div>