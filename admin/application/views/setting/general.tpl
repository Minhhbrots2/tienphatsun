{* Cấu hình hệ thống — một trang, một form, một lần lưu.
   Rail trái chỉ để nhảy nhanh tới nhóm và lọc field, KHÔNG cắt form thành tab:
   mọi field luôn nằm trong DOM nên submit không bao giờ thiếu key. *}
<header class="ui-title-bar-container ">

	<div class="ui-title-bar">

		<div class="ui-title-bar__navigation">

			<div class="ui-breadcrumbs">

				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">

					{$core->makeIcon('angle-left mr-5')}

					<span class="ui-breadcrumb__item">{$core->get_Lang('Setting')}</span>

				</a>

			</div>

		</div>

	</div>

	<div class="ui-title-bar ui-title-bar--separator">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title">Cấu hình</h1>

			</div>

		</div>

	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>

</header>

<div class="clearfix"></div>

<form method="post" action="" enctype="multipart/form-data" class="validate-form">

	<div class="ui-layout">

		<div class="setting-general">

			<aside class="setting-general__rail">

				<div class="setting-general__search">

					{$core->makeIcon('search')}

					<input type="text" id="setting-general-search" class="form-control" placeholder="Tìm cấu hình..." autocomplete="off" aria-label="Tìm cấu hình" />

					<button type="button" class="setting-general__search-clear" id="setting-general-search-clear" aria-label="Xóa từ khóa">{$core->makeIcon('times')}</button>

				</div>

				<nav class="setting-general__nav" aria-label="Nhóm cấu hình">

					{foreach from=$configGroups item=_oGroup name=railLoop}

					<a href="#{$_oGroup.slug|escape}" class="setting-general__nav-item{if $smarty.foreach.railLoop.first} is-active{/if}" data-slug="{$_oGroup.slug|escape}">

						{$core->makeIcon($_oGroup.icon)}

						<span class="setting-general__nav-text">{$_oGroup.label|escape}</span>

						<span class="setting-general__nav-count"></span>

						<i class="setting-general__nav-dot" title="Nhóm này có thay đổi chưa lưu"></i>

					</a>

					{/foreach}

				</nav>

			</aside>

			<div class="setting-general__groups">

				{foreach from=$configGroups item=_oGroup}

				{include file="./_config_group.tpl" group=$_oGroup}

				{/foreach}

				<p class="setting-general__empty">Không có cấu hình nào khớp từ khóa đang tìm.</p>

			</div>

		</div>

		<div class="setting-general__savebar">

			<span class="setting-general__savebar-text"><b class="setting-general__savebar-count">0</b> thay đổi chưa lưu</span>

			<div class="setting-general__savebar-actions">

				<input value="Update" name="submit" type="hidden" />

				{$saveBtn}

			</div>

		</div>

	</div>

</form>
{literal}
<script type="text/javascript">
	$(function(){
		var $groups = $('.setting-general__groups');
		var $navItems = $('.setting-general__nav-item');
		var $saveBar = $('.setting-general__savebar');
		var $search = $('#setting-general-search');

		if(!$groups.length){
			return;
		}

		/* Bảng màu và ô text đi cặp: ô text mới là giá trị được lưu. */
		$groups.on('input change', '.config-color__picker', function(){
			$(this).closest('.input-group').find('.config-color__value').val($(this).val());
		}).on('input change', '.config-color__value', function(){
			var _value = ($(this).val() || '').trim();

			if(/^#[0-9a-fA-F]{6}$/.test(_value)){
				$(this).closest('.input-group').find('.config-color__picker').val(_value);
			}
		});
		
		/* Field đi cặp: ô phụ chỉ có nghĩa với đúng một lựa chọn ở ô chính
		   (vd: chỉ "Admin duyệt" mới phải chỉ đích danh admin nhận phiếu). */
		$groups.on('change', '.setting-general__pair-main', function(){
			var $main = $(this);
			var _open = $main.val() === $main.attr('data-pair-show');

			$main.closest('.setting-general__pair').find('.setting-general__pair-extra').toggleClass('is-open', _open);
		});

		/* Ô xem trước chỉ 40px, không đủ soi logo hay ảnh nền → bấm vào xem cỡ thật.
		   Tự dựng lớp phủ vì admin không nạp fancybox/lightbox nào. */
		var $lightbox = null;

		function closeLightbox(){
			if(!$lightbox){
				return;
			}
			$lightbox.remove();
			$lightbox = null;
		}

		function openLightbox(_src, _alt){
			closeLightbox();
			$lightbox = $('<div class="cfg-lightbox"><button type="button" class="cfg-lightbox__close" aria-label="Đóng">&times;</button><img class="cfg-lightbox__img" alt="" /></div>');
			$lightbox.find('.cfg-lightbox__img').attr('src', _src).attr('alt', _alt || '');
			$lightbox.appendTo(document.body);
		}

		$groups.on('click', '.cfg-imgfield .isoman_img_pop', function(){
			/* .src (thuộc tính DOM) chứ không phải attr: onerror có thể đã đổi sang
			   ảnh trống, lấy attr sẽ mở đúng cái đường dẫn vừa hỏng. */
			openLightbox(this.src, $(this).attr('alt'));
		});

		/* Bấm nền hoặc nút đóng thì thoát; bấm đúng tấm ảnh thì giữ nguyên. */
		$(document).on('click', '.cfg-lightbox', function(e){
			if($(e.target).hasClass('cfg-lightbox__img')){
				return;
			}
			closeLightbox();
		});

		$(document).on('keydown', function(e){
			if(e.which === 27){
				closeLightbox();
			}
		});

		/* Bỏ dấu tiếng Việt để gõ "ngan hang" vẫn tìm ra "Tên ngân hàng". */
		function plainText(_text){
			var _value = (_text === undefined || _text === null) ? '' : String(_text);

			_value = _value.toLowerCase();
			if(_value.normalize){
				_value = _value.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
			}
			return _value.replace(/đ/g, 'd');
		}

		/* So sánh với giá trị lúc mở trang, không chỉ "đã gõ vào": sửa rồi hoàn
		   nguyên thì field sạch trở lại và thanh lưu tự đóng. */
		var $fields = $groups.find('.setting-general__field');
		var _tracking = false;

		function fieldState($field){
			return $field.find('input, select, textarea').serialize();
		}

		/* Chốt mốc sau khi chosen/selectize dựng xong DOM. Chốt ngay trong ready
		   thì phần widget thêm vào sau bị tính là thay đổi, thanh lưu mở sẵn. */
		setTimeout(function(){
			$fields.each(function(){
				var $field = $(this);

				$field.data('initState', fieldState($field));
			});
			_tracking = true;
		}, 0);

		/* Đếm field đã sửa để người dùng biết đang đổi những gì trước khi lưu. */
		function refreshDirty(){
			var _total = 0;

			if(!_tracking){
				return;
			}
			$fields.each(function(){
				var $field = $(this);
				var _dirty = fieldState($field) !== $field.data('initState');

				$field.toggleClass('is-dirty', _dirty);
				if(_dirty){
					_total++;
				}
			});
			$('.setting-general__savebar-count').text(_total);
			$saveBar.toggleClass('is-open', _total > 0);
			$navItems.each(function(){
				var $item = $(this);
				var _dirty = $('#' + $item.attr('data-slug')).find('.setting-general__field.is-dirty').length;

				$item.toggleClass('is-dirty', _dirty > 0);
			});
		}

		$groups.on('input change', 'input, select, textarea', refreshDirty);

		/* isoman (field ảnh) và chosen/selectize ghi thẳng vào value bằng JS,
		   không bắn event nào lên form — không quét lại thì thanh lưu không mở
		   và người dùng mất luôn đường lưu. */
		setInterval(refreshDirty, 500);

		/* Lọc theo nhãn + keyword. Field không khớp chỉ bị ẩn bằng CSS,
		   vẫn nằm trong form nên giá trị cũ không bao giờ bị ghi rỗng. */
		function runSearch(){
			var _term = plainText($search.val()).trim();
			var _visible = 0;

			$('.setting-general__group').each(function(){
				var $group = $(this);
				var _groupHit = _term !== '' && plainText($group.attr('data-label')).indexOf(_term) !== -1;
				var _hit = 0;

				$group.find('.setting-general__field').each(function(){
					var $field = $(this);
					var _haystack = plainText($field.attr('data-label') + ' ' + $field.attr('data-keyword'));
					var _match = _term === '' || _groupHit || _haystack.indexOf(_term) !== -1;

					$field.toggleClass('is-hidden', !_match);
					if(_match){
						_hit++;
					}
				});
				$group.toggleClass('is-hidden', _hit === 0);
				if(_hit > 0){
					_visible++;
				}
				$('.setting-general__nav-item[data-slug="' + $group.attr('data-slug') + '"]')
					.toggleClass('is-empty', _hit === 0)
					.find('.setting-general__nav-count').text(_term === '' ? '' : _hit);
			});
			$('.setting-general').toggleClass('is-searching', _term !== '');
			$('.setting-general__empty').toggleClass('is-open', _visible === 0);
		}

		$search.on('input', runSearch).on('keydown', function(e){
			/* Enter trong ô tìm kiếm không được submit cả form. */
			if(e.which === 13){
				e.preventDefault();
			}
		});
		$('#setting-general-search-clear').on('click', function(){
			$search.val('');
			runSearch();
			$search.focus();
		});

		/* Rail bám theo nhóm đang xem. Không có IntersectionObserver thì rail
		   vẫn bấm được, chỉ mất phần tự sáng. */
		if(window.IntersectionObserver){
			var _spy = new IntersectionObserver(function(entries){
				$.each(entries, function(_index, _entry){
					if(!_entry.isIntersecting){
						return;
					}
					$navItems.removeClass('is-active');
					$('.setting-general__nav-item[data-slug="' + _entry.target.id + '"]').addClass('is-active');
				});
			}, { rootMargin: '-100px 0px -60% 0px' });

			$('.setting-general__group').each(function(){
				_spy.observe(this);
			});
		}

		$navItems.on('click', function(e){
			var _top = $('#' + $(this).attr('data-slug')).offset().top - 100;

			e.preventDefault();
			$navItems.removeClass('is-active');
			$(this).addClass('is-active');
			$('html, body').animate({ scrollTop: _top }, 220);
		});

		/* Ctrl+S lưu như mọi màn soạn thảo khác, không phải cuộn xuống cuối trang.
		   Chưa sửa gì thì bỏ qua, đúng như lúc thanh lưu đang ẩn. */
		$(document).on('keydown', function(e){
			if(!(e.ctrlKey || e.metaKey) || e.which !== 83){
				return;
			}
			e.preventDefault();
			if(!$saveBar.hasClass('is-open')){
				return;
			}
			$saveBar.find('button, input[type="submit"]').first().click();
		});
	});
</script>
{/literal}
