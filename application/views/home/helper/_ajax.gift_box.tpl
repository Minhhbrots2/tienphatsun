<div class="modal-dialog modal-ipad modal-dialog-centered">
	<div class="modal-content popup">
		<div class="modal-header">
			<button type="button" class="btn btn-icon position-absolute text-white btn_close" data-bs-dismiss="modal" aria-label="Close"><i class='bx bx-x fs-24'></i></button>
		</div>
		<div class="modal-body p-0"> 
			<div class="d-flex justify-content-center mb-2">
				<img src="{$URL_IMAGES}/giftbox.png" class="w-px-{if $deviceType eq 'phone'}100{else}150{/if} fadeInUp" />
			</div>
			<h1 class="text-center fleur-de-leah-regular mb-3">💖 Chúc Mừng ngày Phụ Nữ Việt Nam 20/10 💖</h1>
			<h2 class="text-center lobster-regular text-fs-30 mb-4">
				<span class="text-fs-16">{$clsProperty->getTitle($oneProfile.role_id)}</span>
				{$clsProfile->getFullName($profile_id, $oneProfile)}
			</h2>
			<img src="{$oneLuckyPrize.image}" height="150" class="fadeInUp">
			<h2 class="text-fs-30 birthstone-regular fadeInUp mt-2">Chúc mừng bạn! Bạn đã nhận được {$oneLuckyPrize.prize_name}</h2>\
			<canvas id="fireworks"></canvas>
		</div>
	</div>
</div>
<style>
	.popup::before {
		content: "";
		position: absolute;
		inset: -2px;
		border-radius: 22px;
		background: linear-gradient(45deg, #ff80ab, #ff4081, #f50057, #ff80ab);
		z-index: -1;
		filter: blur(6px);
		animation: glow 5s linear infinite;
	}
	.popup {
		position: relative;
		background: radial-gradient(circle at top, #ffb6c1, #ff4081);
		color: white;
		border-radius: 20px;
		padding: 50px 30px;
		text-align: center;
		box-shadow: 0 0 40px rgba(255,105,180,0.6);
		overflow: hidden;
		animation: zoomIn 0.6s ease;
	}
	/* Tiêu đề */
	.popup h1 {
		font-size: 32px;
		margin-bottom: 10px;
		text-shadow: 0 0 10px rgba(255,255,255,0.7);
	}
	.btn_close {
		right: 15px;
		top: 15px;
	}
	.btn_close:hover {
		background: #ffffff12;
		box-shadow: 0px 0px 4px;
	}
</style>
<script type="text/javascript">
	const canvas = document.getElementById("fireworks");
	const ctx = canvas.getContext("2d");
	let particles = [];
	function resizeCanvas() {
	  canvas.width = window.innerWidth;
	  canvas.height = window.innerHeight;
	}
	window.addEventListener("resize", resizeCanvas);
	resizeCanvas();
	function createFirework(x, y, color) {
	  const count = 80;
	  for (let i = 0; i < count; i++) {
		const angle = (Math.PI * 2 * i) / count;
		const speed = 2 + Math.random() * 3;
		particles.push({
		  x, y,
		  vx: Math.cos(angle) * speed,
		  vy: Math.sin(angle) * speed,
		  alpha: 1,
		  color,
		  radius: 2 + Math.random() * 2
		});
	  }
	}
	function updateFireworks() {
	  ctx.clearRect(0, 0, canvas.width, canvas.height);
	  particles.forEach((p, i) => {
		p.x += p.vx;
		p.y += p.vy;
		p.vy += 0.02; // gravity
		p.alpha -= 0.015;
		if (p.alpha <= 0) particles.splice(i, 1);
		ctx.globalAlpha = p.alpha;
		ctx.fillStyle = p.color;
		ctx.beginPath();
		ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
		ctx.fill();
	  });
	  requestAnimationFrame(updateFireworks);
	}
	function launchFireworks() {
	  for (let i = 0; i < 6; i++) {
		setTimeout(() => {
		  const x = Math.random() * canvas.width;
		  const y = Math.random() * canvas.height * 0.5;
		  const colors = ["#FFD700", "#FFF8DC", "#F5DEB3", "#FFE4B5"];
		  createFirework(x, y, colors[Math.floor(Math.random() * colors.length)]);
		}, i * 400);
	  }
	  updateFireworks();
	}
	setTimeout(() => {
		 launchFireworks();
	}, 400);
</script>