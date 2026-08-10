{if !empty($lstBilling)}
	{foreach from=$lstBilling item=_oItem key=key name=i}
		{assign var=oneStaff value=$_oItem.oneStaff }
		{if $smarty.foreach.i.first && $current_page eq '1'}
		<div class="item_mileston item_big border-double  text-dark mb-3">
			<div class="form-row">
				<div class="col-9 flex-fill">
					<div class="item_header d-flex align-items-center">
						<div class="billing_code px-3 py-2 fw-bold">#{$clsISO->parseNumber2($_oItem.bill_number)}</div>
						<div class="time_deposit fw-bold text-black fst-italic">{$_oItem.deposit_date|date_format:"%d/%m/%Y"}</div>
					</div>
					<div class="item_body">
						<div class="staff_info d-flex align-items-center">
							<div class="border-double img_avatar rounded-pill overflow-hidden"><img src="{$clsProfile->getAvatar($_oItem.staff_id,$oneStaff,100,100)}" alt="" class="w-100 h-100 object-fit-cover"></div>
							<div class="flex-fill">
								<h3 class="name">{$clsProfile->getfullname($_oItem.staff_id,$oneStaff)}</h3>
								{if !empty($oneStaff.more_information.department_name)}
								<p class="role">{$oneStaff.more_information.department_name}</p>
								{/if}
							</div>
						</div>
						<div class="bill_info {if $deviceType ne 'phone'}border-bottom{/if}">
							<div class="bill_item">
								<span class="label_item">Dự án:</span>
								<strong class="">{$_oItem.project_name}</strong>
							</div>
							{if !empty($_oItem.bedroom)}
							<div class="bill_item">
								<span class="label_item">Loại căn:</span>
								<strong>{$_oItem.bedroom}</strong>
							</div>
							{/if}
							{if !empty($_oItem.type_villa)}
							<div class="bill_item">
								<span class="label_item">Loại căn:</span>
								<strong>{$_oItem.type_villa}</strong>
							</div>
							{/if}
							{if !empty($_oItem.total_price)}
							<div class="bill_item">
								<span class="label_item">Doanh số :</span>
								<strong>{$_oItem.total_price}</strong>
							</div>
							{/if}
						</div>
						{if $deviceType ne 'phone'}
						<div class="content_item">{$_oItem.content}</div>
						{/if}
					</div>
				</div>
				{if !empty($_oItem.image_poster)}
				<div class="col-3">
					<div class="box_poster">
						<img src="{$_oItem.image_poster}" alt="" class="w-100 object-fit-cover rounded-3 border-double" data-fancybox="poster" data-src="{$_oItem.image_poster}" data-caption='<div class="staff_info d-flex justify-content-center align-items-center gap-2" loading="lazy">
							<div class="avatar avatar-md rounded-pill overflow-hidden"><img src="{$clsProfile->getAvatar($_oItem.staff_id,$oneStaff)}" alt="" class="w-100 h-100 object-fit-cover"></div>
							<div class="text-left">
								<h3 class="name mb-1">{$clsProfile->getfullname($_oItem.staff_id,$oneStaff)}</h3>
								{if !empty($oneStaff.more_information.department_name)}
								<p class="role mb-0">{$oneStaff.more_information.department_name}</p>
								{/if}
							</div>
						</div>'>
					</div>
				</div>
				{/if}
			</div>
		</div>
		{else}
		<li class="timeline-item {if $smarty.foreach.i.index eq 1}timeline_item_first{/if}">
			<span class="timeline-indicator timeline-indicator-primary aos-init aos-animate p-1" data-aos="zoom-in" data-aos-delay="200">
			<img class="w-100" src="{$URL_IMAGES}/billing.png" width="30">
			</span>
			<div class="timeline-event card p-0 aos-init aos-animate" data-aos="{cycle values="fade-right,fade-left"}">
				<div class="item_mileston item_small border-double text-dark h-100">
					<div class="form-row">
						<div class="col-8 flex-fill">
							<div class="item_header d-flex align-items-center">
								<div class="billing_code px-3 py-2 fw-bold">#{$clsISO->parseNumber2($_oItem.bill_number)}</div>
								<div class="time_deposit fw-bold text-black fst-italic">{$_oItem.deposit_date|date_format:"%d/%m/%Y"}</div>
							</div>
							<div class="item_body">
								<div class="staff_info d-flex align-items-center">
									<div class="border-double img_avatar rounded-pill overflow-hidden"><img src="{$clsProfile->getAvatar($_oItem.staff_id,$oneStaff,70,70)}" alt="" class="w-100 h-100 object-fit-cover" ></div>
									<div class="flex-fill">
										<h3 class="name">{$clsProfile->getfullname($_oItem.staff_id,$oneStaff)}</h3>
										{if !empty($oneStaff.more_information.department_name)}
										<p class="role mb-1">{$oneStaff.more_information.department_name}</p>
										{/if}
									</div>
								</div>
								<div class="bill_info">
									<div class="bill_item">
										<span class="label_item">Dự án:</span>
										<strong class="">{$_oItem.project_name}</strong>
									</div>
									{if !empty($_oItem.bedroom)}
									<div class="bill_item">
										<span class="label_item">Loại căn:</span>
										<strong class="">{$_oItem.bedroom}</strong>
									</div>
									{/if}
									{if !empty($_oItem.type_villa)}
									<div class="bill_item">
										<span class="label_item">Loại căn:</span>
										<strong class="">{$_oItem.type_villa}</strong>
									</div>
									{/if}
									{if !empty($_oItem.total_price)}
									<div class="bill_item">
										<span class="label_item">Doanh số:</span>
										<strong class="">{$_oItem.total_price}</strong>
									</div>
									{/if}
								</div>
							</div>
						</div>
						{if !empty($_oItem.image_poster)}
						<div class="col-4">
							<div class="box_poster">
							<img src="{$_oItem.image_poster}" alt="" class="w-100 object-fit-cover rounded-3 border-double" data-fancybox="poster" data-src="{$_oItem.image_poster}" data-caption='<div class="staff_info d-flex justify-content-center align-items-center gap-2" loading="lazy">
								<div class="avatar avatar-md rounded-pill overflow-hidden"><img src="{$clsProfile->getAvatar($_oItem.staff_id,$oneStaff)}" alt="" class="w-100 h-100 object-fit-cover"></div>
								<div class="text-left">
									<h3 class="name mb-1">{$clsProfile->getfullname($_oItem.staff_id,$oneStaff)}</h3>
									{if !empty($oneStaff.more_information.department_name)}
									<p class="role mb-0">{$oneStaff.more_information.department_name}</p>
									{/if}
								</div>
							</div>'>
							</div>
						</div>
						{/if}
					</div>
				</div>					
				<div class="timeline-event-time d-none"><div class="content_item fs-18 text-dark">{$_oItem.content}</div></div>
			</div>
		</li>
		{/if}
	{/foreach}
{elseif $page gt 1}
	<div class="text-center text-center bg-white p-3 rounded-3" colspan="12">
		<img src="{$URL_IMAGES}/listing-empty.svg" class="w-px-150">
		<p clas="text-muted">Danh sách trống</p>
	</div>
{/if}