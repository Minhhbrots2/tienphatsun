var myModal = new bootstrap.Modal($('#careProject')[0]);
$(function(){
	setTimeout(() => {
		var options = !$Core.util.isEmpty(params) ? params : {};
		$Core.document.list_docs(options);
	}, 500);
	$('#categoryMenu').on('show.bs.collapse', function () {
        $('.category-button').find('.toggle-card').removeClass('bx-chevron-down').addClass('bx-chevron-up');
    });
    $('#categoryMenu').on('hide.bs.collapse', function () {
        $('.category-button').find('.toggle-card').removeClass('bx-chevron-up').addClass('bx-chevron-down');
    });
	$('#projectMenu').on('show.bs.collapse', function () {
        $('.project-button').find('.toggle-card').removeClass('bx-chevron-down').addClass('bx-chevron-up');
    });
    $('#projectMenu').on('hide.bs.collapse', function () {
        $('.project-button').find('.toggle-card').removeClass('bx-chevron-up').addClass('bx-chevron-down');
    });
	// if ($(window).width() <= 1024) {
	// 	$Core.document.displayMenu('#categoryMenu');
    //     $Core.document.displayMenu('#projectMenu');
	// }
    $(window).on("load", function() {
        let domain = $(".store-document").data("domain");
        let noImage = $(".store-document").data("src-no-image");
        $Core.document.checkImage("img.item_document-image", noImage, domain);
    });
    $("body").append('<div class="overlay"></div>');
    // khi bấm nút mở
    $("#toggleSidebar").on("click", function(){
        $("#sidebarFilter").toggleClass("active");
        $(".overlay").toggleClass("active");
    });
    // khi bấm ra ngoài
    $_document.on("click", ".overlay", function(){
        $("#sidebarFilter").removeClass("active");
        $(".overlay").removeClass("active");
    });
    $('.btn-close-menu').click(() => {
        $('.overlay').trigger('click');
    });
});
$Core.document = {
	do_search : $Core.util.delay((_this, e) => {
		e.stopPropagation();
		var _keyCode = e.keyCode || e.which();
		if(_keyCode === 13){
			$Core.document.list_docs({});
		}
		return false;
	}, 500),
	list_docs: (options = {}) => {
		var $_adata = options || {};
		let newUrl = window.location.pathname;
		let paramsFirst = true;
        let field_filter = ['cat_id', 'keyword', 'tag_id'];
        let field_add_url = ['page','cat_id', 'keyword', 'tag_id', 'project_filter'];
		if(!$_adata.hasOwnProperty('page')){
			var page = $('input[name=current_page]').val();
			$_adata['page'] = page;
		}
        $is_filter = false;
        if (!$_adata.hasOwnProperty('project_id')) {
			let project_arr = [];
			if($('.item-project-care').length){
				$('.item-project-care').each((_i, _elem) => {
					if($(_elem).find('.btn-project-care').hasClass('active')){
						var project_id = $(_elem).find('.btn-project-care').data('project_id');
						if(typeof project_id !== 'undefined' && $.inArray(project_id, project_arr) === -1) {
							project_arr.push(project_id);
						} 
					}
				});
				if (project_arr.length > 0) {
					$_adata['project_id'] = project_arr.join(',');
				}
			} else {
				$_adata['project_id'] = '';
			}
		}
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if(typeof(field) != 'undefined'){
					if (options != null && options.hasOwnProperty(field)) {
						$('[data-field='+field+']').val(options[field]);
					} else {
						$_adata[field] = $(_elem).val();
					}
				}
			});
		}
		if (!$_adata.hasOwnProperty('cat_id')) { 
			let activeCategoryEl = $('.category_item.active');
			if (activeCategoryEl.length > 0) {
				let elCategoryLink = activeCategoryEl.find('.category_link');
				let cat_id = elCategoryLink.data('cat-id');
				if (typeof cat_id != 'undefined') {
					$_adata['cat_id'] = cat_id;
				}
			} else {
				$_adata['cat_id'] = 0;
			}
		}
		if (!$_adata.hasOwnProperty('project_filter')) { 
			let activeProjectPropertyEl = $('.project_property_item.active');
			if (activeProjectPropertyEl.length > 0) {
				let elProjectPropertyLink = activeProjectPropertyEl.find('.project_property_link');
				let project_property_type = elProjectPropertyLink.data('type');
				let project_property_id = elProjectPropertyLink.data('project-property-id');
				if (typeof project_property_id != 'undefined' && typeof project_property_type != 'undefined') {
					$_adata['project_filter'] = project_property_type+'_'+project_property_id;
				}
			} else {
				$_adata['project_filter'] = '';
			}
		}
		if (!$_adata.hasOwnProperty('tag_id')) {
			if($('.tag_type_tag').length){
				let tagArr = [];
				$('.tag_type_tag').each((_i, _elem) => {
					let tag_id = $(_elem).data('tag-id');
					if (typeof tag_id !== 'undefined') {
						tagArr.push(tag_id);
					} 
				});
				if (tagArr.length > 0) {
					$_adata['tag_id'] = tagArr.join(',');
				}
			} else {
				$_adata['tag_id'] = '';
			}
		}
		for (let key in $_adata) {
			if ($_adata.hasOwnProperty(key) && $_adata[key].toString().length > 0 && $.inArray(key, field_add_url) !== -1) {
				let sign = '&';
				if (paramsFirst) {
					sign = '?';
					paramsFirst = false;
				}
				newUrl += sign + key + '='+ $_adata[key];
                if ($.inArray(key, field_filter) !== -1) {
                    $is_filter = true;
                }
			}
		}
		window.history.pushState({path:newUrl}, '', newUrl);
		$.ajax({
			url: PCMS_URL+'/index.php?mod='+MOD+'&act=list_docs',
			method: 'POST',
			data: $_adata,
			dataType: 'json',
			beforeSend: function() {
				$Core.util.toggleIndicatior(1);
				$('html, body').animate({ scrollTop: 0 }, 500);
			}, success: function(respJson) {
                let total_record = parseInt(respJson.total_record);
				$('.holder_docs').html(respJson.html);
				$('#'+'categoryMenu').html(respJson.htmlCategory);
				$('#'+'projectMenu').html(respJson.htmlProject);
				$('.'+'tag_document').html(respJson.htmlTags);
				if(parseInt(respJson.total_page) > 0 && parseInt(respJson.total_record) > 0){
					$('#pager_docs').pagination({
						listStyle:"pagination justify-content-center",
						currentPage: respJson.current_page, 
						itemsOnPage: respJson.per_page,
						items: respJson.total_record,
						cssStyle: 'light-theme',
						prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
						nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
						hrefTextPrefix: 'javascript:void(0);',
						onPageClick : (pageNumber) => {
							$Core.document.list_docs($.extend($_adata, {'page':pageNumber}));
						}
					});
				} else {
					$('#pager_docs').hide();
				}
				$('.total-result').html(total_record);
                let domain = $(".store-document").data("domain"),
					noImage = $(".store-document").data("src-no-image");
                $Core.document.checkImage("img.image_background", noImage, domain);
                $Core.document.checkImage("img.item_document-image", noImage, domain);
			}, error: function(xhr, status, error) {
				console.log('Có lỗi xảy ra!!!! => ', error);
			}, complete: function() {
				$Core.document.toggleTag();
				$Core.document.checkFilter();
				$Core.util.toggleIndicatior(0);
                $Core.document.displayToggleActiveProject();
			}
		});
	}, checkFilter: function() {
		let keyword = $('.search-keyword').val();
		if(!$Core.util.isEmpty(keyword)){
			let htmlFilter = `<a class="tag_filter_document_item">
				<b>Tìm kiếm:</b> ${keyword} <i class='bx bxs-tag-x cursor-pointer' onClick="$Core.document.clear_keyword(this, event);"></i>
			</a>`;
            $('.group-filter-keyword').empty().append(htmlFilter);
		}
        let activeProjectPropertyEl = $('.project_property_item.active');
        if (activeProjectPropertyEl.length > 0) {
            let elProjectPropertyLink = activeProjectPropertyEl.find('.project_property_link'),
				project_property_type = elProjectPropertyLink.data('type'),
				project_property_id = elProjectPropertyLink.data('project-property-id'),
				project_property_name = elProjectPropertyLink.data('project-property-name');
            if (typeof project_property_id != 'undefined' && typeof project_property_type != 'undefined' 
				&& !$('.tag_filter_document_item').hasClass('project-'+project_property_id)) {
                let title = 'Dự án';
				if (project_property_type == 'block') {
                    title = 'Phân khu';
                } else if (project_property_type == 'building') {
                    title = 'Tòa';
                }
                let htmlFilter = `<a class="tag_filter_document_item tag_type_project project-${project_property_id} ms-2" href="javascript:void(0);"><b>${title}:</b> ${project_property_name} <i class='bx bxs-tag-x' onClick="$Core.document.clear_project(this, event);" data-project-property-id="${project_property_id}"></i></a>`;
                $('.group-filter-project').append(htmlFilter);
            }
        }
		let tagDocumentItemActiveEl = $('.area_document .tag_document_item.active');
		if (tagDocumentItemActiveEl.length > 0) {
			tagDocumentItemActiveEl.each((_i, _elem) => {
				let tag_id = $(_elem).data('tag-id'),
					tag_name = $(_elem).text().trim();
				if (!$('.tag_filter_document_item').hasClass('tag_'+tag_id)) {
					let htmlFilter = `<a class="tag_filter_document_item tag_type_tag tag_${tag_id} ms-2" data-tag-id="${tag_id}" data-tag-name="${tag_name}" href="javascript:void(0);"><b>Tag:</b> ${tag_name} <i class="bx bxs-tag-x" onclick="$Core.document.clear_tag(this, event);" data-tag-id="${tag_id}" data-tag-name="${tag_name}"></i></a>`;
					$('.group-filter-tags').append(htmlFilter);
				}
			});
		}
		$Core.document.displayFilter();
	}, displayToggleActiveProject: () => {
        let activeItemProjectProperty = $('.project_property_item.active');
        if (activeItemProjectProperty.length > 0) {
            activeItemProjectProperty.parents('.submenu').show();
        }
    }, toggleTag: () => {
		let $container = $(".tag_need_toggle");
		if ($container.length > 0) {
			$container.each(function() {
				$Core.document.showCollapsed(this);
			})
		}
	}, clickTagToggle: (_this, e) => {
		let $container = $(_this).parent('.tag_need_toggle');
		if ($(_this).text().includes("Thu gọn")) {
			$Core.document.showCollapsed($container[0]);
		} else {
			$Core.document.showExpanded($container[0]);
		}
	}, showCollapsed: (_this) => {
		let $container = $(_this);
		let $tags = $container.find(".tag_document_item");
		let maxTags = $(_this).data('length');
		$tags.hide().slice(0, maxTags).show();
		$container.find(".tag_toggle").remove();
		if ($tags.length > maxTags) {
			$container.append(`<a href="javascript:void(0);" class="tag_toggle" onClick="$Core.document.clickTagToggle(this, event)"><br>>> Xem thêm (${$tags.length - maxTags} tag)</a>`);
		}
	}, showExpanded:(_this) => {
		let $container = $(_this);
		let $tags = $container.find(".tag_document_item");
		$tags.show();
		$container.find(".tag_toggle").remove();
		$container.append(`<a href="javascript:void(0);" class="tag_toggle" onClick="$Core.document.clickTagToggle(this, event)"><< Thu gọn</a>`);
	}, select_category: (_this, e) => {
		e.preventDefault();
		let categoryEl = $(_this).parent('.category_item');
		let cat_id = $(_this).data('cat-id');
		let cat_name = $(_this).data('cat-name');
		if (categoryEl.hasClass('active')) {
			// click bỏ lọc
			$('.category_item').removeClass('active');
			$('.category-'+cat_id).remove();
		} else {
			// click để lọc
			$('.category_item').removeClass('active');
			let htmlFilter = `<a class="tag_filter_document_item tag_type_category category-`+cat_id+` ms-2" href="javascript:void(0);"><b>Danh mục:</b> `+cat_name+` <i class='bx bxs-tag-x' onClick="$Core.document.clear_category(this, event);" data-cat-id="`+cat_id+`"></i></a>`;
			$('.group-filter-category').html(htmlFilter);
			categoryEl.addClass('active');
		}
        $Core.document.displayFilter();
		$('input[name=current_page]').val(1);
		$Core.document.list_docs();
		// if ($(window).width() <= 1024) {
		// 	$Core.document.displayMenu('#categoryMenu');
		// }
		// $('html, body').animate({
		// 	scrollTop: $(".classContentPage").offset().top
		// }, 800);
	}, select_project: (_this, e) => {
		e.preventDefault();
		let projectEl = $(_this).parent('.project_property_item');
		let project_property_id = $(_this).data('project-property-id');
		let project_property_type = $(_this).data('type');
		let project_property_name = $(_this).data('project-property-name');
		if (projectEl.hasClass('active')) {
			// click bỏ lọc
			$('.project_property_item').removeClass('active');
			$('.project-'+project_property_id).remove();
		} else {
            let title = 'Dự án';
            if (project_property_type == 'block') {
                title = 'Phân khu';
            } else if (project_property_type == 'building') {
                title = 'Tòa';
            }
			$('.project_property_item').removeClass('active');
			let htmlFilter = `<a class="tag_filter_document_item tag_type_project project-`+project_property_id+` ms-2" href="javascript:void(0);"><b>`+title+`:</b> `+project_property_name+` <i class='bx bxs-tag-x' onClick="$Core.document.clear_project(this, event);" data-project-property-id="`+project_property_id+`"></i></a>`;
			$('.group-filter-project').html(htmlFilter);
			projectEl.addClass('active');
			$('.group-filter-tags').empty();
		}
        $Core.document.displayFilter();
		$('input[name=current_page]').val(1);
		$Core.document.list_docs();
	}, select_tag: (_this, e) => {
		e.preventDefault();
		let tag_id = $(_this).data('tag-id'),
			tag_name = $(_this).text();
		if ($(_this).hasClass('active')) {
			// click bỏ lọc
			$('.tag_document_item_'+tag_id).each(() => {
				$(this).removeClass('active');
			})
			$(_this).removeClass('active');
			$('.tag_'+tag_id).remove();
		} else {
			$(_this).addClass('active');
			// click để lọc
			let htmlFilter = `<a class="tag_filter_document_item tag_type_tag tag_`+tag_id+` ms-2" href="javascript:void(0);" data-tag-id="`+tag_id+`" data-tag-name"`+tag_name+`" ><b>Tag:</b> `+tag_name+` <i class='bx bxs-tag-x' onClick="$Core.document.clear_tag(this, event);" data-tag-id="`+tag_id+`" data-tag-name"`+tag_name+`"></i></a>`;
			$('.group-filter-tags').append(htmlFilter);
		}
        $Core.document.displayFilter();
		$('input[name=current_page]').val(1);
		$Core.document.list_docs({});
	}, clickform: (_this, e) => {
		e.preventDefault();
		$('input[name=current_page]').val(1);
        let keyword = $('input[name=keyword]').val();
        if (keyword.trim().length > 0) {
            $('.keyword_search').removeClass('d-none');
            $('.keyword_search').html('<b>Tìm kiếm:</b> '+ keyword);
        } else {
            $('.keyword_search').addClass('d-none');
            $('.keyword_search').html('');
        }
		$Core.document.displayFilter();
		$Core.document.list_docs();
	}, clickToggleCat: (_this, e) => {
		e.stopPropagation();
        let submenu = $(_this).siblings(".submenu");
        submenu.slideToggle(200);
        if ($(_this).find('i').hasClass('bx-chevron-down')) {
            $(_this).html("<i class='bx bx-chevron-right'></i>")
        } else {
            $(_this).html("<i class='bx bx-chevron-down'></i>")
        }
	}, clear_category: (_this, e) => {
		e.preventDefault();
		let cat_id = $(_this).data('cat-id');
		$('.category_item_'+cat_id).removeClass('active');
		$(_this).parent('.tag_filter_document_item').remove();
		$Core.document.displayFilter();
		$Core.document.list_docs();
	}, clear_project: (_this, e) => {
		e.preventDefault();
		let project_property_id = $(_this).data('project-property-id');
        console.log('project_property_id',project_property_id);
		$('.project_property_item_'+project_property_id).removeClass('active');
		$(_this).parent('.tag_filter_document_item').remove();
		$Core.document.displayFilter();
		$Core.document.list_docs();
	}, clear_tag: (_this, e) => {
		e.preventDefault();
		let tag_id = $(_this).data('tag-id');
		$('.tag_document_item_'+tag_id).removeClass('active');
		$(_this).parent('.tag_filter_document_item').remove();
		$Core.document.displayFilter();
		$Core.document.list_docs();
	}, clear_keyword : (_this, e) => {
		e.preventDefault();
		$('.search-keyword').val("");
		$(_this).closest('.tag_filter_document_item').remove();
		$Core.document.displayFilter();
		$Core.document.list_docs();
		return false;
	}, clear_projectx: (_this, e) => {
		e.preventDefault();
		let project_id = $(_this).data('project-id');
		$('.item_project_'+project_id).prop("checked", false);;
		$(_this).parent('.tag_filter_document_item').remove();
		$Core.document.displayFilter();
		$Core.document.list_docs();
	}, displayFilter: () => {
		var total_filters = 0;
		if($('.group-filter-keyword .tag_filter_document_item').length){
			total_filters += 1;
		}
		if($('.group-filter-category .tag_filter_document_item').length){
			total_filters += 1;
		}
		if($('.group-filter-tags .tag_filter_document_item').length){
			total_filters += 1;
		}
		if($('.group-filter-project .tag_filter_document_item').length){
			total_filters += 1;
		}
		if(total_filters > 0){
			$('.area-filter').removeClass('d-none');
		} else {
			$('.area-filter').addClass('d-none');
		}
	}, bookmarked: (_this, e) => {
        e.preventDefault();
		var doc_id = $(_this).attr("doc_id"),
			sheet_id = $(_this).attr("sheet_id"),
			$_adata = {"sheet_id":sheet_id,"doc_id":doc_id};
		if($(_this).hasClass("saved")) {
			$(_this).removeClass("saved");
			$_adata["action"] = "unsave";
		}else{
			$(_this).addClass("saved");
			$_adata["action"] = "save";
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=bookmarked', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);			
		},'json');
		return false;
    }, set_bookmarked: (_this, e) => {
        e.preventDefault();
		var doc_bookmarked = $(_this).is(':checked') ? 1 : 0;
		$.post('/index.php?mod='+MOD+'&act=set_bookmarked', {
			'doc_bookmarked' : doc_bookmarked
		}, function(respJson){
			$('input[name=current_page]').val(1);
			 $Core.document.list_docs();
		},'json');
    }, chooseProject: (_this, e) => {
        e.preventDefault();
         if ($(_this).is(':checked')) {
            let project_id = $(_this).val();
            let project_name = $(_this).parent('.projectItem').text().trim();
            let htmlFilter = `<a class="tag_filter_document_item tag_type_project project_`+project_id+` ms-2" href="javascript:void(0);" data-project-id="`+project_id+`" data-project-name"`+project_name+`" ><b>Dự án:</b> `+project_name+` <i class='bx bxs-tag-x' onClick="$Core.document.clickClearFilterProject(this, event);" data-project-id="`+project_id+`" data-project-name"`+project_name+`"></i></a>`;
            $('.group-filter-project').append(htmlFilter);
			$Core.document.displayFilter();
        }
        $Core.document.list_docs();
    },
    select_this: (_this, e) => {
        e.preventDefault();
        if ($(_this).hasClass('active')) {
            $(_this).removeClass('active');
        } else {
            $(_this).addClass('active');
        }
    },
    store_project_setting: (_this, e) => {
        e.preventDefault();
		var ii = 0, project_care = {};
		$('.item-project-care').each((_i, _elem) => {
			if($(_elem).find('.btn-project-care').hasClass('active')){
				project_care[ii] = $(_elem).find('.btn-project-care').data('project_id');
				++ii;
			}
		});
		$.post('/index.php?mod='+MOD+'&act=store_project_setting', {
			'project_care' : project_care
		}, function(respJson){
			if (myModal) {
				myModal.hide();
			}
			$Core.document.list_docs();
		},'json');
    }, displayMenu: (el) => {
        if ($(el).hasClass('show')) {
            $(el).css('transition', 'none');
            $(el).removeClass('show');
            $(el).parent('.box-filter').find('.toggle-card').removeClass('bx-chevron-up').addClass('bx-chevron-down');
            $(el).collapse('hide');
            $(el).css('transition', '');
        }
    }, checkImage: (el, imageDefault = '', domain = '') => {
        $(el).each(function() {
            let $img = $(this);
            let src = $img.attr("src");
            if (this.complete && this.naturalWidth > 0) {
				
			} else {
                if (!/^https?:\/\//i.test(src)) {
                    src = domain + src;
                }
                let testImg = new Image();
                testImg.onload = function() {
                    $img.attr("src", src);
                };
                testImg.onerror = function() {
                    $img.attr("src", imageDefault);
                };
                testImg.src = src;
            }
        });
    }
}