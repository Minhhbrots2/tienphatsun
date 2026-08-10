{if $template_type eq '_list'}
	{if !empty($list_lucky_wheels)}
		{foreach name=i from=$list_lucky_wheels item = _OI}
		<tr>
			<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
			<td class="align-center">{$_OI.title}</td>
			<td class="align-center">
				{if $_OI.program_type eq 'lucky_wheel'}
					Vòng quay may mắn
				{else}
					Hộp quà bí mật
				{/if}
			</td>
			<td class="align-center text-center">
				<button onclick="$Core.lucky_wheel.view_wheel_prizes(this, event)" wheel_id="{$_OI.id}" class="btn btn-sm btn-outline-default"><i class="bx bx-cog"></i> <strong class="text-main fw-bold">{$_OI.total_prizes}</strong> 🎁</button>
			</td>
			<td class="align-center text-center">
				<button onclick="$Core.lucky_wheel.view_wheel_prizes(this, event)" wheel_id="{$_OI.id}" class="btn btn-sm btn-outline-default"><i class="bx bx-user"></i> {$_OI.total_spins} tham gia</button>
			</td>
			<td class="align-center text-center">{$clsISO->convertTimeToText($_OI.start_date, true)}</td>
			<td class="align-center text-center">{$clsISO->convertTimeToText($_OI.start_date, true)}</td>
			<td class="align-center text-center">
				<Span class="badge bg-label-primary">Tạm dừng</span>
			</td>
			<td class="align-center text-center">
				<div class="dropdown">
					<button type="button" class="btn p-0 dropdown-toggle dropdown-button hide-arrow">
						<i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu">
						{if $_OI.program_type eq 'lucky_wheel'}
						<a href="{$clsLuckyWheel->getLink($_OI.id)}" target="_blank" class="dropdown-item" wheel_id="{$_OI.id}">
							<i class="bx bx-link-external me-1"></i> Xem</a>
						<hr size="0" class="dropdown-divider" />
						{/if}
						<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.lucky_wheel.open(this,event)" 
							wheel_id="{$_OI.id}" ><i class="bx bx-edit-alt me-1"></i> Sửa </a>
						<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.lucky_wheel.delete(this,event)" 
							wheel_id="{$_OI.id}"><i class="bx bx-trash me-1"></i> Xóa</a>
					</div>
				</div>
			</td>
		</tr>
		{/foreach}
	{else}
		
	{/if}
{else}
	{if !empty($list_lucky_wheel_prizes)}
		{foreach name=i from=$list_lucky_wheel_prizes item = _oI}
		<tr id="{$smarty.foreach.i.iteration}">
			{if $deviceType ne 'phone'}
			<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
			{/if}
			<td class="align-center text-center">
				<a class="mySortableHandler"><i class='bx bx-move'></i></a>
			</td>
			<td class="align-center">{$_oI.prize_name}</td>
			<td class="align-center text-center">{$_oI.quantity}</td>
			<td class="align-center text-center">{$_oI.probability}%</td>
			<td class="align-center text-center">
				<div class="d-flex align-items-center gap-1">
					<a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-default" wheel_id="{$wheel_id}" prize_id="{$_oI.id}" onClick="$Core.lucky_wheel.open_wheel_prizes(this,event)"><i class="bx bx-edit-alt"></i></a>
					<a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-default" wheel_id="{$wheel_id}" prize_id="{$_oI.id}" onClick="$Core.lucky_wheel.delete_prize(this,event)"><i class="bx bx-trash"></i></a>
				</div>
			</td>
		</tr>
		{/foreach}
		<tr class="not-sortable">
			<td></td>
			<td class="text-center" colspan="2">
				Tổng cộng
			</td>
			<td class="text-center">{$total_quantity}</td>
			<td class="text-center">{$total_probability}%</td>
			<td></td>
		</tr>
	{else}
		<tr>
			<td class="text-center" colspan="10">
				<img src="{$URL_IMAGES}/illustration-empty-results.svg" class="w-px-100" />
				<p>Chưa có phần thưởng nào được tạo</p>
			</td>
		<tr>
	{/if}
{/if}