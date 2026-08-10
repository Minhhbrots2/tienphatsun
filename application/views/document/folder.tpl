<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex align-items-start justify-content-between mb-2">
		<div class="yvBvmnviXh">
			<h4 class="fw-bold mb-1 {if $deviceType eq 'phone'}fs-6{/if}"><a class="text-black" href="{$clsISO->getLink('document')}">Văn bản hệ thống</a> / <span class="breadcrumd_name">{$oneCat.title}</span></h4>
		</div>
		{if $clsISO->checkPermission("add_doc") || 1==1}
		<div class="d-flex align-items-center gap-1">
			<button class="btn btn-primary text-nowrap" onClick="$Core.docs.open_doc(this,event)" type="button" data-cat_id='{$cat_id}' data-doc_id="0">Thêm mới</button>
		</div>
		{/if}
	</div>
    <div class="card no-shadow">
		<div class="card-header">
			{$core->getBlock("search_docs")}
		</div>
		<div class="card-body">			
			<div class="d-flex justify-content-between align-items-center mb-3 box_view">
				<span class=""></span>
				<div class="input-group w-auto">
					<button class="form-control btn btn-outline-default bg-white btn_left btn_view_docs btn-sm {if $_ss_view_docs eq 'list'}active{/if}" onClick="$Core.docs.setView(this,event)" data-type="list"><i class='bx bx-list-ul'></i></button>
					<button class="form-control btn btn-outline-default bg-white btn_right btn_view_docs btn-sm {if $_ss_view_docs eq 'grid'}active{/if}" onClick="$Core.docs.setView(this,event)" data-type="grid"><i class='bx bx-grid-alt' ></i></button>
				</div>
			</div>
			<div class="list_docs">

			</div>
		</div>
	</div>	
</div>
{$scriptJs}
<script>
	var string_doc = `{$string_doc}`;
</script>
{literal}
<script>
	$(function(){
		$Core.docs.load_docs({"string_doc":string_doc});
	})
</script>
{/literal}
