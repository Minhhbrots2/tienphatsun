<div class="modal-dialog modal-ipad-xl">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Chỉnh sửa{/if} chiến dịch</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-2">
                <div class="col-9 col-md-9">
                    <label class="form-label mb-1">Tên chiến dịch</label>
                    <div class="clearfix"></div>
                    <input class="form-control required" charset="UTF-8" name="title" maxlength="255" placeholder="Nhập tên chiến dịch" value="{if $action eq '_edit'}{$oneCampaign.title}{/if}" />
                </div>
                <div class="col-3 col-md-3">
                    <label class="form-label mb-1">Giao diện</label>
                    <div class="clearfix"></div>
                    <select name="template" class="form-control form-select required">
                        {$clsCampaign->getOptTemplate($oneCampaign.template)}
                    </select>
                </div>
			</div>
			<div class="form-row mb-2">
				<div class="col">
					<label class="form-label mb-1">Ngày bắt đầu</label>
					<div class="clearfix"></div>
					<div class="input-group-date">
						<input type="text" name="start_date" class="form-control datepicker required" placeholder="dd/mm/yy" value="{$oneCampaign.start_date|date_format:'d/m/Y'}">
					</div>
				</div>
				<div class="col">
					<label class="form-label mb-1">Ngày kết thúc</label>
					<div class="clearfix"></div>
					<div class="input-group-date">
						<input type="text" id="end_date" name="end_date" class="form-control datepicker required" placeholder="dd/mm/yy" value="{$oneCampaign.end_date|date_format:'d/m/Y'}">
					</div>
				</div>
			</div>
			<div class="form-group mb-3">
				<label class="form-label mb-1">Ghi chú</label>
				<div class="clearfix"></div>
				<textarea class="form-control" name="intro" cols="255" rows="2">{if $action eq '_edit'}{$oneCampaign.intro}{/if}</textarea>
			</div>
			<div class="form-group mb-3">
                <div class="p-3 bg-lighter rounded-2">
					{assign var = toId value = $clsISO->getUniqid()}
					<div class="d-flex align-items-center">
						<label class="switch mr-2">
							<input type="checkbox" onChange="$Core.campaign.sw_terms(this, event)"{if $oneCampaign.is_terms eq '1'} checked{/if} toId="{$toId}" name="is_terms" value="1" />
							<span class="slider round"></span>
						</label>
						<span>Thiết lập điểm số cho chiến dịch</span>
					</div>
					<div id="{$toId}" class="form-row mt-2{if $oneCampaign.is_terms eq '1'}{else} d-none{/if}">
						{foreach name=i from=$list_billing_type item= _oP}
						{assign var = prop_id value = $_oP.property_id}
						<div class="col-6 mb-2 col-md-2">
							<div class="mb-1 fs-12 text-nowrap">{$_oP.title}</div>
							<div class="input-group">
								<input type="text" name="campaign_terms[{$prop_id}][f1]" 
								class="form-control required" value="{$campaign_terms.$prop_id.f1}" 
								onclick="this.select()" />
								<input type="text" name="campaign_terms[{$prop_id}][cross]" 
								class="form-control required" value="{$campaign_terms.$prop_id.cross}" 
								onclick="this.select()" />
							</div>
						</div>
						{/foreach}
					</div>
				</div>
			</div>
			<div class="form-group mb-3">
				<label class="form-label">Đối tượng tham gia</label>
				<div class="clearfix"></div>
				<div class="btn-group mb-3" role="group">
					{foreach name=i from=$list_selector key=_okey item=_oval}
					<input type="radio" class="btn-check" uid="{$uid}"{if $oneCampaign.selector eq $_okey} checked{/if} name="selector" campaign_id="{$campaign_id}" value="{$_okey}" onchange="$Core.campaign.set_content(this, event)" id="{$uid}_{$_okey}" autocomplete="off">
					<label class="btn btn-outline-default" for="{$uid}_{$_okey}">{$_oval}</label>
					{/foreach}
				</div>
				<div class="loadcontentcampaign_{$uid}"></div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" campaign_id="{$campaign_id}" 
			onClick="$Core.campaign.save(this, event)" class="btn flex-fill btn-primary">Lưu lại</button>
		</div>
	</form>
</div>