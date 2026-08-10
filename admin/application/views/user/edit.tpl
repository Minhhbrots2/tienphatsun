<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Administrators')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{if $pvalTable gt '0'}Cập nhật{else}Thêm{/if} tài khoản quản trị</h1>
			</div>
		</div>
	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>
</header>
<div class="clearfix"></div>
<form method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section">
						<div class="row">
							<div class="col-md-4">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Tài khoản quản trị</h2>
								</div>
								<div class="ui-annotated-section__description">
									Tất cả thông tin liên quan đến tài khoản, bao gồm cả Quyền truy cập các tính năng trong trang quản trị.
								</div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="form-group">
													<div class="col-md-6">
														<label class="col-form-label">Họ <span class="text-red">*</span></label>
														<input type="text" class="form-control required" required="true" placeholder="Nhập tên website" name="first_name" id="first_name" value="{$oneItem.first_name}" aria-required="true" placeholder="Nhập Họ" />
													</div>
													<div class="col-md-6">
														<label class="col-form-label">Tên <span class="text-red">*</span></label>
														<input type="text" class="form-control required" required="true" placeholder="Nhập tên website" name="last_name" id="last_name" value="{$oneItem.last_name}" aria-required="true" placeholder="Nhập tên" />
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-6">
														<label class="col-form-label">Tên đăng nhập<span class="text-red">*</span></label>
														<input type="text" class="form-control required" required="true" name="user_name" id="user_name" value="{$oneItem.user_name}" aria-required="true" placeholder="Nhập Username" />
													</div>
													<div class="col-md-6">
														<label class="col-form-label">Điện thoại <span class="text-gray">(tùy chọn)</span></label>
														<input type="text" class="form-control" name="phone" id="phone" value="{$oneItem.phone}" aria-required="true" placeholder="Nhập Điện thoại" />
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-6">
														<label class="col-form-label">E-Mail<span class="text-red">*</span></label>
														<input type="text" class="form-control required" required="true" name="email" id="email" value="{$oneItem.email}" aria-required="true" placeholder="Nhập E-Mail" />
													</div>
													<div class="col-md-6"></div>
												</div>
												<div class="form-group">
													<div class="col-md-12">
														<label class="col-form-label">Thông tin giới thiệu <span class="text-gray">(tùy chọn)</span></label>
														<textarea rows="3" class="form-control" placeholder="Nhập thông tin giới thiệu" name="about">{$oneItem.about}</textarea>
													</div>
												</div>
												<div class="form-group lines">
													<div class="col-md-6">
														<label class="col-form-label">Mật khẩu{if $pvalTable eq '0'}<span class="text-red">*</span>{/if}</label>
														<input type="password" {if $pvalTable gt '0'}class="form-control"{else}class="form-control required" required{/if} placeholder="Nhập mật khẩu" name="pass1" id="pass1" aria-required="true" />
														<span class="help-block text-gray">{$core->get_Lang('Enter only if you want to change the password')}</span>
													</div>
													<div class="col-md-6">
														<label class="col-form-label">Xác nhận mật khẩu{if $pvalTable eq '0'}<span class="text-red">*</span>{/if}</label>
														<input type="password" {if $pvalTable gt '0'}class="form-control"{else}class="form-control required" required{/if} placeholder="Xác nhận mật khẩu" name="pass2" id="pass2" aria-required="true" />
														<span class="help-block text-gray">{$core->get_Lang('Enter only if you want to change the password')}</span>
													</div>
												</div>
												<div class="form-group lines">
													<div class="col-md-12">
														<label class="col-form-label">Toàn quyền <span class="text-gray">(tùy chọn)</span></label>
														<input type="hidden" name="is_super" value="0" />
														<label class="switch pull-right">
															<input type="checkbox" value="1" name="is_super"{if $oneItem.is_super eq '1'} checked="checked"{/if}>
															<span class="slider round"></span>
														</label>
														<span class="help-block">Cho phép quản trị viên truy suất tất cả chức năng trong hệ thống</span>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-12">
														<label class="col-form-label">Kích hoạt tài khoản.</label>
														<input type="hidden" name="is_active" value="0" />
														<label class="switch pull-right">
															<input type="checkbox" value="1" name="is_active"{if $oneItem.is_active eq '1'} checked="checked"{/if}>
															<span class="slider round"></span>
														</label>
														<span class="help-block">Cho phép quản được phép truy cập vào trong hệ thống</span>
													</div>
												</div>
											</div>
										</div>
										<div class="next-card__section d-none">
											<div class="next-grid next-grid--no-outside-padding">
												<div class="next-grid__cell">
													<div class="next-grid next-grid--no-outside-padding">
														<div class="next-grid__cell">
															<h3 class="next-heading next-heading--small next-heading--half-margin">Chọn các chức năng tài khoản này có thể sử dụng</h3>
														</div>
													</div>
												</div>
											</div>
											<div class="ui-form__section">
												<div class="row">
													{foreach from = $listModule item = module}
													<div class="col-md-6 col-xs-12 mb-1">
														<div class="custom-checkbox-wrapper core-checkbox-custom">
															<label>
																<input{if $core->checkActiveModule($module.name)} disabled="disabled"{/if} name="permiss_mod[]" value="{$module.name}"{if $core->checkPermission($pvalTable,$module.name)} checked="checked"{/if} type="checkbox"> 
																<span class="custom-checkbox custom-icon"></span>
															</label> {$module.Display_Name}
														</div>
													</div>
													{/foreach}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<!-- End section -->
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default">
					{$core->makeIcon('angle-left', $core->get_Lang('Administrators'))}
				</a>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group">{$saveBtn}</div>
			</div>
		</div>
	</div>
</form>