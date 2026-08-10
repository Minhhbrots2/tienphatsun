<div class="container-xxl flex-grow-1 pt-2 container-p-y" >
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
		<div class="p__left mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1">Danh sách khách hàng</h4>
			<span class="text-muted">Tổng hợp các khách hàng đã thực hiện giao dịch</span>
		</div>
	</div>
	<div class="box_statistic mb-4">
		<div class="row">
			<div class="col-12 col-md-4">
				<div class="box_item_statistic p-3 text-white radius-4" style="background:#eba000">
					<p class="title_statistic fs-6 mb-2">Tổng số khách hàng</p>
					<div class="number_total fs-3">{$total_all}</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card mb-4">
        <div class="card-header bg-lightest d-flex flex-wrap justify-content-between align-items-center">
			<h5 class="mb-lg-0 text-main">Khách hàng có sinh nhật ngày hôm nay</h5>
		</div>
		<div class="card-body">
			<div class="table-responsive dragscroll text-nowrap">
				<table class="table table-striped mb-2" width="100%">
					<thead><tr>
						{if $deviceType ne "phone"}
							<th class="text-center" width="60px">STT</th>
						{/if}
						<th class="align-center">Tên khách hàng</th>
						<th class="align-center">Ngày sinh</th>
						<th class="align-center">Giới tính</th>
						<th class="align-center" {if $deviceType ne "phone"}style="border-left: 1px solid #DDD"{/if}>Email</th>
						<th class="align-center">Điện thoại</th>
					</tr> </thead>
					<tbody class="table-border-bottom-0">
						{if !empty($lstClientBirthday)}
							{foreach name=i from=$lstClientBirthday item = _oClient}
							{assign var = client_id value = $_oClient.client_id}
							{assign var = more_information value = $_oClient.more_information}
							<tr{if $_oClient.is_cancel eq '1'} class="trBilling bg-cancel nohover"{else} class="trBilling"{/if}{if $permiss_view eq '1'} ondblclick="view_client(this, event)"{/if} client_id="{$client_id}">
								{if $deviceType ne "phone"}
									<td class="text-center">{$smarty.foreach.i.iteration}</td>
								{/if}
								<td data-label="Tên khách hàng">
									<a  onClick="view_client(this,event)" client_id="{$_oClient.client_id}" href="javascript:void(0);">{$_oClient.full_name}</a>							
								</td>
								<td data-label="Ngày sinh">{$clsISO->convertTimeToTextFormat($_oClient.birthday,'d/m/Y')}</td>
								<td data-label="Giới tính">{$_oClient.gender}</td>
								<td class="text-wrap" data-label="Email" {if $deviceType ne "phone"}style="border-left: 1px solid #DDD"{/if}><a href="mailto:{$_oClient.email}">{$_oClient.email}</a></td>
								<td data-label="Điện thoại"><a href="tel:{$_oClient.phone}">{$_oClient.phone}</a></td>
							</tr>
							{/foreach}
						{else}
							<tr>
								<td class="text-center" colspan="{if $deviceType eq 'phone'}5{else}6{/if}">
									Không có khách hàng sinh nhật vào ngày hôm nay !
								</td>
							</tr>
						{/if}
					</tbody>
				</table>
			</div>
		</div>
    </div>
    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header bg-lightest d-flex flex-wrap justify-content-between align-items-center">
			<h5 class="mb-lg-0 ">Tổng <strong class="text-danger">{$total_record}</strong> khách hàng</h5>
			<form method="POST"{if $deviceType eq 'phone'} class="w-100"{/if}>
				<div class="search d-flex">
					<input type="hidden" id="list_selected_chkitem" name="lst_client">
					<input type="hidden" name="hidden_search" value="hidden_search">
					<div class="input-group">
						<input type="text" name="keyword" value="{$keyword}" class="form-control no-radius-right" placeholder="Tìm kiếm..." />
					</div>	
					<button type="submit" class="btn btn-icon btn-outline-default no-wrap">
						<span>{$clsISO->makeIcon('bx-search')}</span>
					</button>			
					<div class="search{if $deviceType ne 'phone'} d-flex{/if} ml-2 d-none">
						<div class="btn-group">
							<button type="button" class="btn btn-outline-default dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Lựa chọn</button>
							<ul class="dropdown-menu" style="">
								<li><a class="dropdown-item" href="javascript:void(0);" onClick="$Core.client.loadFormSubmit(this,event)" data-type="email" data-action="open">Gửi mail</a></li>
							</ul>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body">
			<div class="table-responsive dragscroll text-nowrap">
				<table class="table table-striped mb-2" width="100%">
					<thead><tr>
						{if $deviceType ne "phone"}
							<th class="text-center" width="60px">STT</th>
						{/if}
						<th class="align-center">Tên khách hàng</th>
						<th class="align-center">Ngày sinh</th>
						<th class="align-center" {if $deviceType ne "phone"}style="border-left: 1px solid #DDD"{/if}>Email</th>
						<th class="align-center">Điện thoại</th>
						<th width="45px"></th>
					</tr> </thead>
					<tbody class="table-border-bottom-0">
						{if !empty($lstClient)}
						{foreach name=i from=$lstClient item = _oClient}
						{assign var = client_id value = $_oClient.client_id}
						{assign var = more_information value = $_oClient.more_information}
						<tr{if $_oClient.is_cancel eq '1'} class="trBilling bg-cancel nohover"{else} class="trBilling"{/if}{if $permiss_view eq '1'} ondblclick="view_client(this, event)"{/if} client_id="{$client_id}">
							{if $deviceType ne "phone"}
								<td class="text-center">{$smarty.foreach.i.iteration}</td>
							{/if}
							<td data-label="Tên khách hàng">
								{if $_oClient.permiss_view}
									<a class="" onClick="view_client(this,event)" client_id="{$_oClient.client_id}" href="javascript:void(0);">{$_oClient.full_name}</a>
								{else}
									{$_oClient.full_name}
								{/if}
							</td>
							<td data-label="Ngày sinh">{if $_oClient.birthday ne ""}{$_oClient.birthday}{else}Chưa cập nhật{/if}</td>
							<td class="text-wrap" data-label="Email" {if $deviceType ne "phone"}style="border-left: 1px solid #DDD"{/if}><a href="mailto:{$_oClient.email}">{$_oClient.email}</a></td>
							<td data-label="Điện thoại"><a href="tel:{$_oClient.phone}">{$_oClient.phone}</a></td>
							<td data-label="H.Động" class="text-center">
								{if $_oClient.permiss_view}
									<form action="POST">
										<div class="dropdown">
											<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
												data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item d-none" href="{$clsClient->getLink($_oClient.client_id,$_oClient)}"><i class="bx bx-bullseye me-1"></i> Xem</a>	
												<a class="dropdown-item" onClick="view_client(this,event)" client_id="{$_oClient.client_id}" href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>											
												<hr size="0" class="dropdown-divider" />
												<a class="dropdown-item" tp="quick" onClick="$Core.client.edit_client(this,event)" client_id="{$_oClient.client_id}" data-action="open" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
												<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.client.delete_client(this,event)" client_id="{$_oClient.client_id}"><i class="bx bx-trash me-1"></i> Xóa</a>
											</div>
										</div>
									</form>
								{/if}
							</td>
						</tr>
						{/foreach}
						{else}
							<tr>
								<td class="text-center" colspan="{if $deviceType eq 'phone'}7{else}8{/if}">
									Không có dữ liệu khách hàng nào !
								</td>
							</tr>
						{/if}
					</tbody>
				</table>
			</div>
			{if $total_page gt '1'}
			<div id="pager" class="d-flex justify-content-center mt-3">
				<ul class="pagination">{$html_pager}</ul>
			</div>
			{/if}
		</div>
    </div>
</div>
{literal}
<style type="text/css">
	tr.bg-cancel td{
		background:#fbe9e9 !important;
		--bs-table-accent-bg:#fbe9e9 !important;
	}
	.freeze-table {
        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
	}
	.freeze-table .table th{
		line-height:16px;
		vertical-align:middle;
	}
	.no-radius-right .selectize-input{
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
		border-right: 0px !important;
	}
	.selectize-input,
	.selectize-control.single .selectize-input.focus{
		padding:7px !important;
		min-height:37.5px !important;
	}
	@media screen and (max-width:648px){
		.freeze-table .table tr>th:nth-child(1),
		.freeze-table .trBilling td:nth-child(1){
			border-right:1px solid #DDD;
		}
	}
	@media screen and (min-width:648px){
		.freeze-table .table{
			margin-bottom:0;
			min-width:1200px;
			max-width:16000px;
		}
		.freeze-table .table tr>th:nth-child(3),
		.freeze-table .trBilling td:nth-child(3){
			border-right:1px solid #DDD;
		}
	}
	.jconfirm.jconfirm-light .jconfirm-box .jconfirm-buttons{
		width:100%;
		display: -webkit-box!important;
		display: -ms-flexbox!important;
		display: flex!important;
		-webkit-box-pack: end!important;
		-ms-flex-pack: end!important;
		justify-content: flex-end!important;
	}
</style>
<script type="text/javascript">
$(function(){
	setTimeout(() => {
		$('.freeze-table').freezeTable({
			'columnNum': {/literal}{if $deviceType eq 'phone'}1{else}3{/if}{literal},
			'scrollable': true,
			'columnKeep': false,
		});
	},1000);
});
</script>
{/literal}