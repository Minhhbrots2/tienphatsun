$().ready(function(){
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
		var $country_id = $('#slb_country').val();
		var $listId = getCheckBoxValueByClass('chkitem');
		
		if(parseInt($country_id)==0){
			alertify.error(field_required);
			$('#slb_country').focus();
			return false;
		}
		if($listId==''){
			alertify.error(field_required);
			return false;
		}
		var adata = {
			'listId' : $listId.join('|'),
			'country_id': $('#slb_country').val()
		};
		$_this.find('u').text(loading);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod="+mod+"&act=ajaxSaveTopCity",
			data: adata,
			dataType: "html",
			success: function(html){
				$_this.find('u').text(save);
				$('#check_all').removeAttr('checked');
				loadCityChoice($('#slb_country').val());
			}
		});
	});
	$('.moveCityTop').live('click',function(){
		var $_this = $(this);
		var $city_id = $_this.attr('data');
		var $country_id = $_this.attr('country_id');
		var $direct = $_this.attr('direct');
		
		var adata = {
			'city_id' : $city_id,
			'direct' : $direct,
			'country_id' : $country_id
		};
		vietiso_loading(1);
		$.ajax({
			type:'POST',	
			url:path_ajax_script+'/index.php?mod=city&act=ajMoveCityTop',	
			data: adata,	
			dataType:'html',	
			success:function(html){
				vietiso_loading(0);
				loadCityTop($country_id);
			}
		});
	});
	$('.clickToCancelTopCity').live('click',function(){
		var $_this = $(this);
		var $country_id = $_this.attr('country_id');
		
		if(confirm(confirm_delete)){
			var adata = {
				'city_id'	: $_this.attr('data'),
				'country_id'	: $country_id
			};
			$.ajax({
				type:'POST',	
				url:path_ajax_script+'/index.php?mod=city&act=ajCancelChooseTopCity',	
				data: adata,	
				dataType:'html',	
				success:function(html){
					loadCityChoice($country_id);
					alertify.error(delete_success);
				}
			});
		}
		return false;
	});
});
function loadCityChoice($country_id){
	$('#quickSearch').html('<li>'+loading+'</li>');
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/index.php?mod=city&act=ajaxLoadCityChoice",
		data: {'country_id' : $country_id},
		dataType: "html",
		success: function(html){
			loadCityTop($country_id);
			$('#quickSearch').html(html);
		}
	});
}
function loadCityTop($country_id){
	vietiso_loading(1);
	$.ajax({
		type:'POST',	
		url:path_ajax_script+'/index.php?mod=city&act=ajaxLoadCityTop',	
		data : {'country_id' : $country_id},	
		dataType:'html',	
		success:function(html){
			vietiso_loading(0);
			$('#tblHoderCity').html(html);
		}
	});
}