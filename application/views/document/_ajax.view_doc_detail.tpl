<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-main">{if !empty($oneItem.is_important) }<i class='bx bxs-star text-warning me-1' title="Quan trọng" ></i>{/if}{$oneItem.title}</h5>
				<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body scroller">
				<div class="body_detail_doc">
					<ul class="nav nav-tabs nav-fill border-bottom" role="tablist">
						<li class="nav-item" role="presentation">
							<button type="button" class="nav-link active p-0 pb-2 bg-white fs-15" role="tab" data-bs-toggle="tab" data-bs-target="#navs-detail_{$uid}" aria-controls="navs-detail_{$uid}" aria-selected="true">
							  <span class="pb-2">Chi tiết</span>
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button type="button" class="nav-link p-0 pb-2 bg-white fs-15" role="tab" data-bs-toggle="tab" data-bs-target="#navs-logs_{$uid}" aria-controls="navs-logs_{$uid}" aria-selected="true">
							  <span class="pb-2">Hoạt động</span>
							</button>
						</li>
					</ul>
					<div class="tab-content no-shadow px-0">
						<div class="tab-pane fade active show" id="navs-detail_{$uid}" role="tabpanel">
							<h6 class="mb-2 text-dark fs-16">Thông tin chi tiết văn bản</h6>
							<div class="d-flex flex-column justify-content-between mb-3 pl-3">
								<span class="text-dark mb-1">Thư mục</span>
								<a class="text-dark rounded-1 border d-flex align-items-center gap-1 folder_detail px-2 py-1 fs-12" href="{$clsFolder->getLink($oneItem.cat_id,$oneCat)}"><i class="bx bxs-folder icon_folder"></i>{$oneCat.title}</a>
							</div>
							{if !empty($more_information.document_number)}
								<div class="d-flex flex-column justify-content-between mb-3 pl-3">
									<span class="text-dark">Số hiệu</span>
									<span class="text-muted">{$more_information.document_number}</span>
								</div>
							{/if}
							{if !empty($file_doc)}
							<div class="d-flex flex-column justify-content-between mb-3 pl-3">
								<span class="text-dark mb-1">File đính kèm</span>
								<div class="text-muted">
									{if $more_information.type eq 'file'}
										{foreach from=$file_doc item=_oFile}
											<div class="d-inline-flex gap-1 align-items-center border btn btn-sm rounded-1 w-auto mb-1" data-fancybox="gallery_detail_{$uid}_{$oneItem.doc_id}" {$_oFile.file_type}SSS {if $_oFile.file_type eq "image"} data-src="{$_oFile.link}"{else if $_oFile.file_type eq "doc"} href="https://docs.google.com/viewer?embedded=true&url={$DOMAIN_URL}{$_oFile.link}" data-type="iframe" {else if $_oFile.file_type eq "excel"} href="https://view.officeapps.live.com/op/view.aspx?src={$smarty.const.FH_URL}{$_oFile.link}." data-type="iframe" {else}href="{$_oFile.link}" data-type="iframe" {/if} data-caption="{$_oFile.name}">
												<img src="{$_oFile.icon}" alt="{$_oFile.name}" class="" width="20">
												<span class="limit_1line">{$_oFile.name}</span>
											</div>
										{/foreach}
									{else}
										{foreach from=$file_doc item=_oFile}
											<div class="d-inline-flex gap-1 align-items-center border btn btn-sm rounded-1 w-auto mb-1" data-fancybox="gallery_detail_{$uid}_{$oneItem.doc_id}" {$_oFile.file_type}SSS {if $_oFile.file_type eq "image"} data-src="{$_oFile.link}"{else if $_oFile.file_type eq "doc"} href="{$_oFile.link}" data-type="iframe" {else if $_oFile.file_type eq "excel"} href="{$_oFile.link}" data-type="iframe" {else}href="{$_oFile.link}" data-type="iframe" {/if}  data-caption="{$_oFile.name}">
												<img src="{$_oFile.icon}" alt="{$_oFile.name}" class="" width="20">
												<span class="limit_1line">{$_oFile.name}</span>
											</div>
										{/foreach}
									{/if}									
								</div>
							</div>
							{/if}
							<div class="d-flex flex-column justify-content-between mb-3 pl-3">
								<span class="text-dark">Người tạo</span>
								<span class="text-muted">{$clsProfile->getFullName($oneItem.user_id)}</span>
							</div>
							<div class="d-flex flex-column justify-content-between mb-3 pl-3">
								<span class="text-dark">Ngày tạo</span>
								<span class="text-muted">{$oneItem.reg_date}</span>
							</div>
							<div class="d-flex flex-column justify-content-between mb-3 pl-3">
								<span class="text-dark">Cập nhật lần cuối</span>
								<span class="text-muted">{$oneItem.upd_date}</span>
							</div>
							{if !empty($oneItem.content)}
								<div class="d-flex flex-column justify-content-between mb-3 pl-3">
									<span class="text-dark">Mô tả</span>
									<span class="text-muted">{$oneItem.content}</span>
								</div>
							{/if}
							<div class="d-flex flex-column justify-content-between mb-3 pl-3">
								<span class="text-dark">Người có quyền truy cập</span>
								<span class="text-muted">{$text_role}</span>
							</div>
							{if !empty($more_information.authorized_person)}
								<div class="d-flex flex-column justify-content-between mb-3 pl-3">
									<span class="text-dark">Người ban hành</span>
									<span class="text-muted">{$clsProfile->getFullName($more_information.authorized_person)}</span>
								</div>
							{/if}
							{if !empty($more_information.effective_date)}
								<div class="d-flex flex-column justify-content-between mb-3 pl-3">
									<span class="text-dark">Ngày ban hành</span>
									<span class="text-muted">{$more_information.effective_date}</span>
								</div>
							{/if}
						</div>
						<div class="tab-pane fade" id="navs-logs_{$uid}" role="tabpanel">
							<h6 class="mb-2 text-dark fs-16">Lịch sử thay đổi</h6>
							
							{if !empty($logs)}
								{foreach from=$logs item=_oLog}
									{assign var=lstUpdate value=$_oLog.logs}
									<div class=" pl-3">
										<div class="d-flex justify-content-start align-items-center pt-3">
											<div class="avatar avatar-sm me-2">
												<img src="{$_oLog.avatar}" alt="Avatar" class="rounded-circle">
											</div>
											<div class="d-flex flex-column fs-13 content-right_log">
												<span class="text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{if $_oLog.action eq 'update'}Người sửa đổi{else}Người tạo{/if}">{$_oLog.full_name} {$_oLog.text}</span>
												<span class="time" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Thời gian">{$_oLog.reg_date}</span>
												{*{if !empty($lstUpdate)}
													{foreach from=$lstUpdate item=_oLogUpdate}
														<div class="d-flex gap-1 mt-2 pl-2">
															<span class="text-dark text-nowrap">{$_oLogUpdate.field_label}: </span>
															<span class="text-muted">{$_oLogUpdate.from_value} <i class='bx bx-right-arrow-alt'></i> {$_oLogUpdate.to_value}</a>
														</div>
													{/foreach}
												{/if}*}
											</div>
										</div>
									</div>
								{/foreach}
							{else}
							<div class="p-2 text-center">Chưa có lịch sử thay đổi văn bản này</div>
							{/if}
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>