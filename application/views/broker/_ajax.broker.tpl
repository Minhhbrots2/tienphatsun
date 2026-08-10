{if $allItem}
	{section name=i loop=$allItem}
		{assign var=more_information value=$allItem[i].more_information}
		<div class="awe__broker-item col-xl-3 col-lg-4 col-md-6 mb-4">
			<div class="card no-shadow">
				<div class="card-body text-center">
					<div class="mx-auto mb-3">
						<a href="{$clsProfile->getLink($allItem[i].profile_id,$allItem[i])}" title="{$allItem[i].full_name}"><img src="{$FH_URL}/{$allItem[i].avatar}" alt="{$allItem[i].full_name}" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'" class="rounded-circle border w-px-100" style="height: 100px"></a>
					</div>
					<h5 class="mb-1 card-title"><a href="{$clsProfile->getLink($allItem[i].profile_id,$allItem[i])}" title="{$allItem[i].full_name}">{$allItem[i].full_name}</a></h5>
					<span>{$clsProperty->getTitle($allItem[i].role_id)}</span>
					<div class="d-flex align-items-center justify-content-center my-4 py-2">
						<div class="me-5">
							{if !empty($more_information.number_sale)}
								<h4 class="mb-1">{$more_information.number_sale}</h4>
							{else}
								<h4 class="mb-1">{$clsISO->formatNumberToEasyRead($allItem[i].total_billings)}</h4>
							{/if}							
							<span class="text-muted">Giao dịch</span>
						</div>
						<div>
							{if !empty($more_information.total_sales)}
								<h4 class="mb-1">{$more_information.total_sales}</h4>
							{else}
								<h4 class="mb-1">{$clsISO->shortNumber($allItem[i].total_sales)}</h4>
							{/if}							
							<span class="text-muted">Doanh số</span>     
						</div>
					</div>
					<div class="d-flex align-items-center justify-content-center">
						{if $allItem[i].phone ne ""}<a href="javascript:;" class="btn btn-primary d-flex align-items-center me-2" {if $deviceType eq 'phone'}style="font-size:0"{/if}><i class='bx bx-phone me-1 fs-5' ></i></i>{$clsProfile->mask($allItem[i].phone, 1)}</a>{/if}
<!--										<a href="javascript:;" class="btn btn-label-secondary btn-outline-default" {if $deviceType eq 'phone'}style="font-size:0"{/if} ><i class='bx bx-envelope me-1 fs-5' ></i>{$clsProfile->mask($allItem[i].email, 1)}</a>-->

							{if $oneProfile.profile_id ne $allItem[i].profile_id && $oneProfile.profile_id ne '118'}
								<button class="btn btn-icon btn-outline-default follow_profile fs-5 {if $clsISO->checkItemInArray($allItem[i].profile_id,$array_follow)}fo followed{/if}" type="button" onClick="$Core.broker.follow_broker(this,{$allItem[i].profile_id})" title="{if $clsISO->checkItemInArray($allItem[i].profile_id,$array_follow)}Bỏ theo dõi{else}Theo dõi{/if}" data-bs-trigger="hover" data-bs-toggle="tooltip">
									<i class='bx bx-user-check'></i>
								</button> 
							{/if}
					</div>
				</div>
			</div>
		</div>
	{/section}
{else}
	<div class="empty">
		<div class="p-5 text-center">
			<img src="{$URL_IMAGES}/listing-empty.svg" />
			<p>Không có kết quả nào phù hợp</p>
		</div>
	</div>
{/if}