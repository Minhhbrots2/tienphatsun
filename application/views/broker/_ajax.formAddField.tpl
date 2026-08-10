<div class="modal-dialog modal-dialog-centered {if $field eq 'history_sale' || $field eq 'customer_review' || $field eq 'working_process'}modal-md{else}modal-sm{/if}">
	<div class="modal-content">
		<div class="modal-header py-2">
			{if $field_id ne ""}
				<h3 class="modal-title"><strong>Sửa</strong></h3>
			{else}
				<h3 class="modal-title"><strong>Thêm mới</strong></h3>
			{/if}
		</div>
		<form action="" method="post" id="frmPayOther" encrupt="miltipart/form-data">
			{if $field eq "history_sale"}
				<div class="modal-body py-2">
					<div class="form-group form-row">
						<div class="col-md-4">
							<label class="w-100 col-form-label">Mã căn*</label>
							<div class="w-100">
								<input type="text" required="true" placeholder="Nhập mã căn" name="stock_code" maxlength="255" charet="UTF-8" class="form_field form-control required no-focus" value="{$oneItem.stock_code}">
							</div>
						</div>
						<div class="col-md-8">
							<label class="w-100 col-form-label">Dự án*</label>
							<div class="w-100">
								<input type="text" class="form_field form-control required" onchange="$Core.leasing.check_stock_code(this, event)" placeholder="Nhập tên dự án" name="project" value="{$oneItem.project}">
							</div>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-12 col-form-label">Tên khách hàng*</label>
						<div class="col-12">
							<input type="text" class="form_field form-control required" placeholder="Nhập tên khách hàng" name="customer_name" value="{$oneItem.customer_name}">
						</div>
					</div>
					<div class="form-group form-row">
						<div class="col-md-6">
							<label class="w-100 col-form-label">Số tiền*</label>
							<div class="w-100">
								<input type="text" class="form_field form-control price-In numberonly no-focus required" placeholder="2.345.000.000" name="price" value="{$oneItem.price}">
							</div>
						</div>
						<div class="col-md-6">
							<label class="w-100 col-form-label">Ngày giao dịch*</label>
							<div class="w-100">
								<div class="form-group box_inp_datepicker">
									<input type="text" class="form_field input_datepicker form-control datepicker required" placeholder="dd/mm/yyyy" name="date_trading" value="{$oneItem.date_trading}">
								</div>
							</div>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-12 col-form-label">Hình ảnh*</label>
						<div class="col-12">
							<div class="d-flex gap-2">
								<input class="form_field" type="hidden" name="image_hidden" value="{$oneItem.image}">
								<input type="file" class="form_field form-control flex-fill" placeholder="Hình ảnh" name="image" value="" onchange="document.getElementById('input_image{$uid}').src = window.URL.createObjectURL(this.files[0])">
								<img class="rounded" src="{$oneItem.image}" width="36" height="36" alt="" onerror="this.src='{$URL_IMAGES}/no-image.jpg'" id="input_image{$uid}">
							</div>
						</div>
					</div>
				</div>
			{elseif $field eq "working_process"}
				<div class="modal-body py-2">
					<div class="form-group form-row">
						<label class="col-12 col-form-label">Tên công ty*</label>
						<div class="col-12">
							<input type="text" class="form_field form-control required" placeholder="Nhập tên công ty" name="iso-company_name" value="{$oneItem.company_name}">
						</div>
					</div>
					<div class="form-group form-row">
						<div class="col-md-8">
							<label class="w-100 col-form-label">Chức vụ*</label>
							<div class="w-100">
								<input type="text" class="form_field form-control required" placeholder="Nhập chức vụ" name="iso-position" value="{$oneItem.position}">
							</div>
						</div>
						<div class="col-md-4">
							<label class="w-100 col-form-label">Thời gian công tác</label>
							<div class="w-100">
								<input type="text" class="form_field form-control required" placeholder="Nhập thời gian" name="iso-time" value="{$oneItem.time}">
							</div>
						</div>
					</div>	
					<div class="form-group form-row">
						<label class="col-12 col-form-label">Logo</label>
						<div class="col-12">
							<div class="d-flex gap-2">
								<input class="form_field" type="hidden" name="image_hidden" value="{$oneItem.image}">
								<input type="file" class="form_field form-control w-80 {if empty($field_id)}required{/if}" placeholder="Hình ảnh" name="image" value="" onchange="document.getElementById('input_image{$uid}').src = window.URL.createObjectURL(this.files[0])">
								<img class="rounded" src="{$oneItem.image}" width="36" height="36" alt="" onerror="this.src='{$URL_IMAGES}/no-image.jpg'" id="input_image{$uid}">
							</div>
						</div>
					</div>				
					<div class="form-group">
						<label class="col-form-label">Nội dung</label>
						<textarea class="form-control form_field isoTextArea" name="iso-content" cols="255" rows="5">{$oneItem.content|html_entity_decode}</textarea>
					</div>	
				</div>
			{else}
				<div class="modal-body py-2">
					<div class="form-group form-row">
						<label class="col-12 col-form-label">Tiêu đề*</label>
						<div class="col-12">
							<input type="text" class="form_field form-control required" placeholder="Nhập tiêu đề" name="title" value="{$oneItem.title}">
						</div>
					</div>
					{if $field eq "project"}
						<div class="form-group form-row">
							<label class="col-12 col-form-label">Số căn bán</label>
							<div class="col-12">
								<input type="text" class="form_field form-control price-In numberonly no-focus" placeholder="0" name="total_sale_project" value="{$oneItem.total_sale_project}">
							</div>
						</div>
					{/if}
					<div class="form-group form-row">
						<label class="col-12 col-form-label">Hình ảnh*</label>
						<div class="col-12">
							<div class="d-flex gap-2">
								<input class="form_field" type="hidden" name="image_hidden" value="{$oneItem.image}">
								<input type="file" class="form_field form-control w-80 {if empty($field_id)}required{/if}" placeholder="Hình ảnh" name="image" value="" onchange="document.getElementById('input_image{$uid}').src = window.URL.createObjectURL(this.files[0])">
								<img class="rounded" src="{$oneItem.image}" width="36" height="36" alt="" onerror="this.src='{$URL_IMAGES}/no-image.jpg'" id="input_image{$uid}">
							</div>
						</div>
					</div>
					{if $field eq "customer_review"}
						<div class="form-group form-row">
							<label class="col-12 col-form-label">Khách hàng</label>
							<div class="col-12">
								<div class="form-group">
									<input type="text" class="form_field form-control required" placeholder="Nhập tên khách hàng" name="customer_name" value="{$oneItem.customer_name}">
								</div>	
							</div>
						</div>
						<div class="form-group form-row">
							<label class="col-12 col-form-label">Đánh giá</label>
							<div class="col-12">
								<div class="d-flex gap-1 flex-wrap">
									{section name=i loop=5 start=0 step=1}
									{assign var=uid value=$clsISO->getUniqid()}
							   		<label class="we-radio" for="star_{$uid}">
									   	<input class="chk_star" type="radio" id="star_{$uid}" name="star" value="{$smarty.section.i.iteration}" {if ($smarty.section.i.last && empty($oneItem.star)) || $oneItem.star eq $smarty.section.i.iteration}checked{/if}>
									   	<span>{$smarty.section.i.iteration} sao</span>
								   	</label>
									{/section}
								</div>
							</div>
						</div>
						{*<div class="form-group form-row">
							<label class="col-12 col-form-label">Ngày đánh giá</label>
							<div class="col-12 ">
								<div class="form-group box_inp_datepicker">
									<input type="text" class="form_field input_datepicker form-control datepicker required" placeholder="dd/mm/yyyy" name="date" value="{$oneItem.date}">
								</div>								
							</div>
						</div>*}
					
						<div class="form-group form-row">
							<label class="col-12 col-form-label">Nội dung</label>
							<div class="col-12">
								<textarea class="form-control form_field" name="content" cols="255" rows="5">{$oneItem.content|html_entity_decode}</textarea>
							</div>
						</div>						
					{/if}
				</div>
			{/if}
			<div class="modal-footer">
				<a type="button" class="btn btn-outline-default" onClick="$Core.leasing.close_pop(this, event)" data-bs-dismiss="modal">
					Huỷ
				</a>
				<input type="hidden" name="field_id" value="{$field_id}">
				<button type="button" class="btn btn btn-primary" onClick="$Core.broker.AddField(this,{if $field eq 'history_sale'}'addHistorySale'{else}'add'{/if})" data-field="{$field}">
					{if $field_id ne ""}
						{$core->makeIcon('check', 'Sửa')}
					{else}
						{$core->makeIcon('check', 'Thêm')}
					{/if}
				</button>
			</div>
		</form>
	</div>
</div>