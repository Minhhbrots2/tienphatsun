<div class="subscribe">
	<h3 class="o-text__heading-3">Đăng ký nhận bản tin</h3>
	<p>Để nhận thông tin mới nhất và tham gia các CTKM hàng tuần của Martens, vui lòng đăng ký email tại đây</p>
	<div class="subscribe-form mt-4">
		<form method="post" action="" accept-charset="UTF-8" enctype="multipart/form-data" class="subscribe-form" >
			<div class="form-group">   
				<input type="text" class="form-control required" name="name" data-val-required="Vui lòng nhập họ tên!" placeholder="{$core->get_Lang('YourName')}">
			</div>
			<div class="form-group">   
				<input type="email" class="form-control valid required" name="email" pattern="{literal}^([a-zA-Z0-9_\-\.\+]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)${/literal}" placeholder="{$core->get_Lang('YourEmail')}" data-val-regex="E-mail không đúng định dạng!" data-val-required="Vui lòng nhập e-mail!" />
			</div>
			<div class="form-group">   
				<button type="button" onclick="subscribe(this); return false;" t1="{$core->get_Lang('Processing')}" t1="{$core->get_Lang('Register')}" class="btn btn-warning pull-right">{$core->get_Lang('Register')}</button>
			</div>
		</form>
	</div>
</div>
{literal}
<script type="text/javascript">
	function subscribe(_this){
		var $_this = $(_this);
		if(!$_this.hasClass('clicked')){
			var _validated = 0,
				_form = $_this.closest('form');
			if($('input.required', _form).length){
				$('input.required', _form).each(function(){
					var __this = $(this);
					if($Core.util.isEmpty(__this.val())){
						_validated++;
						__this.focus();
						$.alert({title: 'Thông báo!', content: __this.data('val-required')});
						return false;
					} else if(__this.hasClass('valid')){
						var pattern = __this.attr('pattern'),
							regex = new RegExp(pattern);
						if(!regex.test(__this.val())){
							_validated++;
							__this.focus();
							$.alert({title: 'Thông báo!', content: __this.data('val-regex')});
							return false;
						}
					}
				});	
			}
			if(parseInt(_validated) == 0){
				_form.ajaxSubmit({
					type: 'POST',
					url: path_ajax_script+'/subscribe.cfg',
					dataType:'html',
					success: function(html){
						$_this.val('Đăng ký');
						if(html.indexOf('err_email_empty') >= 0){
							$('input[nam=email]', _form).focus();
							$.alert({title: 'Thông báo!', content: 'Bạn chưa nhập vào email cần đăng ký.'});
						}else if(html.indexOf('err_email_exist') >= 0){
							$('input[nam=email]', _form).focus();
							$.alert({title: 'Thông báo!', content: 'Địa chỉ email này thực sự đã được đăng ký.'});
						}else if(html.indexOf('reg_error') >= 0){
							$('input[nam=email]', _form).focus();
							$.alert({title: 'Thông báo!', content: 'Hệ thống đang quá tải. Vui lòng thử lại'});
						}else{
							$('input[nam=name]', _form).val('');
							$('input[nam=email]', _form).val('');
							$.alert({title: 'Đăng ký thành công!',content:'Quá trình đăng ký đã hoàn tất.'});
						}
					}
				});
			}
		}
		return false;
	}
</script>
{/literal}