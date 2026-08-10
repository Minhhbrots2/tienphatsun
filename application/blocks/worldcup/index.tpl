{if $clsISO->_DEV() || 1 eq 1}
	<div class="wc-stage wc-2026">
	  <!-- ================= BANNER ================= -->
	  <div class="wc-banner" role="region" aria-label="Dự đoán World Cup 2026" style="background: url('{$URL_IMAGES}/bg_worldcup.png');background-size: auto 100%;
	background-position: center;">

		<!-- Nền + hiệu ứng -->
		<div class="wc-bg" aria-hidden="true"></div>
		<svg class="wc-sparkle" viewBox="0 0 960 320" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
		  <defs>
			<filter id="wcBlur" x="-50%" y="-50%" width="200%" height="200%">
			  <feGaussianBlur stdDeviation="1.1"/>
			</filter>
		  </defs>
		  <g fill="#f6c945" filter="url(#wcBlur)">
			<circle cx="70"  cy="55"  r="3"   opacity=".55"/>
			<circle cx="150" cy="120" r="2"   opacity=".40"/>
			<circle cx="40"  cy="200" r="2.4" opacity=".35"/>
			<circle cx="210" cy="40"  r="1.8" opacity=".45"/>
			<circle cx="300" cy="90"  r="2.6" opacity=".30"/>
			<circle cx="120" cy="270" r="2"   opacity=".30"/>
			<circle cx="640" cy="50"  r="2.4" opacity=".45"/>
			<circle cx="720" cy="120" r="3"   opacity=".40"/>
			<circle cx="880" cy="70"  r="2"   opacity=".50"/>
			<circle cx="820" cy="210" r="2.6" opacity=".32"/>
			<circle cx="910" cy="160" r="1.8" opacity=".40"/>
			<circle cx="560" cy="250" r="2"   opacity=".30"/>
			<circle cx="480" cy="30"  r="1.6" opacity=".35"/>
			<circle cx="760" cy="280" r="2.2" opacity=".28"/>
		  </g>
		</svg>

		<!-- Badge MỚI -->
		<span class="wc-new">Hạng #{$clsWorldcupScore->getRank($profile_id)}</span>

		<div class="wc-inner">

		  <!-- CỘT TRÁI: tiêu đề + đếm ngược -->
		  <div class="wc-col wc-col--left">
			<div class="wc-title">
			  	<span class="wc-title__sub">DỰ ĐOÁN</span>
			  	<span class="wc-title__main">WC 2026</span>
			  	<span class="wc-title__tag">DỰ ĐOÁN HAY – RINH QUÀ KHỦNG</span>
			</div>
			<div class="wc-next" id="cardNext">
				<div class="wc-next__head text-upper">Trận đấu tiếp theo</div>
				<div class="wc-next__body">
					<div class="wc-next__teams">
						<div class="wc-team">
							<img class="wc-team__flag" id="nmHomeFlag" alt="" src="https://flagcdn.com/w40/mx.png">
							<span class="wc-team__name" id="nmHome">MEX</span>
						</div>
						<span class="wc-next__vs">VS</span>
						<div class="wc-team wc-team--away">
							<span class="wc-team__name" id="nmAway">CAN</span>
							<img class="wc-team__flag" id="nmAwayFlag" alt="" src="https://flagcdn.com/w40/ca.png">
						</div>
					</div>
					<div class="wc-next__meta">
						<span class="wc-next__time" id="nmTime">08:00 · 13/06</span>
						<span class="wc-next__cd"><span class="wc-next__dot"></span>Còn <b id="nmCountdown">22:51:01</b></span>
					</div>
				</div>
			</div>
		  </div>

		  <!-- CỘT PHẢI: tính năng + CTA -->
		  <div class="wc-col wc-col--right">
			<ul class="wc-feats">
			  <li class="wc-feat">
				<span class="wc-feat__ic">
				  <!-- icon: mục tiêu/dự đoán -->
				  <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="9"/>
					<circle cx="12" cy="12" r="5"/>
					<circle cx="12" cy="12" r="1.4" fill="var(--wc-gold-2)" stroke="none"/>
				  </svg>
				</span>
				<span class="wc-feat__tx">DỰ ĐOÁN TẤT CẢ<br>CÁC TRẬN ĐẤU</span>
			  </li>

			  <li class="wc-feat">
				<span class="wc-feat__ic">
				  <!-- icon: bảng xếp hạng -->
				  <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<line x1="6"  y1="20" x2="6"  y2="13"/>
					<line x1="12" y1="20" x2="12" y2="8"/>
					<line x1="18" y1="20" x2="18" y2="4"/>
				  </svg>
				</span>
				<span class="wc-feat__tx">BẢNG XẾP HẠNG<br>REAL-TIME TRÊN C-A</span>
			  </li>

			  <li class="wc-feat">
				<span class="wc-feat__ic">
				  <!-- icon: quà tặng -->
				  <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<rect x="4" y="9" width="16" height="11" rx="1.5"/>
					<path d="M3 9h18M12 9v11"/>
					<path d="M12 9C12 6 10 4 8.5 4 7 4 6.5 5.5 7.2 6.6 7.9 7.7 9.8 8.6 12 9z"/>
					<path d="M12 9C12 6 14 4 15.5 4 17 4 17.5 5.5 16.8 6.6 16.1 7.7 14.2 8.6 12 9z"/>
				  </svg>
				</span>
				<span class="wc-feat__tx">CỘNG ĐIỂM MỖI ROUND<br>NHẬN THƯỞNG HẤP DẪN</span>
			  </li>
			</ul>

			<a href="{$clsISO->getLink('worldcup')}" class="wc-cta" id="wcCta">
				DỰ ĐOÁN NGAY
				<i class="bx bx-chevron-right" ></i>
			</a>
		  </div>

		</div>
	  </div>
	  <!-- =============== /BANNER =============== -->

	</div>
<script>
	var fixtures = {$oneNextMatch};
</script>
{literal}
	<script>
		$(function () {
			/* ====== NGÀY KHAI MẠC – CHỈNH TẠI ĐÂY ====== */
			// World Cup 2026 khai mạc 11/06/2026 (giờ VN). Đổi theo nhu cầu của bạn.
			var target = new Date('2026-06-11T23:30:00+07:00').getTime();
			var current = null;

			var $d = $('#cdDays'), $h = $('#cdHours'), $m = $('#cdMins'), $s = $('#cdSecs');
			function parts(ms){
				if (ms < 0) ms = 0;
				return {
					d: Math.floor(ms / 86400000),
					h: Math.floor((ms % 86400000) / 3600000),
					m: Math.floor((ms % 3600000) / 60000),
					s: Math.floor((ms % 60000) / 1000)
				};
			}
			function pad(n){ return (n < 10 ? '0' : '') + n; }

			function render(){
				var dist = target - Date.now();
				if (dist <= 0){                       // đã tới giờ khai mạc
					$d.text('00'); $h.text('00'); $m.text('00'); $s.text('00');
					return false;
				}
				var day  = Math.floor(dist / 86400000);
				var hour = Math.floor((dist % 86400000) / 3600000);
				var min  = Math.floor((dist % 3600000) / 60000);
				var sec  = Math.floor((dist % 60000) / 1000);
				$d.text(pad(day)); $h.text(pad(hour)); $m.text(pad(min)); $s.text(pad(sec));
				return true;
			}
			function nextFixture(){
				var now = Date.now();
				for (var i = 0; i < fixtures.length; i++){
					if (new Date(fixtures[i].kickoff).getTime() > now) return fixtures[i];
				}
				return null; // đã hết trận trong danh sách
			}
			function renderNext(){
				var fx = nextFixture();
				if (!fx){
					$('#nmTime').text('Đang cập nhật lịch');
					$('#nmCountdown').text('—');
					return;
				}
				if (fx !== current){                    // chỉ cập nhật khi sang trận mới
					current = fx;
					$('#nmHome').text(fx.home);
					$('#nmAway').text(fx.away);
					$('#nmHomeFlag').attr('src', 'https://flagcdn.com/w40/' + fx.hc + '.png');
					$('#nmAwayFlag').attr('src', 'https://flagcdn.com/w40/' + fx.ac + '.png');
					var k = new Date(fx.kickoff);
					$('#nmTime').text(pad(k.getHours()) + ':' + pad(k.getMinutes()) + ' · ' + pad(k.getDate()) + '/' + pad(k.getMonth() + 1));
				}
				var t = parts(new Date(fx.kickoff).getTime() - Date.now());
				$('#nmCountdown').text((t.d > 0 ? t.d + 'n ' : '') + pad(t.h) + ':' + pad(t.m) + ':' + pad(t.s));
			}

			renderNext();
			var timer = setInterval(function(){
				if (renderNext() === false) clearInterval(timer);
			}, 1000);
		});
	</script>
{/literal}
{else}
	<div class="wc-stage wc-2026">
	  <!-- ================= BANNER ================= -->
	  <div class="wc-banner" role="region" aria-label="Dự đoán World Cup 2026" style="background: url('{$URL_IMAGES}/bg_worldcup.png');background-size: auto 100%;
	background-position: center;">

		<!-- Nền + hiệu ứng -->
		<div class="wc-bg" aria-hidden="true"></div>
		<svg class="wc-sparkle" viewBox="0 0 960 320" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
		  <defs>
			<filter id="wcBlur" x="-50%" y="-50%" width="200%" height="200%">
			  <feGaussianBlur stdDeviation="1.1"/>
			</filter>
		  </defs>
		  <g fill="#f6c945" filter="url(#wcBlur)">
			<circle cx="70"  cy="55"  r="3"   opacity=".55"/>
			<circle cx="150" cy="120" r="2"   opacity=".40"/>
			<circle cx="40"  cy="200" r="2.4" opacity=".35"/>
			<circle cx="210" cy="40"  r="1.8" opacity=".45"/>
			<circle cx="300" cy="90"  r="2.6" opacity=".30"/>
			<circle cx="120" cy="270" r="2"   opacity=".30"/>
			<circle cx="640" cy="50"  r="2.4" opacity=".45"/>
			<circle cx="720" cy="120" r="3"   opacity=".40"/>
			<circle cx="880" cy="70"  r="2"   opacity=".50"/>
			<circle cx="820" cy="210" r="2.6" opacity=".32"/>
			<circle cx="910" cy="160" r="1.8" opacity=".40"/>
			<circle cx="560" cy="250" r="2"   opacity=".30"/>
			<circle cx="480" cy="30"  r="1.6" opacity=".35"/>
			<circle cx="760" cy="280" r="2.2" opacity=".28"/>
		  </g>
		</svg>

		<!-- Badge MỚI -->
		<span class="wc-new">MỚI</span>

		<div class="wc-inner">

		  <!-- CỘT TRÁI: tiêu đề + đếm ngược -->
		  <div class="wc-col wc-col--left">
			<div class="wc-title">
			  <span class="wc-title__sub">DỰ ĐOÁN</span>
			  <span class="wc-title__main">WC 2026</span>
			  <span class="wc-title__tag">DỰ ĐOÁN HAY – RINH QUÀ KHỦNG</span>
			</div>
			<div class="wc-cd">
				<div class="wc-cd__head text-upper">KHAI MẠC CÒN ...</div>
				<div class="wc-cd__body">
					<div class="wc-cd__cell">
						<span class="wc-cd__num" id="cdDays">00</span>
						<span class="wc-cd__lbl">Ngày</span>
					</div>
					<span class="wc-cd__sep">:</span>
					<div class="wc-cd__cell">
						<span class="wc-cd__num" id="cdHours">00</span>
						<span class="wc-cd__lbl">Giờ</span>
					</div>
					<span class="wc-cd__sep">:</span>
					<div class="wc-cd__cell">
						<span class="wc-cd__num" id="cdMins">00</span>
						<span class="wc-cd__lbl">Phút</span>
					</div>
					<span class="wc-cd__sep">:</span>
					<div class="wc-cd__cell">
						<span class="wc-cd__num" id="cdSecs">00</span>
						<span class="wc-cd__lbl">Giây</span>
					</div>
				</div>
			</div>

		  </div>

		  <!-- CỘT PHẢI: tính năng + CTA -->
		  <div class="wc-col wc-col--right">
			<ul class="wc-feats">
			  <li class="wc-feat">
				<span class="wc-feat__ic">
				  <!-- icon: mục tiêu/dự đoán -->
				  <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="9"/>
					<circle cx="12" cy="12" r="5"/>
					<circle cx="12" cy="12" r="1.4" fill="var(--wc-gold-2)" stroke="none"/>
				  </svg>
				</span>
				<span class="wc-feat__tx">DỰ ĐOÁN TẤT CẢ<br>CÁC TRẬN ĐẤU</span>
			  </li>

			  <li class="wc-feat">
				<span class="wc-feat__ic">
				  <!-- icon: bảng xếp hạng -->
				  <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<line x1="6"  y1="20" x2="6"  y2="13"/>
					<line x1="12" y1="20" x2="12" y2="8"/>
					<line x1="18" y1="20" x2="18" y2="4"/>
				  </svg>
				</span>
				<span class="wc-feat__tx">BẢNG XẾP HẠNG<br>REAL-TIME TRÊN C-A</span>
			  </li>

			  <li class="wc-feat">
				<span class="wc-feat__ic">
				  <!-- icon: quà tặng -->
				  <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<rect x="4" y="9" width="16" height="11" rx="1.5"/>
					<path d="M3 9h18M12 9v11"/>
					<path d="M12 9C12 6 10 4 8.5 4 7 4 6.5 5.5 7.2 6.6 7.9 7.7 9.8 8.6 12 9z"/>
					<path d="M12 9C12 6 14 4 15.5 4 17 4 17.5 5.5 16.8 6.6 16.1 7.7 14.2 8.6 12 9z"/>
				  </svg>
				</span>
				<span class="wc-feat__tx">CỘNG ĐIỂM MỖI ROUND<br>NHẬN THƯỞNG HẤP DẪN</span>
			  </li>
			</ul>

			<a href="{$clsISO->getLink('worldcup')}" class="wc-cta" id="wcCta">
				DỰ ĐOÁN NGAY
				<i class="bx bx-chevron-right" ></i>
			</a>
		  </div>

		</div>
	  </div>
	  <!-- =============== /BANNER =============== -->

	</div>
	{literal}
	<script>
	$(function () {
	  /* ====== NGÀY KHAI MẠC – CHỈNH TẠI ĐÂY ====== */
	  // World Cup 2026 khai mạc 11/06/2026 (giờ VN). Đổi theo nhu cầu của bạn.
	  var target = new Date('2026-06-11T23:30:00+07:00').getTime();

	  var $d = $('#cdDays'), $h = $('#cdHours'), $m = $('#cdMins'), $s = $('#cdSecs');

	  function pad(n){ return (n < 10 ? '0' : '') + n; }

	  function render(){
		var dist = target - Date.now();
		if (dist <= 0){                       // đã tới giờ khai mạc
		  $d.text('00'); $h.text('00'); $m.text('00'); $s.text('00');
		  return false;
		}
		var day  = Math.floor(dist / 86400000);
		var hour = Math.floor((dist % 86400000) / 3600000);
		var min  = Math.floor((dist % 3600000) / 60000);
		var sec  = Math.floor((dist % 60000) / 1000);
		$d.text(pad(day)); $h.text(pad(hour)); $m.text(pad(min)); $s.text(pad(sec));
		return true;
	  }

	  render();
	  var timer = setInterval(function(){
		if (render() === false) clearInterval(timer);
	  }, 1000);
	});
	</script>
	{/literal}
{/if}

