<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Trang tĩnh</h1>
				<p class="type--subdued">Quản lý nội dung các trang tĩnh (điều khoản, chính sách...) hiển thị ở cổng MyFuture.</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/index.php?mod={$mod}&act=edit" class="ui-button ui-button--primary ui-title-bar__action">Thêm trang</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
					<div class="ui-card__section has-bulk-actions pages">
						<form method="post">
							<div class="form-search form-inline">
								<div class="form-group">
									<input type="text" class="form-control" name="keyword" value="{$keyword|escape:'html'}" placeholder="Tìm theo tiêu đề..." />
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Tìm')}</button>
								<div class="form-group pull-right">
									<a href="{$PCMS_URL}/?mod={$mod}" class="btn text-white btn-warning{if $type_list ne 'Trash'} active{/if}">
										<i class="icon-folder-open icon-white"></i>
										<span>Tất cả ({$number_all})</span>
									</a>
									<a href="{$PCMS_URL}/?mod={$mod}&type_list=Trash" class="btn text-white btn-danger{if $type_list eq 'Trash'} active{/if}">
										<i class="icon-warning-sign icon-white"></i>
										<span>Thùng rác ({$number_trash})</span>
									</a>
								</div>
							</div>
							<div class="hastable">
								<table class="table table-vertical table-striped" cellspacing="0" cellpadding="0" width="100%">
									<thead><tr>
										<th class="text-left" width="80px">Ảnh</th>
										<th class="text-left">Tiêu đề</th>
										<th class="text-center" width="8%">Số mục</th>
										<th class="text-right" width="14%">Cập nhật lần cuối</th>
										<th class="text-center" width="10%">Trạng thái</th>
										<th class="text-center" width="40px">Action</th>
									</tr></thead>
									{if $allItem}
									{section name=i loop=$allItem}
									<tr class="{cycle values="row1,row2"}">
										<td class="text-center">
											<a class="aspect-ratio aspect-ratio--square aspect-ratio--square--50 aspect-ratio--interactive" href="{$PCMS_URL}/?mod={$mod}&act=edit&page_id={$core->encryptID($allItem[i].page_id)}">
												{if $allItem[i].image}<img class="aspect-ratio__content" src="{$allItem[i].image}" width="80px" />{else}<span class="text-muted">&mdash;</span>{/if}
											</a>
										</td>
										<td>
											<a href="{$PCMS_URL}/?mod={$mod}&act=edit&page_id={$core->encryptID($allItem[i].page_id)}"><strong>{$allItem[i].title|escape:'html'}</strong></a>
											{if $allItem[i].is_trash eq '1'}<span class="fr text-red">Trong thùng rác</span>{/if}
											{if $allItem[i]._subtitle}<div class="type--subdued">{$allItem[i]._subtitle|escape:'html'}</div>{/if}
										</td>
										<td class="text-center">{$allItem[i]._section_count}</td>
										<td class="text-right">{if $allItem[i]._last_updated gt 0}{$allItem[i]._last_updated|date_format:"%d/%m/%Y"}{else}&mdash;{/if}</td>
										<td class="text-center">
											<a href="{$PCMS_URL}/?mod={$mod}&act=toggle&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}&type_list={$type_list}" title="Đổi trạng thái hiển thị">
												{if $allItem[i].is_online eq '1'}<i class="fa fa-check-circle green"></i>{else}<i class="fa fa-minus-circle red"></i>{/if}
											</a>
										</td>
										<td class="text-center" style="white-space: nowrap;">
											<div class="btn-group dropdown">
												<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"><i class="icon-cog"></i> <span class="caret"></span></button>
												<ul class="dropdown-menu" style="right:0px !important; left:auto">
													{if $allItem[i].is_trash eq '0'}
													<li><a title="Sửa" href="{$PCMS_URL}/?mod={$mod}&act=edit&page_id={$core->encryptID($allItem[i].page_id)}"><i class="icon-edit"></i> <span>Sửa</span></a></li>
													<li><a title="Xoá vào thùng rác" href="{$PCMS_URL}/?mod={$mod}&act=trash&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}&type_list={$type_list}"><i class="icon-trash"></i> <span>Thùng rác</span></a></li>
													{else}
													<li><a title="Khôi phục" href="{$PCMS_URL}/?mod={$mod}&act=restore&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}&type_list={$type_list}"><i class="icon-refresh"></i> <span>Khôi phục</span></a></li>
													<li><a title="Xoá vĩnh viễn" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&page_id={$core->encryptID($allItem[i].page_id)}{$pUrl}&type_list={$type_list}"><i class="icon-remove"></i> <span>Xoá vĩnh viễn</span></a></li>
													{/if}
												</ul>
											</div>
										</td>
									</tr>
									{/section}
									{else}
									<tr><td colspan="6" class="text-center type--subdued" style="padding:24px">Chưa có trang nào.</td></tr>
									{/if}
								</table>
								<div class="t-grid-pager-boder">
									<div class="t-pager t-reset fix-margin-pager">
										{$html_pager}
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
