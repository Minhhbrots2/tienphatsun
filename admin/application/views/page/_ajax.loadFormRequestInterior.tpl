{if $type eq 'parent'}
	<div class="group-parent">
		<h2 class="ui-title-bar__title mb-0">Thuộc tính</h2>
		<div class="form-row form-group align-items-start ">
			<div class="col-md-1 text-right">
				<a href="javascript:void(0);" onClick="$Core.requesInterior.add_item(this,event)" data-type="group-child" data-action="append" data-uid1="{$uid}" class="btn btn-success btn_add">+</a>
				<a href="javascript:void(0);" onClick="$Core.requesInterior.delete_item(this,event)" data-type="group-parent" class="btn btn-danger me-1">-</a>
			</div>
			<div class="col-md-11">
				<div class="form-row">
					<div class="col-md-9">																
						<input type="text" class="form-control require ml-2" required="true" placeholder="Tiêu đề" name="request[{$uid}][title]" value="">
					</div>
					<div class="col-md-3">
						<select class="form-control form-select" name="request[{$uid}][type]" onchange="$Core.requesInterior.add_item(this,event)" data-action="load" data-uid1="{$uid}" data-type="group-child">
							<option value="">Lựa chọn</option>
							<option value="SELECT">Menu</option>
							<option value="CHECKBOX">Lựa chọn</option>
							<option value="INPUT">Tiêu đề</option>
						</select>
					</div>
				</div>
				<div class="lst_group_child">
					
				</div>											
			</div>							
		</div>
	</div>
{else if $type eq 'SELECT' || $type eq 'CHECKBOX'}
	<div class="form-row align-items-end group-child">	
		<div class="col-md-1 text-right">
			<a href="javascript:void(0);" onClick="$Core.requesInterior.delete_item(this,event)" data-type="group-child" class="btn btn-danger me-1">-</a>
		</div>
		<div class="col-md-11">								
			<label class="col-form-label">Lựa chọn</label>									
			<input type="text" class="form-control require ml-2" required="true" placeholder="Lựa chọn" name="request[{$uid1}][child][]" value="">
		</div>
	</div>
{/if}