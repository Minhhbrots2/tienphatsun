{if $template_type eq '_modal'}
{assign var = _cName value = $clsCustomer->getName($customer_id, $oneCustomer)}
{assign var = gid value = $clsISO->getUniqid()}
{assign var = tabid value = $clsISO->getUniqid()}
{assign var = tabid2 value = $clsISO->getUniqid()}
{assign var = _tags value = $clsCustomer->getHTMLTags($customer_id, $oneCustomer)}
<div class="modal right fade" id="{$uid}" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content overflow-hidden crm-tl">
			<div class="crm-tl-head">
				<div class="crm-tl-htop">
					<!-- <img class="crm-tl-avatar-img" src="{$URL_IMAGES}/no-avatar.jpg" onerror="this.onerror=null;this.src='{$URL_IMAGES}/no-avatar.jpg'" alt=""> -->
					<div class="crm-tl-hmain min-w-0">
						<a class="crm-tl-name cursor-pointer" onClick="$Core.crm.open_customer(this,event)" route="/customer/{$customer_id}/overview" customer_id="{$customer_id}">{$_cName|escape}</a>
						<div class="crm-tl-srow">
							<span class="status_{$gid} crm-tl-badge">{$clsProperty->getLabel($oneCustomer.status_id)}</span>
							{if $permiss_action eq '1'}<a class="btn btn-sm crm-tl-chuyen" onClick="$Core.crm.open_upd_status(this, event)" uid="{$gid}" customer_id="{$customer_id}">Chuyển <i class="bx bx-chevron-down"></i></a>{/if}
						</div>
						<div class="crm-tl-id crm-tl-metaline">
							{if $card_source}<span class="crm-tl-mi"><span class="crm-tl-k" title="Nguồn"><i class="bx bx-purchase-tag-alt"></i></span> <span class="crm-tl-v">{$card_source|escape}</span></span>{/if}
							{if $card_regdate}<span class="crm-tl-mi"><span class="crm-tl-k" title="Ngày tạo"><i class="bx bx-calendar"></i></span> <span class="crm-tl-v">{$card_regdate}</span></span>{/if}
							<span class="crm-tl-mi"><span class="crm-tl-k" title="SĐT"><i class="bx bx-phone"></i></span> <span class="crm-tl-v crm-tl-v--phone">{$clsCustomer->getPhoneRevealByCustomer($customer_id,$oneCustomer)}</span></span>
						</div>
						{if $owner_aid > 0}
						<div class="crm-tl-ownerrow">
							<div class="cmc-owner">
								<img class="avatar avatar-sm rounded-pill" src="{$owner_avatar}" onerror="this.onerror=null;this.src='{$URL_IMAGES}/no-avatar.jpg'" alt="">
								<div class="cmc-owner-tx">
									<div class="cmc-owner-nm">{$owner_name|escape}</div>{if $owner_dept}<div class="cmc-owner-dept">{$owner_dept|escape}</div>{/if}
								</div>
							</div>
						</div>
						{/if}
						<div class="crm-tl-actions">
							{if $permiss_action eq '1'}
							<a class="btn crm-tl-abtn {$tabid}" onClick="$Core.crm.open_activity(this, event)" tabid="{$tabid}" tp="follow-ups" followup_id="0" type_id="{$smarty.const._FOLLOWUP_CALL_ID}" 
								customer_id="{$customer_id}"><i class="bx bx-phone crm-ic-call"></i></a>
							<a class="btn crm-tl-abtn {$tabid}" onClick="$Core.crm.open_activity(this, event)" tabid="{$tabid}" tp="follow-ups" followup_id="0" type_id="{$smarty.const._FOLLOWUP_ZALO_ID}" 
								customer_id="{$customer_id}"><img src="{$URL_IMAGES}/zalo_logo.png" width="20px" /></a>
							{if !empty($card_task_current_id)}
								<a class="btn crm-tl-abtn crm-tl-abtn--result" onClick="$Core.crm.crm_task_result_sheet(this, event)" customer_id="{$customer_id}"><i class="bx bx-list-check"></i> Ghi kết quả</a>
							{/if}
							{/if}
							{if $fb_url}
							<a class="btn crm-tl-abtn" href="{$fb_url|escape}" target="_blank" rel="noopener"><i class="bx bxl-facebook-circle crm-ic-fb"></i> Facebook</a>
							{/if}
							{if $permiss_action eq '1' || $permiss_notes eq '1'}
							<div class="dropdown crm-tl-amore-wrap">
								<button type="button" class="btn crm-tl-abtn crm-tl-amore" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Thêm"><i class="bx bx-dots-horizontal-rounded"></i></button>
								<div class="dropdown-menu dropdown-menu-end">
									{if $permiss_action eq '1'}
										{foreach from=$list_activity item=_OA}
										<a class="dropdown-item cursor-pointer {$tabid}" onClick="$Core.crm.open_activity(this, event)" tabid="{$tabid}" tp="follow-ups" 
											followup_id="0" type_id="{$_OA.property_id}" customer_id="{$customer_id}"><i class="bx {$_OA.image}"></i> {$_OA.title}</a>
										{/foreach}
									{/if}
									{if $permiss_notes eq '1'}
									<a class="dropdown-item cursor-pointer {$tabid}" onClick="$Core.crm.open_activity(this, event)" tabid="{$tabid}" tp="notes" 
										note_id="0" type_id="0" customer_id="{$customer_id}"><i class="bx bx-note"></i> Ghi chú</a>
									{/if}
								</div>
							</div>
							{/if}
						</div>
					</div>
					<button type="button" class="crm-tl-x close_pop" data-bs-dismiss="modal" aria-label="Đóng"><i class="bx bx-x"></i></button>
				</div>
				{if $_tags|trim && 1==2}
					<div class="crm-tl-tags tags-box_{$customer_id}">{$_tags}</div>
				{/if}
				<div class="crm-tl-cards">
					<div class="crm-tl-card">
						<span class="crm-tl-ck crm-tl-ck--need">Nhu cầu</span>
						<span class="crm-tl-cv">{if $card_need}{$card_need|escape}{else}--{/if}</span>
					</div>
					<div class="crm-tl-card">
						<span class="crm-tl-ck crm-tl-ck--camp">Chiến dịch</span>
						<span class="crm-tl-cv">{if $card_campaign}{$card_campaign|escape}{else}--{/if}</span>
					</div>
					<div class="crm-tl-card crm-tl-card--last">
						<span class="crm-tl-ck"><i class="bx bx-time-five"></i> Tương tác cuối</span>
						<span class="crm-tl-cv crm-tl-cvlast">{if $card_last}{$card_last}{else}--{/if}</span>
					</div>
				</div>
				{if !empty($oneCustomer.begin_need)}
				<div class="border p-2 mt-2 rounded-2 bg-white">
					{$oneCustomer.begin_need}
				</div>
				{/if}
			</div>
			{if $card_next_task}
			<div class="crm-tl-next{if $card_next_overdue} crm-tl-next--over{/if}">
				<i class="bx bx-chevrons-right crm-tl-next-ic"></i>
				<span class="crm-tl-next-lb">Bước tiếp theo:</span>
				<span class="crm-tl-next-task">{$card_next_task|escape}</span>{$card_attempt_badge}
				{if $card_next_time}<span class="crm-tl-next-time"><i class="bx bx-time-five"></i> {$card_next_time|escape}</span>{/if}
				{if $permiss_action eq '1'}<button type="button" class="crm-tl-next-go" customer_id="{$customer_id}" onClick="$Core.crm.crm_task_move_next(this, event)" title="Chuyển sang thực hiện tác nghiệp này"><i class="bx bx-up-arrow-circle"></i></button><div class="tn-go-hint"><i class="bx bx-up-arrow-alt"></i> bấm để bắt đầu thực hiện</div>{/if}
			</div>
			{/if}
			<div class="crm-tl-tabs">
				<a class="crm-tl-tab js__tab-activity {$tabid2} cursor-pointer active" onClick="$Core.crm.sw_activity(this, event)" tp="activity" tabid="{$tabid2}" customer_id="{$customer_id}">Hoạt động <span class="cnt total_actity">{$total_activity}</span></a>
				{*<a class="crm-tl-tab js__tab-activity {$tabid2} cursor-pointer" tp="notes" onClick="$Core.crm.sw_activity(this, event)" tabid="{$tabid2}" customer_id="{$customer_id}">Ghi chú <span class="cnt cnt--muted total_notes">{$total_notes}</span></a>*}
				{* Ẩn tab Tư vấn theo yêu cầu (giữ lại markup để bật lại khi cần) *}
				{* <a class="crm-tl-tab js__tab-activity {$tabid2} cursor-pointer" tp="consulting" onClick="$Core.crm.sw_activity(this, event)" tabid="{$tabid2}" customer_id="{$customer_id}">Tư vấn</a> *}
				<a class="crm-tl-tab js__tab-activity {$tabid2} cursor-pointer" tp="logs" onClick="$Core.crm.sw_activity(this, event)" tabid="{$tabid2}" customer_id="{$customer_id}">Logs</a>
			</div>
			<div class="modal-body crm-tl-body">
				<div class="holder_activity_{$customer_id}">
					<div class="py-5 text-center text-muted">Đang tải dữ liệu...</div>
				</div>
				{if $permiss_action eq '1'}
				<div class="crm-tl-compose {$tabid}" onClick="$Core.crm.open_activity(this, event)" tabid="{$tabid}" tp="follow-ups" followup_id="0" 
					type_id="{$smarty.const._FOLLOWUP_CALL_ID}" customer_id="{$customer_id}"><i class="bx bx-message-rounded-add"></i> Thêm tương tác mới…</div>
				{/if}
			</div>
			<div class="modal-footer crm-tl-foot">
				<button type="button"{if !$permiss_assign eq '1'} disabled="disabled"{/if} class="btn flex-fill btn-outline-secondary" 
					onClick="$Core.crm.open_in_charge(this, event)" customer_id="{$customer_id}" title="Thay đổi người phụ trách"><i class="bx bx-user-check me-1"></i> Phụ trách</button>
				<button type="button"{if !$permiss_assign eq '1'} disabled="disabled"{/if} onClick="$Core.crm.add_participant(this, event)" 
					class="btn flex-fill btn-outline-secondary" customer_id="{$customer_id}" title="Thêm người liên quan"><i class="bx bx-user-plus me-1"></i> Người liên quan</button>
				<button type="button"{if $permiss_action ne '1'} disabled="disabled"{/if} class="btn flex-fill btn-primary" 
					onClick="$Core.crm.open_customer(this, event)" route="/customer/{$customer_id}/overview" customer_id="{$customer_id}" title="Sửa thông tin khách"><i class="bx bx-edit-alt me-1"></i> Sửa</button>
			</div>
		</div>
	</div>
</div>
{else}
<div class="crm-tl-stream">
	{if !empty($list_followups)}
	<div class="activity mb-0">
		{foreach from=$list_followups item= _oI}
		{assign var = list_reply value = $_oI.list_reply}
		{assign var = oneProperty value = $_oI.oneProperty}
		<div class="d-flex activity-item{if $_oI.status_id eq $smarty.const._FOLLOWUP_STATUS_DONE_ID} bg2-success{/if}">
			<span style="--ic-bg:{$oneProperty.bgcolor}; --ic-fg:{$oneProperty.textcolor}" class="activity-icon d-block rounded-circle text-center border-0 shadow-none">
				<i class="bx {$oneProperty.image}"></i>
			</span>
			<div class="activity-content position-relative">
				{if !empty($_oI.title)}
				<div class="activity-header">
					<h6 class="mb-2">{$_oI.title}</h6>
				</div>
				{/if}
				<div class="activity-body">
					<p class="mb-0">{$_oI.intro}</p>
				</div>
				{if !empty($_oI._result)}
				<div class="activity-result"><span class="activity-result__k">Kết quả</span> {$_oI._result}</div>
				{/if}
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
						{if $permiss_action eq '1' && $_oI.status_id ne $smarty.const._FOLLOWUP_STATUS_DONE_ID}
						<a href="javascript:void(0);" class="cursor-pointer" title="Hoàn thành" onClick="$Core.crm.done_followup(this,event);" followup_id="{$_oI.followup_id}" tp="follow-ups" type_id="{$_oI.type_id}" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-check')}</a>{/if}
					</div>
					<span class="d-flex align-items-center gap-1" title="Người phụ trách">
						<img class="avatar rounded-pill avatar-xxs" src="{$_oI.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oI.admin_id}" 
							data-toggle="webui-popover" data-width="350px" data-trigger="hover" data-placement="auto" />
					</span>
				</div>
				{if $permiss_action eq '1'}
				<div class="dropdown bvRzMvBFYd position-absolute">
					<button class="btn p-0 hide-arrow dropdown-toggle" data-bs-toggle="dropdown">
						<i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu">
						<a class="dropdown-item cursor-pointer" onClick="$Core.crm.open_activity(this,event);" followup_id="{$_oI.followup_id}" tp="follow-ups" type_id="{$_oI.type_id}" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-edit-alt', 'Sửa')}</a>
						<a href="javascript:void(0);" class="dropdown-item cursor-pointer" onClick="$Core.crm.delete_activity(this,event);" type_id="{$_oI.type_id}" followup_id="{$_oI.followup_id}" tp="follow-ups" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-trash', 'Xóa')}</a>
						{if $_oI.status_id ne $smarty.const._FOLLOWUP_STATUS_DONE_ID}
						<div class="dropdown-divider"></div>
						<a class="dropdown-item text-success cursor-pointer" onClick="$Core.crm.done_followup(this,event);" followup_id="{$_oI.followup_id}" tp="follow-ups" type_id="{$_oI.type_id}" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-check-circle', 'Hoàn thành')}</a>
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
						<div class="dropdown">
							<button class="btn p-0 hide-arrow dropdown-toggle" data-bs-toggle="dropdown">
								<i class="bx bx-dots-vertical-rounded"></i>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item cursor-pointer" onClick="$Core.crm.open_reply(this,event);" followup_id="{$_oReply.followup_id}" tp="follow-ups" parent_id="{$_oI.followup_id}" type_id="{$_oI.type_id}" customer_id="{$customer_id}">{$clsISO->makeIcon('bx-edit-alt', 'Sửa')}</a>
							</div>
						</div>
						<img class="avatar rounded-pill avatar-xxs" src="{$_oReply.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oReply.user_id}" data-toggle="webui-popover" data-width="350px" data-trigger="hover" data-placement="auto" />
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
