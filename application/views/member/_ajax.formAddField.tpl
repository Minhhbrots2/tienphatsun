
<div class="modal-dialog modal-standard">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			{if $field_id ne ""}
				<h3 class="modal-title"><strong>Sửa</strong></h3>
			{else}
				<h3 class="modal-title"><strong>Thêm mới</strong></h3>
			{/if}
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				{if $field eq "history_sale"}
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Mã căn*</label>
						<div class="col-xs-12 col-md-10">
							<input type="text" class="form-control form_field required" placeholder="Nhập mã căn" name="stock_code" value="{$oneItem.stock_code}">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Dự án*</label>
						<div class="col-xs-12 col-md-10">
							<input type="text" class="form-control form_field required" placeholder="Nhập tên dự án" name="project" value="{$oneItem.project}">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Tên khách hàng*</label>
						<div class="col-xs-12 col-md-10">
							<input type="text" class="form-control form_field required" placeholder="Nhập tên khách hàng" name="customer_name" value="{$oneItem.customer_name}">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Số tiền*</label>
						<div class="col-md-3">
							<input type="text" class="form-control form_field required" placeholder="2.345.000.000" name="price" value="{$oneItem.price}">
						</div>
						<label class="col-md-2 text-right col-form-label">Ngày giao dịch*</label>
						<div class="col-md-3">
							<input type="text" class="input_datepicker form_field form-control datepicker required" placeholder="dd/mm/yyyy" name="date_trading" value="{$oneItem.date_trading}">
						</div>
					</div>
				{else}	
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Tiêu đề*</label>
						<div class="col-xs-12 col-md-10">
							<input type="text" class="form-control form_field required" placeholder="Nhập tiêu đề" name="title" value="{$oneItem.title}">
						</div>
					</div>
					{if $field eq "project"}
						<div class="form-group form-row">	
							<label class="col-md-2 text-right col-form-label">Số căn bán</label>
							<div class="col-md-3">
								<input type="text" class="form-control form_field" placeholder="0" name="total_sale_project" value="{$oneItem.total_sale_project}">
							</div>
						</div>
					{/if}
					<div class="form-group form-row">
						<label class="col-md-2 text-right col-form-label">Hình ảnh*</label>
						<div class="col-xs-12 col-md-10">
							<div class="input-group w-100">
								<input class="form_field" type="hidden" name="image_hidden" value="{$oneItem.image}">
								<input type="file" class="form_field form-control w-80 mr-2 {if empty($field_id)}required{/if}" placeholder="Hình ảnh" name="image" value="" onchange="document.getElementById('input_image{$uid}').src = window.URL.createObjectURL(this.files[0])">
								<img class="rounded" src="{$oneItem.image}" width="36" height="36" alt="" onerror="this.src='{$URL_IMAGES}/none_image.png'" id="input_image{$uid}">
							</div>
						</div>
					</div>
					{if $field eq "customer_review"}
						<div class="form-group form-row">
							<label class="col-md-2 text-right col-form-label">Đánh giá</label>
							<div class="col-xs-12 col-md-10">
								<div class="d-flex gap-1 flex-wrap">
									{section name=i loop=5 start=0 step=1}
									{assign var=uid value=$clsISO->getUniqid()}
									<label class="we-radio mr-2" for="star_{$uid}">
										<input class="chk_star m-0" type="radio" id="star_{$uid}" name="star" value="{$smarty.section.i.iteration}" {if ($smarty.section.i.last && empty($oneItem.star)) || $oneItem.star eq $smarty.section.i.iteration}checked{/if}>
										<span>{$smarty.section.i.iteration} sao</span>
									</label>
									{/section}
								</div>
							</div>
						</div>
						<div class="form-group form-row">
							<label class="col-md-2 text-right col-form-label">Nội dung</label>
							<div class="col-xs-12 col-md-10">
								<textarea class="form-control form_field" name="content" cols="255" rows="5">{$oneItem.content|html_entity_decode}</textarea>
							</div>
						</div>
					{/if}
				{/if}
			</div>
			<div class="modal-footer">
				<input type="hidden" name="field_id" value="{$field_id}">
				<button type="button" class="btn btn btn-primary" onClick="$Core.member.AddField(this,{if $field eq 'history_sale'}'addHistorySale'{else}'add'{/if})"  data-profile_id='{$profile_id}' data-field="{$field}">
					{if $field_id ne ""}Sửa{else}Thêm{/if}
				</button>
			</div>
		</form>
	</div>
</div>
