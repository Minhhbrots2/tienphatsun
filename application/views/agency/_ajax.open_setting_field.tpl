<div class="modal-dialog modal-md modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header"> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row mb-2">
					<div class="col-12 col-md-8 mb-2 mb-lg-0">
						<label class="form-label mb-1">Tiêu đề</label>
						<input class="form-control required" placeholder="Tiêu đề" name="title" value="{$oneItem.title}" />
					</div>			
					<div class="col-12 col-md-4">
						<label class="form-label mb-1">Site</label>
						<select name="site" class="form-control form-select">
							<option value="_FH" {if $oneItem.site eq '_FH'}selected{/if}>CA</option>
							<option value="_MOC" {if $oneItem.site eq '_MOC'}selected{/if}>MOC</option>
						</select>
					</div>
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-6 col-md-6">
						<label class="form-label mb-1 w-100">Dự án</label>
						<select name="project_id" onchange="$Core.agency.select_block(this, event)" toid="slb_Block_Id_{$uid}" data-width="100%" class="form-select w-100">
							<option value="0">Chọn dự án</option>
							{$clsProject->getSelectOptions($oneItem.project_id)}
						</select>
					</div>
					<div class="col-6 col-md-6">
						<label class="form-label mb-1">Phân khu</label>
						<select class="form-select" name="block_id" data-width="100%" id="slb_Block_Id_{$uid}">
							{$clsProperty->getSelectByPropertyOrigin('_BLOCK',$project_id, $oneItem.block_id,"Chọn phân khu")}
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="d-flex bg-lighter p-3 rounded-2 align-items-center gap-1">
						<label class="switch">
							<input type="checkbox" name="is_vin" value="1" {if $oneItem.is_vin eq 1}checked{/if}>
							<span class="slider round"></span>
						</label>
						<span>Là quỹ Vin</span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.agency.save_setting_field(this, event)" id="{$id}" 
					class="btn btn-success" data-action="save"><span>Lưu lại</span></button>
				<button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">Đóng</button>
			</div>
		</form>
	</div>
</div>
