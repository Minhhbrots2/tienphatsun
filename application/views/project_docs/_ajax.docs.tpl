{if !empty($list_docs)}
<div class="form-row row-cols-2 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-5 gy-4">
	{foreach name=i from=$list_docs item = _oDocument}
        {assign var = _list_images value = $_oDocument.list_images}
        {assign var = _more_information value = $_oDocument.more_information}
		<div class="col mb-2">
			<div class="item_document d-flex flex-column h-100 position-relative">
				{if !empty($_list_images)}
					{foreach from=$_list_images item=item key=key name=name}
						{if $smarty.foreach.name.first}
							<a data-preload="true" class="img-square-wrapper cls-curso-point" target="_blank"
							{if $item.type eq 'youtu.be'}
								data-fancybox="{$_oDocument.id}" href="{$item.image}"
							{elseif $item.type eq 'google.file'}
								data-fancybox="{$_oDocument.id}"
								{if !empty($_list_images)} 
									data-src="{$clsISO->genGoogleURL($_more_information.gg_id)}"
								{else} 
									href="{$item.image}"
								{/if}
							{elseif $item.type eq 'video' || $item.type eq 'pdf' || $item.type eq 'other' || $item.type eq 'docx' || $item.type eq 'docs.google.com'} 
								data-fancybox="{$_oDocument.id}" href="{$item.image}" data-type="iframe"
							{else}
								href="{if $_oDocument.content|@strpos:"http" === false}{FH_URL} {/if}{$_oDocument.content}" target="_blank"
							{/if}>
								<img class="image_background" src="{$_oDocument.image}">
								<img src="{$_oDocument.image}" alt="" class="item_document-image">
								{if $item.type eq 'youtu.be' or $item.type eq 'video'}
									<div class="img-play"><i class="bx bx-play"></i></div>
								{/if}
								<button data-toggle="ripple" class="btn d-flex alig-item-center justify-content-center file-favarite {if $_oDocument.is_save eq '1'} saved {/if}" onClick="$Core.document.bookmarked(this, event);" doc_id="{$_oDocument.id}">
									<i class="bx {if $_oDocument.is_save eq '1'}bxs{else}bx{/if}-heart icon-favarite"></i>
								</button>
								{if !empty($_oDocument.link_download) and $_list_images|@count <= 1 and $_oDocument.type neq 'youtu.be' and $_oDocument.content|@strpos:"drive.google.com" !== false } 
									<a href="{$_oDocument.link_download|default:"#"}" download class="file-icon d-flex justify-content-center align-items-center" title="Download tài liệu {$_oDocument.title}">
										<i class='bx bxs-download'></i>
									</a>
								{elseif !empty($_oDocument.content) and $_oDocument.content|@strpos:"drive.google.com" === false}
									<a href="{$_oDocument.content|default:"#"}" target="_blank" class="file-icon d-flex justify-content-center align-items-center" title="Truy cập tài liệu {$_oDocument.title}">
										<i class='bx bx-link-external' ></i>
									</a>
								{elseif !empty($_oDocument.content) and $item.type neq 'youtu.be'}
									<a href="{$_oDocument.content|default:"#"}" target="_blank" class="file-icon d-flex justify-content-center align-items-center" title="Truy cập tài liệu {$_oDocument.title}">
										<i class='bx bxs-folder-open'></i>
									</a>
								{/if}
							</a>
						{else}
						<a class="d-none" data-fancybox="{$_oDocument.id}"{if $item.type eq 'video' || $item.type eq 'pdf' || $_oDoc.type eq 'other' || $_oDoc.type eq 'docx' || $_oDoc.type eq 'docs.google.com'} data-type="iframe"{/if} data-src="{$item.image}" data-caption="{$_oDoc.title}"></a>
						{/if}
					{/foreach}
				{else}
                    <a data-preload="true" class="img-square-wrapper cls-curso-point" target="_blank"
                    {if $_oDocument.type eq 'youtu.be'}
                        data-fancybox="{$_oDocument.id}" href="https://www.youtube.com/watch?v={$_more_information.youtu_id}"
                    {elseif $_oDocument.type eq 'google.file'}
                        data-fancybox="{$_oDocument.id}"
                        {if !empty($_list_images)} 
                            data-src="{$clsISO->genGoogleURL($_more_information.gg_id)}"
                        {else} 
                            data-type="iframe"
                            href="{$_oDocument.link|trim}"
                        {/if}
                    {elseif $_oDocument.type eq 'video' || $_oDocument.type eq 'pdf' || $_oDocument.type eq 'other' || $_oDocument.type eq 'docx' || $_oDocument.type eq 'docs.google.com'} 
                        data-fancybox="{$_oDocument.id}" href="{$_oDocument.link|trim}" data-type="iframe"
                    {else}
                        {if $_oDocument.content|@strpos:"drive.google.com" === false}
                            data-type="iframe"
                        {/if}
                        data-fancybox="{$_oDocument.id}" href="{if $_oDocument.content|@strpos:"http" === false}{FH_URL} {/if}{$_oDocument.content|trim}" target="_blank"
                    {/if}>
                        <img class="image_background" src="{$_oDocument.image|trim}" data-src="{$_oDocument.image|trim}">
                        <img src="{$_oDocument.image|trim}" data-src="{$_oDocument.image|trim}" alt="" class="item_document-image">
                        {if $_oDocument.type eq 'youtu.be' or $_oDocument.type eq 'video'}
                            <div class="img-play"><i class="bx bx-play"></i></div>
                        {/if}
                        <button data-toggle="ripple" class="btn d-flex alig-item-center justify-content-center file-favarite {if $_oDocument.is_save eq '1'} saved {/if}" onClick="$Core.document.bookmarked(this, event);" doc_id="{$_oDocument.id}">
                            <i class="bx {if $_oDocument.is_save eq '1'}bxs{else}bx{/if}-heart icon-favarite"></i>
                        </button>
                        {if !empty($_oDocument.link_download) and $_list_images|@count <= 1 and $_oDocument.type neq 'youtu.be' and $_oDocument.content|@strpos:"drive.google.com" !== false} 
                            <a href="{$_oDocument.link_download|default:"#"}" download class="file-icon d-flex justify-content-center align-items-center link-download-document" title="Download tài liệu {$_oDocument.title}">
                               <i class='bx bxs-download'></i>
                            </a>
                        {elseif !empty($_oDocument.content) and $_oDocument.content|@strpos:"drive.google.com" === false}
                            <a href="{$_oDocument.content|default:"#"}" target="_blank" class="file-icon d-flex justify-content-center align-items-center" title="Truy cập tài liệu {$_oDocument.title}">
                               <i class='bx bx-link-external' ></i>
                            </a>
                        {elseif !empty($_oDocument.content) and $_oDocument.type neq 'youtu.be'}
                            <a href="{$_oDocument.content|default:"#"}" target="_blank" class="file-icon d-flex justify-content-center align-items-center" title="Truy cập tài liệu {$_oDocument.title}">
                               <i class='bx bxs-folder-open'></i>
                            </a>
                        {/if}
                    </a>
                {/if}
				<div class="content_document mt-3">
					<h4 class="title fs-6 mb-1 fw-bold cls-curso-point" title="{$_oDocument.title}">{$_oDocument.title}</h4>
					<div class="d-flex align-items-center gap-1">
						<span class="text-fs-12">{$clsISO->formatDate($_oDocument.upd_date,'7')} </span> 
						<span class="text-fs-12">{$_oDocument.cat_name}</span>
					</div>
					<div class="my-1 d-flex align-items-center">
						<i class='bx bx-building-house' ></i>
						<span>{$list_project_by_key_id[$_oDocument.project_id]['title']}</span></a><br>
						<span class="{$_oslide}"></span>
					</div>
                    {$clsProjectMeta->getHTMLTag($_oslide.slide_id,$_oDocument, true)}
				</div>
			</div>
		</div>
	{/foreach}
</div>
{else}
	<div class="d-flex justify-content-center h-100">
		<p class="d-flex text-center align-items-center fs-5">Không có dữ liệu</p>
	</div>
{/if}