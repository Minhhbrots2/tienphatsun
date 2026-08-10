{$scriptJS}
<div class="container-xxl flex-grow-1 container-p-y pt-5">
	<div class="row">
		<div class="col-12 col-xxl-8 mb-4">
			<div class="box_header text-center mb-4 mx-auto" style="max-width:600px">
				<h1 class="title fs-30 animate fadeInUp text-main fw-bold">{$oneLuckyWheel.title}</h1>
				<div class="content fs-16">{$oneLuckyWheel.description|nl2br}</div>
			</div>
			<div class="mx-auto position-relative" style="background-image:url('/application/themes/images/bg_wheel.png');width: fit-content;background-color: transparent;background-repeat: no-repeat;background-size: cover;background-position: 50%;padding: 10px;" id="wheel_spin">
				<div class="wheelOfFortune mx-auto{if $max_spin_per_user eq '0'} pe-none{/if}">
					<canvas id="wheel" width="500" height="500"></canvas>
					<div id="spin">
						<span class="position-relative">Quay</span>
					</div>
				</div>
				<div class="d-flex align-items-center justify-content-center position-absolute left-0 top-0 w-100 h-100 rounded-pill no_spin_message{if $max_spin_per_user gt '0'} d-none{/if}" style="background:#0000009c">
					<span class="d-flex align-items-center justify-content-center text-white text-upper">Bạn đã hết lượt quay</span>
				</div>
			</div>
			<div class="d-flex justify-content-center mt-3">
				<div class="btn rounded-pill bg-black-300 text-center text-white text-fs-16">
					Bạn còn <span class="text-danger fw-bold max_spin_per_user">{$max_spin_per_user}</span> lượt
				</div>
			</div>
		</div>
		<div id="prize_sidebar" class="col-12 col-xxl-4">
			<div class="ranking-dept rounded-2">
				<div class="ranking-dept-header mb-3">
					<div class="d-flex align-items-center justify-content-center">
						<div class="ranking-title ext text-yellow">🎁 Danh sách nhận quà</div>
					</div>
				</div>
				<div class="ranking-dept-body">
					<div class="holder_lucky_wheel_spinner overflow-y-auto" style="max-height: 500px">
						{section name=i loop=$list_preloaders max=10}
						<div class="d-flex align-items-center justify-content-between py-1/5 px-2 bg-white-100 rounded-2 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<div class="avatar avatar-xs rounded-pill animate-bg"></div>
								<div class="d-flex text-white flex-column gap-0">
									<h4 class="text-fs-13 mb-0">
										<div class="animate-bg w-px-100 rounded-pill h-px-15"></div>
									</h4>
								</div>
							</div>
							<div class="d-flex gap-2 text-white text-center align-items-center">
								<div class="d-flex justify-content-center text-center w-px-40">
									<div class="animate-bg w-px-30 rounded-pill h-px-15"></div>
								</div>
								<div class="d-flex justify-content-center text-center w-px-90">
									<div class="animate-bg w-px-50 rounded-pill h-px-15"></div>
								</div>
							</div>
						</div>
						{/section}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	function pickByChance(items) {
		// Tính tổng trọng số (phòng khi bạn không chuẩn hóa về 1)
		const total = items.reduce((sum, item) => sum + item.weight, 0);
		// Lấy số ngẫu nhiên trong khoảng [0, total)
		let r = Math.random() * total;
		// Duyệt qua từng phần tử, trừ dần trọng số
		for (let item of items) {
			if (r < item.weight) {
			  return item; // chọn phần tử này
			}
			r -= item.weight;
		}
		// fallback - tránh lỗi do làm tròn
		return items[items.length - 1];
	}
	const canvas = document.getElementById('wheel');
	const spinEl = document.querySelector('#spin')
	const ctx = canvas.getContext('2d');
	const W = canvas.width, H = canvas.height;
	const cx = W/2, cy = H/2;
	const R = Math.min(W,H)/2;
	const outerTextRadius = R * 0.85;   // nơi chữ cong bám theo
	const iconRadius = R * 0.65;        // nơi icon nằm
	const arc = (2*Math.PI) / sectors.length;
	/* preload ảnh -> trả về map index->Image */
	function preloadImages(list){
		const promises = list.map(s => new Promise((res, rej) => {
			const img = new Image();
			img.crossOrigin = 'anonymous';
			img.onload = () => res(img);
			img.onerror = () => { console.warn('image load fail', s.image); res(null); };
			img.src = s.image;
		}));
	  return Promise.all(promises);
	}
	/* Hàm vẽ text theo cung tròn, và đảm bảo text luôn hướng ra ngoài đọc được */
	function drawArcTextCentered(text, radius, startAngle, sweepAngle){
		// compute widths per char -> angle per char = charWidth / radius
		const chars = Array.from(text);
		ctx.save();
		ctx.font = "bold 18px 'Segoe UI', Roboto, Arial, sans-serif";
		// measure total angular width
		let totalAngular = 0;
			const charWidths = chars.map(ch => {
			const w = ctx.measureText(ch).width;
			const angChar = w / radius;
			totalAngular += angChar;
			return {ch, w, angChar};
		});
		// center the text in the arc: compute starting angle offset
		const midOfArc = startAngle + sweepAngle / 2;
		let start = midOfArc - totalAngular/2;
		// draw each char along the circle
		for(let i=0;i<charWidths.length;i++){
			const info = charWidths[i];
			const charAngle = start + info.angChar/2; // center angle for this char
			const x = cx + Math.cos(charAngle) * radius;
			const y = cy + Math.sin(charAngle) * radius;

			ctx.save();
			ctx.translate(x,y);
			// rotate so character tangent to circle and facing outward:
			// rotate by (charAngle + 90deg). If on bottom half (angle between PI/2 and 3PI/2),
			// flip 180deg so characters remain upright.
			let rotation = charAngle + Math.PI/2;
			if (charAngle > Math.PI/2 && charAngle < 3*Math.PI/2) {
				rotation += Math.PI; // flip
			}
			ctx.rotate(rotation);
			// draw char centered at (0,0) but vertical offset because we rotated
			ctx.fillStyle = "#FFF"; // dark color for contrast, adjust as needed
			ctx.fillText(info.ch, 0, 0);
			ctx.restore();
			start += info.angChar;
		}
		ctx.restore();
	}
	/* Vẽ bánh, icons và chữ */
	function drawWheel(angleOffset){
		ctx.clearRect(0,0,W,H);
		// draw sectors
		sectors.forEach((s,i) => {
		const start = i*arc + angleOffset;
		const end = start + arc;
		// sector fill
		ctx.beginPath();
		ctx.moveTo(cx,cy);
		// ctx.arc(cx,cy, R*0.86, start, end, false);
		ctx.arc(cx, cy, R, start, end, false);
		ctx.closePath();
		ctx.fillStyle = s.color;
		ctx.fill();

		// inner icon (preloaded separately)
		const mid = (start+end)/2;
		const ix = cx + Math.cos(mid) * iconRadius;
		const iy = cy + Math.sin(mid) * iconRadius;
		const img = preloadedImages[i];
		if(img){
			const size = Math.floor(R*0.14);
			ctx.save();
			ctx.translate(ix - size/2, iy - size/2);
			ctx.drawImage(img, 0, 0, size, size);
			ctx.restore();
		}
		// outer curved text (we will draw uppercase and trim optional)
		const text = s.label;
		// drawArcTextCentered(text, radius, startAngle, sweep);
		drawArcTextCentered(text, outerTextRadius, start, arc);
	  });
	}
	/* Spin logic */
	let ang = Math.floor(Math.random() * 360) + 1;
	let angVel = 0;
	let spinning = false;
	let preloadedImages = [];
	const friction = 0.993;
	function animate(){
		ang += angVel;
		ang %= (2*Math.PI);
		angVel *= friction;
		if (angVel < 0.0005 && spinning){
			angVel = 0;
			spinning = false;
			// determine result
			const deg = (ang * 180/Math.PI + 90) % 360;
			let idx = Math.floor(sectors.length - deg/(360/sectors.length)) % sectors.length;
			if(idx<0) idx += sectors.length;
			const res = sectors[idx];
			$Core.lucky_wheel.spinner(res);
			// setTimeout(()=> alert('🎉 Bạn trúng: ' + res.label), 250);
		}
		drawWheel(ang);
		requestAnimationFrame(animate);
	}
	/* button */
	document.getElementById('spin').addEventListener('click', ()=>{
		if(spinning) return;
		// pick server result? here random demo
		// spinning = true;
		const picked = pickByChance(sectors);
		const index = sectors.indexOf(picked);
		const spins = 5 + Math.floor(Math.random()*3); // quay vài vòng
		const targetAngle = (Math.PI*2*spins) - (index * arc + arc/2) + Math.PI/2;
		angVel = 0.6 + Math.random()*0.3;
		targetAng = targetAngle;
		spinning = true;
	});
	/* start after images loaded */
	let preloading = preloadImages(sectors).then(imgs=>{
	  preloadedImages = imgs;
	  // set text style globally
	  ctx.textBaseline = 'middle';
	  ctx.textAlign = 'center';
	  ctx.font = "bold 18px 'Segoe UI', Roboto, Arial, sans-serif";
	  drawWheel(0);
	  animate();
	});
</script>
<style>
	.ranking-dept {
		width: 100%;
		background: linear-gradient(-45deg, #20040a, #f5d3e1, #ad0246);
		background-size: 400% 400%;
		-webkit-animation: gradient 15s ease infinite;
		animation: gradient 15s ease infinite;
		position: relative;
		padding: 30px 30px 20px 30px;
	}
	.ranking-title.ext {
		font-size: 20px;
		line-height: 24px;
		text-transform: uppercase;
	}
	@media screen and (max-width:1400px){
		.ranking-dept{
			padding: 20px 1.05rem 20px 1.05rem !important;
		}
		.ranking-title.ext{
			font-size:16px;
		}
	}
	@media screen and (max-width:575px){
		.ranking-dept{ 
			padding:20px 1.05rem !important;
		}
		.ranking-title.ext{
			font-size:16px;
		}
	}
</style>
{/literal}