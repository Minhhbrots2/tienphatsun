<div class="modal-dialog modal-dialog-centered modal-sm">

	<form method="POST" class="modal-content">

		<div class="modal-header border-bottom">

			<h5 class="modal-title">Mục tiêu cá nhân {$titlePage}</h5>

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body">

			<div class="alert alert-warning">

				<strong>Ghi chú</strong><br />

				Thiết lập mục tiêu cho cá nhân, phòng ban, khối kinh doanh

			</div>

			{if $is_sale_dir eq '1' || $is_regional_dir eq '1'}

			<div class="divider mb-2">

				<div class="divider-text">Mục tiêu{if $is_regional_dir eq '1'} Vùng {else} Phòng{/if}</div>

			</div>

			<div class="form-group my-2">

				<label for="name" class="form-label mb-1">Mục tiêu cho</label>

				<div class="clearfix"></div>

				<div class="btn-group w-100" role="group" aria-label="Hiển thị">

					<input type="radio" class="btn-check js__target-type" tp="dep" name="target_dep_config[target_type]" value="only_month" id="only_dep_month_{$uid}"{if $target_dep_config.target_type eq 'only_month'} checked{/if} quantity="{$target_dep_month_config.quantity}" amount="{$target_dep_month_config.amount}">

					<label data-toggle="ripple" class="btn btn-outline-default{if $target_dep_config.target_type eq 'only_month'} active{/if}" for="only_dep_month_{$uid}">Tháng {$smarty.now|date_format:"%m"}</label>

					<input type="radio" class="btn-check js__target-type" tp="dep" name="target_dep_config[target_type]" id="all_dep_month_{$uid}" value="all_month"{if $target_dep_config.target_type eq 'all_month'} checked{/if} quantity="{$target_dep_config.quantity}" amount="{$target_dep_config.amount}">

					<label data-toggle="ripple" class="btn btn-outline-default{if $target_dep_config.target_type eq 'all_month'} active{/if}" for="all_dep_month_{$uid}">Cả năm</label>	

				</div>

			</div>

			<div class="form-group form-row mb-2">

				<div class="col-5">

					<label for="name" class="form-label mb-1">SL. Giao dịch</label>

					<input type="text" autocomplete="off" name="target_dep_config[quantity]" class="form-control numberonly price-In required input_value" placeholder="Nhập mục tiêu" value="{$target_dep_config.quantity}">

				</div>

				<div class="col-7">

					<label for="name" class="form-label mb-1">Doanh số</label>

					<div class="input-group input-group-merge">

						<input type="text" autocomplete="off" name="target_dep_config[amount]" class="form-control required price-In numberonly input_value" placeholder="Nhập doanh số" value="{$target_dep_config.amount}">

						<span class="input-group-text">{$clsISO->getRate()}</span>

					</div>

				</div>

			</div>

			<div class="divider my-2">

				<div class="divider-text">Mục tiêu cá nhân</div>

			</div>

			{/if}

			<div class="form-group mb-2">

				<label for="name" class="form-label mb-1">Mục tiêu cho</label>

				<div class="clearfix"></div>

				<div class="btn-group w-100" role="group" aria-label="Hiển thị">

					<input type="radio" class="btn-check js__target-type" tp="personal" name="target_config[target_type]" value="only_month" id="only_month_{$uid}"{if $target_config.target_type eq 'only_month'} checked{/if} onChange="$Core.dashboard.target_change(this, event)">

					<label data-toggle="ripple" class="btn btn-outline-default{if $target_config.target_type eq 'all_month'} active{/if}" for="only_month_{$uid}">Tháng {$smarty.now|date_format:"%m"}</label>

					<input type="radio" class="btn-check js__target-type" tp="personal" name="target_config[target_type]" id="all_month_{$uid}" value="all_month"{if $target_config.target_type eq 'all_month'} checked{/if} onChange="$Core.dashboard.target_change(this, event)">

					<label data-toggle="ripple" class="btn btn-outline-default{if $target_config.target_type eq 'all_month'} active{/if}" for="all_month_{$uid}">Cả năm</label>	

				</div>

			</div>

			<div class="form-group form-row mb-2">

				<div class="col-5">

					<label for="name" class="form-label mb-1">SL. Giao dịch</label>

					<input type="text" autocomplete="off" name="target_config[quantity]" class="form-control numberonly price-In required input_value" placeholder="Nhập mục tiêu" value="{$target_config.quantity}">

				</div>

				<div class="col-7">

					<label for="name" class="form-label mb-1">Doanh số</label>

					<div class="input-group input-group-merge">

						<input type="text" autocomplete="off" name="target_config[amount]" class="form-control required price-In numberonly input_value" placeholder="Nhập doanh số" value="{$target_config.amount}">

						<span class="input-group-text">{$clsISO->getRate()}</span>

					</div>

				</div>

			</div>

		</div>

		<div class="modal-footer border-top">

			<button type="button" class="btn btn-default" data-bs-dismiss="modal">Đóng</button>

			<button type="button" openFrom="{$openFrom}" uid="{$uid}" onClick="$Core.dashboard.addTargetSales(this,event)" 

			action="_SAVE" class="btn btn-primary">Lưu lại</button>	

		</div>

	</form>

</div>

