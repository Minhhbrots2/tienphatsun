<link rel="stylesheet" type="text/css" href="{$URL_JS}/easyui/themes/gray/easyui.css" media="all" />
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between mb-2">
		<div class="d-flex flex-column mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1">Theo dõi hoa hồng</h4>
			<div class="text-muted">Hiện có tổng <strong class="total text-main">0</strong> giao dịch</div>
		</div>
		<div class="rpfqEIUUwA">
			<div class="d-flex gap-1 algin-items-center">
				<!-- <button type="button" data-toggle="ripple" onclick="$Core.global.commission.open(this, event)" commission_id="0" class="btn btn-outline-primary flex-fill">{$clsISO->makeIcon('bx-plus','Thêm mới')}</button> -->
				<button type="button" data-toggle="ripple" onclick="$Core.commission.crawl(this, event)" class="btn btn-outline-default flex-fill">{$clsISO->makeIcon('bx-upload','Crawl')}</button>
				<div class="btn-group">{$core->getBlock('commission_search')}</div>
				<button type="button" data-toggle="ripple" onclick="$Core.commission.open_setting(this, event)" class="btn btn-icon btn-outline-default">{$clsISO->makeIcon('bx-cog')}</button>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div id="tableCommission" class="freeze-table dragscroll overflow-hidden text-nowrap">
				<table class="table table-bordered mb-0" width="100%">
					<thead><tr>
						<th width="3%" rowspan="3" class="align-center text-center bg-lighter">No.</th>
						<th rowspan="3" class="align-center bg-lighter">Ngày/tháng</th>
						<th rowspan="3" class="align-center bg-lighter">Tên sale</th>
						<th rowspan="3" class="align-center bg-lighter">Mã căn</th>
						<th rowspan="3" class="align-center bg-lighter">Đối tác</th>
						<th rowspan="3" class="align-center bg-lighter">Giá trị HĐMB</th>
						<th rowspan="3" class="align-center bg-lighter text-center">80% Giá trị<br />HĐMB</th>
						<th rowspan="3" class="align-center bg-lighter text-center">% Hoa<br /> Hồng MG<br />ĐẠI LÝ </th>
						<th rowspan="3" class="align-center bg-lighter text-center">Giá trị<br />HHMG được<br />hưởng</th>
						<th colspan="3" rowspan="2" class="align-center bg-lighter text-center">Thưởng nóng</th>
						<th rowspan="3" class="align-center bg-lighter text-center w-px-150">Thưởng<br />GĐDA</th>
						<th rowspan="3" class="align-center bg-lighter text-center w-px-150">Hỗ trợ <br />MKT Đại lý<br />
								<small class="text-none">(Hỗ trợ khác)</small>
						</th>
						<th rowspan="3" class="align-center text-center bg-lighter">Thuế VAT<br />10%</th>
						<th rowspan="3" class="align-center bg-lighter">Thuế TNCN</th>
						<th rowspan="3" class="align-center bg-lighter">TỔNG CỘNG</th>
						<th rowspan="3" class="align-center text-center bg-lighter">Đã tạm<br />ứng</th>
						<th rowspan="3" class="align-center bg-lighter">Phải thu</th>
						<th rowspan="3" class="align-center bg-lighter text-center">Đã TT</th>
						<th colspan="4" rowspan="2" class="align-center bg-lighter text-center">Công ty</th>
						<th rowspan="3" class="align-center bg-lighter text-center">Đã TT</th>
						<th colspan="5" rowspan="2" class="align-center bg-lighter text-center">Phòng KĐ</th>
						<th colspan="11" class="align-center bg-lighter text-center">HH PKD</th>
						<th rowspan="3" class="align-center bg-lighter text-center">%HH khác<br />
							<small class="text-none">(Nếu có)</small>
						</th>
						<th rowspan="3" class="align-center bg-lighter text-center">Thành tiền</th>
						<th rowspan="3" class="align-center bg-lighter text-center">Dự án</th>
						<th rowspan="3" class="align-center bg-lighter text-center">SL Phòng</th>
						<th rowspan="3" class="align-center bg-lighter text-center">Tạm ứng</th>
						<th rowspan="3" class="align-center bg-lighter text-center">Tình trạng</th>
						<th rowspan="3" class="align-center bg-lighter text-center">Ghi chú</th>
						<!-- <th rowspan="3" class="align-center bg-lighter" width="45px"></th> -->
					</tr>
					<tr>
						<td class="align-center fw-bold bg-lighter text-center" colspan="4">Hoa hồng sale bán</td>
						<td class="align-center fw-bold bg-lighter text-center" colspan="4">Hoa hồng Leader</td>
						<td class="align-center fw-bold bg-lighter text-center" colspan="3">Hoa hồng GĐDA</td>
					</tr>
					<tr>
						<th width="6%" class="text-right align-center bg-lighter">Thưởng sale</th>
						<th width="6%" class="text-right align-center bg-lighter">Thưởng ĐL</th>
						<th width="6%" class="text-center align-center bg-lighter">Trừ Vinclub<br />
							<small class="text-none">(Nếu có)</small>
						</th>
						<!-- Công ty -->
						<th width="6%" class="text-right align-center bg-lighter">% HH</th>
						<th width="6%" class="text-right align-center bg-lighter">Thành tiền</th>
						<th width="6%" class="text-right align-center bg-lighter">Thưởng</th>
						<th width="6%" class="text-right align-center bg-lighter">Phải Thu</th>
						<!-- PKD -->
						<th width="6%" class="text-right align-center bg-lighter">% HH</th>
						<th width="6%" class="text-right align-center bg-lighter">Thành tiền</th>
						<th width="6%" class="text-right align-center bg-lighter">Hỗ trợ</th>
						<th width="6%" class="text-right align-center bg-lighter">Thưởng</th>
						<th width="6%" class="text-right align-center bg-lighter">Tổng cộng</th>
						<!-- PKD -->
						<th width="6%" class="text-right align-center bg-lighter">% HH</th>
						<th width="6%" class="text-right align-center bg-lighter">Thành tiền</th>
						<th width="6%" class="text-right align-center bg-lighter">Thưởng</th>
						<th width="6%" class="text-right align-center bg-lighter">Tổng cộng</th>
						<!-- PKD -->
						<th width="6%" class="text-right align-center bg-lighter">% HH</th>
						<th width="6%" class="text-right align-center bg-lighter">Thành tiền</th>
						<th width="6%" class="text-right align-center bg-lighter">Thưởng</th>
						<th width="6%" class="text-right align-center bg-lighter">Tổng cộng</th>
						<!-- GĐ DA -->
						<th width="6%" class="text-right align-center bg-lighter">% HH</th>
						<th width="6%" class="text-right align-center bg-lighter">Thưởng</th>
						<th width="6%" class="text-right align-center bg-lighter">Thành tiền</th>
					</tr>
					<tr>
						<th class="bg-lighter text-center">1</th>
						<th class="bg-lighter text-center text-center">2</th>
						<th class="bg-lighter text-center">3</th>
						<th class="bg-lighter text-center">4</th>
						<th class="bg-lighter text-center">5</th>
						<th class="bg-lighter text-center">6</th>
						<th class="align-center bg-lighter text-center">(7)=(6)*80%</th>
						<th class="bg-lighter text-center">8</th>
						<th class="align-center bg-lighter text-center">(9)=(7)*(8)</th>
						<th class="bg-lighter text-center">10</th>
						<th class="bg-lighter text-center">11</th>
						<th class="bg-lighter text-center">12</th>
						<th class="bg-lighter text-center">13</th>
						<th class="bg-lighter text-center">14</th>
						<th class="bg-lighter text-center">15</th>
						<th class="bg-lighter text-center">16</th>
						<th class="bg-lighter text-center">17</th>
						<th class="bg-lighter text-center">18</th>
						<th class="bg-lighter text-center">19</th>
						<th class="bg-lighter text-center">20</th>
						<th class="bg-lighter text-center">21</th>
						<th class="bg-lighter text-center">22</th>
						<th class="bg-lighter text-center">23</th>
						<th class="bg-lighter text-center">24</th>
						<th class="bg-lighter text-center">25</th>
						<th class="bg-lighter text-center">26</th>
						<th class="bg-lighter text-center">27</th>
						<th class="bg-lighter text-center">28</th>
						<th class="bg-lighter text-center">29</th>
						<th class="bg-lighter text-center">30</th>
						<th class="bg-lighter text-center">31</th>
						<th class="bg-lighter text-center">32</th>
						<th class="bg-lighter text-center">33</th>
						<th class="bg-lighter text-center">34</th>
						<th class="bg-lighter text-center">35</th>
						<th class="bg-lighter text-center">36</th>
						<th class="bg-lighter text-center">37</th>
						<th class="bg-lighter text-center">38</th>
						<th class="bg-lighter text-center">39</th>
						<th class="bg-lighter text-center">40</th>
						<th class="bg-lighter text-center">41</th>
						<th class="bg-lighter text-center">42</th>
						<th class="bg-lighter text-center">43</th>
						<th class="bg-lighter text-center">44</th>
						<th class="bg-lighter text-center">45</th>
						<th class="bg-lighter text-center">46</th>
						<th class="bg-lighter text-center">47</th>
						<th class="bg-lighter text-center">48</th>
						<!-- <th class="bg-lighter text-center">49</th> -->
					</tr>
					<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center">
							<strong class="total_field" data-field="total_commission_money">0.00</strong>
						</th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center">
							<strong class="total_field" data-field="tax_vat_money">0.00</strong>
						</th>
						<th class="bg-lighter text-center">
							<strong class="total_field" data-field="tax_personal_money">0.00</strong>
						</th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<th class="bg-lighter text-center"></th>
						<!-- <th class="bg-lighter text-center"></th> -->
					</tr></thead>
					<tbody class="holder_commission">
						{section name=i loop=$list_preloaders max=25}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<!-- <td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td> -->
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<input type="hidden" name="per_page" value="30" />
			<input type="hidden" name="current_page" value="1" />
			<div id="pager_VAT" class="easyui-pagination"></div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.freeze-table{
		position:relative;
	}
	.freeze-table .table{
		margin-bottom:0;
		min-width:1200px;
		max-width:18000px;
	}
	.freeze-table .table th{
		line-height:16px;
		vertical-align:middle;
	}
	.textbox{
		border-radius:4px;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		-khtml-border-radius:4px;
	}
</style>
<script type="text/javascript">
	$('#tableCommission').freezeTable({
		'columnNum': {/literal}{$columnNum}{literal},
		'scrollBar' : false,
		'scrollable': false,
		'columnKeep': false
	});
</script>
{/literal}
