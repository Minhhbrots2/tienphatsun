<div class="col-12 col-md-6">
	<div class="mb-2 card">
		<div class="card-header">
			<h3 class="card-title">Bảng hàng cao tầng</h3>
		</div>
		<div class="card-body">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="d-flex briefStockHug flex-wrap gap-2 mb-2 align-items-center">
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Tổng quỹ</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_highfloor}</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Dự án</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_block}</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Đại lý</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_agency_highfloor}</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Nhập mới</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_new_highfloor}</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Đã bán</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_sold_highfloor}</strong>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-12 col-md-6">
	<div class="mb-2 card">
		<div class="card-header">
			<h3 class="card-title">Bảng hàng thấp tầng</h3>
		</div>
		<div class="card-body">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="d-flex briefStockHug flex-wrap gap-3 mb-2 align-items-center">
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Tổng quỹ</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_lowfloor}</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Dự án</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_project}</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Đại lý</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_agency_lowfloor}</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Nhập mới</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_new_lowfloor}</strong>
						</li>
					</ul>
				</div>
				<div class="border cursor-pointer flex-fill p-3 rounded-2">
					<div class="d-flex mb-2 align-items-center justify-content-between">
						<h5 class="mb-0">Đã bán</h5>
						<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
							<i class="fa fa-question-circle"></i>
						</a>
					</div>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center justify-content-between">
							<span class="text-muted">Số lượng:</span>
							<strong class="fs-5 text-main">{$total_sold_lowfloor}</strong>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>