<div id="{$uid}" class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">  
			<h5 class="modal-title">
				<img class="w-px-20" src="{$URL_IMAGES}/logo-header.png" />
				<strong class="font-18 ml-md-50 upper-case">Xin nghỉ phép</strong>
			</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<form method="post" action="{$PCMS_URL}/take-leave/edit/{$takeleave_id}" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="widget-block d-none">
					<div class="widget-header">Thông tin nhân viên</div>
					<div class="widget-content">
						<div class="form-group form-row">
							<div class="col-xs-12 col-md-4">
								<label class="col-form-label text-left">{$core->get_Lang('FullName')}<span class="text-red">*</span></label>
								<input type="text" class="form-control required" name="name" readonly value="{$clsProfile->getFullName($profile_id)}" placeholder="{$core->get_Lang('FullName')}" />
							</div>
							<div class="col-xs-12 col-md-4">
								<label class="col-form-label text-left">{$core->get_Lang('Department')}</label>
								<input type="text" class="form-control required" name="department_id" readonly value="{if !empty($department_id)}{$clsProperty->getTitle($department_id)}{/if}" placeholder="{$core->get_Lang('Department')}" />
							</div>
							<div class="col-xs-12 col-md-4">
								<label class="col-form-label text-left">{$core->get_Lang('Position')}</label>
								<input type="text" class="form-control required" name="position" value="{$oneTakeLeave.position}" placeholder="{$core->get_Lang('Position')}" />
							</div>
						</div>
					</div>
				</div>
				<div class="widget-block">
					<div class="widget-header">Thông tin nghỉ phép</div>
					<div class="widget-content">
						<div class="form-group form-row">
							<div class="col-xs-12 col-md-7">
								<label class="col-form-label">Người phụ trách</label>
								<select class="form-control form-select" name="curator_user_id">
									<option value="0">Chọn</option>
									{foreach from=$lstCurator item=item name=item}
									<option value="{$item.profile_id}"{if $item.profile_id eq $oneTakeLeave.curator_user_id} selected{/if}>	
										{$clsProfile->getIndentityV2($item.profile_id, $item)}
									</option>
									{/foreach}
								</select>
							</div>
							<div class="col-xs-12 col-md-5">
								<label class="col-form-label col-md-12">Loại nghỉ phép</label>
								<select class="form-control form-select" name="cat_property_id">
									<option value="0">Chọn</option>
									{foreach from=$lstCat item=item name=item}
										{if $item.property_id eq $oneTakeLeave.cat_property_id}
											{$cat_property_intro = $clsProperty->getIntro($item.property_id)}
										{/if}
										<option value="{$item.property_id}"{if $item.property_id eq $oneTakeLeave.cat_property_id} selected{/if}>{$clsProperty->getTitle($item.property_id,$item)}</option>
									{/foreach}
								</select>
							</div>
						</div>
						<div class="form-group mt-2">
							{if $oneTakeLeave.cat_property_id}
							<div class="alert alert-info alert-cat-property">{$cat_property_intro}</div>
							{else}
							<div class="alert alert-warning alert-cat-property">Bạn cần chọn loại nghỉ phép</div>
							{/if}
						</div>
						<div class="form-group form-row mb-2">
							<div class="col-md-4">
								<p class="mb-1 mt-3">Sử dụng nghỉ có lương(Năm nay)</p>
								<div class="input-group input-group-merge">
									<input type="number" class="form-control" name="number_day_paid_leave_this_year" onchange="set_total_day_takeleave(this, event)" value="{$oneTakeLeave.number_day_paid_leave_this_year}" 
									placeholder="Số ngày nghỉ" takeleave_id="{$takeleave_id}" /> 
									<div class="input-group-text">ngày</div>
								</div>
							</div>
							<div class="col-md-4"{if $smarty.const._MONTH_TAKE_LEAVE_RESET lt $clsISO->getDateCreateFormat($oneTakeLeave.start_date,"n")} style="display: none"{/if}>
								<p class="mb-1 mt-3">Sử dụng nghỉ có lương(Năm trước)</p>	
								<div class="input-group input-group-merge">
									<input type="number" class="form-control" name="number_day_paid_leave_last_year" 
									onchange="set_total_day_takeleave(this, event)" value="{$oneTakeLeave.number_day_paid_leave_last_year}" 
									placeholder="Số ngày nghỉ" takeleave_id="{$takeleave_id}" />
									<div class="input-group-text">ngày</div>
								</div>
							</div>
							<div class="col-md-4">
								<p class="mb-1 mt-3">Sử dụng nghỉ không lương</p>	
								<div class="input-group input-group-merge">
									<input type="number" class="form-control" onchange="set_total_day_takeleave(this, event)" 
									name="number_day_no_paid_leave" value="{$oneTakeLeave.number_day_no_paid_leave}" 
									placeholder="Số ngày nghỉ" takeleave_id="{$takeleave_id}" /> 
									<div class="input-group-text">ngày</div>
								</div>
							</div>
						</div>
						<div class="form-group input-group-date form-row mb-2">
							<div class="col-md-4">
								<p class="mb-1 mt-3">Tổng số ngày nghỉ</p>	
								<div class="input-group input-group-merge">
									<input type="number" class="form-control" name="number_day" value="{$oneTakeLeave.number_day}" 
									placeholder="Số ngày nghỉ" takeleave_id="{$takeleave_id}" /> 
									<div class="input-group-text">ngày</div>
								</div>
							</div>
							<div class="col-md-4">
								<p class="mb-1 mt-3">Từ ngày</p>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i class="bx bx-calendar"></i></span>
									<input type="text" readonly value="{$oneTakeLeave.start_date|date_format:"%d/%m/%Y"}" placeholder="dd/mm/yy" 
									name="start_date" class="form-control from_date w-px-100" takeleave_id="{$takeleave_id}">
									<select class="form-control w-px-100 form-select" name="start_time">
										{$clsISO->makeSelectTimeTakeLeave($oneTakeLeave.start_time)}
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<p class="mb-1 mt-3">Đến ngày </p>
								<div class="input-group input-group-merge">
									<span class="input-group-text"><i class="bx bx-calendar"></i></span>
									<input type="text" readonly value="{$oneTakeLeave.end_date|date_format:"%d/%m/%Y"}" placeholder="dd/mm/yy" 
									name="end_date" class="form-control to_date w-px-100" takeleave_id="{$takeleave_id}">
									<select class="form-control w-px-100 form-select" name="end_time">
										{$clsISO->makeSelectTimeTakeLeave($oneTakeLeave.end_time,'end')}
									</select>
								</div>
							</div>
						</div>
						<div class="form-group mb-2">
							<p class="mb-1 mt-3">Lí do nghỉ</p>
							<textarea id="reason_{$takeleave_id}" class="form-control required minlength" minlength="40" maxlength="10000" name="reason" rows="6">{$oneTakeLeave.reason}</textarea>
							<div class="alert mt-1 alert-danger">Ghi cụ thể lý do nghỉ- không chấp nhận lý do chung như: việc bận/ việc gia đình...</div>
						</div>
					</div>
				</div>
				<div class="widget-block">
					<div class="widget-header">
						<i class="fa fa-chevron-down mr-3"></i>Tình trạng xét duyệt</a>
					</div>
					<div class="widget-content mb-3">
						{foreach from=$lstApproval item=item name=item key=tp}
							{if $tp eq 'curator'}
								{assign var=tp_title value='Người phụ trách'}
							{elseif $tp eq 'head_of_dep'}
								{assign var=tp_title value='Giám đốc'}
							{elseif $tp eq 'hrad'}
								{assign var=tp_title value='Hành chính nhân sự'}
							{elseif $tp eq 'director'}
								{assign var=tp_title value='Ban giám đốc'}
							{/if}
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">
									{$tp_title}: {$clsProfile->getFullName($item.user_id)}
									<div class="pretty font-18 p-jelly p-icon">
											<input type="checkbox" {if $item.is_approval eq '1'}checked class="preventDefault"{/if} value="1">
											<div class="state p-primary">
												<i class="icon material-icons">done</i>
												<label class="bold">&nbsp;</label>
											</div>
										</div>
									</h3>
								</div>
								<div class="panel-body">
									{$item.content|html_entity_decode}
								</div>
							</div>
						{foreachelse}
						<div class="alert alert-warning">Chưa được duyệt</div>
						{/foreach}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="submit" value="Edit" />
				{if $is_view eq 0}<button type="button" class="btn btn-success pull-right" onClick="pop_save_takeleave(this, event); return false;" 
				takeleave_id="{$takeleave_id}">{$core->makeIcon('check',$core->get_Lang("Save"))}</button>{/if}
			</div>
		</form>
	</div>
</div>
{literal}
<style type="text/css">
	.w-250{width: 250px;}
	.input-group{flex-wrap:nowrap}
	.form-control:disabled, 
	.form-control[readonly]{background:rgba(255,255,255,1)}
	.modal-body{max-height:calc(100vh - 150px); overflow: auto;}
	.ui-datepicker{ z-index:9999 !important}
	@media (max-width: 767px) {
		.w-250{width: 100%;}
		.modal-body{max-height:70vh;}
	}
</style>
{/literal}