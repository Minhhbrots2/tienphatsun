// JavaScript Document
// Update is_home_top
$('.sttYes').click(function(e) {
	var tour_id=$(this).attr("id");
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/?mod=tour&act=updateHome",
		data: {'tour_id':tour_id, 'is_top':'1'},
		dataType: "html",
		success: function(html){
			$(this).css('font-weight','bold');
		}
	});
});
$('.topHotel').hover(function(e) {
	if($(this).attr("checked")==true){
		$(this).attr('title','Bỏ tích nếu không muốn tour này là Top');
	}else{
		$(this).attr('title','Tích chọn nếu muốn tour này là Top');
	}
});
$('.topHotel').click(function(e) {
	var is_top='';
	if($(this).attr("checked")==true){
		is_top=1;
		$(this).attr('title','Bỏ tích nếu không muốn tour này là Top');
	}else{
		is_top=0;
		$(this).attr('title','Tích chọn nếu muốn tour này là Top');
	}
	var this_id=$(this).attr("id");
	var adata={
		"class_name": "Tour",
		"this_id": this_id,
		"is_top": is_top
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/?mod=ajax&act=setTop",
		data: adata,
		dataType: "html",
		success: function(html){
			
		}
	});
});
$('.promotion').hover(function(e) {
	if($(this).attr("checked")==true){
		$(this).attr('title','Bỏ tích nếu không muốn tour này là Khuyến mại giảm giá');
	}else{
		$(this).attr('title','Tích chọn nếu muốn tour này là Khuyến mại giảm giá');
	}
});
$('.promotion').click(function(e) {
	var is_promotion='';
	if($(this).attr("checked")==true){
		$(this).attr('title','Bỏ tích nếu không muốn tour này là top');
		is_promotion=1;
	}else{
		is_promotion=0;
		$(this).attr('title','Tích chọn nếu muốn tour này là top');
	}
	var this_id=$(this).attr("id");
	var adata={
		"class_name": "Tour",
		"this_id": this_id,
		"is_promotion": is_promotion
	};
	$.ajax({
		type: "POST",
		url: path_ajax_script+"/?mod=ajax&act=setPromotion",
		data: adata,
		dataType: "html",
		success: function(html){
			
		}
	});
});