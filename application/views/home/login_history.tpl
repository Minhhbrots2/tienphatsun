<div class="container-xxl flex-grow-1 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-3 mb-2">
		<h4 class="fw-bold mb-0">Lịch sử đăng nhập</span></h4>
	</div>
    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
			<h5 class="mb-0">Có <strong class="total_record text-danger">{$total_record}</strong> lịch sử</h5>
			<form method="POST">
				<div class="search d-flex">
					<div class="input-group mr-1 input-group-merge">
						<span class="input-group-text"><i class="bx bx-search"></i></span>
						<input type="text" class="form-control search_field" data-field="keySearch" placeholder="{$core->get_Lang('Search')}" />
					</div>
					{if $deviceType ne 'phone'}
					<div class="input-group input-date-picker mr-1 w-px-250">
						<i class="ico ico-calendar"></i>
						<input type="text" value="{$start_date}" class="form-control from_date w-px-100 search_field" 
						placeholder="Từ ngày" data-field="start_date">
						<input type="text" value="{$end_date}" class="form-control to_date w-px-100 search_field" 
						placeholder="Đến ngày" data-field="end_date">
					</div>
					{/if}
					<div class="btn-group mr-1">
						<button type="button" class="btn btn-default dropdown-toggle" 
						data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"></button>
						<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">
							<div class="p-4">
								{if $deviceType eq 'phone'}
								<div class="input-group input-date-picker mb-2">
									<i class="ico ico-calendar"></i>
									<input type="text" value="{$start_date}" class="form-control from_date search_field w-px-100" placeholder="Từ ngày" data-field="start_date">
									<input type="text" value="" class="form-control to_date search_field w-px-100" 
									placeholder="Đến ngày" data-field="end_date">
								</div>
								{/if}
							   <div class="form-group w-100 mb-2">
									<select onChange="select_profile_in_department(this, event)" toId="slb_Profile_Id" class="form-control w-100 form-select search_field" data-field="department_id">{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$oneItem.department_id,'Phòng ban')}
									</select>
								</div>
								<div class="form-group mb-2">
									<select id="slb_Profile_Id" class="iso-select2 search_field" data-field="user_id" data-width="100%" data-placeholder="Nhân viên" data-allow-clear="true">
										<option value="0">Nhân viên</option>
										{foreach from=$lstUser item=item name=item}
										<option value="{$item.profile_id}">
											{$clsProfile->getFullName($item.profile_id,$item)}
										</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
					</div>
					<button type="button" class="btn btn-success" onClick="do_search(this, event)">
						<i class="bx bx-search"></i>
					</button>
				</div>
			</form>
		</div>
		<div class="card-body">
			<div class="table-responsive freeze-table dragscroll text-nowrap">
				<table border="0" class="table table-striped mb-4" width="100%">
					<thead><tr>
						<th class="align-center">Nhân viên</th>
						<th class="align-center">H.Động</th>
						<th class="align-center">Thời gian</th>
						<th class="align-center">Địa chỉ IP</th>
						<th class="align-center">Trình duyệt</th>
					</tr> </thead>
					<tbody class="holder_logs">
						<tr>
							<td colspan="5">
								<div class="p-5 text-center">
									<img src="{$URL_IMAGES}/ripple-loading.svg" />
									<p class="text-center">Loading...</p>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
    </div>
    <!--/ Basic Bootstrap Table -->
</div>
{literal}
<script type="text/javascript">
	$(function(){
		load_logs({});
	});
	function do_search(_this, e){
		e.preventDefault();
		load_logs({});
		return false;
	}
	function load_logs(options){
		var $_adata = {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_logs', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_logs').html(respJson.html);
			$('.total_record').html(respJson.total_record);
		},'json');
	}
</script>
{/literal}