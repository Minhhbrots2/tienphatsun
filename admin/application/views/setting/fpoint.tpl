<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Setting')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Cấu hình F-Point</h1>
			</div>
		</div>
	</div>
</header>
<form method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="ui-layout__sections"><div class="ui-layout__section">
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="ui-annotated-section__content">
						<div class="next-card">
							<div class="next-card__section">
								<table class="table table-bordered">
									<tr>
										<th></th>
										{foreach from=$list_departments item = _oDep}
										<th class="text-center">{$_oDep.title}</th>
										{/foreach}
									</tr></thead>
									{foreach from=$list_props item = _oProp}
									<tr class='{cycle values="odd,even"}'>
										<td class="bg-danger" width="15%">{$_oProp.title}</td>
										{foreach from=$list_departments item = _oDep}
										{assign var = _okey value = $_oDep.id|cat:"_"|cat:$_oProp.property_id}
										<td class="text-center">
											<input type="number" name="fpoint_configs[{$_okey}][score]" placeholder="Điểm" class="form-control mb-1 nunberonly" value="{$fpoint_configs.$_okey.score}" />
											<input type="text" name="fpoint_configs[{$_okey}][content]" placeholder="Nội dung" class="form-control" value="{$fpoint_configs.$_okey.content}" />
										</td>
										{/foreach}
									</tr>
									{/foreach}
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="row">
						<div class="col-md-3">
							<div class="ui-annotated-section__title">
								<h2 class="ui-heading">Điểm theo giá trị giao dịch</h2>
							</div>
							<div class="ui-annotated-section__description">Thang điểm thi đua định danh — quy đổi điểm theo GIÁ TRỊ giao dịch (đơn vị: tỷ VND).<br /><br />"Đến dưới" = 0 hoặc bỏ trống nghĩa là "trở lên".<br /><br />Bấm "+ Thêm dòng" để thêm bậc mới, bấm "Xóa" để bỏ bậc, sau đó bấm Lưu. Dòng bỏ trống cả 2 ô điểm cũng bị loại khi lưu.</div>
						</div>
						<div class="col-md-9">
							<div class="ui-annotated-section__content">
								<div class="next-card">
									<div class="next-card__section">
										<table class="table table-bordered">
											<thead><tr>
												<th class="text-center">Từ (tỷ)</th>
												<th class="text-center">Đến dưới (tỷ)</th>
												<th class="text-center">Điểm Độc Quyền</th>
												<th class="text-center">Điểm Quỹ Chéo</th>
												<th class="text-center">Ghi chú</th>
												<th class="text-center" width="60px"></th>
											</tr></thead>
											{foreach from=$fpoint_trans_tiers key=_ti item=_oTier}
											<tr class='{cycle values="odd,even"}'>
												<td width="15%"><input type="number" step="any" min="0" name="fpoint_configs[trans_value][{$_ti}][from]" placeholder="Từ" class="form-control" value="{$_oTier.from}" /></td>
												<td width="15%"><input type="number" step="any" min="0" name="fpoint_configs[trans_value][{$_ti}][to]" placeholder="Trở lên" class="form-control" value="{$_oTier.to}" /></td>
												<td width="18%"><input type="number" step="any" min="0" name="fpoint_configs[trans_value][{$_ti}][exclusive_score]" placeholder="Điểm" class="form-control" value="{$_oTier.exclusive_score}" /></td>
												<td width="18%"><input type="number" step="any" min="0" name="fpoint_configs[trans_value][{$_ti}][cross_score]" placeholder="Điểm" class="form-control" value="{$_oTier.cross_score}" /></td>
												<td><input type="text" name="fpoint_configs[trans_value][{$_ti}][note]" placeholder="Ghi chú" class="form-control" value="{$_oTier.note|escape}" /></td>
												<td class="text-center"><button type="button" class="btn btn-default btn-sm" onclick="return fpoint_remove_tier(this);">Xóa</button></td>
											</tr>
											{/foreach}
											<tr class="fpoint-tier-template" style="display:none">
												<td width="15%"><input type="number" step="any" min="0" name="fpoint_configs[trans_value][__IDX__][from]" placeholder="Từ" class="form-control" value="" disabled /></td>
												<td width="15%"><input type="number" step="any" min="0" name="fpoint_configs[trans_value][__IDX__][to]" placeholder="Trở lên" class="form-control" value="" disabled /></td>
												<td width="18%"><input type="number" step="any" min="0" name="fpoint_configs[trans_value][__IDX__][exclusive_score]" placeholder="Điểm" class="form-control" value="" disabled /></td>
												<td width="18%"><input type="number" step="any" min="0" name="fpoint_configs[trans_value][__IDX__][cross_score]" placeholder="Điểm" class="form-control" value="" disabled /></td>
												<td><input type="text" name="fpoint_configs[trans_value][__IDX__][note]" placeholder="Ghi chú" class="form-control" value="" disabled /></td>
												<td class="text-center"><button type="button" class="btn btn-default btn-sm" onclick="return fpoint_remove_tier(this);">Xóa</button></td>
											</tr>
											<tr>
												<td colspan="6"><button type="button" class="btn btn-default" onclick="return fpoint_add_tier(this);">+ Thêm dòng</button></td>
											</tr>
										</table>
										{literal}
										<script type="text/javascript">
										var fpoint_tier_seq = 0;
										function fpoint_add_tier(_this){
											var $tbl = $(_this).closest('table');
											var $tpl = $tbl.find('tr.fpoint-tier-template');
											var idx = 'n' + (new Date().getTime()) + '_' + (++fpoint_tier_seq);
											var $row = $('<tr></tr>').html($tpl.html().replace(/__IDX__/g, idx));
											$row.find('input').prop('disabled', false);
											$tpl.before($row);
											return false;
										}
										function fpoint_remove_tier(_this){
											$(_this).closest('tr').remove();
											return false;
										}
										</script>
										{/literal}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="row">
						<div class="col-md-3">
							<div class="ui-annotated-section__title">
								<h2 class="ui-heading">BO</h2>
							</div>
							<div class="ui-annotated-section__description">Cấu hình điểm số F-Point cho khối BO.</div>
						</div>
						<div class="col-md-9">
							<div class="ui-annotated-section__content">
								<div class="next-card">
									<div class="next-card__section">
										<div class="ui-form__section form-horizontal">
											{foreach name=i from=$list_BO_levels item = _oI}
											{assign var = _okey value = $_oI.property_id|cat:"_seniority"}
											{if $smarty.foreach.i.first}
											<div class="form-row">
												<label class="col-form-label text-right col-xs-12 col-md-4"></label>
												<label class="col-form-label col-xs-12 col-md-2">Điểm</label>
												<label class="col-form-label col-xs-12 col-md-6">Nội dung</label>
											</div>
											{/if}
											<div class="form-group form-row">
												<label class="col-form-label text-right col-xs-12 col-md-3">{$_oI.title}</label>
												<label class="col-form-label col-xs-12 col-md-1 text-center">=</label>
												<div class="col-xs-12 col-md-2">
													<input type="number" placeholder="Số điểm" name="fpoint_configs[{$_okey}][score]" class="form-control nunberonly" value="{if !empty($fpoint_configs[$_okey])}{$fpoint_configs[$_okey].score}{/if}" />
												</div>
												<div class="col-xs-12 col-md-6">
													<input type="text" placeholder="Nội dung" name="fpoint_configs[{$_okey}][content]" 
													class="form-control" value="{if !empty($fpoint_configs[$_okey])}{$fpoint_configs[$_okey].content}{/if}" />
												</div>
											</div>
											{/foreach}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			
			{foreach from=$list_points key = _OGrkey item = _OGoup}
			{assign var = list_actions value = $_OGoup.actions}
			<section class="ui-annotated-section-container">
				<div class="ui-annotated-section">
					<div class="row">
						<div class="col-md-3">
							<div class="ui-annotated-section__title">
								<h2 class="ui-heading">{$_OGoup.title}</h2>
							</div>
							<div class="ui-annotated-section__description">{$_OGoup.description}</div>
						</div>
						<div class="col-md-9">
							<div class="ui-annotated-section__content">
								<div class="next-card">
									<div class="next-card__section">
										<div class="ui-form__section form-horizontal">
											{foreach name=i from=$list_actions key = _okey item = _oText}
											{if $smarty.foreach.i.first}
											<div class="form-row">
												<label class="col-form-label text-right col-xs-12 col-md-4"></label>
												<label class="col-form-label col-xs-12 col-md-2">Điểm</label>
												<label class="col-form-label col-xs-12 col-md-6">Nội dung</label>
											</div>
											{/if}
											<div class="form-group form-row">
												<label class="col-form-label text-right col-xs-12 col-md-3">{$_oText}</label>
												<label class="col-form-label col-xs-12 col-md-1 text-center">+</label>
												<div class="col-xs-12 col-md-2">
													<input type="number" name="fpoint_configs[{$_okey}][score]" class="form-control nunberonly" value="{if !empty($fpoint_configs[$_okey])}{$fpoint_configs[$_okey].score}{/if}" />
												</div>
												<div class="col-xs-12 col-md-6">
													<input type="text" name="fpoint_configs[{$_okey}][content]" class="form-control" value="{if !empty($fpoint_configs[$_okey])}{$fpoint_configs[$_okey].content}{/if}" />
												</div>
											</div>
											{/foreach}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			{/foreach}
		</div></div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="Update" name="submit" type="hidden">
				<div class="ui-page-actions__button-group">{$saveBtn}</div>
			</div>
		</div>
	</div>
</form>