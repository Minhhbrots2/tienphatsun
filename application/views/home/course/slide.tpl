<script type="text/javascript" src="{$URL_JS}/heic2any.min.js?v={$upd_version}"></script>
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
			<button type="button" title="Thêm mới" onClick="$Core.slide.open(this, event)" slide_id="0" 
			class="btn btn-outline-primary mr-2">+ Thêm mới</button>
			<div class="btn-group mr-1">
				<button type="button" class="btn btn-outline-secondary dropdown-toggle" 
				data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
					{$clsISO->makeIcon('bx-search', 'Tìm...')}
				</button>
				<div class="dropdown-menu dropdown-menu-end w-px-350" data-popper-placement="bottom-end">
					<form class="mb-3" method="POST">
						<div class="p-4">
							<div class="form-floating w-100 mb-2">
								<input type="text" class="form-control search_field" data-field="keySearch" placeholder="{$core->get_Lang('Search')}" />
								<label for="{$uid}">Từ khóa</label>
							</div>
							<div class="form-floating w-100 mb-2">
								<select class="form-control w-100 form-select search_field" data-field="cat_id">
									{$clsISO->getSelectByPropertyTypeTitle('_LEARN_CAT',$cat_id,'Loại tài liệu')}
								</select>
								<label for="{$uid}">Loại đào tạo</label>
							</div>
							<div class="form-group mb-2">
								<label>Nhân viên</label>
								<select class="iso-selectizeNotSearch search_field" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" placeholder="Nhân viên" data-field="user_id" data-optgroup="false"></select>				
							</div>
							<div class="form-group mb-2">
								<input type="hidden" class="search_field" value="{$show}" data-field="show" />
								<input type="hidden" class="search_field" value="{$tag_id}" data-field="tag_id" />
								<button type="button" class="btn btn-success" onClick="$Core.slide.do_search(this,event)">
									<i class="bx bx-search"></i> Tìm kiếm
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="holder_slide">
		<div class="p-5 text-center">
			<img src="{$URL_IMAGES}/ripple-loading.svg" />
			<p class="text-center">Loading...</p>
		</div>
	</div>
</div>
{$scriptJs}
{literal}
<style type="text/css">
	.course-item-wrapper .course-body-wrapper{ min-height:230px;}
	.box-done-img .item {width: 148px; height: 110px;}
	.awe__post-item, .awe__post-comment{ padding:15px 0 0;}
</style>
<script type="text/javascript">
	$(function(){
		$Core.slide.list({});
	});
</script>
{/literal}