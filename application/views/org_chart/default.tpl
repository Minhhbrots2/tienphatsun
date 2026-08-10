<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-xxxl-12">
			<div class="card">
				<div class="card-header py-0 pt-5">
					<div class="d-flex  align-items-center justify-content-center">
						<h5 class="chat-title mb-0 {if $deviceType eq 'phone'}fs-6{else}fs-2{/if} text-main fw-bold text-upper text-center">Cơ cấu tổ chức {$smarty.const.BRAND_NAME}</h5>
					</div>
				</div>
				<div class="card-body">
					<div id="tree-level-container">
						{if !empty($arr_node_level)}
							{foreach from=$arr_node_level item=lstNode key=key}
								<div class="tree-level" data-level="{$key}">
									{foreach from=$lstNode item=_oNode}
										{assign var=oneRole value=$_oNode.oneRole}
										{assign var=oneStaff value=$_oNode.oneStaff}
										{if !empty($_oNode.staff_id)}
											<div id="{$_oNode.id}" class="node" data-id="{$_oNode.id}" data-level="{$_oNode.level}" data-parent-id="{$_oNode.parentId}" data-common-parent-ids="{$_oNode.commonParentIds}" data-role_id="{$_oNode.role_id}" data-staff_id="{$_oNode.staff_id}" data-text_name="{$_oNode.text_name}" style="left: {$_oNode.position.left}%; top: {$_oNode.position.top}%;" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oNode.staff_id}&type=org_chart" data-toggle="webui-popover" data-trigger="hover" data-width="350">
												<div class="img_node"><img class="avatar m-0 rounded-pill" src="{$clsProfile->getAvatar($staff_id,$oneStaff)}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'"></div>
												<div class="box_content">
													<h3 class="txt_name fw-bold">{$clsProfile->getFullname($staff_id,$oneStaff)}</h3>
													<span class="txt_role">{if !empty({$_oNode["text_name"]})}{$_oNode["text_name"]}{else}{$oneRole["title"]}{/if}</span>
												</div>
											</div>
										{else}
											<div id="{$_oNode.id}" class="node" data-id="{$_oNode.id}" data-level="{$_oNode.level}" data-parent-id="{$_oNode.parentId}" data-common-parent-ids="{$_oNode.commonParentIds}" data-role_id="{$_oNode.role_id}" data-staff_id="{$_oNode.staff_id}" data-text_name="{$_oNode.text_name}" style="left: {$_oNode.position.left}%; top: {$_oNode.position.top}%;">
												<div class="img_node d-none"><img class="avatar m-0 rounded-pill" src="{$URL_IMAGES}/no-avatar.jpg" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'"></div>
												<div class="box_content no_staff d-flex justify-content-center align-items-center mt-0" style="height: 82px;padding: 5px">
													<span class="txt_role">{if !empty({$_oNode["text_name"]})}{$_oNode["text_name"]}{else}{$oneRole["title"]}{/if}</span>
												</div>
											</div>
										{/if}
									{/foreach}
								</div>
							{/foreach}
						{/if}
						<svg id="tree-connectors"></svg>			
						{if !empty($lst_point)}
							{foreach from=$lst_point item=_oPoint}
								{assign var=position value=$_oPoint.position}
								<div class="control-handle dragged" data-index="{$_oNode.id}" id="{$_oPoint.id}" left="{$position.left}" top="{$position.top}" style="left:{$position.left}%;top:{$position.top}%"></div>
							{/foreach}
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>