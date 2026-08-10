{if !empty($lstFolder)}
	<div class="form-row row-cols-2 row-cols-sm-2 row-cols-md-4 row-cols-lg-5">
		{foreach from=$lstFolder item = _oFolder}
			<div class="col mb-2">
				<div class="card position-relative">					
					{if $_oFolder.user_id eq $profile_id && $_oFolder.type eq 1}
					<div class="list_action_folder">
						<div class="dropdown" data-bs-toggle="tooltip" data-bs-placement="top" title="Thao tác">
							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i></button>
							<div class="dropdown-menu w-px-100 fs-14">
								<a class="dropdown-item pin px-2 fs-14" onClick="$Core.docs.open_folder(this,event)" type="button" data-folder_id="{$_oFolder.folder_id}" href="javascript:void(0);"> Sửa</a>
							</div>
						</div>
					</div>
					{/if}
					<div class="item_folder card-body d-flex flex-column align-items-center gap-2 mx-auto w-100 cursor-pointer" data-href="{$clsFolder->getLink($_oFolder.folder_id,$_oFolder)}" title="{$_oFolder.title}" onClick="$Core.docs.redirectFolder(this,event)">
						<i class='bx bxs-folder icon_folder'></i>
						<div class="w-100 text-center">
							<div class="title_cat mb-0 fs-14 text-dark d-flex align-items-center justify-content-center gap-1">
								<span class="limit_1line">{$_oFolder.title}</span>
								{if !empty($_oFolder.content)}
									<span class="text-primary" data-url="/index.php?mod=document&act=load_intro&id={$_oFolder.folder_id}&table=Folder" data-toggle="webui-popover" data-trigger="hover" data-width="350" data-placement="top"><i class='bx bx-info-circle' ></i></span>
								{/if}
							</div>
							<span class="text-muted fs-12">Có {$_oFolder.total_doc} tài liệu</span>
						</div>
					</div>
				</div>
			</div>
		{/foreach}
	</div>
{/if}