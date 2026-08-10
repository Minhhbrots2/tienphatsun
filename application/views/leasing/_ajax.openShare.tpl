<div class="modal-dialog modal-dialog-centered {if $deviceType eq 'phone'}modal-md{else}modal-xs{/if}">
	<div class="modal-content">
		{assign var = stock_code value = $clsLeasing->getCode($oneLeasing)}
		<div class="modal-header position-relative d-block">			
			<div class="d-flex justify-content-start flex-wrap align-items-center">				
				<h5 class="modal-title fs-5 text-main mr-2">Chia sẻ</h5>
				<div class="btn-share sharer-icons d-flex flex-column mr-2" data-link_share="{$clsLeasing->getLink($leasing_id, $oneLeasing.stock_code)}" data-title_share="{$oneLeasing.title}" style="color: #009dff"></div>
				
				<div class="zalo-share-button mr-2 cursor-pointer" data-href="{$PCMS_URL}{$clsLeasing->getLink($leasing_id, $oneLeasing.stock_code)}" data-oaid="2676137751816348684" data-layout="3" data-color="blue" data-customize="true" data-width="100%" style="width: 40px;height: 40px" data-callback="$Core.leasing.close_pop(this, event)">
					<span class="btn btn-icon btn-outline-default rounded-pill btn-zalo"><img src="{$URL_IMAGES}/zalo_chat.png" width="22" height="22" alt=""></span>
				</div>
				
				<div class="btn-share btn btn-icon btn-outline-default rounded-pill"><a href="javascript:void(0)" onClick="$Core.leasing.copyToClipboard(this, event)" data-bs-toggle="tooltip" title="Sao chép link" data-bs-trigger="hover" data-link="{$PCMS_URL}{$clsLeasing->getLink($leasing_id, $stock_code)}" class="fs-6"  style="color: #009dff">{$clsISO->makeIcon('bx-link')}</a></div>
			</div>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" return_url="{$return_url}" onClick="$Core.leasing.close_pop(this, event)" style="position: absolute;right: 20px;top: 25px"></button>
		</div>
		<form id="frmIssue"  action="POST" enctype="multipart/form-data" charset="UTF-8">
		    <div class="modal-body">
				<div class="border d-flex mb-3 rounded-2 {if $deviceType eq 'phone'}flex-column{/if}">
					<div class="box_image rounded-2" {if $deviceType eq 'phone'}style="max-width:100%;max-height: 150px;width: 100%"{else}style="max-width:120px"{/if}>
						<img class="w-100 h-100 radius-3" src="{$image_share}" alt="" {if $deviceType eq 'phone'}style="object-fit: cover;max-height: 150px !important;"{else}style="object-fit: cover"{/if}>
					</div>
					<div class="content_share px-2 py-4 flex-fill">
						<h5 class="fs-6 mb-2">{$title_share}</h5>
						<div class="content">{$description_share}</div>
					</div>
				</div>
			</div>
		</form>
	</form>
</div>
{literal}
<script>
	$(document).ready(function(){
		$(".sharer-icons").each(function(index){
			var link_share = $(this).data('link_share'),
				title_share = $(this).data('title_share');
			$(this).empty().sharer({
				networks: ["facebook"],
				url : PCMS_URL+link_share,
				title : title_share
			});
		})
	});
</script>
{/literal}