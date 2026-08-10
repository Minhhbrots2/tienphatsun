<div class="main-content container">
	<div class=" pd5  ">
        <ol class="breadcrumb breadcrumb-arrows">
            <li><a href="/" target="_self">Trang chủ</a></li>
            <li><a href="/profile.html">Trang cá nhân</a></li>
            <li class="active"><span>Đổi mật khẩu</span></li>
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
                    	<h1>Đổi mật khẩu</h1>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="holder_box">
                	<div class="form-group">
                        <div class="info_msg_box mb10">
                            <b>Ghi chú</b><br />
                            • Mật khẩu phải có ít nhất 6 ký tự.<br />
                            • Gần đây có nhiều trường hợp bị chiếm đoạt tài khoản sử dụng vào mục đích vi phạm pháp luật. Do vậy:<br />
                            • Bạn không được đặt mật khẩu quá đơn giản, dễ đoán (vd: 123456,...)<br />
                            • Không được trùng với Tên truy cập, Email, Điện thoại cố định, Điện thoại di động
                        </div>
                    </div>
                    <form method="post" action="" name="frm-change-pwd" id="frm-change-pwd">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Mật khẩu cũ:</label>
                            <div class="col-sm-9">
                            	<input type="password" name="old_pass" value="{$old_pass}" class="form-control txt280" />
                                {if $err_old_pass ne ''}
                                <div><div class="herror">{$err_old_pass}</div></div>
                                {/if}
                            </div>      
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Mật khẩu mới:</label>
                            <div class="col-sm-9">
                                <input type="password" name="new_pass" value="{$new_pass}" class="form-control txt280" />
                                {if $err_new_pass ne ''}
                                <div><div class="herror">{$err_new_pass}</div></div>
                                {/if}
                            </div> 
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Nhập lại mật khẩu mới:</label>
                            <div class="col-sm-9">
                                <input type="password" name="new_cpass" value="{$new_cpass}" class="form-control txt280" />
                                {if $err_new_cpass ne ''}
                                <div><div class="herror">{$err_new_cpass}</div></div>
                                {/if}
                            </div> 
                        </div>
                        <div class="form-group mt10">
                            <label class="control-label col-sm-3">&nbsp;</label>
                            <div class="col-sm-9">
                                <button type="submit" class="memberBtn">Thay đổi</button>
                                <input type="hidden" name="submit" value="pwd" />
                             </div>
                        </div>
                    </form>
                    {literal}
                    <script type="text/javascript">
                    $().ready(function(){
                        $('#frm-change-pwd').validate();
                    });
                    </script>
                    {/literal}
                </div>
            </div>
        </div>
    </div>
</div>