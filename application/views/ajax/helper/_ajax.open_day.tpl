<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
	<form class="modal-content" {if $deviceType eq 'phone'}style="border-radius:0"{/if} >
		<div class="modal-header">
			<div class="d-flex flex-column">
				<h5 class="modal-title text-main fw-bold">Ngày {$clsISO->convertTimeToTextFormat($time,"d/m/Y")} 
					<span class="text-dark fs-14 fw-normal">({$lunar_text} âm lịch)</span>
				</h5>
				<div class="txt_canchi text-main fst-italic"></div>
			</div>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body pt-0 text-dark">	
			<input type="hidden" name="total" value="{$total}">
			<input type="hidden" name="total_birthday" value="{$total_birthday}">
			<div class="d-flex gap-1 align-items-center justify-content-center position-relative px-4 day_special py-2 mx-auto fs-16 text-warning d-none" style="width: fit-content">
				<i class="bx bxs-quote-alt-left"></i> 
				<i class="text-center lh-lg xs:text-left xs:text-fs-16 txt_special"></i>
				<i class="bx bxs-quote-alt-right"></i>
			</div>
			{*<div class="py-2 border-top">
				<strong class="fs-16">Giờ đầu ngày</strong>
				<div class="can_hour0 d-flex flex-wrap gap-1 mt-2"></div>
			</div>
			<div class="py-2 border-top">
				<strong class="fs-16">Tiết khí</strong>
				<div class="tiet_khi d-flex flex-wrap gap-1 mt-2"></div>
			</div>*}
			<div class="py-2 border-top">
				<strong class="fs-16">Giờ hoàng đạo</strong>
				<div class="gio_hoang_dao d-flex flex-wrap gap-1 mt-2"></div>
			</div>
			{if !empty($list_billings)}
			<div class="py-2 border-top">
				<strong class="fs-16">Lịch ký</strong>
				<div class="contract_calendar mt-2">
					<div class="table-container no-shadow overflow-x-auto">
						<table class="table mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">
							<thead><tr>
								<th class="align-center" style="background: #FFF !important;">Mã căn</th>
								<th class="align-center">Admin</th>
								<th class="align-center text-right">T.Trạng</th>
								<th class="align-center text-right">Ghi chú</th>
							</tr></thead>
							<tbody class="table-border-bottom-0 billing_calendar" >			
								{foreach from=$list_billings item=_oBilling}
								{assign var=_oAdmin value=$_oBilling.admin}
								{assign var=_oStaff value=$_oBilling.staff}
								<tr class="trBilling">
									<td class="text-left text-nowrap">
										<div class="d-flex flex-column gap-1">
											<div class="d-flex align-items-center justify-content-between">
												<a href="javascript:void(0)" onclick="view_billing(this,event)" billing_id="{$_oBilling.billing_id}"><strong>{$_oBilling.stock_code}</strong> <sup>[{$_oBilling.text_type}]</sup></a>
												{if !empty($is_edit)}
												<a class="text-link" title="chỉnh sửa thông tin" onclick="$Core.billing.add_info(this,event)" 
													billing_id="{$_oBilling.billing_id}"><i class="bx bx-edit fs-14"></i></a>
												{/if}
											</div>
										</div>
									</td>
									<td class="text-left">
										<div class="d-flex align-items-center gap-1 justify-content-start">
											<img class="rounded-pill avatar avatar-xs" src="{if !empty($_oAdmin)}{$clsProfile->getAvatar($_oAdmin.profile_id,$_oAdmin)}{/if}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'">
											<div class="d-flex flex-column">
												<h4 class="text-fs-12 mb-0 fw-bold text-nowrap">{$_oAdmin.full_name}</h4>
												<div class="d-flex align-items-center gap-1 text-fs-11 text-muted text-nowrap">
													<i class="material-icons-outlined fs-13 no-translate">more_time</i>
													{$_oBilling.time}
												</div>
											</div>
										</div>
									</td>
									<td class="text-center">{$_oBilling.status}</td>
									<td class="text-center">
										{if !empty($_oBilling.is_note)}
										<a href="javascript:void(0)" class="btn btn-icon btn-outline-default btn-sm" data-toggle="webui-popover" data-trigger="click" data-type="async" billing_id="{$_oBilling.billing_id}" data-placement="left-bottom" data-closeable="false" data-url="{$PCMS}/index.php?mod=home&sub=calendar&act=load_rescheduling_reason&billing_id={$_oBilling.billing_id}">
											<i class='bx bx-notepad'></i>
										</a>
										{else}
										--
										{/if}
									</td>
								</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			{/if}
			{if !empty($lstNoteCRM)}
			<div class="py-2 border-top">
				<h5 class="fs-16 fw-bold mb-2">Cuộc hẹn khách hàng</h5>
				<div class="note_customer parent_more mt-2" data-max="3">
					{foreach from=$lstNoteCRM item=_oItem name=i}
					<div class="d-flex flex-column justify-content-between align-items-start p-2 rounded-2 position-relative item item_note bg-lighter mb-1"  >
						<div class="note-item"><strong>{$_oItem.time}</strong> — <strong>{$_oItem.followup_type_name}</strong> với khách hàng <strong>{$_oItem.name}</strong></div>
						<div class="d-flex gap-1 align-items-center">
							{$_oItem.intro}
						</div>						
					</div>
					{/foreach}
				</div>
			</div>
			{/if}
			{if !empty($lstCustomer)}
			<div class="py-2 border-top">
				<strong class="fs-16">Sinh nhật khách hàng</strong>
				<div class="birthday_customer mt-2">
					{foreach from=$lstCustomer item=_oItem}
					<div class="d-flex align-items-start gap-1 bg-lighter mb-1 p-2 rounded-2"  >
						<img src="{$URL_IMAGES}/icons/icon_birthday.png" alt="" width="20">
						<div class="d-flex gap-1 align-items-center">Khách hàng <strong>{$_oItem.name}</strong></div>						
					</div>
					{/foreach}
				</div>
			</div>
			{/if}
			{if !empty($lstProfileBirthday)}
			<div class="py-2 border-top">
				<strong class="fs-16">Sinh nhật thành viên {$smarty.const.BRAND_NAME}</strong>
				<div class="birthday_FH parent_more form-row row-cols-1 row-cols-md-2 mt-2" {if $deviceType eq 'phone'}data-max="3"{else}data-max="6"{/if}>
					{foreach from=$lstProfileBirthday item=_oItem}
						<div class="col item">
							<div class="d-inline-flex align-items-center bg-lighter mb-1 p-2 rounded-2 w-100">
								<img class="avatar avatar-sm mr-2 rounded-pill" src="{$clsProfile->getAvatar($_oItem.profile_id, $_oItem)}" onerror="this.src='{$URL_IMAGES}/no-image.png'" />
								<div class="line-height-0">
									<p class="mb-n1 text-nowrap fw-bold mb-1">{$clsProfile->getFullName($_oItem.profile_id, $_oItem)}</p>
									<small class="text-muted">{$_oItem.depart_name}</small>
								</div>
							</div>
						</div>
					{/foreach}
				</div>
			</div>
			{/if}
			{if !empty($list_events)}
			<div class="py-2 border-top">
				<strong class="fs-16">Sự kiện + Đào tạo</strong>
				<div class="birthday_FH parent_more mt-2" {if $deviceType eq 'phone'}data-max="3"{else}data-max="6"{/if}>
					{foreach from=$list_events item=_oItem}
						<div class="d-flex align-items-start gap-1 bg-lighter mb-1 p-2 rounded-2"  >
							<img src="{$URL_IMAGES}/icons/icon_event.png" alt="" width="20">
							<div class="d-flex flex-column">				
								<div class="fw-semibold">{$_oItem.title}{$_oItem.status}</div>
								<span class="time mb-1 text-muted fs-11">
									{$clsCourse->getTimeStartCourse($_oItem.start_date)} 
								</span>
							</div>						
						</div>
					{/foreach}
				</div>
			</div>
			{/if}
			<div class="py-2 border-top">
				<div class="d-flex justify-content-between align-items-center">
					<strong class="fs-16">Ghi chú</strong>
					<button class="btn btn-primary btn-sm" type="button" onClick="$Core.note_calendar.open_note(this,event)" note_id="0"  date="{$date}" _type="_note" toId="{$uid}" >Thêm ghi chú</button>
				</div>
				<div class="lst_note pb-2" id="lst_note_{$uid}">					
					<div class="text-muted">Chưa có ghi chú</div>
				</div>
			</div>
		</div>
	</form>
</div>