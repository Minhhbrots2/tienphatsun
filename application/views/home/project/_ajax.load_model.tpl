<div class="modal right fade show w-100 modal-criterial" id="{$uid}" role="dialog">

	<div class="modal-dialog modal-dialog-scrollabe">

		<div class="modal-content overflow-y">

			<div class="modal-header border-bottom">

				<h5 class="modal-title" id="modalTopTitle">{if $view_type eq 'is_handoverSpecs'}Tiêu chuẩn bàn giao{else}Hình ảnh, video nhà mẫu{/if}</h5>

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

			</div>

			<div class="modal-body overflow-y">

				{if !empty($list_docs)}

					<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxxl-5">

						<!-- End Post -->

						{foreach from=$list_docs item=_oDoc key=k_doc name=n_doc}

						<div class="col mb-2">

							{assign var = oneItem value = $_oDoc}

							{$core->getBlock('item_doc', ['_type'=>"detail",'oneItem' => $_oDoc])}

						</div>

						{/foreach}

					</div>

				{else}

					<div class="d-flex justify-content-center">

						<div class="text-center p-4">

							<img src="{$URL_IMAGES}/no-data.png" height="200">

							<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>

						</div>

					</div>

				{/if}

			</div>

		</div>

	</div>

</div>