<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-lg-10 offset-lg-1">
			<div class="card">
				<div class="card-header">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex flex-column">
							<h5 class="chat-title mb-0">Vòng quay may mắn</h5>
							<span class="text-muted fs-11">Tổng <strong class="text-main">0</strong> chương trình đã tạo</span>
						</div>
						<button type="button" onclick="$Core.lucky_wheel.open(this, event)" wheel_id="0" 
							class="btn btn-outline-default"><i class="bx bx-plus"></i> Thêm mới</button>
					</div>
				</div>
				<div class="card-body">
					<div class="table-container no-shadow overflow-x-auto">
						<table cellspacing="0" cellspacing="0" class="table table-striped dragable table-bordered">
							<thead><tr>
								<th class="align-center text-center h-px-35 bg-lighter" width="40px">STT</th>
								<th class="align-center h-px-35 bg-lighter">Tên chương trình</th>
								<th class="align-center h-px-35 bg-lighter">Loại chương trình</th>
								<th class="align-center text-center h-px-35 bg-lighter">Phần thưởng</th>
								<th class="align-center text-center h-px-35 bg-lighter">Tham gia</th>
								<th class="align-center h-px-35 bg-lighter text-center">Bắt đầu</th>
								<th class="align-center h-px-35 bg-lighter text-center">Kết thúc</th>
								<th class="align-center h-px-35 bg-lighter text-center">Trạng thái</th>
								<th class="align-center h-px-35 bg-lighter" width="40px"></th>
							</tr></thead>
							<tbody class="holder_lucky_wheel">
								{section name=i loop=$list_preloaders}
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
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
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$().ready(function(){
		$_document.on('click', '.dropdown-button', function (event) {
			event.stopPropagation(); 
			let button = $(this),
				offset = button.offset(),
				dropdown = button.siblings('.dropdown-menu').clone();
			// Xóa dropdown cũ trước khi mở cái mới
			$('.dropdown-menu-floating').remove();
			// Thêm vào body và hiển thị tạm thời để lấy kích thước chính xác
			dropdown.addClass('dropdown-menu-floating')
				.css({ position: 'absolute', display: 'block'})
				.appendTo('body');
			// Lấy kích thước dropdown
			let dropdownWidth = dropdown.outerWidth(),
				buttonWidth = button.outerWidth();
			// Căn dropdown về bên phải của nút
			dropdown.css({
				top: offset.top + button.outerHeight(), // Ngay dưới nút
				left: offset.left + buttonWidth - dropdownWidth, // Căn phải
				zIndex: 1000
			});
			// Đóng dropdown khi click ra ngoài
			$_document.on('click', function () {
				$('.dropdown-menu-floating').remove();
			});
		});
	});
</script>
{/literal}