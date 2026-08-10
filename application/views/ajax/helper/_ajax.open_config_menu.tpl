{if $deviceType eq 'phone'}
<div class="modal fade bottom modal_fade_bottom" id="{$uid}" tabindex="-1" aria-modal="true" role="dialog"> 
{/if}
	<div class="modal-dialog modal-xs modal-dialog-centered">
		<form action="" class="modal-content overflow-hidden">
			<div class="modal-header border-bottom d-flex align-items-center justify-content-between">
				<div class="d-flex gap-2 align-items-center">
					<button type="button" class="btn_close btn btn-icon btn-sm" data-bs-dismiss="modal" aria-label="Close"><i class='bx bx-x' ></i></button>
					<h5 class="modal-title" id="modalTopTitle">Tùy chỉnh tính năng</h5>
				</div>				
				<button type="button" class="btn btn-outline-primary btn-sm ml-2" onClick="$Core.mobile.saveMenu(this,event)">Lưu</button>				
			</div>
			<div class="modal-body scroller p-0">
				<div class="p-3" style="background: #0080000a">
					<div class="d-flex flex-wrap list_active drag_menu_active" id="list_active_{$uid}">
						{foreach from=$utilities_active item=_oUtilities key=key name=i}
							<div class="item_menu_grid item_menu_active w-25 mb-2 px-2" id="{$_oUtilities.id}_{$uid}" >
								<label class="text-dark text-center text-center d-block" href="javascript:void(0);" for="item_active_{$_oUtilities.id}_{$uid}">
									<div class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto position-relative">
										<i class="fs-30 text-main {$_oUtilities.icon}"></i>
										<button class="btn btn-icon btn-xs bg-white rounded-pill" onClick="$Core.mobile.removeMenu(this,event)" toId="item_unactive_{$_oUtilities.id}_{$uid}" style="position: absolute;top: -12px;right: -12px;box-shadow: 0px 0px 2px #00000061"><i class='bx bx-minus'></i></button>
									</div>
									<span class="text-dark fw-semibold fs-12">{$_oUtilities.title}</span>
									<input type="checkbox" name="utilites_menu[]" value="{$_oUtilities.id}" id="item_active_{$_oUtilities.id}_{$uid}" class="d-none" checked>
								</label>
							</div>
						{/foreach}
					</div>					
				</div>
				<div class="alert-warning text-center p-2">Cần chọn tối thiểu 8 tính năng </div>
				<div class="p-3 list_unactive">
					<h3 class="text-dark">Tính năng đề xuất</h3>
					<div class="form-row row-cols-4  " style="row-gap: 10px">
						{foreach from=$lst_utilities item=_oUtilities key=key name=i}
							<div class="col item_menu_grid position-relative {if !$clsISO->checkItemInArray($_oUtilities.id,$utilities_unactive)}d-none{/if}" id="item_unactive_{$_oUtilities.id}_{$uid}">
								<a class="text-dark text-center text-center d-block" href="javascript:void(0);">
									<div class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto">
										<i class="fs-30 text-main {$_oUtilities.icon}"></i>
										<button class="btn btn-icon btn-xs bg-white rounded-pill" onClick="$Core.mobile.addMenu(this,event)" toId="list_active_{$uid}" uid="{$uid}" options='{ldelim}"utilities_id":"{$_oUtilities.id}","icon":"{$_oUtilities.icon}","title":"{$_oUtilities.title}"{rdelim}' utilities_id="{$_oUtilities.id}" style="position: absolute;top: -12px;right: -12px;box-shadow: 0px 0px 2px #00000061"><i class='bx bx-plus'></i></button>
									</div>
									<span class="text-dark fw-semibold fs-12">{$_oUtilities.title}</span>
								</a>								
							</div>
						{/foreach}
					</div>
				</div>
			</div>
		</form>
	</div>
{if $deviceType eq 'phone'}
</div>
{/if}
{literal}
<style>
	.ui-sortable-placeholder{
		visibility: visible !important
	}
.ui-sortable-helper {
	display:block;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important; /* Giữ nền trong suốt nếu icon có card */
    pointer-events: none; /* Tránh cản trở việc tính toán drop zone */
}
/* Tùy chỉnh thêm để bản sao trông nổi bật hơn khi kéo */
.ui-sortable-helper .item_icon {
    box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
    transform: scale(1.05); /* Phóng to nhẹ để tạo cảm giác đang nhấc lên */
    transition: transform 0.2s;
}
</style>
{/literal}