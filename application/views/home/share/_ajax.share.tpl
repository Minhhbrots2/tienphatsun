<div class="modal-dialog modal-fullscreen">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Tạo mới</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row">
				<div class="col-12 bg-lightest h-100 col-md-3">
					
				</div>
				<div class="col-12 h-100 col-md-9">
					<div class="d-flex justify-content-center">
						<div id="html_content_{$uid}" class="box_images">
							<img class="fh_bg img-responsive img-fluid" src="{$URL_IMAGES}/backgrounds/z5176451533507_adef7452ae396db5f0342d5605bd4a94.jpg" />
							<img class="fh_avatar" src="{$clsProfile->getAvatar($profile_id, $oneProfile, 274,274)}" />
							<div class="fh_text">
								<div class="fh_line_1">Nguyễn Hồng Sơn</div>
								<div class="fh_line_2">Giám đốc dự án Masteri</div>
								<div class="fh_line_3">Phòng kinh doanh - FH09</div>
								<div class="fh_line_4">Giao dịch thành công U38-35XX</div>
								<div class="fh_line_5">Dự án Masteri Waterfont</div>
								<div class="fh_line_6">Doanh số: 3 tỷ</div>
							</div>
						</div>
					</div>
					{literal}
					<style type="text/css">
						.box_images{ 
							width: 600px; 
							position: relative;
						}
						.box_images > .fh_avatar{
							position: absolute;
							left: 50%; top:210px;
							transform: translateX(-50%); 
							border-radius: 50%;
							-moz-border-radius: 50%;
							-webkit-border-radius: 50%;
							-khtml-border-radius: 50%;
						}
						.fh_text{
							width: 80%;
							position: absolute;
							left: 50%; top:500px;
							transform: translateX(-50%); 
							text-align: center;
							font-weight: bold;
							font-family: "Roboto Condensed", serif;
    						font-weight: 500;
						}
						.fh_line_1{
							color:black;
							font-size: 24px;
							margin-bottom: 20px;
							text-transform: uppercase;
						}
						.fh_line_2,
						.fh_line_3,
						.fh_line_5,
						.fh_line_6{
							color:#edc949;
							font-size: 20px;
							text-transform: uppercase;
							line-height: 32px;
						}
						.fh_line_4{
							color:#edc949;
							font-size: 28px;
							text-transform: uppercase;
						}
						.fh_line_5{
							margin-bottom: 22px;
						}
					</style>
					{/literal}
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" onClick="$Core.share.do_gen_image(this, event)" uid="{$uid}" class="btn btn-primary">Tạo ảnh</button>
		</div>
	</form>
</div>