<div class="container">
    <div class="member-content">
        <div class="row">
        	<div class="col-lg-4 col-md-4 col-md-offset-2">
            	{$core->getBlock('member_benefit')}
            </div>
            <div class="col-lg-4 col-md-4 col-md-offset-2">
                <div class="member_box">
                	<h2 class="headPage">{if $message eq 'register'}Đăng ký thành công !{else}Khôi phục mật khẩu thành công !{/if}</h2>
                    <div class="member_in_box">
                    	<div class="formatText success_box">
                        	{if $message eq 'register'}
                            <h2>Đăng ký thành công tài khoản trên {$PAGE_NAME}</h2>
                            <p>Sau khi nhận được thông tin đăng ký của bạn.<br />
                            Chúng tôi gửi thông tin đăng ký của bạn đến mail.<br /><br />
                            Xin vui lòng kiểm tra email của bạn & làm theo hướng dẫn để kích hoạt tài khoản!
                            </p>
                            {else}
                            Cảm ơn bạn, hướng dẫn lấy lại mật khẩu đã được gửi đến email của bạn. 
                            <br /><br />
                            Xin vui lòng kiểm tra và sử dụng thư điện tử để xử lý các bước tiếp theo.
                            <br /><br />
                            Là một thành viên đã đăng ký trên {$PAGE_NAME}, bạn có quyền truy cập miễn phí không giới hạn các tính năng nổi bật của hệ thống !
                            <br /><br />
                            {/if}
                            <p class="mt5"><a href="{$PCMS_URL}" title="Trang chủ">&laquo; Quay lại trang chủ</a></p>
                            <div class="liner mt10 noteTxt">Chú ý: Bạn nên kiểm tra bulk hoặc spam nếu không nhận được email của chúng tôi.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>