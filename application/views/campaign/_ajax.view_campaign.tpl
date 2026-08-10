<div class="modal-dialog modal-ipad">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$oneCampaign.title}</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="overflow-x-auto text-nowrap">
				<table width="100%" class="table table-campaign">
					<thead><tr class="nohover">
						<th class="p_header text-center text-upper" colspan="5">
							<strong>Bảng xếp hạng {$oneCampaign.title}</strong>
							({$clsISO->convertTimeToText($oneCampaign.start_date)}-
							{$clsISO->convertTimeToText($oneCampaign.end_date)})
						</th>
					</tr>
                    {if $oneCampaign.selector eq 'staff'}
                        <tr>
                            <th class="p_head text-center">STT</th>
                            <th class="p_head text-center">Họ và tên</th>
                            <th class="p_head text-center">Tổng điểm</th>
                        </tr></thead>
                        {if !empty($list_staffs)}
                            {foreach name=i from=$list_staffs item=_oStaff}
                            <tr>
                                <td class="p_cell text-center font-bold">{$smarty.foreach.i.iteration}</td>
                                <td class="p_cell text-center font-bold">{$_oStaff.full_name}</td>
                                <td class="p_cell text-center font-bold">{$_oStaff.total_scores}</td>
                            </tr>
                            {/foreach}
                        {/if}
                    {else}
					<tr>
						<th class="p_head text-center">STT</th>
						<th class="p_head text-center">Team</th>
						<th class="p_head text-center">Họ & tên đội</th>
						<th class="p_head text-center">Tổng điểm</th>
					</tr></thead>
					<tbody>
						{if !empty($list_groups)}
							{foreach name=i from=$list_groups item = _oGroup}
							<tr class="{if !empty($_oGroup.total_scores)}lighter{/if}">
								<td class="p_cell text-center font-bold">{$smarty.foreach.i.iteration}</td>
								<td class="p_cell text-center font-bold">{$_oGroup.name}</td>
								<td class="p_cell text-center text-main">
									{if !empty($_oGroup.total_scores)}
										<strong>{$_oGroup.group_members}</strong>
									{else}
										{$_oGroup.group_members}
									{/if}
								</td>
								<td class="p_cell text-center font-bold">{$_oGroup.total_scores}</td>
							</tr>
							{/foreach}
						{/if}
					</tbody>
                    {/if}
					{if $oneCampaign.is_terms eq '1' && !empty($campaign_terms)}
					<tfoot>
						<tr class="nohover">
							<td style="background:#ffff0e" class="p_cell text-center" colspan="5">
								<div class="w-px-250 mx-auto">
									<table class="w-100 text-main">
										<thead><tr class="nohover">
											<td style="background:#ffff0e"  class="border-0 text-pink text-left" colspan="2"><strong>Cách tính điểm thi đua như sau:</strong></td>
										</tr></thead>
										<tbody>
											{foreach from=$campaign_terms key = prop_id item=score}
												{if !empty($score)}
												<tr class="nohover">
													<td class="text-left text-pink border-0">{$clsProperty->getTitle($prop_id)}</td>
													<td class="text-left text-pink border-0">
														<strong>{$score}</strong>
													</td>
												</tr>
												{/if}
											{/foreach}
										</tbody>
									</table>
								</div>
							</td>
						</tr>
					</tfoot>
					{/if}
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" 
			data-bs-dismiss="modal">Close</button>
		</div>
	</div>
</div>