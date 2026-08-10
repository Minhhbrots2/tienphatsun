<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="d-flex flex-wrap justify-content-between align-items-center">
		<div class="nvzAnQLQxE mb-3">
			<h4 class="fw-bold mb-1"><span>Danh sách yêu cầu PTG</span></h4>
			<span class="text-muted">Có tổng cộng <strong>({$total_record})</strong> yêu cầu</span>
		</div>
		{if $deviceType eq "phone"}
		<div class="dropdown">
			<button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" 
				aria-haspopup="true" aria-expanded="false">Chọn</button>
			<ul class="dropdown-menu dropdown-scrollable scroll-y-auto-hover">
				<li class="dropdown-item">
					<a href="{$clsISO->getLink('request_ptg')}" class="dropdown-link {if $status eq "" }text-primary{else}text-dark{/if}">Tất cả ({$total_record})</a>
				</li>
				<li class="dropdown-item">
					<a href="{$clsISO->getLink('request_ptg')}?status=0" class="dropdown-link {if $status eq '0' }text-primary{else}text-dark{/if}">Đang chờ ({$total_pendding})</a>
				</li>
				<li class="dropdown-item">
					<a href="{$clsISO->getLink('request_ptg')}?status=1" class="dropdown-link {if $status eq '1' }text-primary{else}text-dark{/if}">Đã cập nhật ({$total_success})</a>
				</li>
			</ul>
		</div>
		{else}
		<div class="d-flex align-items-center gap-1">
			<a href="{$clsISO->getLink('request_ptg')}" class="btn {if $status eq "" }btn-primary{else}btn-outline-default{/if}">Tất cả ({$total_record})</a>
			<a href="{$clsISO->getLink('request_ptg')}?status=0" class="btn {if $status eq '0' }btn-primary{else}btn-outline-default{/if}">Đang chờ ({$total_pendding})</a>
			<a href="{$clsISO->getLink('request_ptg')}?status=1" class="btn {if $status eq '1' }btn-primary{else}btn-outline-default{/if}">Đã cập nhật ({$total_success})</a>
		</div>
		{/if}
	</div>
	<div class="form-row">
		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
			<div class="box_item_statistic h-100 p-3 bg-danger text-white rounded-2"> 
				<p class="title_statistic fs-6 mb-2">Tổng số yêu cầu</p>
				<div class="number_total fs-3">{$total_record}<span class="fs-14 ml-1">yêu cầu</span></div>
			</div>
		</div>
		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
			<div class="box_item_statistic h-100 p-3 text-white rounded-2" style="background:#eba000"> 
				<p class="title_statistic fs-6 mb-2">Đang chờ</p>
				<div class="number_total fs-3">{$total_pendding}<span class="fs-14 ml-1">yêu cầu</span></div>
			</div>
		</div>
		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
			<div class="box_item_statistic h-100 p-3 text-white rounded-2" style="background:#1d6a01"> 
				<p class="title_statistic fs-6 mb-2">Đã phản hồi</p>
				<div class="number_total fs-3">{$total_success}<span class="fs-14 ml-1">yêu cầu</span></div>
			</div>
		</div>
		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">
			<div class="box_item_statistic h-100 p-3 text-white rounded-2 bg-primary"> 
				{if $deviceType eq 'phone'}
				<p class="title_statistic fs-6 mb-2">T.Gian trung bình</p>
				{else}
				<p class="title_statistic fs-6 mb-2">Thời gian trung bình</p>
				{/if}
				{foreach from=$list_ranges item = _oR}
				<div class="d-flex mb-0 align-items-center justify-content-between">
					<span>{$_oR.title}</span>
					<span>{$_oR.avg_time}</span>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="table-container overflow-x-auto text-nowrap no-shadow table-sticky-last">
				<table class="table dragable" cellpadding="0" cellspacing="0" 
					   style="width:{if $deviceType eq 'phone'}calc(100% + 100px){else}100%{/if}">
					<thead><tr>
						{if $deviceType ne 'phone'}
						<th width="3%" class="align-center bg-lighter h-px-40 text-center">No.</th>
						{/if}
						<th width="100px" class="align-center bg-lighter h-px-40 text-left">Mã căn</th>
						<th width="" class="align-center h-px-40 bg-lighter text-left">Người yêu cầu</th>
						<th class="align-center h-px-40 bg-lighter border-right" width="150px">Thời gian gửi</th>
						<th class="align-center h-px-40 bg-lighter border-right" width="200px">Nội dung</th>
						<th width="" class="align-center bg-lighter h-px-40 text-left">Cập nhật bởi</th>
						<th class="align-center bg-lighter h-px-40 border-right" width="150px">Thời gian cập nhật</th>
						<th class="align-center bg-lighter h-px-40 text-center" width="150px">Trạng thái</th>
						<th width="100px" class="align-center bg-lighter h-px-40 text-align-center"></th>
					</tr></thead>
					<tbody class="holder_stocks">
						{foreach from=$list_requests item=_oItem key=key name=i}
						<tr>
							{if $deviceType ne 'phone'}
							<td class="text-center">{$smarty.foreach.i.iteration}</td>
							{/if}
							<td class="text-left">
								{if $_oItem.is_urgent eq '1'}
								<span class="label bg-label-danger">GẤP</span>
								{/if}
								<a href="javascript:void(0)" {if $deviceType eq 'phone'}onClick="$Core.helper.open_stock('{$_oItem.stock_id}');" {else}data-url="{$PCMS_URL}/index.php?mod=home&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto"{/if} data-width="350">{$_oItem.ms_code}</a>
								{if $deviceType eq 'phone' && $_oItem.status_id eq '0'}
								<div class="btn-group ml-1">
									<button data-toggle="ripple" type="button" onclick="$Core.helper.open_quick_stock(this,event)" tp="sheet_price" stock_id="{$_oItem.stock_id}" class="btn btn-sm btn-icon btn-outline-default text-nowrap" from="request_ptg"><i class="fa fa-plus"></i> </button>
									<button data-toggle="ripple" type="button" onclick="$Core.helper.soldout_stock(this,event)" tp="sheet_price" stock_id="{$_oItem.stock_id}" request_ptg_id="{$_oItem.id}" class="btn btn-sm btn-icon btn-outline-danger text-nowrap" from="request_ptg"><i class="bx bx-x"></i></button>
									{if !empty($_oItem.price_sheets)}
										<button data-toggle="ripple" type="button" onclick="$Core.helper.exist_ptg(this,event)" tp="sheet_price" stock_id="{$_oItem.stock_id}" request_ptg_id="{$_oItem.id}" class="btn btn-sm btn-icon btn-outline-danger text-nowrap" from="request_ptg"><i class='bx bx-circle'></i></button>
									{/if}
								</div>
								{/if}
								{if $_oItem.status_id eq '1' && !empty($_oItem.time_feedback)}
									({$clsISO->getTimeHtml($_oItem.time_feedback)})
								{else}
									{$clsISO->getTimeHtml($_oItem.time_waiting,"rồi")}
								{/if}
							</td>
							<td class="text-nowrap">{$_oItem.staff_name}</td>
							<td class="border-right">{$clsISO->formatDate($_oItem.reg_date,4)}</td>
							<td class="border-right">{$_oItem.notes|html_entity_decode|strip_tags}</td>
							{if !empty($_oItem.user_updated_id)}
								<td class="text-nowrap">{$clsProfile->getFullName($_oItem.user_updated_id)}</td>
							{else}
								<td>--</td>
							{/if}
							{if !empty($_oItem.upd_date)}
								<td class="text-nowrap">{$clsISO->formatDate($_oItem.upd_date,4)}</td>
							{else}
								<td class="text-left">--</td>
							{/if}
							{if $_oItem.status_id eq '0'}
								<td class="text-warning text-center">Đang chờ</td>
								<td class="text-center">
									<div class="btn-group">
										<button data-toggle="ripple" type="button" onclick="$Core.helper.open_quick_stock(this,event)" tp="sheet_price" stock_id="{$_oItem.stock_id}" class="btn btn-sm btn-outline-default text-nowrap" from="request_ptg"><i class="fa fa-plus"></i> PTG</button>
										<button data-toggle="ripple" type="button" onclick="$Core.helper.soldout_stock(this,event)" tp="sheet_price" stock_id="{$_oItem.stock_id}" request_ptg_id="{$_oItem.id}" class="btn btn-sm btn-danger text-nowrap" from="request_ptg"><i class="bx bx-x"></i> Đã bán</button>
										{if !empty($_oItem.price_sheets)}
											<button data-toggle="ripple" type="button" onclick="$Core.helper.exist_ptg(this,event)" tp="sheet_price" stock_id="{$_oItem.stock_id}" request_ptg_id="{$_oItem.id}" class="btn btn-sm btn-success text-nowrap" from="request_ptg"><i class='bx bx-circle'></i> Đã có</button>
										{/if}
									</div>
								</td>
							{else}
								<td class="text-success text-center">Đã có</td>
								<td class="text-center">--</td>
							{/if}										
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>			
			{if !empty($html_pager)}
				<div class="pagination justify-content-center mt-4 flex-wrap" style="row-gap: 3px">{$html_pager}</div>
			{/if}
		</div>
	</div>
</div>
{literal}
<style type="text/css">
@media screen and (min-width : 576px){
	.table-container .table tr th:nth-child(2),
	.table-container .table tr td:nth-child(2){
		z-index:2;
		position:sticky;
		left:45px; top:0;
	}
	.table-container .table tr th:nth-child(2){
		background:#F5F7F8 !important;
	}
	.table-container .table tr td:nth-child(2){
		background:var(--bs-white);
	}
}
	
.zalo_chat{
	display:inline-block;
	width:18px; 
	height:18px;
	border-radius:3px;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	-khtml-border-radius:3px;
	background:#03a5fa url("/application/themes/images/logo_white_s_40.png") no-repeat center center;
	background-size:13px;
	transform: translate(5px, 5px);
	-moz-transform: translate(5px, 5px);
	-webkit-transform: translate(5px, 5px);
	-khtml-transform: translate(5px, 5px);
}
</style>
{/literal}