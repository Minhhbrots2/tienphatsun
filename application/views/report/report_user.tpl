<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Danh sách người dùng</span></h4>
			<p class="text-muted mb-0">Hệ thống hỗ trợ bán hàng Ocean City</p>
		</div>
	</div>
	<div class="box_statistic">
		<div class="row mt-2">
			<div class="col-12">
				<div class="dashboard-panel-item dashboard-panel-item--full">
					<div class="panel border-0 no-shadow panel-default">
						<div class="panel-heading d-flex flex-wrap justify-content-between align-items-center gap-3 head_user">
							<h3 class="panel-title" style="white-space: break-spaces">Danh sách người dùng</h3>
							<div class="d-flex flex-wrap align-items-center justify-content-between flex-fill gap-3">
								<ul class="tab_package nav d-flex flex-wrap gap-2 list pull-right">															
									<li class="nav-item">										
										<input type="radio" name="status" value="unactive" onchange="$Core.report.loadUser('unactive', event)" id="unactive" checked>
										<label href="javascript:void(0);" for="unactive" class="js_choose-time cursor-pointer">Chưa xác thực</label>
									</li>											
									<li class="nav-item">										
										<input type="radio" name="status" value="active" onchange="$Core.report.loadUser('active', event)" id="active" >
										<label href="javascript:void(0);" for="active" class="js_choose-time cursor-pointer">Đã xác thực</label>
									</li>
								</ul>
								<div class="p_right ">
									<div class="input-group mr-1 input-group-merge">
										<span class="input-group-text"><i class="bx bx-search"></i></span>
										<input type="text" class="form-control search_field" name="keyword" data-field="keySearch" placeholder="Search" onkeyup="$Core.report.searchUser(this,event)" id="keySearchUser">
									</div>
								</div>
							</div>							
						</div>
						<div id="list_user" class="panel-body px-0">
							<table class="table table-bordered mb-0" width="100%">
								<thead><tr>
									{if $deviceType ne 'phone'}
									<th width="30px" class="align-center nosort bg-lighter">STT</th>
									{/if}
									<th class="align-center text-left bg-lighter" style="width:100px">Họ và tên</th>
									<th class="align-center text-left bg-lighter">Email</th>
									<th class="align-center text-left bg-lighter">Số điện thoại</th>
									<th class="align-center text-left bg-lighter">Xác thực</th>
								</tr></thead>
								<tbody>
									{section name=i loop=$list_preloaders max=20}
									<tr>
										{if $deviceType ne 'phone'}
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										{/if}
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
										<td><div class="animate-bg w-100 h-px-20 rounded-1"></div></td>
									</tr>
									{/section}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
$(document).ready(function(){
	$Core.report.loadUser('unactive', event);
});
</script>
{/literal}