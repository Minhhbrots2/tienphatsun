<header class="ui-title-bar-container ">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Hiệu suất đầu tư</h1>
			</div>
		</div> 		 
		<div class="action-bar" style="margin-top: -5px">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="$Core.loan_interest.add_page(this,event)" class="ui-button ui-button--primary ui-title-bar__action">Thêm page</a>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<form action="" method="post" enctype="multipart/form-data">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section" id="list_page">
						<div class="item_bank mb-3">  
							<div class="p-3 next-card">  
								<div class="item_body collapse in"  id="{$key}">
									<div class="form-row form-group">
										<div class="col-md-6">
											<label class="col-form-label">Key page <span class="text-red">*</span></label>
											<input type="text" class="form-control require" required="true" placeholder="Key page" name="data[{$key}][key_page]" value="{$_oItem.key_page}">
										</div>
										<div class="col-md-6">
											<label class="col-form-label">Class Name <span class="text-red">*</span></label>
											<div class="input-group input-group-merge d-flex align-items-center">
												<input type="text" class="form-control mr-2" value="{$_oItem.className}" placeholder="Class Name" id="className" name="data[{$key}][className]">
											</div>
										</div>
									</div>
									<div class="form-row form-group">
										<div class="col-md-12">
											<label class="col-form-label">Tiêu đề <span class="text-red">*</span></label>										
											<div class="input-group d-flex">
												<input type="text" class="form-control calc_field" value="{$_oItem.title}" placeholder="Tiêu đề" id="title" name="data[{$key}][title]" >
											</div>
										</div>
										<div class="col-md-12">
											<label class="col-form-label">Hình ảnh</label>	
											<div class="input-group">
												<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh" id="isoman_url_image" value="{$_oItem.image}">
												<div class="input-group-btn">
													<button class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="" isoman_name="image" style="padding:9px 10px"><i class="fa fa-image"></i></button>
												</div>	
											</div>
										</div>
										<div class="col-md-12">
											<label class="col-form-label">Nội dung <span class="text-red">*</span></label>										
											<div class="input-group d-flex w-100">
												<textarea id="{$clsISO->getUniqid()}" class="form-control isoTextArea w-100 " name="data[{$key}][intro]" cols="255" rows="15">{$_oItem.intro}</textarea>
											</div>
										</div>
									</div>
									<div class="d-flex justify-content-end">
										<button class="btn btn-danger ml-2" title="Xoá" type="button" onclick="$Core.loan_interest.delete_item_bank(this,event)">Xoá</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				
			</div>
		</div>
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