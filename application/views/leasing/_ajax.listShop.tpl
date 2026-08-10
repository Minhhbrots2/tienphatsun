<div class="modal-dialog right">
	<div class="modal-content">
		<div class="modal-header border-bottom py-4"> 
			<h3 class="modal-title"><strong>Cửa hàng/ Tiện ích</strong></h3>
		</div>
		<form action="" method="post" id="frmPayOther" encrupt="miltipart/form-data">
			<div class="modal-body border-bottom py-2">
				{section name=i loop=$lstShop}
				<div class="d-flex justify-content-between align-items-center {if !$smarty.section.i.last}border-bottom{/if}">
					<label class="col-form-label">{$lstShop[i].title}</label>
					<a class="text-link" href="{$lstShop[i].map}" target="_blank" title="Chỉ đường">
						<i class="bx bxs-direction-right"></i> Chỉ đường
					</a>
				</div>
				{/section}
			</div>
			<div class="modal-footer justify-content-center">
				<a type="button" class="btn btn-outline-default" onClick="$Core.leasing.close_pop(this, event)" data-bs-dismiss="modal">
					Đóng
				</a>
			</div>
		</form>
	</div>
</div>