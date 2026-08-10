{if !empty($lstDepartment)}
	<div class="col-md-6 col-sm-12 col-12 flex-fill">
		<table class="table">
			<thead>
				<tr>
					<th class="text-center" scope="col" width="50px">STT</th>
						{if $deviceType eq 'phone'}
							<th scope="col">Phòng</th>
						{else}
							<th scope="col">Phòng được tri ân</th>
						{/if}
					<th class="text-right" scope="col" width="100px">Điểm/1 người</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$lstDepartment item=oneDepartment key=key name=i}
					<tr>
						<td class="text-center">{$smarty.foreach.i.iteration}</td>
						<td>{$oneDepartment.title} ({$oneDepartment.total_profile})</td>
						<td class="text-right">
							<input type="text" class="form-control text-center price-In px-1" name="scoreDep[{$oneDepartment.property_id}]" min="0" value="{$arr_department[$oneDepartment.property_id]}" style="width:50px;float: right" onChange="$Core.gratitude.updateNumberPoint(this,event)" data-type="dep" data-objID="{$oneDepartment.property_id}">
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{/if}
{if !empty($lst_profile)}
	<div class="col-md-6 col-sm-12 col-12 flex-fill">
		<table class="table">
			<thead>
				<tr>
					<th class="text-center" scope="col" width="50px">STT</th>
					{if $deviceType eq 'phone'}
						<th scope="col">Người</th>
					{else}
						<th scope="col">Người được tri ân</th>
					{/if}
					<th class="text-right" scope="col" width="100px">Điểm</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$lst_profile item=oneProfile key=key name=i}
					<tr>
						<td class="text-center">{$smarty.foreach.i.iteration}</td>
						<td>{$oneProfile.full_name}</td>
						<td class="text-right">
							<input type="text" class="form-control text-center price-In px-1" name="scoreEmp[{$oneProfile.profile_id}]" min="0" value="{$arr_staff[$oneProfile.profile_id]}" style="width:50px;float: right" onChange="$Core.gratitude.updateNumberPoint(this,event)" data-type="emp" data-objID="{$oneProfile.profile_id}">
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{/if}