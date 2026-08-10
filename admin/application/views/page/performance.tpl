<header class="ui-title-bar-container ">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Hiệu suất đầu tư</h1>
			</div>
		</div> 
	</div>
</header>
<div class="clearfix"></div>
<form action="" method="post" enctype="multipart/form-data">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<section class="ui-annotated-section-container pb-3">
					<div class="ui-annotated-section" id="list_performance">
						<div class="group-parent group_item">
							<div class="d-flex justify-content-between align-items-center">
								<h2 class="ui-title-bar__title mb-0">Nguồn thu dự kiến</h2>
								<a href="javascript:void(0);" onClick="$Core.performance.add_item_performance(this,event)" data-type="child-1" data-property="revenue" class="btn btn-success">+</a>
							</div>
							{foreach from=$lst_investment_performance_revenue item=lst_child_1 key=k_child_1}
								<div class="group-child-1 group_item pl-8">
									<div class="form-row form-group align-items-end">
										<div class="col-md-1 text-right">
											<a href="javascript:void(0);" onClick="$Core.performance.add_item_performance(this,event)" data-type="child-2" data-property="revenue" data-uid1="{$k_child_1}" class="btn btn-success">+</a>
											<a href="javascript:void(0);" onClick="$Core.performance.delete_item_performance(this,event)" data-type="group-child-1" class="btn btn-danger me-1">-</a>
										</div>
										<div class="col-md-11">
											<label class="col-form-label">Tên thuộc tính 1</label>									
											<input type="text" class="form-control require ml-2" required="true" placeholder="Tên thuộc tính" name="revenue[{$k_child_1}][title]" value="{$lst_child_1.title}">
										</div>
									</div>
									{assign var=lst_child_2 value=$lst_child_1.lstChild}
									{foreach from=$lst_child_2 item=child_2 key=k_child_2}
										<div class="group-child-2 pl-8">
											<div class="form-row form-group align-items-end">
												<div class="col-md-1 text-right">
													<a href="javascript:void(0);" onClick="$Core.performance.delete_item_performance(this,event)" data-type="group-child-2" class="btn btn-danger me-1">-</a>
												</div>
												<div class="col-md-11">
													<div class="form-row">											
														<div class="col-md-6">
															<label class="col-form-label">Tên thuộc tính 2</label>									
															<input type="text" class="form-control require ml-2" required="true" placeholder="Tên thuộc tính" name="revenue[{$k_child_1}][lstChild][{$k_child_2}][title]" value="{$child_2.title}">
														</div>
														<div class="col-md-3">
															<label class="col-form-label">Giá trị</label>										
															<div class="input-group d-flex">
																<input type="number" class="form-control" value="{$child_2.value}" placeholder="Giá trị" id="introRate" name="revenue[{$k_child_1}][lstChild][{$k_child_2}][value]" min="0" step="0.1">
																<select class="form-control form-select" name="revenue[{$k_child_1}][lstChild][{$k_child_2}][unit_type]">
																	<option value="_PERCENT" {if $child_2.unit_type eq "_PERCENT"}selected{/if}>%</option>
																	<option value="_MONEY" {if $child_2.unit_type eq "_MONEY"}selected{/if}>VNĐ</option>
																</select>
															</div>
														</div>
														<div class="col-md-3">
															<label class="col-form-label">Thời gian</label>										
															<div class="input-group d-flex align-items-center">
																<input type="number" class="form-control calc_field mr-2" value="{$child_2.time}" placeholder="Thời gian" id="introMonths" name="revenue[{$k_child_1}][lstChild][{$k_child_2}][time]" min="0">
																<span class="input-group-text cursor-pointer">tháng</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									{/foreach}
								</div>	
							{/foreach}
						</div>
					</div>
				</section>
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section" id="list_performance">
						<div class="group-parent group_item">
							<div class="d-flex justify-content-between align-items-center">
								<h2 class="ui-title-bar__title mb-0">Chi phí bỏ ra</h2>
								<a href="javascript:void(0);" onClick="$Core.performance.add_item_performance(this,event)" data-type="child-1" data-property="expense" class="btn btn-success">+</a>
							</div>
							{foreach from=$lst_investment_performance_expense item=lst_child_1 key=k_child_1}
								<div class="group-child-1 group_item pl-8">
									<div class="form-row form-group align-items-end">
										<div class="col-md-1 text-right">
											<a href="javascript:void(0);" onClick="$Core.performance.add_item_performance(this,event)" data-type="child-2" data-property="expense" data-uid1="{$k_child_1}" class="btn btn-success">+</a>
											<a href="javascript:void(0);" onClick="$Core.performance.delete_item_performance(this,event)" data-type="group-child-1" class="btn btn-danger me-1">-</a>
										</div>
										<div class="col-md-11">
											<label class="col-form-label">Tên thuộc tính 1</label>									
											<input type="text" class="form-control require ml-2" required="true" placeholder="Tên thuộc tính" name="expense[{$k_child_1}][title]" value="{$lst_child_1.title}">
										</div>
									</div>
									{assign var=lst_child_2 value=$lst_child_1.lstChild}
									{foreach from=$lst_child_2 item=child_2 key=k_child_2}
										<div class="group-child-2 pl-8">
											<div class="form-row form-group align-items-end">
												<div class="col-md-1 text-right">
													<a href="javascript:void(0);" onClick="$Core.performance.delete_item_performance(this,event)" data-type="group-child-2" class="btn btn-danger me-1">-</a>
												</div>
												<div class="col-md-11">
													<div class="form-row">											
														<div class="col-md-6">
															<label class="col-form-label">Tên thuộc tính 2</label>									
															<input type="text" class="form-control require ml-2" required="true" placeholder="Tên thuộc tính" name="expense[{$k_child_1}][lstChild][{$k_child_2}][title]" value="{$child_2.title}">
														</div>
														<div class="col-md-3">
															<label class="col-form-label">Giá trị</label>										
															<div class="input-group d-flex">
																<input type="number" class="form-control" value="{$child_2.value}" placeholder="Giá trị" id="introRate" name="expense[{$k_child_1}][lstChild][{$k_child_2}][value]" min="0" step="0.1">
																<select class="form-control form-select" name="expense[{$k_child_1}][lstChild][{$k_child_2}][unit_type]">
																	<option value="_PERCENT" {if $child_2.unit_type eq "_PERCENT"}selected{/if}>%</option>
																	<option value="_MONEY" {if $child_2.unit_type eq "_MONEY"}selected{/if}>VNĐ</option>
																</select>
															</div>
														</div>
														<div class="col-md-3">
															<label class="col-form-label">Thời gian</label>										
															<div class="input-group d-flex align-items-center">
																<input type="number" class="form-control calc_field mr-2" value="{$child_2.time}" placeholder="Thời gian" id="introMonths" name="expense[{$k_child_1}][lstChild][{$k_child_2}][time]" min="0">
																<span class="input-group-text cursor-pointer">tháng</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									{/foreach}
								</div>
							{/foreach}
							
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