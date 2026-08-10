<div class="modal-dialog modal-dialog-centered modal-{if $deviceType eq 'phone'}md{else}xs{/if}">
	<div class="modal-content">
		<div class="modal-header position-relative d-block">			
			<div class="d-flex justify-content-start flex-wrap align-items-center">				
				<h5 class="modal-title fs-5 text-main mr-2">Chia sẻ</h5>
				<div uid="{$uid}" class="btn-share sharer-icons d-flex flex-column mr-2" data-link_share="{$more_information.link_share}" data-title_share="{$more_information.title_page}" style="color: #009dff"></div>
				<div class="zalo-share-button mr-2 cursor-pointer" data-href="{$more_information.link_share}" data-oaid="2676137751816348684" data-layout="3" data-color="blue" data-customize="true" data-width="100%" style="width:40px; height:40px" data-callback="$Core.sop.close_pop(this, event)"><span class="btn btn-icon btn-outline-default rounded-pill btn-zalo"><img src="{$URL_IMAGES}/zalo_chat.png" width="22" height="22"></span></div>
				<div class="btn-share btn btn-icon btn-outline-default rounded-pill"><a href="javascript:void(0)" onClick="$Core.sop.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="Sao chép link" data-bs-trigger="hover" data-link="{$more_information.link_share}" class="fs-6" style="color:#009dff">{$clsISO->makeIcon('bx-link')}</a></div>
			</div>
			<button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" 
			aria-label="Close" style="right:20px; top:25px"></button>
		</div>
		<form id="frmIssue" action="POST" enctype="multipart/form-data" charset="UTF-8">
		    <div class="modal-body">
				<div class="d-flex mb-3 gap-2 p-3 border rounded-2{if $deviceType eq 'phone'} flex-column{/if}">
					<div class="box_image rounded-2" {if $deviceType eq 'phone'}style="max-width:100%; max-height:150px; width: 100%"{else}style="max-width:120px"{/if}>
						<img class="w-100 h-100 radius-3" src="{$more_information.image_share}" alt="" {if $deviceType eq 'phone'}style="object-fit: cover;max-height: 150px !important;"{else}style="object-fit: cover"{/if}>
					</div>
					<div class="content_share px-2 py-4 flex-fill">
						<h5 class="fs-6 b-link mb-2">{$more_information.title_page}</h5>
						<div class="content">{$more_information.description_page}</div>
					</div>
				</div>
			</div>
		</form>
	</form>
</div>