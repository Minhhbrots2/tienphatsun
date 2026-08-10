<div class="container-xxl flex-grow-1 container-p-y">
	<h4 class="fw-bold py-2 mb-2">Danh sách nhà môi giới</h4>
	<div class="form-row row g-4 mb-4 {if $deviceType eq 'phone'}mt-1{/if}"> 
		{section name=i loop=$list_statistic}
			<div class="col-3 col-md-3 col-xl-3 {if $deviceType eq 'phone'}col_item_mb mt-2{/if}">
				<div class="card">
					<div class="card-body">
						<div class="d-flex {if $deviceType eq 'phone'}align-items-end justify-content-center{else}align-items-start justify-content-between{/if}">
							<div class="content-left">
								<span>{$list_statistic[i].title}</span>
								<div class="mt-2">
									<h4 class="mb-0">{$list_statistic[i].number}</h4>
								</div> 
							</div>
							<div class="avatar">
								<span class="avatar-initial rounded {$list_statistic[i].classBgColor}">
								<i class="bx {$list_statistic[i].icon} bx-sm"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		{/section}
	</div>
	<div class="card border-0 my-4">
		<div class="card-body row pb-3"> 
			<div class="col-12 col-md-8 card-separator">
				<h3>Welcome back, Felecia 👋🏻 </h3>
				<div class="col-12 col-lg-7">
					<p>Your progress this week is Awesome. let's keep it up and get a lot of points reward !</p>
				</div>
				<div class="d-flex justify-content-between flex-wrap gap-3 me-5">
					<div class="d-flex align-items-center gap-3 me-4 me-sm-0">
						<span class=" bg-label-primary p-2 rounded">
						<i class="bx bx-laptop bx-sm"></i>
						</span>
						<div class="content-right">
							<p class="mb-0">Hours Spent</p>
							<h4 class="text-primary mb-0">34h</h4>
						</div>
					</div>
					<div class="d-flex align-items-center gap-3">
						<span class="bg-label-info p-2 rounded">
						<i class="bx bx-bulb bx-sm"></i>
						</span>
						<div class="content-right">
							<p class="mb-0">Test Results</p>
							<h4 class="text-info mb-0">82%</h4>
						</div>
					</div>
					<div class="d-flex align-items-center gap-3">
						<span class="bg-label-warning p-2 rounded">
						<i class="bx bx-check-circle bx-sm"></i>
						</span>
						<div class="content-right">
							<p class="mb-0">Course Completed </p>
							<h4 class="text-warning mb-0">14</h4>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-4 ps-md-3 ps-lg-5 pt-3 pt-md-0">
				<div class="d-flex justify-content-between align-items-center" style="position: relative;">
					<div>
						<div>
							<h5 class="mb-2">Time Spendings</h5>
							<p class="mb-4">Weekly report</p>
						</div>
						<div class="time-spending-chart">
							<h3 class="mb-2">231<span class="text-muted">h</span> 14<span class="text-muted">m</span> </h3>
							<span class="badge bg-label-success">+18.4%</span>
						</div>
					</div>
					<div id="leadsReportChart" style="min-height: 139.8px;">
						<div id="apexchartsjzhc62rz" class="apexcharts-canvas apexchartsjzhc62rz apexcharts-theme-light" style="width: 130px; height: 139.8px;">
							<svg id="SvgjsSvg1644" width="130" height="139.8" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
								<g id="SvgjsG1646" class="apexcharts-inner apexcharts-graphical" transform="translate(-0.5, 0)">
									<defs id="SvgjsDefs1645">
										<clipPath id="gridRectMaskjzhc62rz">
											<rect id="SvgjsRect1648" width="137" height="155" x="-2" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
										</clipPath>
										<clipPath id="forecastMaskjzhc62rz"></clipPath>
										<clipPath id="nonForecastMaskjzhc62rz"></clipPath>
										<clipPath id="gridRectMarkerMaskjzhc62rz">
											<rect id="SvgjsRect1649" width="137" height="159" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
										</clipPath>
									</defs>
									<g id="SvgjsG1650" class="apexcharts-pie">
										<g id="SvgjsG1651" transform="translate(0, 0) scale(1)">
											<circle id="SvgjsCircle1652" r="45.658536585365866" cx="66.5" cy="66.5" fill="transparent"></circle>
											<g id="SvgjsG1653" class="apexcharts-slices">
												<g id="SvgjsG1654" class="apexcharts-series apexcharts-pie-series" seriesName="36h" rel="1" data:realIndex="0">
													<path id="SvgjsPath1655" d="M 66.5 5.621951219512184 A 60.878048780487816 60.878048780487816 0 0 1 117.38951211290043 33.087511612715616 L 104.66713408467533 41.44063370953671 A 45.658536585365866 45.658536585365866 0 0 0 66.5 20.841463414634134 L 66.5 5.621951219512184 z" fill="#71dd37e8" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-0" index="0" j="0" data:angle="56.71232876712329" data:startAngle="0" data:strokeWidth="0" data:value="23" data:pathOrig="M 66.5 5.621951219512184 A 60.878048780487816 60.878048780487816 0 0 1 117.38951211290043 33.087511612715616 L 104.66713408467533 41.44063370953671 A 45.658536585365866 45.658536585365866 0 0 0 66.5 20.841463414634134 L 66.5 5.621951219512184 z"></path>
												</g>
												<g id="SvgjsG1656" class="apexcharts-series apexcharts-pie-series" seriesName="56h" rel="2" data:realIndex="1">
													<path id="SvgjsPath1657" d="M 117.38951211290043 33.087511612715616 A 60.878048780487816 60.878048780487816 0 0 1 103.12569906852212 115.12812962742359 L 93.9692743013916 102.97109722056769 A 45.658536585365866 45.658536585365866 0 0 0 104.66713408467533 41.44063370953671 L 117.38951211290043 33.087511612715616 z" fill="#71dd37bf" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-1" index="0" j="1" data:angle="86.30136986301369" data:startAngle="56.71232876712329" data:strokeWidth="0" data:value="35" data:pathOrig="M 117.38951211290043 33.087511612715616 A 60.878048780487816 60.878048780487816 0 0 1 103.12569906852212 115.12812962742359 L 93.9692743013916 102.97109722056769 A 45.658536585365866 45.658536585365866 0 0 0 104.66713408467533 41.44063370953671 L 117.38951211290043 33.087511612715616 z"></path>
												</g>
												<g id="SvgjsG1658" class="apexcharts-series apexcharts-pie-series" seriesName="16h" rel="3" data:realIndex="2">
													<path id="SvgjsPath1659" d="M 103.12569906852212 115.12812962742359 A 60.878048780487816 60.878048780487816 0 0 1 79.49873670579831 125.9741092188255 L 76.24905252934873 111.10558191411914 A 45.658536585365866 45.658536585365866 0 0 0 93.9692743013916 102.97109722056769 L 103.12569906852212 115.12812962742359 z" fill="rgba(113,221,55,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-2" index="0" j="2" data:angle="24.657534246575352" data:startAngle="143.013698630137" data:strokeWidth="0" data:value="10" data:pathOrig="M 103.12569906852212 115.12812962742359 A 60.878048780487816 60.878048780487816 0 0 1 79.49873670579831 125.9741092188255 L 76.24905252934873 111.10558191411914 A 45.658536585365866 45.658536585365866 0 0 0 93.9692743013916 102.97109722056769 L 103.12569906852212 115.12812962742359 z"></path>
												</g>
												<g id="SvgjsG1660" class="apexcharts-series apexcharts-pie-series" seriesName="32h" rel="4" data:realIndex="3">
													<path id="SvgjsPath1661" d="M 79.49873670579831 125.9741092188255 A 60.878048780487816 60.878048780487816 0 0 1 29.87430093147789 115.12812962742359 L 39.03072569860842 102.9710972205677 A 45.658536585365866 45.658536585365866 0 0 0 76.24905252934873 111.10558191411914 L 79.49873670579831 125.9741092188255 z" fill="#71dd3799" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-3" index="0" j="3" data:angle="49.315068493150676" data:startAngle="167.67123287671234" data:strokeWidth="0" data:value="20" data:pathOrig="M 79.49873670579831 125.9741092188255 A 60.878048780487816 60.878048780487816 0 0 1 29.87430093147789 115.12812962742359 L 39.03072569860842 102.9710972205677 A 45.658536585365866 45.658536585365866 0 0 0 76.24905252934873 111.10558191411914 L 79.49873670579831 125.9741092188255 z"></path>
												</g>
												<g id="SvgjsG1662" class="apexcharts-series apexcharts-pie-series" seriesName="56h" rel="5" data:realIndex="4">
													<path id="SvgjsPath1663" d="M 29.87430093147789 115.12812962742359 A 60.878048780487816 60.878048780487816 0 0 1 15.61048788709956 33.08751161271562 L 28.332865915324668 41.44063370953671 A 45.658536585365866 45.658536585365866 0 0 0 39.03072569860842 102.9710972205677 L 29.87430093147789 115.12812962742359 z" fill="#71dd3766" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-4" index="0" j="4" data:angle="86.30136986301372" data:startAngle="216.986301369863" data:strokeWidth="0" data:value="35" data:pathOrig="M 29.87430093147789 115.12812962742359 A 60.878048780487816 60.878048780487816 0 0 1 15.61048788709956 33.08751161271562 L 28.332865915324668 41.44063370953671 A 45.658536585365866 45.658536585365866 0 0 0 39.03072569860842 102.9710972205677 L 29.87430093147789 115.12812962742359 z"></path>
												</g>
												<g id="SvgjsG1664" class="apexcharts-series apexcharts-pie-series" seriesName="16h" rel="6" data:realIndex="5">
													<path id="SvgjsPath1665" d="M 15.61048788709956 33.08751161271562 A 60.878048780487816 60.878048780487816 0 0 1 66.48937477611985 5.621952146737883 L 66.49203108208988 20.84146411005341 A 45.658536585365866 45.658536585365866 0 0 0 28.332865915324668 41.44063370953671 L 15.61048788709956 33.08751161271562 z" fill="#71dd3733" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-5" index="0" j="5" data:angle="56.71232876712327" data:startAngle="303.28767123287673" data:strokeWidth="0" data:value="23" data:pathOrig="M 15.61048788709956 33.08751161271562 A 60.878048780487816 60.878048780487816 0 0 1 66.48937477611985 5.621952146737883 L 66.49203108208988 20.84146411005341 A 45.658536585365866 45.658536585365866 0 0 0 28.332865915324668 41.44063370953671 L 15.61048788709956 33.08751161271562 z"></path>
												</g>
											</g>
										</g>
										<g id="SvgjsG1666" class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)">
											<text id="SvgjsText1667" font-family="Helvetica, Arial, sans-serif" x="66.5" y="86.5" text-anchor="middle" dominant-baseline="auto" font-size=".7rem" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-datalabel-label" style="font-family: Helvetica, Arial, sans-serif;">Total</text>
											<text id="SvgjsText1668" font-family="Public Sans" x="66.5" y="67.5" text-anchor="middle" dominant-baseline="auto" font-size="1.5rem" font-weight="500" fill="#566a7f" class="apexcharts-text apexcharts-datalabel-value" style="font-family: &quot;Public Sans&quot;;">231h</text>
										</g>
									</g>
									<line id="SvgjsLine1669" x1="0" y1="0" x2="133" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
									<line id="SvgjsLine1670" x1="0" y1="0" x2="133" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
								</g>
								<g id="SvgjsG1647" class="apexcharts-annotations"></g>
							</svg>
							<div class="apexcharts-legend"></div>
							<div class="apexcharts-tooltip apexcharts-theme-false">
								<div class="apexcharts-tooltip-series-group" style="order: 1;">
									<span class="apexcharts-tooltip-marker" style="background-color: rgba(113, 221, 55, 0.91);"></span>
									<div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
										<div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
										<div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
										<div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
									</div>
								</div>
								<div class="apexcharts-tooltip-series-group" style="order: 2;">
									<span class="apexcharts-tooltip-marker" style="background-color: rgba(113, 221, 55, 0.75);"></span>
									<div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
										<div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
										<div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
										<div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
									</div>
								</div>
								<div class="apexcharts-tooltip-series-group" style="order: 3;">
									<span class="apexcharts-tooltip-marker" style="background-color: rgb(113, 221, 55);"></span>
									<div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
										<div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
										<div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
										<div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
									</div>
								</div>
								<div class="apexcharts-tooltip-series-group" style="order: 4;">
									<span class="apexcharts-tooltip-marker" style="background-color: rgba(113, 221, 55, 0.6);"></span>
									<div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
										<div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
										<div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
										<div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
									</div>
								</div>
								<div class="apexcharts-tooltip-series-group" style="order: 5;">
									<span class="apexcharts-tooltip-marker" style="background-color: rgba(113, 221, 55, 0.4);"></span>
									<div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
										<div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
										<div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
										<div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
									</div>
								</div>
								<div class="apexcharts-tooltip-series-group" style="order: 6;">
									<span class="apexcharts-tooltip-marker" style="background-color: rgba(113, 221, 55, 0.2);"></span>
									<div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
										<div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
										<div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
										<div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="resize-triggers">
						<div class="expand-trigger">
							<div style="width: 411px; height: 141px;"></div>
						</div>
						<div class="contract-trigger"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mb-4 g-4">
		<div class="col-12 col-xl-8">
			<div class="card h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="card-title m-0 me-2">Topic you are interested in</h5>
					<div class="dropdown">
						<button class="btn p-0" type="button" id="topic" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-end" aria-labelledby="topic">
							<a class="dropdown-item" href="javascript:void(0);">Highest Views</a>
							<a class="dropdown-item" href="javascript:void(0);">See All</a>
						</div>
					</div>
				</div>
				<div class="card-body row g-3">
					<div class="col-md-6" style="position: relative;">
						<div id="horizontalBarChart" style="min-height: 285px;">
							<div id="apexchartsjl9vz1bj" class="apexcharts-canvas apexchartsjl9vz1bj apexcharts-theme-light" style="width: 426px; height: 270px;">
								<svg id="SvgjsSvg1671" width="426" height="270" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
									<g id="SvgjsG1673" class="apexcharts-inner apexcharts-graphical" transform="translate(32.296875, -5)">
										<defs id="SvgjsDefs1672">
											<linearGradient id="SvgjsLinearGradient1677" x1="0" y1="0" x2="0" y2="1">
												<stop id="SvgjsStop1678" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop>
												<stop id="SvgjsStop1679" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
												<stop id="SvgjsStop1680" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
											</linearGradient>
											<clipPath id="gridRectMaskjl9vz1bj">
												<rect id="SvgjsRect1682" width="373.47314453125" height="247.73" x="-2" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
											</clipPath>
											<clipPath id="forecastMaskjl9vz1bj"></clipPath>
											<clipPath id="nonForecastMaskjl9vz1bj"></clipPath>
											<clipPath id="gridRectMarkerMaskjl9vz1bj">
												<rect id="SvgjsRect1683" width="373.47314453125" height="251.73" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
											</clipPath>
										</defs>
										<rect id="SvgjsRect1681" width="0" height="247.73" x="147" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient1677)" class="apexcharts-xcrosshairs" y2="247.73" filter="none" fill-opacity="0.9" x1="147" x2="147"></rect>
										<g id="SvgjsG1744" class="apexcharts-yaxis apexcharts-xaxis-inversed" rel="0">
											<g id="SvgjsG1745" class="apexcharts-yaxis-texts-g apexcharts-xaxis-inversed-texts-g" transform="translate(0, 0)">
												<text id="SvgjsText1746" font-family="Public Sans" x="-15" y="22.520909090909093" text-anchor="end" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-yaxis-label " style="font-family: &quot;Public Sans&quot;;">
													<tspan id="SvgjsTspan1747">6</tspan>
													<title>6</title>
												</text>
												<text id="SvgjsText1748" font-family="Public Sans" x="-15" y="63.80924242424243" text-anchor="end" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-yaxis-label " style="font-family: &quot;Public Sans&quot;;">
													<tspan id="SvgjsTspan1749">5</tspan>
													<title>5</title>
												</text>
												<text id="SvgjsText1750" font-family="Public Sans" x="-15" y="105.09757575757575" text-anchor="end" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-yaxis-label " style="font-family: &quot;Public Sans&quot;;">
													<tspan id="SvgjsTspan1751">4</tspan>
													<title>4</title>
												</text>
												<text id="SvgjsText1752" font-family="Public Sans" x="-15" y="146.38590909090908" text-anchor="end" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-yaxis-label " style="font-family: &quot;Public Sans&quot;;">
													<tspan id="SvgjsTspan1753">3</tspan>
													<title>3</title>
												</text>
												<text id="SvgjsText1754" font-family="Public Sans" x="-15" y="187.6742424242424" text-anchor="end" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-yaxis-label " style="font-family: &quot;Public Sans&quot;;">
													<tspan id="SvgjsTspan1755">2</tspan>
													<title>2</title>
												</text>
												<text id="SvgjsText1756" font-family="Public Sans" x="-15" y="228.96257575757573" text-anchor="end" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-yaxis-label " style="font-family: &quot;Public Sans&quot;;">
													<tspan id="SvgjsTspan1757">1</tspan>
													<title>1</title>
												</text>
											</g>
										</g>
										<g id="SvgjsG1724" class="apexcharts-xaxis apexcharts-yaxis-inversed">
											<g id="SvgjsG1725" class="apexcharts-xaxis-texts-g" transform="translate(0, -8.666666666666666)">
												<text id="SvgjsText1726" font-family="Helvetica, Arial, sans-serif" x="369.47314453125" y="277.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
													<tspan id="SvgjsTspan1728">35%</tspan>
													<title>35%</title>
												</text>
												<text id="SvgjsText1729" font-family="Helvetica, Arial, sans-serif" x="295.478515625" y="277.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
													<tspan id="SvgjsTspan1731">28%</tspan>
													<title>28%</title>
												</text>
												<text id="SvgjsText1732" font-family="Helvetica, Arial, sans-serif" x="221.48388671875" y="277.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
													<tspan id="SvgjsTspan1734">21%</tspan>
													<title>21%</title>
												</text>
												<text id="SvgjsText1735" font-family="Helvetica, Arial, sans-serif" x="147.4892578125" y="277.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
													<tspan id="SvgjsTspan1737">14%</tspan>
													<title>14%</title>
												</text>
												<text id="SvgjsText1738" font-family="Helvetica, Arial, sans-serif" x="73.49462890625" y="277.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
													<tspan id="SvgjsTspan1740">7%</tspan>
													<title>7%</title>
												</text>
												<text id="SvgjsText1741" font-family="Helvetica, Arial, sans-serif" x="-0.5" y="277.73" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
													<tspan id="SvgjsTspan1743">0%</tspan>
													<title>0%</title>
												</text>
											</g>
										</g>
										<g id="SvgjsG1758" class="apexcharts-grid">
											<g id="SvgjsG1759" class="apexcharts-gridlines-horizontal"></g>
											<g id="SvgjsG1760" class="apexcharts-gridlines-vertical">
												<line id="SvgjsLine1761" x1="0" y1="0" x2="0" y2="247.73" stroke="#eceef1" stroke-dasharray="10" stroke-linecap="butt" class="apexcharts-gridline"></line>
												<line id="SvgjsLine1762" x1="74.19462890625" y1="0" x2="74.19462890625" y2="247.73" stroke="#eceef1" stroke-dasharray="10" stroke-linecap="butt" class="apexcharts-gridline"></line>
												<line id="SvgjsLine1763" x1="148.38925781250003" y1="0" x2="148.38925781250003" y2="247.73" stroke="#eceef1" stroke-dasharray="10" stroke-linecap="butt" class="apexcharts-gridline"></line>
												<line id="SvgjsLine1764" x1="222.58388671875005" y1="0" x2="222.58388671875005" y2="247.73" stroke="#eceef1" stroke-dasharray="10" stroke-linecap="butt" class="apexcharts-gridline"></line>
												<line id="SvgjsLine1765" x1="296.77851562500007" y1="0" x2="296.77851562500007" y2="247.73" stroke="#eceef1" stroke-dasharray="10" stroke-linecap="butt" class="apexcharts-gridline"></line>
												<line id="SvgjsLine1766" x1="370.97314453125006" y1="0" x2="370.97314453125006" y2="247.73" stroke="#eceef1" stroke-dasharray="10" stroke-linecap="butt" class="apexcharts-gridline"></line>
											</g>
											<line id="SvgjsLine1768" x1="0" y1="247.73" x2="369.47314453125" y2="247.73" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
											<line id="SvgjsLine1767" x1="0" y1="1" x2="0" y2="247.73" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
										</g>
										<g id="SvgjsG1684" class="apexcharts-bar-series apexcharts-plot-series">
											<g id="SvgjsG1685" class="apexcharts-series" rel="1" seriesName="seriesx1" data:realIndex="0">
												<path id="SvgjsPath1689" d="M 0.1 6.193250000000001L 362.57314453125 6.193250000000001Q 369.57314453125 6.193250000000001 369.57314453125 13.19325L 369.57314453125 28.095083333333335Q 369.57314453125 35.095083333333335 362.57314453125 35.095083333333335L 362.57314453125 35.095083333333335L 0.1 35.095083333333335L 0.1 35.095083333333335Q 0.1 35.095083333333335 0.1 35.095083333333335L 0.1 6.193250000000001Q 0.1 6.193250000000001 0.1 6.193250000000001z" fill="rgba(105,108,255,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskjl9vz1bj)" pathTo="M 0.1 6.193250000000001L 362.57314453125 6.193250000000001Q 369.57314453125 6.193250000000001 369.57314453125 13.19325L 369.57314453125 28.095083333333335Q 369.57314453125 35.095083333333335 362.57314453125 35.095083333333335L 362.57314453125 35.095083333333335L 0.1 35.095083333333335L 0.1 35.095083333333335Q 0.1 35.095083333333335 0.1 35.095083333333335L 0.1 6.193250000000001Q 0.1 6.193250000000001 0.1 6.193250000000001z" pathFrom="M 0.1 6.193250000000001L 0.1 6.193250000000001L 0.1 35.095083333333335L 0.1 35.095083333333335L 0.1 35.095083333333335L 0.1 35.095083333333335L 0.1 35.095083333333335L 0.1 6.193250000000001" cy="47.48158333333333" cx="369.57314453125" j="0" val="35" barHeight="28.901833333333332" barWidth="369.47314453125"></path>
												<path id="SvgjsPath1695" d="M 0.1 47.48158333333333L 204.22751116071427 47.48158333333333Q 211.22751116071427 47.48158333333333 211.22751116071427 54.48158333333333L 211.22751116071427 69.38341666666666Q 211.22751116071427 76.38341666666666 204.22751116071427 76.38341666666666L 204.22751116071427 76.38341666666666L 0.1 76.38341666666666L 0.1 76.38341666666666Q 0.1 76.38341666666666 0.1 76.38341666666666L 0.1 47.48158333333333Q 0.1 47.48158333333333 0.1 47.48158333333333z" fill="rgba(3,195,236,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskjl9vz1bj)" pathTo="M 0.1 47.48158333333333L 204.22751116071427 47.48158333333333Q 211.22751116071427 47.48158333333333 211.22751116071427 54.48158333333333L 211.22751116071427 69.38341666666666Q 211.22751116071427 76.38341666666666 204.22751116071427 76.38341666666666L 204.22751116071427 76.38341666666666L 0.1 76.38341666666666L 0.1 76.38341666666666Q 0.1 76.38341666666666 0.1 76.38341666666666L 0.1 47.48158333333333Q 0.1 47.48158333333333 0.1 47.48158333333333z" pathFrom="M 0.1 47.48158333333333L 0.1 47.48158333333333L 0.1 76.38341666666666L 0.1 76.38341666666666L 0.1 76.38341666666666L 0.1 76.38341666666666L 0.1 76.38341666666666L 0.1 47.48158333333333" cy="88.76991666666666" cx="211.22751116071427" j="1" val="20" barHeight="28.901833333333332" barWidth="211.12751116071428"></path>
												<path id="SvgjsPath1701" d="M 0.1 88.76991666666666L 140.8892578125 88.76991666666666Q 147.8892578125 88.76991666666666 147.8892578125 95.76991666666666L 147.8892578125 110.67174999999999Q 147.8892578125 117.67174999999999 140.8892578125 117.67174999999999L 140.8892578125 117.67174999999999L 0.1 117.67174999999999L 0.1 117.67174999999999Q 0.1 117.67174999999999 0.1 117.67174999999999L 0.1 88.76991666666666Q 0.1 88.76991666666666 0.1 88.76991666666666z" fill="rgba(113,221,55,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskjl9vz1bj)" pathTo="M 0.1 88.76991666666666L 140.8892578125 88.76991666666666Q 147.8892578125 88.76991666666666 147.8892578125 95.76991666666666L 147.8892578125 110.67174999999999Q 147.8892578125 117.67174999999999 140.8892578125 117.67174999999999L 140.8892578125 117.67174999999999L 0.1 117.67174999999999L 0.1 117.67174999999999Q 0.1 117.67174999999999 0.1 117.67174999999999L 0.1 88.76991666666666Q 0.1 88.76991666666666 0.1 88.76991666666666z" pathFrom="M 0.1 88.76991666666666L 0.1 88.76991666666666L 0.1 117.67174999999999L 0.1 117.67174999999999L 0.1 117.67174999999999L 0.1 117.67174999999999L 0.1 117.67174999999999L 0.1 88.76991666666666" cy="130.05825" cx="147.8892578125" j="2" val="14" barHeight="28.901833333333332" barWidth="147.7892578125"></path>
												<path id="SvgjsPath1707" d="M 0.1 130.05825L 119.77650669642857 130.05825Q 126.77650669642857 130.05825 126.77650669642857 137.05825L 126.77650669642857 151.96008333333333Q 126.77650669642857 158.96008333333333 119.77650669642857 158.96008333333333L 119.77650669642857 158.96008333333333L 0.1 158.96008333333333L 0.1 158.96008333333333Q 0.1 158.96008333333333 0.1 158.96008333333333L 0.1 130.05825Q 0.1 130.05825 0.1 130.05825z" fill="rgba(133,146,163,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskjl9vz1bj)" pathTo="M 0.1 130.05825L 119.77650669642857 130.05825Q 126.77650669642857 130.05825 126.77650669642857 137.05825L 126.77650669642857 151.96008333333333Q 126.77650669642857 158.96008333333333 119.77650669642857 158.96008333333333L 119.77650669642857 158.96008333333333L 0.1 158.96008333333333L 0.1 158.96008333333333Q 0.1 158.96008333333333 0.1 158.96008333333333L 0.1 130.05825Q 0.1 130.05825 0.1 130.05825z" pathFrom="M 0.1 130.05825L 0.1 130.05825L 0.1 158.96008333333333L 0.1 158.96008333333333L 0.1 158.96008333333333L 0.1 158.96008333333333L 0.1 158.96008333333333L 0.1 130.05825" cy="171.3465833333333" cx="126.77650669642857" j="3" val="12" barHeight="28.901833333333332" barWidth="126.67650669642858"></path>
												<path id="SvgjsPath1713" d="M 0.1 171.3465833333333L 98.66375558035713 171.3465833333333Q 105.66375558035713 171.3465833333333 105.66375558035713 178.3465833333333L 105.66375558035713 193.24841666666666Q 105.66375558035713 200.24841666666666 98.66375558035713 200.24841666666666L 98.66375558035713 200.24841666666666L 0.1 200.24841666666666L 0.1 200.24841666666666Q 0.1 200.24841666666666 0.1 200.24841666666666L 0.1 171.3465833333333Q 0.1 171.3465833333333 0.1 171.3465833333333z" fill="rgba(255,62,29,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskjl9vz1bj)" pathTo="M 0.1 171.3465833333333L 98.66375558035713 171.3465833333333Q 105.66375558035713 171.3465833333333 105.66375558035713 178.3465833333333L 105.66375558035713 193.24841666666666Q 105.66375558035713 200.24841666666666 98.66375558035713 200.24841666666666L 98.66375558035713 200.24841666666666L 0.1 200.24841666666666L 0.1 200.24841666666666Q 0.1 200.24841666666666 0.1 200.24841666666666L 0.1 171.3465833333333Q 0.1 171.3465833333333 0.1 171.3465833333333z" pathFrom="M 0.1 171.3465833333333L 0.1 171.3465833333333L 0.1 200.24841666666666L 0.1 200.24841666666666L 0.1 200.24841666666666L 0.1 200.24841666666666L 0.1 200.24841666666666L 0.1 171.3465833333333" cy="212.63491666666664" cx="105.66375558035713" j="4" val="10" barHeight="28.901833333333332" barWidth="105.56375558035714"></path>
												<path id="SvgjsPath1719" d="M 0.1 212.63491666666664L 88.10738002232142 212.63491666666664Q 95.10738002232142 212.63491666666664 95.10738002232142 219.63491666666664L 95.10738002232142 234.53674999999998Q 95.10738002232142 241.53674999999998 88.10738002232142 241.53674999999998L 88.10738002232142 241.53674999999998L 0.1 241.53674999999998L 0.1 241.53674999999998Q 0.1 241.53674999999998 0.1 241.53674999999998L 0.1 212.63491666666664Q 0.1 212.63491666666664 0.1 212.63491666666664z" fill="rgba(255,171,0,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskjl9vz1bj)" pathTo="M 0.1 212.63491666666664L 88.10738002232142 212.63491666666664Q 95.10738002232142 212.63491666666664 95.10738002232142 219.63491666666664L 95.10738002232142 234.53674999999998Q 95.10738002232142 241.53674999999998 88.10738002232142 241.53674999999998L 88.10738002232142 241.53674999999998L 0.1 241.53674999999998L 0.1 241.53674999999998Q 0.1 241.53674999999998 0.1 241.53674999999998L 0.1 212.63491666666664Q 0.1 212.63491666666664 0.1 212.63491666666664z" pathFrom="M 0.1 212.63491666666664L 0.1 212.63491666666664L 0.1 241.53674999999998L 0.1 241.53674999999998L 0.1 241.53674999999998L 0.1 241.53674999999998L 0.1 241.53674999999998L 0.1 212.63491666666664" cy="253.92324999999997" cx="95.10738002232142" j="5" val="9" barHeight="28.901833333333332" barWidth="95.00738002232143"></path>
												<g id="SvgjsG1687" class="apexcharts-bar-goals-markers" style="pointer-events: none">
													<g id="SvgjsG1688" className="apexcharts-bar-goals-groups"></g>
													<g id="SvgjsG1694" className="apexcharts-bar-goals-groups"></g>
													<g id="SvgjsG1700" className="apexcharts-bar-goals-groups"></g>
													<g id="SvgjsG1706" className="apexcharts-bar-goals-groups"></g>
													<g id="SvgjsG1712" className="apexcharts-bar-goals-groups"></g>
													<g id="SvgjsG1718" className="apexcharts-bar-goals-groups"></g>
												</g>
											</g>
											<g id="SvgjsG1686" class="apexcharts-datalabels" data:realIndex="0">
												<g id="SvgjsG1691" class="apexcharts-data-labels" transform="rotate(0)">
													<text id="SvgjsText1693" font-family="Public Sans" x="184.83657226562502" y="25.144166666666663" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="200" fill="#ffffff" class="apexcharts-datalabel" cx="184.83657226562502" cy="25.144166666666663" style="font-family: &quot;Public Sans&quot;;">UI Design</text>
												</g>
												<g id="SvgjsG1697" class="apexcharts-data-labels" transform="rotate(0)">
													<text id="SvgjsText1699" font-family="Public Sans" x="105.66375558035713" y="66.43249999999999" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="200" fill="#ffffff" class="apexcharts-datalabel" cx="105.66375558035713" cy="66.43249999999999" style="font-family: &quot;Public Sans&quot;;">UX Design</text>
												</g>
												<g id="SvgjsG1703" class="apexcharts-data-labels" transform="rotate(0)">
													<text id="SvgjsText1705" font-family="Public Sans" x="73.99462890625" y="107.72083333333333" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="200" fill="#ffffff" class="apexcharts-datalabel" cx="73.99462890625" cy="107.72083333333333" style="font-family: &quot;Public Sans&quot;;">Music</text>
												</g>
												<g id="SvgjsG1709" class="apexcharts-data-labels" transform="rotate(0)">
													<text id="SvgjsText1711" font-family="Public Sans" x="63.43825334821428" y="149.00916666666666" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="200" fill="#ffffff" class="apexcharts-datalabel" cx="63.43825334821428" cy="149.00916666666666" style="font-family: &quot;Public Sans&quot;;">Animation</text>
												</g>
												<g id="SvgjsG1715" class="apexcharts-data-labels" transform="rotate(0)">
													<text id="SvgjsText1717" font-family="Public Sans" x="52.881877790178564" y="190.29749999999999" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="200" fill="#ffffff" class="apexcharts-datalabel" cx="52.881877790178564" cy="190.29749999999999" style="font-family: &quot;Public Sans&quot;;">React</text>
												</g>
												<g id="SvgjsG1721" class="apexcharts-data-labels" transform="rotate(0)">
													<text id="SvgjsText1723" font-family="Public Sans" x="47.60369001116071" y="231.5858333333333" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="200" fill="#ffffff" class="apexcharts-datalabel" cx="47.60369001116071" cy="231.5858333333333" style="font-family: &quot;Public Sans&quot;;">SEO</text>
												</g>
											</g>
										</g>
										<line id="SvgjsLine1769" x1="0" y1="0" x2="369.47314453125" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
										<line id="SvgjsLine1770" x1="0" y1="0" x2="369.47314453125" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
										<g id="SvgjsG1771" class="apexcharts-yaxis-annotations"></g>
										<g id="SvgjsG1772" class="apexcharts-xaxis-annotations"></g>
										<g id="SvgjsG1773" class="apexcharts-point-annotations"></g>
									</g>
									<g id="SvgjsG1674" class="apexcharts-annotations"></g>
								</svg>
								<div class="apexcharts-legend" style="max-height: 135px;"></div>
								<div class="apexcharts-tooltip apexcharts-theme-light" style="left: 179.297px; top: 58.7563px;">
									<div class="px-3 py-2"><span>14%</span></div>
								</div>
								<div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
									<div class="apexcharts-yaxistooltip-text"></div>
								</div>
							</div>
						</div>
						<div class="resize-triggers">
							<div class="expand-trigger">
								<div style="width: 443px; height: 286px;"></div>
							</div>
							<div class="contract-trigger"></div>
						</div>
					</div>
					<div class="col-md-6 d-flex justify-content-around align-items-center">
						<div>
							<div class="d-flex align-items-baseline">
								<span class="text-primary me-2"><i class="bx bxs-circle"></i></span>
								<div>
									<p class="mb-2">UI Design</p>
									<h5>35%</h5>
								</div>
							</div>
							<div class="d-flex align-items-baseline my-3">
								<span class="text-success me-2"><i class="bx bxs-circle"></i></span>
								<div>
									<p class="mb-2">Music</p>
									<h5>14%</h5>
								</div>
							</div>
							<div class="d-flex align-items-baseline">
								<span class="text-danger me-2"><i class="bx bxs-circle"></i></span>
								<div>
									<p class="mb-2">React</p>
									<h5>10%</h5>
								</div>
							</div>
						</div>
						<div>
							<div class="d-flex align-items-baseline">
								<span class="text-info me-2"><i class="bx bxs-circle"></i></span>
								<div>
									<p class="mb-2">UX Design</p>
									<h5>20%</h5>
								</div>
							</div>
							<div class="d-flex align-items-baseline my-3">
								<span class="text-secondary me-2"><i class="bx bxs-circle"></i></span>
								<div>
									<p class="mb-2">Animation</p>
									<h5>12%</h5>
								</div>
							</div>
							<div class="d-flex align-items-baseline">
								<span class="text-warning me-2"><i class="bx bxs-circle"></i></span>
								<div>
									<p class="mb-2">SEO</p>
									<h5>9%</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-xl-4 col-md-6">
			<div class="card h-100">
				<div class="card-header d-flex align-items-center justify-content-between">
					<div class="card-title mb-0">
						<h5 class="m-0 me-2">Top doanh thu</h5>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-borderless border-top">
						<thead class="border-bottom">
							<tr>
								<th>Nhà môi giới</th>
								<th class="text-end">Doanh thu</th> 
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="d-flex justify-content-start align-items-center mt-lg-4">
										<div class="avatar me-3">
											<img src="../../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
										</div>
										<div class="d-flex flex-column">
											<h6 class="mb-1 text-truncate">Maven Analytics</h6>
											<small class="text-truncate text-muted">Business Intelligence</small>
										</div>
									</div>
								</td>
								<td class="text-end">
									<div class="user-progress mt-lg-4">
										<h6 class="mb-0">33</h6>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="d-flex justify-content-start align-items-center">
										<div class="avatar me-3">
											<img src="../../assets/img/avatars/2.png" alt="Avatar" class="rounded-circle" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
										</div>
										<div class="d-flex flex-column">
											<h6 class="mb-1 text-truncate">Zsazsa McCleverty</h6>
											<small class="text-truncate text-muted">Digital Marketing</small>
										</div>
									</div>
								</td>
								<td class="text-end">
									<div class="user-progress">
										<h6 class="mb-0">52</h6>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="d-flex justify-content-start align-items-center">
										<div class="avatar me-3">
											<img src="../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
										</div>
										<div class="d-flex flex-column">
											<h6 class="mb-1 text-truncate">Nathan Wagner</h6>
											<small class="text-truncate text-muted">UI/UX Design</small>
										</div>
									</div>
								</td>
								<td class="text-end">
									<div class="user-progress">
										<h6 class="mb-0">12</h6>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="d-flex justify-content-start align-items-center">
										<div class="avatar me-3">
											<img src="../../assets/img/avatars/4.png" alt="Avatar" class="rounded-circle" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'">
										</div>
										<div class="d-flex flex-column">
											<h6 class="mb-1 text-truncate">Emma Bowen</h6>
											<small class="text-truncate text-muted">React Native</small>
										</div>
									</div>
								</td>
								<td class="text-end">
									<div class="user-progress">
										<h6 class="mb-0">8</h6>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- Users List Table -->
	<div class="">
		<div class="card-datatable table-responsive">
			<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
				<div class="row mx-2">
					<div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
						<form action="" method="post">
							<div id="DataTables_Table_0_filter" class="dataTables_filter"><div class="input-group">
							<input type="text" class="form-control" name="keyword" placeholder="Search" value="{$keyword}" aria-label="Search" aria-describedby="button-addon2" style=" width: calc(100% - 97px);margin: 0"> 
								<input type="hidden" name="filter" value="filter">
								<button class="btn btn-outline-primary" type="submit" id="button-addon2">Tìm kiếm</button> 
							</div></div>
						</form>
					</div>
				</div>
				<div class="row g-4">
					{section name=i loop=$allItem}
						<div class="col-xl-4 col-lg-4 col-md-6">
							<div class="card">
								<div class="card-body text-center">
									<div class="mx-auto mb-3">
										<a href="{$clsClassTable->getLink($allItem[i].profile_id,$allItem[i])}" title="{$allItem[i].full_name}"><img src="{$allItem[i].avatar}" alt="{$allItem[i].full_name}" onerror="this.src='{$URL_IMAGES}/avatars/avatar.jpg'" class="rounded-circle w-px-100"></a>
									</div>
									<h5 class="mb-1 card-title"><a href="{$clsClassTable->getLink($allItem[i].profile_id,$allItem[i])}" title="{$allItem[i].full_name}">{$allItem[i].full_name}</a></h5>
									<span>{$clsProperty->getTitle($allItem[i].role_id)}</span>
									<div class="d-flex align-items-center justify-content-around my-4 py-2">
										<div>
											<h4 class="mb-1">{$clsISO->formatNumberToEasyRead($allItem[i].total_billings)}</h4>
											<span>Th�nh t�ch</span>
										</div>
										<div>
											<h4 class="mb-1">
												{if $deviceType eq 'phone'}
													{$clsISO->shortNumber($allItem[i].total_sales)}
												{else}
													{$clsISO->formatNumberToEasyRead($allItem[i].total_sales)} {$clsISO->getRate()}
												{/if}
											</h4>
											<span>Doanh s?</span>    
										</div>
										<div>
											<h4 class="mb-1">129</h4>
											<span>Connections</span>
										</div> 
									</div>
									<div class="d-flex align-items-center justify-content-center">
										{if $allItem[i].phone ne ""}<a href="javascript:;" class="btn btn-primary d-flex align-items-center me-3" {if $deviceType eq 'phone'}style="font-size:0"{/if}><i class='bx bx-phone me-1 fs-5' ></i></i>{$clsProfile->mask($allItem[i].phone, 1)}</a>{/if}
										<a href="javascript:;" class="btn btn-label-secondary btn-outline-default" {if $deviceType eq 'phone'}style="font-size:0"{/if}><i class='bx bx-envelope me-1 fs-5' ></i>{$clsProfile->mask($allItem[i].email, 1)}</a>
									</div>
								</div>
							</div>
						</div>
					{/section}
				</div>
			
				<div class="row mx-2 my-5">
					<div class="col-sm-12 col-md-6">
						<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
							<ul class="pagination flex-wrap gap-1">
								{$html_pager}
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>