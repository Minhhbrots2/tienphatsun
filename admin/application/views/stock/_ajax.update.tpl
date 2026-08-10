<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Import File</strong></h3>
		</div>
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group stock__line d-flex align-items-center row">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="col-md-4">
						<div class="checkbox">
							<input type="checkbox" id="{$uid}" name="stock_field[{$uid}][p_field]" value="view_id" id="{$uid}" />
							<label for="{$uid}">Cập nhật [View] </label>
						</div>
					</div>
					<div class="col-md-1 text-center">=</div>
					<div class="col-md-7">
						<select name="stock_field[{$uid}][p_value]" id="{$uid}" class="form-control iso-selectize">
							{$clsProperty->getSelectByProperty('_VIEW', 'View')}
						</select>
					</div>
				</div>
				<hr class="my-2" />
				<div class="form-group stock__line d-flex align-items-center row">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="col-md-4">
						<div class="checkbox mr-2">
							<input type="checkbox" name="stock_field[{$uid}][p_field]" value="type_id" id="{$uid}" />
							<label for="{$uid}">Cập nhật [Loại] = </label>
						</div>
					</div>
					<div class="col-md-1 text-center"> =</div>
					<div class="col-md-7">
						<select name="stock_field[{$uid}][p_value]" class="form-control iso-selectize">
							{$clsProperty->getSelectByProperty('_TYPE', 'Loại')}
						</select>
					</div>
				</div>
				<hr class="my-2" />
				<div class="form-group stock__line d-flex align-items-center row">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="col-md-4">
						<div class="checkbox mr-2">
							<input type="checkbox" name="stock_field[{$uid}][p_field]" value="agency_id" id="{$uid}" />
							<label for="{$uid}">Cập nhật [Đại lý]</label>
						</div>
					</div>
					<div class="col-md-1 text-center"> =</div>
					<div class="col-md-7">
						<select name="stock_field[{$uid}][p_value]" class="form-control iso-selectize">
							{$clsProperty->getSelectByProperty('_AGENCY', 'Đại lý')}
						</select>
					</div>
				</div>
				<hr class="my-2" />
				<div class="form-group stock__line d-flex align-items-center row">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="col-md-4">
						<div class="checkbox mr-2">
							<input type="checkbox" name="stock_field[{$uid}][p_field]" value="status_id" id="{$uid}"  />
							<label for="{$uid}">Cập nhật [Tình trạng]</label>
						</div>
					</div>
					<div class="col-md-1 text-center"> =</div>
					<div class="col-md-7">
						<select name="stock_field[{$uid}][p_value]" class="form-control iso-selectize">
							{$clsProperty->getSelectByProperty('_STATUS', 'Tình trạng')}
						</select>
					</div>
				</div>
				<hr class="my-2" />
				<div class="form-group stock__line d-flex align-items-center row">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="col-md-4">
						<div class="checkbox mr-2">
							<input type="checkbox" name="stock_field[{$uid}][p_field]" value="hide_price_sheets" id="{$uid}"  />
							<label for="{$uid}">Cập nhật [Ẩn PTG]</label>
						</div>
					</div>
					<div class="col-md-1 text-center"> =</div>
					<div class="col-md-7">
						<select name="stock_field[{$uid}][p_value]" class="form-control iso-selectize">
							<option value="1">Có</option>
							<option selected value="0">Không</option>
						</select>
					</div>
				</div>
				<hr class="my-2" />
				<div class="form-group stock__line d-flex align-items-center row">
					{assign var = uid value = $clsISO->getUniqid()}
					<div class="col-md-4">
						<div class="checkbox mr-2">
							<input type="checkbox" name="stock_field[{$uid}][p_field]" value="show_website" id="{$uid}"  />
							<label for="{$uid}">Cập nhật [Hiển thị]</label>
						</div>
					</div>
					<div class="col-md-1 text-center"> =</div>
					<div class="col-md-7">
						<div class="d-flex align-items-center">
							{foreach from=$list_website item = _oW}
							<div class="checkbox mr-2">
								<input type="checkbox" checked name="stock_field[{$uid}][p_value][]" value="{$_oW}" />
								<label>{$_oW}</label>
							</div>
							{/foreach}
						</div>
					</div>
				</div>
				{if !empty($stock_ids)}
					{foreach name=i from=$stock_ids item = stock_id}
					<input type="hidden" name="stock_ids[]" value="{$stock_id}" />
					{/foreach}
				{/if}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="start_update_field(this,event)">Cập nhật</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>