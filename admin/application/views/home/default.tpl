<link rel="stylesheet" href="{$URL_CSS}/{$mod}.css?v={$upd_version}" type="text/css" media="all">
{if $message eq 'invalidlicense'}
<div class="errorbox"><strong><span class="title">{$core->get_Lang("Invalid License Module")}</span></strong><br></div>
{/if}
{if $message eq 'modulenotactive'}
<div class="errorbox"><strong><span class="title">{$core->get_Lang("Module is not activated!")}</span></strong><br></div>
{/if}
<div class="container-fluid">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">
					Xin chào {$clsUser->getFullName($_loged_id)}
				</h1>
			</div>
		</div>
	</div>
	<div class="wrap">
		{literal}
<style>
.ca-dash{--cad-surface:#fff;--cad-surface2:#f7f9fd;--cad-border:#e5e8f1;--cad-ink:#1a2138;--cad-ink2:#586079;--cad-ink3:#8a92ab;--cad-accent:#4356e0;--cad-ok:#12946a;--cad-sold:#3b76f0;--cad-excl:#d98211;--cad-hold:#e1508a;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif;color:var(--cad-ink);margin-bottom:22px}
.dark-layout .ca-dash{--cad-surface:#161c2e;--cad-surface2:#1b2237;--cad-border:#28304a;--cad-ink:#eaedf7;--cad-ink2:#a3abc6;--cad-ink3:#6c7594}
.ca-dash .cad-num{font-variant-numeric:tabular-nums}
.ca-dash .cad-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:13px;margin-bottom:14px}
.ca-dash .cad-card{position:relative;background:var(--cad-surface);border:1px solid var(--cad-border);border-radius:13px;padding:15px;box-shadow:0 1px 2px rgba(20,26,46,.04),0 10px 22px -14px rgba(20,26,46,.22);transition:.16s;overflow:hidden;display:block}
.ca-dash a.cad-card:hover{transform:translateY(-3px);box-shadow:0 2px 6px rgba(20,26,46,.08),0 18px 34px -16px rgba(20,26,46,.3);text-decoration:none}
.ca-dash .cad-chip{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center}
.ca-dash .cad-chip svg{width:21px;height:21px}
.ca-dash .cad-lab{font-size:12.5px;color:var(--cad-ink2);font-weight:600;margin-top:12px}
.ca-dash .cad-big{font-size:29px;font-weight:800;letter-spacing:-.03em;line-height:1.1;margin-top:2px;color:var(--cad-ink)}
.ca-dash .cad-subs{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px}
.ca-dash .cad-pill{font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;background:var(--cad-surface2);color:var(--cad-ink2);border:1px solid var(--cad-border);white-space:nowrap}
.ca-dash .cad-pill.ok{color:var(--cad-ok)}.ca-dash .cad-pill.ex{color:var(--cad-excl)}
.ca-dash .cad-src{position:absolute;top:13px;right:14px;font-family:ui-monospace,Menlo,Consolas,monospace;font-size:9.5px;color:var(--cad-ink3);opacity:.7}
.ca-dash .cad-panels{display:grid;grid-template-columns:1.05fr .95fr;gap:13px;margin-bottom:14px}
.ca-dash .cad-lists{display:grid;grid-template-columns:1fr 1fr;gap:13px}
.ca-dash .cad-panel{background:var(--cad-surface);border:1px solid var(--cad-border);border-radius:13px;padding:17px;box-shadow:0 1px 2px rgba(20,26,46,.04),0 10px 22px -14px rgba(20,26,46,.22)}
.ca-dash .cad-panel h3{margin:0 0 2px;font-size:15px;font-weight:700;color:var(--cad-ink)}
.ca-dash .cad-psub{color:var(--cad-ink3);font-size:12px;margin:0 0 15px}
.ca-dash .cad-donutwrap{display:flex;align-items:center;gap:20px;flex-wrap:wrap}
.ca-dash .cad-donut{position:relative;width:148px;height:148px;flex-shrink:0}
.ca-dash .cad-ctr{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
.ca-dash .cad-ctr b{font-size:25px;font-weight:800;letter-spacing:-.03em}
.ca-dash .cad-ctr span{font-size:11px;color:var(--cad-ink3)}
.ca-dash .cad-legend{flex:1;min-width:150px;display:flex;flex-direction:column;gap:10px}
.ca-dash .cad-lg{display:flex;align-items:center;gap:10px;font-size:13px}
.ca-dash .cad-dot{width:11px;height:11px;border-radius:4px;flex-shrink:0}
.ca-dash .cad-lg .nm{color:var(--cad-ink2)}.ca-dash .cad-lg .v{margin-left:auto;font-weight:700}.ca-dash .cad-lg .pc{color:var(--cad-ink3);font-size:11px;width:34px;text-align:right}
.ca-dash .cad-bars{display:flex;flex-direction:column;gap:13px}
.ca-dash .cad-barrow{display:grid;grid-template-columns:100px 1fr 32px;align-items:center;gap:11px;font-size:13px}
.ca-dash .cad-barrow .nm{color:var(--cad-ink2);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ca-dash .cad-track{height:11px;border-radius:7px;background:var(--cad-surface2);overflow:hidden;border:1px solid var(--cad-border)}
.ca-dash .cad-fill{height:100%;border-radius:7px;background:linear-gradient(90deg,var(--cad-accent),#8a5cf6)}
.ca-dash .cad-barrow .v{text-align:right;font-weight:700}
.ca-dash .cad-row{display:flex;align-items:center;gap:11px;padding:10px 0;border-top:1px solid var(--cad-border)}
.ca-dash .cad-row:first-of-type{border-top:0}
.ca-dash .cad-tt{flex:1;min-width:0}
.ca-dash .cad-tt b{font-size:13px;font-weight:600;display:block;overflow:hidden;text-overflow:ellipsis;color:var(--cad-ink)}
.ca-dash .cad-tt small{color:var(--cad-ink3);font-size:11px}
.ca-dash .cad-badge{font-size:11px;font-weight:700;padding:3px 9px;border-radius:7px;white-space:nowrap}
.ca-dash .cad-badge.on{background:rgba(18,148,106,.14);color:var(--cad-ok)}
.ca-dash .cad-badge.dr{background:var(--cad-surface2);color:var(--cad-ink3);border:1px solid var(--cad-border)}
@media(max-width:1200px){.ca-dash .cad-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:900px){.ca-dash .cad-panels,.ca-dash .cad-lists{grid-template-columns:1fr}}
@media(max-width:600px){.ca-dash .cad-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:400px){.ca-dash .cad-grid{grid-template-columns:1fr}}
</style>
{/literal}
<div class="ca-dash">
	<div class="cad-grid">
		<a class="cad-card" href="{$DOMAIN_URL}?mod=profile">
			<div class="cad-src">default_profile</div>
			<div class="cad-chip" style="background:#ecebff;color:#4a3fd1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0M16 5.5a3 3 0 0 1 0 5.6M17 20a5.4 5.4 0 0 0-2-4.2"/></svg></div>
			<div class="cad-lab">Nhân viên</div><div class="cad-big cad-num">{$staff_total}</div>
			<div class="cad-subs"><span class="cad-pill ok">Đang làm {$staff_active}</span><span class="cad-pill">Nghỉ {$staff_off}</span></div>
		</a>
		<a class="cad-card" href="{$DOMAIN_URL}?mod=project">
			<div class="cad-src">default_project</div>
			<div class="cad-chip" style="background:#dbf4ec;color:#0c7c59"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 21V6l7-3v18M11 21V9l8 3v9M2 21h20"/></svg></div>
			<div class="cad-lab">Dự án</div><div class="cad-big cad-num">{$project_total}</div>
			<div class="cad-subs"><span class="cad-pill ok">Hiển thị {$project_show}</span></div>
		</a>
		<a class="cad-card" href="{$DOMAIN_URL}?mod=stock&stock_type=178">
			<div class="cad-src">stock · cao tầng</div>
			<div class="cad-chip" style="background:#e1ecff;color:#1f5ac2"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="2" width="12" height="20" rx="1.5"/><path d="M9.5 6h1M13.5 6h1M9.5 10h1M13.5 10h1M9.5 14h1M13.5 14h1"/></svg></div>
			<div class="cad-lab">Quỹ căn cao tầng</div><div class="cad-big cad-num">{$total_highlevel}</div>
			<div class="cad-subs"><span class="cad-pill">Đã bán {$total_highlevel_sold}</span><span class="cad-pill ex">Độc quyền {$total_highlevel_dq}</span></div>
		</a>
		<a class="cad-card" href="{$DOMAIN_URL}?mod=stock&stock_type=177">
			<div class="cad-src">stock · thấp tầng</div>
			<div class="cad-chip" style="background:#fce8dd;color:#bf551e"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10l9-6 9 6v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg></div>
			<div class="cad-lab">Quỹ căn thấp tầng</div><div class="cad-big cad-num">{$total_lowfloor}</div>
			<div class="cad-subs"><span class="cad-pill">Đã bán {$total_lowfloor_sold}</span><span class="cad-pill ex">Độc quyền {$total_lowfloor_dq}</span></div>
		</a>
		<a class="cad-card" href="{$DOMAIN_URL}?mod=news">
			<div class="cad-src">default_news</div>
			<div class="cad-chip" style="background:#fbeecf;color:#9e6c08"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h6M7 12h10M7 16h10"/></svg></div>
			<div class="cad-lab">Bản tin</div><div class="cad-big cad-num">{$news_total}</div>
			<div class="cad-subs"><span class="cad-pill ok">Xuất bản {$news_online}</span><span class="cad-pill">Nháp {$news_draft}</span></div>
		</a>
		<a class="cad-card" href="{$DOMAIN_URL}?mod=training">
			<div class="cad-src">default_training</div>
			<div class="cad-chip" style="background:#fbe4ee;color:#a63a63"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l9-4 9 4-9 4z"/><path d="M7 10v5c0 1.4 2.7 2.5 5 2.5s5-1.1 5-2.5v-5"/></svg></div>
			<div class="cad-lab">Trung tâm đào tạo</div><div class="cad-big cad-num">{$training_total}</div>
			<div class="cad-subs"><span class="cad-pill ok">Đang mở {$training_online}</span></div>
		</a>
	</div>

	<div class="cad-panels">
		<div class="cad-panel">
			<h3>Quỹ căn theo trạng thái</h3><p class="cad-psub">Tổng hợp cao + thấp tầng</p>
			<div class="cad-donutwrap">
				<div class="cad-donut">
					<svg viewBox="0 0 120 120" width="148" height="148" style="transform:rotate(-90deg)">
						<circle cx="60" cy="60" r="54" fill="none" stroke="var(--cad-surface2)" stroke-width="12"/>
						{foreach from=$donut item=d}<circle cx="60" cy="60" r="54" fill="none" stroke="{$d.color}" stroke-width="12" stroke-dasharray="{$d.dash}" stroke-dashoffset="{$d.offset}"/>{/foreach}
					</svg>
					<div class="cad-ctr"><b class="cad-num">{$units_total}</b><span>tổng căn</span></div>
				</div>
				<div class="cad-legend">
					{foreach from=$donut item=d}<div class="cad-lg"><span class="cad-dot" style="background:{$d.color}"></span><span class="nm">{$d.nm}</span><span class="v cad-num">{$d.v}</span><span class="pc cad-num">{$d.pc}%</span></div>{/foreach}
				</div>
			</div>
		</div>
		<div class="cad-panel">
			<h3>Nhân viên theo phòng ban</h3><p class="cad-psub">Đang làm việc · top 6 phòng</p>
			<div class="cad-bars">
				{foreach from=$dept_stats item=d}
				<div class="cad-barrow"><span class="nm" title="{$d.name}">{$d.name}</span><span class="cad-track"><span class="cad-fill" style="width:{$d.pct}%"></span></span><span class="v cad-num">{$d.count}</span></div>
				{foreachelse}
				<p class="cad-psub" style="margin:0">Chưa có dữ liệu phòng ban.</p>
				{/foreach}
			</div>
		</div>
	</div>

	<div class="cad-lists">
		<div class="cad-panel">
			<h3>Bản tin mới nhất</h3><p class="cad-psub">default_news · mới nhất theo ngày đăng</p>
			{foreach from=$news_recent item=n}
			<a class="cad-row" href="{$DOMAIN_URL}?mod=news" style="text-decoration:none">
				<div class="cad-chip" style="width:34px;height:34px;background:#fbeecf;color:#9e6c08"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 9h10M7 13h7"/></svg></div>
				<div class="cad-tt"><b>{$n.title}</b><small>{$n.reg_date|date_format:"%d/%m/%Y"}</small></div>
				{if $n.is_online}<span class="cad-badge on">Xuất bản</span>{else}<span class="cad-badge dr">Nháp</span>{/if}
			</a>
			{foreachelse}<p class="cad-psub" style="margin:0">Chưa có bản tin.</p>{/foreach}
		</div>
		<div class="cad-panel">
			<h3>Trung tâm đào tạo</h3><p class="cad-psub">default_training · khoá gần đây</p>
			{foreach from=$training_recent item=t}
			<a class="cad-row" href="{$DOMAIN_URL}?mod=training" style="text-decoration:none">
				<div class="cad-chip" style="width:34px;height:34px;background:#fbe4ee;color:#a63a63"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l9-4 9 4-9 4z"/><path d="M7 10v5c0 1.4 2.7 2.5 5 2.5s5-1.1 5-2.5v-5"/></svg></div>
				<div class="cad-tt"><b>{$t.title}</b><small>{$t.reg_date|date_format:"%d/%m/%Y"}</small></div>
				{if $t.is_online}<span class="cad-badge on">Đang mở</span>{else}<span class="cad-badge dr">Nháp</span>{/if}
			</a>
			{foreachelse}<p class="cad-psub" style="margin:0">Chưa có khoá đào tạo.</p>{/foreach}
		</div>
	</div>
</div>
<div class="form-row colorbox-group-widget" style="display:none">
			
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-primary">
						<a href="{$DOMAIN_URL}?mod=stock&stock_type=178" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$total_highlevel}
									<span class="pull-right">{$core->makeIcon('building-o')}</span></h3>
								<p class="info-text font-12">Cao tầng</p>
								<div class="d-flex justify-content-end gap-2">
									<p class="info-ot font-12">Đã bán<span class="label label-rounded font-12">{$total_highlevel_sold}</span></p>
									<p class="info-ot font-12">Độc quyền<span class="label label-rounded font-12">{$total_highlevel_dq}</span></p>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-danger">
						<a href="{$DOMAIN_URL}?mod=stock&stock_type=177" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$total_lowfloor} 
									<span class="pull-right">{$core->makeIcon('home')}</span></h3>
								<p class="info-text font-12">Thấp tầng</p>
								<div class="d-flex justify-content-end gap-2">
									<p class="info-ot font-12">Đã bán<span class="label label-rounded font-12">{$total_lowfloor_sold}</span></p>
									<p class="info-ot font-12">Độc quyền<span class="label label-rounded font-12">{$total_lowfloor_dq}</span></p>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-warning">
						<a href="{$DOMAIN_URL}?mod=sop" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$clsSop->countItem()} 
									<span class="pull-right">{$core->makeIcon('newspaper-o')}</span></h3>
								<p class="info-text font-12">Chuyển nhượng</p>
								<p class="info-ot font-12">{$core->get_Lang('Total Pending')}<span class="label label-rounded font-12">{$clsSop->countItem('is_online=0')}</span></p>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-success">
						<a href="{$DOMAIN_URL}?mod=leasing" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$clsLeasing->countItem()} 
									<span class="pull-right">{$core->makeIcon('newspaper-o')}</span></h3>
								<p class="info-text font-12">Cho thuê</p>
								<p class="info-ot font-12">{$core->get_Lang('Total Pending')}<span class="label label-rounded font-12">{$clsLeasing->countItem('is_online=0')}</span></p>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-primary">
						<a href="{$DOMAIN_URL}" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$clsInterior->countItem()} 
									<span class="pull-right">{$core->makeIcon('newspaper-o')}</span></h3>
								<p class="info-text font-12">Thiết kế</p>
								<p class="info-ot font-12">Yêu cầu phê duyệt<span class="label label-rounded font-12">{$clsInterior->countItem("is_online=0")}</span></p>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-success">
						<a href="{$DOMAIN_URL}?mod=service" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$clsService->countItem()} 
									<span class="pull-right">{$core->makeIcon('newspaper-o')}</span></h3>
								<p class="info-text font-12">Dịch vụ tiện ích</p>
								<p class="info-ot font-12">Yêu cầu phê duyệt<span class="label label-rounded font-12">{$clsService->countItem('is_online=0')}</span></p>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="ca-dash">
	<div class="cad-lists">
		<div class="cad-panel">
			<h3>Nhân viên mới</h3><p class="cad-psub">default_profile · thêm gần đây</p>
			{foreach from=$staff_recent item=p}
			<a class="cad-row" href="{$DOMAIN_URL}?mod=profile" style="text-decoration:none">
				<div class="cad-chip" style="width:34px;height:34px;border-radius:50%;background:#ecebff;color:#4a3fd1"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3.6"/><path d="M5 20a7 7 0 0 1 14 0"/></svg></div>
				<div class="cad-tt"><b>{$p.full_name}</b><small>{$clsProperty->getTitle($p.department_id)}</small></div>
			</a>
			{foreachelse}<p class="cad-psub" style="margin:0">Chưa có nhân viên.</p>{/foreach}
		</div>
		<div class="cad-panel">
			<h3>Danh sách dự án</h3><p class="cad-psub">default_project</p>
			{foreach from=$project_list item=pj}
			<a class="cad-row" href="{$DOMAIN_URL}?mod=project" style="text-decoration:none">
				<div class="cad-chip" style="width:34px;height:34px;background:#dbf4ec;color:#0c7c59"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 21V6l7-3v18M11 21V9l8 3v9M2 21h20"/></svg></div>
				<div class="cad-tt"><b>{$pj.title}</b></div>
				{if $pj.is_menu}<span class="cad-badge on">Đang bán</span>{else}<span class="cad-badge dr">Ẩn</span>{/if}
			</a>
			{foreachelse}<p class="cad-psub" style="margin:0">Chưa có dự án.</p>{/foreach}
		</div>
	</div>
</div>