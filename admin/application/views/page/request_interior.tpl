<header class="ui-title-bar-container ">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Thuộc tính yêu cầu thiết kế</h1>
			</div>
		</div> 
		<div class="action-bar" style="margin-top: -5px">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:void(0);" onClick="$Core.requesInterior.add_item(this,event)" data-type="parent" data-uid1="" data-action="append" class="ui-button ui-button--primary ui-title-bar__action">{$core->get_Lang('Addnew')}</a>
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
				<section class="ui-annotated-section-container pb-3">
					<div class="ui-annotated-section" id="list_requestInterior">
						{if !empty($lst_field_request_interior)}
							{foreach from=$lst_field_request_interior item=request key=k_req}
								{assign var=lstChild value=$request.child}
								<div class="group-parent">
									<h2 class="ui-title-bar__title mb-0">Thuộc tính</h2>
									<div class="form-row form-group align-items-start ">
										<div class="col-md-1 text-right">
											<a href="javascript:void(0);" onClick="$Core.requesInterior.add_item(this,event)" data-type="group-child" data-action="append" data-uid1="{$k_req}" class="btn btn-success btn_add" {if $request.type eq 'INPUT'}style="display:none"{/if}>+</a>
											<a href="javascript:void(0);" onClick="$Core.requesInterior.delete_item(this,event)" data-type="group-parent" class="btn btn-danger me-1">-</a>
										</div>
										<div class="col-md-11">
											<div class="form-row">
												<div class="col-md-9">																
													<input type="text" class="form-control require ml-2" required="true" placeholder="Tiêu đề" name="request[{$k_req}][title]" value="{$request.title}">
												</div>
												<div class="col-md-3">
													<select class="form-control form-select" name="request[{$k_req}][type]" onchange="$Core.requesInterior.add_item(this,event)" data-action="load" data-uid1="{$k_req}" data-type="group-child">
														<option value="">Lựa chọn</option>
														<option value="SELECT" {if $request.type eq 'SELECT'}selected{/if}>Menu</option>
														<option value="CHECKBOX" {if $request.type eq 'CHECKBOX'}selected{/if}>Lựa chọn</option>
														<option value="INPUT" {if $request.type eq 'INPUT'}selected{/if}>Tiêu đề</option>
													</select>
												</div>
											</div>
											<div class="lst_group_child">
												{if !empty(lstChild)}
													{foreach from=$lstChild item=child key=k_child}
														<div class="form-row align-items-end group-child">	
															<div class="col-md-1 text-right">
																<a href="javascript:void(0);" onClick="$Core.requesInterior.delete_item(this,event)" data-type="group-child" class="btn btn-danger me-1">-</a>
															</div>
															<div class="col-md-11">								
																<label class="col-form-label">Lựa chọn</label>									
																<input type="text" class="form-control require ml-2" required="true" placeholder="Lựa chọn" name="request[{$k_req}][child][]" value="{$child}">
															</div>
														</div>
													{/foreach}
												{/if}
											</div>											
										</div>							
									</div>
								</div>
							{/foreach}
						{/if}
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