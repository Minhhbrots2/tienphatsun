<div class="ranking-club rounded-2{if $deviceType ne 'phone'} xs:mt-2{/if} mb-2">
	<div class="ranking-header">
		<div class="text-center text-white fs-4 fw-bold py-2">
			<span>{$smarty.now|date_format:"%H:%M"}</span>
			<span>{$smarty.now|date_format:"%d/%m"}</span>
		</div>
	</div>
	<div class="text-center mb-4">
		<h2 class="ranking-title mb-2 text-yellow">BẢNG XẾP HẠNG</h2>
		<p class="text-white">Từ 1/7/2025 - 30/09/2025</p>
	</div>
	<div class="d-flex gap-1 justify-content-end align-items-center mb-2">
		<div class="w-px-40 text-upper text-white text-center text-fs-12">Số TV</div>
		<div class="w-px-40 text-upper text-white text-center text-fs-12">Số GD</div>
		<div class="w-px-80 text-upper text-white text-center text-fs-12">Doanh số</div>
	</div>
	<div class="ranking-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_ranking_club">
		<div class="ranking-item d-flex fw-bold align-items-center justify-content-between w-100 h-px-40 mb-2">
			<div class="ranking-text d-flex align-items-center gap-1 text-white">
				<img src="{$URL_IMAGES}/icons/logo-future-starter.png" class="w-px-25" />
				<span>Starters</span>
			</div>
			<div class="d-flex gap-1 align-items-center">
				<div class="w-px-40 text-center">0</div>
				<div class="w-px-40 text-center">0</div>
				<div class="w-px-80 text-center">0.00 tỷ</div>
			</div>
		</div>
		<div class="ranking-item d-flex fw-bold align-items-center justify-content-between w-100 h-px-40">
			<div class="ranking-text d-flex align-items-center gap-1 text-white">
				<img src="{$URL_IMAGES}/icons/logo-future-diamond.png" class="w-px-25" />
				<span>Diamonds</span>
			</div>
			<div class="d-flex gap-1 align-items-center">
				<div class="w-px-40 text-center">0</div>
				<div class="w-px-40 text-center">0</div>
				<div class="w-px-80 text-center">0.00 tỷ</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.ranking-club{
		width:100%;
		background: linear-gradient(-45deg, var(--rank-1), var(--rank-2), #000, var(--rank-3));
		background-size: 400% 400%;
		-webkit-animation: gradient 15s ease infinite;
		animation: gradient 15s ease infinite;
		position:relative;
		padding:80px 30px 50px 30px;
	}
	.ranking-header{
		position:absolute;
		top:0; left:30%;
		width:40%;
		height:50px;
		background:var(--rank-3);
	}
	.ranking-header:before{
		content: "";
		position: absolute;
		left: -30px;
		bottom: 0;
		width: 0px;
		height: 0px;
		border-right: 30px solid var(--rank-3);
		border-left: 0px solid transparent;
		border-bottom: 50px solid transparent;
	}
	.ranking-header:after{
		content: "";
		position: absolute;
		right: -30px;
		bottom: 0;
		width: 0px;
		height: 0px;
		border-left: 30px solid var(--rank-3);
		border-right: 0px solid transparent;
		border-bottom: 50px solid transparent;
	}
	.ranking-title {
		font-size: 35px;
		font-weight: bold;
		font-family:'UTM_Avo_Bold', Arial, San-serif;
	}
	.ranking-item{
		position:relative;
		overflow: hidden;
		background:var(--bs-white);
	}
	.ranking-item .ranking-text{
		width:60%;
		height:100%;
		padding: 10px;
		flex: 60% 0 0;
		position:relative;
		font-size: 14px;
		line-height: 20px;
		background:var(--rank-gold);
		text-transform: uppercase;
	}
	.ranking-body .ranking-item__1 .ranking-text{
		background:var(--rank-gold);
	}
	.ranking-body .ranking-item__11 .ranking-text{
		background:var(--rank-accent);
	}
	.ranking-item .ranking-text:after{
		content:"";
		position:absolute;
		right:0; top:0;
		width:0px;
		height:0px;
		border-right: 20px solid var(--bs-white);
		border-top: 40px solid transparent;
		border-bottom: 0px solid transparent;
	}
	@media screen and (max-width:1600px){
		.ranking-club{
			padding:80px 20px 30px 20px;
		}
		.ranking-title{
			font-size:28px;
		}
		.ranking-item .ranking-text{
			width:42%;
			flex: 42% 0 0;
			font-size:12px;
			line-height:12px;
		}
	}
	@media screen and (max-width:575px){
		.ranking-club{
			padding:80px 20px 30px 20px;
		}
		.ranking-item .ranking-text{
			width:50%;
			flex: 50% 0 0;
			font-size:11px;
			line-height:12px;
		}
	}
	@media screen and (max-width:375px){
		.ranking-club{
			padding:80px 20px 30px 20px;
		}
		.ranking-item .ranking-text{
			width:45%;
			flex: 45% 0 0;
			font-size:11px;
			line-height:12px;
			padding-top:8px;
		}
	}
	@-webkit-keyframes gradient {
		0% {
			background-position: 0% 50%;
		}
		50% {
			background-position: 100% 50%;
		}
		100% {
			background-position: 0% 50%;
		}
	}
	@keyframes gradient {
		0% {
			background-position: 0% 50%;
		}
		50% {
			background-position: 100% 50%;
		}
		100% {
			background-position: 0% 50%;
		}
	}
</style>