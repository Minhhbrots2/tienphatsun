{if !empty($lstItem)}
	{if $type eq '_SOP'}
		{foreach from=$lstItem item=_oItem name=i}
			{assign var=more_information value=$_oItem.more_information}
			{math equation="x+1" x=$offset assign="offset"}
			<tr>
				<td>{$offset}</td>
				<td class="text-left"><a class="fw-bold" href="{$clsClassTable->getLink($_oItem.sop_id,$more_information.stock_code)}" target="_blank" stock_id="{$_oItem.stock_id}" sop_id="{$_oItem.sop_id}">{$_oItem.stock_code}</a></td>
				<td class="text-left">{$clsMember->getFullName($_oItem.user_id)}</td>
				<td class="text-right">{$clsISO->convertTimeToText($_oItem.reg_date, true)}</td>
				{*<td class="text-center" style="white-space: nowrap;">
					<div class="btn-group {if $smarty.foreach.i.last} dropup{/if}">
						<button class="btn-xs btn-default dropdown-toggle py-1 px-2" type="button" data-toggle="dropdown">
							<i class="fa fa-cog"></i> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" style="right:0;left: unset;min-width: 100px;">
							<li><a title="Duyệt" data-action="approve" href="javascript:void(0)" data-table="Sop" data-type="{$type}" data-id="{$_oItem.sop_id}" onClick="$Core.home.approve(this,event)">
								<i class="fa fa-check"></i> Duyệt</a>
							</li>
							<li><a title="Huỷ duyệt" data-action="noapprove" data-table="Sop" data-type="{$type}" data-id="{$_oItem.sop_id}" href="javascript:void(0)" onClick="$Core.home.show_notes(this,event)">
								<i class="fa fa-close"></i> Không duyệt</a>
							</li>
							<li><a title="Xoá" data-action="unapprove" data-table="Sop" data-type="{$type}" data-id="{$_oItem.sop_id}" href="javascript:void(0)" onClick="$Core.home.delete(this,event)"> 
								<i class="fa fa-minus"></i> Xoá</a>
							</li>
						</ul>
					</div>
				</td>*}
			</tr>
		{/foreach}
	{else if $type eq '_LEASING'}
		{foreach from=$lstItem item=_oItem name=i}
			{assign var=more_information value=$_oItem.more_information}
			{math equation="x+1" x=$offset assign="offset"}
			<tr>
				<td>{$offset}</td>
				<td class="text-left"><a class="fw-bold" href="{$clsClassTable->getLink($_oItem.leasing_id,$more_information.stock_code)}" target="_blank" stock_id="{$_oItem.stock_id}" leasing_id="{$_oItem.leasing_id}">{$_oItem.stock_code}</a></td>
				<td class="text-left">{$clsMember->getFullName($_oItem.user_id)}</td>
				<td class="text-right">{$clsISO->convertTimeToText($_oItem.reg_date, true)}</td>
				{*<td class="text-center" style="white-space: nowrap;">
					<div class="btn-group {if $smarty.foreach.i.last} dropup{/if}">
						<button class="btn-xs btn-default dropdown-toggle py-1 px-2" type="button" data-toggle="dropdown">
							<i class="fa fa-cog"></i> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" style="right:0;left: unset;min-width: 100px;">
							<li><a title="Duyệt" data-action="approve" href="javascript:void(0)" data-table="Leasing" data-type="{$type}" data-id="{$_oItem.leasing_id}" onClick="$Core.home.approve(this,event)">
								<i class="fa fa-check"></i> Duyệt</a>
							</li>
							<li><a title="Huỷ duyệt" data-action="noapprove" data-table="Leasing" data-type="{$type}" data-id="{$_oItem.leasing_id}" href="javascript:void(0)" onClick="$Core.home.show_notes(this,event)">
								<i class="fa fa-close"></i> Không duyệt</a>
							</li>
							<li><a title="Xoá" data-action="unapprove" data-table="Leasing" data-type="{$type}" data-id="{$_oItem.leasing_id}" href="javascript:void(0)" onClick="$Core.home.delete(this,event)"> 
								<i class="fa fa-minus"></i> Xoá</a>
							</li>
						</ul>
					</div>
				</td>*}
			</tr>
		{/foreach}
	{else if $type eq '_SERVICES'}
		{foreach from=$lstItem item=_oItem name=i}
			{assign var=more_information value=$_oItem.more_information}
			{math equation="x+1" x=$offset assign="offset"}
			<tr>
				<td>{$offset}</td>
				<td class="text-left"><a class="fw-bold" href="{$clsClassTable->getLink($_oItem.service_id,$_oItem)}" target="_blank" >{$_oItem.name}</a></td>
				<td class="text-left">{$more_information.phone}</td>
				<td class="text-left">{$clsMember->getFullName($_oItem.user_id)}</td>
				{*<td class="text-center" style="white-space: nowrap;">
					<div class="btn-group {if $smarty.foreach.i.last} dropup{/if}">
						<button class="btn-xs btn-default dropdown-toggle py-1 px-2" type="button" data-toggle="dropdown">
							<i class="fa fa-cog"></i> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" style="right:0;left: unset;min-width: 100px;">
							<li><a title="Duyệt" data-action="approve" href="javascript:void(0)" data-table="Service" data-type="{$type}" data-id="{$_oItem.service_id}" onClick="$Core.home.approve(this,event)">
								<i class="fa fa-check"></i> Duyệt</a>
							</li>
							<li><a title="Xoá" data-action="unapprove" data-table="Service" data-type="{$type}" data-id="{$_oItem.service_id}" href="javascript:void(0)" onClick="$Core.home.delete(this,event)"> 
								<i class="fa fa-minus"></i> Xoá</a>
							</li>
						</ul>
					</div>
				</td>*}
			</tr>
		{/foreach}
	{else if $type eq '_INTERIOR'}
		{foreach from=$lstItem item=_oItem name=i}
			{math equation="x+1" x=$offset assign="offset"}
			<tr>
				<td>{$offset}</td>
				<td class="text-left"><a class="fw-bold" href="{$clsClassTable->getLink($_oItem.interior_id,$_oItem)}" target="_blank" >{$_oItem.title}</a></td>
				<td class="text-left">{$clsCompany->getTitle($_oItem.company_id)}</td>
				<td class="text-left">{$clsMember->getFullName($_oItem.user_id)}</td>
				<td class="text-right">{$clsISO->convertTimeToText($_oItem.reg_date, true)}</td>
				{*<td class="text-center" style="white-space: nowrap;">
					<div class="btn-group {if $smarty.foreach.i.last} dropup{/if}">
						<button class="btn-xs btn-default dropdown-toggle py-1 px-2" type="button" data-toggle="dropdown">
							<i class="fa fa-cog"></i> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" style="right:0;left: unset;min-width: 100px;">
							<li><a title="Duyệt" data-action="approve" href="javascript:void(0)" data-table="Interior" data-type="{$type}" data-id="{$_oItem.interior_id}" onClick="$Core.home.approve(this,event)">
								<i class="fa fa-check"></i> Duyệt</a>
							</li>
							<li><a title="Xoá" data-action="unapprove" data-table="Interior" data-type="{$type}" data-id="{$_oItem.interior_id}" href="javascript:void(0)" onClick="$Core.home.delete(this,event)"> 
								<i class="fa fa-minus"></i> Xoá</a>
							</li>
						</ul>
					</div>
				</td>*}
			</tr>
		{/foreach}
	{else if $type eq '_INTERIOR_REQUEST'}
		{foreach from=$lstItem item=_oItem name=i}
			{math equation="x+1" x=$offset assign="offset"}
			{assign var=more_information value=$_oItem.more_information.field}
			<tr>
				<td>{$offset}</td>
				<td class="text-left">{$_oItem.name}</td>
				<td class="text-left">{$_oItem.phone}</td>
				<td class="text-left">
					{foreach from=$more_information item=_oItemMore}
						<p class=""><strong>{$_oItemMore.title}:</strong> {$_oItemMore.value}</p>
					{/foreach}
				</td>
				<td class="text-left">{$_oItem.intro|html_entity_decode}</td>
				<td class="text-left">{$clsMember->getFullName($_oItem.profile_id)}</td>
				<td class="text-right">{$clsISO->convertTimeToText($_oItem.reg_date, true)}</td>
				{*<td class="text-center" style="white-space: nowrap;">
					<div class="btn-group {if $smarty.foreach.i.last} dropup{/if}">
						<button class="btn-xs btn-default dropdown-toggle py-1 px-2" type="button" data-toggle="dropdown">
							<i class="fa fa-cog"></i> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" style="right:0;left: unset;min-width: 100px;">
							<li><a title="Xoá" data-action="unapprove" data-table="InteriorRequest" data-type="{$type}" data-id="{$_oItem.id}" href="javascript:void(0)" onClick="$Core.home.delete(this,event)"> 
								<i class="fa fa-minus"></i> Xoá</a>
							</li>
						</ul>
					</div>
				</td>*}
			</tr>
		{/foreach}
	{/if}
{else}
	<tr>
		<td class="text-center" colspan="{if $type eq '_INTERIOR'}5{else if $type eq '_INTERIOR_REQUEST'}8{else}4{/if}">
			<div class="text-center">
				<img class="mb-2" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAE0AAABqCAIAAAB2wktpAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkY0RTUxMzM1OEZBQTExRThBOUQxQjhGNDMzNjRGM0JDIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkY0RTUxMzM2OEZBQTExRThBOUQxQjhGNDMzNjRGM0JDIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjRFNTEzMzM4RkFBMTFFOEE5RDFCOEY0MzM2NEYzQkMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjRFNTEzMzQ4RkFBMTFFOEE5RDFCOEY0MzM2NEYzQkMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6dXDCjAAAD+ElEQVR42uycC0/aUBTHaSm1iI6HlJfR4URnNDoU39F9fDcQhOjMHo4Z5SXjIaUKqDx21C0xxUkbq97Wc0JI7k1J+NH/edx7T6FEUby4uNiOxgWhatCCURS1MD/n8bgVfYputVrhSEwrkGCdTiee2CuVyso4fyYPRfHMoClrt9vRWELR16aPjlIGDVqz2Yxs79TrdZnXM1dXV3fHNpt1aMhBGhXcDACTTDYajXA4tr6+zLJsb07JOPhh1mLpJ40zm811c4Kd12oQQVdXFo1GYw/dSsc0rS0BVypCPLELHquMU4uWzxd2d/d1yAkpVDKTyea+fT/QG+eYf3RwYEAymUwePpA7NMkJAXZ5eYHjOMn8l/2vcGN15Z8Auby0wDDSfAGOem+ppOE4NDg4sBgKSjLK/0olbcdbKGnmg3OSsASZ9nM4CqlVV3nF7eZnZqYkk5eXl9uRHXjXVf58OzoSCLy7t1SC1Zh+OMHeTwaAtrtUikbjt6USo0UquFfFYqlLwC6YlLhlsVSGCBwMzmqSM5XKwEvmxZBROTOnE90+bFAqvQpOzcSh7mpWn5zT01OPXP1rIw4B5MfN9YpQbf/Lhw8bVAjxxJ72OG83Ohx2m8yL6/WGDvcT9OOfyKl2HCqXT3O5fLvTJmVF5nD4fB6VOaFoDEdiPfcOn9OOj9MMY3S5eDV1WxVEoiD/Suy0orJ/2u3W7p2YlzWKonjeqbJuOY7b2FgF/7x38/9FcqmLd9psVvXjkKW/PzA+hnkFOZETOZETOZET1yvddpL/nUplCKlyKcrA8/yYf1RlzqooxmIJou5PoVDqY00+n1dN3Z6f1wiUYlVRP5jMRS3H9ZEVV2jaq6SVUZZuWda0ubEGUmnJ21Z8ege9/ukV7ejKjUMsyw4Pew2aNcyfyImcyImcyPkSdTxU8PKPH5/BrNY3JpNJZc5ms7m1FRHPCHosAkq09bUV+SWRLN0WS2WiIA3XJ9ZX6UxWZf/sk/FAwfObuav/9rG6tdttM9NTqXSGqHOHkZFh9eOQ3z/qV7J+x7yCnMiJnMiJnMj5etYrlYqQzeZaxHTX8LzT43apzFmr1T993iatT2ppacElu7VGlm4rgkBin1T5VGX/hDqe6fVg8AtI1zmksm7NN31SmewJKeeCN/7pcNjVj0MWi2VyYhzzCnIiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3I+NWdHF1xSCul+/MHBL0XPd5NphUJRyknT9N1Tk1Q6Ay+didZopGmf16N75/R6PPTkZIBlTTqGZFkWGGmzmQuF5vWKClyhUBAYKVEUYVxvNA5+JHMnpPwTxOONYRiv1z0xMX7bvvpHgAEAO0R+YvoBo4cAAAAASUVORK5CYII=" width="40px">
				<p class="type--subdued">Danh sách trống</p>
			</div>
		</td>
	</tr>
{/if}