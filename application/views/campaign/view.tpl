<div class="container-xxl flex-grow-1 pt-2 container-p-y">
    <div class="col-12 col-md-8 offset-lg-2">
		<div class="alert alert-warning text-center mb-2">
			<a href="javascript:;" class="text-main d-flex font-bold text-upper align-items-center justify-content-center fs-20" campaign_id="{$oneCampaign.campaign_id}"><img src="{$URL_IMAGES}/gift-icon-hot.gif" class="w-px-30" /> {$oneCampaign.title}</a>
			<ul class="countdown mb-0" data-end="{$clsISO->convertTimeToTextFormat($oneCampaign.end_date)} 23:59:59">
				<li><span class="days">00</span>
					<p class="mb-0 days_text">ngày</p>
				</li>
				<li class="seperator">:</li>
				<li><span class="hours">00</span>
					<p class="mb-0 hours_text">giờ</p>
				</li>
				<li class="seperator">:</li>
				<li><span class="minutes">00</span>
					<p class="mb-0 minutes_text">phút</p>
				</li>
				<li class="seperator">:</li>
				<li><span class="seconds">00</span>
					<p class="mb-0 seconds_text">giây</p>
				</li>
			</ul>
		</div>
		<div class="iKuJnjIFyr {$oneCampaign.template}">
			<div class="hJsiGEcCOJ {$oneCampaign.template}">
				<table width="100%" class="table table-campaign {$oneCampaign.template}">
					<thead><tr class="nohover">
						<th class="p_header text-center text-upper" colspan="10">
							{if $oneCampaign.template ne 'template_1'}
							<div class="mb-2">
								<img src="{$URL_IMAGES}/logo-f.png" style="max-width: 72px" />
							</div>
							{/if}
							<strong>Bảng xếp hạng {$oneCampaign.title}</strong><br />
							({$clsISO->convertTimeToText($oneCampaign.start_date)}-
							{$clsISO->convertTimeToText($oneCampaign.end_date)})
						</th>
					</tr>
					{if $oneCampaign.selector eq 'staff'}
						<tr>
							<th class="p_head text-center">STT</th>
							<th class="p_head text-left">Họ và tên</th>
							<th class="p_head text-center">Tổng điểm</th>
							{if $clsISO->checkPermissionGroup('DIRECTOR') eq '1'}
							<th class="p_head text-center">Tổng tiền</th>
							{/if}
						</tr></thead>
						{if !empty($list_staffs)}
							{assign var = total_score value = $clsConfiguration->getValue('total_score')}
							{foreach name=i from=$list_staffs item=_oStaff}
							<tr class="p_row text-white">
								<td class="p_cell text-center font-bold">{$smarty.foreach.i.iteration}</td>
								<td class="p_cell font-bold">{$_oStaff.full_name}</td>
								<td class="p_cell text-center font-bold">{$_oStaff.total_scores}
									<!-- <span class="text-orange">/{$total_score}</span> -->
								</td>
								{if $clsISO->checkPermissionGroup('DIRECTOR') eq '1'}
								<td class="p_cell text-center font-bold">
									{$clsISO->formatPrice($_oStaff.total_expenses)} 
									{$clsISO->getRate()}
								</td>
								{/if}
							</tr>
							{/foreach}
						{/if}
						{if $clsISO->checkPermissionGroup('DIRECTOR') eq '1'}
						<tr class="p_row text-white">
							<td colspan="3" class="p_cell"></td>
							<td class="p_cell font-bold text-center">
								{$clsISO->formatPrice($total_all_expenses)}
								{$clsISO->getRate()}
							</td>
						</tr>
						{/if}
					{else}
						{if $oneCampaign.template eq 'template_1'}
						<thead><tr>
							<th width="40px" class="p_head text-center">STT</th>
							<th width="15%" class="p_head text-center">Team</th>
							<th class="p_head text-center">Họ & tên đội</th>
							<th width="15%" class="p_head text-center">{if $oneCampaign.is_term eq '1'}Tổng điểm{else}Giao dịch{/if}</th>
							{if isset($campaign_config.is_complete) && $campaign_config.is_complete eq '1'}
							<th width="15%" class="p_head text-center">%Hoàn thành</th>
							{/if}
						</tr></thead>
						{else}
						<thead><tr>
							<th width="40px" class="p_head text-center">STT</th>
							<th class="p_head text-left">Team</th>
							<th width="10%" class="p_head text-center">Tháng {$prev_month}</th>
							<th width="10%" class="p_head text-center">Tháng {$current_month}</th>
							<th width="10%" class="p_head text-center">Tổng giao dịch</th>
							{if isset($campaign_config.is_complete) && $campaign_config.is_complete eq '1'}
							<th width="10%" class="p_head text-center">%Hoàn thành</th>
							{/if}
						</tr></thead>
						{/if}
						{if !empty($list_groups)}
							{if $oneCampaign.template eq 'template_1'}
								{foreach name=i from=$list_groups item = _oGroup}
								<tr class="p_row {if !empty($_oGroup.total_scores)}lighter{/if}">
									<td class="p_cell text-center font-bold">{$smarty.foreach.i.iteration}</td>
									<td class="p_cell font-bold">{$_oGroup.name}</td>
									<td class="p_cell text-main">
										{if !empty($_oGroup.total_scores)}
											<strong>{$_oGroup.group_members}</strong>
										{else}
											{$_oGroup.group_members}
										{/if}
									</td>
									<td class="p_cell text-main">
										{if !empty($_oGroup.total_scores)}
											<strong>{$_oGroup.group_members}</strong>
										{else}
											{$_oGroup.group_members}
										{/if}
									</td>
									<td class="p_cell text-center font-bold">
										{$_oGroup.total_scores}
										{if isset($campaign_config.is_target) && $campaign_config.is_target eq '1'}
											<span class="text-orange">/{$_oGroup.group_target}</span>
										{/if}
									</td>
								</tr>
								{/foreach}
							{elseif $oneCampaign.template eq 'template_3'}
								{foreach name=i from=$list_groups item = _oGroup}
								{assign var = list_members value = $_oGroup.list_members}
								<tr class="p_row selected_tr {if !empty($_oGroup.total_scores)}lighter{/if}">
									<td class="p_cell text-center font-bold">{$smarty.foreach.i.iteration}</td>
									<td class="p_cell font-bold">{$_oGroup.name}</td>
									<td class="p_cell text-center font-bold">
										{$_oGroup.total_prev_group_trans}
										{if isset($campaign_config.is_target) && $campaign_config.is_target eq '1'}
											<span class="text-orange">/{$_oGroup.num_group_trans_before_month}</span>
										{/if}
									</td>
									<td class="p_cell text-center font-bold">
										{$_oGroup.total_group_trans}
										{if isset($campaign_config.is_target) && $campaign_config.is_target eq '1'}
											<span class="text-orange">/{$_oGroup.num_group_trans_curr_month}</span>
										{/if}
									</td>
									<td class="p_cell text-center font-bold">
										{$_oGroup.total_scores}
										{if isset($campaign_config.is_target) && $campaign_config.is_target eq '1'}
											<span class="text-orange">/{$_oGroup.group_target}</span>
										{/if}
									</td>
									{if isset($campaign_config.is_complete) && $campaign_config.is_complete eq '1'}
									<td class="p_cell text-center">
										{$_oGroup.percent_complete}%
									</td>
									{/if}
								</tr>
									{if !empty($list_members)}
										{foreach name=k from = $list_members item = _oMember}
										<tr>
											<td class="p_cell text-center">{$smarty.foreach.i.iteration}.{$smarty.foreach.k.iteration}</td>
											<td class="p_cell text-left">{$clsProfile->getIndentityV2($_oMember.profile_id, $_oMember, false)}</td>
											<td class="p_cell text-center">
												{$_oMember.num_prev_trans}
												{if isset($campaign_config.is_target) && $campaign_config.is_target eq '1'}
													<span class="text-orange">/{$_oMember.num_trans_before_month}</span>
												{/if}
											</td>
											<td class="p_cell text-center">
												{$_oMember.num_trans}
												{if isset($campaign_config.is_target) && $campaign_config.is_target eq '1'}
													<span class="text-orange">/{$_oMember.num_trans_curr_month}</span>
												{/if}
											</td>
											<td class="p_cell text-center">
												{$_oMember.total_scores}
												{if isset($campaign_config.is_target) && $campaign_config.is_target eq '1'}
													<span class="text-orange">/{$_oMember.num_target}</span>
												{/if}
											</td>
											<td class="p_cell fs-12 text-center">{$_oMember.usr_percent_complete}%</td>
										</tr>
										{/foreach}
									{/if}
								{/foreach}
							{/if}
						{/if}
					{/if}
					{if $oneCampaign.is_terms eq '1' && !empty($campaign_terms) && $oneCampaign.template ne 'template_2'}
					<tfoot>
						<tr class="nohover">
							<td class="p_cell bg-yellow text-center" colspan="5">
								<div class="w-px-250 mx-auto">
									<table class="w-100 text-main">
										<thead><tr class="nohover">
											<td style="background:#ffff0e" class="border-0 text-pink text-left" colspan="2">
												<strong>Cách tính điểm thi đua như sau:</strong>
											</td>
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
    </div>
</div>