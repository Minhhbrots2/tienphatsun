<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Định nghĩa quyền</h1>
				<p class="type--subdued">Bảng quyền hạn được truy cập</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:;" onClick="$Core.permiss.open_permiss(this, event)" parent_id="0" tp="group" profile_type="{$profile_type}" 
						permiss_id="0" class="ui-button ui-button--transparent ui-title-bar__action" title="{$core->get_Lang('Addnew')}">{$core->makeIcon('plus', 'Thêm nhóm')}</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var profile_type = '{$profile_type}';
</script>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
			<div class="next-tab__container">
				<ul class="next-tab__list filter-tab-list">
					<li class="filter-tab-item" data-tab-index="1">
						<a href="{$PCMS_URL}/index.php?mod={$mod}&profile_type=user.fh" 
							class="filter-tab filter-tab-active show-all-items next-tab{if $profile_type=='user.fh'} next-tab--is-active{/if}">Danh sách quyền</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post" enctype="multipart/form-data">
					<div class="form-search">
						<div class="d-flex align-items-center">
							<div class="input-group mr-2">
								<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="tìm kiếm..." />
							</div>
							<input type="hidden" name="filter" value="filter" />
							<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Tìm')}</button>
						</div>
					</div>
					<div class="hastable">
						<div class="freeze-table dragscroll" style="overflow-x: scroll; width:100%;">
							<table id="tableCall" cellspacing="0" class="table table-vertical table-striped" width="100%">
								<thead><tr>
									<th width="5%" class="text-center">No</th>
									<th width="5%" class="text-center"></th>
									<th width="30%" class="text-left">Tiêu đề</th>
									<th class="text-left">Mã</th>
									<th class="text-left">Mô tả</th>
									<th class="text-center" width="90px">Hiển thị</th>
									<th width="150px" class="text-left">H.Động</th>
								</tr></thead>
								{foreach name=i from=$list_groups item = _oGroup}
									{assign var = list_permiss value = $_oGroup.list_permiss}
									{assign var = gId value = $clsISO->getUniqId()}
									<tr>
										<td class="text-center">{$smarty.foreach.i.iteration}</td>
										<td class="text-center">
											<button type="button" onClick="$Core.permiss.open_permiss(this, event)" tp="permiss" profile_type="{$profile_type}" 
												permiss_id="0" parent_id="{$_oGroup.permiss_id}" class="btn btn-success">+ Tạo</button>
										</td>
										<td class="bold">{$_oGroup.title}</td>
										<td>{$_oGroup.code}</td>
										<td>{$_oGroup.description}</td>
										<td class="text-center">
											<label class="switch" title="Hiển thị / Ẩn nhóm quyền">
												<input type="checkbox" tp="group" gId="{$gId}" permiss_id="{$_oGroup.permiss_id}"{if $_oGroup.is_active eq '1'} checked{/if} 
													onclick="$Core.permiss.toggle_permiss(this, event)">
												<span class="slider round"></span>
											</label>
										</td>
										<td class="text-center">
											<button onClick="$Core.permiss.open_permiss(this, event)" tp="group" parent_id="0" permiss_id="{$_oGroup.permiss_id}" class="btn btn-default">{$core->makeIcon('pencil')}</button>
											<button onClick="$Core.permiss.delete_permiss(this, event)" tp="group" parent_id="0" permiss_id="{$_oGroup.permiss_id}" class="btn btn-default">{$core->makeIcon('trash')}</button>
										</td>
									</tr>
									{if !empty($list_permiss)}

										{foreach name=k from=$list_permiss item = _oPermiss}
										<tr>
											<td></td>
											<td class="text-center">{$smarty.foreach.i.iteration}.{$smarty.foreach.k.iteration}</td>
											<td class="bold">{$_oPermiss.title}</td>
											<td>{$_oPermiss.code}</td>
											<td>{$_oPermiss.description}</td>
											<td class="text-center">
												<label class="switch" title="Hiển thị / Ẩn quyền">
													<input type="checkbox" tp="permiss" gId="{$gId}" permiss_id="{$_oPermiss.permiss_id}"{if $_oPermiss.is_active eq '1'} checked{/if} onclick="$Core.permiss.toggle_permiss(this, event)">
													<span class="slider round"></span>
												</label>
											</td>
											<td class="text-center">
												<button onClick="$Core.permiss.open_permiss(this, event)" tp="permiss" parent_id="{$_oPermiss.parent_id}" parent_id="0" permiss_id="{$_oPermiss.permiss_id}" class="btn btn-default">{$core->makeIcon('pencil')}</button>
												<button onClick="$Core.permiss.delete_permiss(this, event)" tp="permiss" parent_id="{$_oPermiss.parent_id}" permiss_id="{$_oPermiss.permiss_id}" class="btn btn-default">{$core->makeIcon('trash')}</button>
											</td>
										</tr>
										{/foreach}
									{/if}
								{/foreach}
							</table>
						</div>
					</div>
				</form>
			</div></div>
		</div></div>
	</div>
</div>