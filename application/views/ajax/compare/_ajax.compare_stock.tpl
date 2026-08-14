<div class="modal-dialog modal-dialog-centered modal-lg modal_compare">
	<form class="modal-content" id="frmIssue" enctype="multipart/form-data">		
		<div class="modal-header card-header d-flex align-items-center justify-content-between gap-2 bg-main text-white py-3">
			<div class="card-title mb-0 text-upper flex-fill">So sánh căn hộ</div>
			<button class="btn-add-can btn btn-sm btn-default text-white" onClick="$Core.global.compare.add_stock_compare(this,event)" _tp="modal" action="open" toId="body_{$uid}" ><i class="bx bx-plus" ></i> Thêm căn</button>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body bg-lighter overflow-y-auto" style="max-height: calc(100vh - 150px)">
			<div class="card no-shadow border">
				<div class="card-body pt-3" id="body_{$uid}" >
					<div class="table-container overflow-x-auto text-nowrap no-shadow bg-white">
						{if !empty($arr_compare)}
							<table cellpadding="0" cellspacing="0" width="100%" class="table table-billing table-bordered dragable mb-0">
								<thead>
									<tr>
										<th class="align-center bg-lighter h-px-40" width="150px"></th>
										<th class="align-center bg-lighter h-px-40">
											<div class="card h-100 no-shadow border">
												<div class="apt-card p-2">							
													<div class="apt-code mb-1" href="{$clsStock->getLink($_oItem.ms_code)}" target="_blank" ><div class="animate-bg w-100 rounded-2 h-px-15"></div> <i class="fas fa-external-link-alt" style="font-size:10px"></i></div>
													<div class="apt-price mb-1 text-main fw-bold fs-5"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>
													<div class="apt-price mb-1 text-main fw-bold fs-5"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>
													<div class="apt-promo2 mb-1"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>
												</div>
											</div>
										</th>
										<th class="align-center bg-lighter h-px-40">
											<div class="card h-100 no-shadow border">
												<div class="apt-card p-2">			
													<button class="btn btn-icon btn-sm position-absolute right-0 top-0"><i class="bx bx-x" ></i></button>							
													<div class="apt-code mb-1" href="{$clsStock->getLink($_oItem.ms_code)}" target="_blank" ><div class="animate-bg w-100 rounded-2 h-px-15"></div> <i class="fas fa-external-link-alt" style="font-size:10px"></i></div>
													<div class="apt-price mb-1 text-main fw-bold fs-5"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>
													<div class="apt-price mb-1 text-main fw-bold fs-5"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>
													<div class="apt-promo2 mb-1"><div class="animate-bg w-100 rounded-2 h-px-15"></div></div>
												</div>
											</div>
										</th>
									</tr>
									<tr>
										<th class="align-center bg-lighter h-px-40" width="150px">Tiêu chí</th>
										<th class="align-center bg-lighter h-px-40"><div class="animate-bg w-100 rounded-2 h-px-15"></div></th>
										<th class="align-center bg-lighter h-px-40"><div class="animate-bg w-100 rounded-2 h-px-15"></div></th>
									</tr> 
								</thead>
								<tbody class="table-border-bottom-0">
									{if !empty($arr_aciteria)}
										{foreach name=i from=$arr_aciteria item = _oItem key=key}
											<tr class="trBilling ">
												<td class="align-center">{$_oItem.title}</td>
												<td class="align-center text-center"><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
												<td class="align-center text-center"><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
											</tr>
										{/foreach}
									{else}
										<tr>
											<td class="text-center" colspan="3">
												Chưa có tiêu chí
											</td>
										</tr>
									{/if}
								</tbody>
							</table>
						{/if}
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer justify-content-center">
			<button type="button" class="btn btn-outline-secondary d-none" id="delete_all_body_{$uid}" onClick="$Core.global.compare.delete_all_compare(this,event)" toId="body_{$uid}">Xóa tất cả</button>
		</div>
	</form>
</div>
{literal}
<style>
	.modal_compare .table-container .table tbody tr td {
		background: inherit !important;
	}
</style>
{/literal}
