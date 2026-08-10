<div class="modal-dialog modal-ipad">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Mã căn<span class="text-red">*</span></label>
					<input type="text" class="form-control required" placeholder="Nhập mã căn hộ" name="ms_code" value="{$oneItem.ms_code}" />
				</div>
				<div class="form-group">
					<label class="col-form-label">Hình ảnh</label>
					<div class="d-flex align-items-center gap-2">
						<img src="{$oneItem.image_banner}" id="isoman_show_image_banner" onerror="this.src='{$URL_IMAGES}/no-image.jpg'" class="mr-2 border w-35px h-35px radius-3" />
						<div class="input-group w-100">
							<input class="form-control" id="isoman_hidden_image_banner" name="image" value="{$oneItem.image}" />
							<div class="input-group-btn">
								<a class="btn btn-default ajOpenDialog" isoman_for_id="image_banner" isoman_val="{$oneItem.image}" isoman_name="image_banner"><i class="fa fa-image"></i> Thêm ảnh</a>
							</div>	
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" project_id="{$project_id}" banner_stock_id="{$banner_stock_id}"  onClick="$Core.global.project.save_banner_stock(this,event)">Lưu lại</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>



