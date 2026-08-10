<div class="table-container overflow-auto text-nowrap no-shadow table-container2 " style="max-height: 300px">
	<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
		<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
			<tr>
				<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
				<th class="align-center h-px-40 zindex-3 text-center" colspan="2">{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}Phân khu{else}Dự án{/if}</th>
			</tr>
			<tr>
				<th class="align-center h-px-40 text-center">Có link</th>
				<th class="align-center h-px-40 text-center">Chưa có link</th>
			</tr>
		</thead>
		<tbody class="table-border-bottom-0">
			{if !empty($list_agency)}
				{assign var=index value=0}
				{foreach from=$list_agency item=_oItem name=i }
					{assign var = lstHasLink value = $_oItem.lstHasLink}
					{assign var = lstNotHasLink value = $_oItem.lstNotHasLink}
					<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
						<td class="text-nowrap" data-label="Tiêu đề" width="100px">{$_oItem.title}</td>
						<td class="text-center fw-bold">
							<div class="d-flex flex-wrap gap-1">
								{if !empty($lstHasLink)}
									{foreach from=$lstHasLink item=_oItemLink name=i_y}
										{if !$smarty.foreach.i_y.first}|{/if}<a href="{$_oItemLink.link}" target="_blank" class="text-link text-decoration-underline">{$_oItemLink.title}</a>
									{/foreach}
								{/if}
							</div>
						</td>
						<td class="text-center fw-bold">
							<div class="d-flex flex-wrap gap-1">
								{if !empty($lstNotHasLink)}
									{foreach from=$lstNotHasLink item=_oItemNotLink name=i_n}
										{if !$smarty.foreach.i_n.first}|{/if}<a class="text-danger">{$_oItemNotLink.title}</a>
									{/foreach}
								{/if}
							</div>
						</td>
					</tr>
				{/foreach}
			{else}
				<tr>
					<td class="text-center" colspan="3">
						Danh sách trống!
					</td>
				</tr>
			{/if}
		</tbody>
	</table>
</div>