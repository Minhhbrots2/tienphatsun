<div class="modal-dialog">

	<div class="modal-content">

		<div class="modal-header"> 

			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 

			<h3 class="modal-title"><strong>Cài đặt tầng đặc biệt</strong></h3>

		</div>

		<form method="post" action="" enctype="multipart/form-data">

			<div class="modal-body modal-body-scrollable">

				<table class="table">

					<thead><tr>

						<th class="text-center border-end" width="5%">Tầng</th>

						<th class="text-center border-end" width="5%">Tên thay thế</th>

						<th class="text-center border-end" width="10%">Layout header</th>

						<th class="text-center border-end" width="10%">Không theo thứ tự</th>

						<th>Loại tầng</th>

					</tr></thead>

					{if !empty($lst_floor)}

						{foreach from=$lst_floor key = key item = _floor}

							{assign var=_cfg value=$floor_arrs[$_floor]}

						<tr>

							<td class="text-center border-end">{$_floor}</td>

							<td class="text-center border-end">

								<input class="form-control" type="text" name="floor_config[{$_floor}][name_floor]" value="{$_cfg.name_floor}">

							</td>

							<td class="text-center border-end">

								<label class="switch">

									<input type="checkbox" name="floor_config[{$_floor}][is_special]"{if $_cfg.is_special eq '1'} checked{/if} value="1">

									<span class="slider round"></span>

								</label>

							</td>

							<td class="text-center border-end">

								<label class="switch">

									<input type="checkbox" name="floor_config[{$_floor}][is_out_order]"{if $_cfg.is_out_order eq '1'} checked{/if} value="1">

									<span class="slider round"></span>

								</label>

							</td>

							<td class="text-center">

								<div class="input-group d-flex" style="min-width: 200px">

									<select name="floor_config[{$_floor}][floor_type]" class="form-control no-border-right">

										{$clsProperty->getSelectOptimizeProperty('_FLOOR_TYPE', $_cfg.floor_type, $arrFloorType)}

									</select>

									<input type="text" class="form-control" name="floor_config[{$_floor}][cell_merge]" value="{$_cfg.cell_merge}" >

								</div>

							</td>

						</tr>

						{/foreach}

					{/if}

				</table>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-success" building_id="{$building_id}" onClick="$Core.project.save_setup_floor(this, event)">Cập nhật</button>

				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>

			</div>

		</form>

	</div>

</div>