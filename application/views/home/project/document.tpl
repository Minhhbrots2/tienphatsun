<script type="text/javascript" src="{$URL_JS}/heic2any.min.js?v={$upd_version}"></script>
<style>
	.tag_document .tag_document_item {
	    font-size: 11px;
		display: inline-block;
		border-radius: 3px;
		padding: .2em .5em .2em;
		border-radius: 2px;
		background: var(--tag-bg);
		color: var(--text-color);
		margin: .25em .1em;
		background: #ededed;
	}
	.img-square-wrapper {
	  width: 100%; 
	  aspect-ratio: 1 / 1;
	  overflow: hidden;
	  border-radius: 8px;
	  display: flex;
	  justify-content: center;
	  align-items: center;
	}

	.img-square-wrapper img {
	  width: 100%;
	  height: 100%;
	  object-fit: contain;
	  display: block;
	}
	
	.toggle-icon {
        cursor: pointer;
        {* margin-left: 5px; *}
        user-select: none;
    }
    .submenu {
        display: block;
        padding-left: 20px;
    }

	.img-square-wrapper {
		position: relative;
		display: inline-block;
		overflow: hidden;
		border-radius: 8px;
	}

	.image_background {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		object-fit: cover !important;
		filter: blur(8px);
		opacity: 0.3;
		z-index: 0;
	}

	.item_document-image {
		position: relative;
		z-index: 1;
	}
	.box-document .category_link,
	.box-document .document_link {
		color: #697a8d;
	}
	.content_document .title {
		 font-size: clamp(15px, 1vw + 0.5rem, 17px);
		 height: 48px; /* ví dụ 48px, bạn chỉnh theo ý muốn */
		  overflow: hidden; /* ẩn phần text tràn */
		  display: -webkit-box;
		  -webkit-line-clamp: 2; /* giới hạn 2 dòng */
		  -webkit-box-orient: vertical;
		  line-height: 24px; /* căn chỉnh theo height chia dòng */
	}
	.item_document {
		padding: 15px 10px 20px 10px;
	}
	.item_document .download-icon {
		bottom: 7px; 
		right: 7px; 
		color: #697a8d;
	}
	.category_item.active > span,
	.category_item.active > a {
		color: #696cff;
		font-weight: bold;
	}
	.tag_document .tag_document_item.active {
		color: #fff;
		background: #696cff;
	}
	@media screen and (max-width:768px){
		.item_document {
			padding: 0.5rem !important;
		}
		
		.img-square-wrapper {
			aspect-ratio: 1.5 / 1;
		}
	}
	
</style>
<div class="container-xxl flex-grow-1 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-3">
		<div class="p__left">
			<h4 class="fw-bold mb-2">Kho tài liệu</span></h4>
			<nav aria-label="breadcrumb" style="margin-top:-2px">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="{$PCMS_URL}">Trang chủ</a></li>
					<li class="breadcrumb-item"><a href="/hoc-tap.html">Kho tài liệu</a></li>
					{if $show eq 'tag'}
					<li class="breadcrumb-item active">Tag: {$clsTag->getTitle($tag_id)}</li>
					{/if}
				</ol>
			</nav>
		</div>
		<div class="right__buttons d-flex align-items-center">
			<form method="POST">
				<div class="search d-flex flex-wrap align-items-center gap-1">
					<div class="input-group input-group-merge w-px-200">
						<span class="input-group-text" id="keysearch"><i class="icon-base bx bx-search"></i></span>
						<input type="text" class="form-control search_field" name="keyword" placeholder="Tìm kiếm" aria-label="Tìm kiếm" data-field="keyword" aria-describedby="keysearch">
					</div>
					{*<div class="input-group w-auto">
						<select data-field="type" class="form-control js__search-department-field search_field w-px-100 form-select search_field" >
							<option value="">Loại file</option>
						</select>
					</div> *}
					<div class="input-group w-auto">
						<select data-field="project_id" class="form-control js__search-department-field search_field w-px-100 w-100 form-select search_field">
							<option value="">Dự án</option>
							{foreach from = $list_projects item=item_project key=key_item_project}
							<option value="{$item_project.project_id}">{$item_project.title}</option>
							{/foreach}
						</select>
					</div>
					<button class="btn btn-outline-primary" type="submit" onClick="$Core.document.clickform(this, event);">Tìm kiếm</button>
				</div>
			</form>			
		</div>
	</div>
	{* {if $profile_id ne "1107"}
	<div class="row">
		<div class="col-12 col-md-8">
			<div class="form-row">
			{foreach name=i from=$list_document item = _oDocument}
				<div class="col-12 col-md-2 col-lg-3 col-xxl-4">
					<div class="item_document d-flex">
						<img src="{$_oDocument.image}" alt="" width="30" height="30">
						<div class="content_document">
							<h3 class="title">{$_oDocument.title}</h3>
							<p class="">{$clsProjectMeta->getHTMLTag($_oslide.slide_id,$_oDocument)}</p>
						</div>
					</div>
				</div>
			{/foreach}
			</div>
		</div>
		<div class="col-12 col-md-4">
			
		</div>
	</div>
	{else} *}
		<div class="row box-document ">
			<div class="col-12 col-md-3 col-lg-3 col-xl-3 col-xxl-2 col-sm-12">
				<div class="card no-shadow mb-2">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="card-title m-0 me-2">Danh mục</h5>
						<button class="btn btn-sm" 
								data-bs-toggle="collapse" 
								data-bs-target="#categoryMenu"
								aria-expanded="true" 
								aria-controls="categoryMenu">
						  <i class="bx bx-chevron-up toggle-card"></i>
						</button>
					</div>
					<div id="categoryMenu" class="card-body collapse show">
						{$htmlCategory|default:""}
					</div>
				</div>
				<div class="card no-shadow mb-2">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="card-title m-0 me-2">Tags</h5>
					</div>
					<div class="card-body tag_document">
						{* {foreach name=i from=$all_tags item = tags_item key = slug_tag}
							<a class="tag_document_item" href="/kho-tai-lieu/{$slug_tag}.html">{$tags_item}</a>
						{/foreach} *}
						<a class="tag_document_item tag_document_item_masterise-da-nang {if $clsISO->checkItemInArray('masterise-da-nang',$arrTag)} active {/if}" data-tag="{$tag_id}" href="javascript:void(0);" onClick="$Core.document.clickTag(this, event);" data-tag-id="masterise-da-nang">Masterise Đà Nẵng</a>
						<a class="tag_document_item tag_document_item_nha-mau {if $clsISO->checkItemInArray('nha-mau',$arrTag)} active {/if}" data-tag="{$tag_id}" href="javascript:void(0);" onClick="$Core.document.clickTag(this, event);" data-tag-id="nha-mau">Nhà mẫu</a>
						<a class="tag_document_item tag_document_item_masteri-trinity-square {if $clsISO->checkItemInArray('masteri-trinity-square',$arrTag)} active {/if}" href="javascript:void(0);" onClick="$Core.document.clickTag(this, event);" data-tag-id="masteri-trinity-square">Masteri Trinity Square</a>
						<a class="tag_document_item tag_document_item_lumiere-prime-hills {if $clsISO->checkItemInArray('lumiere-prime-hills',$arrTag)} active {/if}" href="javascript:void(0);" onClick="$Core.document.clickTag(this, event);" data-tag-id="lumiere-prime-hills">Lumière Prime Hills</a>
						<a class="tag_document_item tag_document_item_masteri-lakeside {if $clsISO->checkItemInArray('masteri-lakeside',$arrTag)} active {/if}" href="javascript:void(0);" onClick="$Core.document.clickTag(this, event);" data-tag-id="masteri-lakeside">Masteri LakeSide</a>
						<a class="tag_document_item tag_document_item_du-an-sun-feliza-suites {if $clsISO->checkItemInArray('du-an-sun-feliza-suites',$arrTag)} active {/if}" href="javascript:void(0);" onClick="$Core.document.clickTag(this, event);" data-tag-id="du-an-sun-feliza-suites">Dự án Sun Feliza Suites</a>
						<a class="tag_document_item tag_document_item_lumiere-springbay {if $clsISO->checkItemInArray('lumiere-springbay',$arrTag)} active {/if}" href="javascript:void(0);" onClick="$Core.document.clickTag(this, event);" data-tag-id="lumiere-springbay">Lumiere SpringBay</a>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-9 col-lg-9 col-xl-9 col-xxl-10 col-sm-12 " >
				<div class="card no-shadow mb-2 h-100" >
					<div class="card-body holder_slide h-100">
					</div>
					<div id="pager_slide" class="simple-pagination"></div>
				</div>
			</div>
		</div>
	{* {/if} *}
</div>
<input type="hidden" name="current_page" value="{$current_page}">
<input type="hidden" name="query_use" id="query_use" value="{$query_use|@json_encode|escape:'htmlall'}">
{$scriptJs}
{literal}
<style type="text/css">
	.course-item-wrapper .course-body-wrapper{ min-height:230px;}
	.box-done-img .item {width: 148px; height: 110px;}
	.awe__post-item, .awe__post-comment{ padding:15px 0 0;}
</style>
{/literal}
<script>
$(document).ready(function(){
	let query_use = $('#query_use').val();
	query_use = JSON.parse(query_use);
	$Core.document.list(query_use);
	$('#categoryMenu').on('show.bs.collapse', function () {
        $('.toggle-card').removeClass('bx-chevron-down').addClass('bx-chevron-up');
    });

    $('#categoryMenu').on('hide.bs.collapse', function () {
        $('.toggle-card').removeClass('bx-chevron-up').addClass('bx-chevron-down');
    });
	if ($(window).width() <= 1024) {
		if ($('#categoryMenu').hasClass('show')) {
			$('#categoryMenu').css('transition', 'none');
			$('#categoryMenu').removeClass('show');
			$('.toggle-card').removeClass('bx-chevron-up').addClass('bx-chevron-down');
			$('#categoryMenu').collapse('hide');
			$('#categoryMenu').css('transition', '');
		}
	}
});
</script>