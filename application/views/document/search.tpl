<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex align-items-start justify-content-between mb-2">
		<div class="yvBvmnviXh">
			<h4 class="fw-bold mb-1 {if $deviceType eq 'phone'}fs-5{/if}"><a class="text-black" href="{$clsISO->getLink('docs')}">Văn bản hệ thống</a> / <span>{$oneCat.title}</span></h4>
		</div>
		<div class="d-flex align-items-center gap-1">
			<button class="btn btn-primary" onClick="$Core.docs.open_doc(this,event)" type="button" data-cat_id='{$cat_id}' data-doc_id="0">Thêm mới</button>
		</div>
	</div>
    <div class="card no-shadow">
		<div class="card-header">
			<form action="" method="POST" id="form_search">
				<div class="form_search">
					<div class="input-group input-group-merge rounded-pill mb-3">
						<span class="input-group-text" id="basic-addon-search31"><i class="icon-base bx bx-search"></i></span>
						<input type="text" class="form-control form-control-lg input_search search_field" placeholder="Tìm kiếm văn bản" name="keyword" data-field="keyword"  aria-label="Tìm kiếm văn bản" aria-describedby="basic-addon-search31" value="{$keyword}">
					</div>
					<div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
						<div class="w-px-150">
							<select class="form-select border-0 rounded-pill bg-lighter text-dark w-100 search_field" data-width="100%" placeholder="Danh mục" name="cat_id" data-field="cat_id" onChange="$Core.docs.search_doc(this,event)">
								<option value="">Chọn danh mục</option>
								{if !empty($lstCategory_doc)}
									{foreach from=$lstCategory_doc item=item}
										<option value="{$item.property_id}" {if $cat_id eq $item.property_id}selected{/if}>{$item.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						<div class="w-w-auto">
							<select class="form-select border-0 rounded-pill bg-lighter text-dark w-100 search_field" data-width="100%" placeholder="Người ban hành" name="authorized_person" data-field="authorized_person" onChange="$Core.docs.search_doc(this,event)">
								<option value="">Chọn người ban hành</option>
								{if !empty($list_staffs)}
									{foreach from=$list_staffs item=item}
										<option value="{$item.profile_id}" {if $authorized_person eq $item.property_id}selected{/if}>{$item.full_name}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						<input class="form-control w-px-150 rounded-pill border-0 bg-lighter text-dark search_field" type="date" name="effective_date" data-field="effective_date" value="{$effective_date}" placeholder="" onChange="$Core.docs.search_doc(this,event)">
					</div>
				</div>
				<input type="hidden" name="submit" value="search">
			</form>
		</div>
		<div class="card-body">			
			<div class="d-flex justify-content-between align-items-center box_view">
				<span class=""></span>
				<div class="input-group w-auto">
					<button class="form-control btn btn-outline-default w-px-75 bg-white btn_left btn_view_docs btn-sm {if $_ss_view_docs eq 'list'}active{/if}" onClick="$Core.docs.setView(this,event)" data-type="list"><i class='bx bx-list-ul'></i></button>
					<button class="form-control btn btn-outline-default w-px-75 bg-white btn_right btn_view_docs btn-sm {if $_ss_view_docs eq 'grid'}active{/if}" onClick="$Core.docs.setView(this,event)" data-type="grid"><i class='bx bx-grid-alt' ></i></button>
				</div>
			</div>
			<div class="list_docs">

			</div>
		</div>
	</div>	
</div>
{literal}
<script>
	$(function(){
		$Core.docs.load_docs({});
	})
</script>
{/literal}
