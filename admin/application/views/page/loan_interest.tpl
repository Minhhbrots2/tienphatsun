<header class="ui-title-bar-container ">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Quản lý lãi suất ngân hàng</h1>
			</div>
		</div> 
		<div class="action-bar" style="margin-top: -5px">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="$Core.loan_interest.add_item_loan_interest(this,event)" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
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
					<div class="ui-annotated-section form-row" id="list_loan_interest">
						{foreach from=$lst_loan_interest key=key item=_oItem}
							<div class="item_bank col-md-6 mb-3">  
								<div class="p-3 next-card">  
									<div class="item_top d-flex justify-content-between align-items-start mb-2">
										<h2 class="title_item m-0 flex-fill" style="font-size:18px">{$_oItem.bank_name}</h2>
										<button class="btn ml-2" type="button" type="button" data-toggle="collapse" data-target="#{$key}" aria-expanded="false" aria-controls="{$key}"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
									</div>
									<div class="item_body collapse in"  id="{$key}">
										<div class="form-row form-group">
											<div class="col-md-12">
												<label class="col-form-label">Tên ngân hàng <span class="text-red">*</span></label>
												<input type="text" class="form-control require" required="true" placeholder="Tên ngân hàng" onKeyUp="$Core.loan_interest.setTitleItem(this,event)" name="data[{$key}][bank_name]" value="{$_oItem.bank_name}">
											</div>
										</div>
										<div class="form-row form-group">
											<div class="col-md-6">
												<label class="col-form-label">Lãi suất ưu đãi <span class="text-red">*</span></label>
												<div class="input-group input-group-merge d-flex align-items-center">
													<input type="number" class="form-control mr-2" value="{$_oItem.introRate}" placeholder="Lãi suất ưu đãi (%)" id="introRate" name="data[{$key}][introRate]" min="0" step="0.1">
													<span class="input-group-text cursor-pointer">%</span>
												</div>
											</div>
											<div class="col-md-6">
												<label class="col-form-label">Thời gian ưu đãi <span class="text-red">*</span></label>										
												<div class="input-group d-flex">
													<input type="number" class="form-control calc_field" value="{$_oItem.introMonths}" placeholder="Nhập thời gian" id="introMonths" name="data[{$key}][introMonths]" min="0">
													<select class="form-control form-select" name="data[{$key}][introMonthsUnit]">
														<option value="_MONTH" {if $_oItem.introMonthsUnit eq '_MONTH'}selected{/if}>Tháng</option>
														<option value="_YEAR" {if $_oItem.introMonthsUnit eq '_YEAR'}selected{/if}>Năm</option>
													</select>
												</div>
											</div>
										</div>
										<div class="form-row form-group">
											<div class="col-md-12">
												<label class="col-form-label">Lãi suất sau ưu đãi <span class="text-red">*</span></label>
												<div class="input-group input-group-merge d-flex align-items-center">
													<input type="number" class="form-control mr-2" value="{$_oItem.rate}" placeholder="Lãi suất sau ưu đãi (%)" id="rate" name="data[{$key}][rate]" min="0" step="0.1">
													<span class="input-group-text cursor-pointer">%</span>
												</div>
											</div>
										</div>
										<div class="d-flex justify-content-end">
											<button class="btn btn-danger ml-2" title="Xoá" type="button" onclick="$Core.loan_interest.delete_item_bank(this,event)">Xoá</button>
										</div>
									</div>
								</div>
							</div>
						{/foreach}
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