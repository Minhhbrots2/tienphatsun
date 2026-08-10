<div class="modal-dialog modal-dialog-centered modal-sm">
	<form class="modal-content" id="frmIssue" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title">Thêm căn hộ so sánh</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label for="name" class="form-label mb-1">Dự án</label>
				<select name="project_id" id="" class="form-control form-select" onChange="$Core.global.compare.load_block_building(this,event)" _type="block" >
					{foreach from=$lstProject item=_oItem key=key name=i}
						<option value="{$_oItem.project_id}">{$_oItem.title}</option>
					{/foreach}
				</select>
			</div>
			<div class="form-group mb-2">
				<label for="name" class="form-label mb-1">Phân khu</label>
				<select name="block_id" id="" class="form-control form-select" onChange="$Core.global.compare.load_block_building(this,event)" _type="building">
				</select>
			</div>
			<div class="form-group mb-2">
				<label for="name" class="form-label mb-1">Tòa/dãy</label>
				<select name="building_id" id="" class="form-control form-select">
				</select>
			</div>
			<div class="form-group position-relative">
				<label for="name" class="form-label mb-1">Mã căn</label>
				<input class="form-control" type="text" name="ms_code" value="" onkeyup="$Core.global.compare.do_search_stock(this, event)" uid="sugget_{$uid}" >
				<div id="sugget_{$uid}" class="autosugget d-none" style="top: 60px"></div>
				<input type="hidden" name="stock_id" value="0">
			</div>
		</div>
		<div class="modal-footer justify-content-end">
			<button type="button" block_id="{$block_id}" onclick="$Core.global.compare.add_stock_compare(this,event)" _tp="modal" action="add" toId="{$toId}" 
				class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
