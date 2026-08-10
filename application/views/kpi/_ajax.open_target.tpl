<div class="modal-dialog">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-3">
				<label for="deposit_date" class="form-label">Tên chỉ tiêu</label>
				<input type="text" placeholder="Tên chỉ tiêu" name="title" class="form-control required" value="{if $action eq '_edit'}{$oneTarget.title}{/if}">
			</div>
			<div class="form-group mb-3">
				<label for="deposit_date" class="form-label">Nguồn lấy dữ liệu</label>
				<div class="clearfix"></div>
				<select class="form-control required iso-selectize" name="target_field" data-width="100%">
					{$clsKPI->getSourceFields($oneTarget.target_field)}
				</select>
			</div>
			<div class="form-group mb-3">
				<label for="deposit_date" class="form-label">Mô tả</label>
				<textarea name="intro" class="form-control" cols="255" rows="3" placeholder="Mô tả">{if $action eq '_edit'}{$oneTarget.intro}{/if}</textarea>
			</div>
			<div class="form-group mb-3">
				<label for="deposit_date" class="form-label">Hiển thị</label>
				<div class="clearfix">
					<div class="form-check cursor-pointer form-check-inline">
						<input id="{$uid}_1"{if $oneTarget.position eq 'TARGET' && $action eq '_edit'} checked{/if} name="position" class="form-check-input" type="radio" value="TARGET" />
						<label for="{$uid}_1" class="form-check-label">Chỉ bên Target</label>
					</div>
					<div class="form-check cursor-pointer form-check-inline">
						<input id="{$uid}_2" name="position"{if $oneTarget.position eq 'RESULT' && $action eq '_edit'} checked{/if} class="form-check-input" type="radio" value="RESULT" />
						<label for="{$uid}_2" class="form-check-label">Chỉ bên Kết quả</label>
					</div>
					<div class="form-check cursor-pointer form-check-inline">
						<input id="{$uid}_3" name="position"{if $action eq '_add' || $oneTarget.position eq 'ALL'} checked{/if} class="form-check-input" type="radio" value="ALL" />
						<label for="{$uid}_3" class="form-check-label">Cả 2 bên</label>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" kpi_id="{$kpi_id}" kpi_target_id="{$kpi_target_id}" 
			onClick="$Core.kpi.pop_save_target(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>