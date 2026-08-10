{if !empty($lstItem)}
	<div class="card bg-main h-100 box">
		<div class="card-body d-flex justify-content-center align-items-center">
			<div class="d-flex align-items-center justify-content-center gap-2 position-relative {if $deviceType eq 'phone'}flex-column{/if}">
				<div class="marquee flex-fill overflow-hidden {if $deviceType eq 'phone'}w-100{/if}">
					<div class="marquee__inner flex-fill d-flex align-items-center gap-4 justify-content-center">
						{foreach from=$lstItem item=_oItem}
						<div class="item item_incentive cursor-pointer d-flex flex-column align-items-center gap-1" data-fancybox data-src="{$_oItem.image}" data-caption="{$_oItem.title}">
							<p class="text-nowrap mb-0 text-fs-13 text-white">Chương trình thi đua nhân viên xuất sắc nhất năm</p>
							<div class="d-flex align-items-center gap-2 mt-n1">
								<svg class="animated-icon text-white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16">
									<path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
								</svg>
								<a class="text-nowrap {if $deviceType eq 'phone'}fs-20{else}fs-24{/if} text-main pulse_incentive position-relative" target="_blank" rel="nofollow noindex"  title="{$_oItem.title}" style="background: linear-gradient(90deg, #f78200, #ffffff, #f78200);-webkit-background-clip: text;-webkit-text-fill-color: transparent">{$_oItem.title}</a>
								<svg class="animated-icon text-white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16">
									<path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
								</svg>		
							</div>
						</div>
						{/foreach}
					</div>
				</div>
			</div>
		</div>
	</div>
	{literal}
		<style>
			/*news*/
			.marquee__inner {
			  display: inline-flex;     /* giữ items trên 1 hàng */
			  align-items: center;
			  white-space: nowrap;
			  position: relative;
			  will-change: transform;
			  transform: translateX(0);
			}
			.item_incentive:after{
				content:"";
				position:absolute;
				left:0; top:0;
				width:100%;
				height:100%;
				background:url("/application/themes/images/l03b0s.d57ca31e.gif") repeat-x center -80px/auto;
			}
			.pulse_incentive:before {
				content: '';
				position: absolute;
				left: calc(50% - 15px);
				right: unset;
				top: calc(50% - 15px);
				width: 30px;
				height: 30px;
				display: block;
				box-sizing: border-box;
				border-radius: 45px;
				background-color: rgb(159, 34, 58);
				animation: pulse-ring 1.25s 
				cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
			}
			.item {
			  display: inline-block;
			  box-sizing: border-box;
			}
			.marquee:hover { cursor: default; }
			/*end news*/
		</style>
		<script>
			$(function(){
				if($(".item_incentive").length > 1 && 1==2) {
					const $marquee = $('.marquee');
					const $inner = $marquee.find('.marquee__inner');
					const $originalItems = $inner.children().clone(); // clone bộ item gốc để dùng lại
					if ($originalItems.length === 0) return;

					// lưu HTML gốc (bộ item ban đầu) để append khi cần
					const origHTML = $('<div>').append($originalItems.clone()).html();

					// đảm bảo inner đủ dài (ít nhất gấp 2 container) -> append bản gốc nhiều lần
					// dùng while + safeguard
					let safe = 0;
					while ($inner.width() < $marquee.width() * 2 && safe < 40) {
						$inner.append(origHTML);
						safe++;
					}
					// tính width của 1 'bộ' gốc (tổng width của các item gốc)
					function calcSingleWidth() {
						let w = 0;
						// dùng first N children tương ứng số item gốc
						const children = $inner.children().slice(0, $originalItems.length);
						children.each(function() { w += $(this).outerWidth(true); });
						return w || $inner.width();
					}
					let singleWidth = calcSingleWidth();

					// animation bằng requestAnimationFrame (delta time) -> mượt
					let speed = 50; // pixels per second, chỉnh để nhanh/chậm
					let x = 0;       // vị trí hiện tại (px)
					let lastTime = null;
					let running = true;
					let rafId;

					function step(ts) {
						if (!lastTime) lastTime = ts;
						const dt = (ts - lastTime) / 1000; // seconds
						lastTime = ts;

						if (running) {
							x -= speed * dt; // dịch sang trái
							// reset mượt khi đã dịch hết 1 bộ (singleWidth)
							if (Math.abs(x) >= singleWidth) {
								// cộng singleWidth để dịch về range [-singleWidth, 0)
								x += singleWidth;
							}
							$inner.css('transform', 'translateX(' + x + 'px)');
						}

						rafId = requestAnimationFrame(step);
					}

					rafId = requestAnimationFrame(step);

					// pause / resume khi hover
					$marquee.on('mouseenter', function(){
						running = false;
					}).on('mouseleave', function(){
						running = true;
						lastTime = null; // tránh jump do dt lớn
					});

					// hỗ trợ touch trên mobile
					$marquee.on('touchstart', function(){ running = false; }).on('touchend touchcancel', function(){ running = true; lastTime = null; });

					// khi resize: tính lại singleWidth, đảm bảo đủ độ dài
					$(window).on('resize', function(){
						singleWidth = calcSingleWidth();
						// append thêm nếu cần
						let safe2 = 0;
						while ($inner.width() < $marquee.width() * 2 && safe2 < 5) {
							$inner.append(origHTML);
							safe2++;
						}
					});

					// optional: nếu muốn dừng hoàn toàn khi rời trang
					$(window).on('blur', function(){ running = false; });
					$(window).on('focus', function(){ running = true; lastTime = null; });
					/*end news*/
				}
			})
		</script>
	{/literal}
{/if}