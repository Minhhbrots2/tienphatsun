"use strict";
$(function(){
	if(MOD == 'issue' && ACT == 'target'){
		$Core.issue.load_issue_target({});
	}
	/* Router */
	$_document.on('click', '.goLink', function(){
		var $_this = $(this);
		if($_this.hasAttr('route')){
			/*$.router.set("#" +$_this.attr("route"));*/
			$Core.util.popstate('/issue.html#'+$_this.attr("route"));
		}else{
			/*$.unroute();*/
			$Core.util.dopopstate();
		}
	});
	$_document.on('change', '.js-toggle-checklist-item:not(.preventDefault)', function(ev){
		var _this = $(this),
			issue_id = _this.attr('issue_id'),
			issue_task_id = _this.attr('issue_task_id'),
			is_done = _this.is(':checked') ? 1 : 0;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=tick_issue_task', {
			'issue_id' : issue_id,
			'issue_task_id' : issue_task_id,
			'is_done' : is_done
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$Core.issue.load_issue_tasks(issue_id, {});
		});
	});
	$_document.on('change', '.issue_upload_file,.issue_task_upload_file', function(ev){
		var _this = $(this),
			_uid = _this.attr('uid'),
			_form = _this.closest('form');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=upload_multipe_file",
			data: {'_uid':_uid},
			success: function(html){
				_form.resetForm();
				$Core.util.toggleIndicatior(0);
				$('#'+'issue_attachments_file_'+_uid).append(html);
			}
		});
	});
	$_document.on('change', '.search_field', function(ev){
		$.each(['me','assign','related'], function(_key,_val) {
			$Core.issue.load_issues(_val, {});
		});
		$Core.issue.load_issue_kanban("",{});
	});
	/* Router */
	$.route("/calendar", function () {
		// $('.js__open-calendar').trigger('click');
	});
	$.router.init();
	/* End Router */
});

$Core.issue = {
	open_issue: (_this, e) => {
		e.preventDefault();
		var issue_id = $(_this).getAttr('issue_id', 0),
			parent_id = $(_this).getAttr('parent_id', 0),
			issue_type = $(_this).getAttr('issue_type', "_addissue");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=issue&act=open_issue', {
			'issue_id' : issue_id,
			'parent_id' : parent_id,
			'issue_type' : issue_type
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			if($('.slb_participants:not(.open_issue)').length){
				$('.slb_participants:not(.open_issue)').addClass('open_issue').multiselect({
					header: true,
					enableFiltering: true,
					buttonWidth: '100%',
					buttonTextAlignment: 'left',
					nonSelectedText: "Lựa chọn",
					nSelectedText: " lựa chọn",
					filterPlaceholder: "Tìm kiếm"
				}).on('change', function(e){
					var participants = $(this).val();
					$.post(PCMS_URL+'/index.php?mod=issue&act=issue_participants', {
						'participants' : participants
					}, function(html){
						$('.issue_participants_'+issue_id).html(html);
					});
				});
			}
		}, 'json');
		return false;
	}, pop_save_issue: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			issue_id = $(_this).attr('issue_id'),
			$_adata = {'issue_id':issue_id};
		if($('select.required:visible,input.required:visible', _form).length){
			$('select.required:visible,input.required:visible', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if($('.isoTextArea', _form).length){
			$('.isoTextArea', _form).each((_i, _elem) => {
				var name = $(_elem).data('name'),
					editorId = $(_elem).attr('id');
				$_adata[name] = $Core.util.getTinyMCEContent(editorId);
			});
		}
		if(_validated==0){
			$(_this).addClass('clicked');
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL+"/index.php?mod=issue&act=pop_save_issue",
				data: $_adata,
				success: function(html){
					$(_this).removeClass('clicked');
					if(html.indexOf('_success') >= 0){
						var tmp = html.split('|||');
						$('.btn-close', _form).trigger('click');
						if($('.issue_page').length){
							$Core.issue.load_issues(tmp[1], {});
							$Core.issue.load_issue_kanban("",{});
						} else {
							$Core.swal.success("Thông báo", "Tạo công việc thành công !")
						}
					} else if(html.indexOf('_error') >= 0){
						$Core.swal.error( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} else if(html.indexOf('_invalid') >= 0){
						var tmp = html.split('|||');
						$Core.swal.error( "Oops", tmp[1]);
					}else if(html.indexOf('_duplicated') >= 0){
						$Core.swal.error( "Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại");
					}
				}
			});
		}
		return false;
	}, view_issue : (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		var issue_id = $(_this).attr('issue_id');
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=view_issue', {
			'issue_id' : issue_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			if(__w > 1366){
				$Core.popup.openfull(__w/2,respJson.html,respJson.uid);
			} else {
				$Core.popup.openfull(__w/3,respJson.html,respJson.uid);
			}
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				$Core.issue.load_issue_notes(issue_id, {});
				$Core.issue.load_issue_tasks(issue_id, {});
				$Core.issue.load_issues('_child', {'issue_id':issue_id});
				if($('#'+respJson.uid+' .timeCountUp:not(installed)').length){
					setInterval(function(){
						$('#'+respJson.uid+' .timeCountUp:not(installed)').each((_i, _elem) => {
							var time_shown = $(_elem).text(),
								time_chunks = time_shown.split(":"),
								time_sp = time_shown.split(' ');
							var ex = '';
							if(time_sp[1]=='ngày'||time_sp[1]=='days'){
								time_chunks = time_sp[2].split(":");
								if(time_sp[1]=='ngày')
									ex = time_sp[0]+' ngày ';
								else
									ex = time_sp[0]+' days ';
							}
							var hour, mins, secs;
							hour = Number(time_chunks[0]);
							mins = Number(time_chunks[1]);
							secs = Number(time_chunks[2]);
							secs ++;
							if (secs==60){
								secs = 0;
								mins=mins + 1;
							}
							if (mins==60){
								mins=0;
								hour=hour + 1;
							}
							$(_elem).addClass('installed').text(ex+$Core.util.plz(hour) + ":" + $Core.util.plz(mins) + ":" + $Core.util.plz(secs));
						});
					},1000);
				}
			});
		}, 'json');
		return false;
	}, do_search : (_this, e) => {
		e.preventDefault();
		var current_view = $('.js__rdo-view:checked').val();
		if(current_view == 'grid'){
			$.each(['_all','_working'], function(_key, _val) {
				$Core.issue.load_issues(_val, {});
			});
		} else {
			$Core.issue.load_issue_kanban("",{});
		}
		return false;
	}, filter_status : (_this, e) => {
			e.preventDefault();
			var $chip = $(_this),
				status = $chip.data('status'),
				$field = $('.search_field[data-field=status_id]');
			if($chip.hasClass('active')){
				$chip.removeClass('active');
				$field.val(0);
			} else {
				$('.issue-chip').removeClass('active');
				$chip.addClass('active');
				$field.val(status);
			}
			$Core.issue.do_search(_this, e);
			return false;
		}, load_issue_notes : (issue_id, options) => {
		var $_adata = options || {};
		$_adata['issue_id'] = issue_id;
		$.post('/index.php?mod='+MOD+'&act=load_issue_notes', $_adata, function(respJson) {
			$Core.util.toggleIndicatior(0);
			$(".holder_issue_notes_"+issue_id).html(respJson.html);
		}, 'json');
	}, add_issue_note : (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			issue_id = $(_this).attr('issue_id'),
			upload_id = $(_this).attr('upload_id');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type:'POST',
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=add_issue_note",
			data: {'issue_id':issue_id},
			success: function(html){
				$Core.util.toggleIndicatior(0);
				_form.clearForm();
				_form.resetForm();
				$Core.util.clearEditor(_form); 
				$Core.issue.load_issue_notes(issue_id, {});
				$('#issue_attachments_file_'+upload_id).empty();
			}
		});
		return false;
	}, add_issue_task: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			issue_id = $(_this).attr('issue_id');
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=add_issue_task', {
			'toId' : toId,
			'issue_id' : issue_id
		}, function(html) {
			$Core.util.toggleIndicatior(0);
		   $('#'+toId).addClass('d-none').before(html);
			$('.textarea_issue_task_'+toId).focus();
		});
		return false;
	} , cancel_add_task: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			action = $(_this).attr('action'),
			issue_id = $(_this).attr('issue_id');
		if(action=='_add'){
			$('.'+toId).remove();
			$('#'+toId).removeClass('d-none');
		} else {
			$('.'+toId).addClass('d-none');
			$('.'+toId).prev().removeClass('d-none');
			$('.'+toId).closest('.checklist-item-details').removeClass('editing');
		}
		return false;
	}, save_add_task: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			issue_id = $(_this).attr('issue_id'),
			issue_task_id = $(_this).attr('issue_task_id');
		if($('textarea.required', _form).length){
			$('textarea.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_add_task",
				data: {'issue_id':issue_id,'issue_task_id':issue_task_id},
				success: function(html){
					_form.resetForm();
					$Core.util.toggleIndicatior(0);
					$Core.issue.load_issue_tasks(issue_id, {});
					$('.add_task_cancel_'+issue_id).trigger('click');
				}
			});
		}
		return false;
	}, load_issue_tasks : (issue_id, options) => {
		var $_adata = options || {};
		$_adata['issue_id'] = issue_id;
		$.post('/index.php?mod='+MOD+'&act=load_issue_tasks', $_adata, function(respJson) {
			$('.holder_task_'+issue_id).html(respJson.html);
			$('.rajqXvSIGn_'+issue_id).html(respJson.total_udone);
			$('.entqeZXPZg_'+issue_id).html(respJson.total_done);
		}, 'json');
	}, edit_issue_task: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('.editing').each((_i, _elem) => {
			$(_elem).find('.js-cancel-edit').trigger('click');
		});
		$(_this).parent().addClass('d-none');
		$(_this).closest('.checklist-item-details').addClass('editing');
		$('.'+toId).removeClass('d-none');
		return false;
	}, delete_issue_task: (_this, e) => {
		e.preventDefault();
		var issue_id = $(_this).attr('issue_id'),
			issue_task_id = $(_this).attr('issue_task_id'),
			$_adata = {'issue_id':issue_id,'issue_task_id':issue_task_id};
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa công việc này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete_issue_task', $_adata, function(html) {
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_invalid') >= 0){
					$Core.alert.error("Bạn không có quyền xóa");
				} else if(html.indexOf('_error') >= 0){
					$Core.alert.error("Đã xảy ra lỗi trong quá trình xóa dữ liệu");
				} else {
					$Core.issue.load_issue_tasks(issue_id, {});
				}
			});
		});
		return false;
	}, delete_target: (_this, e) => {
		e.preventDefault();
		var issue_target_id = $(_this).attr('issue_target_id'),
			$_adata = {'issue_target_id':issue_target_id};
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa kế hoạch này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete_target', $_adata, function(html) {
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_invalid') >= 0){
					$Core.alert.error("Bạn không có quyền xóa");
				} else if(html.indexOf('_error') >= 0){
					$Core.alert.error("Đã xảy ra lỗi trong quá trình xóa dữ liệu");
				} else {
					$Core.issue.load_issue_target({});
				}
			});
		});
		return false;
	}, save_issue_pfield: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			issue_id = $(_this).attr('issue_id');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: "POST",
			url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_issue_pfield",
			data: { 'issue_id': issue_id  },
			dataType: "json",
			success: function(respJson) {
				$Core.util.toggleIndicatior(0);
				$Core.issue.load_issue_notes(issue_id, {});
				if(respJson.p_field=='done_ratio'){
					$('#done_ratio_'+issue_id).html(respJson.html);
				}
				$('.bs-webui-popover').webuiPopover('hideAll');
			}
		});
		return false;
	}, open_issue_edit: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field'),
			$_adata = {'p_id':p_id,'p_field':p_field,'toId':toId};
			
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=open_issue_edit', $_adata, function(respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, pop_save_issue_edit: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field'),
			$_adata = {'toId':toId, 'p_id':p_id, 'p_field':p_field};
		
		var _validated = 0,
			_form = $(_this).closest('form');
		if($('select.required:visible,input.required:visible', _form).length){
			$('select.required:visible,input.required:visible', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}	
		if($('.isoTextArea', _form).length){
			$('.isoTextArea', _form).each((_i, _elem) => {
				var name = $(_elem).data('name'),
					editorId = $(_elem).attr('id');
				$_adata[name] = $Core.util.getTinyMCEContent(editorId);
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type:'POST',
				url: "/index.php?mod="+MOD+"&act=pop_save_issue_edit",
				data: $_adata,
				success: function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
					var tmp = html.split('|||');
						$('#'+toId).html(tmp[1]);
						$('.btn-close', _form).trigger('click');
						if(p_field=='assign_to_id'){
							$Core.issue.load_issue_notes(p_id, {});
						}
					} else if(html.indexOf('_error') >= 0){
						$Core.swal.error( "Oops","Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} else if(html.indexOf('_duplicated') >= 0){
						$Core.swal.error( "Oops","Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại");
					}
				}
			});
		}
	}, set_mention_list : (_this, e) => {
		e.preventDefault();
		var gid = $(_this).attr('gid');
		$('#'+gid).toggleClass('d-none');
		return false;
	}, set_fireEvent : (_this, e) => {
		var _keyCode = e.keyCode || e.which,
			_form = $(_this).closest('form');
		if(_keyCode===13){
			//$('.js-add-task', _form).trigger('click');
		} else if(_keyCode===27){
			$('.js-cancel-task', _form).trigger('click');
		}
	},  delete_issue : (_this, e) => {
		e.preventDefault();
		var issue_id = $(_this).attr('issue_id'),
			$_adata = {'issue_id':issue_id};
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa công việc này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete_issue', $_adata, function(html) {
				$Core.util.toggleIndicatior(0);
				if(html.indexOf('_doing') >= 0){
					$Core.alert.error("Công việc này đang thực hiện không được phép xóa!");
				} else if(html.indexOf('_error') >= 0){
					$Core.alert.error("Xóa công việc không thành công!");
				} else {
					if(ACT == 'issue') {
						$(_this).closest(".kanban-item").remove();
					}
					$Core.issue.load_issues('_all',{});
					$Core.alert.success("Xóa công việc thành công!");
				}
			});
		});
		return false;
	}, issue_notes_upload : (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid');
		$('#issue_task_upload_file_'+uid).trigger('click');
		return false;
	}, issue_task_upload : (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId');
		$('#issue_task_upload_file_'+toId).trigger('click');
		return false;
	}, click_href : (_this, e) => {
		e.preventDefault();
		var _tp = $(_this).attr('tp'),
			_href = $(_this).data('href');
		$('.nav-link').removeClass('active');
		$(_this).addClass('active');
		$Core.util.popstate(_href);
		$Core.issue.load_issue_report({'tp' : _tp});
		return false;
	}, load_issue_report: (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_issue_report', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.holder_report').html(respJson.html);
		}, 'json');
	}, toggle_myday: (_this, e) => {
		e.preventDefault();
		var on = $(_this).hasClass('active') ? 0 : 1;
		$(_this).toggleClass('active', on==1);
		$('.search_field[data-field=mine]').val(on);
		$Core.issue.do_search(_this, e);
		return false;
	}, load_calendar: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if($(_elem).is(':checkbox')){
					$_adata[field] = $(_elem).is(':checked') ? 1 : 0;
				} else {
					$_adata[field] = $(_elem).val();
				}
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_calendar', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#issue-calendar-holder').html(respJson.html);
		}, 'json');
	}, load_timeline: (options) => {
		var $_adata = options || {};
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if($(_elem).is(':checkbox')){
					$_adata[field] = $(_elem).is(':checked') ? 1 : 0;
				} else {
					$_adata[field] = $(_elem).val();
				}
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=load_timeline', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('#issue-timeline-holder').html(respJson.html);
		}, 'json');
	}, load_issues: (holderG, options) => {
		var $_adata = options || {};
		$_adata['holderG'] = holderG;	
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if($(_elem).is(':checkbox')){
					var _checked = $(_elem).is(':checked') ? 1 : 0;
					$_adata[field] = _checked;
				} else {
					if(typeof(field) !== 'undefined'){
						$_adata[field] = $(_elem).val();
					}
				}
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_issues', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			if(holderG == '_child'){
				$(`.holder_issue_${$_adata.issue_id}`).html(respJson.html);
			} else {
				$(`.holder_issue_${holderG}`).html(respJson.html);
			}
			if($(`.holder_issue_${holderG} .timeCountUp:not(installed)`).length){
				setInterval(() => {
					$(`.holder_issue_${holderG} .timeCountUp:not(installed)`).each((_i, _elem) => {
						var time_shown = $(_elem).text(),
							time_chunks = time_shown.split(":"),
							time_sp = time_shown.split(' ');
						var ex = '';
						if(time_sp[1]=='ngày'||time_sp[1]=='days'){
							time_chunks = time_sp[2].split(":");
							if(time_sp[1]=='ngày')
								ex = time_sp[0]+' ngày ';
							else
								ex = time_sp[0]+' days ';
						}
						var hour, mins, secs;
						hour=Number(time_chunks[0]);
						mins=Number(time_chunks[1]);
						secs=Number(time_chunks[2]);
						secs++;
						if (secs==60){
							secs = 0;
							mins=mins + 1;
						}
						if (mins==60){
							mins=0;
							hour=hour + 1;
						}
						$(_elem).addClass('installed').text(ex+$Core.util.plz(hour) +":" + $Core.util.plz(mins) + ":" + $Core.util.plz(secs));
					});
				},1000);
			}
			if(parseInt(respJson.total_page) > 1){
				$('input[name=hid_current_page_'+holderG+']').val(respJson.current_page);
				$('#pager_'+holderG).removeClass('d-none').pagination({
					listStyle:"pagination justify-content-center",
					currentPage: respJson.current_page, 
					itemsOnPage: respJson.per_page,
					items: respJson.total_record,
					cssStyle: 'light-theme',
					prevText : '<i class="tf-icon bx bx-chevrons-left"></i>',
					nextText : '<i class="tf-icon bx bx-chevrons-right"></i>',
					onPageClick : function(pageNumber){
						$Core.issue.load_issues(holderG,$.extend(options,{'page':pageNumber}));
					}
				});
			} else {
				$('#pager_'+holderG).addClass('d-none');
			}
		}, 'json');
	}, load_issue_target: (options) => {
		$Core.util.toggleIndicatior(1);
		var $_adata = options || {};
		$.post('/index.php?mod='+MOD+'&act=load_issue_target', $_adata, function(respJson) {
			$Core.util.toggleIndicatior(0);
			$('.holder_issue_target').html(respJson.html);
			let charts = document.getElementsByClassName('mkCharts');
			for(let i=0;i<charts.length;i++) {
				let chart = charts[i];
				let percent = chart.dataset.percent;
				let color = ('color' in chart.dataset) ? chart.dataset.color : "#2F4F4F";
				let size = ('size' in chart.dataset) ? chart.dataset.size : "100";
				let stroke = ('stroke' in chart.dataset) ? chart.dataset.stroke : "1";
				charts[i].innerHTML = $Core.issue.make_circle_chart(percent, color, size, stroke);
			}
		}, 'json');
	}, make_circle_chart : (percent, color, size, stroke) =>  {
		let svg = `<svg class="mkc_circle-chart" viewbox="0 0 36 36" width="${size}" height="${size}" xmlns="http://www.w3.org/2000/svg">
			<path class="mkc_circle-bg" stroke="#eeeeee" stroke-width="${stroke * 0.5}" fill="none" d="M18 2.0845
				  a 15.9155 15.9155 0 0 1 0 31.831
				  a 15.9155 15.9155 0 0 1 0 -31.831"/>
			<path class="mkc_circle" stroke="${color}" stroke-width="${stroke}" stroke-dasharray="${percent},100" stroke-linecap="round" fill="none"
				d="M18 2.0845
				  a 15.9155 15.9155 0 0 1 0 31.831
				  a 15.9155 15.9155 0 0 1 0 -31.831" />
			<text class="mkc_info" x="50%" y="50%" alignment-baseline="central" text-anchor="middle" font-size="8">${percent}%</text>
		</svg>`;
		return svg;
	}, add_target: (_this, e) => {
		e.preventDefault();
		var _body = $(_this).closest('.dropdown-body'),
			uid = $(_this).attr('uid'),
			issue_id = $(_this).attr('issue_id'),
			issue_target_id = $('select[name=issue_target_id]', _body).val();
		
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=add_target', {
			'issue_id' : issue_id,
			'issue_target_id' : issue_target_id
		}, function(html) {
			$Core.util.toggleIndicatior(0);
			$('.issue_target_'+uid).text(html).dropdown('toggle');;
		});
		return false;
	}, load_select_target: (_this, e) => {
		var _toId = $(_this).attr('toId'),
			department_id = $(_this).val();
		$('.issue_target_'+_toId).html(`<select class="iso-selectizeNotSearch" data-url="/index.php?mod=${MOD}&act=get_issue_target&department_id=${department_id}" data-optgroup="false" placeholder="Chọn mục tiêu công việc"></select>`);
		$Core.init();
	}, open_target: (_this, e) => {
		e.preventDefault();
		var issue_target_id = $(_this).attr('issue_target_id'),
			$_adata = {'issue_target_id' : issue_target_id};
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=open_target', $_adata, function(respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, `open_target_${respJson.uid}`);
			$('#attachments_'+respJson.uid).MultiFile({
				list: '#MultiFile-preview_'+respJson.uid
			});
		}, 'json');
		return false;
	}, save_issue_target: (_this, e) => {
		e.preventDefault();
		var _validated = 0, 
			_form = $(_this).closest('form'),
			issue_target_id = $(_this).attr('issue_target_id'),
			$_adata = { 'issue_target_id' : issue_target_id };
		if($('select.required:visible,input.required:visible', _form).length){
			$('select.required:visible,input.required:visible', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if($('.isoTextArea', _form).length){
			$('.isoTextArea', _form).each((_i, _elem) => {
				var name = $(_elem).data('name'),
					editorId = $(_elem).attr('id');
				$_adata[name] = $Core.util.getTextAreaContent(editorId);
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: "POST",
				url: PCMS_URL + "/index.php?mod="+MOD+"&act=save_issue_target",
				data: $_adata,
				dataType: "html",
				success: function(html) {
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$Core.issue.load_issue_target({'page':1});
						$('.btn-close', _form).trigger('click');
					} else {
						$Core.swal.error( "Oops","Đã xảy ra lỗi! Xin vui lòng thử lại");
					}
				}
			});
		}
		return false;
	}, open_issue_quick: (_this, e) => {
		e.preventDefault();
		var issue_target_id = $(_this).attr('issue_target_id'),
			$_adata = {'issue_target_id' : issue_target_id};
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=open_issue_quick', $_adata, function(respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
		return false;
	}, archive: function(_this, e){
		e.preventDefault();
		var holderG = $(_this).attr('holderG'),
			issue_id = $(_this).attr('issue_id'),
			page = $('input[name=hid_current_page_'+holderG+']').val(),
			$_adata = {'issue_id' : issue_id};
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=archive_issue', $_adata, function(html) {
			$Core.util.toggleIndicatior(0);
			$Core.issue.load_issues(holderG, {'page':page});
		}, 'json');
		return false;
	}, open_issue_notes : function (_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			issue_id = $(_this).attr('issue_id'),
			issue_note_id = $(_this).attr('issue_note_id'),
			$_adata = {'tp':tp, 'issue_id':issue_id, 'issue_note_id':issue_note_id};
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=open_issue_notes', $_adata, function(respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		},'json');
		return false;
	}, pop_save_issue_notes: function (_this, e){
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			issue_id = $(_this).attr('issue_id'),
			issue_note_id = $(_this).attr('issue_note_id'),
			$_adata = {'tp':tp, 'issue_id':issue_id, 'issue_note_id':issue_note_id};
		
		var _validated = 0, _form = $(_this).closest('form');
		if($('select.required:visible,input.required:visible,textarea.required', _form).length){
			$('select.required:visible,input.required:visible,textarea.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			var action = 'pop_save_issue_notes';
			if(tp == 'add') {
				action = 'add_issue_note';
			}
			_form.ajaxSubmit({
				type: "POST",
				url: PCMS_URL + "/index.php?mod="+MOD+"&act="+action,
				data: $_adata,
				dataType: "html",
				success: function(html) {
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_success') >= 0){
						$Core.issue.load_issue_notes(issue_id, {});
						$('.btn-close', _form).trigger('click');
					} else {
						$Core.swal.error( "Oops","Đã xảy ra lỗi! Xin vui lòng thử lại");
					}
				}
			});
		}
		return false;
	}, delete_notes: function(_this, e){
		var issue_id = $(_this).attr('issue_id'),
			issue_note_id = $(_this).attr('issue_note_id'),
			$_adata = {'issue_id':issue_id, 'issue_note_id':issue_note_id};
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa công việc này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod='+MOD+'&act=delete_issue_notes', $_adata, function(html) {
				$Core.util.toggleIndicatior(0);
				$Core.issue.load_issue_notes(issue_id, {});
			});
		});
	}, done_issue :(_this, e) => {
		e.preventDefault();
		var issue_id = $(_this).attr('issue_id'),
			$_adata = {'issue_id' : issue_id };
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=done_issue', $_adata, function(respJson) {
			$Core.util.toggleIndicatior(0);
			if(respJson.msg.indexOf('_success') >= 0){
				$Core.issue.load_issues('_all',{});
				$Core.issue.load_issues('_working',{});
				$Core.issue.load_issue_notes(issue_id, {});
				$('#done_ratio_'+issue_id).html(respJson.html);
				$(`.time-issue-${issue_id}`).removeClass('timeCountUp');
				$(`.issue-action-${issue_id}`).html(respJson.html_buttons);
			} else {
				$Core.alert.error('Lỗi');
			}
		}, 'json');
		return false;
	}, stop_issue :(_this, e) => {
		e.preventDefault();
		var issue_id = $(_this).attr('issue_id'),
			$_adata = {'issue_id' : issue_id };
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=stop_issue', $_adata, function(respJson) {
			$Core.util.toggleIndicatior(0);
			if(respJson.msg.indexOf('_success') >= 0){
				$Core.issue.load_issues('_all',{});
				$Core.issue.load_issues('_working',{});
				$Core.issue.load_issue_notes(issue_id, {});
				$(`.time-issue-${issue_id}`).removeClass('timeCountUp');
				$(`.issue-action-${issue_id}`).html(respJson.html_buttons);
			} else {
				$Core.alert.error('Lỗi');
			}
		}, 'json');
		return false;
	}, start_issue :(_this, e) => {
		e.preventDefault();
		var issue_id = $(_this).attr('issue_id'),
			$_adata = {'issue_id' : issue_id };
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod='+MOD+'&act=start_issue', $_adata, function(respJson) {
			$Core.util.toggleIndicatior(0);
			if(respJson.msg.indexOf('_success') >= 0){
				$Core.issue.load_issues('_all',{});
				$Core.issue.load_issues('_working',{});
				$Core.issue.load_issue_notes(issue_id, {});
				$(`.time-issue-${issue_id}`).addClass('timeCountUp');
				$(`.issue-action-${issue_id}`).html(respJson.html_buttons);
			} else {
				$Core.alert.error('Lỗi');
			}
		}, 'json');
		return false;
	}, load_issue_kanban: (holderG,options) => {
		var $_adata = options || {};
		$_adata['holderG'] = holderG;	
		if($('.search_field').length){
			$('.search_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if($(_elem).is(':checkbox')){
					var _checked = $(_elem).is(':checked') ? 1 : 0;
					$_adata[field] = _checked;
				} else {
					if(typeof(field) !== 'undefined'){
						$_adata[field] = $(_elem).val();
					}
				}
			});
		}
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=load_issue_kanban', $_adata, function(respJson){
			if(respJson.type == "load_more"){
				$("#showmorethisresult_"+respJson.status_id).before(respJson.html);
				$("#showmorethisresult_"+respJson.status_id).find(".showmorethisresult").attr("page",respJson.current_page);
				if(respJson.current_page >= respJson.total_page) {
					$("#showmorethisresult_"+respJson.status_id).find(".showmorethisresult").hide();
				}
			}else{
				$("#kanban-canvas").html(respJson.html);
				if($(".ui-sortable-handle",$("#kanban-canvas")).length > 0) {
					$(".kanban-drag").sortable("refresh");
				}else{
					$(".kanban-drag").sortable({
						items: ".kanban-item:not(.unsortable)",
						connectWith: ".kanban-drag",
						update: function (event, ui) {
							if(ui.sender != null){
								var status_id = this.id;
								var p_id = ui.item.attr('id');
								var p_field = "status_id";
								$Core.util.toggleIndicatior(1);
								console.log({"status_id":status_id,"p_id":p_id,"p_field":p_field});
								$.post('/index.php?mod='+MOD+'&act=pop_save_issue_edit', {"status_id":status_id,"p_id":p_id,"p_field":p_field}, function(html) {
									$Core.util.toggleIndicatior(0); 
									$Core.issue.load_issue_kanban(holderG,options);
								});

							}
						}
					}).disableSelection();
				}				
			}			
			
		}, 'json');
	}, load_more_kanban: (_this,e) => {
		var $_adata = options || {},
			options = $(_this).data("options"),
			status_id = $(_this).data("status"),
			page = $(_this).attr("page");
	
		$_adata['status'] = status_id;
		$_adata['page'] = parseInt(page) + 1;
		$_adata['type'] = "load_more";
		$Core.issue.load_issue_kanban($_adata["holderG"],$_adata);
	}, set_view: (_this, e) => {
		var call_from = $(_this).getAttr('call_from', 'issue'),
			view = $('input[name=view]:checked').val();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod='+MOD+'&act=set_view', {
			'view' : view,
			'call_from' : call_from
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			window.location.reload(true);
		});
	},
};