<div class="main-content container">
	<div class=" pd5  ">
        <ol class="breadcrumb breadcrumb-arrows">
            <li><a href="/" target="_self">Trang chủ</a></li>
            <li><a href="/profile.html">Trang cá nhân</a></li>
            <li class="active"><span>Thông tin tài khoản</span></li>
        </ol>
    </div>
    <div class="member-content">
        <div class="row">
        	<div class="col-lg-3 col-md-3 col-xs-12">
            	{$core->getBlock('member_left_profile')}
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12">
            	<div class="box-title-collection m-b-15 clearfix">
                	<div class="box-title-collection__title">
                    	<h1><img style="vertical-align:0px" align="absmiddle" src="../../../application/modules/member/{$URL_IMAGES}/member/account.png" /> Thông tin tài khoản</h1>
                    </div>
                </div>
                <div class="holder_box">
                	<div class="form-group">
                    	<h3 class="h3bold"><img align="absmiddle" src="../../../application/modules/member/{$URL_IMAGES}/member/customer_name.png" /> Thông tin tài khoản</h3>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Địa chỉ email:</label> {$clsProfile->getEmail($profile_id)}
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Mật khẩu:</label> <a href="/doi-mat-khau.html" title="Đổi mật khẩu">Đổi mật khẩu</a>
                    </div>
                    <div class="form-group">
                    	<h3 class="h3bold">Thông tin cá nhân <a href="/edit-profile.html" class="edit">Sửa</a></h3>
                     </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Tên đầy đủ:</label> {$clsProfile->getName($profile_id)}
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Số điện thoại:</label> {$clsProfile->getPhone($profile_id)}
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Giới tính:</label> {$clsProfile->getSex($profile_id)}
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Địa chỉ:</label> {$clsProfile->getAddress($profile_id)}
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Tỉnh/ Thành phố:</label> {$clsProfile->getCityName($profile_id)}
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Quận/ Huyện:</label> {$clsProfile->getDistrictName($profile_id)}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{literal}
<script type="text/javascript">
	$().ready(function(){
		$('#frm-confirm').validate();
	});
</script>
{/literal}