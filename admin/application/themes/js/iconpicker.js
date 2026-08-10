/* $Core.iconpicker — bộ chọn icon (boxicons) DÙNG LẠI ĐƯỢC ở mọi nơi trong admin.
   Tải global trong index.tpl (sau admin.js) → có sẵn trên mọi trang.

   CÁCH DÙNG (markup contract) — đặt khối này ở bất kỳ form nào:
     <div class="iconpicker-field" data-iconpicker>
       <span class="iconpicker-preview-box"><i class="iconpicker-preview {value}"></i></span>
       <input type="text" class="form-control iconpicker-input" name="..." value="{value}" />
       <button type="button" class="btn btn-default iconpicker-btn"><i class="bx bx-grid-alt"></i></button>
     </div>
   - Bấm nút .iconpicker-btn → mở popup chọn icon.
   - Chọn icon + Xác nhận → ghi class vào .iconpicker-input, cập nhật .iconpicker-preview, bắn 'change'.
   - Gõ tay vào .iconpicker-input cũng tự cập nhật preview (không bắt buộc readonly).
   - Muốn trỏ tới input NGOÀI wrapper: thêm data-iconpicker-target="#css_selector" trên .iconpicker-field.
   Lưu ý: mỗi phần tử trong ICONS đã có sẵn tiền tố "bx " nên render thẳng <i class="{icon}">. */
(function($){
	if(typeof window.$Core === 'undefined'){ window.$Core = {}; }

	var ICONS = [
		'bx bx-child','bx bx-sushi','bx bx-shower','bx bx-rfid','bx bx-universal-access','bx bx-shield-minus','bx bx-shield-plus',
		'bx bx-vertical-bottom','bx bx-vertical-top','bx bx-horizontal-right','bx bx-horizontal-left','bx bx-objects-vertical-bottom',
		'bx bx-objects-vertical-center','bx bx-objects-vertical-top','bx bx-objects-horizontal-right','bx bx-objects-horizontal-center',
		'bx bx-objects-horizontal-left','bx bx-color','bx bx-reflect-horizontal','bx bx-reflect-vertical','bx bx-cart-add','bx bx-cart-download',
		'bx bx-no-signal','bx bx-signal-5','bx bx-signal-4','bx bx-signal-3','bx bx-signal-2','bx bx-signal-1','bx bx-cheese','bx bx-hard-hat',
		'bx bx-home-alt-2','bx bx-lemon','bx bx-cable-car','bx bx-cricket-ball','bx bx-male-female','bx bx-baguette','bx bx-fork','bx bx-knife',
		'bx bx-circle-half','bx bx-circle-three-quarter','bx bx-circle-quarter','bx bx-bowl-rice','bx bx-bowl-hot','bx bx-popsicle','bx bx-cross',
		'bx bx-scatter-chart','bx bx-money-withdraw','bx bx-candles','bx bx-math','bx bx-party','bx bx-leaf','bx bx-injection','bx bx-expand-vertical',
		'bx bx-expand-horizontal','bx bx-collapse-vertical','bx bx-collapse-horizontal','bx bx-collapse-alt','bx bx-qr','bx bx-qr-scan','bx bx-podcast',
		'bx bx-checkbox-minus','bx bx-speaker','bx bx-registered','bx bx-phone-off','bx bx-buildings','bx bx-store-alt','bx bx-bar-chart-alt-2',
		'bx bx-message-dots','bx bx-message-rounded-dots','bx bx-memory-card','bx bx-wallet-alt','bx bx-slideshow','bx bx-message-square',
		'bx bx-message-square-dots','bx bx-book-content','bx bx-chat','bx bx-edit-alt','bx bx-mouse-alt','bx bx-bug-alt','bx bx-notepad',
		'bx bx-video-recording','bx bx-shape-square','bx bx-shape-triangle','bx bx-ghost','bx bx-mail-send','bx bx-code-alt','bx bx-grid',
		'bx bx-user-pin','bx bx-run','bx bx-copy-alt','bx bx-transfer-alt','bx bx-book-open','bx bx-landscape','bx bx-comment','bx bx-comment-dots',
		'bx bx-pyramid','bx bx-cylinder','bx bx-lock-alt','bx bx-lock-open-alt','bx bx-left-arrow-alt','bx bx-right-arrow-alt','bx bx-up-arrow-alt',
		'bx bx-down-arrow-alt','bx bx-shape-circle','bx bx-cycling','bx bx-dna','bx bx-bowling-ball','bx bx-search-alt-2','bx bx-plus-medical',
		'bx bx-street-view','bx bx-droplet','bx bx-paint-roll','bx bx-shield-alt-2','bx bx-error-alt','bx bx-square','bx bx-square-rounded',
		'bx bx-polygon','bx bx-cube-alt','bx bx-cuboid','bx bx-user-voice','bx bx-accessibility','bx bx-building-house','bx bx-doughnut-chart',
		'bx bx-log-in-circle','bx bx-log-out-circle','bx bx-check-square','bx bx-message-alt','bx bx-message-alt-dots','bx bx-no-entry','bx bx-palette',
		'bx bx-basket','bx bx-purchase-tag-alt','bx bx-receipt','bx bx-line-chart','bx bx-map-pin','bx bx-hive','bx bx-band-aid','bx bx-credit-card-alt',
		'bx bx-wifi-off','bx bx-brightness-half','bx bx-brightness','bx bx-filter-alt','bx bx-dialpad-alt','bx bx-border-right','bx bx-border-left',
		'bx bx-border-top','bx bx-border-bottom','bx bx-border-all','bx bx-mobile-landscape','bx bx-mobile-vibration','bx bx-gas-pump',
		'bx bx-pie-chart-alt-2','bx bx-time-five','bx bx-briefcase-alt-2','bx bx-brush-alt','bx bx-customize','bx bx-radio','bx bx-printer',
		'bx bx-sort-a-z','bx bx-sort-z-a','bx bx-conversation','bx bx-exit','bx bx-extension','bx bx-face','bx bx-file-find','bx bx-label',
		'bx bx-check-shield','bx bx-border-radius','bx bx-add-to-queue','bx bx-archive-in','bx bx-archive-out','bx bx-alarm-add','bx bx-space-bar',
		'bx bx-image-alt','bx bx-image-add','bx bx-fridge','bx bx-dish','bx bx-spa','bx bx-cake','bx bx-bolt-circle','bx bx-tone','bx bx-bitcoin',
		'bx bx-lira','bx bx-ruble','bx bx-rupee','bx bx-euro','bx bx-pound','bx bx-won','bx bx-yen','bx bx-shekel','bx bx-health','bx bx-clinic',
		'bx bx-male','bx bx-female','bx bx-male-sign','bx bx-female-sign','bx bx-food-tag','bx bx-food-menu','bx bx-meh-alt','bx bx-wink-tongue',
		'bx bx-happy-alt','bx bx-cool','bx bx-tired','bx bx-smile','bx bx-angry','bx bx-happy-heart-eyes','bx bx-dizzy','bx bx-wink-smile',
		'bx bx-confused','bx bx-sleepy','bx bx-shocked','bx bx-happy-beaming','bx bx-meh-blank','bx bx-laugh','bx bx-upside-down','bx bx-diamond',
		'bx bx-align-left','bx bx-align-middle','bx bx-align-right'
	];

	$Core.iconpicker = {
		id: 'iconpicker_modal',
		icons: ICONS,
		_$field: null,
		// input đích của field (hỗ trợ data-iconpicker-target trỏ ra ngoài)
		_targetInput: function($field){
			var sel = $field.attr('data-iconpicker-target');
			if(sel && $(sel).length) return $(sel).first();
			return $field.find('.iconpicker-input').first();
		},
		_preview: function($field){
			return $field.find('.iconpicker-preview').first();
		},
		// Mở popup cho 1 field
		open: function($field){
			if(!$field || !$field.length) return;
			$Core.iconpicker.close(); // dọn instance cũ nếu còn
			$Core.iconpicker._$field = $field;
			var cur = $.trim($Core.iconpicker._targetInput($field).val() || '');
			$Core.popup.open('auto', 'auto', $Core.iconpicker._html(cur), $Core.iconpicker.id, 'choose_icon_modal');
			var $m = $('#' + $Core.iconpicker.id);
			$m.find('.ip-search').on('keyup', function(){
				var q = $.trim(this.value).toLowerCase();
				$m.find('.ip-item').each(function(){
					var ic = ($(this).attr('data-icon') || '').toLowerCase();
					$(this).toggle(q === '' || ic.indexOf(q) >= 0);
				});
			}).focus();
			$m.on('click', '.ip-item', function(){
				$m.find('.ip-item.selected').removeClass('selected');
				$(this).addClass('selected');
			});
			$m.on('dblclick', '.ip-item', function(){
				$m.find('.ip-item.selected').removeClass('selected');
				$(this).addClass('selected');
				$Core.iconpicker.confirm();
			});
			// cuộn tới icon đang chọn
			var $sel = $m.find('.ip-item.selected');
			if($sel.length){ var g = $m.find('.ip-grid')[0]; if(g){ g.scrollTop = Math.max(0, $sel[0].offsetTop - 80); } }
		},
		// Xác nhận lựa chọn hiện tại
		confirm: function(){
			var $m = $('#' + $Core.iconpicker.id),
				$sel = $m.find('.ip-item.selected'),
				val = $sel.length ? ($sel.attr('data-icon') || '') : '';
			$Core.iconpicker._apply(val);
			$Core.iconpicker.close();
		},
		// Bỏ icon (xoá giá trị)
		clear: function(){
			$Core.iconpicker._apply('');
			$Core.iconpicker.close();
		},
		_apply: function(val){
			var $field = $Core.iconpicker._$field;
			if(!$field || !$field.length) return;
			$Core.iconpicker._targetInput($field).val(val).trigger('change');
			$Core.iconpicker._preview($field).attr('class', 'iconpicker-preview ' + val);
		},
		close: function(){
			$Core.popup.close($('#' + $Core.iconpicker.id));
		},
		_html: function(cur){
			var items = '', i, ic, sel;
			for(i = 0; i < ICONS.length; i++){
				ic = ICONS[i];
				sel = (ic === cur) ? ' selected' : '';
				items += '<div class="ip-item' + sel + '" data-icon="' + ic + '" title="' + ic + '"><i class="' + ic + '"></i></div>';
			}
			return '<div class="modal-dialog modal-lg">'
				+ '<div class="modal-content">'
				+   '<div class="modal-header">'
				+     '<a href="javascript:void(0)" class="close" onclick="$Core.iconpicker.close()"><span>&times;</span></a>'
				+     '<h3 class="modal-title"><strong>Chọn icon</strong></h3>'
				+   '</div>'
				+   '<div class="modal-body">'
				+     '<input type="text" class="form-control ip-search" placeholder="Tìm icon… (vd: home, chart, user, message)" />'
				+     '<div class="ip-grid">' + items + '</div>'
				+   '</div>'
				+   '<div class="modal-footer">'
				+     '<button type="button" class="btn btn-default pull-left" onclick="$Core.iconpicker.clear()">Bỏ icon</button>'
				+     '<button type="button" class="btn btn-default" onclick="$Core.iconpicker.close()">Đóng</button>'
				+     '<button type="button" class="btn btn-success" onclick="$Core.iconpicker.confirm()">Xác nhận</button>'
				+   '</div>'
				+ '</div>'
				+ '</div>';
		}
	};

	// Delegated: bấm nút mở picker
	$(document).on('click', '.iconpicker-field .iconpicker-btn', function(e){
		e.preventDefault();
		$Core.iconpicker.open($(this).closest('.iconpicker-field'));
	});
	// Delegated: gõ tay vào input → cập nhật preview ngay
	$(document).on('input', '.iconpicker-field .iconpicker-input', function(){
		$(this).closest('.iconpicker-field').find('.iconpicker-preview').first()
			.attr('class', 'iconpicker-preview ' + $.trim(this.value));
	});
})(jQuery);
