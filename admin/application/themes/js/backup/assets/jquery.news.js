// JavaScript Document
$.urlParam = function(name, def){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return def;
    }else{
       return results[1] || def;
    }
}
$(function(){
	if(mod=='news' && act == 'default'){
		$('#forums select').change(function(){
			$('#forums').submit();
		});
	}
});
function loadCategoryByDomain($list_domain_id){
	console.log($list_domain_id);
	$.ajax({
		type: "POST",
		url: path_ajax_script+'/?mod='+mod+'&act=ajaxCategoryByDomain',
		data: {'list_domain_id': $list_domain_id},
		dataType: "html",
		success: function(html){
			$('#slb_SelectCategory').html(html);
			vietiso_loading(0);
		}
	});
}