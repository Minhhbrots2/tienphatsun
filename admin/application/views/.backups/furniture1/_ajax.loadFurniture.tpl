{if !empty($lstCatFurniture)}
	{foreach from=$lstCatFurniture item=item name=i}
		{assign var=lstFurniture value=$item.lstFurniture}
		{if !empty($lstFurniture)}
			<div class="form-group item_property p-3 {if $smarty.foreach.i.last}mb-0{else}border-bottom{/if}">
				<label for="">{$item.title}</label>
				<div>
					<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">
						<thead><tr>
							<th class="text-left" width="25%" col-span="2">Tên sản phẩm</th>
							<th class="text-left" width="60%">Thuộc tính</th>
						</tr></thead>
						{section name=i loop=$lstFurniture}
							{assign var=price_property value=$lstFurniture[i].price_property}
							<tr class="{cycle values="row1,row2"} row_{$item.property_id}_{$lstFurniture[i].furniture_id}">
								<td class="text-left">{$lstFurniture[i].title}</td>
								<td class="text-left">
									<input type="hidden" class="furniture_hidden" name="furniture_ids[{$item.property_id}][]" value="{$lstFurniture[i].furniture_id}">
									<input type="hidden" class="price_hidden" name="prices[{$item.property_id}][{$lstFurniture[i].furniture_id}]" value="{$lstFurniture[i].price_hidden}">
									{if $lstFurniture[i].is_property eq "1"}
										<input type="hidden" class="property_hidden" name="property[{$item.property_id}][{$lstFurniture[i].furniture_id}]" value="{$lstFurniture[i].property_hidden}">
										<input type="hidden" class="number_hidden" name="number_pro[{$item.property_id}][{$lstFurniture[i].furniture_id}]" value="{$lstFurniture[i].number_hidden}">
										<div class="d-flex justify-content-between align-items-start">
											<button type="button" class="btn btn-default" onClick="$Core.furniture.proprertyFurniture(this,'open')" data-furniture_id="{$lstFurniture[i].furniture_id}" data-cat_id="{$item.property_id}" style="height:34px">Lựa chọn</button>
											<div class="p_detail w-80 pl-2">
												{foreach from=$price_property.detail name=i item=item_property key=key}
													<p>{$item_property.property} ({$item_property.number} x {$clsISO->formatPrice($item_property.price)}đ)</p>
												{/foreach}
											</div>
										</div>
									{else}
										<input type="hidden" class="number_hidden" name="number_pro[{$item.property_id}][{$lstFurniture[i].furniture_id}]" value="{$lstFurniture[i].number_hidden}">
										<div class="d-flex justify-content-between align-items-start">
											<div style="width:80px">
												<select name="number" class="iso-select2" onChange="$Core.furniture.updateNumberSelect(this,event)" data-price="{$lstFurniture[i].price}">
													<option value="">Chọn</option>
													{section name=j loop=10 start=0 step=1}
														<option value="{$smarty.section.j.iteration}" {if $lstFurniture[i].number_hidden eq $smarty.section.j.iteration}selected{/if} >{$smarty.section.j.iteration}</option>
													{/section}
												</select>
											</div>
											<div class="p_detail w-80 pl-2">
												{foreach from=$price_property.detail name=i item=item_property key=key}
													<p>{$item.number} x {$clsISO->formatPrice($item_property.price)}đ</p>
												{/foreach}
											</div>
										</div>
									{/if}
								</td>					
							</tr>
						{/section}
					</table>
				</div>
			</div>
		{/if}
	{/foreach}
{/if}