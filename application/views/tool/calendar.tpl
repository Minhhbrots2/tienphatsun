<link rel="stylesheet" type="text/css" href="{$URL_JS}/fullcalendar-1.6.0/fullcalendar/fullcalendar.css?v={$upd_version}" media="all" />
<script type="text/javascript" src="{$URL_JS}/fullcalendar-1.6.0/fullcalendar/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/fullcalendar-1.6.0/fullcalendar/fullcalendar.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/fullcalendar-1.6.0/fullcalendar/gcal.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-xxxl-10 offset-xxxl-1">
			<div class="d-flex flex-wrap align-items-center gap-2 py-1 mb-2">
				<div class="me-auto">
					<h4 class="fw-bold mb-0">Đăng ký phòng họp</h4>
					<span class="text-muted d-none d-md-block">Đăng ký sử dụng phòng họp</span>
				</div>
				<div class="d-flex align-items-center gap-2 {if $deviceType eq 'phone'}flex-fill justify-content-end{/if}">
					<div class="input-group flex-nowrap js__room-filter-group {if $deviceType eq 'phone'}flex-fill{/if}">
						<span class="input-group-text px-2"><i class='bx bx-buildings'></i></span>
						<select class="form-control js__room-filter" name="room_id" title="Chọn phòng họp" onChange="$Core.calendar.change_room(this, event)">
							{if !empty($room_offices)}
								{foreach from=$room_offices item=_oOffice}
									{assign var=_oid value=$_oOffice.office_id}
									{if !empty($room_list[$_oid])}
										<optgroup label="{$_oOffice.title}">
											{foreach from=$room_list[$_oid] item=_oRoom}
												<option value="{$_oRoom.office_id}:{$_oRoom.room_id}"{if $_oRoom.office_id eq $default_office_id && $_oRoom.room_id eq $default_room_id} selected{/if}>{if $_oRoom.is_default}Phòng họp {/if}{$_oRoom.title}</option>
											{/foreach}
										</optgroup>
									{/if}
								{/foreach}
							{/if}
						</select>
					</div>
					<div class="d-flex gap-2 flex-shrink-0 {if $deviceType eq 'phone'}flex-fill justify-content-end{/if}">
						<button type="button" data-toggle="ripple" title="Thống kê" openFrom="_dashboard" onClick="$Core.calendar.loadTotal_calendar(this, event)" data-type="_OPEN" class="btn btn-primary text-nowrap {if $deviceType ne 'computer'}btn-icon{/if}">{if $deviceType ne 'computer'}<i class="bx bx-bar-chart-square"></i>{else}Thống kê{/if}</button>
						<button type="button" data-toggle="ripple" title="Thêm mới" openFrom="_dashboard" onClick="$Core.calendar.open(this, event)" class="btn btn-outline-primary text-nowrap {if $deviceType ne 'computer'}btn-icon{/if}"><i class='bx bx-plus'></i>{if $deviceType eq 'computer'} Thêm mới{/if}</button>
					</div>
				</div>
			</div>
			<div class="overflow-x-auto">
				<div class="fh-calendar" id="fh-calendar">
					<div class="p-5 text-center text-muted">Loading...</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.selectize-dropdown,
	.selectize-dropdown.form-control {
		height: auto;
		padding: 0;
		margin: 2px 0 0;
		z-index: 1000;
		background: #fff;
		border: 1px solid #ccc;
		border: 1px solid rgba(0,0,0,.15);
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		-webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
		box-shadow: 0 6px 12px rgba(0,0,0,.175)
	}
</style>
