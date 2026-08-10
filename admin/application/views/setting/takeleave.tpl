<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Setting')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Cấu hình Nghỉ phép</h1>
			</div>
		</div>
	</div>
</header>
<form method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="ui-layout__sections"><div class="ui-layout__section">
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="ui-annotated-section__content">
						<div class="form-row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Cài đặt chung</h2>
								</div>
								<div class="ui-annotated-section__description">
									Thiết lập chung các quy định nghỉ phép
								</div>
							</div>
							<div class="col-md-8">
								<div class="next-card">
									<div class="next-card__section">
										<div class="form-group font-weight-bold">
											Cộng ngày phép cho nhân viên vào ngày 
											<select name="takeleave_configs[leave_addition_date]" 
												class="form-control w-auto d-inline-block">
												{$clsISO->getSelect(1, 31, $takeleave_configs.leave_addition_date)}
											</select> 
											hàng tháng
										</div>
										<h5 class="font-weight-bold">Cho phép cộng dồn nghỉ phép qua hàng năm</h5>
										<div class="pl-8">
											<div class="d-flex align-items-center gap-4 mb-3">
												{assign var = toId value = $clsISO->getUniqid()}
												<div class="radio">
													<input id="{$toId}" type="radio" class="styled" name="is_leave_carryover"{if $takeleave_configs.is_leave_carryover eq '1'} checked{/if} value="1" />
													<label for="{$toId}">Có</label>
												</div>
												{assign var = toId value = $clsISO->getUniqid()}
												<div class="radio">
													<input id="{$toId}" type="radio" class="styled" name="is_leave_carryover"{if $takeleave_configs.is_leave_carryover eq '0'} checked{/if} value="0" />
													<label for="{$toId}">Không</label>
												</div>
											</div>
										</div>
										<h5 class="font-weight-bold">Số ngày phép được cộng thêm hàng tháng</h5>
										<div class="pl-8">
											<div class="form-group font-weight-bold">
												<select name="takeleave_configs[leave_days_per_month]" 
													class="form-control w-auto d-inline-block">
													{$clsISO->getSelect(1, 10, $takeleave_configs.leave_days_per_month)}
												</select> 
												<label class="col-form-label">ngày phép</label>	
											</div>
										</div>
										<h5 class="font-weight-bold">Thời điểm bắt đầu tính phép</h5>
										<div class="pl-8">
											<div class="d-flex align-items-center gap-4 mb-3">
												{assign var = toId value = $clsISO->getUniqid()}
												<div class="radio">
													<input id="{$toId}" type="radio" class="styled" 
														name="takeleave_configs[leave_accrual_start]"{if $takeleave_configs.leave_accrual_start eq 'official'} checked{/if} value="official" />
													<label for="{$toId}">Chính thức</label>
												</div>
												{assign var = toId value = $clsISO->getUniqid()}
												<div class="radio">
													<input id="{$toId}" type="radio" class="styled" 
														name="takeleave_configs[leave_accrual_start]"{if $takeleave_configs.leave_accrual_start eq 'probation'} checked{/if} value="probation" />
													<label for="{$toId}">Thử việc</label>
												</div>
											</div>
											<p>Tháng đầu tiên được tính phép nếu</p>
											<div class="form-group">
												{assign var = toId value = $clsISO->getUniqid()}
												<div class="radio">
													<input type="radio" onchange="x(this, event)" name="takeleave_configs[leave_start_month]" 
														value="any"{if $takeleave_configs.leave_start_month eq 'any'} checked{/if} id="{$toId}" />
													<label for="{$toId}">Tiếp nhận ngày bất kỳ trong tháng</label>
												</div>
											</div>
											<div class="form-group">
												{assign var = toId value = $clsISO->getUniqid()}
												<div class="radio">
													<input type="radio" onchange="x(this, event)" id="{$toId}" value="option" name="takeleave_configs[leave_start_month]"{if $takeleave_configs.leave_start_month eq 'option'} checked{/if} />
													<label for="{$toId}">Tuỳ chọn</label>
												</div>
											</div>
											<div class="pl-8">
												<div class="form-group">
													Tiếp nhận trước ngày <select{if $takeleave_configs.leave_start_month eq 'any'} disabled{/if} name="takeleave_configs[before_date][request_date]" class="form-control d-inline-block w-auto">
														{$clsISO->getSelect(1,31,$takeleave_configs.before_date.request_date)}
													</select> thì được tính <select{if $takeleave_configs.leave_start_month eq 'any'} disabled{/if} name="takeleave_configs[before_date][leave_days_allowed]" class="form-control d-inline-block w-auto">
														{$clsISO->getSelect(0,10,$takeleave_configs.before_date.leave_days_allowed)}
													</select> ngày phép
												</div>
												<div class="form-group">
													Tiếp nhận từ ngày <select{if $takeleave_configs.leave_start_month eq 'any'} disabled{/if} name="takeleave_configs[after_date][request_date]" class="form-control d-inline-block w-auto">
														{$clsISO->getSelect(1,31,$takeleave_configs.after_date.request_date)}
													</select> trở về sau thì được tính <select{if $takeleave_configs.leave_start_month eq 'any'} disabled{/if} name="takeleave_configs[after_date][leave_days_allowed]" class="form-control d-inline-block w-auto">{$clsISO->getSelect(0,10,$takeleave_configs.after_date.leave_days_allowed)}</select> ngày phép
												</div>
											</div>
											<div class="form-group">
												{assign var = toId value = $clsISO->getUniqid()}
												 <div class="checkbox">
													<input type="checkbox" id="{$toId}" class="styled" name="is_fulltime_only" value="1" {if $takeleave_configs.is_fulltime_only eq '1'} checked{/if}>
													<label for="{$toId}" name="takeleave_configs[is_fulltime_only]" value="1">Chỉ được sử dụng khi đã là nhân viên chính thức.(Thời gian thử việc vẫn có ngày phép nhưng chưa được sử dụng)</label>
												</div>
											</div>	
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="ui-annotated-section__content">
						<div class="form-row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Cài đặt người duyệt</h2>
								</div>
								<div class="ui-annotated-section__description">
									Thiết lập chung các quy định nghỉ phép
								</div>
							</div>
							<div class="col-md-8">
								<div class="next-card">
									<div class="next-card__section">
										{foreach from=$list_approved_by key = _oKey item = _oItem name = i}
										<div class="form-group{if !$smarty.foreach.i.last} lines{/if}">
											<label class="col-form-label">{$_oItem.title} duyệt <span class="text-gray">(tùy chọn)</span></label>
											<div class="d-flex  pull-right align-items-center gap-2">
												<div class="w-px-200">
													<label class="switch  pull-right">
														<input type="checkbox" value="1"{if $takeleave_configs.approver.$_oKey.status eq '1'} checked{/if} name="takeleave_configs[approver][{$_oKey}][status]">
														<span class="slider round"></span>
													</label>
												</div>
												{if $_oItem.has_select_staff eq '1'}
												<select name="takeleave_configs[approver][{$_oKey}][approver_id]" class="form-control iso-select2">
													<option>Lựa chọn người duyệt</option>
													{foreach from=$list_staffs item = _oStaff}
													<option{if $takeleave_configs.approver.$_oKey.approver_id eq $_oStaff.profile_id} selected{/if} value="{$_oStaff.profile_id}">{$clsProfile->getFullName($_oStaff.profile_id,$_oStaff)}</option>
													{/foreach}
												</select>
												{/if}
											</div>
										</div>
										{/foreach}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div></div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group">{$saveBtn}</div>
			</div>
		</div>
	</div>
</form>