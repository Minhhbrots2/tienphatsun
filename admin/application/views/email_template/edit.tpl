<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Email Templates')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{if $pvalTable gt '0'}Cập nhật{else}Thêm{/if} Email Templates #{$pvalTable}</h1>
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
							<div class="col-md-3">
								<div class="ui-annotated-section__title">
									<h2 class="ui-heading">Email Templates</h2>
								</div>
								<div class="ui-annotated-section__description">
									Những email này được gửi tự động tới bạn hoặc khách hàng. Click vào tên mẫu email để chỉnh sửa.
								</div>
							</div>
							<div class="col-md-9">
								<div class="ui-annotated-section__content">
									<div class="next-card">
										<div class="next-card__section">
											<div class="ui-form__section form-horizontal">
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label">{$core->get_Lang('TemplateName')}</label>
														<input class="form-control" name="iso-title" value="{$oneItem.title}" placeholder="Nhập tên mẫu ở đây" maxlength="255" type="text" >
													</div>
												</div>
												<div class="form-group">
													<div class="col-xs-12 col-md-6">
														<label class="col-form-label">From name</label>
														<input type="text" class="form-control" placeholder="From name" name="iso-fromname" value="{$oneItem.fromname}" />
													</div>
													<div class="col-xs-12 col-md-6">
														<label class="col-form-label">From email</label>
														<input type="text" class="form-control" name="iso-fromemail" placeholder="example@gmail.com" value="{$oneItem.fromemail}" />
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label">{$core->get_Lang('Copy To')}</label>
														<input class="form-control" name="iso-copyto" value="{$oneItem.copyto}" placeholder="Nhập email tách nhau bằng dấu (,)" maxlength="255" type="text" >
														<small class="text-muted">Nhập email tách nhau bằng dấu (,)</small>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label">{$core->get_Lang('Subject')}</label>
														<input class="form-control" name="iso-subject" placeholder="Nhập subject ở đây" value="{$oneItem.subject}" maxlength="255" type="text" >
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label">{$core->get_Lang('Header')}</label>
														<textarea class="form-control isoTextArea" rows="3" cols="255" id="{$clsISO->getUniqid()}" name="iso-header">{$oneItem.header}</textarea>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label">{$core->get_Lang('Content')}</label>
														<textarea class="form-control isoTextArea" rows="10" cols="255" id="{$clsISO->getUniqid()}" name="iso-content">{$oneItem.content}</textarea>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md12 col-xs-12">
														<label class="col-form-label">{$core->get_Lang('Footer')}</label>
														<textarea class="form-control isoTextArea" rows="3" cols="255" id="{$clsISO->getUniqid()}" name="iso-footer">{$oneItem.footer}</textarea>
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
				<!-- End section -->
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default">
					{$core->makeIcon('angle-left', $core->get_Lang('Email Templates'))}
				</a>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group">{$saveBtn}</div>
			</div>
		</div>
	</div>
</form>

