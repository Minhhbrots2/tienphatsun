<link rel="stylesheet" type="text/css" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$upd_version}" media="all" />
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/html2canvas.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.js?v={$upd_version}"></script>
<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">Nhân viên</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				{if $pvalTable gt '0'}
				<h1 class="ui-title-bar__title">Chỉnh sửa</h1>
				{else}
				<h1 class="ui-title-bar__title">Thêm mới</h1>
				{/if}
			</div>
		</div>
		{if $action eq '_edit'}
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/?mod={$mod}&act=view&profile_id={$pvalTable}" class="btn btn-default ui-title-bar__action" title="{$core->get_Lang('View')}">{$core->makeIcon('eye', 'Xem chi tiết')}</a>
				</div>
			</div>
		</div>
		{/if}
	</div>
</div>
<div class="ui-layout">
	<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
		<div class="row">
			<div class="col-md-8">
				{if $errMsg}
					<div class="message text-danger text-red" style="margin-bottom:25px">{$errMsg}</div>
				{/if}
				<div class="box light">
					<div class="box-title">
						<div class="caption">
							<span class="bold">Thông tin đăng nhập</span>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Tên đăng nhập <span class="requiredMark">*</span></label>
									<input type="text" class="form-control" name="iso-user_name" value="{if $action eq '_edit'}{$oneItem.user_name}{/if}" 
									placeholder="Tên đăng nhập" required="true" autocomplete="off" autocomplete="chrome-off" />
								</div>
								<div class="col-md-6">
									<label>{$core->get_Lang('Email')} <span class="requiredMark">*</span></label>
									<input type="text" class="form-control email" name="iso-email" value="{if $action eq '_edit'}{$oneItem.email}{/if}" 
									placeholder="Nhập tên" required="true" autocomplete="off" autocomplete="chrome-off" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label class="col-form-label">Mật khẩu</label>
									{if $action eq '_add'} <span class="requiredMark">*</span>{/if}</label>
									<input type="password" class="form-control" name="user_pass" placeholder="{$core->get_Lang('Password')}"{if $action eq '_add'} required="true"{/if} autocomplete="new-password" />
								</div>
								<div class="col-md-6">
									<label class="col-form-label">Xác nhận mật khẩu</label> 
									{if $action eq '_add'} <span class="requiredMark">*</span>{/if}</label>
									<input type="password" class="form-control" name="user_cpass" placeholder="{$core->get_Lang('Confirm Password')}"{if $action eq '_add'} required="true"{/if} autocomplete="new-password" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box light">
					<div class="box-title">
						<div class="caption">
							<span class="bold">Thông tin chi tiết</span>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<label>Mã nhân viên</label>
									<input type="text" name="iso-code" class="form-control" value="{$oneItem.code}" placeholder="Nhập mã" />
								</div>
								<div class="col-md-3">
									<label>Họ</label>
									<input type="text" name="iso-first_name" class="form-control" value="{$oneItem.first_name}" placeholder="Nhập họ" />
								</div>
								<div class="col-md-3">
									<label>Tên</label>
									<input type="text" name="iso-last_name" class="form-control" value="{$oneItem.last_name}" placeholder="Nhập tên" />
								</div>
								<div class="col-md-3">
									<label>Giới tính</label>
									<select class="form-control" name="iso-gender_id"  id="">
										<option value="0" >-- Chọn --</option>
										<option value="1" {if $oneItem.gender_id eq 1}selected{/if}>-- Nam --</option>
										<option value="2" {if $oneItem.gender_id eq 2}selected{/if}>-- Nữ --</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label>Điện thoại</label>
									<input type="text" class="form-control" name="iso-phone" value="{$oneItem.phone}" placeholder="Nhập điện thoại" />
								</div>
								<div class="col-md-8">
									<label>Địa chỉ</label>
									<input type="text" class="form-control" name="iso-address" value="{$oneItem.address}" placeholder="Nhập địa chỉ" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<label>Ngày sinh</label>
									<input type="text" class="form-control datepicker" name="birthday" value="{if !empty($oneItem.birthday)}{$oneItem.birthday|date_format:'%d/%m/%Y'}{/if}" placeholder="dd/mm/yyy" />
								</div>
								
								<div class="col-md-3">
									<label>CCCD</label>
									<input type="text" class="form-control" name="iso-CCID" value="{$oneItem.CCID}" placeholder="CCID/CMTND" />
								</div>
								<div class="col-md-3">
									<label>Ngày bắt đầu</label>
									<input type="text" class="form-control datepicker" required name="start_date" value="{if !empty($oneItem.start_date)}{$oneItem.start_date|date_format:'%d/%m/%Y'}{/if}" placeholder="dd/mm/yyyy" />
								</div>
								<div class="col-md-3">
									<label>Ngày kết thúc</label>
									<input type="text" class="form-control datepicker" name="end_date" value="{if !empty($oneItem.end_date)}{$oneItem.end_date|date_format:'%d/%m/%Y'}{/if}" placeholder="dd/mm/yyyy" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label>Quốc gia</label>
									<select class="form-control" name="iso-country_id" id="country_id">
										{$clsCountry->makeSelectOption($oneItem.country_id)}
									</select>
								</div>
								<div class="col-md-4">
									<label>Tỉnh/Thành phố</label>
									<select class="form-control" name="iso-city_id" id="city_id">
										<option value="0">-- Tỉnh/ Thành phố --</option>
									</select>
								</div>
								<div class="col-md-4">
									<label>Quận/Huyện</label>
									<select class="form-control" name="iso-district_id"  id="district_id">
										<option value="0">-- Quận/ Huyện --</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6 mb-3">
									<label>LinkedIn</label>
									<input type="text" class="form-control" name="linkedin" value="{$more_information.linkedin}" placeholder="https://www.linkedin.com/" />
								</div>
								<div class="col-md-6 mb-3">
									<label>Instagram</label>
									<input type="text" class="form-control" name="instagram" value="{$more_information.instagram}" placeholder="https://www.instagram.com/" />
								</div>
								<div class="col-md-6 mb-3">
									<label>Facebook</label>
									<input type="text" class="form-control" name="facebook" value="{$more_information.facebook}" placeholder="https://www.facebook.com/" />
								</div>
								<div class="col-md-6 mb-3">
									<label>Twitter</label>
									<input type="text" class="form-control" name="twitter" value="{$more_information.twitter}" placeholder="https://twitter.com/" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box light">
					<div class="box-title">
						<div class="caption">
							<span class="bold">Giới thiệu</span>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<textarea id="{$clsISO->getUniqid()}" class="form-control isoTextArea edit_profile_field_about" name="about" cols="255" rows="15">{$more_information.about}</textarea>
						</div>
					</div>
				</div>
				<div class="box light">
					<div class="box-title">
						<div class="caption">
							<span class="bold">Tài khoản ngân hàng</span>
						</div>
					</div>
					<div class="box-body">
						<div class="banks">
							<div class="holder_banks">
								{if !empty($banks_info)}
									{foreach from = $banks_info key = uid item=_oBank}
									<div class="bank-item">
										<a class="remove_bank" onclick="remove_bank(this, event)"></a>
										<div class="form-group">
											<label class="colf-form-label">Số tài khoản</label>
											<input type="text" class="form-control account_number numberonly" value="{$_oBank.account_number}" name="banks_info[{$uid}][account_number]" placeholder="Số tài khoản">
										</div>
										<div class="form-group">
											<label class="colf-form-label">Chủ tài khoản</label>
											<input type="text" class="form-control" value="{$_oBank.account_person}"
												name="banks_info[{$uid}][account_person]" placeholder="Chủ tài khoản">
										</div>
										<div class="form-group row">
											<div class="col-xs-12 col-md-6 pr-0">
												<label class="colf-form-label">Ngân hàng</label>
												<input type="text" class="form-control" value="{$_oBank.bank_name}"
													name="banks_info[{$uid}][bank_name]" placeholder="Tên ngân hàng">
											</div>
											<div class="col-xs-12 col-md-6">
												<label class="colf-form-label">Chi nhánh</label>
												<input type="text" class="form-control" value="{$_oBank.location}"
													name="banks_info[{$uid}][location]" placeholder="Tên chi nhánh (nếu có)">
											</div>
										</div>
									</div>
									{/foreach}
								{else}
								{assign var = uid value = $clsISO->getUniqid()}
								<div class="bank-item">
									<a class="remove_bank" onclick="remove_bank(this, event)"></a>
									<div class="form-group">
										<label class="colf-form-label">Số tài khoản</label>
										<input type="text" class="form-control account_number numberonly"
											name="banks_info[{$uid}][account_number]" placeholder="Số tài khoản">
									</div>
									<div class="form-group">
										<label class="colf-form-label">Chủ tài khoản</label>
										<input type="text" class="form-control" name="banks_info[{$uid}][account_person]"
											placeholder="Chủ tài khoản">
									</div>
									<div class="form-group row">
										<div class="col-xs-12 col-md-6 pr-0">
											<label class="colf-form-label">Ngân hàng</label>
											<input type="text" class="form-control" mask="Mm/yy" name="banks_info[{$uid}][bank_name]"
												placeholder="Tên ngân hàng">
										</div>
										<div class="col-xs-12 col-md-6">
											<label class="colf-form-label">Chi nhánh</label>
											<input type="text" class="form-control" name="banks_info[{$uid}][location]"
												placeholder="Tên chi nhánh (nếu có)">
										</div>
									</div>
								</div>
								{/if}
							</div>							
							<div class="clearfix">
								<button type="button" id="add_branch_bank" onclick="add_bank(this, event)"
									class="btn btn-block btn-primary text-bold">
									{$core->makeIcon('plus', 'Thêm mới')}
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="box light">
					<div class="box-title no-bottom-border">
						<div class="caption"><span class="bold">{$core->get_Lang('Avatar')}</span></div>
					</div>
					<div class="box-body">
						<div class="avatar">
							<div class="in">
								<img id="avatar" src="{$oneItem.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.svg'" alt="Chưa có hình ảnh" />
							</div>
							<a onclick="file_explorer(this, event)" toId="selectFile" toImg="avatar" member_id="{$pvalTable}" class="camera">{$core->makeIcon('camera')}</a>
							<input type="file" id="selectFile" class="hidden d-none" maxlength="255" name="avtar" >
						</div>
						{*<div class="avatar">
							<div class="in">
								<img id="avatar" src="{$oneItem.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.svg'" alt="Chưa có hình ảnh" style="height: 100%;object-fit: cover"/>
							</div>
							<a onclick="$Core.member.uploadImage(this,event)" toId="selectFile" toImg="avatar" data-type="avatar" member_id="{$pvalTable}" class="camera" profile_id="{$oneItem.profile_id}">{$core->makeIcon('camera')}</a>
							<input type="file" id="selectFile" class="hidden d-none" maxlength="255" name="avtar" >
						</div>*}
						
					</div>
					<div class="box-title border-top no-margin">
						<div class="caption">
							<span class="bold"><i class="fa fa-users"></i> Nhóm/Group(s)</span>
						</div>
					</div>
                    <div class="box-body pt-4">
                    	<div class="form-group">
							<label>Phòng ban</label>
							<select class="iso-selectize required" required name="iso-department_id" id="department_id">
								{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$oneItem.department_id,'Phòng ban')}
							</select>
						</div>
						<div class="form-group">
							<label>Vai trò</label>
							<div class="slb_RoleId">
								<select class="iso-selectize required" required name="iso-role_id" id="role_id">
									{$html_role_options}
								</select>
							</div>
						</div>
						<div class="form-group mb-0 group_block">
							<label>Dự án phụ trách</label>
							<div class="slb_BlockId">
								<select class="iso-selectize" name="block_ids[]" id="block_ids" multiple placeholder="Dự án">
									{$clsProperty->getSelectByPropertyV2('_BLOCK',$oneItem.block_ids,'',0)}
								</select>
							</div>
						</div>
                    </div>
					<div class="box-title border-top no-margin">
						<div class="caption">
							<span class="bold"><i class="fa fa-users"></i> Tình trạng</span>
						</div>
					</div>
                    <div class="box-body pt-4">
						<select class="iso-selectize custom-select required" required name="iso-status_id" id="status_id">
							{$clsISO->getSelectByPropertyTypeTitle('_STATUS_STAFF',$oneItem.status_id,'Tình trạng')}
						</select>
                    </div>
					<div class="box-title border-top no-margin">
						<div class="caption">
							<span class="bold"><i class="fa fa-users"></i> {$core->get_Lang('Notes')}</span>
						</div>
					</div>
					<div class="box-end no-border-top">
						<textarea class="form-control" name="note" placeholder="Nhập ghi chú" rows="3">{$oneItem.note}</textarea>
					</div>
				</div>
				{if $pvalTable}
				<div class="box light">
					<div class="box-title d-flex justify-content-between align-items-center">
						<div class="caption">
							<span class="bold">Video</span> 
						</div>
					</div>
					<div class="box-body">						
						<div class="input-group box_form mb-3 w-100">
							<input type="text" class="form-control" name="link_video" placeholder="Link video" value="{$more_information.link_video}" aria-label="Search" aria-describedby="button-addon2" style=" width: calc(100% - 97px);margin: 0"> 
							<button class="btn btn-primary" type="button" onClick="$Core.member.addVideo(this,{$pvalTable})">Thay đổi</button> 
						</div>
						<div class="box_body_video rounded" id="box_video">					
							{if $more_information.link_video ne ''}
								{$clsISO->getEmbedVideo($more_information.link_video,'100%',150)} 
							{else}
								Chưa có video
							{/if}

						</div>
					</div>
				</div>				
				<div class="box light">
					<div class="box-title d-flex justify-content-between align-items-center">
						<div class="caption">
							<span class="bold">Hình ảnh</span>
						</div>
						<input type="file" name="images[]" multiple hidden id="images" style="display:none">
						<button type="button" class="btn btn-primary text-nowrap" onclick="$Core.member.uploadImage(this,event);" toId="images" toImg="list_image" data-type="images" profile_id="{$pvalTable}">
							{$core->makeIcon('plus', 'Thêm mới')}
						</button>
					</div>
					<div class="box-body">
						<div class="form-row row" id="list_image">
							{if !empty($more_information.image)}
								{foreach from=$more_information.image item=image}
									<div class="item col-xs-3 mb-3" data-fancybox="gallery" href="{$image}">
										<img class="rounded drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;height: auto">
									</div>
								{/foreach}
							{else}
								Thư viện trống
							{/if}									
						</div>
					</div>
				</div>
				{/if}	
			</div>
		</div>
		<div class="ui-page-actions ui-page-actions--has-secondary" style="position: sticky;bottom: 0;background: #f4f6f8">
			<input value="Update" name="submit" type="hidden">
			<div class="ui-page-actions__container">
				<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
				<div class="ui-page-actions__actions ui-page-actions__actions--primary">
					<div class="ui-page-actions__button-group">
						<a class="btn btn-default" href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Calcel')}</a>
						{$saveBtn} {$saveList}
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	var country_id = '{$oneItem.country_id}',
		city_id = '{$oneItem.city_id}',
		district_id = '{$oneItem.district_id}';
</script>
{literal}
<script type="text/javascript">
	$(function(){
		loadCity(country_id, city_id);
		loadDistrict(city_id,district_id);
		$('input[name=iso-first_name],input[name=iso-last_name]').keyup(function(){
			var $_this = $(this);
			if($_this.hasClass('f_name')){
				$('input[name=iso-address_first_name]').val($_this.val());
			}else{
				$('input[name=iso-address_last_name]').val($_this.val());
			}
		});
		$('select[name=iso-country_id]').change(function(){
			var $_this=$(this);
			loadCity($_this.val(),'');
		});
		$('select[name=iso-city_id]').change(function(){
			var $_this=$(this);
			loadDistrict($_this.val(),'');
		});
		$('select[name=iso-department_id]').change(function(){
			var $_this = $(this);
			$.post(path_ajax_script+"/index.php?mod="+mod+"&act=load_option_role", {
				'department_id' : $_this.val()
			}, function(respJson){
				$('.slb_RoleId').html(respJson.html_role_options);
				$('.slb_TeamId').html(respJson.html_team_options);
			}, 'json');
		});
		/*$(document).on("change",'select[name=iso-role_id]',function(){
			var $_this = $(this);
			$.post(path_ajax_script+"/index.php?mod="+mod+"&act=load_option_block", {
				'role_id' : $_this.val()
			}, function(respJson){
				if(respJson.result){
					$(".group_block").removeClass("d-none");
					$('.slb_BlockId').html(respJson.html);
				}else{
					$(".group_block").addClass("d-none");
				}
				
			},"json");
		});*/
		$('.ajCreateQuickNewCity').live('click',function(){
			var $this = $(this);
			var $country_id = $('#country_id').val();
			if($country_id==''){
				$('#country_id').focus();
				alert(field_is_required);
				return false;
			}
			global_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=country&act=ajLoadCreateNewCity",
				data: {'country_id': $country_id},
				dataType: "html",
				success: function(html){
					makepopup(400,'auto',html,'pop_CityBox');
					global_loading(0);
				}
			});
			return false;
		});
		$('.ajSubmitQuickCity').live('click',function(){
			var $_this = $(this);
			var $title = $('#pop_CityBox #title');
			var $city_id = $_this.attr('city_id');
			var $country_id = $_this.attr('country_id');
			/**/
			if($title.val()==''){
				$title.focus();
				alertify.error(field_is_required);
				return false;
			}
			var adata = {
				'city_id' : $city_id,
				'title' : $title.val(),
				'country_id' : $country_id
			};
			global_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=country&act=ajSubmitQuickCity",
				data: adata,
				dataType: "html",
				success: function(html){
					global_loading(0);
					if(html.indexOf('_EXIST')>=0){
						alertify.error(message_error_exit);
					}else if(html.indexOf('_SUCCESS')>=0){
						var $htm = html.split('$$');
						loadCity($country_id,$htm[1]);
						$_this.closest('#pop_CityBox').find('.close_pop').trigger('click');
					}else{
						alertify.error(message_error_sys);
					}
				}
			});
			return false;
		});
		/**/
		$('.ajCreateQuickNewDistrict').live('click',function(){
			var $this = $(this);
			var $city_id = $('#city_id').val();
			if($city_id==''){
				$('#city_id').focus();
				alert(message_city_required);
				return false;
			}
			global_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=country&act=ajLoadCreateNewDistrict",
				data: {'city_id': $city_id},
				dataType: "html",
				success: function(html){
					makepopup(400,'auto',html,'pop_DistrictBox');
					global_loading(0);
				}
			});
			return false;
		});
		$('.ajSubmitQuickDistrict').live('click',function(){
			var $_this = $(this);
			var $title = $('#pop_DistrictBox #title');
			var $district_id = $_this.attr('district_id');
			var $city_id = $_this.attr('city_id');
			/**/
			if($title.val()==''){
				$title.focus();
				alertify.error(field_is_required);
				return false;
			}
			var adata = {
				'district_id' : $district_id,
				'title' : $title.val(),
				'city_id' : $city_id
			};
			global_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+"/index.php?mod=country&act=ajSubmitQuickDistrict",
				data: adata,
				dataType: "html",
				success: function(html){
					global_loading(0);
					if(html.indexOf('_EXIST')>=0){
						alertify.error(message_error_exit);
					}else if(html.indexOf('_SUCCESS')>=0){
						var $htm = html.split('$$');
						loadDistrict($city_id,$htm[1]);
						$_this.closest('#pop_DistrictBox').find('.close_pop').trigger('click');
					}else{
						alertify.error(message_error_sys);
					}
				}
			});
			return false;
		});
	});
	function loadCity($country_id,$city_id){
		$('select[name=iso-city_id]').html('<option value="">Loading...</option>');
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=country&act=ajMakeSelectboxCity",
			data: {"country_id"	: $country_id, 'city_id':$city_id},
			dataType: "html",
			success: function(html){
			  $('select[name=iso-city_id]').html(html);
			}
		});
	}
	function loadDistrict($city_id,$district_id){
		$('select[name=iso-district_id]').html('<option value="">Loading...</option>');
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod=country&act=ajMakeSelectboxDistrict",
			data: {"city_id":$city_id, 'district_id':$district_id},
			dataType: "html",
			success: function(html){
			  $('select[name=iso-district_id]').html(html);
			}
		});
	}
</script>
{/literal}