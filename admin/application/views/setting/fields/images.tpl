{* Chọn ảnh qua trình quản lý file — cùng bố cục với màn Thông tin công ty.
   isoman ghi kết quả vào #isoman_url_/#isoman_hidden_/#isoman_show_<for_id>
   nên id của 3 phần tử bắt buộc theo đúng khuôn đó.
   width/height nhận qua {include} là kích thước ảnh KHI HIỂN THỊ NGOÀI TRANG,
   không liên quan tới ô xem trước ở đây (ô đó cố định bằng CSS). Lưu thành 2 key
   phụ <key>_width / <key>_height, .tpl khác đọc lại bằng
   $clsConfiguration->getImageAttr('<key>'). *}
<div class="cfg-imgfield">

	<img class="isoman_img_pop" id="isoman_show_{$keyword|escape}" src="{$val.preview_src|escape}" alt="{$val.label|escape}" onerror="this.src='{$URL_IMAGES}/none_image.png'" />

	<input type="hidden" id="isoman_hidden_{$keyword|escape}" value="{$current|escape}" />

	<input type="text" class="form-control" id="isoman_url_{$keyword|escape}" name="config[{$keyword|escape}]" value="{$current|escape}"{if !empty($val.placeholder)} placeholder="{$val.placeholder|escape}"{/if} />

	<input type="number" class="form-control cfg-imgfield__size" name="config[{$val.width_keyword|escape}]" value="{$width}" min="0" step="1" title="Bề ngang (px)" aria-label="Bề ngang (px)" />

	<input type="number" class="form-control cfg-imgfield__size" name="config[{$val.height_keyword|escape}]" value="{$height}" min="0" step="1" title="Chiều cao (px)" aria-label="Chiều cao (px)" />

	<a href="#" class="ajOpenDialog cfg-pick" isoman_for_id="{$keyword|escape}" isoman_val="{$current|escape}" isoman_name="{$keyword|escape}" title="{$val.label|escape}"><img src="{$URL_IMAGES}/general/folder-32.png" alt="Open" /></a>

</div>
