<div class="modal-dialog modal-standard">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">&nbsp;</h5>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="printArea">
				<div class="text-center mb-4">
					<h2 class="fs-3 mb-2 text-upper">CÔNG TY CỔ PHẦN TM & DV {$smarty.const.BRAND_NAME}</h2>
					<p>Địa chỉ: BH10A-SP10-24 Vinhomes Ocean Park, Kiêu Kỵ, Gia Lâm, Hà Nội.</p>
				</div>
				<h4 class="text-center">PHIẾU XÁC NHẬN HOA HỒNG</h4>
				<table class="table-print" width="100%">
					<tr>
						<td><strong>Họ và tên:</strong> Bùi Văn Cường</td>
						<td><strong>Cấp bậc:</strong> Giám Đốc Kinh Doanh	</td>
						<td><strong>Số CMND:</strong> 008200007052</td>
					</tr>
					<tr>
						<td colspan="2"><strong>Hộ khẩu thường trú:</strong> Kháng Nhật, Sơn Dương, Tuyên Quang</td>
						<td><strong>Số điện thoại:</strong> 0869015555</td>
					</tr>
					<tr>
						<td colspan="3"><strong>Địa chỉ liên hệ:</strong> Tòa S2.08 Vinhomes Ocean Park, Gia Lâm Hà Nội</td>
					</tr>
					<tr>
						<td colspan="3"><strong>TPKD/ GĐKD phụ trách:</strong> Lương Tiến Dũng</td>
					</tr>
					<tr>
						<td ><strong>Số tài khoản:</strong> 1026859999	</td>
						<td><strong>Chủ tài khoản:</strong> Bùi Văn Cường</td>
						<td><strong>Ngân hàng:</strong> Vietcombank	</td>
					</tr>
				</table>
				<br />
				<table class="table-print table-print-bordered mb-1">
					<thead><tr>
						<th class="text-center bg-lighter" rowspan="3" width="3%">STT</th>
						<th class="text-center bg-lighter" rowspan="3">Mã căn</th>
						<th class="text-center bg-lighter" rowspan="3">Dự án</th>
						<th width="120px" class="text-center bg-lighter" rowspan="3">Ngày kí<br />HĐMB</th>
						<th width="120px" class="text-center bg-lighter" rowspan="3">Gía trị<br />HĐMB</th>
						<th class="text-center bg-lightest" colspan="4">Thông tin hoa hồng</th>
						<th width="150px" class="text-center bg-lighter" rowspan="3">Ghi chú</th>
					</tr>
					<tr>
						<th class="text-center bg-lighter" rowspan="2">Tỷ lệ<br />HH(%)</th>
						<th class="text-center bg-lighter" colspan="2">Thưởng</th>
						<th class="text-center bg-lighter" rowspan="2">Hỗ trợ<br />(Nếu có)</th>
					</tr>
					<tr>
						<th class="text-center bg-lighter">Đại lý</th>
						<th class="text-center bg-lighter">CĐT</th>
					</tr></thead>
					{foreach name=i from=$billing_store item=_oBilling}
					<tr>
						<td style="text-align:center">{$smarty.foreach.i.iteration}</td>
						<td>{$_oBilling.stock_code}</td>
						<td>VHOP</td>
						<td>{$_oBilling.contract_date}</td>
						<td style="text-align:right">{$_oBilling.total_price}</td>
						<td style="text-align:center">{$_oBilling.commission}</td>
						<td style="text-align:right">{$_oBilling.price_ms}</td>
						<td style="text-align:right">{$_oBilling.price_ns}</td>
						<td style="text-align:right">{$_oBilling.price_ms}</td>
						<td>{$_oBilling.notes}</td>
					</tr>
					{/foreach}
				</table>
				<br />
				<table width="100%">
					<tr>
						<td style="text-align:center"><strong>Nhân viên kinh doanh<br />(Ký và xác nhận)</strong></td>
						<td style="text-align:center"><strong>Trợ lý kinh doanh<br / >(Ký và xác nhận)</strong></td>
						<td style="text-align:center"><strong>TPKD/ GĐKD<br />(Ký và xác nhận)</strong></td>
						<td style="text-align:center"><strong>GĐDA/ P.TGĐ <br />(Ký và xác nhận)</strong></td>
						<td style="text-align:center"><strong>Tổng giám đốc<br />Phê duyệt</strong></td>
					</tr>
				</table>
				<br />
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.printArea{
		margin-top:-30px;
		color:rgba(0,0,0,1);
	}
	.table-print th,
	.table-print td{
		padding:3px 3px;
	}
	.table-print-bordered td,
	.table-print-bordered th{
		border:1px solid rgba(0,0,0,1);
	}
</style>
{/literal}