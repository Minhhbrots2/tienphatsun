
{if $_type eq "detail"}
	{assign var = _list_images value = $oneItem.list_images}
	{assign var = _more_information value = $oneItem.more_information}
	<div class="awe__doc-item position-relative">
		<a href="{$oneItem.link_download}" class="btn btn-default bg-white text-dark btn-sm p-1 position-absolute zindex-1" target="_blank" {if $deviceType ne 'phone'}style="top:15px;right: 15px"{else}style="top:8px;right: 8px"{/if}>
			{if $oneItem.type eq 'folder' || $oneItem.type eq 'youtu.be' || $oneItem.type eq 'docs.google.com' || $_more_information.file_type eq 'folder' || $oneItem.type eq 'user.fh'}
				<i class='bx bx-link-external'></i>
			{else}
				<i class="bx bx-download"></i>
			{/if}
		</a>
		<a href="javascript:void(0)" onClick="$Core.project.save_docs(this,event)" sheet_id="{$_more_information.gg_id}" docs_id="{$oneItem.id}" class="awe__doc-button awe__doc-save rounded-pill d-flex justify-content-center align-items-center d-none {if $clsISO->checkItemInArray($_more_information.gg_id,$arr_docs_save)} saved{/if}"><i class="fs-16 bx bx-heart bx-sm"></i></a>
		<a class="link awe__doc-link overflow-hidden rounded-3" data-preload="false" data-caption="{$oneItem.title}" 
		   {if $oneItem.type eq 'youtu.be'}
				data-fancybox="{$oneItem.id}" href="https://www.youtube.com/watch?v={$_more_information.youtu_id}"
		   {elseif $oneItem.type eq 'google.file'}
				data-fancybox="{$oneItem.id}"
			   {if !empty($_list_images)} 
					data-src="{$clsISO->genGoogleURL($_more_information.gg_id)}"
			   {else} 
					href="{$oneItem.link}"
			   {/if}
		   {elseif $oneItem.type eq 'video' || $oneItem.type eq 'pdf' || $oneItem.type eq 'other' || $oneItem.type eq 'docx' || $oneItem.type eq 'docs.google.com'} 
				data-fancybox="{$oneItem.id}" href="{$oneItem.link}" data-type="iframe"
		   {else}
				href="{$oneItem.content}" target="_blank"
		   {/if}>
			<div class="awe__doc-img position-relative">
				{if $oneItem.type eq 'video' || $oneItem.type eq 'youtu.be'}
				<div class="img-play"><i class="bx bx-play"></i></div>{/if}
				<div class="img-background" style="background-image:url('{$oneItem.image}'),url('{$URL_IMAGES}/no-image.png')"></div>
			</div>
			{if !empty($oneItem.image)}
				<img src="{$clsISO->getGoogleUrl($oneItem.image,150)}" alt="" class="d-none" loading="lazy">
			{/if}
			<h4 class="fs-13 line-clamp-2 text-center text-white lh-sm mb-0"><span class="limit_2line">{$oneItem.title}</span></h4>
		</a>
		{if !empty($_list_images)}
			{foreach from=$_list_images item = _oImage}
				<a class="d-none" data-fancybox="{$oneItem.id}"{if $_oImage.type eq 'video' || $_oImage.type eq 'pdf' || $oneItem.type eq 'other' || $oneItem.type eq 'docx' || $oneItem.type eq 'docs.google.com'} data-type="iframe"{/if} data-src="{$_oImage.image}" data-caption="{$oneItem.title}">
					<img src="{$clsISO->getGoogleUrl($_oImage.image,150)}" alt="" class="d-none" loading="lazy">
				</a>
			{/foreach}
		{/if}
	</div> 
{else}
	{assign var = _list_images value = $oneItem.list_images}
	{assign var = _more_information value = $oneItem.more_information}
	{assign var = gId value = $clsISO->getUniqid()}
	<div class="item_document position-relative rounded-3 mb-2 overflow-hidden border {if $deviceType ne 'phone'}p-3{else}p-2{/if} h-100">
		<div class="rounded-3 mb-2 overflow-hidden">
			<a class="link awe__doc-link overflow-hidden rounded-3" data-preload="false" 
			   {if $oneItem.type eq 'youtu.be'}
					data-fancybox href="https://www.youtube.com/watch?v={$_more_information.youtu_id}"
			   {elseif $oneItem.type eq 'google.file'}
					data-fancybox="{$gId}" {if !empty($_list_images)} data-src="{$oneItem.link}"{else} href="{$oneItem.link}"{/if}
			   {elseif $oneItem.type eq 'video' || $oneItem.type eq 'pdf' || $oneItem.type eq 'other' || $oneItem.type eq 'docx' || $oneItem.type eq 'docs.google.com'} 
					data-fancybox="{$result_id}" href="{$oneItem.link}" data-type="iframe"
			   {else}
					href="{$oneItem.link}" target="_blank"
			   {/if}>
				<div class="awe__doc-img position-relative">
					<div class="img-background" style="background-image:url('{$oneItem.image}'),url({$URL_IMAGES}/no-image.png)"></div>
					{if $oneItem.type eq 'video' || $oneItem.type eq 'youtu.be'}
					<div class="img-play"><i class="bx bx-play"></i></div>
					{/if}
				</div>
				<img src="{$clsISO->getGoogleUrl($oneItem.image,150)}" width="150" alt="" class="d-none" loading="lazy">
			</a>
			{if !empty($_list_images)}
				{foreach from=$_list_images item=_link}
					<a class="d-none" data-preload="true" 
					   {if $_link.type eq 'youtu.be'}
							data-fancybox href="https://www.youtube.com/watch?v={$_more_information.youtu_id}"
					   {elseif $_link.type eq 'google.file'}
							data-fancybox="{$gId}" {if !empty($_list_images)} data-src="{$_link.image}"{else} href="{$_link.image}"{/if}
					   {elseif $_link.type eq 'video' || $_link.type eq 'pdf' || $_link.type eq 'other' || $_link.type eq 'docx' || $_link.type eq 'docs.google.com'} 
							data-fancybox="{$result_id}" href="{$_link.image}" data-type="iframe"
					   {else}
							href="{$_link.image}" target="_blank"
					   {/if}>
						<img src="{$clsISO->getGoogleUrl($_link.image,150)}" width="150" alt="" class="d-none" loading="lazy">
					</a>
				{/foreach}
			{/if}
		</div>
		<h4 class="fs-6 lh-sm mb-0 text-dark limit_2line" title="{$_oResult.title}">{$core->replaceString($_oResult.title,$keyword)}</h4>
		<a href="{$_oResult.link_download}" class="btn btn-default bg-white text-dark btn-sm p-1 position-absolute zindex-1" target="_blank" {if $deviceType ne 'phone'}style="top:15px;right: 15px"{else}style="top:8px;right: 8px"{/if}>
		{if $_more_information.file_type eq 'folder' || $oneItem.type eq 'youtu.be' || $oneItem.type eq 'docs.google.com' || $_more_information.file_type eq 'user.fh'}
			<i class='bx bx-link-external'></i>
		{else}
			<i class="bx bx-download"></i>
		{/if}
		</a>
		{if !empty($_oResult.html_info)}<div class="fs-12 my-2">{$_oResult.html_info}</div>{/if}
		{if !empty($_oResult.list_tags)}
			<div class="tags">
				{foreach from=$_oResult.list_tags key=key item=tag}
					<a href="/tim-kiem/{$tag}.html" class="tag">{$core->replaceString($tag,$keyword)}</a>
				{/foreach}
			</div>
		{/if}
	</div>
{/if}