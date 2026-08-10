{if $_ss_view_docs eq "grid"}	

    <div class="form-row row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-lg-5">

		{if !empty($lstDocs)}

		{foreach from=$lstDocs item=_oItem}

			{assign var=file_doc value=$_oItem.file_doc}

				<div class="col box_item_doc item_doc_{$_oItem.doc_id} mb-2">

					<div class="item_doc p-2 bg-lighter rounded-3 {if !empty($file_doc)}cursor-pointer{else}cursor-no-drop{/if}" onDblClick="$Core.docs.view_doc(this,event)" data-view="{$_ss_view_docs}"  title="{$_oItem.title}">

						<div class="d-flex justify-content-between align-items-center gap-2 p-2 text-dark">

							<div class="title_doc mb-0 fs-16 d-flex align-items-center gap-1">

								{if !empty($_oItem.is_important) }<i class='bx bxs-star text-warning align-bottom' data-bs-toggle="tooltip" data-bs-placement="top" title="Quan trọng" ></i>{/if} 

								<span class="limit_1line">{$_oItem.title}</span>

								{if !empty($_oItem.content)}

									<span class="text-primary" data-url="/index.php?mod=document&act=load_intro&id={$_oItem.doc_id}&table=Docs" data-toggle="webui-popover" data-trigger="hover" data-width="350" data-placement="top"><i class='bx bx-info-circle' ></i></span>

								{/if}

							</div>

							<div class="dropdown" data-bs-toggle="tooltip" data-bs-placement="top" title="Thao tác">

								<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i></button>

								<div class="dropdown-menu w-px-100 fs-14">

									<a class="dropdown-item pin px-2 fs-14 document_{$_oItem.doc_id} {if !empty($_oItem.pinned)}active{/if}" onclick="$Core.docs.pin_doc(this,event)" doc_id="{$_oItem.doc_id}" status="{$_oItem.pinned}" href="javascript:void(0);"> {if empty($_oItem.pinned)}Ghim{else}Bỏ ghim{/if}</a>

									{if !empty($file_doc)}

									<a class="dropdown-item px-2 fs-14" onclick="$Core.docs.view_doc(this,event)" data-view="{$_ss_view_docs}" doc_id="{$_oItem.doc_id}" href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>

									{/if}

									<a class="dropdown-item px-2 fs-14" onclick="$Core.docs.view_doc_detail(this,event)" doc_id="{$_oItem.doc_id}" href="javascript:void(0);" title="Thông tin chi tiết văn bản"><i class="bx bx-info-circle me-1"></i> Chi tiết</a>

									{if $profile_id eq $_oItem.user_id}

									<a class="dropdown-item px-2 fs-14" onclick="$Core.docs.open_doc(this,event)" data-cat_id='{$_oItem.cat_id}' data-doc_id="{$_oItem.doc_id}" href="javascript:void(0);"><i class='bx bx-edit-alt me-1'></i> Sửa</a>

									<a class="dropdown-item px-2 fs-14" href="javascript:void(0);" onclick="$Core.docs.delete_doc(this,event)" doc_id="{$_oItem.doc_id}"><i class="bx bx-trash me-1"></i> Xóa</a>

									{/if}

								</div>

							</div>

						</div>

						<div class="px-4 py-5 d-flex justify-content-center bg-white rounded-1">

							<img src="{$_oItem.icon}" alt="" class="" width="60">

						</div>

						<div class="d-flex justify-content-start align-items-start pt-3 pb-2">

							<div class="avatar avatar-xxs me-1">

						  		<img src="{$clsProfile->getAvatar($_oItem.user_id)}" alt="Avatar" class="rounded-circle">

							</div>

							<div class="fs-13 info_bottom">

								<span class="" data-bs-toggle="tooltip" data-bs-placement="top" title="Người tạo">{$clsProfile->getFullName($_oItem.user_id)}</span>

								<span class="time" data-bs-toggle="tooltip" data-bs-placement="top" title="Ngày tạo">{$_oItem.time}</span>

							</div>

						</div>

						{if !empty($file_doc)}

							<div class="lst_image">

								{if $_oItem.type eq 'file'}

									{foreach from=$file_doc item=_oFile}

										<div class="item_file d-none" data-fancybox="gallery_{$_oItem.doc_id}" {if $_oFile.file_type eq "image"} data-src="{$_oFile.link}"{else if $_oFile.file_type eq "doc"} href="https://docs.google.com/viewer?embedded=true&url={$DOMAIN_URL}{$_oFile.link}" data-type="iframe" {else if $_oFile.file_type eq "excel"} href="https://view.officeapps.live.com/op/view.aspx?src={$smarty.const.FH_URL}{$_oFile.link}." data-type="iframe" {else}href="{$_oFile.link}" data-type="iframe" {/if} data-caption="{$_oFile.name}" ><img src="{$_oFile.link}" alt=""></div>

									{/foreach}

								{else}

									{foreach from=$file_doc item=_oFile}

										<div class="item_file d-none" data-fancybox="gallery_{$_oItem.doc_id}" {if $_oFile.file_type eq "image"} data-src="{$_oFile.link}"{else if $_oFile.file_type eq "doc"} href="{$_oFile.link}" data-type="iframe" {else if $_oFile.file_type eq "excel"} href="{$_oFile.link}" data-type="iframe" {else}href="{$_oFile.link}" data-type="iframe" {/if} data-caption="{$_oFile.name}" ><img src="{$_oFile.link}" alt=""></div>

									{/foreach}

								{/if}

								

							</div>

						{/if}

					</div>

				</div>

		{/foreach}

		{else}

			<div class="p-2 text-center d-flex justify-content-center flex-column align-items-center w-100">

				<img src="{$URL_IMAGES}/listing-empty.svg" width="200" height="200">

				<p>Không có văn bản tài liệu nào</p>

			</div>

		{/if}

	</div>

{else}

	<div class="{if $deviceType eq 'phone'}table-container overflow-x-auto{/if} no-shadow lst_doc">

		<table cellpadding="0" cellspacing="0" class="table mb-0 text-dark" width="100%">

			<thead><tr>

				<th class="align-center h-px-35 text-left">Tiêu đề</th>

				<th class="align-center h-px-35 text-left">Nội dung</th>

				<th class="align-center h-px-35 text-left">Thời gian</th>

				<th class="align-center h-px-35 text-left">Người tạo</th>

				<th class="align-center w-px-50 text-left"></th>

			</tr></thead>

			<tbody class="holder_reports_activity_log">

				{if !empty($lstDocs)}

				{foreach from=$lstDocs item=_oItem}

					{assign var=file_doc value=$_oItem.file_doc}

					<tr onDblClick="$Core.docs.view_doc(this,event)" data-view="view_tr" class="item_doc item_doc_{$_oItem.doc_id} {if !empty($file_doc)}cursor-pointer{else}cursor-no-drop{/if}">

						<td>{if !empty($_oItem.is_important) }<i class='bx bxs-star text-warning align-top' title="Quan trọng" ></i>{/if} {$_oItem.title}</td>

						<td>{$_oItem.content}</td>

						<td>{$_oItem.time}</td>

						<td>{$clsProfile->getFullName($_oItem.user_id)}</td>

						<td class="text-center">

							<div class="dropdown dropstart">

								<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i></button>

								<div class="dropdown-menu w-px-100">

									<a class="dropdown-item px-2 fs-14 pin document_{$_oItem.doc_id} {if !empty($_oItem.pinned)}active{/if}" onclick="$Core.docs.pin_doc(this,event)" doc_id="{$_oItem.doc_id}" status="{$_oItem.pinned}" href="javascript:void(0);"> {if empty($_oItem.pinned)}Ghim{else}Bỏ ghim{/if}</a>

									{if !empty($file_doc)}

									<a class="dropdown-item px-2 fs-14" onclick="$Core.docs.view_doc(this,event)"  data-view="view_td" doc_id="{$_oItem.doc_id}" href="javascript:void(0);"><i class="bx bx-bullseye me-1"></i> Xem</a>

									{/if}

									<a class="dropdown-item px-2 fs-14" onclick="$Core.docs.view_doc_detail(this,event)" doc_id="{$_oItem.doc_id}" href="javascript:void(0);" title="Thông tin chi tiết văn bản"><i class="bx bx-info-circle me-1"></i> Chi tiết</a>

									{if $profile_id eq $_oItem.user_id}

										<a class="dropdown-item px-2 fs-14" onclick="$Core.docs.open_doc(this,event)" data-cat_id='{$_oItem.cat_id}' data-doc_id="{$_oItem.doc_id}" href="javascript:void(0);"><i class='bx bx-edit-alt me-1'></i> Sửa</a>

										<a class="dropdown-item px-2 fs-14" href="javascript:void(0);" onclick="$Core.docs.delete_doc(this,event)" doc_id="{$_oItem.doc_id}"><i class="bx bx-trash me-1"></i> Xóa</a>

									{/if}

								</div>

							</div>

							{if !empty($file_doc)}

								<div class="lst_image">

									{if $_oItem.type eq 'file'}

										{foreach from=$file_doc item=_oFile}

											<div class="item_file d-none" data-fancybox="gallery_{$_oItem.doc_id}" {if $_oFile.file_type eq "image"} data-src="{$_oFile.link}"{else if $_oFile.file_type eq "doc"} href="https://docs.google.com/viewer?url={$DOMAIN_URL}{$_oFile.link}&embedded=true" data-type="iframe" {else if $_oFile.file_type eq "excel"} href="https://view.officeapps.live.com/op/view.aspx?src={$smarty.const.FH_URL}{$_oFile.link}." data-type="iframe" {else}href="{$_oFile.link}" data-type="iframe" {/if} data-caption="{$_oFile.name}"><img src="{$_oFile.link}" alt=""></div>

										{/foreach}

									{else}

										{foreach from=$file_doc item=_oFile}

											<div class="item_file d-none" data-fancybox="gallery_{$_oItem.doc_id}" {if $_oFile.file_type eq "image"} data-src="{$_oFile.link}"{else if $_oFile.file_type eq "doc"} href="https://docs.google.com/viewer?url={$DOMAIN_URL}{$_oFile.link}&embedded=true" data-type="iframe" {else if $_oFile.file_type eq "excel"} href="{$_oFile.link}" data-type="iframe" {else}href="{$_oFile.link}" data-type="iframe" {/if} data-caption="{$_oFile.name}"><img src="{$_oFile.link}" alt=""></div>

										{/foreach}

									{/if}



								</div>

							{/if}

						</td>

					</tr>

				{/foreach}

				{else}

					<tr>

						<td class="text-center" colspan="5">Không có văn bản tài liệu nào</td>

					</tr>

				{/if}

			</tbody>

		</table>

	</div>

{/if}