<div class="modal-dialog modal-dialog-centered modal-xs">
	<div class="modal-content">
		{assign var = stock_code value = $clsSop->getCode($oneSop)}
		<div class="modal-header position-relative d-block">
			<h5 class="modal-title fs-5 text-main mr-2 text-upper">Chia sẻ bất động sản</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" return_url="{$return_url}" onClick="$Core.sop.close_pop(this, event)" style="position: absolute;right: 20px;top: 25px"></button>
		</div>
		<form id="frmIssue"  action="POST" enctype="multipart/form-data" charset="UTF-8">
		    <div class="modal-body">
				<h5 class="fs-6 mb-2">{$title_share}</h5>
				<div class="content mb-4 ">{$description_share}</div>
				<div class="d-flex justify-content-start flex-wrap align-items-start">
					<div class="btn-share sharer-icons d-flex flex-column mr-2" data-link_share="{$clsSop->getLink($sop_id, $oneSop.stock_code)}" data-title_share="{$oneSop.title}"></div>
					<div class="position-relative mb-2">
						<span class="btn btn-icon position-absolute btn-outline-default rounded-pill btn-zalo"><img src="{$URL_IMAGES}/zalo_chat.png" width="22" height="22" alt=""></span>
						<div class="zalo-share-button w-100" data-href="{$PCMS_URL}{$clsSop->getLink($sop_id, $oneSop.stock_code)}" data-oaid="2676137751816348684" data-layout="3" data-color="blue" data-customize="false" data-width="100%"></div>
					</div>
				</div>
			</div>
		</form>
	</form>
</div>
<script src="https://sp.zalo.me/plugins/sdk.js"></script>
<script src="{$URL_JS}/jquery.sharer.js?v={$upd_version}"></script>
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