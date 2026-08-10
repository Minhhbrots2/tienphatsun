$().ready(function(){
	$('#slb_city_top_domain').change(function(){
		var $_this = $(this);
		vietiso_loading(1);
		window.location.href = '/ucp/index.php?mod=city&act='+act+'&domain_id='+$_this.val();
	});
	loadCityChoice($country_id);
	$('#check_all').change(function(){
		if($(this).is(':checked')){
			$('.chkitem').attr('checked',true);
		}else{
			$('.chkitem').removeAttr('checked');
		}
	});
	$('#slb_country').change(function(){
		var $_this = $(this);
		var $country_id = $_this.val();
		loadCityChoice($country_id);
	});
	$('#key').bind('keyup keydown change',function(){
		var _this=$(this);
		var rows = $('#tblHoderCity tr').size();
		if(rows > 1 && _this.val() != ''){
			s=_this.val();
			$("#tblHoderCity tr").each(function(){
				$(this).find('td:eq(1)').text().search(new RegExp(s,"i"))<0? $(this).hide():$(this).show();
			});
		}else{
			$('#tblHoderCity tr').each(function(){
				$(this).show();
			});
		}
	});
	$('#quickSearch input[class=chkitem]').live('change',function(){
		setList();
	});
	$('#clickToSaveTop').click(function(){
		var $_this = $(this);
		var $listId = getCheckBoxValueByClass('chkitem');
		
		if($('select[name=iso-country_id]').val()==''){
			alertify.error('Bạn chưa chọn Quốc Gia');
			$('select[name=iso-country_id]').focus();
			return false;
		}
		if($listId==''){
			alertify.error('Bạn chưa chọn Tỉnh/ Thành Phố');
			return false;
		}
		var adata = {
			'domain_id': $domain_id,
			'listId' : $listId.join('|'),
			'country_id': $('#slb_country').val()
		};
		$_this.find('u').text('Đang xử lý...');
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod="+mod+"&act=ajaxSaveTopCity",
			data: adata,
			dataType: "html",
			success: function(html){
				$_this.find('u').text('Lưu chọn');
				$('#check_all').removeAttr('checked');
				loadCityChoice($('#slb_country').val());
			}
		});
	});
	$('.moveCityTop').live('click',function(){
		var $_this = $(this);
		var $citydomain_store_id = $_this.attr('data');
		var $country_id = $_this.attr('country_id');
		var $direct = $_this.attr('direct');
		var adata = {
			'citydomain_store_id' : $citydomain_store_id,
			'country_id' : $country_id,
			'domain_id' : $domain_id,
			'direct' : $direct
		};
		vietiso_loading(1);
		$.ajax({
			type:'POST',	
			url:path_ajax_script+'/index.php?mod=city&act=ajMoveCityTop',	
			data: adata,	
			dataType:'html',	
			success:function(html){
				vietiso_loading(0);
				loadCityTop($domain_id, $country_id);
			}
		});
	});
	$('.clickToCancelTopCity').live('click',function(){
		var $_this = $(this);
		var $country_id = $_this.attr('country_id');
		if(confirm(confirm_delete)){
			var adata = {
				'citydomain_store_id'	: $_this.attr('data')
			};
			$.ajax({
				type:'POST',	
				url:path_ajax_script+'/index.php?mod=city&act=ajCancelChooseTopCity',	
				data: adata,	
				dataType:'html',	
				success:function(html){
					loadCityChoice($country_id);
					alertify.error('Bạn đã xóa thành công !');
				}
			});
		}
		return false;
	});
});
function loadCityChoice($country_id){
	loadCityTop($domain_id, $country_id);
	$('#quickSearch').html('<li>Loading...</li>');
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=city&act=ajaxLoadCityChoice",
		data: {'country_id' : $country_id,'domain_id': $domain_id},
		dataType: "html",
		success: function(html){
			$('#quickSearch').html(html);
		}
	});
}
function loadCityTop($domain_id, $country_id){
	var adata = {
		'domain_id' : $domain_id,
		'country_id' : $country_id
	};
	vietiso_loading(1);
	$.ajax({
		type:'POST',	
		url:path_ajax_script+'/index.php?mod=city&act=ajaxLoadCityTop',	
		data : adata,	
		dataType:'html',	
		success:function(html){
			vietiso_loading(0);
			$('#tblHoderCity').html(html);
		}
	});
}