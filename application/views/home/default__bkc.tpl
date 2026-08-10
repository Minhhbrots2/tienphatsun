<section class="section-body">
	<div class="background"></div>
	<div class="page_container">
		<div class="logo">
			<img class="img-responsive" src="https://w.ladicdn.com/s550x550/62108042a9d1d90012df6a49/logo-khong-nen-20230406081958-uoxm3.png">
		</div>
		<div class="frame-layer">
			<div class="frame-layer-ls">
				<div class="frame-layer-xs">
					<div class="d-flex align-items-center py-2">
						<div class="logo-branch">
							<img class="img-responsive" style="width:150px" src="{$URL_IMAGES}/logo-h.png" />
						</div>
						<div style="width:calc(100% - 150px)" class="frame-layer-title text-center">
							Tra cứu thông tin căn hộ cao tầng
						</div>
					</div>
					<table class="table table-ilooca" width="100%">
						<tbody>
							<tr>
								<td width="25%">Dự án</td>
								<td class="p-0" colspan="3">
									<select data-field="project_id" name="project_id" class="form-control sop_field iso-selectize text-upper text-center">{$clsProperty->getSelectByProperty('_PROJECT', 0, "Lựa chọn dự án")}</select>
								</td>
							</tr>
							<tr>
								<td width="25%">Tòa nhà</td>
								<td class="p-1 holder__ipn-cell" width="25%"></td>
								<td colspan="2" class="text-center" rowspan="3">
									Điền Toà-Tầng-Mã căn vào ô bên trái
								</td>
							</tr>
							<tr>
								<td>Tầng</td>
								<td class="p-0">
									<input class="form-control text-center custom-input sop_field numberonly" data-field="floor" placeholder="Nhập số tầng" value="" maxlength="255" />
								</td>
							</tr>
							<tr>
								<td>Mã căn</td>
								<td class="p-0">
									<input class="form-control text-center custom-input numberonly sop_field" data-field="code" placeholder="Nhập mã căn hộ" maxlength="255" />
								</td>
							</tr>
						</tbody>
						<tbody class="holder_result">
							<tr>
								<td>Mã căn đầy đủ</td>
								<td class="text-center" colspan="2">--</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-right" colspan="2">Loại căn hộ</td>
								<td class="text-center">---</td>
								<td class="text-center">(số phòng ngủ)</td>
							</tr>
							<tr>
								<td class="text-right" colspan="2">Diện tích thông thủy (m2)</td>
								<td class="text-center">--</td>
								<td class="text-center">m2</td>
							</tr>
							<tr>
								<td class="text-right" colspan="2">Diện tích tim tường (m2)</td>
								<td class="text-center">--</td>
								<td class="text-center">m2</td>
							</tr>
							<tr>
								<td class="text-right" colspan="2">Hướng</td>
								<td class="text-center">--</td>
								<td class="text-center">Hướng ban công</td>
							</tr>
							<tr>
								<td class="text-right" colspan="2">GBCH (Gồm VAT +KPBT)</td>
								<td class="text-center">--</td>
								<td class="text-center">Giá full VAT</td>
							</tr>
							<tr>
								<td class="text-center" colspan="2">Link mặt bằng tổng VHOP</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="text-center" width="25%">Layout --</td>
								<td class="text-center" width="25%">Tiện ích nội khu --</td>
								<td class="text-center" width="25%">Thiết kế căn --</td>
								<td class="text-center" width="25%">Layout ZR1</td>
							</tr>
							<tr>
								<td  class="text-center">Video mẫu --</td>
								<td class="text-center">Tiêu chuẩn bàn giao --</td>
								<td class="text-center">CSBS</td>
								<td class="text-center">Ảnh căn mẫu --</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
{literal}
<style type="text/css">
	.ui-autocomplete {
		z-index: 99999;
	}
</style>
<script type="text/javascript">
	$(function(){
		setTimeout(() => {
			$('select[name=project_id]').trigger('change');
		}, 1000);
		$_document.on('change', 'select[name=project_id]', function(){
			var _this = $(this),
				project_id = _this.val();
			if(!$Core.util.isEmpty(project_id)){
				$.post('/index.php?mod='+MOD+'&act=load_input_building', {
					'project_id' : project_id
				}, function(respJson){
					$('.holder__ipn-cell').html(respJson.html);
					if(respJson.callback) eval(respJson.callback);
				},'json');
			} else {
				var html = '<input type="hidden" class="sop_field" name="hid_building" data-field="building" />'
				+'<input class="form-control custom-input" placeholder="Chọn tòa nhà" />';
				$('.holder__ipn-cell').html(html);
			}
		});
		$_document.on('change', '.sop_field', function(){
			var _validated = 0, $_adata = {};
			$('.sop_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
				} else {
					$_adata[field] = $(_elem).val();
				}
			});
			if(parseInt(_validated) == 0){
				$.post('/index.php?mod='+MOD+'&act=load_apartment_info', $_adata, function(html){
					$('.holder_result').html(html);
				});
			}
		});
	});
</script>
{/literal}