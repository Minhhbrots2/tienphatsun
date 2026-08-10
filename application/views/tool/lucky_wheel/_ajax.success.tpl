<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content popup">
		<div class="modal-header">
			<button type="button" class="btn btn-icon position-absolute text-white btn_close" data-bs-dismiss="modal" aria-label="Close"><i class='bx bx-x fs-24'></i></button>
		</div>
		<div class="modal-body p-0"> 
			<h1 class="text-center mb-4">💖 Chúc Mừng ngày 20/10 💖</h1>
			<img src="{$onePrize.image}" height="150" class="">
			<h2 class="fs-20 lh-base mt-3">Chúc mừng bạn đã nhận được {$onePrize.prize_name}</h2>
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
