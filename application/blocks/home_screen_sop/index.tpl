<div class="form-row">
	<div class="col-12 col-lg-8 mb-2 mb-lg-0">
		<div class="sticky">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="w-100">
				<div class="ajax briefs mb-2 gap-2 d-flex flex-wrap briefs_accountant" gId="{$gId}" data-url="/index.php?mod={$mod}&sub=sop&act=load_quantity_statistics" data-options={ldelim}{rdelim}>
					<div class="brief-item bg-orange a1a p-3 clickable">
						<p class="fs-16 mb-3">Tổng số</p>
						<h3 class="{if $deviceType eq 'phone'}text-fs-16{else}text-fs-20{/if} mb-0 text-white">0 căn</h3>
					</div>
					<div class="brief-item p-3 bg-azure a2a">
						<p class="fs-16 mb-3">Đã duyệt</p>
						<h3 class="{if $deviceType eq 'phone'}text-fs-16{else}text-fs-20{/if} mb-0 text-white">0 căn</h3>
					</div>
					<div class="brief-item p-3 bg-warning a3a">
						<p class="fs-16 mb-3">Chờ duyệt</p>
						<h3 class="{if $deviceType eq 'phone'}text-fs-16{else}text-fs-20{/if} mb-0 text-white">0 căn</h3>
					</div>
					<div class="brief-item p-3 bg-cyan a4a">
						<p class="fs-16 mb-3">Hôm qua</p>
						<h3 class="{if $deviceType eq 'phone'}text-fs-16{else}text-fs-20{/if} mb-0 text-white">0 căn</h3>
					</div>
					<div class="brief-item p-3 bg-green a6a">
						<p class="fs-16 mb-3">Hôm nay</p>
						<h3 class="{if $deviceType eq 'phone'}text-fs-16{else}text-fs-20{/if} mb-0 text-white">0 căn</h3>
					</div>
				</div>
			</div>
			<div class="card mb-2">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Chuyển nhượng nội bộ <a href="{$clsISO->getLink('sop')}"><i class="bx bx-link-external"></i></a></h5>
					<div class="p-right ox:w-100">
						<div class="input-group w-px-150 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
							<select class="form-control form-control-sm form-select" name="month" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this,event)"> 
								<option value="">Tháng</option>			
								{foreach from=$list_months item = _month}
								<option value="{$_month}">T{$_month}</option>
								{/foreach}
							</select>
							<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this,event)">
								{foreach from=$list_years item = _year}
								<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
								{/foreach}
							</select>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-container overflow-x-auto text-nowrap no-shadow">
						<table class="table table-bordered table-borderd" cellpadding="0" cellspacing="0" >
							<thead><tr>
								{if $deviceType ne 'phone'}
								<th width="5%" class="align-center bg-lightest text-center">STT</th>
								{/if}
								<th class="align-center bg-lightest h-px-35" width="120px">Mã căn</th>
								<th class="align-center bg-lightest h-px-35 text-left">Loại căn</th>
								<th class="align-center bg-lightest h-px-35 text-left">Giá chủ</th>
								<th class="align-center bg-lightest h-px-35 text-left">Giá bán</th>
								<th class="align-center bg-lightest h-px-35 text-left">Người liên hệ</th>
								<th class="align-center bg-lightest h-px-35 text-left">Điện thoại</th>
								<th class="align-center bg-lightest h-px-35 text-left">Ngày đăng</th>
								<th class="align-center bg-lightest h-px-35 text-left" width="40px"></th>
							</tr></thead>
							<tbody class="ajax home_list" data-url="/index.php?mod={$mod}&sub=sop&act=list_sop" 
								gId="{$gId}" data-options='{ldelim}"utm_source":"_me"{rdelim}'>
								{section name=i loop=$list_preloaders max = 10}
								<tr>
									{if $deviceType ne 'phone'}
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									{/if}
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card mb-2">
				{assign var = gId value = $clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Chuyển nhượng trường <a href="{$clsISO->getLink('sop')}"><i class="bx bx-link-external"></i></a></h5>
					<div class="p-right ox:w-100">
						<div class="input-group w-px-150 ox:w-100 d-flex" role="group" aria-label="Sắp xếp">
							<select class="form-control form-control-sm form-select" name="month" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this,event)"> 
								<option value="">Tháng</option>			
								{foreach from=$list_months item = _month}
								<option value="{$_month}">T{$_month}</option>
								{/foreach}
							</select>
							<select class="form-control form-control-sm form-select" name="year" gId="{$gId}" 
								onChange="$Core.dashboard.reload(this,event)">
								{foreach from=$list_years item = _year}
								<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
								{/foreach}
							</select>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-container overflow-x-auto text-nowrap no-shadow">
						<table class="table table-bordered table-borderd" cellpadding="0" cellspacing="0" >
							<thead><tr>
								{if $deviceType ne 'phone'}
								<th width="5%" class="align-center bg-lightest text-center">STT</th>
								{/if}
								<th class="align-center bg-lightest h-px-35" width="120px">Mã căn</th>
								<th class="align-center bg-lightest h-px-35 text-left">Loại căn</th>
								<th class="align-center bg-lightest h-px-35 text-left">Giá chủ</th>
								<th class="align-center bg-lightest h-px-35 text-left">Giá bán</th>
								<th class="align-center bg-lightest h-px-35 text-left">Người liên hệ</th>
								<th class="align-center bg-lightest h-px-35 text-left">Điện thoại</th>
								<th class="align-center bg-lightest h-px-35 text-left">Ngày đăng</th>
								<th class="align-center bg-lightest h-px-35 text-left" width="40px"></th>
							</tr></thead>
							<tbody class="ajax home_list" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=sop&act=list_sop" 
								gId="{$gId}" data-options='{ldelim}"utm_source":"_market"{rdelim}'>
								{section name=i loop=$list_preloaders max = 10}
								<tr>
									{if $deviceType ne 'phone'}
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									{/if}
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
									<td><div class="animate-bg h-px-20 w-100 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-12 col-md-6 mb-2 mb-lg-0">
					<div class="card h-100">
						<div class="card-header d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-0">Giao dịch mới nhất</h5>
							<a><i class="bx bx-help-circle"></i></a>
						</div>
						<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=dashboard&tp=top_billing" 
						data-options='{ldelim}{rdelim}'>
							<div class="loader text-center py-8">
								<img src="{$URL_IMAGES}/loading.gif" />
								<p>Loading...</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					{$core->getBlock('top_staff', ['class' => ' h-100'])}
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-4">
		{assign var = gId value = $clsISO->getUniqid()}
		{if $clsISO->checkPermission('sop_chatlogs')}
		<div class="card mb-2">
			<div class="card-header">
				<div class="d-flex align-items-center justify-content-between">
					<div class="pdhuULxcFa">
						<h5 class="mb-0"><i class='bx bx-chat'></i> Tin Zalo Group</h5>
						<small class="text-muted">Tổng hợp thông tin từ Zalo Group</small>
					</div>
					<div class="nKCGAbtolv d-flex align-items-center gap-1">
						<a href="{$PCMS_URL}/sop/chatlogs.html" title="Xem tất cả" class="btn btn-link">
							Xem tất cả
						</a>
					</div>
				</div>
			</div>
			<div class="card-body bg-lighter" gId="{$gId}">
				<div class="chatlogs min-height-450 overflow-y-auto">
					{section name=i loop=$list_preloaders max=20}
					<div class="awe__chat-item awe__chat-preloader js__chat-item">
						<div class="w-100 d-flex gap-2">
							<div class="awe__chat-avatar" title="Tư Lê Văn">
								<a class="bs-webui-popover" data-target="webuiPopover19">
									<div class="w-px-40 h-px-40 animate-bg rounded-pill"></div>
								</a>
							</div>
							<div class="awe__chat-item-body relative py-2 px-4 rounded-3 shadow-sm bg-white">
								<div class="animate-bg mb-2 w-px-100 h-px-15 rounded-2"></div>
								<div class="awe__chat-description mb-2">
									<div class="animate-bg mb-1 w-px-150 h-px-15 rounded-2"></div>
									<div class="animate-bg mb-1 w-px-100 h-px-15 rounded-2"></div>
									<div class="animate-bg w-px-200 h-px-15 rounded-2"></div>
								</div>
								<div class="animate-bg w-px-100 h-px-15 rounded-2"></div>
							</div>
						</div>
					</div>
					{/section}
				</div>
			</div>
		</div>
		<script>
			$(function(){
				setTimeout(() => {
					$Core.global.sop.load_chatlogs({});
				}, 1000);
			});
		</script>
		{/if}
		<div class="card mb-2">
			<div class="card-header">
				<h5 class="mb-0">Chuyển nhượng nội bộ</h5>
			</div>
			<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=sop&act=load_sop_chart" 
				gId="{$gId}" data-options='{ldelim}"utm_source":"_market"{rdelim}'>
				<div class="p-5">
					<div class="p-5">
						<div class="p-5">
							<div class="p-3 text-center">
								<img src="{$URL_IMAGES}/loading_48.gif" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{assign var = _gId value = $clsISO->getUniqid()}
		<div class="btn-group d-flex align-items-center justify-content-center mb-2">	
			<input onchange="$Core.helper.set_billing_type(this, event)" type="radio" class="btn-check" 
				id="{$_gId}_primary" name="billing_type" value="primary" checked>
			<label class="btn btn-md text-nowrap btn-outline-default btn-outline-main js-ripple" for="{$_gId}_primary">Sơ cấp, Bán mới</label>
			<input onchange="$Core.helper.set_billing_type(this, event)" type="radio" class="btn-check" 
				id="{$_gId}_secondary" name="billing_type" value="secondary" autocomplete="off">
			<label class="btn btn-md text-nowrap btn-outline-default btn-outline-main js-ripple" for="{$_gId}_secondary">Chuyển nhượng</label>
		</div>
		<div class="card ranking">
			<div class="card-body">
				{$core->getBlock('top_ranking')}
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.card-header{
		position:relative; 
	}
	.card-header::before{
		content: "";
		width: 0px;
		height: 30px;
		position: absolute;
		left: 0px; top: 20px;
		border-left: 5px solid #950b25;
	}
</style>
{/literal}