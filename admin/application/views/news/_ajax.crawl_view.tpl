<style>.cm-cover input:checked + img{ border-color:#a4161a; box-shadow:0 0 0 2px rgba(164,22,26,.22) }</style>
<div class="ui-card__header" style="display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #eee">
	<h2 class="ui-heading" style="margin:0">Duyệt &amp; đăng tin</h2>
	<button type="button" class="ui-button ui-button--small" onclick="crawlClose()">&times; Đóng</button>
</div>
<div class="ui-card__section" style="padding:18px">
	<p class="type--subdued" style="margin-bottom:12px">
		Nguồn <strong>{$o.source_code|escape:'html'}</strong> ·
		<a href="{$o.source_url|escape:'html'}" target="_blank" rel="noopener">link gốc &rarr;</a>
		· bấm <strong>Duyệt &amp; đăng</strong> = đăng thẳng lên website ({$o.images_arr|@count} ảnh)
	</p>

	<div class="mb-2"><label>Tiêu đề (sửa lại)</label>
		<input class="form-control" id="cm_title" value="{$o.title|escape:'html'}"></div>
	<div class="mb-2"><label>Tóm tắt</label>
		<textarea class="form-control" id="cm_summary" rows="2">{$o.summary|escape:'html'}</textarea></div>

	<div class="row mb-2">
		<div class="col-md-6"><label>Website (đăng lên)</label>
			<select class="form-control" id="cm_domain" onchange="$Core.news.loadCategory(this,event)" cat_id="0" toId="cm_cat">
				{foreach from=$domains item=d}<option value="{$d.cat_id}">{$d.domain|escape:'html'}</option>{/foreach}
			</select>
		</div>
		<div class="col-md-6"><label>Danh mục <span style="color:#a4161a">*</span></label>
			<select class="form-control" id="cm_cat">
				<option value="0">— Chọn danh mục —</option>
				{$clsProperty->getListOption("_NEWS_CATEGORY", $src_cat, $def_domain)}
			</select>
		</div>
	</div>

	<div class="mb-2"><label>Nội dung — <strong>viết lại</strong> (không copy nguyên văn)</label>
		<textarea class="form-control isoTextArea" id="cm_content" rows="12">{$o.content|escape:'html'}</textarea></div>

	{if !empty($o.images_arr)}
	<div class="mb-2"><label>Ảnh đại diện — bấm chọn 1 (tải về local khi đăng)</label>
		<div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px">
		{foreach from=$o.images_arr item=img name=im}{if $smarty.foreach.im.index lt 8}
			<label class="cm-cover" style="cursor:pointer;margin:0" title="Chọn làm ảnh đại diện">
				<input type="radio" name="cm_cover" value="{$img|escape:'html'}"{if $smarty.foreach.im.first} checked{/if} style="display:none">
				<img src="{$img|escape:'html'}" style="height:72px;border-radius:6px;border:2px solid #e4e7ec;display:block">
			</label>
		{/if}{/foreach}
		</div>
	</div>
	{/if}

	<div style="background:#fff8e1;border:1px solid #ffe7a3;border-radius:6px;padding:10px;margin:12px 0">
		<label style="font-weight:600;color:#946a00;margin:0">
			<input type="checkbox" id="cm_rewritten"> Tôi đã <strong>viết lại</strong> nội dung (không sao chép nguyên văn)
		</label>
	</div>

	<div style="display:flex;justify-content:flex-end;gap:8px">
		<button type="button" class="ui-button" onclick="crawlReject({$o.crawl_id}); crawlClose();">Bỏ tin</button>
		<button type="button" class="ui-button ui-button--primary" onclick="crawlApprove({$o.crawl_id})">Duyệt &amp; đăng</button>
	</div>
</div>
