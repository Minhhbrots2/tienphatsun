<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
		<div class="kYlZoryVmS">
			<h4 class="fw-bold mb-0">Quản lý đại lý</h4>
			<span class="text-muted">Các đại lý trên hệ thống</span>
		</div>
		<div class="d-flex align-items-center gap-1">
			{*{if $deviceType ne phone}
			<a href="javascript:void(0)" onClick="$Core.agency.open(this,event)" data-type="open" data-agency_id="0" class="btn btn-outline-primary mr-2">Thêm mới </a>
			{else}
			<a href="javascript:void(0)" onClick="$Core.agency.open(this,event)" data-type="open" data-agency_id="0" class="btn btn-icon btn-outline-primary mr-2"><i class='bx bx-plus'></i></a>
			{/if}*}
			<button type="button" class="btn btn-icon btn-default ml2" onclick="$Core.agency.storage_cache(this, event)" title="Cập nhật cache" property_id="0" property_type="_AGENCY"><i class="fa fa-cloud"></i></button>
			<button class="btn btn-icon btn-default ml2" type="button" onclick="$Core.agency.open_setting(this, event)" title="Cấu hình ẩn quỹ đại lý"><i class="fa fa-cog fs-16"></i></button>			
		</div>
	</div>
	<div class="card no-shadow">
		<div class="card-body">
			<div class="table-container no-shadow overflow-auto table_sticky holderPropertyType_agency">
				<table class="table table-bordered dragable" width="100%" cellpadding="0" cellspacing="0">
					<thead><tr>
						<th class="text-left column_sticky sticky_left" {if $deviceType eq 'phone'}width="80px"{else}width="45%"{/if} rowspan="2" style="vertical-align: middle;">Tên đại lý</th>
						<!-- MOC -->
						{assign var=total_col value=6}
						{if !empty($agency_hidden_stock_MOC)}
							{foreach from=$agency_hidden_stock_MOC item=hidden_stock}
								{math equation="x+1" x=$total_col assign="total_col"}
								<th class="text-left">{$hidden_stock.title}</th>
							{/foreach}
						{/if}
						<!-- user.FH -->										
						{if !empty($agency_hidden_stock_FH)}
							{foreach from=$agency_hidden_stock_FH item=hidden_stock name=i}
								{math equation="x+1" x=$total_col assign="total_col"}
								<th class="text-left">{$hidden_stock.title}</th>
							{/foreach}
						{/if}										
						<th class="text-center" rowspan="2" style="vertical-align: middle;">Tình trạng</th>
						{*<th class="text-left column_sticky sticky_right" rowspan="2" style="vertical-align: middle;"></th>*}
					</tr>
					<tr>
						<!-- MOC -->
						{if !empty($agency_hidden_stock_MOC)}
							{foreach from=$agency_hidden_stock_MOC item=hidden_stock key=key}
								<th class="text-left">
									<div class="d-flex gap-1">
										<button class="btn btn-sm btn-icon btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="{$key}" onClick="$Core.agency.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
										<button class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="{$key}" onClick="$Core.property.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
									</div>
								</th>
							{/foreach}
						{/if}
						<!-- user.FH -->										
						{if !empty($agency_hidden_stock_FH)}
							{foreach from=$agency_hidden_stock_FH item=hidden_stock key=key}
								<th class="text-left" {if $smarty.foreach.i.last}style="border-right: 1px solid #d9dee3;"{/if}>
									<div class="d-flex">
										<button class="btn btn-sm btn-icon btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="{$key}" onClick="$Core.agency.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
										<button class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="{$key}" onClick="$Core.property.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
									</div>
								</th>
							{/foreach}
						{/if}
					</tr></thead>
					<tbody>
						{section name=i loop=$list_preloaders max = 30}
						<tr class="bold" id="{$agency_id}">
							<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<!-- MOC -->
							{if !empty($agency_hidden_stock_MOC)}
								{foreach from=$agency_hidden_stock_MOC item=hidden_stock key=key}
									<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								{/foreach}
							{/if}
							<!-- user.FH -->										
							{if !empty($agency_hidden_stock_FH)}
								{foreach from=$agency_hidden_stock_FH item=hidden_stock key=key}
									<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
								{/foreach}
							{/if}
							<!-- End -->									
							{*<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
							<td class="text-center"><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>*}
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			{if !empty($html_pager)}
				<div class="pagination justify-content-center">{$html_pager}</div>
			{/if}
		</div>
	</div>
</div>
<style>
	.table-container{
		max-height:calc(100vh - 225px);
		min-height:550px;
	}
</style>