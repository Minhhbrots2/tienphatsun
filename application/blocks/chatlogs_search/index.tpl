{if $deviceType eq 'phone'}
<div class="dropdown">
	<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow" data-bs-toggle="dropdown" 
	data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false"><i class="bx bx-filter-alt"></i></button>
	<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-350">
		<div class="p-3">
			<div class="input-group mb-2">
				<div class="input-group input-group-merge">
					<span class="input-group-text"><i class="bx bx-search"></i></span>
					<input type="text" name="keyword" class="form-control search_field" data-field="keyword" 
						placeholder="Nhập từ khoá &amp; nhấn Enter...">
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Nhu cầu</label>
				<select name="type_list" class="form-control search_field form-select">
					<option value="0">Nhu cầu</option>
					<option value="can_ban">Cần bán</option>
					<option value="can_mua">Cần mua</option>
				</select>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Loại căn hộ</label>
				<select class="form-control form-select" name="billing_type">
					<option value="0">Loại hình</option>
					<option value="448">Masterise Homes</option>
				</select>
			</div>
			<hr class="my-2">
			<button type="button" onClick="$Core.global.sop.do_chatlogs_search(this, event)" class="btn btn-primary">
				<i class="bx bx-search"></i> Áp dụng</button>
			<button type="reset" class="btn btn-warning"><i class="bx bx-refresh"></i> Xóa</button>
		</div>
	</div>
</div> 							
{else}
<div class="d-flex gap-1 align-items-center">
	<div class="input-group">
		<div class="input-group input-group-merge">
			<span class="input-group-text"><i class="bx bx-search"></i></span>
			<input type="text" name="keyword" onchange="$Core.global.sop.search_chatlogs_field(this, event)" 
				class="form-control search_field" data-field="keyword" placeholder="Nhập từ khoá &amp; nhấn Enter...">
		</div>
	</div>
	<select name="type_list" onchange="$Core.global.sop.do_chatlogs_search(this, event)" 
		class="form-control search_field w-px-100 form-select" data-field="type_list">
		<option value="0">Nhu cầu</option>
		<option value="can_ban">Cần bán</option>
		<option value="can_mua">Cần mua</option>
	</select>
	<div class="dropdown">
		<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow" data-bs-toggle="dropdown" 
			data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false"><i class="bx bx-filter-alt"></i></button>
		<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-300">
			<div class="p-3">
				<div class="form-group mb-2">
					<label class="form-label mb-1">Tình trạng</label>
					<select class="form-control form-select" name="contract_status_id">
						<option value="0">Tình trạng HĐMB</option>
						<option value="131">Đã ký HĐMB</option>
					</select>
				</div>
				<div class="form-group mb-1">
					<label class="form-label mb-1">Loại hình</label>
					<select class="form-control form-select" name="billing_type">
						<option value="0">Loại hình</option>
						<option value="448">Masterise Homes</option>
					</select>
				</div>
				<hr class="my-2">
				<button type="button" class="btn btn-primary"><i class="bx bx-search"></i> Áp dụng</button>
				<button type="button" class="btn btn-warning"><i class="bx bx-refresh"></i> Xóa</button>
			</div>
		</div>
	</div> 
</div>
{/if}