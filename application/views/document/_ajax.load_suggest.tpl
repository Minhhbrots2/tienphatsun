{if !empty($lstItem)}
	{if !empty($lstItem)}
		<div class="d-flex flex-wrap gap-2 mt-2 border-top py-2">
			<div class="w-100 text-dark fs-6 fw-semibold">Tài liệu</div>
			{foreach from=$lstItem item=_oItem name=i}
				{assign var=file_doc value=$_oItem.file_doc}
					<div class="item_suggest cursor-pointer py-2 border-bottom w-100">
						<div class="title_doc mb-0 fs-16 d-flex flex-column gap-1 justify-content-between" onclick="$Core.docs.view_doc(this,event)" data-view="view_suggest" doc_id="{$_oItem.doc_id}">
							<div class="d-flex align-items-center gap-1 text-dark">
								{if !empty($_oItem.is_important) }<i class='bx bxs-star text-warning align-bottom' ></i>{/if} 
								<span class="limit_1line">{$_oItem.title}</span>
								{if !empty($_oItem.content)}
									<span class="text-primary" data-url="/index.php?mod=document&act=load_intro&id={$_oItem.doc_id}&table=Docs" data-toggle="webui-popover" data-trigger="hover" data-width="350" data-placement="top" data-target="webuiPopover1"><i class="bx bx-info-circle"></i></span>
								{/if}
							</div>
							<div class="d-flex align-items-center justify-content-start gap-2 flex-wrap">
								<div class="item_folder_suggest d-inline-flex  w-auto gap-1 cursor-pointer p-0 rounded-1">
									<i class='bx bxs-folder icon_folder'></i>
									<div class="title_cat mb-0 fs-14 text-muted d-flex align-items-center justify-content-center gap-1">
										<span class="limit_1line">{$_oItem.folder_name}</span>
									</div>
								</div>
								<div class="d-flex justify-content-start align-items-center text-muted">
									<div class="avatar avatar-xxs me-1">
										<img src="{$_oItem.avatar}" alt="Avatar" class="rounded-circle">
									</div>
									<div class="fs-13">
										<span class="">{$_oItem.full_name}</span>
										<span class="time">{$_oItem.time}</span>
									</div>
								</div>
							</div>
						</div>
						{if !empty($file_doc)}
							<div class="lst_image">
								{if $_oItem.type eq 'file'}
									{foreach from=$file_doc item=_oFile}
										<div class="item_file d-none" data-fancybox="gallery_suggest_{$_oItem.doc_id}" {if $_oFile.file_type eq "image"} data-src="{$_oFile.link}"{else if $_oFile.file_type eq "doc"} href="https://docs.google.com/viewer?embedded=true&url={$DOMAIN_URL}{$_oFile.link}" data-type="iframe" {else if $_oFile.file_type eq "excel"} href="https://view.officeapps.live.com/op/view.aspx?src={$smarty.const.FH_URL}{$_oFile.link}." data-type="iframe" {else}href="{$_oFile.link}" data-type="iframe" {/if}><img src="{$_oFile.link}" alt=""></div>
									{/foreach}
								{else}
									{foreach from=$file_doc item=_oFile}
										<div class="item_file d-none" data-fancybox="gallery_suggest_{$_oItem.doc_id}" {if $_oFile.file_type eq "image"} data-src="{$_oFile.link}"{else if $_oFile.file_type eq "doc"} href="{$_oFile.link}" data-type="iframe" {else if $_oFile.file_type eq "excel"} href="{$_oFile.link}" data-type="iframe" {else}href="{$_oFile.link}" data-type="iframe" {/if}><img src="{$_oFile.link}" alt=""></div>
									{/foreach}
								{/if}
							</div>
						{/if}
					</div>
			{/foreach}
		</div>
	{/if}
{else}
	<div class="p-2 text-center">
		<img src="{$URL_IMAGES}/listing-empty.svg" width="100" height="100">
		<p>Không có kết quả nào phù hợp</p>
	</div>
{/if}