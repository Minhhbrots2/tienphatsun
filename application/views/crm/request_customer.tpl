<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-xxl-10 offset-xxl-1">
			<div class="card">
				<div class="card-header">
					<div class="d-flex  align-items-center justify-content-between">
						<div class="d-flex flex-column">
							<h5 class="chat-title mb-0">Quản lý yêu cầu mua gói</h5>
							<span class="text-muted fs-11">Tổng <strong class="text-main">{$total_record}</strong> yêu cầu</span>
						</div>
					</div>
				</div>
				<div class="card-body">
					{assign var=uid value=$clsISO->getUniqid()}
					<div class="briefs  d-flex gap-1 gap-lg-2 align-items-center mb-2">
						<a href="{$clsISO->getLink(request_package)}" _type="_buy" class="brief-item a1a bg-orange flex-fill cursor-pointer" status="0" uid="{$uid}">
							<p class="text-fs-14 mb-0">Tổng y/c</p>
							<hr class="w-px-50 my-2">
							<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">{$total_record}</h3>
						</a>
						<a href="{$clsISO->getLink(request_package)}?status=1" _type="_buy" status="1" 
							class="brief-item a2a bg-azure flex-fill cursor-pointer" uid="{$uid}">
							<p class="text-fs-14 mb-0">Đã xử lý</p>
							<hr class="w-px-50 my-2">
							<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">{$total_assigned}</h3>
						</a>
						<a  href="{$clsISO->getLink(request_package)}?status=2" _type="_buy" status="2" 
							class="brief-item a2a bg-purple flex-fill cursor-pointer" uid="{$uid}">
							<p class="text-fs-14 mb-0">Chưa xử lý</p>
							<hr class="w-px-50 my-2">
							<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">{$total_unassigned}</h3>
						</a>
					</div>
					<div class="table-container text-nowrap no-shadow overflow-x-auto">
						<table cellpadding="0" cellspacing="0" class="table dragable table-bordered mb-0" width="100%">
							<thead><tr>
								<th class="align-center h-px-35 bg-lighter">Ngày</th>
								<th class="align-center h-px-35 bg-lighter">Họ và tên</th>
								<th class="align-center h-px-35 bg-lighter">Gói</th>
								<th class="align-center h-px-35 bg-lighter">S.Lượng</th>
								<th class="align-center h-px-35 bg-lighter">Dự án</th>
								<th class="align-center h-px-35 bg-lighter">Người xử lý</th>
								<th class="align-center h-px-35 bg-lighter">Ngày xử lý</th>
								<th class="align-center h-px-35 bg-lighter text-center">T.Trạng</th>
							</tr></thead>
							<tbody class="">
								{if !empty($list_items)}
									{foreach from=$list_items item=_oItem}
										<tr class="">
											<td class="align-center">{$clsISO->convertTimeToText($_oItem.reg_date, true)}</td>
											<td class="align-center">{$_oItem.staff_name}</td>
											<td class="align-center">{$_oItem.package_name}</td>
											<td class="align-center text-center">{$_oItem.amount}</td>
											<td class="align-center">{$_oItem.project_name}</td>
											<td class="align-center">{if !empty($_oItem.user_update)}{$_oItem.user_update}{else}--{/if}</td>
											<td class="align-center">{if !empty($_oItem.status_date)}{$clsISO->convertTimeToText($_oItem.status_date,true)}{else}--{/if}</td>
											<td class="align-center text-center">
												{if !empty($_oItem.status)}
													
													{if $_oItem.status eq 1 || $_oItem.total_paid gte $_oItem.amount}
														<span class="text-success text-center">Đã trả hết</span>														
													{else}
														<span class="text-warning text-center">Đã trả {$_oItem.total_paid}, còn {$_oItem.total_remining}</span>
													{/if}
												{else}
													<span class="text-danger text-center">Chưa trả khách</span>
												{/if}
											</td>
										</tr>
									{/foreach}
								{else}
									<tr>
										<td colspan="7" class="text-center border-end">
											<img src="{$URL_IMAGES}/DataEmpty.svg" class="w-px-100" />
											<p class="text-muted">Chưa có yêu cầu nào</p>
										</td>
									</tr>
								{/if}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var uid = `{$uid}`;
</script>
{literal}
<script>
	$(document).ready(function(){
		$Core.global.crm.load_req_customer(uid, {"_type":"_buy"}, false);
	});
</script>
{/literal}