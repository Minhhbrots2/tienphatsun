{if $action eq "_detail"}
	{assign var = list_images value = $oneToday.list_images}
	{assign var = total_images value = $oneToday.total_images}
	<div class="modal-dialog modal-dialog-centered modal-md modal-today">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title fs-{if $deviceType eq 'phone'}5{else}4{/if} text-main">{$oneToday.title}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="rounded-2 mb-3 overflow-hidden"> 
					<div class="d-flex highlight tEzTuYtLau bg-grayter p-lg-2 mb-0">
						<div class=" text-center flex-fill">
							<p class="mb-0 text-muted lh-base">
								<i class="d-inline-block re__icon-house--sm"></i> Mã căn
							</p>
							<div class="fw-bold">
								<a href="javascript:void(0);" class="text-main" onClick="$Core.helper.open_stock({$oneToday.stock_id})">{$oneToday.stock_code} <i class="bx bx-link-external"></i></a>
							</div>
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="re__icon-ying-yang--xl"></i> Ban công</p>
							<div class="fw-bold text-main">{$oneToday.home_direction_name}</div>
						</div>
						<div class=" text-center flex-fill">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ</p>
							<div class="fw-bold text-main">{$oneToday.DT_TT}m<sup>2</sup></div>
						</div>
						<div class=" text-center flex-fill d-none d-lg-block">
							<p class="mb-0 lh-base"><i class="d-inline-block re__icon-money--sm"></i> Giá/m2</p>
							<div class="fw-bold text-main">{$oneToday.price_m2}tr</div>
						</div>
					</div>
					{if !empty($list_images)}
					<div class="box_image"{if $total_images gt '1'} id="slider-images_{$uid}"{/if} style="max-height:400px">
						{foreach from=$list_images name=i item=img}
						<div class="slideshow-item w-100 h-100 position-relative overflow-hidden" style="max-height:400px">
							<img class="sop_slider_overlay position-absolute zindex-1 owl-lazy" class="owl-lazy" data-src="{$img}" src="{$URL_IMAGES}/no-image.jpg" />
							<div class="sop_slider_image d-flex justify-content-center position-relative zindex-2">
								<img {if $total_images gt '1'}class="owl-lazy" data-src="{$img}" src="{$URL_IMAGES}/no-image.jpg"{else} src="{$img}" class="w-100"{/if} alt="{$oneToday.title}" height="400"/>
							</div>
							<span class="sop_slider-pagination d-inline-block position-absolute">
								{$smarty.foreach.i.iteration}/{$list_images|@count}
							</span>
						</div>
						{/foreach}
					</div>
					{/if}
				</div>
				<div class="content tinyContent my-2 content-copy">
					<div class="d-flex justify-content-between">						
						<div class="content tinyContent text-black">
							{$oneToday.content|nl2br}
						</div>
						<a href="javascript:void(0)" onclick="$Core.today.copyContentToClipboard(this, event)" 
							data-bs-toggle="tooltip" data-bs-trigger="click" class="sop_copy text-dark rounded-pill fs-6" 
							title="Đã sao chép nội dung mô tả"><i class="bx bx-copy"></i>
						</a>
					</div>
				</div>
				<button type="button" class="btn btn-block btn-lg btn-warning" data-bs-dismiss="modal">Đóng cửa sổ</button> 
			</div>
		</div>
	</div>
	<script>
		var uid=`{$uid}`;
	</script>
	{literal}
		<script type="text/javascript">
			$(function(){
				if($('#slider-images_'+uid).length){
					$('#'+uid).on('shown.bs.modal', function (e) {
						$('#slider-images_'+uid).owlCarousel({
							margin:0,
							loop:true,
							nav: true,
							lazyLoad:true,
							dots:true,
							autoplay:false,
							responsiveClass:true,
							navText: ['<i class="fa fa-angle-left"></i>',
								'<i class="fa fa-angle-right"></i>'],
							responsive:{
								0:{items:1},
								1200:{items:1},
							}
						});
					});		
				}
				clearTimeout(_timeOut);
				_timeOut = setTimeout(() => {
					var myModal = new bootstrap.Modal(document.getElementById(uid), {});
					myModal.show();
				}, 500);
			});
		</script>
	{/literal}
{else}
<div class="modal-dialog modal-dialog-centered modal-md">
	<form class="modal-content" enctype="multipart/form-data">
		<div class="modal-header flex-wrap">
			<h5 class="modal-title">Thông báo MOC</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			{if !empty($oneToday.user_id_update)}
			<span class="w-100 text-muted fs-12">Cập nhật bởi: <strong>{$clsProfile->getFullName($oneToday.user_id_update)}</strong> lúc {$clsISO->convertTimeToText($oneToday.upd_date, true)}</span>
			{/if}
		</div>
		<div class="modal-body pt-0">	
			<div class="form-row form-group">
				<div class="col-8 col-md-9 mb-2">
					<label class="w-100 form-label mb-1">Tiêu đề</label>
					<input type="text" placeholder="Nhập tiêu đề" name="title" maxlength="255" charet="UTF-8" class="form_field form-control required no-focus" value="{if $action eq '_edit'}{$oneToday.title}{/if}">
				</div>
				<div class="col-4 col-md-3 mb-2">
					<label class="w-100 form-label mb-1">Mã căn</label>
					<input type="text" onchange="$Core.today.check_stock_code(this, event)" placeholder="Nhập mã căn" name="stock_code" maxlength="255" charet="UTF-8" class="form_field form-control required no-focus" value="{if $action eq '_edit'}{$oneToday.stock_code}{/if}">
				</div>
			</div>
			<div class="form-row form-group">
				<div class="col-6 mb-2">
					<label class="w-100 form-label mb-1">Bắt đầu</label>
					<input type="datetime-local" placeholder="dd/mm/yyyy H:i" name="start_date" min="{$minDate}" class="form_field form-control required no-focus" value="{$oneToday.start_date}">
				</div>
				<div class="col-6 mb-2">
					<label class="w-100 form-label mb-1">Kết thúc</label>
					<input type="datetime-local" placeholder="dd/mm/yyyy H:i" name="end_date" min="{$minDate}" class="form_field form-control required no-focus" value="{$oneToday.end_date}">
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="w-100  form-label mb-1">Nội dung</label>
				<textarea id="{$uid}" class="form-control" name="content" cols="255" rows="10">{if $action eq '_edit'}{$oneToday.content|html_entity_decode}{/if}</textarea>
			</div>
			<div class="form-group mb-2">
				<div class="d-flex align-items-center justify-content-between">
					<label class="form-label mb-1">Hình ảnh</label>
					<input type="hidden" name="total_images" value="{$oneToday.total_images}" />
					<div class="d-flex align-items-center">
						<div class="button btn btn-outline-default me-2 btn-sm delete_all" onClick="$Core.today.delete_image(this, event)" data-type="all" {if $oneToday.total_images eq 0}style="display: none"{/if}><i class="bx bx-x"></i> Xoá tất cả</div>
						<div class="position_relative">
							<input type="file" name="images[]" multiple class="position-absolute" accept="image/*" onChange="$Core.today.do_upload(this, event)" id="select_file_{$uid}" style="z-index: 1;opacity: 0;height: 35px;width: 100px"/>
							<button type="button" class="btn btn-outline-primary text-nowrap btn-sm" toid="select_file_{$uid}" toimg="list_image_{$uid}" data-type="images" profile_id="{$profile_id}"><i class="bx bx-image-add"></i>Tải ảnh</button>
						</div>
					</div>
				</div>
				<div class="form-row row imageList mt-2">
					{if $oneToday.total_images gt '0'}
						{foreach from=$more_information.images item=image}
							{if $image ne ""}
							<div class="item col-6 col-sm-3 mb-2 position-relative">
								<img class="w-100 rounded-1" src="{$image}" style="height:90px; object-fit:cover"/>
								<input type="hidden" name="images[]" value="{$image}" />
								<a class="delete text-white position-absolute top-0 cursor-pointer" src="{$image}" onClick="$Core.today.delete_image(this, event)" data-type="item" style="right:10px">x</a>
							</div>
							{/if}
						{/foreach}
					{else}
						<div class="border border-dashed p-2 text-center mb-2 rounded-1" ondragover="return false"> 
							<a href="javascript:void(0)" class="d-block mb-2 cursor-pointer">
								{$smarty.const.ICON_UPLOAD}
								<p class="mb-0 text-muted">Bấm để chọn ảnh cần tải lên</p> 
							</a>
						</div>
					{/if}																					
				</div>
			</div>
		</div>
		<div class="modal-footer justify-content-between">			
			<div class="d-flex align-items-center">
				<div class="d-flex gap-1 align-items-center">
					<label class="switch">
						<input type="checkbox" name="is_online" value="1" {if $oneToday.is_online eq 1} checked{/if}>
						<span class="slider round"></span>
					</label>
					<label class="col-form-label mr-2">On/Off</label>
				</div>		
				<div class="d-flex gap-1 align-items-center">
					<label class="switch">
						<input type="checkbox" name="is_repeat" value="1"{if $oneToday.is_repeat eq 1} checked{/if}>
						<span class="slider round"></span>
					</label>
					<label class="col-form-label mr-2">Lặp lại</label>
				</div>
			</div>
			<div class="d-flex align-items-center">
				<input type="hidden" name="submit" value="Update" />
				<!-- <button type="button" class="btn bg-white btn-outline-default me-2" data-bs-dismiss="modal">Đóng</button>-->
				<button type="button" today_id="{$oneToday.today_id}" onClick="$Core.today.save_stock_today(this, event)" class="btn btn-primary {if $deviceType eq 'phone'}px-2{/if}">Cập nhật</button>
			</div>
		</div>
	</form>
</div>
{/if}