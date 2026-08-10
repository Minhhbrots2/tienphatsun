<div class="worldcup_page" ng-app="wcApp">	
	<div class="main-content" ng-controller="MainCtrl as app" ng-cloak>
		<div class="container-xxl">
			<script>window.WC_BOOT = {$wc_boot|@json_encode};</script>
			{literal}
			<div class="app">
				<!-- ============================ TOP NAV ============================ -->
				<header class="topbar">
					<div class="wrap row_wc">
						<div class="brandmark">
							<svg width="26" height="26" viewBox="0 0 24 24" fill="none" style="color:var(--brand-700)" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
							<span>Dự đoán <span class="hl">WC 2026</span></span>
						</div>
						<nav class="topnav">
							<button ng-repeat="n in app.nav" ng-class="{on: app.tab===n.k}" ng-click="app.setTab(n.k)">
								<ic name="{{n.ic}}"></ic>
								{{n.l}}
							</button>
						</nav>
						<div class="spacer"></div>
						<div class="right">
							<div class="ptschip">
								<ic name="trophy" color="var(--brand-700)"></ic>
								<span>{{app.me.pts}}</span><span style="color:var(--ink-4)">· #{{app.me.rank}}</span>
							</div>
							<button class="btn primary sm" ng-if="app.isDev" ng-click="app.adminOpen=true">
								<ic name="shield-check"></ic>
								<span class="adminlbl">Quản trị</span>
							</button>
						</div>
					</div>
				</header>
				<main class="main" ng-switch="app.tab">
					<!-- ============================ MATCHES ============================ -->
					<div ng-switch-when="matches" class="col gap-18">
						<!-- hero -->
						<section class="hero">
							<div class="lines"></div>
							<div class="glow"></div>
							<div class="inner">
								<div style="min-width:280px">
									<span class="tag">MINI GAME</span>
									<h1>Dự đoán <span class="gold">WC 2026</span></h1>
									<div class="sub">Dự đoán hay — rinh quà khủng</div>
									<div class="feats">
										<span>
											<ic name="target" color="#e7c97a"></ic>
											Dự đoán mọi trận
										</span>
										<span>
											<ic name="bar-chart-3" color="#e7c97a"></ic>
											BXH real-time
										</span>
										<span>
											<ic name="gift" color="#e7c97a"></ic>
											Cộng điểm mỗi round
										</span>
									</div>
								</div>
								<div style="text-align:center">
									<div ng-if="app.hasNextMatch">
										<div class="cdlabel">LOẠT TRẬN TIẾP THEO LĂN BÓNG SAU</div>
										<div class="countdown">
											<div class="cd-cell" ng-repeat-start="c in app.cd">
												<div class="v">{{c.v}}</div>
												<div class="l">{{c.l}}</div>
											</div>
											<div class="cd-colon" ng-if="!$last" ng-repeat-end>:</div>
										</div>
									</div>
									<div ng-if="!app.hasNextMatch" class="cdlabel" style="padding:14px 0 4px">Chưa có loạt trận sắp tới</div>
									<div class="mechip">
										<ic name="trophy" color="#e7c97a"></ic>
										<span>Điểm của bạn: <b>{{app.me.pts}}</b></span>
										<span class="div"></span>
										<span>Hạng <b>#{{app.me.rank}}</b></span>
									</div>
								</div>
							</div>
						</section>
						<!-- section head -->
						<div class="sec-head">
							<div>
								<h2 class="h2">Lịch thi đấu</h2>
							</div>
							<span class="pill ok" ng-if="!app.slotLocked()">
								<ic name="circle-dot"></ic>
								Đang mở bình chọn
							</span>
						</div>
						<!-- gate đăng nhập / xác thực -->
							<div class="lock-note" ng-if="!app.isLoggedIn" style="background:var(--warning-soft);border-color:var(--warning);color:var(--warning-ink)">
								<ic name="log-in" color="var(--warning)"></ic>
								Bạn cần <a ng-href="{{app.loginUrl}}" style="color:inherit;font-weight:700;border-bottom:1px solid">đăng nhập</a> để dự đoán. Vẫn có thể xem lịch thi đấu &amp; bảng xếp hạng.
							</div>
							<div class="lock-note" ng-if="app.isLoggedIn && !app.isVerified" style="background:var(--warning-soft);border-color:var(--warning);color:var(--warning-ink)">
								<ic name="alert-circle" color="var(--warning)"></ic>
								Tài khoản chưa xác thực — chưa thể bình chọn.
							</div>
							<!-- cards -->
						<div class="day-section" ng-repeat="g in app.matchGroups() track by g.key">
							<div class="day-head">
								<ic name="clock" color="var(--brand-700)"></ic>
								<span class="day-title">{{g.label}}</span>
								<span class="day-count">{{g.matches.length}} trận</span>
							</div>
							<div class="cards">
							<div class="card" ng-repeat="m in g.matches track by m.id">
								<div class="card-head">
									<div class="eyebrow">{{m.round}}</div>
									<span class="pill muted" ng-if="app.isFinished(m)">
										<ic name="flag"></ic>
										Đã có kết quả
									</span>
									<span class="pill muted" ng-if="app.notYetOpen(m)">
										<ic name="lock"></ic>
										Mở lúc {{app.timeTag(app.openAt(m)).time}} {{app.timeTag(app.openAt(m)).day}}
									</span>
									<span class="pill muted" ng-if="app.isClosed(m)">
											<ic name="lock"></ic>
											Đã đóng bình chọn
										</span>
										<span class="pill brand" ng-if="app.isOpen(m)">
										<ic name="timer"></ic>
										Dự đoán trước {{m.last_time_vote}}
										<!--Còn {{app.toLock(m) | hms}} để dự đoán-->
									</span>
								</div>
								<div class="teams" ng-class="{stacked: app.tw.cardVariant==='stacked'}">
									<div class="team">
										<flag code="{{m.home.code}}" size="{{app.tw.cardVariant==='stacked' ? 56 : 46}}"></flag>
										<div style="min-width:0">
											<div class="nm">{{m.home.name}}</div>
											<div class="sh">{{m.home.short}}</div>
										</div>
									</div>
									<div class="vs">
										<div class="lab">VS</div>
										<div class="ko">{{app.timeTag(m.slot).time}}<br>{{app.timeTag(m.slot).day}}</div>
									</div>
									<div class="team right">
										<flag code="{{m.away.code}}" size="{{app.tw.cardVariant==='stacked' ? 56 : 46}}"></flag>
										<div style="min-width:0">
											<div class="nm">{{m.away.name}}</div>
											<div class="sh">{{m.away.short}}</div>
										</div>
									</div>
								</div>
								<div class="votes">
									<button class="vote" ng-class="app.voteClass(m,'H')" ng-click="app.pick(m,'H')">
										<span class="bar d-none" ng-if="app.tw.showCommunity" ng-style="{width: app.pct(m.votes,'H')+'%'}"></span>
										<span class="top">
											<ic ng-if="app.showCheck(m,'H')" name="check"></ic>
											<span class="lab">{{m.home.short}}</span>
										</span>
										<span class="sub">Thắng<span class="d-none" ng-if="app.tw.showCommunity"> · {{app.pct(m.votes,'H')}}%</span></span>
									</button>
									<button class="vote" ng-if="!app.isKnockout(m)" ng-class="app.voteClass(m,'D')" ng-click="app.pick(m,'D')">
										<span class="bar" ng-if="app.tw.showCommunity" ng-style="{width: app.pct(m.votes,'D')+'%'}"></span>
										<span class="top">
											<ic ng-if="app.showCheck(m,'D')" name="check"></ic>
											<span class="lab">Hòa</span>
										</span>
										<span class="sub">X<span class="d-none" ng-if="app.tw.showCommunity"> · {{app.pct(m.votes,'D')}}%</span></span>
									</button>
									<button class="vote" ng-class="app.voteClass(m,'A')" ng-click="app.pick(m,'A')">
										<span class="bar d-none" ng-if="app.tw.showCommunity" ng-style="{width: app.pct(m.votes,'A')+'%'}"></span>
										<span class="top">
											<ic ng-if="app.showCheck(m,'A')" name="check"></ic>
											<span class="lab">{{m.away.short}}</span>
										</span>
										<span class="sub">Thắng<span class="d-none" ng-if="app.tw.showCommunity"> · {{app.pct(m.votes,'A')}}%</span></span>
									</button>
								</div>
								<div class="card-foot">
									<span class="l">
										<ic name="users" color="var(--ink-4)"></ic>
										{{app.totalVotes(m.votes) | number}} lượt dự đoán
									</span>
									<span class="picked" ng-if="app.picks[m.id]">
										<ic name="check-check" color="var(--brand-700)"></ic>
										Đã chọn: {{app.voteLabel(m, app.picks[m.id])}}
									</span>
									<span style="color:var(--ink-4)" ng-if="!app.picks[m.id] && app.notYetOpen(m)">Mở bình chọn {{app.timeTag(app.openAt(m)).time}} {{app.timeTag(app.openAt(m)).day}}</span>
										<span ng-if="!app.picks[m.id] && app.isClosed(m)">Chưa kịp dự đoán</span>
									<span style="color:var(--ink-4)" ng-if="!app.picks[m.id] && !app.isLocked(m)">Chọn 1 cửa để dự đoán</span>
								</div>
							</div>
						</div>
						</div>
							<!--=============== [Worldcup - Empty/Error state trận đấu] - START ===============-->
							<div class="lock-note" ng-if="app.loadError" style="background:var(--warning-soft);border-color:var(--warning);color:var(--warning-ink)">
								<ic name="alert-triangle" color="var(--warning)"></ic>
								Không tải được dữ liệu trận đấu. Vui lòng tải lại trang hoặc kiểm tra kết nối mạng.
							</div>
							<div class="lock-note" ng-if="!app.loading && !app.loadError && !app.upcomingMatches().length">
								<ic name="info" color="var(--brand-700)"></ic>
								Chưa có trận đấu nào.<span ng-if="app.isDev">&nbsp;Hãy import lịch thi đấu (Quản trị → Import CSV) hoặc chạy <code>worldcup_fixtures.sql</code> vào CSDL.</span>
							</div>
							<!--=============== [Worldcup - Empty/Error state trận đấu] - END ===============-->
							<!-- demo bar -->
						<div class="center" style="padding-top:4px" ng-if="false">
							<div class="demobar">
								<span class="lbl">
									<ic name="clock" color="var(--ink-4)"></ic>
									Mô phỏng thời gian:
								</span>
								<div class="seg">
									<button ng-repeat="o in app.demoOpts" ng-class="{on: app.offsetMin===o.v}" ng-click="app.setOffset(o.v)">{{o.l}}</button>
								</div>
							</div>
						</div>
					</div>
					<!-- ============================ RESULTS ============================ -->
					<div ng-switch-when="results" class="col gap-22">
						<div class="col gap-22" ng-if="app.allFinished().length">
						<div>
							<div class="eyebrow brand">Round vừa kết thúc</div>
							<h2 class="h2">Kết quả · {{app.lastFinishedSlot() | kickoff}} {{app.lastFinishedSlot() | dateVi}}</h2>
						</div>
						<div class="earn">
							<div class="lft">
								<div class="ico">
									<ic name="trophy" color="#e7c97a"></ic>
								</div>
								<div>
									<div class="k">Bạn nhận được round này</div>
									<div class="v">+{{app.roundEarned()}} <small>điểm</small></div>
								</div>
							</div>
							<div class="rgt">Đoán đúng <b>{{app.roundCorrect()}}/{{app.finishedMatches().length}}</b> trận<br>Hạng hiện tại: <b>#{{app.me.rank}}</b></div>
						</div>
						<div class="cards">
							<div class="res-card" ng-class="{win: m.myPick && m.myPick===m.result}" ng-repeat="m in app.finishedMatches()">
								<div class="card-head">
									<div class="eyebrow">{{m.round}}</div>
									<span class="pill ok" ng-if="m.myPick && m.myPick===m.result">
										<ic name="check"></ic>
										+{{app.scoreFor(m).pts}} điểm
									</span>
									<span class="pill bad" ng-if="m.myPick && m.myPick!==m.result">
										<ic name="x"></ic>
										+0
									</span>
									<span class="pill ghost" ng-if="!m.myPick">Bỏ qua</span>
								</div>
								<div class="tline">
									<div class="t">
										<flag code="{{m.home.code}}" size="34"></flag>
										<span class="nm">{{m.home.short}}</span>
									</div>
									<span class="sc">{{m.score[0]}}–{{m.score[1]}}<small ng-if="m.pen" style="font-size:.62em;color:var(--ink-4);font-weight:600"> pen {{m.pen[0]}}-{{m.pen[1]}}</small></span>
									<div class="t r">
										<span class="nm">{{m.away.short}}</span>
										<flag code="{{m.away.code}}" size="34"></flag>
									</div>
								</div>
								<div class="meta">
									<span>Kết quả: <b>{{app.outcomeLabel[m.result]}}</b></span>
									<span>Bạn chọn: <b ng-style="{color: m.myPick ? (m.myPick===m.result ? 'var(--success)' : 'var(--danger)') : 'var(--ink-4)'}">{{ m.myPick ? app.voteLabel(m, m.myPick) : '—' }}</b></span>
								</div>
								<div class="bonus" ng-if="app.scoreFor(m).bonus > 0">
									<ic name="sparkles" color="var(--warning)"></ic>
									Thưởng cửa ít người chọn +{{app.scoreFor(m).bonus}}
								</div>
							</div>
						</div>
						</div>
						<div class="wc-empty" ng-if="!app.allFinished().length">
							<div class="wc-empty-ic"><ic name="flag"></ic></div>
							<div class="wc-empty-title">Chưa có kết quả nào</div>
							<div class="wc-empty-sub">Kết quả &amp; điểm sẽ xuất hiện ngay khi trận đấu đầu tiên kết thúc. Hãy dự đoán trước nhé!</div>
							<button class="btn primary sm wc-empty-cta" ng-click="app.setTab('matches')"><ic name="swords"></ic> Đến Trận đấu</button>
						</div>
					</div>
					<!-- ========================= LEADERBOARD ========================== -->
					<div ng-switch-when="leaderboard" class="col gap-22">
						<div class="col gap-22" ng-if="app.podiumOrder.length || app.lbRest.length">
						<div class="sec-head">
							<div>
								<div class="eyebrow brand d-none">Cập nhật real-time</div>
								<h2 class="h2">Bảng xếp hạng</h2>
							</div>
							<div class="seg d-none">
								<button ng-repeat="t in ['Tổng','Round này','Tuần này']" ng-class="{on: app.lbTab===t}" ng-click="app.lbTab=t">{{t}}</button>
							</div>
						</div>
						<div class="podium">
							<div class="stand">
								<div class="slot" ng-repeat="u in app.podiumOrder">
									<div class="av" ng-style="{borderColor: app.medal[u.rank-1]}" ><img ng-src="{{u.avt}}" onerror="this.src='{/literal}{$URL_IMAGES}/no-avatar.jpg{literal}'" alt="" width="54" height="54" class="avatar w-100 h-100 img-cover rounded-pill"></div>
									<div class="nm">{{u.name}}</div>
									<div class="pts">{{u.pts}} điểm</div>
									<div class="bar" ng-style="{height: app.podiumH[u.rank-1]+'px', background: 'linear-gradient(180deg,'+app.medal[u.rank-1]+',rgba(255,255,255,0.06))'}">{{u.rank}}</div>
								</div>
							</div>
						</div>
						<div class="overflow-x-auto">
							<table class="lb">
								<thead>
									<tr class="lb-head">
										<th>Hạng</th><th>Người chơi</th>
										<th style="text-align:right">Đúng</th><th style="text-align:right">Streak</th><th style="text-align:right">Điểm</th>
									</tr>
								</thead>
								<tbody>
									<tr class="lb-row" ng-class="{me: u.me}" ng-repeat="u in app.lbRest">
										<td class="rk text-center">{{u.rank}}</td>
										<td class="who">
											<div class="d-flex align-items-center">
												<span class="ava" ng-if="u.avt"><img ng-src="{{u.avt}}" onerror="this.src='{/literal}{$URL_IMAGES}/no-avatar.jpg{literal}'" alt="" width="30" height="30" class="avatar w-100 h-100 img-cover rounded-pill"></span><span class="name">{{u.name || '--'}}</span><span class="tag-me" ng-if="u.me">Bạn</span>
											</div>
										</td>
										<td class="c text-center">{{u.correct}}</td>
										<td class="st text-center"><span class="flame" ng-if="u.streak>0"><ic name="flame" color="var(--warning)"></ic>{{u.streak}}</span><span class="dash" ng-if="u.streak===0">—</span></td>
										<td class="p text-center">{{u.pts}}</td>
									</tr>
								</tbody>
							</table>	
						</div>
						</div>
						<div class="wc-empty loading" ng-if="!app.lbLoaded">
							<div class="wc-empty-ic"><ic name="clock"></ic></div>
							<div class="wc-empty-title">Đang tải bảng xếp hạng…</div>
							<div class="wc-empty-sub">Vui lòng chờ trong giây lát.</div>
						</div>
						<div class="lb-empty" ng-if="app.lbLoaded && !app.podiumOrder.length && !app.lbRest.length">
							<div class="lb-ghost" aria-hidden="true">
								<div class="podium">
									<div class="stand">
										<div class="slot" ng-repeat="u in app.lbGhost.podium">
											<div class="av" ng-style="{borderColor: app.medal[u.rank-1]}"><img src="{{u.avt}}"  onerror="this.src='{/literal}{$URL_IMAGES}/no-avatar.jpg{literal}'" alt="" width="54" height="54" class="avatar w-100 h-100 img-cover rounded-pill"></div>
											<div class="nm">{{u.name}}</div>
											<div class="pts" ng-if="u.pts">{{u.pts}} điểm</div>
											<div class="bar" ng-style="{height: app.podiumH[u.rank-1]+'px', background: 'linear-gradient(180deg,'+app.medal[u.rank-1]+',rgba(255,255,255,0.06))'}">{{u.rank}}</div>
										</div>
									</div>
								</div>
								<div class="overflow-x-auto">
									<table class="lb">
										<thead>
											<tr class="lb-head">
												<th>Hạng</th><th>Người chơi</th>
												<th style="text-align:right">Đúng</th><th style="text-align:right">Streak</th><th style="text-align:right">Điểm</th>
											</tr>
										</thead>
										<tbody>
											<tr class="lb-row" ng-repeat="u in app.lbGhost.rest">
												<td class="rk text-center">{{u.rank}}</td>
												<td class="who">
													<div class="d-flex align-items-center">
														<span class="ava" ng-if='u.avt'><img ng-src="{{u.avt}}" onerror="this.src='{/literal}{$URL_IMAGES}/no-avatar.jpg{literal}'" alt="" width="30" height="30" class="avatar w-100 h-100 img-cover rounded-pill"></span><span class="name">{{u.name}}</span>
													</div>
												</td>
												<td class="c text-center">{{u.correct || '--'}}</td>
												<td class="st text-center"><span class="flame" ng-if="u.streak>0"><ic name="flame" color="var(--warning)"></ic>{{u.streak || '--'}}</span><span class="dash" ng-if="u.streak===0">—</span></td>
												<td class="p text-center">{{u.pts || '--'}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- =========================== HISTORY ============================ -->
					<div ng-switch-when="history" class="col gap-22">
						<div class="col gap-22" ng-if="app.allFinished().length">
						<div>
							<div class="eyebrow brand">Theo dõi phong độ</div>
							<h2 class="h2">Lịch sử dự đoán của bạn</h2>
						</div>
						<div class="stats">
							<div class="stat">
								<div class="k">Tổng điểm tích lũy</div>
								<div class="v brand">{{app.me.pts}}</div>
								<div class="s">trên toàn giải</div>
							</div>
							<div class="stat">
								<div class="k">Tỉ lệ đoán đúng</div>
								<div class="v">{{app.histAccuracy()}}%</div>
								<div class="s">{{app.histCorrect()}}/{{app.histPlayed()}} trận gần đây</div>
							</div>
							<div class="stat">
								<div class="k">Chuỗi đúng hiện tại</div>
								<div class="v warn">{{app.me.streak}}</div>
								<div class="s">trận liên tiếp</div>
							</div>
						</div>
						<div class="hist">
							<div class="hist-row" ng-repeat="m in app.allFinished()">
								<div class="res">
									<flag code="{{m.home.code}}" size="30"></flag>
									<span class="sc">{{m.score[0]}}–{{m.score[1]}}<small ng-if="m.pen" style="font-size:.62em;color:var(--ink-4);font-weight:600"> pen {{m.pen[0]}}-{{m.pen[1]}}</small></span>
									<flag code="{{m.away.code}}" size="30"></flag>
									<span class="mt">{{m.home.short}} v {{m.away.short}}</span>
								</div>
								<div class="pk">Bạn chọn: <b>{{ m.myPick ? app.voteLabel(m, m.myPick) : '—' }}</b></div>
								<span class="pill ok" ng-if="m.myPick && m.myPick===m.result">
									<ic name="check"></ic>
									Đúng
								</span>
								<span class="pill bad" ng-if="m.myPick && m.myPick!==m.result">
									<ic name="x"></ic>
									Sai
								</span>
								<span class="pill ghost" ng-if="!m.myPick">Bỏ qua</span>
								<div class="pts" ng-class="app.scoreFor(m).pts ? 'plus' : 'zero'">{{app.scoreFor(m).pts ? '+'+app.scoreFor(m).pts : '0'}}</div>
							</div>
						</div>
						</div>
						<div class="wc-empty" ng-if="!app.allFinished().length">
							<div class="wc-empty-ic"><ic name="history"></ic></div>
							<div class="wc-empty-title">Chưa có lịch sử dự đoán</div>
							<div class="wc-empty-sub">Các trận đã kết thúc cùng kết quả dự đoán của bạn sẽ hiển thị tại đây.</div>
							<button class="btn primary sm wc-empty-cta" ng-click="app.setTab('matches')"><ic name="swords"></ic> Bắt đầu dự đoán</button>
						</div>
					</div>
				</main>
				<!-- ============================ BOTTOM NAV ========================== -->
				<nav class="bottomnav">
					<button ng-repeat="n in app.nav" ng-class="{on: app.tab===n.k}" ng-click="app.setTab(n.k)">
						<ic name="{{n.ic}}"></ic>
						<span>{{n.l}}</span>
					</button>
				</nav>
			</div>
			<!-- ============================ SETTINGS PANEL ======================= -->
			<button class="gear d-none" ng-if="!app.panelOpen" ng-click="app.panelOpen=true" title="Tùy chỉnh">
				<ic name="sliders-horizontal"></ic>
			</button>
			<div class="panel" ng-if="app.panelOpen">
				<div class="ph">
					<span class="t">Tùy chỉnh</span>
					<button class="x" ng-click="app.panelOpen=false">
						<ic name="x"></ic>
					</button>
				</div>
				<div class="ts">Giao diện thẻ trận</div>
				<div class="tr">
					<span class="lab">Bố cục thẻ</span>
					<div class="seg mini">
						<button ng-class="{on: app.tw.cardVariant==='classic'}" ng-click="app.tw.cardVariant='classic'">classic</button>
						<button ng-class="{on: app.tw.cardVariant==='stacked'}" ng-click="app.tw.cardVariant='stacked'">stacked</button>
					</div>
				</div>
				<div class="tr"><span class="lab">Hiển thị % cộng đồng</span><button class="sw" ng-class="{on: app.tw.showCommunity}" ng-click="app.tw.showCommunity=!app.tw.showCommunity"></button></div>
				<div class="ts">Luật bình chọn</div>
				<div class="tr"><span class="lab">Khóa trước giờ bóng lăn</span><span class="val">{{app.tw.lockMin}} phút</span></div>
				<input type="range" min="5" max="60" step="5" ng-model="app.tw.lockMin" />
				<div class="ts" ng-if="app.isDev">Cơ chế cộng điểm</div>
				<div class="lock-note" ng-if="app.isDev" style="margin-top:4px">Điểm thực do server cộng <b>theo từng vòng</b> (đoán đúng). Chỉnh tại <button class="swap" ng-click="app.adminOpen=true; app.adminTab='points'; app.loadCfgForm()">Quản trị → Điểm theo vòng</button>.</div>
			</div>
			<!-- ============================ ADMIN MODAL ========================== -->
			<div class="overlay" ng-if="app.adminOpen" ng-click="app.adminOpen=false">
				<div class="modal admin_modal" ng-click="$event.stopPropagation()">
					<div class="modal-head">
						<div class="l">
							<span class="badge">
								<ic name="shield-check" color="#fff"></ic>
							</span>
							<div>
								<div class="ti">Quản trị trận đấu</div>
								<div class="su">Nhập thông tin trận hoặc import danh sách</div>
							</div>
						</div>
						<button class="x" ng-click="app.adminOpen=false">
							<ic name="x"></ic>
						</button>
					</div>
					<div class="modal-tabs">
						<button ng-class="{on: app.adminTab==='manual'}" ng-click="app.adminTab='manual'">
							<ic name="pencil"></ic>
							Nhập 1 trận
						</button>
						<button ng-class="{on: app.adminTab==='result'}" ng-click="app.adminTab='result'">
								<ic name="flag"></ic>
								Kết quả
							</button>
							<button ng-class="{on: app.adminTab==='import'}" ng-click="app.adminTab='import'">
							<ic name="file-spreadsheet"></ic>
							Import CSV
						</button>
						<button ng-class="{on: app.adminTab==='points'}" ng-click="app.adminTab='points'; app.loadCfgForm()">
							<ic name="trophy"></ic>
							Điểm theo vòng
						</button>
					</div>
					<div class="modal-body">
						<!--=============== [Worldcup - Tab Điểm theo vòng] - START ===============-->
							<div ng-if="app.adminTab==='points'" class="col" style="gap:14px">
								<div class="imp-head"><span style="color:var(--ink-3)">Điểm cộng khi đoán <b>ĐÚNG</b> mỗi trận, theo vòng. Lưu xong áp dụng cho các lần chốt kết quả sau.</span></div>
								<div class="form-grid">
									<label class="field" ng-repeat="s in app.stages">
										<div class="lab">{{s.l}}</div>
										<input class="inp" type="number" min="0" ng-model="app.cfgForm[s.k]" />
									</label>
								</div>
								<div class="form-actions" style="margin-top:6px">
									<button class="btn ghost" ng-click="app.loadCfgForm()">
										<ic name="rotate-ccw"></ic>
										Khôi phục
									</button>
									<button class="btn primary" ng-click="app.saveConfig()">
										<ic name="check"></ic>
										Lưu điểm theo vòng
									</button>
								</div>
							</div>
							<!--=============== [Worldcup - Tab Điểm theo vòng] - END ===============-->
							<!-- manual -->
						<div ng-if="app.adminTab==='manual'">
							<div class="preview-strip">
								<div class="t" ng-if="app.form.a">
									<flag code="{{app.team(app.form.a).code}}" size="36"></flag>
									<b>{{app.team(app.form.a).name}}</b>
								</div>
								<span class="ph" ng-if="!app.form.a">Đội nhà</span>
								<span class="vs">VS</span>
								<div class="t" ng-if="app.form.b">
									<b>{{app.team(app.form.b).name}}</b>
									<flag code="{{app.team(app.form.b).code}}" size="36"></flag>
								</div>
								<span class="ph" ng-if="!app.form.b">Đội khách</span>
							</div>
							<div class="form-grid">
								<label class="field">
									<div class="lab">Đội nhà</div>
									<select class="sel" ng-model="app.form.a" ng-options="t.id as t.name for t in app.teamsExcept(app.form.b)">
										<option value="">Chọn đội…</option>
									</select>
								</label>
								<label class="field">
									<div class="lab">Đội khách</div>
									<select class="sel" ng-model="app.form.b" ng-options="t.id as t.name for t in app.teamsExcept(app.form.a)">
										<option value="">Chọn đội…</option>
									</select>
								</label>
								<label class="field">
									<div class="lab">Vòng đấu (tính điểm)</div>
										<select class="sel" ng-model="app.form.stage" ng-options="s.k as s.l for s in app.stages"></select>
									</label>
									<label class="field">
										<div class="lab">Ngày thi đấu</div>
									<input class="inp" type="date" ng-model="app.form.date" />
								</label>
								<label class="field">
									<div class="lab">Giờ bóng lăn</div>
									<input class="inp" type="time" ng-model="app.form.time" />
								</label>
								<label class="field">
										<div class="lab">Mở bình chọn (phút trước)</div>
										<input class="inp" type="number" min="1" ng-model="app.form.openMin" />
									</label>
									<label class="field">
										<div class="lab">Đóng bình chọn (phút trước)</div>
										<input class="inp" type="number" min="0" ng-model="app.form.lockMin" />
									</label>
							</div>
							<div class="err" ng-if="app.form.a && app.form.b && app.form.a===app.form.b" style="margin-top:14px">
								<ic name="alert-circle" color="var(--danger)"></ic>
								Hai đội không được trùng nhau.
							</div>
							<div class="form-actions" style="margin-top:16px">
								<button class="btn ghost" ng-click="app.resetForm()">
									<ic name="rotate-ccw"></ic>
									Xóa form
								</button>
								<button class="btn primary" ng-disabled="!app.formValid()" ng-click="app.addMatch()">
									<ic name="plus"></ic>
									{{app.form.id ? 'Lưu thay đổi' : 'Thêm trận đấu'}}
								</button>
							</div>
							<div class="added" ng-if="app.added.length">
								<div class="eyebrow" style="margin-bottom:10px">Đã thêm trong phiên này ({{app.added.length}})</div>
								<div class="col" style="gap:7px">
									<div class="item" ng-repeat="m in app.added">
										<flag code="{{m.home.code}}" size="22"></flag>
										<b>{{m.home.short}}</b>
										<span style="color:var(--ink-4)">vs</span><b>{{m.away.short}}</b>
										<flag code="{{m.away.code}}" size="22"></flag>
										<span class="ko">{{m.date}} · {{m.time}}</span>
									</div>
								</div>
							</div>
						</div>
						<!-- import -->
						<div ng-if="app.adminTab==='result'" class="col" style="gap:10px">
								<div class="imp-head">
									<span style="color:var(--ink-3)">Nhập tỉ số để chốt kết quả &amp; cộng điểm cho người chơi.</span>
								</div>
								<div class="imp-table">
									<div class="imp-row head" style="grid-template-columns:1fr 52px 52px 120px"><span>Trận</span><span>Nhà</span><span>Khách</span><span></span></div>
									<div class="imp-row" style="grid-template-columns:1fr 52px 52px 120px" ng-repeat="m in app.adminMatches()">
										<span><b>{{m.home.short}}</b> vs <b>{{m.away.short}}</b> <small style="color:var(--ink-4)">· {{m.slot|kickoff}} {{m.slot|dateVi}}</small><span ng-if="app.isKnockout(m)" class="pill ghost" style="margin-left:6px">loại trực tiếp</span><span ng-if="m.status===1" class="pill ok" style="margin-left:6px">KQ {{m.score[0]}}–{{m.score[1]}}<span ng-if="m.pen"> · pen {{m.pen[0]}}-{{m.pen[1]}}</span></span><span class="pen-line" ng-if="app.needPen(m)"><ic name="alert-circle" color="var(--warning)"></ic> Hòa → luân lưu: <input class="inp pen" type="number" min="0" ng-model="app.resultForm[m.id].ph" /><span>–</span><input class="inp pen" type="number" min="0" ng-model="app.resultForm[m.id].pa" /></span></span>
										<input class="inp" type="number" min="0" ng-model="app.resultForm[m.id].h" />
										<input class="inp" type="number" min="0" ng-model="app.resultForm[m.id].a" />
										<span class="end" style="display:flex;gap:4px;justify-content:flex-end"><button class="btn sm" ng-click="app.editMatch(m)" title="Sửa giờ/đội">Sửa</button><button class="btn sm primary" ng-click="app.saveResult(m)">Chốt</button></span>
									</div>
								</div>
							</div>
							<div ng-if="app.adminTab==='import'" class="col" style="gap:16px">
							<div class="imp-head" style="justify-content:space-between">
								<span style="color:var(--ink-3)">File CSV cần các cột: <code>Đội nhà · Đội khách · Ngày(dd/mm/yyyy) · Giờ(HH:MM) · Vòng</code></span>
								<button class="btn sm" ng-click="app.downloadSample()">
									<ic name="download"></ic>
									Tải file mẫu .csv
								</button>
							</div>
							<div class="dropzone" ng-if="!app.imp.imported" ng-click="app.triggerCsv()" style="cursor:pointer">
								<ic name="file-spreadsheet" color="var(--brand-700)"></ic>
								<div class="t">Bấm để chọn file CSV lịch thi đấu</div>
								<div class="s">Cột: Đội nhà, Đội khách, Ngày (dd/mm/yyyy), Giờ (HH:MM), Vòng</div>
								<input type="file" id="wcCsvInput" accept=".csv,text/csv" wc-file="app.onCsvFile($file)" style="display:none" />
							</div>
							<div class="col" style="gap:12px" ng-if="app.imp.imported">
								<div class="imp-head">
									<span class="pill ok">
										<ic name="check-circle"></ic>
										{{app.importOkCount()}} hợp lệ
									</span>
									<span class="pill bad">
										<ic name="alert-triangle"></ic>
										{{app.importRows.length - app.importOkCount()}} lỗi
									</span>
									<span style="color:var(--ink-3)">{{app.imp.fileName}}</span>
									<button class="swap" ng-click="app.resetImport()">Chọn file khác</button>
								</div>
								<div class="imp-table">
									<div class="imp-row head"><span>Đội nhà</span><span>Đội khách</span><span>Ngày</span><span>Giờ</span><span>Vòng</span><span></span></div>
									<div class="imp-row" ng-class="{bad: !r.ok}" ng-repeat="r in app.importRows">
										<span style="font-weight:600">{{r.a}}</span><span style="font-weight:600">{{r.b}}</span>
										<span class="mono">{{r.date}}</span>
										<span class="mono" ng-class="{miss: !r.time}">{{r.time || 'thiếu'}}</span>
										<span style="color:var(--ink-2)">{{r.round}}</span>
										<span class="end">
											<ic ng-if="r.ok" name="check" color="var(--success)"></ic>
											<ic ng-if="!r.ok" name="alert-circle" color="var(--danger)"></ic>
										</span>
									</div>
								</div>
								<div class="form-actions" style="margin-top:4px">
									<button class="btn ghost" ng-click="app.resetImport()">
										<ic name="rotate-ccw"></ic>
										Chọn file khác
									</button>
									<button class="btn primary" ng-disabled="!app.importOkCount()" ng-click="app.confirmImport()">
										<ic name="check"></ic>
										Nhập {{app.importOkCount()}} trận hợp lệ
									</button>
								</div>
								<div class="import-hint">
									<ic name="info" color="var(--ink-4)"></ic>
									Các dòng lỗi (sai tên đội, thiếu ngày/giờ) sẽ tự bỏ qua khi nhập.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="toast" ng-if="app.toast">
				<ic name="check-circle" color="#fff"></ic>
				{{app.toast}}
			</div>			
			{/literal}
		</div>
	</div>
</div>