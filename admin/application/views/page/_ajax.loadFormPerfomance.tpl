{if $type eq 'child-1'}
	<div class="group-child-1 group_item pl-8">
		<div class="form-row form-group align-items-end">
			<div class="col-md-1 text-right">
				<a href="javascript:void(0);" onClick="$Core.performance.add_item_performance(this,event)" data-type="child-2" data-property="{$property}" data-uid1="{$uid}" class="btn btn-success">+</a>
				<a href="javascript:void(0);" onClick="$Core.performance.delete_item_performance(this,event)" data-type="group-child-1" class="btn btn-danger me-1">-</a>
			</div>
			<div class="col-md-11">
				<label class="col-form-label">Tên thuộc tính 1</label>									
				<input type="text" class="form-control require ml-2" required="true" placeholder="Tên thuộc tính" name="{$property}[{$uid}][title]" value="">
			</div>
		</div>
	</div>
{else if $type eq 'child-2'}
	<div class="group-child-2 pl-8">
		<div class="form-row form-group align-items-end">
			<div class="col-md-1 text-right">
				<a href="javascript:void(0);" onClick="$Core.performance.delete_item_performance(this,event)" data-type="group-child-2" class="btn btn-danger me-1">-</a>
			</div>
			<div class="col-md-11">
				<div class="form-row">											
					<div class="col-md-6">
						<label class="col-form-label">Tên thuộc tính 2</label>									
						<input type="text" class="form-control require ml-2" required="true" placeholder="Tên thuộc tính" name="{$property}[{$uid1}][lstChild][{$uid}][title]" value="">
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Giá trị</label>										
						<div class="input-group d-flex">
							<input type="number" class="form-control" value="" placeholder="Giá trị" id="introRate" name="{$property}[{$uid1}][lstChild][{$uid}][value]" min="0" step="0.1">
							<select class="form-control form-select" name="{$property}[{$uid1}][lstChild][{$uid}][unit_type]">
								<option value="_PERCENT">%</option>
								<option value="_MONEY">VNĐ</option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Thời gian</label>										
						<div class="input-group d-flex align-items-center">
							<input type="number" class="form-control calc_field mr-2" value="" placeholder="Thời gian" id="introMonths" name="{$property}[{$uid1}][lstChild][{$uid}][time]" min="0">
							<span class="input-group-text cursor-pointer">tháng</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{/if}