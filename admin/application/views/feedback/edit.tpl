<header class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Feedbacks')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$core->get_Lang('FeedbackContent')}</h1>
			</div>
		</div>
	</div>
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
								<div class="ui-annotated-section__title"><h2 class="ui-heading">Feedacks</h2></div>
								<div class="ui-annotated-section__description">Feedbacks là những nội dung được khách hàng gửi từ Form liên hệ ngoài website của bạn. Đi tới <a href="{$DOMAIN_NAME}/lien-he.html">trang liên hệ</a></div>
							</div>
							<div class="col-md-8">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<header class="ui-card__header">
											<div class="ui-stack ui-stack--wrap">
												<div class="ui-stack-item ui-stack-item--fill">
													<h2 class="ui-heading">{$core->get_Lang('Preview')}</h2>
												</div>
											</div>
										</header>
										<div class="next-card__section">
											<div class="ui-form__section">
												{assign var=FEEDBACKVALUE value = $clsISO->getArrayFromString($oneTable.feedback_store)}
												<table class="form" cellpadding="3" cellspacing="3" border="0" width="100%">
													<tr>
														<td colspan="2" style="text-align:center; text-transform:uppercase">{$core->get_Lang('contactdetail')}</td>
													</tr>
													<tr>
														<td width="15%" class="fieldarea">{$core->get_Lang('datetime')}:</td>
														<td class="fieldarea">{$clsISO->convertTimeTOText($oneTable.reg_date, true)}</td>
													</tr>
													<tr>
														<td class="fieldarea">{$core->get_Lang('Full Name')}:</td>
														<td class="fieldarea">{$oneTable.full_name}</td>
													</tr>
													<tr>
														<td class="fieldarea">{$core->get_Lang('phone')}:</td>
														<td class="fieldarea">{$oneTable.phone}</td>
													</tr>
													{if $oneTable.address}
													<tr>
														<td class="fieldarea">{$core->get_Lang('address')}:</td>
														<td class="fieldarea">{$oneTable.address}</td>
													</tr>
													{/if}
													<tr>
														<td class="fieldarea">Email:</td>
														<td class="fieldarea">{$oneTable.email}</td>
													</tr>
													<tr>
														<td class="fieldarea">{$core->get_Lang('message')}:</td>
														<td class="fieldarea">{$oneTable.message}</td>
													</tr>
												</table>
											</div>
										</div>
										<div class="next-card__section">
											<div class="form-group">
												<label for="" class="col-form-label">{$core->get_Lang('Notes')}</label>
												{$clsForm->showInput('note')}
											</div>
										</div>
										<div class="next-card__section ">
											<input value="Update" name="submit" type="hidden">
											<div class="row">
												<div class="col-md-6">
													<div class="checkbox">
														<input type="checkbox"{if $oneTable.is_done eq '1'} checked{/if} name="is_done" class="chkitem styled" value="1" />
														<label>Tick vào đây nếu Feedback này đã được xử lý</label>
													</div>
												</div>
												<div class="col-md-6 text-right">
													{$saveBtn}
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
</form>