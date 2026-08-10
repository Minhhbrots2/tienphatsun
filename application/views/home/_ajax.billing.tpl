<div class="modal-dialog modal-ipad-xl">
	{assign var = toId value = $clsISO->getUniqid()}
	<form class="d-none" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="hid" value="upload" />
		<input type="hidden" name="billing_code" value="{$oneBilling.billing_code}" />
		<input type="file" toId="{$toId}" class="upload_file_{$toId}" onChange="$Core.billing.upload_sp_file(this, event)" 
		name="upload_file" billing_id="{$billing_id}" />
	</form>
	<form class="modal-content bf-form">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Chỉnh sửa{/if} giao dịch</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{if $is_content_changing eq '1' && !empty($arr_change_logs)}
			<div class="alert alert-warning">
				<h3 class="mb-2 text-fs-18"><i class="bx bx-bell"></i> Thay đổi đang chờ được chấp nhận</h3>
				<p class="mb-1"><strong>Lý do:</strong> {$arr_change_logs.reason}</p>
				<p class="mb-1"><strong>Thời gian:</strong> {$clsISO->convertTimeToText($arr_change_logs.reg_date, true)}</p>
				<b>Chi tiết: </b>
				<ul class="mb-0">
					{foreach from=$arr_change_logs.content_change key = _oField item = _oValue}
						{if $_oField eq 'totalgrand'}
						<li>{$clsBilling->getFieldName($_oField)} : {$clsISO->formatPrice($_oValue)}</li>
						{elseif $_oField eq 'staff_id'}
						<li>{$clsBilling->getFieldName($_oField)} : {$clsProfile->getFullName($_oValue)}</li>
						{elseif $_oField eq 'stock_code'}
						<li>{$clsBilling->getFieldName($_oField)} : {$_oValue}</li>
						{/if}
					{/foreach}
				</ul>
			</div>
			{/if}
			<div class="bf-sec"><span>1 &middot; Giao dịch</span></div>
			<div class="form-row mb-2">
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label for="trans_code" class="form-label mb-1">Mã GD</label>
					<input type="text" id="trans_code" name="billing_code" class="form-control required" placeholder="Mã GD" value="{$oneBilling.billing_code}"{if $action eq '_edit'} readonly{/if} >
				</div>
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label for="deposit_date" class="form-label mb-1">Ngày cọc</label>
					<input type="date" id="deposit_date" name="deposit_date" class="form-control required"
					placeholder="dd/mm/yy" lang="vi-VN" value="{if $action eq '_edit'}{$oneBilling.deposit_date|date_format:'%Y-%m-%d'}{else}{$smarty.now|date_format:'%Y-%m-%d'}{/if}">
				</div>
				<div class="col-6 col-md-3 mb-2 mb-lg-0">
					<label class="form-label mb-1">Loại hình</label>
					{assign var = uid value = $clsISO->getUniqid()}
					<select id="{$uid}" class="form-control form-select required" name="billing_type">
						{$clsProperty->getSelectByProperty('_BILLING_TYPE',$oneBilling.billing_type)}
					</select>
				</div>
				<div class="col-6 col-md-3">
					<label class="form-label mb-1">Trạng thái</label>
					<select class="form-control iso-selectize required" name="state_id">
						<option value="">Trạng thái</option>
						{$clsProperty->getSelectByProperty('BILLING_STATE',$oneBilling.state_id)}
					</select>
				</div>
			</div>
			{* Nguồn gốc / nguồn quỹ / bán cho tả CĂN chứ không tả người bán nên gom về đây,
			   cạnh dự án - phân khu - mã căn, thay vì đứng thành một nhóm riêng. *}
			<div class="bf-sec"><span>2 &middot; Căn bán</span></div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Dự án</label>
					<div class="clearfix"></div>
					<select placeholder="Chọn dự án" class="iso-selectizeNotSearch required w-100" name="project_id"
					data-url="{$PCMS_URL}/index.php?mod=home&act=list_project" data-optgroup="false"
					onChange="$Core.billing.handle_stock_changed(this, event);$Core.billing.load_block(this,event)" block_id="{if $action eq '_edit'}{$more_information.block_id}{else}0{/if}" toId="block{$uid}" >
						{if $action eq '_edit'}
						<option value="{$oneBilling.project_id}" selected="selected">
							{$clsProject->getTitle($oneBilling.project_id)}
						</option>
						{/if}
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Phân khu</label>
					<div class="clearfix"></div>
					<select data-placeholder="Chọn phân khu" class="form-control form-select {if $action ne '_edit' || (!empty($oneBilling.project_id) && $oneBilling.project_id ne $smarty.const._PROJECT_OTHER_ID)}required{/if}"
						name="block_id" data-width="100%" data-allow-clear="true" id="block{$uid}" onChange="$Core.billing.loadProjectDirector(this,event)" >
						<option value="0">Phân khu</option>
						{if !empty($lstBlock)}
							{foreach from=$lstBlock item=_oBlock key=key name=i}
								<option value="{$_oBlock.property_id}" {if $_oBlock.property_id eq $more_information.block_id}selected{/if} >{$_oBlock.title}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label id="product_code" class="form-label mb-1">Mã Căn</label>
					<div class="clearfix"></div>
					<input type="text" name="stock_code" autocomplete="off" placeholder="S1.01XXXX" class="form-control required"
						value="{if $action eq '_edit'}{$oneBilling.stock_code}{/if}" />
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Nguồn gốc</label>
					<select class="iso-selectizeNotSearch w-100 required" placeholder="Nguồn gốc" data-url="{$PCMS_URL}/index.php?mod=ajax&sub=helper&act=get_property&property_type=_AGENCY" name="stock_resource" data-optgroup="false">
						{if $action eq '_edit'}
							<option value="{$more_information.stock_resource}" selected="selected">
								{$clsProperty->getTitle($more_information.stock_resource)}
							</option>
						{else}
							<option value="{$oneBilling.stock_resource}" selected="selected">
								{$clsProperty->getTitle($oneBilling.stock_resource)}
							</option>
						{/if}
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Nguồn quỹ</label>
					<select class="form-control form-select required" name="billing_source_id">
						{$clsProperty->getSelectByProperty('BILLING_SOURCE',$oneBilling.billing_source_id)}
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Loại giao dịch</label>
					<select class="form-control form-select" name="deal_type" onChange="$Core.billing.handle_deal_type_changed(this, event)" title="Bán cho F2 = bán sỉ cho đại lý, không phát sinh hoa hồng cá nhân, chỉ có phần công ty">
						<option value="sale"{if $more_information.deal_type neq 'channel' && $more_information.deal_type neq 'f2'} selected="selected"{/if}>Sale nội bộ</option>
						<option value="channel"{if $more_information.deal_type eq 'channel'} selected="selected"{/if}>Phát triển đối tác (PTĐT)</option>
						<option value="f2"{if $more_information.deal_type eq 'f2'} selected="selected"{/if}>Bán cho F2</option>
					</select>
				</div>
				<input type="hidden" name="sold_to_type" value="{$oneBilling.sold_to_type|escape}">
			</div>
			<div class="widget-block mb-2 collapsed">
				<div onclick="$Core.helper.toggle_block(this,event)" class="widget-header">3 &middot; Khách hàng</div>
				<div class="widget-content">
					<div class="form-row">
						<div class="col-12 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Tên khách hàng</label>
							<input type="text" name="customer_name" class="form-control" placeholder="Nguyễn Văn A" value="{if $action eq '_edit'}{$more_information.customer_name}{/if}">
						</div>
						<div class="col-12 col-md-4 mb-2 mb-lg-0">
							<label class="form-label mb-1">Điện thoại</label>
							<input type="text" name="customer_phone" class="form-control" placeholder="Điện thoại" value="{if $action eq '_edit'}{$more_information.customer_phone}{/if}">
						</div>
						<div class="col-12 col-md-4">
							<label class="form-label mb-1">E-mail</label>
							<input type="text" name="customer_email" class="form-control" placeholder="example@gmail.com" value="{if $action eq '_edit'}{$more_information.customer_email}{/if}">
						</div>
					</div>
				</div>
			</div>
			<div class="bf-sec"><span>4 &middot; Người bán</span></div>
			{assign var=_uid_deal value=$clsISO->getUniqid()}
			<div class="form-row mb-2">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Sales bán</label>
					{* iso-selectizeSync + danh sách render sẵn từ server, KHÔNG dùng bộ đôi cũ
					   (iso-selectizeImageSearch khi thêm / iso-selectize khi sửa):
					   - iso-selectizeImageSearch tải danh sách từ xa nên LÀM RƠI lựa chọn có sẵn
					   - iso-selectize thì giữ được lựa chọn nhưng KHÔNG tải danh sách, mà template
					     chỉ render đúng MỘT option là người đang chọn ⇒ lúc sửa không đổi được sale.
					   Cùng cách 3 ô TPKD/GĐKD/Admin bên dưới đang dùng. *}
					{* `required` chỉ áp khi CÓ sale nội bộ. Validator coi 0 là rỗng (isEmptyZero) nên giao
					   dịch bán cho F2 — vốn không có sale — sẽ không lưu được, mà form chỉ nhảy con trỏ
					   về đây chứ không báo gì. JS gỡ/gắn lại class này khi đổi loại giao dịch. *}
					<select class="w-100 iso-selectizeSync js__staff-select" data-placeholder="Chọn sales bán" 
						name="staff_id" data-width="100%" data-allow-clear="true" data-optgroup="false"
						onChange="$Core.billing.handle_staff_changed(this, event)">
						<option value="0">Chọn sales bán</option>
						{if !empty($arr_staffs)}
							{foreach from=$arr_staffs item=_oStaff}
							<option{if $oneBilling.staff_id eq $_oStaff.profile_id} selected="selected"{/if} value="{$_oStaff.profile_id|escape}">{$_oStaff.full_name|escape}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Đại lý bán</label>
					<select class="iso-selectizeNotSearch w-100" placeholder="Đại lý bán" data-width="100%" name="sale_agency_id" data-optgroup="false"
						data-url="{$PCMS_URL}/index.php?mod=ajax&sub=helper&act=get_property&property_type=_AGENCY" >
						{if !empty($more_information.sale_agency_id)}
						<option value="{$more_information.sale_agency_id}" selected="selected">{$clsProperty->getTitle($more_information.sale_agency_id)}</option>
						{/if}
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label mb-1">Admin phụ trách</label>
					<select class="iso-selectizeImageSearch required w-100" placeholder="Nhân viên"  name="admin_id" data-optgroup="false"
						data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=admin">
						{if $action eq '_edit'}
						<option value="{$oneBilling.admin_id}" selected>{$clsProfile->getIndentity($oneBilling.admin_id)}</option>
						{/if}
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-row mb-2">
				<div class="col-12">
					{assign var=co_main value=100}
					{if !empty($list_co_sellers)}{foreach from=$list_co_sellers item=_cs}{assign var=co_main value=$co_main-$_cs.share_ratio}{/foreach}{/if}
					{assign var=co_show value=$co_main}
					{if $co_main < 0}{assign var=co_show value=0-$co_main}{/if}
					<div class="co-box">
						<div class="co-box-header">
							<span class="fw-medium">Sale phụ (co-sale)</span>
							<span class="co-main-chip{if $co_main < 0} is-over{/if}">
								<span class="co-main-label">{if $co_main < 0}Vượt{else}Sale chính giữ{/if}</span>
								<strong class="co_main_ratio">{$co_show}</strong>%
							</span>
						</div>
						<div class="co-box-body" id="co_sale_list">
							{section name=r loop=$co_rows_count}
							{assign var=_ridx value=$smarty.section.r.index}
							{if $_ridx < $list_co_sellers|@count}{assign var=_co value=$list_co_sellers[$_ridx]}{else}{assign var=_co value=false}{/if}
							<div class="co-sale-row d-flex align-items-center gap-2 mb-2{if $_ridx > 0 && !$_co} d-none{/if}">
								<span class="co-index">{$_ridx+1}</span>
								<div class="flex-grow-1">
									<select class="iso-selectizeImageSearch w-100" placeholder="Chọn sale phụ" name="co_sale[{$_ridx}][staff_id]" data-optgroup="false" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=all">
										{if $_co}<option value="{$_co.staff_id}" selected>{$clsProfile->getIndentity($_co.staff_id)|escape}</option>{/if}
									</select>
								</div>
								<div class="input-group flex-shrink-0 w-px-125">
									<input type="text" class="form-control text-end co-ratio" inputmode="decimal" name="co_sale[{$_ridx}][ratio]" placeholder="0" value="{if $_co}{$_co.share_ratio}{/if}" onkeyup="$Core.billing.update_co_sale_hint()">
									<span class="input-group-text">%</span>
								</div>
								<button type="button" class="co-remove" title="Xoá sale phụ" onclick="$Core.billing.remove_co_sale(this,event)"><i class="bx bx-x"></i></button>
							</div>
							{/section}
						</div>
						<div class="co-box-footer">
							<button type="button" class="btn btn-sm btn-link p-0 btn_add_co_sale" onclick="$Core.billing.add_co_sale(this,event)"><i class="bx bx-plus-circle"></i> Thêm sale phụ</button>
						</div>
					</div>
				</div>
			</div>
			{* 4 ô %Hoa hồng GĐKD / GĐ Vùng / GĐDA đã bỏ: đo trên 342 giao dịch live thì
			   KHÔNG ô nào từng có giá trị. Hoa hồng vai trò tính theo bậc thang luỹ kế cả năm,
			   không nhập tay từng giao dịch. *}
			{* TPKD/GĐKD ở đây gán cho SALE CHÍNH. Trước đây 2 ô này là UI chết (server không đọc,
			   luôn suy từ chuỗi phòng ban rồi ghi đè); nay ghi thật vào dòng phân bổ của sale chính.
			   Sale phụ khác phòng có quản lý riêng → chỉnh ở màn Quyết toán. *}
			<div class="bf-sec"><span>5 &middot; Quản lý hưởng hoa hồng</span></div>
			<div class="form-group form-row mb-2">
				{* Dùng iso-selectizeSync + danh sách render sẵn từ server, KHÔNG dùng
				   iso-selectizeImageSearch: biến thể đó preload danh sách từ xa và làm RƠI
				   lựa chọn có sẵn khi mở lại giao dịch (đó cũng là lý do ô "Sales bán" tự
				   đổi sang iso-selectize khi sửa). Cách này giữ được lựa chọn mà vẫn đổi được. *}
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Trưởng phòng Kinh doanh</label>
					<div class="clearfix"></div>
					<select data-placeholder="Chọn TPKD" class="form-control iso-selectizeSync" name="main_head_of_dep_id"
						data-width="100%" data-allow-clear="true">
						<option value="0">Trưởng phòng KD</option>
						{if !empty($arr_staffs)}
							{foreach from=$arr_staffs item=_oStaff}
							<option{if $main_head_of_dep_id eq $_oStaff.profile_id} selected="selected"{/if} value="{$_oStaff.profile_id|escape}">{$_oStaff.full_name|escape}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label class="form-label mb-1">Giám đốc Kinh doanh</label>
					<div class="clearfix"></div>
					<select data-placeholder="Chọn GĐKD" class="form-control iso-selectizeSync" name="main_sale_dir_id"
						data-width="100%" data-allow-clear="true">
						<option value="0">Giám đốc KD</option>
						{if !empty($arr_staffs)}
							{foreach from=$arr_staffs item=_oStaff}
							<option{if $main_sale_dir_id eq $_oStaff.profile_id} selected="selected"{/if} value="{$_oStaff.profile_id|escape}">{$_oStaff.full_name|escape}</option>
							{/foreach}
						{/if}
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label mb-1">Chọn GĐDA</label>
					<div class="clearfix"></div>
					<select data-placeholder="Chọn GĐDA" class="form-control iso-selectizeSync" name="project_director_id"
						data-width="100%" data-allow-clear="true">
						<option value="0">Giám Đốc DA</option>
						{if !empty($arr_staffs)}
							{foreach from=$arr_staffs item = _oStaff}
							<option{if $dep_logs.project_director_id eq $_oStaff.profile_id} selected="selected"{/if} value="{$_oStaff.profile_id}">{$_oStaff.full_name}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>
			{* "Số tiền" đứng cạnh "Giá tính hoa hồng" và "% Hoa hồng Sale": ba số gốc mà mọi
			   con số quyết toán tính ra từ đó, để soi chéo được ngay lúc nhập. *}
			<div class="bf-sec"><span>6 &middot; Tiền &amp; hoa hồng</span></div>
			<div class="bg-lighter rounded-2 p-3 mb-2">
				<div class="form-group form-row mb-2">
					<div class="col-12 col-md-4 mb-2">
						<label for="totalgrand" class="form-label mb-1">Số tiền (giá trị bán)</label>
						<div class="clearfix"></div>
						<div class="input-group input-group-merge">
							<input type="text" id="totalgrand" name="totalgrand" class="form-control numberonly price-In required" autocomplete="off"
								placeholder="0.00" value="{if $action eq '_edit'}{$oneBilling.totalgrand}{/if}">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-12 col-md-4 mb-2">
						<label class="form-label mb-1">Giá tính hoa hồng</label>
						<div class="clearfix"></div>
						<div class="input-group input-group-merge">
							<input type="text" id="commission_value" name="commission_value" class="form-control numberonly price-In" autocomplete="off" placeholder="0.00" value="{if $action eq '_edit'}{$more_information.commission_value}{else}0{/if}" onClick="this.select()">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-12 col-md-4 mb-2">
						<label class="form-label mb-1">% Hoa hồng Sale </label>
						<div class="clearfix"></div>
						<div class="input-group input-group-merge">
							<input type="text" id="commission" name="commission" onChange="$Core.util.is_valid_percent(this, event)" class="form-control required numberonly" autocomplete="off" placeholder="0.00" value="{if $action eq '_edit'}{$more_information.commission}{/if}" onClick="this.select()">
							<span class="input-group-text">%</span>
						</div>
					</div>
					{* Thưởng nóng khách hàng · Thưởng nóng đại lý (CTV/ĐL) · Tổng tiền giảm trừ ·
					   % Tỷ lệ giảm trừ sales chịu · Công ty chịu → đã chuyển sang màn Quyết toán.
					   KHÔNG gửi lên nữa; handler chỉ ghi đè key nào form thực sự có gửi, nên số
					   quyết toán không bị xoá mỗi lần lưu giao dịch. *}
					<div class="col-6 col-md-3">
						<label class="form-label mb-1">Thưởng Sale</label>
						<div class="input-group input-group-merge">
							<input type="text" id="sale_bonus" name="sale_bonus" class="form-control numberonly price-In" autocomplete="off" placeholder="0.00" value="{if $action eq '_edit'}{$more_information.sale_bonus}{else}0{/if}" onClick="this.select()">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<label class="form-label mb-1">Thưởng đại lý</label>
						<div class="input-group input-group-merge">
							<input type="text" id="bonus_agency" name="bonus_agency" class="form-control numberonly price-In" autocomplete="off" placeholder="0.00" value="{if $action eq '_edit'}{$more_information.bonus_agency}{else}0{/if}" onClick="this.select()">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<label class="form-label mb-1">Thưởng nóng</label>
						<div class="input-group input-group-merge">
							<input type="text" id="hot_bonus" name="hot_bonus" class="form-control numberonly price-In" autocomplete="off" placeholder="0.00" value="{if $action eq '_edit'}{$more_information.hot_bonus}{else}0{/if}" onClick="this.select()">
							<span class="input-group-text">.đ</span>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<label class="form-label mb-1">Tiền hỗ trợ(nếu có)</label>
						<div class="clearfix"></div>
						<div class="input-group input-group-merge">
							<input type="text" name="support_sale" autocomplete="off" placeholder="0.00" class="form-control numberonly price-In" value="{if $action eq '_edit'}{$more_information.support_sale}{else}0{/if}" onClick="this.select()" />
							<span class="input-group-text">.đ</span>
						</div>
					</div>
				</div>
			</div>
			{* Ảnh đính kèm + ghi chú gộp thành một nhóm thu gọn: đều là hồ sơ kèm theo,
			   người nhập thường chỉ mở tới khi đã có file. *}
			<div class="widget-block mb-2 collapsed">
				<div onclick="$Core.helper.toggle_block(this,event)" class="widget-header">7 &middot; Hồ sơ &amp; ghi chú</div>
				<div class="widget-content">
					<div class="form-group form-row mb-2">
						<div class="col-12 col-md-3 mb-2 mb-lg-0">
							<label class="d-flex align-items-center justify-content-between form-label mb-1">Ảnh chụp xác nhận
								<a title="Dán ảnh từ Clipboard" contenteditable="true" class="btn btn-outline-default btn-xs btn-icon" toId="{$toId}" to_field="capture_confirm_file" billing_id="{$billing_id}" onpaste="$Core.global.billing.upload_sp_file_clipboard(this, event)">
									<i class='bx bx-paste fs-12'></i></a>
							</label>
							<div class="clearfix"></div>
							<div id="capture_confirm_file_{$toId}" class="capture_confirm_file_{$toId}">
								{if !empty($more_information.capture_confirm_file)}
								<a class="download w-100 limit_1line text-nowrap" data-fancybox="capture_confirm_file_{$uid}" href="{$clsISO->getGoogleUrl($more_information.capture_confirm_file)}">
									{$clsISO->formatFileName($more_information.capture_confirm_file)}
								</a>
								{else}
								<a href="javascript:void(0);" toId="{$toId}" to_field="capture_confirm_file" p_id="{$billing_id}" onclick="$Core.global.billing.select_sp_file(this, event)" class="w-100 btn btn-sm btn-outline-default text-none"><i class="bx bx-upload"></i> Tải file</a>
								{/if}
							</div>
						</div>
						<div class="col-6 col-md-3">
							<label class="d-flex align-items-center justify-content-between form-label mb-1">Ảnh CSBH
								<a title="Dán ảnh từ Clipboard" contenteditable="true" class="btn btn-outline-default btn-xs btn-icon" toId="{$toId}" to_field="sale_policy_file" billing_id="{$billing_id}" onpaste="$Core.global.billing.upload_sp_file_clipboard(this, event)">
									<i class='bx bx-paste fs-12'></i></a>
							</label>
							<div class="clearfix"></div>
							<div id="sale_policy_file_{$toId}" class="sale_policy_file_{$toId}">
								{if !empty($more_information.sale_policy_file)}
								<a class="download w-100 limit_1line text-nowrap" data-fancybox="sale_policy_file_{$uid}" href="{$clsISO->getGoogleUrl($more_information.sale_policy_file)}">{$clsISO->formatFileName($more_information.sale_policy_file)}</a>
								{else}
								<a href="javascript:void(0);" toId="{$toId}" to_field="sale_policy_file" p_id="{$billing_id}" onclick="$Core.global.billing.select_sp_file(this, event)" class="w-100 btn btn-sm btn-outline-default text-none"><i class="bx bx-upload"></i> Tải file</a>
								{/if}
							</div>
						</div>
						<div class="col-6 col-md-3">
							<label class="d-flex align-items-center justify-content-between form-label mb-1">Ảnh bảng thưởng
								<a title="Dán ảnh từ Clipboard" contenteditable="true" class="btn btn-outline-default btn-xs btn-icon" toId="{$toId}" to_field="table_bonus_file" billing_id="{$billing_id}" onpaste="$Core.global.billing.upload_sp_file_clipboard(this, event)">
									<i class='bx bx-paste fs-12'></i></a>
							</label>
							<div class="clearfix"></div>
							<div id="table_bonus_file_{$toId}" class="table_bonus_file_{$toId}">
								{if !empty($more_information.table_bonus_file)}
								<a class="download w-100 limit_1line text-nowrap" data-fancybox="table_bonus_file_{$uid}" href="{$clsISO->getGoogleUrl($more_information.table_bonus_file)}">{$clsISO->formatFileName($more_information.table_bonus_file)}</a>
								{else}
								<a href="javascript:void(0);" toId="{$toId}" to_field="table_bonus_file" p_id="{$billing_id}" onclick="$Core.global.billing.select_sp_file(this, event)" class="w-100 btn btn-sm btn-outline-default text-none"><i class="bx bx-upload"></i> Tải file</a>
								{/if}
							</div>
						</div>
						<div class="col-6 col-md-3">
							<label class="d-flex align-items-center justify-content-between form-label mb-1">Ảnh vinh danh
								<a title="Dán ảnh từ Clipboard" contenteditable="true" class="btn btn-outline-default btn-xs btn-icon" toId="{$toId}" to_field="image_poster" billing_id="{$billing_id}" onpaste="$Core.global.billing.upload_sp_file_clipboard(this, event)">
									<i class='bx bx-paste fs-12'></i></a>
							</label>
							<div class="clearfix"></div>
							<div id="image_poster_{$toId}" class="image_poster_{$toId}">
								{if !empty($more_information.image_poster)}
								<a class="download w-100 limit_1line text-nowrap" data-fancybox="image_poster_{$uid}" href="{$clsISO->getGoogleUrl($more_information.image_poster)}">
									{$clsISO->formatFileName($more_information.image_poster,10)}
								</a>
								{else}
								<a href="javascript:void(0);" toId="{$toId}" to_field="image_poster" p_id="{$billing_id}" onclick="$Core.global.billing.select_sp_file(this, event)" class="w-100 btn btn-sm btn-outline-default text-none">
									<i class="bx bx-upload"></i> Tải file</a>
								{/if}
							</div>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<label class="form-label mb-1">Ghi chú</label>
							<textarea class="form-control" placeholder="Viết ghi chú" name="staff_notes" rows="2">{if !empty($more_information.staff_notes)}{$more_information.staff_notes}{/if}</textarea>
						</div>
					</div>
				</div>
			</div>
			{* 4 công tắc Liên minh · Full điểm · Nộp PCC · Gửi e-mail đã bỏ:
			   đo live 0/344 giao dịch nào từng bật cái nào. *}
			{* Khối "Gửi Zalo chúc mừng" đã bỏ. Gate cũ có điều kiện 1==2 nên KHÔNG BAO GIỜ
			   hiển thị — code chết. Đo live: 0/344 giao dịch bật gửi Zalo, 0 giao dịch có
			   người được chúc. Ô "Ảnh vinh danh" giữ lại vì là ô upload riêng, 1 giao dịch dùng. *}
		</div>
		<div class="modal-footer">
			<input type="hidden" name="regional_id" value="{$oneBilling.regional_id}" />
			<input type="hidden" name="department_id" value="{$oneBilling.department_id}" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" openFrom="{$openFrom}" holderG="save" uid="{$uid}" billing_id="{$billing_id}" is_ignore_confirmed="0" 
				onClick="$Core.global.billing.pop_save_billing(this, event)" class="btn btn-primary js__save-billing">{$core->makeIcon('floppy-o', 'Lưu lại')}</button>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	input[name=ms_date]{ width:100px !important;}
	.selectize-dropdown{ z-index:3 !important}
</style>
{/literal}
