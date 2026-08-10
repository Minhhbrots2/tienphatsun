<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="col-12 col-xxl-10 mx-auto">
		<div class="w-100 d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
			<div class="lycYJcfXJY">
				<h2 class="fw-bold mb-0 fs-20">Tài chính dòng tiền</h2>
			</div>
			{assign var=gId value=$clsISO->getUniqid()}
			<div class="search d-flex flex-wrap align-items-center gap-1">	
				<div class="input-group w-px-350 flex-fill">
					<select class="form-control form-select search_field search_month" 
						name="company_id" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)"> 
						<option value="">Đơn vị</option>			
						{foreach from=$list_company item = _oI}
						<option{if $smarty.const._GROUP_COMPANY_FH_ID eq $_oI.setting_id} selected{/if} value="{$_oI.setting_id}">{$_oI.title}</option>
						{/foreach}
					</select>
					<select gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" data-field="year" name="year" 
						class="form-control js__search-year-field search_field form-select">
						{foreach from=$list_years item = _year}
							<option{if $_year eq $current_year} selected{/if} value="{$_year}">{$_year}</option>
						{/foreach}
					</select>
					<select gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" data-field="month" name="month" 
						class="form-control js__search-month-field search_field form-select">
						<option value="">Tháng</option>			
						{foreach from=$list_months item = _month}
							<option value="{$_month}">T{$_month}</option>
						{/foreach}
					</select>
					<select gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" data-field="date_type" name="date_type" 
						class="form-control js__search-date_type-field form-select">
						<option value="">Khoảng</option>
						<option value="7days">7 ngày qua</option>
						<option value="15days">15 ngày qua</option>
						<option value="30days">30 ngày qua</option>
					</select>
				</div>
				<div class="input-group w-auto flex-fill">
					<input type="date" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" class="form-control js__search-start_date-field 
					js__search-date-field search_field w-px-125" name="start_date" data-field="start_date" value="{$start_date}" />
					<input type="date" gId="{$gId}" onChange="$Core.ops_cost.reloadAll(this,event)" class="form-control js__search-end_date-field 
					js__search-date-field search_field w-px-125" name="end_date" data-field="end_date" value="{$end_date}" max="{$smarty.now|date_format:'%Y-%m-%d'}" />
				</div>
			</div>
		</div>
		<!-- KPI ROW 1 -->
		<div class="ajax" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_total_expense" data-options='{ldelim}{rdelim}' >
			<div class="row g-2 mb-2">
				<div class="col-6 col-sm-3">
					<div class="kpi-card kpi-green">
						<div class="kpi-label">💵 Dòng tiền</div>
						<div><span class="kpi-value">+0đ</span><span class="kpi-badge">▲0%</span></div>
					</div>
				</div>
				<div class="col-6 col-sm-3">
					<div class="kpi-card kpi-blue">
						<div class="kpi-label">🏦 Tiền mặt</div>
						<div><span class="kpi-value">+0đ</span><span class="kpi-badge">▲0%</span></div>
					</div>
				</div>
				<div class="col-6 col-sm-3">
					<div class="kpi-card kpi-orange">
						<div class="kpi-label">📋 Phải thu</div>
						<div><span class="kpi-value">+0đ</span><span class="kpi-badge">▲0%</span></div>
					</div>
				</div>
				<div class="col-6 col-sm-3">
					<div class="kpi-card kpi-red">
						<div class="kpi-label">📌 Phải trả</div>
						<div><span class="kpi-value">+0đ</span><span class="kpi-badge">▲0%</span></div>
					</div>
				</div>
			</div>
			<!-- KPI ROW 2 -->
			<div class="row g-2 mb-3">
				<div class="col-6 col-sm-3">
					<div class="kpi-card kpi-green2">
						<div class="kpi-label">📈 Dự thu</div>
						<div><span class="kpi-value">0đ</span></div>
					</div>
				</div>
				<div class="col-6 col-sm-3">
					<div class="kpi-card kpi-red2">
						<div class="kpi-label">📉 Dự chi</div>
						<div><span class="kpi-value">0đ</span></div>
					</div>
				</div>
				<div class="col-6 col-sm-3">
					<div class="kpi-card kpi-purple">
						<div class="kpi-label">🧾 Thuế</div>
						<div><span class="kpi-value">0đ</span></div>
					</div>
				</div>
				<div class="col-6 col-sm-3">
					<div class="kpi-card kpi-navy">
						<div class="kpi-label">💳 Vay nợ</div>
						<div><span class="kpi-value">0đ</span></div>
					</div>
				</div>
			</div>
		</div>
		<!-- CHARTS ROW 1: Dòng tiền + VAT -->
		<div class="row g-3 chart-row">
			<div class="col-12 col-sm-6">
				<div class="chart-card">
					<div class="d-flex justify-content-between align-items-center">
						<div class="card-title">Dòng tiền</div>
						<a href="javascript:void(0)" class="btn btn-outline-info btn-sm" onClick="$Core.fund.dashboard.load_detail_cash_flow(this,event)" data-type="CASH_FLOW" >Chi tiết</a>
					</div>					
<!--					<div id="cDongTien" style="height:190px;"></div>-->
					<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_chart" data-options='{ldelim}{rdelim}' ></div>
				</div>
			</div>
			<div class="col-12 col-sm-6">
				<div class="chart-card">
					<div class="d-flex justify-content-between align-items-center">
						<div class="card-title">Thuế</div>
						<a href="javascript:void(0)" class="btn btn-outline-info btn-sm" onClick="$Core.fund.dashboard.load_detail_cash_flow(this,event)" data-type="OUTPUT_TAX" >Chi tiết</a>
					</div>
					<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_chart_tax" data-options='{ldelim}{rdelim}' ></div>
<!--					<div id="cVAT" style="height:190px;"></div>-->
				</div>
			</div>
		</div>
		<!-- CHARTS ROW 2: Tiền theo tài khoản + Công nợ -->
		<div class="row g-3 chart-row">
			<div class="col-12 col-sm-6">
				<div class="chart-card">
					<div class="d-flex justify-content-between align-items-center">
						<div class="card-title">Tiền theo tài khoản</div>
						<a href="javascript:void(0)" class="btn btn-outline-info btn-sm" onClick="$Core.fund.dashboard.load_detail_cash_flow(this,event)" data-type="ACCOUNT_CASH" >Chi tiết</a>
					</div>					
					<div class="donut-pair">
						<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_chart_cash_account" data-options='{ldelim}{rdelim}' >
							<!--<div id="cDonut1" style="height:185px;"></div>-->
							<div class="d-flex gap-1 flex-column px-2 justify-content-center">
								<div class="d-flex align-items-center justify-content-between gap-2">
									<div class="d-flex align-items-center gap-2">
										<span class="w-px-15 h-px-15 rounded-pill" style="background: #ffab00"></span>
										<span class="">Cash</span>
									</div>
									<span class="text-fs-16 fw-bold">0.9 tỷ</span>
								</div>
								<div class="d-flex align-items-center justify-content-between gap-2">
									<div class="d-flex align-items-center gap-2">
										<span class="w-px-15 h-px-15 rounded-pill" style="background: #007bff"></span>
										<span class="">VCB</span>
									</div>
									<span class="text-fs-16 fw-bold">2.5 tỷ</span>
								</div>
								<div class="d-flex align-items-center justify-content-between gap-2">
									<div class="d-flex align-items-center gap-2">
										<span class="w-px-15 h-px-15 rounded-pill" style="background: #71dd37"></span>
										<span class="">TCB</span>
									</div>
									<span class="text-fs-16 fw-bold">1.8 tỷ</span>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6">
				<div class="chart-card">
					<div class="d-flex justify-content-between align-items-center">
						<div class="card-title">Công nợ</div>
						<a href="javascript:void(0)" class="btn btn-outline-info btn-sm" onClick="$Core.fund.dashboard.load_detail_cash_flow(this,event)" data-type="RECEIVABLES_PAYABLES" >Chi tiết</a>
					</div>		
					<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_chart_receivables_payables" data-options='{ldelim}{rdelim}' >
<!--						<div id="cCongNo" style="height:185px;"></div>-->
					</div>
				</div>
			</div>
		</div>
		<!-- CHARTS ROW 3: Dự thu - Dự chi + BĐS & Vay nợ -->
		<div class="row g-3 chart-row">
			<div class="col-12 col-sm-6">
				<div class="chart-card">
					<div class="d-flex justify-content-between align-items-center">
						<div class="card-title">Nguồn vốn</div>
						<a href="javascript:void(0)" class="btn btn-outline-info btn-sm" onClick="$Core.fund.dashboard.load_detail_cash_flow(this,event)" data-type="LIABILITIES_EQUITY" >Chi tiết</a>
					</div>		
					<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_chart_liabilities_equity" data-options='{ldelim}{rdelim}' >
					</div>
					
				</div>
			</div>
			<div class="col-12 col-sm-6">
				<div class="chart-card">
					<div class="d-flex justify-content-between align-items-center">
						<div class="card-title">Tài sản</div>
						<a href="javascript:void(0)" class="btn btn-outline-info btn-sm" onClick="$Core.fund.dashboard.load_detail_cash_flow(this,event)" data-type="ASSET" >Chi tiết</a>
					</div>	
					<div class="bds-inner">
						<div gId="{$gId}" class="ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub={$sub}&act=load_chart_assets" data-options='{ldelim}{rdelim}' >
							<!--<div id="cBDS"></div>
							<div id="cVayNo"></div>-->
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<!-- CHARTS ROW 4: Chi phí -->
		<div class="row g-3 chart-row">
		</div>
	</div>
</div>
{literal}
<script>
	$(function() {
    	const FONT = "Public Sans";

		function axX(labels) {
			return {
				lineThickness: 0,
				tickThickness: 0,
				labelFontFamily: FONT,
				labelFontSize: 10,
				labelFontColor: "#a1acb8",
				labelFormatter: labels ?
					function(e) {
						return labels[e.value] !== undefined ? labels[e.value] : "";
					} :
					undefined
			};
		}

		function axY() {
			return {
				gridThickness: 1,
				gridColor: "#f0f1f5",
				lineThickness: 0,
				tickThickness: 0,
				labelFontFamily: FONT,
				labelFontSize: 10,
				labelFontColor: "#a1acb8"
			};
		}

		function leg(va, ha) {
			return {
				fontFamily: FONT,
				fontSize: 11,
				fontColor: "#697a8d",
				verticalAlign: va || "bottom",
				horizontalAlign: ha || "center",
				cursor: "pointer"
			};
		}

		function pts(arr, labels) {
			return arr.map((y, i) => ({
				label: labels ? labels[i] : undefined,
				y
			}));
		}

		const M8 = ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8"];

		/* 1. DÒNG TIỀN */
		if($("#cDongTien").length > 0){
			new CanvasJS.Chart("cDongTien", {
			animationEnabled: true,
			backgroundColor: "transparent",
			toolTip: {
				shared: true,
				fontFamily: FONT
			},
			legend: leg(),
			axisX: axX(M8),
			axisY: axY(),
			data: [{
					type: "spline",
					name: "Thu",
					showInLegend: true,
					lineColor: "#71dd37",
					markerColor: "#71dd37",
					markerSize: 5,
					markerType: "circle",
					dataPoints: pts([3.2, 3.5, 3.9, 3.7, 4.2, 4.0, 4.6, 4.9])
				},
				{
					type: "spline",
					name: "Chi",
					showInLegend: true,
					lineColor: "#ff3e1d",
					markerColor: "#ff3e1d",
					markerSize: 5,
					markerType: "circle",
					dataPoints: pts([2.1, 2.4, 2.2, 2.7, 2.5, 2.9, 3.2, 3.5])
				},
				{
					type: "area",
					name: "Net",
					showInLegend: true,
					fillOpacity: 0.15,
					lineColor: "#8592a3",
					markerColor: "#8592a3",
					markerSize: 5,
					markerType: "circle",
					color: "#8592a3",
					dataPoints: pts([1.1, 1.1, 1.7, 1.0, 1.7, 1.1, 1.4, 1.4])
				}
			]
		}).render();
		}
		/* 2. VAT */
		if($("#cVAT").length > 0){
		new CanvasJS.Chart("cVAT", {
			animationEnabled: true,
			backgroundColor: "transparent",
			toolTip: {
				shared: true,
				fontFamily: FONT
			},
			legend: leg(),
			axisX: {
				lineThickness: 0,
				tickThickness: 0,
				labelFontFamily: FONT,
				labelFontSize: 10,
				labelFontColor: "#a1acb8"
			},
			axisY: axY(),
			data: [{
					type: "column",
					name: "Mua vào",
					showInLegend: true,
					color: "#007bff",
					dataPoints: [{
						label: "T1",
						y: 3.2
					}, {
						label: "T2",
						y: 2.8
					}, {
						label: "T3",
						y: 3.0
					}]
				},
				{
					type: "column",
					name: "Bán ra",
					showInLegend: true,
					color: "#71dd37",
					dataPoints: [{
						label: "T1",
						y: 3.8
					}, {
						label: "T2",
						y: 4.1
					}, {
						label: "T3",
						y: 4.4
					}]
				}
			]
		}).render();
		}
		/* 3a. DONUT 1 */
		if($("#cDonut1").length > 0) {
			new CanvasJS.Chart("cDonut1", {
				animationEnabled: true,
				backgroundColor: "transparent",
				legend: leg("bottom", "center"),
				data: [{
					type: "doughnut",
					innerRadius: "52%",
					showInLegend: false,
					legendText: "{label}",
					toolTipContent: "<b>{label}</b>: {y}tỷ",
					dataPoints: [{
							label: "VCB",
							y: 2.5,
							color: "#007bff"
						},
						{
							label: "TCB",
							y: 1.8,
							color: "#71dd37"
						},
						{
							label: "Cash",
							y: 0.9,
							color: "#ffab00"
						}
					]
				}]
			}).render();
		}


		/* 4. CÔNG NỢ */
		if($("#cCongNo").length > 0) {
			new CanvasJS.Chart("cCongNo", {
				animationEnabled: true,
				backgroundColor: "transparent",
				toolTip: {
					shared: true,
					fontFamily: FONT
				},
				legend: leg(),
				axisX: {
					lineThickness: 0,
					tickThickness: 0,
					labelFontFamily: FONT,
					labelFontSize: 10,
					labelFontColor: "#a1acb8"
				},
				axisY: axY(),
				data: [{
						type: "column",
						name: "Phải thu",
						showInLegend: true,
						color: "#71dd37",
						dataPoints: [{
							label: "<30 ngày",
							y: 3.2
						}, {
							label: "30-60 ngày",
							y: 2.8
						}, {
							label: ">60 ngày",
							y: 2.1
						}]
					},
					{
						type: "column",
						name: "Phải trả",
						showInLegend: true,
						color: "#ff3e1d",
						dataPoints: [{
							label: "<30 ngày",
							y: 3.9
						}, {
							label: "30-60 ngày",
							y: 2.2
						}, {
							label: ">60 ngày",
							y: 0.9
						}]
					}
				]
			}).render();
		}

		/* 5. DỰ THU - DỰ CHI */
		if($("#cDuThu").length > 0) {
			new CanvasJS.Chart("cDuThu", {
				animationEnabled: true,
				backgroundColor: "transparent",
				toolTip: {
					shared: true,
					fontFamily: FONT
				},
				legend: leg(),
				axisX: {
					lineThickness: 0,
					tickThickness: 0,
					labelFontFamily: FONT,
					labelFontSize: 10,
					labelFontColor: "#a1acb8"
				},
				axisY: axY(),
				data: [{
						type: "spline",
						name: "Dự thu",
						showInLegend: true,
						lineColor: "#71dd37",
						markerColor: "#71dd37",
						markerSize: 5,
						markerType: "circle",
						dataPoints: [{
							label: "T3",
							y: 3.5
						}, {
							label: "T4",
							y: 3.2
						}, {
							label: "T5",
							y: 4.1
						}, {
							label: "T6",
							y: 3.9
						}, {
							label: "T7",
							y: 4.5
						}, {
							label: "T8",
							y: 4.3
						}]
					},
					{
						type: "spline",
						name: "Dự chi",
						showInLegend: true,
						lineColor: "#ff3e1d",
						markerColor: "#ff3e1d",
						markerSize: 5,
						markerType: "circle",
						dataPoints: [{
							label: "T3",
							y: 2.8
						}, {
							label: "T4",
							y: 2.4
						}, {
							label: "T5",
							y: 3.3
						}, {
							label: "T6",
							y: 3.0
						}, {
							label: "T7",
							y: 3.8
						}, {
							label: "T8",
							y: 3.6
						}]
					},
					{
						type: "area",
						name: "Net",
						showInLegend: true,
						fillOpacity: 0.15,
						lineColor: "#8592a3",
						markerColor: "#8592a3",
						markerSize: 5,
						markerType: "circle",
						color: "#8592a3",
						dataPoints: [{
							label: "T3",
							y: 0.7
						}, {
							label: "T4",
							y: 0.8
						}, {
							label: "T5",
							y: 0.8
						}, {
							label: "T6",
							y: 0.9
						}, {
							label: "T7",
							y: 0.7
						}, {
							label: "T8",
							y: 0.7
						}]
					}
				]
			}).render();
		}

		/* 6. BĐS DONUT */
		if($("#cBDS").length > 0) {
			new CanvasJS.Chart("cBDS", {
				animationEnabled: true,
				backgroundColor: "transparent",
				legend: {
					fontFamily: FONT,
					fontSize: 10,
					fontColor: "#697a8d",
					verticalAlign: "bottom",
					horizontalAlign: "center"
				},
				data: [{
					type: "doughnut",
					innerRadius: "42%",
					showInLegend: true,
					legendText: "{label}",
					toolTipContent: "<b>{label}</b>: {y}tỷ",
					dataPoints: [{
							label: "Bđa",
							y: 2.0,
							color: "#007bff"
						},
						{
							label: "Đầu tư",
							y: 3.5,
							color: "#71dd37"
						},
						{
							label: "CSVC",
							y: 1.5,
							color: "#ff3e1d"
						}
					]
				}]
			}).render();
		}

		/* 7. VAY NỢ BAR */
		if($("#cVayNo").length > 0) {
			new CanvasJS.Chart("cVayNo", {
				animationEnabled: true,
				backgroundColor: "transparent",
				axisX: {
					lineThickness: 0,
					tickThickness: 0,
					labelFontFamily: FONT,
					labelFontSize: 10,
					labelFontColor: "#a1acb8"
				},
				axisY: axY(),
				data: [{
					type: "column",
					toolTipContent: "{label}: {y}tỷ",
					dataPoints: [{
							label: "Ngắn hạn",
							y: 6.5,
							color: "#233446"
						},
						{
							label: "Dài hạn",
							y: 4.2,
							color: "#ff3e1d"
						},
						{
							label: "",
							y: 2.5,
							color: "#696cff"
						}
					]
				}]
			}).render();
		}

		/* 8. CHI PHÍ LINE */
		new CanvasJS.Chart("cChiPhiLine", {
			animationEnabled: true,
			backgroundColor: "transparent",
			toolTip: {
				shared: true,
				fontFamily: FONT
			},
			axisX: axX(M8),
			axisY: axY(),
			data: [{
					type: "spline",
					name: "CP1",
					lineColor: "#71dd37",
					markerColor: "#71dd37",
					markerSize: 5,
					markerType: "circle",
					dataPoints: pts([2.0, 2.3, 2.1, 2.5, 2.2, 2.8, 3.0, 3.3])
				},
				{
					type: "spline",
					name: "CP2",
					lineColor: "#ff3e1d",
					markerColor: "#ff3e1d",
					markerSize: 5,
					markerType: "circle",
					dataPoints: pts([1.5, 1.6, 1.8, 1.7, 2.0, 2.2, 2.4, 2.5])
				}
			]
		}).render();

		/* 9. CHI PHÍ BAR */
		new CanvasJS.Chart("cChiPhiBar", {
			animationEnabled: true,
			backgroundColor: "transparent",
			axisX: {
				lineThickness: 0,
				tickThickness: 0,
				labelFontFamily: FONT,
				labelFontSize: 10,
				labelFontColor: "#a1acb8"
			},
			axisY: axY(),
			data: [{
				type: "column",
				toolTipContent: "{label}: {y}tỷ",
				color: "#696cff",
				dataPoints: [{
						label: "Marketing",
						y: 3.5
					},
					{
						label: "Sale",
						y: 2.9
					},
					{
						label: "Ops",
						y: 2.3
					},
					{
						label: "Lãi vay",
						y: 1.7
					}
				]
			}]
		}).render();
	});

  
</script>
{/literal}
