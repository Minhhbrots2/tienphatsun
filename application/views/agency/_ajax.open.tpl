<div class="modal-dialog modal-dialog-scrollable modal-ipad-xl">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center justify-content-between">
				<div class="modal-header__left">
					{if $course_id eq 0}
					<h5 class="modal-title">Thêm mới sự kiện</h5>
					{else}
					<h5 class="modal-title">Sửa sự kiện</h5>
					{/if}
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
				</div>
			</div>
			<div class="modal-body modal-body-scrollable">	
				<div class="form-group form-row mb-2">
					<div class="col-12 col-xxl-4 mb-2 mb-lg-0">
						<label for="title" class="form-label mb-1">Tên đại lý</label>
						<input type="text" class="form-control required form-field" name="title" placeholder="Tên đại lý" value="{$oneItem.title}">
					</div>
					<div class="col-12 col-xxl-4 mb-2 mb-lg-0">
						<label for="title" class="form-label mb-1">Tên viết tắt</label>
						<input type="text" class="form-control form-field" name="title_vn" placeholder="Tên viết tắt" value="{$oneItem.title_vn}">
					</div>
					<div class="col-12 col-xxl-4 mb-2 mb-lg-0">
						<label for="title" class="form-label mb-1">Mã đại lý</label>
						<input type="text" class="form-control form-field" name="property_code" placeholder="Mã đại lý" value="{$oneItem.title}">
					</div>
				</div>	
				{if !empty($list_folder_price_sheets)}<hr />
					{foreach name=k from=$list_folder_price_sheets key = _oK item = _oP}
						<div class="form-group form-row mb-2 group_price_sheets">
							<div class="col-12 col-md-6 mb-2 mb-lg-0">
								<label for="title" class="form-label mb-1">Folder PTG</label>
								<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets[{$_oK}][title]" value="{$_oP.title}" placeholder="Tên folder" maxlength="255">
							</div>
							<div class="col-12 col-md-6 mb-2 mb-lg-0">
								<label for="title" class="form-label mb-1">Link PTG</label>
								<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets[{$_oK}][link]" value="{$_oP.link}" placeholder="Link folder" maxlength="255">
							</div>
						</div>
						{if $smarty.foreach.k.last}
							<a href="javascript:void(0);" onClick="$Core.agency.add_folder_price_sheets(this,event)">+Thêm</a>
						{/if}
					{/foreach}
					<hr />
				{/if}
				<div class="form-group form-row mb-2">
					<div class="col-12 col-md-12 mb-2 mb-lg-0">
						<label for="title" class="form-label mb-1">Nhóm Zalo</label>
						<input type="text" class="form-control required" placeholder="https://zalo.me/g/xxx" 
						name="group_zalo" onClick="this.select();" value="{if !empty($more_information.group_zalo)}{$more_information.group_zalo}{/if}">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="d-flex align-items-center">
					<label class="switch mr-2">
						<input type="checkbox"{if isset($more_information.hide_stock_globe) && $more_information.hide_stock_globe eq '1'} checked{/if} name="hide_stock_globe" value="1" />
						<span class="slider round"></span>
					</label>
					<span>Ẩn khỏi CA</span>
				</div>
				<button type="button" class="btn btn-outline-primary" onClick="$Core.agency.save_agency(this,event)" agency_id="{$agency_id}" data-type="{$action}">Lưu lại</button>
			</div>
		</div>
	</form>
</div>