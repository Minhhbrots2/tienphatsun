$().ready(function() {
	// System quick add tours
	if(mod=='tour' && act=='default' || mod=='tour' && act=='edit'){
		// Some thing
	}else{
		// System quick add tour
		$(document).on('change', '#slb_TourGroupID', function(ev){
			var $_this = $(this);
			var $tp = $_this.attr('tp').toLowerCase();
			if($tp=='ajax' && $SiteHasCategoryGroup_Tours==1){
				loadQuickTourCategory($_this.val(),0,0,'slb_CategoryID');
			}
		});
		$(document).on('click', '.createNewTour', function(ev){ 
			var $_this = $(this);
			var $tour_group_id = 0;
			if($('#slb_TourGroup') != undefined){
				$tour_group_id = $('#slb_TourGroup').val();
			}
			$tour_type_id = 0;
			if($('#slb_TourType') != undefined){
				$tour_type_id = $('#slb_TourType').val();
			}
			$cat_id = 0;
			if($('#slb_Category') != undefined){
				$cat_id = $('#slb_Category').val();
			}
			var adata = {};
			adata['tour_group_id'] = $tour_group_id;
			adata['tour_type_id'] = $tour_type_id;
			if($SiteHasCat_Tours==1){
				adata['cat_id'] = $cat_id;
			}
			adata['tp'] = 'F';
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url: path_ajax_script+'/?mod=tour&act=ajaxCreateQuickTour',
				data: adata,
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					makepopupnotresize('560px','auto',html,'CreateTour');
				}
			});
			return false;
		});
		$(document).on('click', '.clickToSubmitNewTour', function(ev){
			var $_this = $(this);
			var $_form = $_this.closest('.frmPop');
			
			if($SiteHasGroup_Tours){
				var $groupID = $('#slb_TourGroupID');
				if(parseInt($groupID.val())==0){
					$groupID.focus();
					setSelectOpen($groupID);
					alertify.error(field_required);
					return false;
				}
			}
			if($SiteHasCat_Tours == 1) {
				var $catID = $('#slb_CategoryID');
				if(parseInt($catID.val())==0){
					$catID.focus();
					setSelectOpen($catID);
					alertify.error(field_required);
					return false;
				}
			}
			var $title = $_form.find('#NewTourTitle');
			if($.trim($title.val())==''){
				$title.focus();
				alertify.error(field_required);
				return false;
			}
			vietiso_loading(1);
			$('#frmCrxTour').ajaxSubmit({
				type: "POST",
				url: path_ajax_script+'/?mod=tour&act=ajaxCreateQuickTour',
				dataType: "html",
				success: function(html){
					vietiso_loading(0);
					if(html.indexOf('_ERROR') >= 0) {
						alertify.error(identicaltour);
					}
					else if(html.indexOf('_EXIST') >= 0) {
						alertify.error(identicaltour);
						alertify.error(existedtour);
					} 
					else {
						location.href = html;
					}
				}
			});
			return false;
		});
	}
	
	// System quick add hotel
	$(document).on('click', '.createNewHotel', function(ev) {
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script + '/?mod=hotel&act=ajaxCreateQuickHotel',
			dataType: "html",
			data: {'tp'	: 'F'},	
			success: function(html) {
				vietiso_loading(0);
				makepopupnotresize('500px', 'auto', html, 'CreateHotel');
			}
		});
		return false;
	});
	$(document).on('click', '.clickToSubmitNewHotel', function(ev) {
		var $_this = $(this);
		var $_form = $_this.closest('.frmPop');
		
		var $title = $_form.find('#NewHotelTitle');
		if ($.trim($title.val())=='') {
			$title.addClass('error').focus();
			alertify.error(field_required);
			return false;
		}
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script + '/?mod=hotel&act=ajaxCreateQuickHotel',
			data: {'title': $title.val(),'tp':'S'},
			dataType: "html",
			success: function(html) {
				vietiso_loading(0);
				if(html.indexOf('_ERROR') >= 0) {
					alertify.error(insert_error);
				}
				else if(html.indexOf('_EXIST') >= 0) {
					alertify.error(exist_error);
				} 
				else {
					location.href = html;
				}
			}
		});
		return false;
	});
	
	// System quick add hotel
	$(document).on('click', '.createNewCruise', function(){
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/?mod=cruise&act=ajaxCreateQuickCruise",
			data: {'tp':'F'},
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				makepopupnotresize('500px','auto',html,'box_CreateNewCruise');
			}
		});
		return false;
	});
	$(document).on('click', '.clickToSubmitNewCruise', function(){
		var $_this = $(this);
		var $_form = $_this.closest('.frmPop');
		
		if($SiteHasCruisesCategory == 1) {
			var $catID = $_form.find('#slb_CategoryID');
			if(parseInt($catID.val())==0){
				$catID.addClass('error').focus();
				setSelectOpen($catID);
				alertify.error(field_required);
				return false;
			}
		}
		var $title = $_form.find('#NewCruiseTitle');
		if($.trim($title.val())==''){
			$title.addClass('error').focus();
			alertify.error(field_required);
			return false;
		}
		vietiso_loading(1);
		$('#frmCrxCruise').ajaxSubmit({
			type: "POST",
			url: path_ajax_script+"/?mod=cruise&act=ajaxCreateQuickCruise",
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				if(html.indexOf('_ERROR') >= 0) {
					alertify.error(insert_error);
				}
				else if(html.indexOf('_EXIST') >= 0) {
					alertify.error(exist_error);
				} 
				else {
					location.href = html;
				}
			}
		});
		return false;
	});
	// System event click
    $('.ajClickAddCity').click(function() {
        var $_this = $(this);
        var $country_id = $('select[name=country_id]').val();
        if ($country_id == '' || $country_id == '0') {
            alertify.error('Bạn cần lựa chọn quốc gia !');
            return false;
        }
        vietiso_loading(1);
        $.ajax({
            type: "POST",
            url: path_ajax_script + "/index.php?mod=city&act=ajLoadFormAddQuickCity",
            dataType: "html",
            data: {'country_id': $country_id, 'forid': $_this.attr('forid')},
            success: function(html) {
                makepopup('400px', '', html, 'cmsbox_BoxAddCity');
                vietiso_loading(0);
            }
        });
    });
    $('.ajSubmitQuickCity').live('click', function() {
        var $_this = $(this);
        var $forid = $_this.attr('forid');
        var $country_id = $_this.attr('country_id');
        if ($('#cmsbox_BoxAddCity input[name=title]').val() == '') {
            $('#cmsbox_BoxAddCity input[name=title]').focus();
            alertify.error(field_is_required);
            return false;
        }
        vietiso_loading(1);
        var adata = {
            'city_id': $_this.attr('data'),
            'title': $('#cmsbox_BoxAddCity input[name=title]').val(),
            'country_id': $country_id
        };
        vietiso_loading(1);
        $.ajax({
            type: "POST",
            url: path_ajax_script + "/index.php?mod=city&act=ajSubmitQuickCity",
            data: adata,
            dataType: "html",
            success: function(html) {
                if (html.indexOf('_SUCCESS') >= 0) {
                    alertify.success(insert_success);
                    $('#cmsbox_BoxAddCity .close_pop').trigger('click');
                    loadCity($country_id);
                }
                if (html.indexOf('_ERROR') >= 0) {
                    alertify.success(insert_error);
                }
                if (html.indexOf('_EXIST') >= 0) {
                    alertify.success(exist_error);
                }
                vietiso_loading(0);
            }
        });
    });
    // End city
    $('.clickToManagerProperty').live('click', function() {
        var $_this = $(this);

        var $_width = $_this.attr('_width') != '' ? $_this.attr('_width') : '60%';
        var $_height = $_this.height('_height') != '' ? $_this.height('_height') : '60%';
        var adata = {
            'property_type': $_this.attr('data'),
            'property_form': $_this.attr('property_form'),
            'property_check': $_this.attr('property_check'),
            'property_value': $_this.val(),
        };
        vietiso_loading(1);
        $.ajax({
            type: "POST",
            url: path_ajax_script + "/index.php?mod=property&act=ajGetBoxManagerProperty",
            data: adata,
            dataType: "html",
            success: function(html) {
                makepopup($_width, $_height, html, 'cmsbox_ManagerProperty');
                $('#cmsbox_ManagerProperty').css('top', '40px');
                vietiso_loading(0);
            }
        });
        return false;
    });
    $('input[class=chkitem]').live('change', function() {
        var $_this = $(this);
        if ($_this.is(':checked')) {
            $_this.closest('tr').addClass('hightlight');
        } else {
            $_this.closest('tr').removeClass('hightlight');
        }
    });
    $('.clickToUpdatePropertyList').live('click', function() {
        var _this = $(this);
        var list_selected_chkitem = $('#list_selected_chkitem').val();
        var property_type = _this.attr('property_type');
        var adata = {
            'property_type': property_type,
            'list_selected_chkitem': list_selected_chkitem
        };
        $.ajax({
            type: "POST",
            url: path_ajax_script + "/index.php?mod=property&act=ajSavePropertyToText",
            data: adata,
            dataType: "html",
            success: function(html) {
                var html = html.replace(' ', '');
                $('.loadProperty_' + property_type).val(html);
                $('#hidden_' + property_type).val(list_selected_chkitem);
                $('#hidden_property_type_' + property_type).val(list_selected_chkitem);
                _this.closest('.frmPop').find('.close_pop').click();
            }
        });
        return false;
    });
    $('.clickToAddProperty').live('click', function() {
        var _this = $(this);
        var adata = {
            'property_type': _this.attr('property_type'),
            'property_form': _this.attr('property_form')
        };
        vietiso_loading(1);
        $.ajax({
            type: "POST",
            url: path_ajax_script + "/?mod=property&act=ajGetAddProperty",
            data: adata,
            dataType: "html",
            success: function(html) {
                makepopup('45%', 'auto', html, 'frmAddProperty');
                makeSystemTab();
                vietiso_loading(0);
            }
        });
        return false;
    });
    $('.submitToProperty').live('click', function() {
        var $_this = $(this);
        var $property_type = $_this.attr('property_type');
        var $property_form = $_this.attr('property_form');
        /**/
        if ($_this.attr('property_form') == 'full') {

            if ($('#property_code').val() == '') {
                $('#property_code').addClass('error').focus();
                return false;
            }
            if ($('#property_title_en').val() == '') {
                $('#property_title_en').addClass('error').focus();
                return false;
            }
            var adata = {
                'property_id': $_this.attr('property_id'),
                'property_type': $property_type,
                'property_form': $property_form,
                'property_code': $('#property_code').val(),
                'title_en': $('#property_title_en').val(),
                'title_vn': $('#property_title_vn').val(),
                'intro_en': $('#property_intro_en').val(),
                'intro_vn': $('#property_intro_vn').val(),
                'order_no': $('#property_order').val()
            };
        } else {
            if ($('#property_title_en').val() == '') {
                $('#property_title_en').addClass('error').focus();
                return false;
            }
            var adata = {
                'property_id': $_this.attr('property_id'),
                'property_type': $property_type,
                'property_form': $property_form,
                'title_en': $('#property_title_en').val(),
                'title_vn': $('#property_title_vn').val(),
                'order_no': $('#property_order').val()
            };
        }
        vietiso_loading(1);
        $.ajax({
            type: "POST",
            url: path_ajax_script + "/?mod=property&act=ajSubmitProperty",
            data: adata,
            dataType: "html",
            cache: false,
            success: function(html) {
                if (type == "HotelFacilities") {
                    lstHotelFacilities();
                }
                var htm = parseInt(html);
                if (html == 1) {
                    loadTablePropertyType(type);
                    loadListCheckBoxPropertyType(type);
                    $(".contentPop").animate({scrollTop: $(".contentPop")[0].scrollHeight}, 100);
                    alertify.success('Thêm mới thành công');
                    $('#frmAddProperty a.close_pop').trigger('click');
                }
                if (html == 2) {
                    loadTablePropertyType(type);
                    $(".contentPop").animate({scrollTop: $(".contentPop")[0].scrollHeight}, 100);
                    alertify.success('Cập nhật thành công');
                    $('#frmEditProperty a.close_pop').trigger('click');
                }
                if (html == 3) {
                    alertify.error('Lỗi !');
                }
                if (htm == 5) {
                    alertify.error('Đã tồn tại !');
                }
                vietiso_loading(0);
            }
        });
        return false;
    });
	$(document).on('click', '.ajOpenElFinder', function(ev){
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script + "/index.php?mod=home&act=ajOpenElfinder",
			dataType: "html",
			success: function(html) {
				vietiso_loading(0);
				makepopupnotresize(920,'auto',html,'pop_Elfinder');
				var elf = $('#elfinder').elfinder({
					url : path_ajax_script+'/editor/elfinder/php/connector.php',
					getfile : {
                        onlyURL : true,
                        multiple : true,
                        folders : false
                    },
					getFileCallback : function(file) {
						alert(file);
						$('.close_pop').trigger('click');
					},
					resizable:false,
					docked: false,
					dialog: false
				}).elfinder('instance');
				$('#pop_Elfinder').css('top',100);
				return false;			
			}
		});
	});
});
function loadQuickTourCategory($tour_group_id, $selected, $chosen, $conatiner){
	if($conatiner=='' || $conatiner==undefined){
		$conatiner = 'slb_Category';
	}
	$('#'+$conatiner).html('<option value="0">'+loading+'</option>');
	$.ajax({
		type: "POST",
		url: path_ajax_script+'/?mod=tour&act=ajaxSiteTourCategory',
		data:{
			"tour_group_id"	: $tour_group_id,
			"cat_id"	: $selected,
			"chosen"	: $chosen,
		},
		dataType: "html",
		success: function(html){
			if($chosen){
				$('#'+$conatiner).html(html);
				$(".chosen-select").chosen({max_selected_options: 5,width:'100%'});
			}else{
				$('#'+$conatiner).html(html);
			}
		}
	});
}
function loadSelectBoxCategory($parent_id, $el) {
    var adata = {
        'parent_id': $parent_id
    };
    $.ajax({
        type: "POST",
        url: path_ajax_script + "/?mod=category&act=ajmakeSelectBoxOption",
        data: {"parent_id": $_this.val()},
        dataType: "html",
        success: function(html) {
            $el.html(html);
        }
    });
}
function initGalleryGlobal($table_id,$container) {
    var adata = {
        'table_id': $table_id
    };
    $.ajax({
        type: "POST",
        url: path_ajax_script + "/index.php?mod=hotel&act=ajaxInitPhotosGallery",
        data: adata,
        dataType: "html",
        cache: true,
        success: function(html) {
            $('#' + $container).html(html);
            loadListGallery($table_id,'',1);
        }
    });
}
function loadListGallery($table_id, $keyword, $page, $number_per_page) {
    $.ajax({
        type: "POST",
        url: path_ajax_script + "/index.php?mod=hotel&act=ajLoadPhotosGallery",
        data: {
			'table_id': $table_id,
			'keyword': $keyword,
			'page': $page,
			'number_per_page': $number_per_page
		},
        dataType: "html",
        success: function(html) {
			var $htm = html.split('$$$');
            $('#preview').html($htm[0]);
			if($.trim($htm[1]) != ''){
				$('#gallery_paginate').height(24).html($htm[1]);
			}
        }
    });
}
