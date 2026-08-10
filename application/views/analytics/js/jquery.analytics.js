/* Dashboard BOD (module analytics) — render biểu đồ CanvasJS + focus tab + tooltip.
   Chart server-render: <div class="an-chart" data-ck="growth|dau|gen|pkg" data-points='[...]'>.
   renderCharts() đọc data-* -> CanvasJS.Chart. Chỉ render chart trong tab đang mở
   (tab ẩn width=0 -> sai kích thước); các tab còn lại render khi shown.bs.tab. */
(function(){
	if (typeof window.$Core === 'undefined') { window.$Core = {}; }

	$Core.analytics = {
		loadPanel: function(el){
			var $el = $(el);
			var url = $el.attr('data-url');
			if(!url){ return; }
			var params = $el.data('params') || {};
			$.post(url, params, function(res){
				$el.addClass('loaded');
				if(!res){ $el.html('<div class="text-muted py-3 text-center">Không có dữ liệu</div>'); return; }
				$el.html(res.html || '');
				if(typeof res.draw_chart !== 'undefined' && res.draw_chart == 1){
					try {
						if(typeof res.multi_chart !== 'undefined' && res.multi_chart == 1 && $Core.chart && $Core.chart.canvas_multi){
							$Core.chart.canvas_multi(res.uid, res.barChartData);
						} else if($Core.chart && $Core.chart.canvas){
							$Core.chart.canvas(res.uid, res.barChartData);
						} else if(typeof CanvasJS !== 'undefined'){
							var opt = res.barChartData || {};
							new CanvasJS.Chart(res.uid, {
								animationEnabled: true,
								axisY: { includeZero: true },
								data: [ opt.data ]
							}).render();
						}
					} catch(e){ if(window.console){ console.warn('analytics chart error', e); } }
				}
			}, 'json');
		},
		loadPane: function(scope){
			$(scope).find('.analytics-ajax').each(function(){
				if(!$(this).hasClass('loaded')){ $Core.analytics.loadPanel(this); }
			});
		},
		// Vào dashboard bằng điều hướng mới (URL không kèm ?tab=...) -> luôn focus tab đầu tiên (Tăng trưởng).
		// Reload do đổi bộ lọc (form GET gắn tab=...) -> giữ nguyên tab hiện tại. No-op nếu tab đầu đã active.
		focusFirstTab: function(){
			if(/[?&]tab=/.test(window.location.search || '')){ return; }
			var $first = $('.crm-ld-tabs .nav-link').first();
			if(!$first.length || $first.hasClass('active')){ return; }
			try {
				if(window.bootstrap && bootstrap.Tab){
					bootstrap.Tab.getOrCreateInstance($first[0]).show();
				} else {
					$('.crm-ld-tabs .nav-link').removeClass('active').attr('aria-selected','false');
					$('.tab-content > .tab-pane').removeClass('show active');
					$first.addClass('active').attr('aria-selected','true');
					var tgt = $first.attr('data-bs-target') || '';
					if(tgt){ $(tgt).addClass('show active'); $('#analytics-tab').val(tgt.replace('#','')); }
				}
			} catch(e){ if(window.console){ console.warn('analytics focusFirstTab error', e); } }
		},
		// Parse JSON từ data-* (server json_encode); lỗi -> [].
		_parse: function($el, name){
			var raw = $el.attr('data-' + name);
			if(!raw){ return []; }
			try { return JSON.parse(raw); } catch(e){ return []; }
		},
		// Render mọi .an-chart trong scope (tab). Guard data('rendered') tránh vẽ lại.
		renderCharts: function(scope){
			if(typeof CanvasJS === 'undefined'){ return; }
			var A = $Core.analytics;
			var axisXcat = { labelFontSize:11, labelFontColor:"#8592a3", lineColor:"#ebedf0", tickColor:"#ebedf0" };
			var axisXday = { interval:4, labelFontSize:10, labelFontColor:"#8592a3", lineColor:"#ebedf0", tickColor:"#ebedf0" };
			var axisY    = { includeZero:true, labelFontSize:10, labelFontColor:"#8592a3", gridColor:"#f0f1f3", lineThickness:0, tickLength:0 };
			var tip      = { fontSize:12, cornerRadius:6 };
			(scope ? $(scope) : $(document)).find('.an-chart').each(function(){
				var el = this, $el = $(el);
				if($el.data('rendered') || !el.id){ return; }
				var type = $el.attr('data-ck'), cfg = null;
				if(type === 'dau'){
					cfg = { animationEnabled:true, backgroundColor:"transparent", axisX:axisXday,
						axisY:{ includeZero:false, labelFontSize:10, labelFontColor:"#8592a3", gridColor:"#f0f1f3", lineThickness:0, tickLength:0 },
						toolTip:tip,
						data:[{ type:"splineArea", color:"#696cff", fillOpacity:.10, lineThickness:2.5, markerSize:5, markerColor:"#696cff",
							toolTipContent:"Ngày {label}: <b>{y}</b> user active", dataPoints: A._parse($el,'points') }] };
				} else if(type === 'growth'){
					cfg = { animationEnabled:true, backgroundColor:"transparent", axisX:axisXcat, axisY:axisY, toolTip:tip,
						data:[{ type:"column", color:"#696cff", cornerRadius:4,
							toolTipContent:"Tháng {label}: <b>{y}</b> user mới", dataPoints: A._parse($el,'points') }] };
				} else if(type === 'gen'){
					cfg = { animationEnabled:true, backgroundColor:"transparent", axisX:axisXcat, axisY:axisY, toolTip:tip,
						data:[{ type:"column", color:"#696cff", cornerRadius:4,
							toolTipContent:"Thế hệ {label}: <b>{y}</b> người", dataPoints: A._parse($el,'points') }] };
				} else if(type === 'pkg'){
					cfg = { animationEnabled:true, backgroundColor:"transparent", axisX:axisXcat, axisY:axisY,
						toolTip:{ shared:true, fontSize:12, cornerRadius:6 },
						legend:{ fontSize:11, fontColor:"#8592a3", verticalAlign:"bottom", horizontalAlign:"center" },
						data:[
							{ type:"stackedColumn", name:"Free", showInLegend:true, color:"#8592a3", toolTipContent:"{name}: <b>{y}</b>", dataPoints: A._parse($el,'free') },
							{ type:"stackedColumn", name:"Pro",  showInLegend:true, color:"#696cff", toolTipContent:"{name}: <b>{y}</b>", dataPoints: A._parse($el,'pro') },
							{ type:"stackedColumn", name:"VVIP", showInLegend:true, color:"#f5b50a", toolTipContent:"{name}: <b>{y}</b>", dataPoints: A._parse($el,'vvip') }
						] };
				}
				if(!cfg){ return; }
				try { new CanvasJS.Chart(el.id, cfg).render(); $el.data('rendered', true); }
				catch(e){ if(window.console){ console.warn('analytics chart error', el.id, e); } }
			});
		},
		init: function(){
			if(typeof MOD === 'undefined' || MOD !== 'analytics'){ return; }
			$Core.analytics.focusFirstTab();
			$Core.analytics.loadPane('.tab-pane.active');
			$Core.analytics.renderCharts('.tab-pane.active');
			// Init tooltip chú thích chỉ số (ⓘ) — Bootstrap Tooltip (Sneat có sẵn)
			try {
				if(window.bootstrap && bootstrap.Tooltip){
					document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el){ bootstrap.Tooltip.getOrCreateInstance(el, { container: 'body' }); });
				}
			} catch(e){ if(window.console){ console.warn('analytics tooltip init error', e); } }
			$('.crm-ld-tabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e){
				var target = $(e.target).attr('data-bs-target');
				if(target){
					$('#analytics-tab').val(target.replace('#',''));
					$Core.analytics.loadPane(target);
					$Core.analytics.renderCharts(target);
				}
			});
		}
	};
	$(function(){ $Core.analytics.init(); });
})();
