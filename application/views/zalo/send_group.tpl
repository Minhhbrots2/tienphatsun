<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap align-items-start justify-content-between mb-2">
		<div class="yvBvmnviXh mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1 {if $deviceType eq 'phone'}fs-5{/if}">Gửi tin nhắn Zalo</h4>
			<p class="text-muted mb-0">Có <span class="total_results text-main">0</span> danh sách tin</p>
		</div>
		<div class="yvBvmnviXg d-flex xs:w-100 gap-1">
			<button type="button" onClick="$Core.zalo.open_msg(this, event)" msg_id="0" send_type="send_group" 
				class="btn xs:flex-fill bg-white btn-outline-default"><i class="bx bx-plus"></i> Thêm mới</button>
			<button type="button" onClick="$Core.zalo.manager_group(this, event)" 
				class="btn xs:flex-fill bg-white btn-outline-primary"><i class="bx bx-group"></i> Quán lý nhóm</button>
		</div>
	</div>
	<div class="card no-shadow">
		<div class="card-body">
			<div class="table-container no-shadow text-nowrap overflow-x-auto">
				<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped dragable table-bordered">
					<thead><tr>
						<th class="align-center h-px-40 bg-lighter">Tiêu đề</th>
						<th class="align-center h-px-40 bg-lighter">Nhóm</th>
						<th class="align-center w-px-125 h-px-40 bg-lighter">Hình thức</th>
						<th class="align-center w-px-100 h-px-40 bg-lighter text-center">Công cụ</th>
						<th class="align-center w-px-125 h-px-40 bg-lighter text-center">Tình trạng</th>
						<th class="align-center w-px-150 h-px-40 bg-lighter">Thời gian</th>
						<th width="80px" class="align-center text-center h-px-40 bg-lighter">Công cụ</th>
					</tr></thead>
					<tbody class="holder_message">
						{section name=i loop=$load_preloaders max=30}
						<tr>
							<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
							<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
							<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
							<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
							<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
							<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
							<td class="align-center"><div class="animate-bg rounded-pill w-100 h-px-15"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(() => { $Core.zalo.load_msg({}); });
</script>
{/literal}