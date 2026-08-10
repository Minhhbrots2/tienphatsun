{if !empty($list_document)}
<div class="form-row gy-4">
	{foreach name=i from=$list_document item = _oDocument}
		<div class="col-6 col-md-6 col-lg-4 col-xl-4 col-xxl-3 mt-2">
			<div class="item_document d-flex flex-column h-100 position-relative" style="border: 1px solid #d9dee3;border-radius: 8px;">
				
				<a href="{$_oDocument.content|default:"#"}" class="img-square-wrapper" target="_blank">
					<img class="image_background" src="{$_oDocument.image}">
					<img src="{$_oDocument.image}" alt="" class="item_document-image" style="border-radius: 8px;">
				</a>
				
				<div class="content_document mt-3">
					<h4 class="title" title="{$_oDocument.title}"><a href="{$_oDocument.content|default:"#"}" class="document_link lh-base" target="_blank">{$_oDocument.title}</a></h4>
					{* <b>Danh mục:</b> <span>{$_oDocument.cat_name}</span><br>
					<b>Số file:</b> <span>{$_oDocument.list_images|@count}</span><br>
					<b>Thời gian tạo:</b> <span>{$clsISO->formatDate($_oDocument.reg_date, '4')}</span><br>
					<b>Thời gian cập nhật:</b> <span>{$clsISO->formatDate($_oDocument.upd_date, '4')}</span><br>*}
					<span class="{$_oslide}">{$clsProjectMeta->getHTMLTag($_oslide.slide_id,$_oDocument)}</span>
				</div>
				{* {if !empty($_oDocument.link_download)} 
				 <a href="{$_oDocument.link_download|default:"#"}" download
				   class="download-icon position-absolute" title="Download tài liệu {$_oDocument.title}">
				   <i class='bx bx-download fs-4'></i>
				</a>
				{/if} *}
			</div>
		</div>
	{/foreach}
</div>
{else}
	<div class="d-flex justify-content-center h-100">
		<p class="d-flex text-center align-items-center fs-5">Không có dữ liệu</p>
	</div>
{/if}