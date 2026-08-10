<div class="modal-dialog modal-dialog-centered modal-sm">
	<form method="POST" action="#" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-3">
				<label for="stock_code" class="form-label mb-1">Mã căn</label>
				<!-- onChange="$Core.calendar.check_code(this, event)" -->
				<input type="hidden" name="billing_id" value="{$billing_id}" />
				<input type="text" name="stock_code" class="form-control required"{if $tp eq '_cancel'} disabled{/if} 
				placeholder="Nhập mã căn" value="{$stock_code}">
			</div>
			<div class="form-group mb-3">
				<label class="form-label mb-1">Dự án</label>
				<div class="clearfix"></div>
				<select placeholder="Chọn dự án" class="iso-selectizeNotSearch required w-100" name="project_id" 
				data-url="{$PCMS_URL}/index.php?mod=home&act=list_project" data-optgroup="false" 
				onChange="$Core.billing.load_block(this,event)" block_id="{$block_id}" toId="block{$uid}" >
					{if !empty($billing_id)}
					<option value="{$project_id}" selected="selected">
						{$clsProject->getTitle($project_id)}
					</option>
					{/if}
				</select>
			</div>
			<div class="form-group mb-3">
				<label class="form-label mb-1">Phân khu</label>
				<div class="clearfix"></div>
				<select data-placeholder="Chọn phân khu" class="form-control form-select required" 
					name="block_id" data-width="100%" data-allow-clear="true" id="block{$uid}" onChange="$Core.billing.loadProjectDirector(this,event)" >
					<option value="0">Phân khu</option>	
					{if !empty($lstBlock)}
						{foreach from=$lstBlock item=_oBlock key=key name=i}
							<option value="{$_oBlock.property_id}" {if $_oBlock.property_id eq $block_id}selected{/if} >{$_oBlock.title}</option>	
						{/foreach}
					{/if}
				</select>
			</div>
			<div class="form-group mb-3">
				<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
					<input type="radio" class="btn-check" uid="{$uid}" onchange="$Core.calendar.select_this(this, event)" name="date_type" 
					id="VBTT_{$uid}"{if $tp eq '_cancel'} disabled{/if} value="_text"{if $sign_type eq '_text'} checked="checked"{/if} />
					<label data-toggle="ripple" class="btn btn-outline-default" for="VBTT_{$uid}">VBTT</label>
					<input type="radio" class="btn-check"{if $tp eq '_cancel'} disabled{/if} uid="{$uid}" name="date_type" id="HDMB_{$uid}" 
					onchange="$Core.calendar.select_this(this, event)" value="_contract"{if $sign_type eq '_contract'} checked="checked"{/if} />
					<label data-toggle="ripple" class="btn btn-outline-default" for="HDMB_{$uid}">HĐMB</label>
				</div>
			</div>
			<div class="form-group {$uid}_text{if $sign_type eq '_text'}{else} d-none{/if} mb-3">
				<label for="agree_date" class="form-label mb-1">Ngày ký VBTT</label>
				<input type="datetime-local" id="agree_date" name="agree_date" class="form-control" placeholder="dd/mm/yy" lang="vi-VN" value="{$sign_date}" />
			</div>
			<div class="form-group {$uid}_contract{if $sign_type eq '_contract'}{else} d-none{/if} mb-3">
				<label for="estimate_date" class="form-label mb-1">Ngày dự kiến ký HĐMB</label>
				<input type="datetime-local" id="estimate_date" name="estimate_date" class="form-control" placeholder="dd/mm/yy" lang="vi-VN" />
			</div>
			<div class="form-group {$uid}_contract{if $sign_type eq '_contract'}{else} d-none{/if}">
				<label for="contract_date" class="form-label mb-1">Ngày ký HĐMB</label>
				<input type="datetime-local" id="contract_date" name="contract_date" class="form-control" placeholder="dd/mm/yy" lang="vi-VN" value="{$sign_date}" />
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="tp" value="{$tp}" />
			<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn flex-fill btn-primary" cal_id="{$cal_id}" 
				onClick="$Core.calendar.save_sign_date(this, event)" tp="{$tp}">Cập nhật</button>
		</div>
	</form>
</div>
