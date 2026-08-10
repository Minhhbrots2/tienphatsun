{if $template_type eq '_form'}
<div class="modal-dialog modal-xs modal-dialog-centered">
	<form class="modal-content" method="POST" enctype="multipart/form-data">
		<div class="modal-header border-bottom">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Tên chiến dịch</label>
				<input name="title" class="form-control required" placeholder="Tên chiến dịch" value="{$oneCampaign.title}" />
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1">Dự án</label>
					<select onchange="$Core.global.load_block(this, event)" toId="slb_Block_{$uid}" 
						class="iso-selectizeSync" is_selectize="1" name="project_id" placeholder="Chọn dự án">
						<option value="">Chọn dự án</option>
						{if !empty($arr_projects)}
							{foreach from=$arr_projects item = _oProject}
							<option{if $campaign_config.project_id eq $_oProject.project_id} selected{/if} value="{$_oProject.project_id}">{$_oProject.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-6">
					<label class="form-label mb-1">Phân khu</label>
					<div id="slb_Block_{$uid}">
						<select class="iso-selectizeSync" name="block_id" placeholder="Chọn phân khu">
							<option value="0">Chọn phân khu</option>
							{if !empty($arr_blocks)}
								{foreach from=$arr_blocks item = _oBlock}
								<option{if $campaign_config.block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</div>
			</div>
			<div class="form-group mb-3">
				<label class="form-label mb-1">Quyền truy cập</label>
				<div class="p-3 rounded-2 bg-lighter">
					<div class="d-flex gap-1 align-items-center">
						<div class="clearfix"></div>
						<label class="switch">
							<input{if $profile_id eq $smarty.const._PROFILE_ROOT_ID || $oneCampaign.use_globe eq '1'} checked{/if} 
								type="checkbox" name="use_globe" value="1">
							<span class="slider round"></span>
						</label>
						<span>Public với mọi người</span>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button class="btn btn-primary" type="button" campaign_id="{$campaign_id}" toId="{$toId}" 
				onClick="$Core.global.crm.save_campaign(this, event)" >Lưu lại</button>
		</div>
	</form>
</div>
{elseif $template_type eq '_merge'}
<div class="modal-dialog modal-xs modal-dialog-centered">
	<form class="modal-content" method="POST" enctype="multipart/form-data">
		<div class="modal-header border-bottom">
			<h5 class="modal-title">Gộp khách hàng</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Chiến dịch gốc</label>
				<div class="form-control form-select">
					<span>{$oneCampaign.title}</span>
				</div>
			</div>
			<div class="d-flex align-items-center justify-content-center">
				<i class='bx bx-down-arrow-circle text-muted text-fs-32'></i>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Chiến dịch cần gộp</label>
				<select name="merge_id" class="form-control iso-selectizeSync required" data-placeholder="Chọn chiến dịch">
					{$html_campaigns}
				</select>
			</div> {$campaign_id}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button class="btn btn-primary" type="button" campaign_id="{$campaign_id}" 
				onClick="$Core.global.crm.do_merge_campaign(this, event)">Thực hiện</button>
		</div>
	</form>
</div>
{elseif $template_type eq '_manager'}
<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" enctype="multipart/form-data">
				<div class="modal-header border-bottom">
					<h5 class="modal-title">Danh sách chiến dịch</h5>
					<button onClick="$Core.global.crm.open_campaign(this, event)" campaign_id="0" 
						class="btn btn-outline-default"><i class="bx bx-plus"></i> Thêm mới</button>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body scroller">
					<div class="table-container no-shadow">
						<table cellspacing="0" cellpadding="0" class="table table-customer mb-0">
							<thead><tr>
								<th width="45px" class="bg-lighter h-px-40">STT</th>
								<th class="bg-lighter h-px-40">Campaign</th>
								<th width="100px"  class="bg-lighter text-center h-px-40">Tổng</th>
								<th width="45px" class="bg-lighter h-px-40"></th>
							</tr></thead>
							<tbody class="holder_campaigns">
								{section name=i loop=$list_preloaders max = 20}
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
{/if}