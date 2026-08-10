<div class="modal-dialog modal-dialog-scrollable modal-sm">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center justify-content-between">
				<div class="modal-header__left">
					<h5 class="modal-title">Báo cáo</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
				</div>
			</div>
			<div class="modal-body modal-body-scrollable">	
				<div class="form-group form-row mb-2">
					<div class="col-12">
						<label class="form-label mb-1">Link chia sẻ</label>
						<input class="form-control form-field w-100 required" data-label="Link chia sẻ" type="text"  name="link" value="{$more_information.link}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label mb-1">Hình ảnh nghiệm thu</label>
					<div class="we-filedrop-wrapper mt-1">
						{assign var=toId value=$clsISO->getUniqid()}
						<input type="file" class="btn_checkin d-none required" name="image" data-label="Hình ảnh nghiệm thu" onChange="$Core.course.loadImage(this,event)" data-course_id="{$oneEvent.course_id}" id="upload_image_{$toId}" openFrom="_desktop" toId="{$toId}">
						<div uid="{$uid}" class="we-filedrop position-relative" style="padding:50px 15px" onClick="$Core.course.select_image(this,event)" course_id="{$oneEvent.course_id}" openFrom="_pop" toId="{$toId}" > 
							<img class="preview_image position-absolute left-0 top-0 w-100 h-100" id="isoman_show_image" src="{$oneItem.image}" style="object-fit: cover;max-height: 200px;display:none">
							<svg class="mb-2" width="70" height="70" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M118.42 75.84C118.43 83.2392 116.894 90.5589 113.91 97.33H16.09C12.8944 90.0546 11.3622 82.1579 11.6049 74.2154C11.8477 66.2728 13.8593 58.4844 17.4932 51.4177C21.1271 44.3511 26.2918 38.1841 32.6109 33.3662C38.93 28.5483 46.2443 25.2008 54.0209 23.5676C61.7976 21.9345 69.8406 22.0568 77.564 23.9257C85.2873 25.7946 92.4965 29.363 98.6661 34.3709C104.836 39.3787 109.81 45.6999 113.228 52.8739C116.645 60.0478 118.419 67.8937 118.42 75.84Z" fill="#F2F2F2"></path><path d="M5.54 97.33H126.37" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M97 97.33H49.91V34.65C49.91 34.3848 50.0154 34.1305 50.2029 33.9429C50.3904 33.7554 50.6448 33.65 50.91 33.65H84.18C84.6167 33.6541 85.0483 33.7445 85.4499 33.9162C85.8515 34.0878 86.2152 34.3372 86.52 34.65L96.02 44.15C96.3321 44.4533 96.5811 44.8153 96.7527 45.2151C96.9243 45.615 97.0152 46.0449 97.02 46.48L97 97.33Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" fill="white"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M88.97 52.42H77.33V40.77L88.97 52.42Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M27.32 65.49V70.6" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M29.88 68.04H24.76" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M110.49 32.5601V39.9901" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M114.2 36.27H106.77" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M34.07 14.58V25.59" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M39.57 20.08H28.57" stroke="#D7D7D7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M67 115.86V67.12" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" fill="white"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" stroke="#63666A" stroke-width="1" stroke-miterlimit="10"></path></svg> 
							<p class="mb-0">Bấm để chọn một hình ảnh cần tải lên !!!</p>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" onClick="$Core.course.save_report(this,event)" course_id="{$course_id}">Xác nhận</button>
			</div>
		</div>
	</form>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$_document.on('change', 'input[name=is_all_staff]', function(){
			var _this = $(this);
			var form = _this.closest("form");
			var is_all_staff = $('input[name=is_all_staff]:checked',form).val();
			if(is_all_staff == 1){
				console.log(1);
				$('.unit_staff',form).hide();
				$('.unit_group_staff',form).hide();
			}else if(is_all_staff == 2){
				console.log(2);
				$('.unit_staff',form).hide();
				$('.unit_group_staff',form).show();
			} else {
				console.log(3);
				$('.unit_staff',form).show();
				$('.unit_group_staff',form).hide();									
			}
		});
	});
</script>
{/literal}