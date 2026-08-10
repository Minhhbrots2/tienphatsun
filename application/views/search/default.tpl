<div class="container-xxl flex-grow-1 pt-2 container-p-y">

	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

		<h4 class="fw-bold mb-0">Kết quả "<strong class="text-main fs-22">{$keyword}</strong>"</span></h4>

		{if $clsISO->checkPermission('view_stock_resource')}

		<a class="btn btn-sm btn-primary text-white {if $hide_agent eq '1'} hide-agent{/if}" t1="Ẩn ĐL" t2="Hiện ĐL" onClick="$Core.search.toggle_agent(this, event)">{if $hide_agent eq '1'}Hiện ĐL{else}Ẩn ĐL{/if}</a>

		{/if}

	</div>

	<div class="card no-shadow">

		<div class="card-body">

			{if !empty($lst_stocks) || !empty($lst_results) || !empty($lst_projects) || !empty($list_agent_stocks)}

				<div class="list-results sssssssss">

					{if !empty($lst_stocks)}

						<h4 class="position-relative search-header my-3">Căn hộ</h4>

						{if $deviceType ne 'phone'}<div class="row">{/if}

                        {foreach name=i from=$lst_stocks item = _oStock}

							{assign var = block_id value = $_oStock.block_id}

							{assign var = building_id value = $_oStock.building_id}

							{assign var = project_id value = $_oStock.project_id}

							{assign var = bedroom_id value = $_oStock.bedroom_id}

							{assign var = agency_id value = $_oStock.agency_id}

							{assign var = home_direction_id value = $_oStock.home_direction_id}

							{assign var = oneStatus value = $_oStock.oneStatus}

							{assign var = more_information value = $_oStock.more_information}

                            {if $deviceType eq 'phone'}

							<div class="list-result-item{if $smarty.foreach.i.last} border-0{/if}">

                                <h4 class="fs-6 d-flex justify-content-between mb-2">

									<a onClick="$Core.helper.open_stock({$_oStock.stock_id})" href="javascript:void(0)">{if $_oStock.is_fund_type}<span class="badge bg-green py-1 mr-1" title="Thứ cấp">TC</span>{/if} {$core->replaceString($_oStock.ms_code,$keyword)}, {if $_oStock.stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}Dãy{else}Tòa{/if} {$arr_cached_property.$building_id}-{$arr_cached_property.$block_id}-{$arr_cached_project.$project_id}</a>

									<span class="text-nowrap">{$clsISO->priceFormatV2($more_information.total_price_vat,3)} tỷ</span>

								</h4>

								<div class="d-flex align-items-center justify-content-between">

									<div class="fs-13">

										<span class="label d-inline-block mr-1" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:{$oneStatus.textcolor} ;background:{$oneStatus.bgcolor}">{$_oStock.status_name}</span>

										<span class="gdwYosCzip  mr-1">

											<i class="re__icon-size--sm"></i>

											{$more_information.DT_TT} m2

										</span>

										<span class="gdwYosCzip  mr-1">

											<i class="re__icon-bedroom--sm"></i> 

											{$arr_cached_property.$bedroom_id}

										</span>

										<span class="gdwYosCzip">

											<i class="re__icon-ying-yang--xl"></i>

											{$arr_cached_query.$home_direction_id}

										</span>

									</div>

									{if $clsISO->checkPermission('edit_stock_advanced') 

										&& $_oStock.status_id ne $smarty.const._STOCK_STATUS_SOLD_ID}

									<div class="d-flex gap-1">

										<span class="badge re__label-agency{if $hide_agent eq '1'} d-none{/if} text-nowrap bg-label-secondary">

											{$clsISO->truncate($arr_cached_query.$agency_id,2,"")}

										</span>

										{if $hide_stock_globe eq '1'}<button stock_id="{$_oStock.stock_id}" class="btn btn-sm px-1 py-0 {if $clsStock->checkShow($_oStock.show_website,'MOC')}btn-outline-primary{else}btn-outline-default{/if}" onClick="$Core.helper.hide_stock_MOC(this,event)" type="button">MOC</button>{/if}

									</div>

									{/if}

								</div>

							</div>

                            {else}

							{assign var = list_price_configs value = $_oStock.list_price_configs}

                            <div class="col-12">

                                <div class="list-result-item">

                                    <h4 class="fs-6 mb-2">

                                        <a onClick="$Core.helper.open_stock({$_oStock.stock_id})" href="javascript:void(0)">{if $_oStock.is_fund_type}<span class="badge bg-green py-1 mr-1" title="Thứ cấp">TC</span>{/if}{$core->replaceString($_oStock.ms_code,$keyword)}, {if $_oStock.stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}Dãy{else}Tòa{/if} {$arr_cached_property.$building_id}-{$arr_cached_property.$block_id}-{$arr_cached_project.$project_id}</a>

                                    </h4>

									<div class="d-flex align-items-center justify-content-between">

										<div class="d-flex align-items-center gap-2 fs-13">

											<span class="label d-inline-block" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:{$oneStatus.textcolor}; background:{$oneStatus.bgcolor}">

												{if $_oStock.status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}

													Đã bán

												{else}

													{if !empty($more_information.total_price_vat)}

														{$clsISO->priceFormatV2($more_information.total_price_vat,3)} tỷ

													{else}

														Check

													{/if}

												{/if}

											</span>

											{if !empty($list_price_configs) && $_oStock.status_id ne $smarty.const._STOCK_STATUS_SOLD_ID}

												{foreach from=$list_price_configs name=k item = _oPrice}

												<span class="label d-inline-block" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:{$oneStatus.textcolor}; background:{if $smarty.foreach.k.first}{$oneStatus.bgcolor}{else}{$_oPrice.bgcolor}; color:var(--bs-white){/if}">{$_oPrice.title} : {$clsISO->priceFormatV2($_oPrice.price, 3)} tỷ</span>

												{/foreach}

											{/if}

											<span class="gdwYosCzip">

												<i class="re__icon-size--sm"></i>

												{$more_information.DT_TT} m2

											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-bedroom--sm"></i> 

												{$arr_cached_property.$bedroom_id}

											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-ying-yang--xl"></i>

												{$arr_cached_query.$home_direction_id}

											</span>

										</div>

										{if $clsISO->checkPermission('edit_stock_advanced') 

											&& $_oStock.status_id ne $smarty.const._STOCK_STATUS_SOLD_ID}

										<div class="d-flex gap-2">

											<span class="badge{if $hide_agent eq '1'} d-none{/if} re__label-agency bg-label-secondary">{$arr_cached_query.$agency_id}</span>

											{if $hide_stock_globe eq '1'}<button stock_id="{$_oStock.stock_id}" class="btn btn-sm py-1/2 {if $clsStock->checkShow($_oStock.show_website,'MOC')}btn-outline-primary{else}btn-outline-default{/if}" onClick="$Core.helper.hide_stock_MOC(this,event)" type="button">{$core->makeIcon('check-square-o', 'MOC')}</button>{/if}

										</div>

										{/if}

									</div>

                                </div>

                            </div>

                            {/if}

						{/foreach}

                        {if $deviceType ne 'phone'}</div>{/if}

					{/if}

					{if !empty($list_agent_stocks)}

						{foreach from = $list_agent_stocks item= _oG}

						{assign var = list_stocks value = $_oG.list_stocks}

						{if !empty($list_stocks)}

							<h4 class="position-relative search-header my-3">{$_oG.title}({$_oG.property_code})</h4>

							{if $deviceType ne 'phone'}<div class="row">{/if}

							{foreach name=i from=$list_stocks item = _oStock}

								{assign var = project_id value = $_oStock.project_id}

								{assign var = block_id value = $_oStock.block_id}

								{assign var = building_id value = $_oStock.building_id}

								{assign var = bedroom_id value = $_oStock.bedroom_id}

								{assign var = agency_id value = $_oStock.agency_id}

								{assign var = home_direction_id value = $_oStock.home_direction_id}

								{assign var = oneStatus value = $_oStock.oneStatus}

								{assign var = more_information value = $_oStock.more_information}

								{if $deviceType eq 'phone'}

								<div class="list-result-item{if $smarty.foreach.i.last} border-0{/if}">

									<h4 class="fs-6 d-flex justify-content-between mb-2">

										<a onClick="$Core.helper.open_stock({$_oStock.stock_id})" href="javascript:void(0)">{if $_oStock.is_fund_type}<span class="badge bg-green py-1 mr-1" title="Thứ cấp">TC</span>{/if}{$core->replaceString($_oStock.ms_code, $keyword)}, {if $_oStock.stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}Dãy{else}Tòa{/if} {$arr_cached_property.$building_id}-{$arr_cached_property.$block_id}-{$arr_cached_project.$project_id}</a>

										<span>{$clsISO->priceFormatV2($more_information.total_price_vat,3)} tỷ</span>

									</h4>

									<div class="d-flex align-items-center justify-content-between">

										<div class="fs-13">

											<span class="label d-inline-block mr-1" style="transform:translateY(-2px); -moz-transform:translateY(-2px); -webkit-transform:translateY(-2px); color:{$oneStatus.textcolor} ;background:{$oneStatus.bgcolor}">{$_oStock.status_name}</span>

											<span class="gdwYosCzip mr-1">

												<i class="re__icon-size--sm"></i>

												{$more_information.DT_TT} m2

											</span>

											<span class="gdwYosCzip mr-1">

												<i class="re__icon-bedroom--sm"></i> 

												{$arr_cached_property.$bedroom_id}

											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-ying-yang--xl"></i>

												{$arr_cached_query.$home_direction_id}

											</span>

										</div>

										{if $clsISO->checkPermission('edit_stock_advanced') && $_oStock.status_id ne $smarty.const._STOCK_STATUS_SOLD_ID}

										<div class="d-flex gap-1">

											<span class="badge{if $hide_agent eq '1'} d-none{/if} re__label-agency bg-label-secondary">

												{$clsISO->truncate($arr_cached_query.$agency_id,2,'')}

											</span>

											{if $hide_stock_globe eq '1'}<button type="button" stock_id="{$_oStock.stock_id}" class="btn btn-sm px-1 py-0 {if $clsStock->checkShow($_oStock.show_website,'MOC')}btn-outline-primary{else}btn-outline-default{/if}" onClick="$Core.helper.hide_stock_MOC(this,event)">MOC</button>{/if}

										</div>

										{/if}

									</div>

								</div>

								{else}

								{assign var = list_price_configs value = $_oStock.list_price_configs}

								<div class="col-12">

									<div class="list-result-item">

										<h4 class="fs-6 mb-1">

											<a onClick="$Core.helper.open_stock({$_oStock.stock_id})" href="javascript:void(0)">{if $_oStock.is_fund_type}<span class="badge bg-green py-1 mr-1" title="Thứ cấp">TC</span>{/if}{$core->replaceString($_oStock.ms_code,$keyword)}, {if $_oStock.stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}Dãy{else}Tòa{/if} {$arr_cached_property.$building_id}-{$arr_cached_property.$block_id}-{$arr_cached_project.$project_id}</a>

										</h4>

										<div class="d-flex align-items-center justify-content-between gap-2">

											<div class="d-flex align-items-center gap-2 fs-13">

											{if !empty($list_price_configs) && $_oStock.status_id ne $smarty.const._STOCK_STATUS_SOLD_ID}

												{foreach from=$list_price_configs name = k item = _oPrice}

												<span class="label d-inline-block" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:{$oneStatus.textcolor}; background:{if $smarty.foreach.k.first}{$oneStatus.bgcolor}{else}{$_oPrice.bgcolor}; color:var(--bs-white){/if}">{$_oPrice.title} : {$clsISO->shortNumber($_oPrice.price)}</span>

												{/foreach}

											{else}

												<span class="label d-inline-block" style="transform:translateY(-3px); -moz-transform:translateY(-3px); -webkit-transform:translateY(-3px); color:{$oneStatus.textcolor} ;background:{$oneStatus.bgcolor}">

													{if $_oStock.status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}

														Đã bán

													{else}

														{if !empty($more_information.total_price_vat)}

															{$clsISO->shortNumber($more_information.total_price_vat)}

														{else}

															Check

														{/if}

													{/if}

												</span>

											{/if}

											<span class="gdwYosCzip">

												<i class="re__icon-size--sm"></i>

												{$more_information.DT_TT} m2

											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-bedroom--sm"></i> 

												{$arr_cached_property.$bedroom_id}

											</span>

											<span class="gdwYosCzip">

												<i class="re__icon-ying-yang--xl"></i>

												{$arr_cached_query.$home_direction_id}

											</span></div>

											{if $clsISO->checkPermission('edit_stock_advanced') 

												&& $_oStock.status_id ne $smarty.const._STOCK_STATUS_SOLD_ID}

											<div class="d-flex gap-2">

												<span class="badge{if $hide_agent eq '1'} d-none{/if} re__label-agency bg-label-secondary">{$arr_cached_query.$agency_id}</span>

												{if $hide_stock_globe eq '1'}<button stock_id="{$_oStock.stock_id}" class="btn btn-sm py-1/2 {if $clsStock->checkShow($_oStock.show_website,'MOC')}btn-outline-primary{else}btn-outline-default{/if}" onClick="$Core.helper.hide_stock_MOC(this,event)" type="button">{$core->makeIcon('check-square-o', 'MOC')}</button>{/if}

											</div>

											{/if}

										</div>

									</div>

								</div>

								{/if}

							{/foreach}	

							{if $deviceType ne 'phone'}</div>{/if}

						{/if}

						{/foreach}

					{/if}

					{if !empty($lst_projects)}

						<h4 class="position-relative search-header my-3">Tòa nhà, phân khu</h4>

						{foreach from=$lst_projects item = _oPro}

						{assign var = list_props value = $_oPro.list_props}

                        {assign var = list_attrs value = $_oPro.list_attrs}

						<div class="list-result-item">

							<h4 class="fs-6 mb-1">

                                <a target="_blank" href="{$_oPro.link}">{$core->replaceString($_oPro.title,$keyword)}</a>

                                {if !empty($list_attrs)}

                                <div class="d-flex flex-wrap my-1 gap-1">

                                    {foreach from=$list_attrs item = _oAttr}

                                    <span class="badge bg-label-default mb-1 xs:mb-1" style="color:#696cff">{$_oAttr.title}: {$_oAttr.content}</span>

                                    {/foreach}

                                </div>

                                {/if}

                            </h4>

							<a class="text-muted">{$_oPro.intro|strip_tags|truncate:100}</a>

                            {if !empty($list_props)}

							<div class="d-flex my-2 gap-1">

								{foreach from=$list_props item = _oProp}

								<a href="{$_oProp.link}" data-fancybox{if $_oProp.is_driver eq '1'} data-type="iframe"{/if} class="badge bg-label-primary">{$_oProp.title}</a>

								{/foreach}

							</div>

							{/if}

						</div>

						{/foreach}

					{/if}

					{if !empty($lst_results)}

						<h4 class="position-relative search-header my-3">Thông tin <span class="fs-13 text-muted">(<span class="text-main fs-bold">{$lst_results|@count}</span> kết quả)</span></h4>

						<div class="{if $deviceType eq 'phone'}form-{/if}row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-5">

							{foreach from=$lst_results item = _oResult} 

								{assign var = list_docs value = $_oResult.list_docs}

								{assign var = result_id value = $_oResult.id}							

								{foreach from=$list_docs item=_oDoc key=k_doc name=n_doc}

									<div class="col mb-4">

										{assign var = oneItem value = $_oDoc}

										{$core->getBlock('item_doc', ['_type'=>"search",'oneItem' => $oneItem,'result_id'=>$result_id,'keyword'=>$keyword,'_oResult'=>$_oResult])}

									</div>

								{/foreach}

							{/foreach}

						</div>

					{/if}

				</div>

			{else}

				<div class="p-0 p-lg-5 text-center">

					<img src="{$URL_IMAGES}/illustration-empty-results.svg"{if $deviceType eq 'phone'} width="100%"{/if} loading="lazy" />

					<p>Không tìm thấy kết quả phù hợp<p>

				</div>

			{/if}

		</div>

	</div>

</div>