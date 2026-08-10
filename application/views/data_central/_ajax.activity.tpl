{if $template_type eq '_modal'}
<div class="modal right fade" id="{$uid}" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content overflow-hidden">
			{literal}<style type="text/css">
				@media screen and (max-width:575px){
					.gPdbgmIWnr{ padding-left:0 !important}
					.gPdbgmIWnr{ width:calc(100% - 0px) !important}
					.gPdbgmIWnr .nav-link{ padding:0.5rem 0.5rem !important}
				}
			</style>{/literal}
			<div class="modal-header flex-column gPdbgmIWnr px-lg-0 py-0">
				<div id="ffHIpmIfQe" class="ffHIpmIfQe w-100 bg-lighter p-2 pl-3">
					<div class="d-flex justify-content-between align-items-center">
						<div class="uktQqXJiZX d-flex align-items-center gap-1">
							<div class="uktQqXJiZX">
								<div class="d-flex align-items-center"><a class="fw-bold link view_customer cursor-pointer" >{$oneCustomer.full_name} <i class="bx bx-link-external fs-13"></i></a></div>
								<div class="d-flex gap-1 align-items-center">
									{assign var = gid value = $clsISO->getUniqid()}
									<span class="status_{$gid}"><a onClick="$Core.data_central.open_upd_status(this, event)" uid="{$gid}" customer_id="{$customer_id}">{$clsProperty->getLabel($oneCustomer.status_id)}</a></span>
									<span class="text-warning d-flex align-items-center fs-12 gap-1">
										<i class="material-icons-outlined fs-13 no-translate">update</i>
										{$clsISO->getTimeAgo($oneCustomer.upd_date)}
									</span>
								</div>
							</div>
						</div>
						<div class="d-flex align-items-center gap-1">
							{if $deviceType eq 'phone'}
							<div class="btn-group">
								<button type="button" class="btn btn-primary dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true"><i class="bx bx-phone-call"></i> {$oneCustomer.phone}</button>
								<ul class="dropdown-menu dropdown-menu-end" data-popper-placement="top-start">
									<li><a class="dropdown-item" href="https://zalo.me/{$oneCustomer.phone}" target="_blank">
										<img src="{$URL_IMAGES}/zalo_chat.png" width="22px" /> Chat Zalo 
									</a></li>
									<li><a class="dropdown-item" href="tel:{$oneCustomer.phone}">
										<i class="bx bx-phone-call"></i> {$oneCustomer.phone}
									</a></li>
								</ul>
							</div>
							<button type="button" class="btn btn-icon btn-outline-default btn-icon bg-white close_pop" data-bs-dismiss="modal">
								<i class="bx bx-x"></i>
							</button>
							{else}
							<a class="btn btn-sm btn-outline-default" href="tel:{$oneCustomer.phone}">
								<i class="bx bx-phone-call"></i> {$oneCustomer.phone}
							</a>
							<a class="btn btn-sm btn-icon btn-outline-default" href="https://zalo.me/{$oneCustomer.phone}" target="_blank">
								<img src="{$URL_IMAGES}/zalo_chat.png" width="16px" />
							</a>
							{/if}
						</div>
					</div>
					<div class="d-flex align-items-center gap-1 tags-box_{$customer_id}">
						{$clsDataCentral->getHTMLTags($customer_id, $oneCustomer)}
					</div>
				</div>
				{if empty($check_expired)}
				<div class="clearfix"></div>
					{assign var = tabid value = $clsISO->getUniqid()}
					<ul class="nav ffHIpmIfQe nav-tabs nav-fill w-100 nav-tabs-bordered">
						{foreach name=i from=$list_activity item = _OA}
							{assign var = icon value = $_OA.image|cat:" fs-20"}
							<li class="nav-item"><a class="nav-link pb-3 {$tabid}" tabid="{$tabid}" onClick="$Core.data_central.open_activity(this, event)" tp="follow-ups" followup_id="0" type_id="{$_OA.property_id}" customer_id="{$customer_id}">{$clsISO->makeIcon($icon)}<br />
								<span class="fs-12 text-dark">{$_OA.title}</span>
							</a></li>
						{/foreach}
						<li class="nav-item"><a class="nav-link pb-3 {$tabid}" tabid="{$tabid}" onClick="$Core.data_central.open_activity(this, event)" tp="notes" note_id="0" customer_id="{$customer_id}" type_id="0">{$clsISO->makeIcon('bx-notepad fs-20')}<br >
							<span class="fs-12 text-link">Ghi chú</span>
						</a></li>
					</ul>
				{/if}
				{if $deviceType ne 'phone'}
				<button type="button" class="btn-close close_pop" data-bs-dismiss="modal"></button>
				{/if}
			</div>
			<div class="clearfix"></div>
			<div class="modal-body modal-body-scrollable pt-1">
				<div class="mb-4">
					{assign var = tabid value = $clsISO->getUniqid()}
					<ul class="nav nav-tabs nav-fill w-100 nav-tabs-bordered" role="tabpanel">
						<li class="nav-item"><a class="nav-link lrwkABnJPk js__tab-activity {$tabid} cursor-pointer active" onClick="$Core.data_central.sw_activity(this, event)" tp="activity" tabid="{$tabid}" customer_id="{$customer_id}">Hoạt động <span class="badge total_actity bg-success">{$total_activity}</span></a></li>
						<li class="nav-item"><a class="nav-link lrwkABnJPk js__tab-activity {$tabid} cursor-pointer" tp="notes" onClick="$Core.data_central.sw_activity(this, event)" tabid="{$tabid}" customer_id="{$customer_id}">Ghi chú <span class="badge total_notes bg-danger">{$total_notes}</span></a></li>
						<li class="nav-item"><a class="nav-link lrwkABnJPk js__tab-activity {$tabid} cursor-pointer" tp="consulting" onClick="$Core.data_central.sw_activity(this, event)" tabid="{$tabid}" customer_id="{$customer_id}">Tư vấn</a></li>
						<li class="nav-item"><a class="nav-link lrwkABnJPk js__tab-activity {$tabid} cursor-pointer" tp="logs" onClick="$Core.data_central.sw_activity(this, event)" tabid="{$tabid}" customer_id="{$customer_id}">Logs</a></li>
					</ul>
				</div>
				<div class="holder_activity_{$customer_id}">
					<div class="py-3">
						<div class="py-5 text-center">Đang tải dữ liệu...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{else}
<div class="mt-3">
	{if !empty($list_followups)}
	<div class="activity mb-0">
		{foreach from=$list_followups item= _oI}
		{assign var = list_reply value = $_oI.list_reply}
		{assign var = oneProperty value = $_oI.oneProperty}
		<div class="d-flex activity-item{if $_oI.status_id eq $smarty.const._FOLLOWUP_STATUS_DONE_ID} bg2-success{/if} mb-2">
			<span style="background:{$oneProperty.bgcolor}; color:{$oneProperty.textcolor}" class="activity-icon d-block mt-1 rounded-circle text-center border-0 mr-3 shadow-none">
				<i class="bx {$oneProperty.image} fs-20 m-2"></i>
			</span>
			<div class="activity-content position-relative rounded-1">
				{if !empty($_oI.title)}
				<div class="activity-header">
					<h6 class="mb-2">{$_oI.title}</h6>
				</div>
				{/if}
				
				{if !empty($_oI.campaign_name)}
					<small class="activity-body">
						<span class="mb-0">Chiến dịch: </span>
						<span class="mb-0 text-warning fw-bold">{$_oI.campaign_name}</span>
					</small>
				{/if}
				<div class="activity-body">
					<p class="mb-0">{$_oI.intro}</p>
					{if !empty($_oI._result)}
					--- <br />
					<strong>KQ:</strong> {$_oI._result}
					{/if}
				</div>
				<div class="activity-footer d-flex align-items-center justify-content-between">
					<div class="d-flex align-items-center gap-2">
						<a href="javascript:void(0);" followup_id="0" parent_id="{$_oI.followup_id}" customer_id="{$customer_id}" tp="follow-ups" type_id="{$_oI.type_id}" onClick="$Core.crm.open_reply(this, event)" class="fw-medium">
							<i class='bx bx-reply'></i> 
							<small>Trả lời</small>
						</a>
						<small class="text-{if $_oI.date_id gt $smarty.now}main{else}muted{/if} fw-medium">
							<i class="material-icons-outlined">update</i>
							{if $_oI.date_id gt $smarty.now}
								{$clsISO->getTimeMore($_oI.date_id)} 
							{else}
								{$clsISO->getTimeAgo($_oI.date_id)} 
							{/if}
						</small> 
						<small>
							<i class="material-icons-outlined">more_time</i>
							{$clsISO->getTimeAgo($_oI.reg_date)}
						</small>
						{if $_oI.admin_id eq $profile_id && $_oI.status_id ne $smarty.const._FOLLOWUP_STATUS_DONE_ID}
						<a href="javascript:void(0);" class="cursor-pointe" title="Hoàn thành" onClick="$Core.data_central.done_followup(this,event);" followup_id="{$_oI.followup_id}" tp="follow-ups" type_id="{$_oI.type_id}" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-check')}</a>{/if}
					</div>
					<img class="avatar rounded-pill avatar-xxs" src="{$_oI.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oI.admin_id}" data-toggle="webui-popover" data-width="350px" data-trigger="hover" data-width="400" data-placement="auto" />
				</div>
				{if  $_oI.admin_id eq $profile_id && empty($_oI.check_expired)}
				<div class="dropdown bvRzMvBFYd position-absolute">
					<button class="btn p-0 hide-arrow dropdown-toggle" data-bs-toggle="dropdown">
						<i class="bx bx-dots-vertical-rounded"></i>
					</button> 
					<div class="dropdown-menu">
						<a class="dropdown-item cursor-pointer" onClick="$Core.data_central.open_activity(this,event);" followup_id="{$_oI.followup_id}" tp="follow-ups" type_id="{$_oI.type_id}" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-edit-alt', 'Sửa')}</a>
						<a href="javascript:void(0);" class="dropdown-item cursor-pointer" onClick="$Core.data_central.delete_activity(this,event);" type_id="{$_oI.type_id}" followup_id="{$_oI.followup_id}" tp="follow-ups" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-trash', 'Xóa')}</a>
						{if $_oI.status_id ne $smarty.const._FOLLOWUP_STATUS_DONE_ID}
						<div class="dropdown-divider"></div>
						<a class="dropdown-item text-success cursor-pointer" onClick="$Core.data_central.done_followup(this,event);" followup_id="{$_oI.followup_id}" tp="follow-ups" type_id="{$_oI.type_id}" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-check-circle', 'Hoàn thành')}</a>
						{/if}
					</div>
				</div>
				{/if}
			</div>
		</div>
		{if !empty($list_reply)}
		<div class="reply-container">
			{foreach from= $list_reply item = _oReply}
			<div class="reply-item relative border p-2 rounded-2 my-2">
				<div class="reply-icon border bg-white rounded-pill zindex-2 position-absolute w-px-30 h-px-30 d-flex align-items-center justify-content-center">
					<i class='bx bx-comment'></i>
				</div>
				<div class="d-flex gap-2 justify-content-between">
					<div class="reply-item-body">
						<div class="">{$_oReply.intro}</div>
						<small class="text-muted fw-medium">
							<i class="material-icons-outlined">update</i>
							{$clsISO->getTimeAgo($_oReply.reg_date)}
						</small>
					</div>
					<div class="d-flex flex-column justify-content-between">
						{if empty($_oI.check_expired)}
							<div class="dropdown">
								<button class="btn p-0 hide-arrow dropdown-toggle" data-bs-toggle="dropdown">
									<i class="bx bx-dots-vertical-rounded"></i>
								</button> 
								<div class="dropdown-menu">
									<a class="dropdown-item cursor-pointer" onClick="$Core.crm.open_reply(this,event);" followup_id="{$_oReply.followup_id}" tp="follow-ups" parent_id="{$_oI.followup_id}" type_id="{$_oI.type_id}" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-edit-alt', 'Sửa')}</a>
								</div>
							</div>
						{/if}
						<img class="avatar rounded-pill avatar-xxs" src="{$_oReply.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oReply.user_id}" data-toggle="webui-popover" data-width="350px" data-trigger="hover" data-width="350" data-placement="auto" />
					</div>
				</div>
			</div>
			{/foreach}
		</div>
		{/if}
		{/foreach}
	</div>
	{else}
	<div class="p-5">
		{$htmlNotFound}
	</div>
	{/if}
</div>
{/if}