<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Tin đã crawl</h1>
				<p class="type--subdued">Duyệt → viết lại → đẩy sang tin nháp. Tổng <strong>{$total}</strong> tin.</p>
			</div>
		</div>
		<div class="action-bar">
			<a href="{$PCMS_URL}/index.php?mod=news&act=crawl_source" class="ui-button ui-title-bar__action">Nguồn báo</a>
		</div>
	</div>
</div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
					<div class="ui-card__section">
						<form method="get" action="index.php" class="form-inline mb-2">
							<input type="hidden" name="mod" value="news"><input type="hidden" name="act" value="crawl_list">
							<select name="f_source" class="form-control mr-1">
								<option value="0">Tất cả nguồn</option>
								{foreach from=$sources item=so}<option value="{$so.source_id}" {if $f.f_source eq $so.source_id}selected{/if}>{$so.name|escape:'html'}</option>{/foreach}
							</select>
							<select name="f_status" class="form-control mr-1">
								<option value="">Mọi trạng thái</option>
								{foreach from=['new'=>'Mới','blocked'=>'Bị chặn','pushed'=>'Đã đẩy','rejected'=>'Đã bỏ'] key=k item=v}<option value="{$k}" {if $f.f_status eq $k}selected{/if}>{$v}</option>{/foreach}
							</select>
							<input type="text" name="kw" value="{$f.kw|escape:'html'}" class="form-control mr-1" placeholder="Tìm tiêu đề…">
							<button class="ui-button">Lọc</button>
						</form>

						<div class="table-responsive">
						<table class="table table-striped" width="100%">
							<thead><tr><th>Tiêu đề</th><th>Nguồn</th><th>Ngày</th><th class="text-center">Ảnh</th><th class="text-center">Trạng thái</th><th></th></tr></thead>
							<tbody>
								{if !empty($rows)}
									{foreach from=$rows item=r}
									<tr>
										<td><strong>{$r.title|escape:'html'}</strong><br><small class="type--subdued">{$r.summary|truncate:90|escape:'html'}</small></td>
										<td><span class="badge" style="background:#e7e7ff;color:#5f61c4">{$r.source_code|escape:'html'}</span></td>
										<td><small>{if $r.pub_date}{$clsISO->convertTimeToText($r.pub_date,true)}{/if}</small></td>
										<td class="text-center">{$r.img_count}</td>
										<td class="text-center">
											{if $r.status eq 'new'}<span class="badge" style="background:#fff2d6;color:#9a6a00">Mới</span>
											{elseif $r.status eq 'pushed'}<span class="badge" style="background:#e8fadf;color:#3a7d22">Đã đẩy #{$r.news_id}</span>
											{elseif $r.status eq 'rejected'}<span class="badge" style="background:#eceef1;color:#777">Đã bỏ</span>
											{elseif $r.status eq 'blocked'}<span class="badge" style="background:#ffe0db;color:#d83a2b">Bị chặn</span>
											{else}{$r.status}{/if}
										</td>
										<td class="text-nowrap">
											{if $r.status eq 'new'}
												<button class="ui-button ui-button--small ui-button--primary" onclick="crawlView({$r.crawl_id})">Xem / Duyệt</button>
												<button class="ui-button ui-button--small" onclick="crawlReject({$r.crawl_id}, this)">Bỏ</button>
											{elseif $r.status eq 'pushed'}
												<a class="ui-button ui-button--small" href="{$PCMS_URL}/index.php?mod=news&act=edit&news_id={$r.news_id}">Mở tin nháp</a>
											{else}—{/if}
										</td>
									</tr>
									{/foreach}
								{else}
									<tr><td colspan="6" class="text-center type--subdued" style="padding:18px">Không có tin</td></tr>
								{/if}
							</tbody>
						</table>
						</div>

						{if $total_page gt 1}
						<div class="text-center" style="margin-top:12px">
							{section name=p start=1 loop=$total_page+1}
								<a class="ui-button ui-button--small{if $smarty.section.p.index eq $page} ui-button--primary{/if}" href="{$PCMS_URL}/index.php?mod=news&act=crawl_list&page={$smarty.section.p.index}{$qs}">{$smarty.section.p.index}</a>
							{/section}
						</div>
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="crawlModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;overflow:auto;padding:24px">
	<div style="max-width:820px;margin:0 auto;background:#fff;border-radius:8px" id="crawlModalBody"></div>
</div>
{literal}
<script>
function crawlEditorKill(){ // gỡ editor cũ (plugin admin isoTextArea KHÔNG tự dọn trước init → phải gỡ tay)
	if(typeof tinyMCE !== 'undefined'){ try { if(tinyMCE.get('cm_content')) tinyMCE.execCommand('mceRemoveControl', false, 'cm_content'); } catch(e){} }
}
function crawlView(id){
	var m=document.getElementById('crawlModal'), b=document.getElementById('crawlModalBody');
	crawlEditorKill();
	b.innerHTML='<div style="padding:24px">Đang tải…</div>'; m.style.display='block';
	$.post(path_ajax_script+'/index.php?mod=news&act=crawl_view',{crawl_id:id},function(r){
		b.innerHTML=r.html;
		// gắn editor lên textarea#cm_content vừa inject (đồng bộ ngay sau innerHTML); convert_urls tắt → giữ URL ảnh tuyệt đối cho crawImage
		if(typeof tinyMCE !== 'undefined' && $.fn.isoTextArea){ try { crawlEditorKill(); $('#cm_content').isoTextArea(); } catch(e){} }
	},'json');
}
function crawlClose(){ crawlEditorKill(); document.getElementById('crawlModal').style.display='none'; }
function crawlReject(id, el){
	if(!confirm('Bỏ tin này?')) return;
	$.post(path_ajax_script+'/index.php?mod=news&act=crawl_reject',{crawl_id:id},function(r){
		if(r&&r.msg=='_success'){ if(el){var tr=el.closest('tr'); if(tr) tr.style.opacity=.4;} }
	},'json');
}
function crawlApprove(id){
	if(!document.getElementById('cm_rewritten').checked){ alert('Phải tích "Đã viết lại" trước khi duyệt (tránh copy nguyên văn — vi phạm bản quyền).'); return; }
	var catEl = document.getElementById('cm_cat');
	if(catEl && (parseInt(catEl.value,10)||0) <= 0){ alert('Hãy chọn Danh mục trước khi đăng.'); catEl.focus(); return; }
	if(typeof tinyMCE !== 'undefined' && tinyMCE.triggerSave) tinyMCE.triggerSave(); // flush HTML iframe → textarea trước khi đọc .value
	var cover = document.querySelector('input[name="cm_cover"]:checked');
	$.post(path_ajax_script+'/index.php?mod=news&act=crawl_approve', {
		crawl_id:id, is_rewritten:1,
		title:document.getElementById('cm_title').value,
		summary:document.getElementById('cm_summary').value,
		content:document.getElementById('cm_content').value,
		domain_id:(document.getElementById('cm_domain')||{}).value||0,
		cat_id:(catEl||{}).value||0,
		cover: cover ? cover.value : ''
	}, function(r){
		if(r&&r.msg=='_success'){ alert('Đã đăng tin #'+r.news_id+' lên website.'+((r.cover==0)?' (Lưu ý: ảnh bìa chưa tải được — kiểm tra lại trong tin.)':'')); crawlClose(); location.reload(); }
		else if(r&&r.msg=='_need_rewrite'){ alert('Phải tích "Đã viết lại".'); }
		else if(r&&r.msg=='_need_cat'){ alert('Hãy chọn Danh mục trước khi đăng.'); }
		else { alert('Lỗi duyệt.'); }
	},'json');
}
document.getElementById('crawlModal').addEventListener('click',function(e){ if(e.target===this) crawlClose(); });
</script>
{/literal}
