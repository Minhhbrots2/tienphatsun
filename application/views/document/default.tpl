<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex align-items-center justify-content-between mb-2">
		<div class="yvBvmnviXh">
			<h4 class="fw-bold mb-0 {if $deviceType eq 'phone'}fs-6{/if}">Văn bản hệ thống</h4>
		</div>
		{if $clsISO->checkPermission("add_folder") || 1==1}
		<div class="d-flex align-items-center gap-1">
			<button class="btn btn-primary text-nowrap" onClick="$Core.docs.open_folder(this,event)" type="button" data-folder_id='0'>Thêm thư mục</button>
		</div>
		{/if}
	</div>
	<div class="card no-shadow">
		<div class="card-header mb-4">
			{$core->getBlock("search_docs")}
		</div>
		<div class="card-body">				
			<div class="box_doc_top position-relative mb-4">
				<div class="form-row header_doc">
					<h3 class="title_collapse title_pinned text-dark fs-6 d-inline-flex align-items-center px-2 py-1 rounded-pill mb-0" data-bs-toggle="collapse" href="#collapseTopDocs" role="button" aria-expanded="true" aria-controls="collapseTopDocs">Tệp đề xuất</h3>
				</div>
				<div class="body_collapse collapse show" id="collapseTopDocs">
					<div class="d-flex justify-content-between align-items-center box_view position-absolute">
						<span class=""></span>
						<div class="input-group w-auto">
							<button class="form-control btn btn-outline-default bg-white btn_left btn_view_docs btn-sm {if $_ss_view_docs eq 'list'}active{/if}" onClick="$Core.docs.setView(this,event)" data-type="list" data-doc_type="top"><i class='bx bx-list-ul'></i></button>
							<button class="form-control btn btn-outline-default bg-white btn_right btn_view_docs btn-sm {if $_ss_view_docs eq 'grid'}active{/if}" onClick="$Core.docs.setView(this,event)" data-type="grid" data-doc_type="top"><i class='bx bx-grid-alt' ></i></button>
						</div>
					</div>
					<div class="list_docs_top">

					</div>
				</div>
			</div>
			<div class="box_doc_pin position-relative mb-4">
				<div class="form-row header_doc">
					<h3 class="title_collapse title_pinned text-dark fs-6 d-inline-flex align-items-center px-2 py-1 rounded-pill mb-0" data-bs-toggle="collapse" href="#collapsePinned" role="button" aria-expanded="true" aria-controls="collapsePinned">Tệp đã ghim</h3>
				</div>
				<div class="body_collapse collapse show" id="collapsePinned">
					<div class="d-flex justify-content-between align-items-center box_view position-absolute">
						<span class=""></span>
						<div class="input-group w-auto">
							<button class="form-control btn btn-outline-default bg-white btn_left btn_view_docs btn-sm {if $_ss_view_docs eq 'list'}active{/if}" onClick="$Core.docs.setView(this,event)" data-type="list" data-doc_type="pin"><i class='bx bx-list-ul'></i></button>
							<button class="form-control btn btn-outline-default bg-white btn_right btn_view_docs btn-sm {if $_ss_view_docs eq 'grid'}active{/if}" onClick="$Core.docs.setView(this,event)" data-type="grid" data-doc_type="pin"><i class='bx bx-grid-alt' ></i></button>
						</div>
					</div>
					<div class="list_docs_pin">

					</div>
				</div>
			</div>
			<div class="box_folder_general position-relative mb-4">
				<div class="form-row header_doc">
					<h3 class="title_collapse title_pinned text-dark fs-6 d-inline-flex align-items-center px-2 py-1 rounded-pill mb-0" data-bs-toggle="collapse" href="#collapseFolder" role="button" aria-expanded="true" aria-controls="collapseFolder">Thư mục chung</h3>
				</div>
				<div class="body_collapse collapse show" id="collapseFolder">
					<div class="list_folder"></div>
				</div>
			</div>
			<div class="box_my_folder position-relative mb-4">
				<div class="form-row header_doc">
					<h3 class="title_collapse title_pinned text-dark fs-6 d-inline-flex align-items-center px-2 py-1 rounded-pill mb-0" data-bs-toggle="collapse" href="#collapseMyFolder" role="button" aria-expanded="true" aria-controls="collapseMyFolder">Thư mục được chia sẻ</h3>
				</div>
				<div class="body_collapse collapse show" id="collapseMyFolder">
					<div class="my_list_folder"></div>
				</div>
			</div>		
			
			
			
		</div>
	</div>
</div>
{literal}
<script>
	$(function(){
		$Core.docs.load_folder({"type":"0"}); 
		$Core.docs.load_folder({"type":"1"}); 
		$Core.docs.load_docs({'type':"pin"}); 
		$Core.docs.load_docs({'type':"top"}); 
	})
</script>
{/literal}
