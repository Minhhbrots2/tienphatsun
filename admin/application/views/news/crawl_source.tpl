<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Nguồn báo crawl</h1>
				<p class="type--subdued">Thêm/sửa nguồn — tương lai có báo mới chỉ cần dán RSS vào đây. Selector để trống = engine tự bóc.</p>
			</div>
		</div>
		<div class="action-bar">
			<a href="{$PCMS_URL}/index.php?mod=news&act=crawl_list" class="ui-button ui-title-bar__action">Tin đã crawl &rarr;</a>
		</div>
	</div>
</div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">

				<div class="ui-card">
					<div class="ui-card__section">
						<div class="table-responsive">
						<table class="table table-striped" width="100%">
							<thead><tr>
								<th>Tên</th><th>RSS feed</th><th class="text-center">Giới hạn</th><th>Sức khỏe</th><th class="text-center">Bật</th><th></th>
							</tr></thead>
							<tbody>
								{if !empty($sources)}
									{foreach from=$sources item=s}
									<tr>
										<td><strong>{$s.name|escape:'html'}</strong><br><span class="type--subdued">{$s.code|escape:'html'}</span></td>
										<td style="word-break:break-all"><small>{$s.rss_url|escape:'html'}</small></td>
										<td class="text-center">{$s.crawl_limit}</td>
										<td>
											{if $s.last_status eq 'error'}<span class="badge" style="background:#ffe0db;color:#d83a2b">Lỗi</span> <small>{$s.last_error|truncate:40|escape:'html'}</small>
											{elseif $s.last_status eq 'ok'}<span class="badge" style="background:#e8fadf;color:#3a7d22">OK</span> <small>{if $s.last_run}{$clsISO->convertTimeToText($s.last_run,true)}{/if}</small>
											{else}<span class="type--subdued">chưa chạy</span>{/if}
										</td>
										<td class="text-center">{if $s.is_active}<span style="color:#3a7d22">●</span>{else}<span class="type--subdued">○</span>{/if}</td>
										<td class="text-nowrap">
											<a href="{$PCMS_URL}/index.php?mod=news&act=crawl_source&source_id={$s.source_id}" class="ui-button ui-button--small">Sửa</a>
											<a href="{$PCMS_URL}/index.php?mod=news&act=crawl_source_delete&source_id={$s.source_id}" class="ui-button ui-button--small" onclick="return confirm('Xoá nguồn này?')">Xoá</a>
										</td>
									</tr>
									{/foreach}
								{else}
									<tr><td colspan="6" class="text-center type--subdued" style="padding:18px">Chưa có nguồn nào</td></tr>
								{/if}
							</tbody>
						</table>
						</div>
					</div>
				</div>

				<div class="ui-card">
					<div class="ui-card__header"><h2 class="ui-heading">{if $edit}Sửa nguồn{else}Thêm nguồn mới{/if}</h2></div>
					<div class="ui-card__section">
						<form method="post" action="{$PCMS_URL}/index.php?mod=news&act=crawl_source_save" id="crawlSrcForm">
							<input type="hidden" name="source_id" value="{if $edit}{$edit.source_id}{/if}">
							<div class="row">
								<div class="col-md-6 mb-2"><label>Tên nguồn</label><input class="form-control" name="name" value="{if $edit}{$edit.name|escape:'html'}{/if}" required></div>
								<div class="col-md-3 mb-2"><label>Mã (code)</label><input class="form-control" name="code" value="{if $edit}{$edit.code|escape:'html'}{/if}" required></div>
								<div class="col-md-3 mb-2"><label>Giới hạn bài/lần</label><input class="form-control" name="crawl_limit" type="number" value="{if $edit}{$edit.crawl_limit}{else}20{/if}"></div>
							</div>
							<div class="row">
								<div class="col-md-3 mb-2"><label>Kiểu nguồn</label>
									<select class="form-control" name="is_rss" id="f_isrss" onchange="crawlToggleType()">
										<option value="1"{if !$edit || $edit.is_rss} selected{/if}>RSS feed</option>
										<option value="0"{if $edit && !$edit.is_rss} selected{/if}>Trang danh sách (HTML)</option>
									</select>
								</div>
								<div class="col-md-9 mb-2" id="f_rss_wrap"><label>RSS feed URL</label><input class="form-control" id="f_rss" name="rss_url" value="{if $edit}{$edit.rss_url|escape:'html'}{/if}" placeholder="https://...rss"></div>
								<div class="col-md-9 mb-2" id="f_list_wrap"><label>URL trang danh sách <small class="type--subdued">(báo không có RSS — vd https://batdongsan.com.vn/tin-tuc)</small></label><input class="form-control" id="f_list" name="list_url" value="{if $edit}{$edit.list_url|escape:'html'}{/if}" placeholder="https://.../tin-tuc"></div>
							</div>
							<div class="row">
								<div class="col-md-4 mb-2"><label>Chuyên mục (cat_id)</label><input class="form-control" name="cat_id" type="number" value="{if $edit}{$edit.cat_id}{else}0{/if}"></div>
								<div class="col-md-4 mb-2"><label>Giữ tin (ngày)</label><input class="form-control" name="retention_days" type="number" value="{if $edit}{$edit.retention_days}{else}30{/if}"></div>
							</div>
							<details class="mb-2" style="border:1px dashed #ccc;border-radius:6px;padding:10px">
								<summary style="cursor:pointer;font-weight:600">Selector nâng cao (để trống = tự bóc JSON-LD/OG/readability)</summary>
								<div class="row" style="margin-top:10px">
									<div class="col-md-6 mb-2"><label>Nội dung (CSS)</label><input class="form-control" id="f_sel" name="content_selector" value="{if $edit}{$edit.content_selector|escape:'html'}{/if}" placeholder=".fck_detail"></div>
									<div class="col-md-6 mb-2"><label>Loại bỏ rác (CSS, phẩy)</label><input class="form-control" name="remove_selectors" value="{if $edit}{$edit.remove_selectors|escape:'html'}{/if}" placeholder=".box-tinlienquan"></div>
								</div>
							</details>
							<div class="mb-2">
								<label><input type="checkbox" name="is_active" value="1" {if !$edit || $edit.is_active}checked{/if}> Đang bật</label>
							</div>
							<button type="button" class="ui-button" onclick="crawlTest()">⚡ Test ngay</button>
							<button type="submit" class="ui-button ui-button--primary">Lưu nguồn</button>
						</form>
						<div id="crawlTestResult" style="margin-top:16px"></div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
{literal}
<script>
// Escape text trước khi nhét vào innerHTML (title/summary là plain-text từ trang nguồn → chống XSS admin)
function nxEsc(s){ return String(s==null?'':s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
function crawlToggleType(){
	var t = document.getElementById('f_isrss').value;
	var rw = document.getElementById('f_rss_wrap'), lw = document.getElementById('f_list_wrap');
	if(rw) rw.style.display = (t=='0') ? 'none' : '';
	if(lw) lw.style.display = (t=='0') ? '' : 'none';
}
function crawlTest(){
	var t = document.getElementById('f_isrss').value;
	var rss = document.getElementById('f_rss').value;
	var list = document.getElementById('f_list').value;
	var sel = document.getElementById('f_sel') ? document.getElementById('f_sel').value : '';
	var box = document.getElementById('crawlTestResult');
	if((t=='0' && !list) || (t!='0' && !rss)){ box.innerHTML = '<div class="ui-card"><div class="ui-card__section" style="color:#d83a2b">Nhập URL trước</div></div>'; return; }
	box.innerHTML = '<div class="ui-card"><div class="ui-card__section">Đang test…</div></div>';
	$.post(path_ajax_script+'/index.php?mod=news&act=crawl_test', {is_rss:t, rss_url:rss, list_url:list, content_selector:sel}, function(r){
		if(!r || r.ok!=1){ box.innerHTML = '<div class="ui-card"><div class="ui-card__section" style="color:#d83a2b">✗ '+nxEsc((r&&r.error)||'lỗi')+'</div></div>'; return; }
		var tl = r.tier_log||{};
		var tiers = 'JSON-LD:'+(tl.jsonld?'✓':'–')+' · OG:'+(tl.og?'✓':'–')+' · tier:'+nxEsc(tl.tier||'?')+' · '+(parseInt(tl.words,10)||0)+' từ';
		// r.content đã được _sanitize_html ở server (script/onerror/iframe đã loại) → render HTML; title/summary/url plain-text → escape
		var imgs = (r.images||[]).slice(0,1).map(function(u){return '<img src="'+nxEsc(u)+'" style="max-width:200px;border-radius:6px">';}).join('');
		box.innerHTML = '<div class="ui-card"><div class="ui-card__section">'
			+ '<div style="color:#3a7d22;font-weight:600">✓ Bóc OK — '+tiers+'</div>'
			+ '<p style="font-weight:600;margin:8px 0">'+nxEsc(r.title||'')+'</p>'
			+ '<p class="type--subdued">'+nxEsc(r.summary||'')+'</p>'+imgs
			+ '<div style="max-height:200px;overflow:auto;border:1px solid #eee;border-radius:6px;padding:10px;margin-top:8px;font-size:13px">'+(r.content||'')+'</div>'
			+ '<small class="type--subdued">'+(r.images||[]).length+' ảnh · '+nxEsc(r.source_url||'')+'</small>'
			+ '</div></div>';
	}, 'json').fail(function(){ box.innerHTML = '<div class="ui-card"><div class="ui-card__section" style="color:#d83a2b">Lỗi kết nối</div></div>'; });
}
crawlToggleType();
</script>
{/literal}
