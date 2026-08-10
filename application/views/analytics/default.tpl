<div class="container-xxl flex-grow-1 pt-2 container-p-y crm-ld">
	<div class="row">
		<div class="col-12 col-xxl-10 offset-xxl-1">
			{if !$analytics_access}
			<div class="alert alert-danger my-5 text-center">
				<h5 class="mb-1">Không có quyền truy cập</h5>
				<p class="mb-0 text-muted">Dashboard Quản trị BOD chỉ dành cho Ban điều hành (BOD).</p>
			</div>
			{else}
			<div class="crm-ld-eyebrow mb-2">Lăng kính Ban điều hành · MyFuture · Toàn hệ thống</div>
			<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
				<ul class="nav crm-ld-tabs" role="tablist">
					<li class="nav-item" role="presentation"><button type="button"
							class="nav-link{if $active_tab eq 'an-growth'} active{/if}" data-bs-toggle="tab"
							data-bs-target="#an-growth" role="tab" aria-controls="an-growth"
							aria-selected="{if $active_tab eq 'an-growth'}true{else}false{/if}"><i
								class="bx bx-trending-up"></i> Tăng trưởng</button></li>
					<li class="nav-item" role="presentation"><button type="button"
							class="nav-link{if $active_tab eq 'an-inventory'} active{/if}" data-bs-toggle="tab"
							data-bs-target="#an-inventory" role="tab" aria-controls="an-inventory"
							aria-selected="{if $active_tab eq 'an-inventory'}true{else}false{/if}"><i
								class="bx bx-building-house"></i> Quỹ hàng</button></li>
					<li class="nav-item" role="presentation"><button type="button"
							class="nav-link{if $active_tab eq 'an-affiliate'} active{/if}" data-bs-toggle="tab"
							data-bs-target="#an-affiliate" role="tab" aria-controls="an-affiliate"
							aria-selected="{if $active_tab eq 'an-affiliate'}true{else}false{/if}"><i
								class="bx bx-share-alt"></i> Affiliate</button></li>
					<li class="nav-item" role="presentation"><button type="button"
							class="nav-link{if $active_tab eq 'an-health'} active{/if}" data-bs-toggle="tab"
							data-bs-target="#an-health" role="tab" aria-controls="an-health"
							aria-selected="{if $active_tab eq 'an-health'}true{else}false{/if}"><i
								class="bx bx-heart"></i> Sức khỏe HST</button></li>
				</ul>
				<form method="get" action="/index.php" class="crm-ld-filter d-flex flex-wrap align-items-center gap-2">
					<input type="hidden" name="mod" value="analytics">
					<input type="hidden" name="tab" id="analytics-tab" value="{$active_tab}">
					<select name="period" class="form-select form-select-sm w-auto" onchange="this.form.submit()"
						title="Kỳ thời gian (số mới/kỳ ở Tăng trưởng &amp; Affiliate)">
						<option value="month" {if $filters.period eq 'month' } selected{/if}>Tháng này</option>
						<option value="quarter" {if $filters.period eq 'quarter' } selected{/if}>Quý này</option>
						<option value="year" {if $filters.period eq 'year' } selected{/if}>Năm nay</option>
					</select>
					<select name="region" class="form-select form-select-sm w-auto" onchange="this.form.submit()"
						title="Khu vực (Quỹ hàng)">
						<option value="">Tất cả khu vực</option>
						{foreach from=$list_regions item=rg}<option value="{$rg|escape}" {if $filters.region eq $rg}
							selected{/if}>{$rg|escape}</option>{/foreach}
					</select>
					<select name="investor" class="form-select form-select-sm w-auto" onchange="this.form.submit()"
						title="Chủ đầu tư (Quỹ hàng)">
						<option value="">Tất cả CĐT</option>
						{foreach from=$list_investors item=iv}<option value="{$iv|escape}" {if $filters.investor eq $iv}
							selected{/if}>{$iv|escape}</option>{/foreach}
					</select>
					<select name="project_id" class="form-select form-select-sm w-auto" onchange="this.form.submit()"
						title="Dự án (Quỹ hàng)">
						<option value="0">Tất cả dự án</option>
						{foreach from=$list_projects item=pj}<option value="{$pj.project_id}" {if $filters.project_id eq
							$pj.project_id} selected{/if}>{$pj.title|escape}</option>{/foreach}
					</select>
				</form>
			</div>

			<div class="tab-content p-0">
				<!-- ① TĂNG TRƯỞNG -->
				<div class="tab-pane fade{if $active_tab eq 'an-growth'} show active{/if}" id="an-growth"
					role="tabpanel">
					<div class="crm-ld-head">
						<h2 class="crm-ld-title">Tăng trưởng người dùng</h2>
						<div class="crm-ld-sub">Tổng user, người dùng mới, mức độ hoạt động (DAU/WAU/MAU) &amp; giữ
							chân.</div>
					</div>
					<div class="row g-3 mb-3">
						<div class="col-6 col-xl-2">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Tổng user</div>
									<div class="crm-ld-kpi-val">{$kpi.total_users|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-data"></i> toàn hệ thống</div><i
										class="bx bx-group crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-2">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">User mới ({$filters.period_label})</div>
									<div class="crm-ld-kpi-val is-up">{$kpi.new_users_month|number_format:0:",":"."}
									</div>{if $mom_text ne ''}<div
										class="crm-ld-kpi-sub {if $mom_up}is-up{else}is-down{/if}"><i
											class="bx bx-{if $mom_up}up{else}down{/if}-arrow-alt"></i> {$mom_text}</div>
									{else}<div class="crm-ld-kpi-sub"><i class="bx bx-user-plus"></i> tháng này</div>
									{/if}<i class="bx bx-user-plus crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-2">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">DAU (hôm nay) <i class="bx bx-info-circle info-i"
											data-bs-toggle="tooltip"
											title="Daily Active Users — số người dùng hoạt động DUY NHẤT trong 24h qua (đếm distinct; vào 10 lần vẫn tính 1)."></i>
									</div>
									<div class="crm-ld-kpi-val">{$kpi.dau|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-calendar-check"></i> đang diễn ra</div>
									<i class="bx bx-calendar-check crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-2">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">WAU (7 ngày) <i class="bx bx-info-circle info-i"
											data-bs-toggle="tooltip"
											title="Weekly Active Users — distinct user hoạt động trong 7 ngày gần nhất (cửa sổ trượt, không phải tuần lịch)."></i>
									</div>
									<div class="crm-ld-kpi-val">{$kpi.wau|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-calendar-week"></i> active 7 ngày</div>
									<i class="bx bx-calendar-week crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-2">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">MAU (30 ngày) <i class="bx bx-info-circle info-i"
											data-bs-toggle="tooltip"
											title="Monthly Active Users — distinct user hoạt động trong 30 ngày gần nhất (cửa sổ trượt)."></i>
									</div>
									<div class="crm-ld-kpi-val">{$kpi.mau|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-calendar"></i> active 30 ngày</div><i
										class="bx bx-calendar crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-2">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Stickiness <i class="bx bx-info-circle info-i"
											data-bs-toggle="tooltip"
											title="DAU/MAU — độ 'dính': trong số user hoạt động/tháng, trung bình mỗi ngày bao nhiêu % quay lại. ≥20% tốt, ≥50% xuất sắc."></i>
									</div>
									<div class="crm-ld-kpi-val is-up">{$kpi.stickiness|number_format:1:",":"."}%</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-pulse"></i> DAU/MAU</div><i
										class="bx bx-pulse crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="row g-3">
						<div class="col-12 col-lg-7">
							<div class="crm-ld-panel h-100">
								<div class="crm-ld-panel-h">
									<h4><i class="bx bx-bar-chart-alt-2"></i> User mới theo tháng</h4><span
										class="crm-ld-hint">12 tháng · reg_date</span>
								</div>
								<div class="p-3 pt-4">
									<div id="anGrowthNew" class="an-chart" data-ck="growth" data-points='{$growth_points|@json_encode}'></div>{if $mom_text ne ''}<div class="crm-ld-cap">User mới tháng này
										{$kpi.new_users_month|number_format:0:",":"."} ({$mom_text})</div>{/if}
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-5">
							<div class="crm-ld-panel h-100">
								<div class="crm-ld-panel-h">
									<h4><i class="bx bx-line-chart"></i> DAU 30 ngày</h4><span
										class="crm-ld-hint">distinct user / ngày</span>
								</div>
								<div class="p-3 pt-4">
									<div id="anDau" class="an-chart" data-ck="dau" data-points='{$dau_points|@json_encode}'></div>
									<div class="crm-ld-cap">Nhóm active chủ yếu là sale/agent dùng tra cứu.</div>
								</div>
							</div>
						</div>
					</div>
					<div class="crm-ld-panel mt-3">
						<div class="crm-ld-panel-h">
							<h4><i class="bx bx-revision"></i> Tỷ lệ quay lại (Retention) <i class="bx bx-info-circle info-i"
									data-bs-toggle="tooltip"
									title="Cohort retention = chia user theo NGÀY ĐĂNG KÝ, đo % của nhóm đó còn quay lại hoạt động ở mốc D1/D7/D30. Vd: 100 người đăng ký 01/06, sau 7 ngày còn 12 active → D7=12%. Phản ánh chất lượng giữ chân THẬT."></i>
								</h4><span
								class="crm-ld-hint">cohort 90 ngày</span>
						</div>
						<div class="p-3 crm-ld-table">
							<div class="crm-ld-th-row head" style="grid-template-columns:80px 1fr 1fr 2fr">
								<div>Mốc</div>
								<div class="text-end">Cohort</div>
								<div class="text-end">Quay lại</div>
								<div>Tỷ lệ</div>
							</div>
							{foreach from=$retention item=r}
							<div class="crm-ld-th-row" style="grid-template-columns:80px 1fr 1fr 2fr">
								<div class="p-2" style="font-weight:700;color:var(--ld-ink1)">{$r.n}</div>
								<div class="p-2 text-end ff-num">{$r.cohort|number_format:0:",":"."}</div>
								<div class="p-2 text-end ff-num">{$r.ret|number_format:0:",":"."}</div>
								<div class="p-2">
									<div class="d-flex align-items-center gap-2">
										<div class="crm-ld-mini-track"><span
												style="width:{$r.pct}%;background:var(--ld-success)"></span></div><small
											class="text-nowrap">{$r.pct}%</small>
									</div>
								</div>
							</div>
							{/foreach}
							<div class="crm-ld-note mt-3"><i class="bx bx-info-circle"></i> Retention thấp vì user mới
								(affiliate/SĐT) chưa phát sinh hoạt động tra cứu; log đăng nhập dừng từ 12/2024. Tạm
								dùng <strong>Stickiness {$kpi.stickiness|number_format:1:",":"."}%</strong> làm thước đo
								quay lại; cần bật log hoạt động cho user mới để đo chuẩn.</div>
						</div>
					</div>
					<!-- ① Cơ cấu & tăng trưởng theo gói tài khoản (_MF_PACKAGE) -->
					<div class="crm-ld-head mt-3">
						<h2 class="crm-ld-title" style="font-size:18px">Cơ cấu &amp; tăng trưởng theo gói tài khoản</h2>
						<div class="crm-ld-sub">Phân bố toàn bộ user theo gói <code>_MF_PACKAGE</code> · Free = mặc định /
							chưa nâng cấp.</div>
					</div>
					<div class="row g-3">
						<div class="col-12 col-lg-6">
							<div class="crm-ld-panel h-100">
								<div class="crm-ld-panel-h">
									<h4><i class="bx bx-pie-chart-alt-2"></i> Tỷ trọng theo gói</h4><span
										class="crm-ld-hint">_MF_PACKAGE · toàn hệ thống</span>
								</div>
								<div class="p-3">
									{foreach from=$pkg_tiers item=t}
									<div class="crm-ld-src">
										<div class="crm-ld-src-top"><span class="crm-ld-src-name"><span
													class="crm-ld-src-dot"
													style="background:{$t.color}"></span>{$t.label}</span><span
												class="crm-ld-src-val">{$t.count|number_format:0:",":"."} ·
												{$t.pct|number_format:2:",":"."}%</span></div>
										<div class="crm-ld-src-track"><span
												style="width:{if $t.count>0}max({$t.pct}%,4px){else}0{/if};background:{$t.color}"></span>
										</div>
									</div>
									{/foreach}
									<div class="crm-ld-cap">Free = chưa nâng cấp gói trả phí. Gói trả phí (Pro/VVIP) mới khởi
										động — thanh đã phóng tối thiểu để thấy được.</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-6">
							<div class="crm-ld-panel h-100">
								<div class="crm-ld-panel-h">
									<h4><i class="bx bx-bar-chart-alt-2"></i> Tăng trưởng theo gói (12 tháng)</h4><span
										class="crm-ld-hint">user mới / tháng</span>
								</div>
								<div class="p-3 pt-4">
									<div id="anPkg" class="an-chart" data-ck="pkg" data-free='{$pkg_free_pts|@json_encode}' data-pro='{$pkg_pro_pts|@json_encode}' data-vvip='{$pkg_vvip_pts|@json_encode}'></div>
									<div class="crm-ld-cap">Tăng trưởng user mới theo gói. Pro/VVIP còn rất ít nên cap màu rất
										mỏng.</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- ② QUỸ HÀNG -->
				<div class="tab-pane fade{if $active_tab eq 'an-inventory'} show active{/if}" id="an-inventory"
					role="tabpanel">
					<div class="crm-ld-head">
						<h2 class="crm-ld-title">Quỹ hàng &amp; hàng hóa</h2>
						<div class="crm-ld-sub">Tồn kho, tốc độ hấp thụ theo dự án &amp; cơ cấu sản phẩm (chỉ tính quỹ
							đã mở bán).</div>
					</div>
					<div class="row g-3 mb-3">
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Tổng dự án</div>
									<div class="crm-ld-kpi-val">{$kpi.total_projects|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-buildings"></i> đang phân phối</div><i
										class="bx bx-buildings crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Căn đã mở bán</div>
									<div class="crm-ld-kpi-val">{$kpi.total_units|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-home"></i> status &gt; 0</div><i
										class="bx bx-home crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Tỷ lệ hấp thụ</div>
									<div class="crm-ld-kpi-val is-up">{$kpi.absorption|number_format:1:",":"."}%</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-trending-up"></i>
										{$kpi.sold_units|number_format:0:",":"."} đã bán</div><i
										class="bx bx-pie-chart-alt-2 crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Tồn kho</div>
									<div class="crm-ld-kpi-split"><span style="color:var(--ld-success-ink)">Còn
											hàng</span><b class="ff-num"
											style="color:var(--ld-success-ink)">{$kpi.avail_units|number_format:0:",":"."}</b>
									</div>
									<div class="crm-ld-kpi-split"><span style="color:var(--ld-warning-ink)">Giữ
											chỗ</span><b class="ff-num"
											style="color:var(--ld-warning-ink)">{$kpi.lock_units|number_format:0:",":"."}</b>
									</div>
									<div class="crm-ld-kpi-split"><span style="color:var(--ld-danger-ink)">Đã
											bán</span><b class="ff-num"
											style="color:var(--ld-danger-ink)">{$kpi.sold_units|number_format:0:",":"."}</b>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="crm-ld-panel mb-3">
						<div class="crm-ld-panel-h">
							<h4><i class="bx bx-bar-chart"></i> Tốc độ hấp thụ theo dự án</h4><span
								class="crm-ld-hint">top 10 · căn mở bán</span>
						</div>
						<div class="p-3 crm-ld-table">
							<div class="crm-ld-th-row head" style="grid-template-columns:2fr 1fr 1fr 1fr 2fr">
								<div>Dự án</div>
								<div class="text-end">Mở bán</div>
								<div class="text-end">Đã bán</div>
								<div class="text-end">Giữ chỗ</div>
								<div>Hấp thụ</div>
							</div>
							{foreach from=$proj_rows item=r}
							<div class="crm-ld-th-row" style="grid-template-columns:2fr 1fr 1fr 1fr 2fr">
								<div class="p-2" style="font-weight:600;color:var(--ld-ink1)">{$r.title|escape}</div>
								<div class="p-2 text-end ff-num">{$r.total|number_format:0:",":"."}</div>
								<div class="p-2 text-end ff-num">{$r.sold|number_format:0:",":"."}</div>
								<div class="p-2 text-end ff-num">{$r.lock|number_format:0:",":"."}</div>
								<div class="p-2">
									<div class="d-flex align-items-center gap-2">
										<div class="crm-ld-mini-track"><span
												style="width:{$r.pct}%;background:var(--ld-{$r.bc})"></span></div><small
											class="text-nowrap">{$r.pct}%</small>
									</div>
								</div>
							</div>
							{/foreach}
						</div>
					</div>
					<div class="row g-3">
						<div class="col-12 col-lg-6">
							<div class="crm-ld-panel h-100">
								<div class="crm-ld-panel-h">
									<h4><i class="bx bx-map"></i> Cơ cấu theo khu vực</h4>
								</div>
								<div class="p-3">
									{foreach from=$region_rows item=r}
									<div class="crm-ld-src">
										<div class="crm-ld-src-top"><span class="crm-ld-src-name"><span
													class="crm-ld-src-dot"
													style="background:{$r.color}"></span>{$r.name|escape}</span><span
												class="crm-ld-src-val">{$r.units|number_format:0:",":"."}</span></div>
										<div class="crm-ld-src-track"><span
												style="width:{$r.pct}%;background:{$r.color}"></span></div>
									</div>
									{/foreach}
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-6">
							<div class="crm-ld-panel h-100">
								<div class="crm-ld-panel-h">
									<h4><i class="bx bx-buildings"></i> Cơ cấu theo chủ đầu tư</h4>
								</div>
								<div class="p-3">
									{foreach from=$investor_rows item=r}
									<div class="crm-ld-src">
										<div class="crm-ld-src-top"><span class="crm-ld-src-name"><span
													class="crm-ld-src-dot"
													style="background:{$r.color}"></span>{$r.name|escape}</span><span
												class="crm-ld-src-val">{$r.units|number_format:0:",":"."}</span></div>
										<div class="crm-ld-src-track"><span
												style="width:{$r.pct}%;background:{$r.color}"></span></div>
									</div>
									{/foreach}
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- ③ AFFILIATE -->
				<div class="tab-pane fade{if $active_tab eq 'an-affiliate'} show active{/if}" id="an-affiliate"
					role="tabpanel">
					<div class="crm-ld-head">
						<h2 class="crm-ld-title">Mạng lưới Affiliate</h2>
						<div class="crm-ld-sub">Quy mô mạng lưới, phát triển F1/F2/F3, người giới thiệu hoạt động &amp;
							BXH.</div>
					</div>
					<div class="row g-3 mb-3">
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Tổng Affiliate</div>
									<div class="crm-ld-kpi-val">{$kpi.total_affiliates|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-network-chart"></i> tài khoản</div><i
										class="bx bx-network-chart crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Người GT hoạt động</div>
									<div class="crm-ld-kpi-val">{$kpi.active_referrers|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-user-check"></i> có ≥1 GT</div><i
										class="bx bx-user-check crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Giới thiệu mới ({$filters.period_label})</div>
									<div class="crm-ld-kpi-val is-up">{$new_gen.total|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><span class="gentag"><i
												style="background:#8592a3"></i>F1 <b>{$new_gen[1]}</b></span><span
											class="gentag"><i style="background:#696cff"></i>F2 <b>{$new_gen[2]}</b></span><span
											class="gentag"><i style="background:#f5b50a"></i>F3 <b>{$new_gen[3]}</b></span></div><i
										class="bx bx-user-plus crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Hoa hồng ({$filters.period_label})</div>
									<div class="crm-ld-kpi-val">{$kpi.commission_month|number_format:0:",":"."}₫</div>
									{if $kpi.commission_month eq 0}<div class="crm-ld-kpi-sub"><i
											class="bx bx-info-circle"></i> chưa phát sinh</div>{/if}<i
										class="bx bx-dollar-circle crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="row g-3">
						<div class="col-12 col-lg-6">
							<div class="crm-ld-panel h-100">
								<div class="crm-ld-panel-h">
									<h4><i class="bx bx-sitemap"></i> Phân bố thế hệ</h4><span
										class="crm-ld-hint">F1/F2/F3</span>
								</div>
								<div class="p-3 pt-4">
									<div id="anGen" class="an-chart" data-ck="gen" data-points='{$gen_points|@json_encode}'></div>
									<div class="crm-ld-cap">Mạng lưới còn nông (chủ yếu F1) — chương trình vừa khởi
										động.</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-6">
							<div class="crm-ld-panel h-100">
								<div class="crm-ld-panel-h">
									<h4><i class="bx bx-trophy"></i> BXH Affiliate — theo tổng mạng lưới</h4><span
										class="crm-ld-hint">F1/F2/F3 = downline của người đó</span>
								</div>
								<div class="p-3 crm-ld-table">
									<div class="crm-ld-th-row head" style="grid-template-columns:46px 1fr 60px 60px 60px 76px">
										<div>#</div>
										<div>Affiliate</div>
										<div class="text-end">F1</div>
										<div class="text-end">F2</div>
										<div class="text-end">F3</div>
										<div class="text-end">Tổng</div>
									</div>
									{foreach from=$aff_top item=r}
									<div class="crm-ld-th-row" style="grid-template-columns:46px 1fr 60px 60px 60px 76px">
										<div class="p-2"><span class="crm-ld-lb-rank {$r.rankcls}">{$r.rank}</span></div>
										<div class="p-2" style="font-weight:600;color:var(--ld-ink1)">{$r.name|escape}</div>
										<div class="p-2 text-end ff-num">{$r.f1|number_format:0:",":"."}</div>
										<div class="p-2 text-end ff-num{if $r.f2 eq 0} muted0{/if}">{$r.f2|number_format:0:",":"."}
										</div>
										<div class="p-2 text-end ff-num{if $r.f3 eq 0} muted0{/if}">{$r.f3|number_format:0:",":"."}
										</div>
										<div class="p-2 text-end ff-num" style="font-weight:700;color:var(--ld-brand-d)">
											{$r.total|number_format:0:",":"."}</div>
									</div>
									{foreachelse}
									<div class="text-center text-muted py-3">Chưa có dữ liệu</div>
									{/foreach}
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- ⑤ SỨC KHỎE HST -->
				<div class="tab-pane fade{if $active_tab eq 'an-health'} show active{/if}" id="an-health"
					role="tabpanel">
					<div class="crm-ld-head">
						<h2 class="crm-ld-title">Sức khỏe hệ sinh thái MyFuture</h2>
						<div class="crm-ld-sub">Tổng hợp toàn hệ thống — user, quỹ hàng đang phân phối, affiliate &amp;
							tra cứu.</div>
					</div>
					<div class="row g-3 mb-3">
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Tổng User</div>
									<div class="crm-ld-kpi-val">{$kpi.total_users|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub is-up"><i class="bx bx-up-arrow-alt"></i> active 30d:
										{$kpi.mau|number_format:0:",":"."}</div><i
										class="bx bx-group crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Căn đang phân phối</div>
									<div class="crm-ld-kpi-val">{$kpi.avail_units|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-home"></i>
										{$kpi.total_projects|number_format:0:",":"."} dự án</div><i
										class="bx bx-home crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Affiliate hoạt động</div>
									<div class="crm-ld-kpi-val">{$kpi.active_referrers|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-network-chart"></i> /
										{$kpi.total_affiliates|number_format:0:",":"."} tài khoản</div><i
										class="bx bx-user-check crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
						<div class="col-6 col-xl-3">
							<div class="card crm-ld-kpi">
								<div class="card-body">
									<div class="crm-ld-kpi-label">Lượt tra cứu (hôm nay)</div>
									<div class="crm-ld-kpi-val">{$kpi.searches_today|number_format:0:",":"."}</div>
									<div class="crm-ld-kpi-sub"><i class="bx bx-search-alt"></i> search _user</div><i
										class="bx bx-search-alt crm-ld-kpi-ic"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="row g-3">
						<div class="col-12 col-lg-6">
							<div class="crm-ld-fc">
								<div class="crm-ld-fc-eye"><i class="bx bx-pulse"></i> Chỉ số sức khỏe tổng</div>
								<div class="d-flex align-items-end gap-2"><span
										class="crm-ld-fc-big">{$kpi.stickiness|number_format:1:",":"."}</span><span
										style="font-size:18px;font-weight:600;color:rgba(255,255,255,.7);padding-bottom:6px">%
										stickiness</span></div>
								<div class="d-flex align-items-center gap-1 mt-2"
									style="font-size:13px;font-weight:600;color:#8de36a"><i
										class="bx bx-trending-up"></i> Hấp thụ
									{$kpi.absorption|number_format:1:",":"."}% · User mới tháng
									{$kpi.new_users_month|number_format:0:",":"."}</div>
								<div class="crm-ld-fc-row">
									<div>
										<div class="crm-ld-fc-k">DAU / MAU</div>
										<div class="ff-num" style="font-size:20px;font-weight:700;margin-top:3px">
											{$kpi.dau|number_format:0:",":"."} / {$kpi.mau|number_format:0:",":"."}
										</div>
									</div>
									<div>
										<div class="crm-ld-fc-k">Affiliate F1 (tháng)</div>
										<div class="ff-num" style="font-size:20px;font-weight:700;margin-top:3px">
											{$kpi.f1_new_month|number_format:0:",":"."}</div>
									</div>
									<div>
										<div class="crm-ld-fc-k">Quỹ đã bán</div>
										<div class="ff-num" style="font-size:20px;font-weight:700;margin-top:3px">
											{$kpi.sold_units|number_format:0:",":"."}</div>
									</div>
								</div>
								<i class="bx bx-heart crm-ld-fc-ic"></i>
							</div>
						</div>
						<div class="col-12 col-lg-6">
							<div class="crm-ld-panel h-100">
								<div class="crm-ld-panel-h">
									<h4><i class="bx bx-bar-chart-alt-2"></i> Tăng trưởng user (12 tháng)</h4>
								</div>
								<div class="p-3 pt-4">
									<div id="anGrowthTotal" class="an-chart" data-ck="growth" data-points='{$growth_points|@json_encode}'></div>
									<div class="crm-ld-cap">Tổng {$kpi.total_users|number_format:0:",":"."} user.</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			{/if}
		</div>
	</div>
</div>