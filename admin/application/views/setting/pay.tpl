<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Setting')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$core->get_Lang('Payment Config')}</h1>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="row">
					<div class="col-md-4">
						<div class="ui-annotated-section__annotation">
							<div class="ui-annotated-section__title">
								<h2 class="ui-heading">Thanh toán được chấp nhận</h2>
							</div>
							<div class="ui-annotated-section__description" id="credit-card-gateway-scroll" data-tg-refresh="credit-card-gateway-scroll">
								<p>Các hình thức thanh toán khi mua hàng</p>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="panel__container">
							<div class="panel panel-default panel-light rightContent full_width_767">
								<div class="panel-body no-space tooltipToggle payment-setting">
									<form action="#" id="form-atm" method="post" class="validate-form">    
										<div class="payment-header">
											<div class="row">
												<div class="col-md-3"><img class="slideToggle img-responsive" height="50" src="{$clsISO->getVar('PAYMENT_METHOD_MUNUAL')}"></div>
												<div class="col-md-9">
													<p>Bạn có thể cấu hình, hình thức thanh toán bằng tiền mặt trên website</p>
												</div>
											</div>
										</div>
										<div class="payment-method-setting slidedown-hidden">
											<div style="margin-bottom:0;" class="panel panel-default">
												<div class="panel-body paymentType">
													<div class="form-group">
														<div class="custom-checkbox-wrapper core-checkbox-custom">
															<label>
																<input name="SitePay_CashStatus_Mode"{if $clsConfiguration->getValue('SitePay_CashStatus_Mode') eq '1'}checked{/if} value="1" type="checkbox"> 
																<span class="custom-checkbox custom-icon"></span>
															</label> Cho phép thanh toán Tiền mặt tại quầy
														</div>
													</div>
													{assign var = SitePay_CashName value = 'SitePay_CashName_'|cat:$_LANG_ID}
													{assign var = SitePay_CashDesc value = 'SitePay_CashDesc_'|cat:$_LANG_ID}
													<div class="form-group">
														<label class="col-form-label">{$core->get_Lang('Name Method')}</label>
														<div class="controls">
															<input bind="name" class="form-control required" data-val="true" name="iso-{$SitePay_CashName}" type="text" value="{$clsConfiguration->getValue($SitePay_CashName)}" placeholder="Tên phương thức">
														</div>
													</div>
													 <div class="form-group">
														<label class="col-form-label">{$core->get_Lang('Description')}</label>
														<div class="controls">
															<textarea id="textarea_{$SitePay_CashDesc}_{$now}" class="isoTextArea" name="iso-{$SitePay_CashDesc}" style="width:100%">{$clsConfiguration->getValue($SitePay_CashDesc)}</textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="top21">
											<input type="hidden" name="Hid_Pay1" value="Hid_Pay1" />
											<button type="button" class="btn btn-default pay_setting">{$core->get_Lang('Setting')}</button>
											<button type="button" class="btn btn-default cancel_setting" name="submit" >{$core->get_Lang('Cancel')}</button>
											<button  type="submit" class="btn btn-default save_setting">{$core->get_Lang('Save')}</button>
										</div>
									</form>        
								</div>
								<div class="panel-body no-space tooltipToggle payment-setting">
									<form action="#" id="form-atm" method="post" class="validate-form">    
										<div class="payment-header">
											<div class="row">
												<div class="col-md-3"><img class="slideToggle img-responsive" height="50" src="{$clsISO->getVar('GATEWAY_BANK_TRANFER')}"></div>
												<div class="col-md-9">
													<p>Bạn có thể cấu hình, hình thức thanh toán bằng tiền mặt trên website</p>
												</div>
											</div>
										</div>
										<div class="payment-method-setting slidedown-hidden">
											<div style="margin-bottom:0;" class="panel panel-default">
												<div class="panel-body paymentType">
													<div class="form-group">
														<div class="custom-checkbox-wrapper core-checkbox-custom">
															<label>
																<input name="SitePay_Bank_Mode"{if $clsConfiguration->getValue('SitePay_Bank_Mode') eq '1'}checked{/if} value="1" type="checkbox"> 
																<span class="custom-checkbox custom-icon"></span>
															</label> Cho phép thanh toán bằng chuyển khoản ngân hàng
														</div>
													</div>
													{assign var = SitePay_BankName value = 'SitePay_BankName_'|cat:$_LANG_ID}
													{assign var = SitePay_BankDesc value = 'SitePay_BankDesc_'|cat:$_LANG_ID}
													<div class="form-group">
														<label class="col-form-label">{$core->get_Lang('Name Method')}</label>
														<div class="controls">
															<input bind="name" class="form-control required" data-val="true"   name="iso-{$SitePay_BankName}" type="text" value="{$clsConfiguration->getValue($SitePay_BankName)}" placeholder="Tên phương thức">
														</div>
													</div>
													 <div class="form-group">
														<label class="col-form-label">{$core->get_Lang('Description')}</label>
														<div class="controls">
															<textarea id="textarea_{$SitePay_BankDesc}_{$now}" class="isoTextArea" name="iso-{$SitePay_BankDesc}" style="width:100%">{$clsConfiguration->getValue($SitePay_BankDesc)}</textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div  class="top21">
											<input type="hidden" name="Hid_Pay2" value="Hid_Pay2" />
											<button type="button" class="btn btn-default pay_setting">{$core->get_Lang('Setting')}</button>
											<button type="button" class="btn btn-default cancel_setting" name="submit" >{$core->get_Lang('Cancel')}</button>
											<button  type="submit" class="btn btn-default save_setting">{$core->get_Lang('Save')}</button>
										</div>
									</form>        
								</div>
								<div id="atm-payment-setting" class="panel-body no-space tooltipToggle payment-setting">
									<form action="#" id="form-atm" method="post" class="validate-form">    
										<div class="payment-header">
											<div class="row">
												<div class="col-md-3"><img class="slideToggle img-responsive" height="50" src="{$clsISO->getVar('GATEWAY_ONEPAY_ATM')}"></div>
												<div class="col-md-9">Hệ thống của bạn chưa cấu hình thanh toán online bằng thẻ nội địa (ATM). Để tìm hiểu thêm về OnePay và các thông tin khác vui lòng xem tại <a href="http://onepay.com.vn/" target="_blank">OnePay</a></div>
											</div>
										</div>
										<div class="payment-method-setting slidedown-hidden">
											<div style="margin-bottom:0;" class="panel panel-default">
												<div class="panel-body paymentConfig">
													<div class="form-group">
														<div class="custom-checkbox-wrapper core-checkbox-custom">
															<label>
																<input name="SitePay_Bank_Mode"{if $clsConfiguration->getValue('ONEPAY_Status_Mode') eq '1'}checked{/if} value="1" type="checkbox"> 
																<span class="custom-checkbox custom-icon"></span>
															</label> Cho phép thanh toán qua OnePay
														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">{$core->get_Lang('Name Method')}</label>
														<div class="controls">
															{assign var = ONEPAY_Name value = 'ONEPAY_Name_'|cat:$_LANG_ID}
															<input bind="name" class="form-control required" name="iso-{$ONEPAY_Name}" type="text" value="{$clsConfiguration->getValue($ONEPAY_Name)}" plcaeholder="{$core->get_Lang('Name Method')}">
														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">URL Payment</label>
														<div class="controls">
															<input bind="name" class="form-control required" data-val="true" data-val-length="Tên hiển thị tối đa 250 ký tự" data-val-length-max="250" data-val-required="Tên hiển thị không được để trống" id="Name" name="iso-ONEPAY_URL_PAYMENT" type="text"  value="{$clsConfiguration->getValue('ONEPAY_URL_PAYMENT')}" plcaeholder="Thanh toán online qua thẻ nội địa (ATM)">
														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">Merchant ID</label>
														<div class="controls">
															<input class="form-control required" data-val="true" placeholder="MerchantId không vượt quá 500 ký tự" id="Setting_MerchantId" name="iso-ONEPAY_Merchant_ID" type="text" value="{$clsConfiguration->getValue('ONEPAY_Merchant_ID')}">
														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">{$core->get_Lang('Hashcode')}</label>
														<div class="controls">
															<input class="form-control required" data-val="true"  data-val-length-max="500" placeholder="Hashcode không được để trống" id="Setting_Hashcode" name="iso-ONEPAY_Secure_Hash" type="text" value="{$clsConfiguration->getValue('ONEPAY_Secure_Hash')}">

														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">{$core->get_Lang('AccessCode')}</label>
														<div class="controls">
															<input class="form-control requrired" placeholder="AccessCode không được để trống" id="Setting_AccessCode" name="iso-ONEPAY_Access_Code" type="text" value="{$clsConfiguration->getValue('ONEPAY_Access_Code')}">
														</div>
													</div>
													<div class="form-group">
														<div class="controls">
															<label class="checkbox-inline"><input type="checkbox" name="ONEPAY_Test_Mode" {if $clsConfiguration->getValue('ONEPAY_Test_Mode') eq '1'}checked{/if} value="1" /> {$core->get_Lang('Test Mode')}
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
										<div  class="top21">
											<input type="hidden" name="Hid_Pay4" value="Hid_Pay4" />
											<button type="button" class="btn btn-default pay_setting">{$core->get_Lang('Setting')}</button>
											<button  type="button" class="btn btn-default cancel_setting">{$core->get_Lang('Cancel')}</button>
											<button type="submit" class="btn btn-default save_setting" name="submitatm" >{$core->get_Lang('Save')}</button>
										</div>
									</form>        
								</div>
								<div class="clearfix"></div>
								<div id="visa-payment-setting" class="panel-body no-space tooltipToggle payment-setting">
									<form action="#" id="form-visa" method="post" class="validate-form">    
										<div class="payment-header">
											<div class="row">
												<div class="col-md-3" ><img class="slideToggle img-responsive" height="50" src="{$clsISO->getVar('GATEWAY_ONEPAY_VISA')}"></div>
												<div class="col-md-9">Hệ thống của bạn chưa cấu hình thanh toán online bằng thẻ quốc tế (Visa/MasterCard). Để tìm hiểu thêm về OnePay và các thông tin khác vui lòng xem tại <a href="http://onepay.com.vn/" target="_blank">OnePay</a></div>
											</div>
										</div>
										<div class="payment-method-setting slidedown-hidden">
											<div style="margin-bottom:0;" class="panel panel-default">
												<div class="panel-body paymentConfig">
													<div class="form-group">
														<div class="custom-checkbox-wrapper core-checkbox-custom">
															<label>
																<input name="ONEPAY_Visa_Status_Mode"{if $clsConfiguration->getValue('ONEPAY_Visa_Status_Mode') eq '1'}checked{/if} value="1" type="checkbox"> 
																<span class="custom-checkbox custom-icon"></span>
															</label> Cho phép thanh toán qua OnePay Visa
														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">{$core->get_Lang('Name Method')}</label>
														<div class="controls">
															{assign var = ONEPAY_Visa_Name value = 'ONEPAY_Visa_Name_'|cat:$_LANG_ID}
															<input bind="name" class="form-control required" name="iso-{$ONEPAY_Visa_Name}" type="text" value="{$clsConfiguration->getValue($ONEPAY_Visa_Name)}" plcaeholder="{$core->get_Lang('Name Method')}">
														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">URL Payment</label>
														<div class="controls">
															<input bind="name" class="form-control required" id="ONEPAY_Visa_URL_PAYMENT" name="iso-ONEPAY_Visa_URL_PAYMENT" type="text"  value="{$clsConfiguration->getValue('ONEPAY_Visa_URL_PAYMENT')}" plcaeholder="Thanh toán online qua thẻ nội địa (ATM)">
														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">Merchant ID</label>
														<div class="controls">
															<input class="form-control required" placeholder="MerchantId không vượt quá 500 ký tự" id="ONEPAY_Visa_Merchant_ID" name="iso-ONEPAY_Visa_Merchant_ID" type="text" value="{$clsConfiguration->getValue('ONEPAY_Visa_Merchant_ID')}">
														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">{$core->get_Lang('Hashcode')}</label>
														<div class="controls">
															<input class="form-control required" placeholder="Hashcode không được để trống" id="ONEPAY_Visa_Secure_Hash" name="iso-ONEPAY_Visa_Secure_Hash" type="text" value="{$clsConfiguration->getValue('ONEPAY_Visa_Secure_Hash')}">
														</div>
													</div>
													<div class="form-group">
														<label class="col-form-label strong">{$core->get_Lang('AccessCode')}</label>
														<div class="controls">
															<input class="form-control required" data-val="true" placeholder="AccessCode không được để trống" name="iso-ONEPAY_Visa_Access_Code" id="ONEPAY_Visa_Access_Code" type="text" value="{$clsConfiguration->getValue('ONEPAY_Visa_Access_Code')}">
														</div>
													</div>
													<div class="form-group">
														<div class="controls">
															<label class="checkbox-inline"><input type="checkbox" name="ONEPAY_Visa_Test_Mode" id="ONEPAY_Visa_Test_Mode" {if $clsConfiguration->getValue('ONEPAY_Visa_Test_Mode') eq '1'}checked{/if} value="1" /> {$core->get_Lang('Test Mode')}
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
										<div  class="top21">
											<input type="hidden" name="Hid_Pay5" value="Hid_Pay5" />
											<button type="button" class="btn btn-default pay_setting">{$core->get_Lang('Setting')}</button>
											<button type="button" class="btn btn-default cancel_setting">{$core->get_Lang('Cancel')}</button>
											<button type="submit" class="btn btn-default save_setting" name="submitvisa" >{$core->get_Lang('Save')}</button>
										</div>
									</form>        
								</div>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
		
{literal}
<script type="text/javascript">
	$(function(){
		$('.pay_setting').click(function(){
			var $_this = $(this);
			var $_body = $_this.closest('form').find('.payment-method-setting');
			if($('.payment-method-setting:visible').length){
				$('.payment-method-setting:visible').closest('form').find('.cancel_setting').trigger('click');
			}
			$_body.stop(false, true).removeClass('slidedown-hidden').addClass('slidedown-visible');
			$_this.hide();
			$_this.closest('form').find('.cancel_setting,.save_setting').show();
			return false;
		});
	});
	$('.cancel_setting').click(function(){
		var $_this = $(this);
		var $_body = $_this.closest('form').find('.payment-method-setting');
		$_body.stop(false, true).removeClass('slidedown-visible').addClass('slidedown-hidden');
		$_this.hide();
		$_this.closest('form').find('.save_setting').hide();
		$_this.closest('form').find('.pay_setting').show();
		return false;
	});
	
</script>
<style type="text/css">
	.panel__container{
		width:100%;
		clear:both;
	}
	.panel__container:after,
	.panel__container:before{
		display:table;
		clear:both;
		content:"";
	}
	.panel {
		width: 100%;
		background:none;
		border-radius: 4px;
	}
	.panel-left{
		float:left;
		width:24%;
	}
	
	.clearfix::after,
	.clearfix::before {
		content: " ";
		display: table;
	}
	.payment-setting{
		margin-bottom: 10px;
		border: 1px solid #ccc;
		border-radius: 3px;
		padding-bottom:0px;
		padding-left: 0;
		padding-right: 0;
	}
	.payment-setting .payment-header {
		position: relative;
		padding:10px 0px 10px 50px;
	}
	.slidedown-hidden.slidedown-visible {
		-webkit-transition: max-height 1s;
		-moz-transition: max-height 1s;
		transition: max-height 1s;
		max-height: 1500px;
		position: relative;
		visibility: visible;
		overflow: hidden;
	}
	.slidedown-hidden {
		position: absolute;
		visibility: hidden;
		max-height: 0;
		background: #f5f6f7;
		padding: 10px 20px 20px;
	}
	.panel-default {
		border-color: #ddd;
	}
	.panel>.panel-body:last-child {
		border-bottom-right-radius: inherit;
		border-bottom-left-radius: inherit;
	}
	.panel-body:after, .panel-body:before,
	.form-group:after,
	.form-group:before {
		content: " ";
		display: table;
		clear:both;
	}
	.form-group {
		margin-bottom: 15px;
	}
	.form-control {
		height: 32px;
		box-shadow: none;
		padding: 6px 8px;
		background-image: none;
	}
	.btn {
		line-height: 18px;
		padding: 6px 10px;
	}
	.btn-default {
		border-color: #d0d0d0;
	}
	.btn {
		display: inline-block;
		margin-bottom: 0;
		font-weight: 400;
		text-align: center;
		vertical-align: middle;
		cursor: pointer;
		border: 1px solid #DDD;
		white-space: nowrap;
		font-size: 13px;
		border-radius: 4px;
		-ms-user-select: none;
		user-select: none;
		background-image: none;
		color: #479ccf;
	}
	.form-control, output {
		font-size: 13px;
		line-height: 1.428571429;
		color: #555;
		display: block;
	}
	.form-control {
		width: 100%;
		background-color: #fff;
		border: 1px solid #ccc;
		border-radius: 3px;
		-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
		-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
		transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	}
	.payment-setting .logo {
		border-right: dotted 1px #eaeaea;
		position: absolute;
		width: 60px;
		height: 100%;
		top: 0;
		left: 0;
	}
	.top21 {
		padding: 10px 0px;
		border-top: 1px solid #ebeef0;
		text-align: right;
		margin-right: 20px;
	}
	
	label.strong {
		font-weight: 700;
		margin-bottom: 5px;
		display: block
	}
	.cancel_setting,
	.save_setting{
		display:none;
	}
	.panel .panel-body, .panel-lg .panel-body, .panel-lg .panel-footer, .panel-lg .panel-heading {
		background: #fff;
	}
	.payment-setting .payment-header, .payment-setting .payment-content, .payment-setting .payment-method-setting {
		padding: 20px;
		overflow:hidden;
	}
	.slideToggle,.slideToggle1{
		cursor:pointer;
	}
</style>
{/literal}