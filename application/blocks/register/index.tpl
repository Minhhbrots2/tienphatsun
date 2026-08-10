{literal}
<style type="text/css">
    .reg{
        border-radius: 5px;
        -moz-radius: 5px;
        -webkit-radius: 5px;
        -khtml-radius: 5px;
        overflow: hidden;
    }
	.reg > .title{
		background:#f54337;
		text-align:center;
		padding:15px 10px; 
		color:#FFF; 
		font-size:20px;
		text-transform:uppercase;
		margin:0;
	}
	.reg > .register-buy > .body{
		padding:30px;
		background:#c5c5c5 !important;
	}
	.form-control {
		height:38px;
		border-radius:2px;
		-moz-border-radius:2px;
		-webkit-border-radius:2px;
		-khtml-border-radius:2px;
	}
	.reg .btn{
		width:100%;
		background:#fead00;
		text-align:center;
		color:#FFF;
		padding:6px;
		outline:none;
		cursor:pointer;
		margin:0;
	}
</style>
{/literal}
<div class="reg">
	<h2 class="title">Đặt mua sản phẩm</h2>
	<div class="register-buy">
		<div class="body">
			<form method="post" action="" enctype="multipart/form-data">
				<div class="form-group">
					<input type="text" name="name" class="form-control" placeholder="Họ và tên" />
				</div>
				<div class="form-group">
					<input type="text" name="phone" class="form-control" placeholder="Số điện thoại" />
				</div>
				<div class="form-group">
					<select class="form-control" name="register_type">
						{$clsProperty->makeListOption('223','_REGISTER_TYPE',0,false)}
					</select>
				</div>
				<div class="form-group">
					<textarea class="form-control" name="message" placeholder="Mô tả ngắn" rows="6"></textarea>
				</div>
				<div class="alert-success alert mfp-hide">
					<strong>Đăng ký thành công</strong><br />
					<p>Đội nghũ chăm sóc khách hàng của chúng tôi sẽ liên hệ với quý khách muộn nhất trong 24h tới.<br /><br />
					{$PAGE_NAME} xin cảm ơn quý khách.</p>
				</div>
				<div class="form-group mb-0">
					<input type="hidden" name="hid" value="register" />
					<button type="button" class="btn" onclick="buy(this); return false;">Đăng ký ngay</button>
				</div>
			</form>
			
		</div>
	</div>
</div>