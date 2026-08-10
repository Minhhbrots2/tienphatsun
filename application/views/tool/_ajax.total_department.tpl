{if $type eq "_OPEN"}
<div class="modal-dialog modal-dialog-centered">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Thống kê phòng ban đăng ký phòng họp</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="d-flex justify-content-end w-100 mb-2">
				<div class="input-group d-flex {if $deviceType ne 'phone'}w-px-250{/if}" role="group" aria-label="Sắp xếp">
					<select name="month" class="form-control form-select" style="width:100px" onChange="$Core.calendar.loadTotal_calendar(this,event)" data-type="_SEARCH"> 
						<option value="">Tháng</option>
						{assign var=curent_month value=$smarty.now|date_format:"m"}
						{section name=i loop=12 start=0 step=1}
							<option value="{$smarty.section.i.iteration}">Tháng {$smarty.section.i.iteration}</option>
						{/section}
					</select>

						{assign var=curent_year value=$smarty.now|date_format:"Y"}
					<select name="year"  class="form-control form-select" style="width:80px" onChange="$Core.calendar.loadTotal_calendar(this,event)" data-type="_SEARCH">
						{section name=i loop=$curent_year+1 start=2024 step=1}
							<option value="{$smarty.section.i.index}">{$smarty.section.i.index}</option>
						{/section}
					</select>
				</div>
			</div>
			<div class="overflow-auto" >
				<table class="table mb-0 text-center border">
					<thead>
						<tr>
							<th class="bg-lighter text-center border-1" width="60">STT</th>
							<th class="bg-lighter text-left border-1">Phòng ban</th>
							<th class="bg-lighter text-center border-1" width="100">Lượt đăng ký</th>
						</tr>
					</thead>
					<tbody class="lst_department">	
						{if !empty($arr_data)}
							{foreach from=$arr_data item=_oItem key=key name=i}
								<tr>
									<td class="text-nowrap border-1">{$smarty.foreach.i.iteration}</td>
									<td class="text-left border-1">{$_oItem.department_name}</td>
									<td class="text-nowrap border-1 fw-bold {if empty($_oItem.total)}text-main{else}text-success{/if}">{$_oItem.total}</td>
								</tr>
							{/foreach}
							<tr>
								<td class="text-nowrap border-1 bg-lighter" colspan="2">Tổng</td>
								<td class="text-nowrap border-1">{$total}</td>
							</tr>
						{else}
							<tr>
								<td class="text-center border-1" colspan="3">
									<div class="">
										<img src="{$URL_IMAGES}/listing-empty.svg" />
										<p>Danh sách trống</p>
									</div>
								</td>
							</tr>
						{/if}
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>
{else}
	{if !empty($arr_data)}
		{foreach from=$arr_data item=_oItem key=key name=i}
			<tr>
				<td class="text-nowrap border-1">{$smarty.foreach.i.iteration}</td>
				<td class="text-left border-1">{$_oItem.department_name}</td>
				<td class="text-nowrap border-1 fw-bold {if empty($_oItem.total)}text-main{else}text-success{/if}">{$_oItem.total}</td>
			</tr>
		{/foreach}
		<tr>
			<td class="text-nowrap bg-lighter border-1" colspan="2">Tổng</td>
			<td class="text-nowrap border-1">{$total}</td>
		</tr>
	{else}
		<tr>
			<td class="text-center border-1" colspan="3">
				<div class="">
					<img src="{$URL_IMAGES}/listing-empty.svg" />
					<p>Danh sách trống</p>
				</div>
			</td>
		</tr>
	{/if}
{/if}
