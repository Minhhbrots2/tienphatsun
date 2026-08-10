<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
	<form class="modal-content modal-content-clb">
		<div class="modal-header">
			<h5 class="modal-title">{$oneGroup.title} <br />
				<small class="text-muted text-fs-10">Tổng thành viên : {$total_members}</small>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="table-wrapper">
				<table class="table"> 
					<thead><tr>
						{if $deviceType ne 'phone'}
						<th class="align-center w-px-40 text-white h-px-35">No</th>{/if}
						<th class="align-center text-left h-px-35 text-white">Họ và tên</th>
						<th class="align-center w-px-40 text-center h-px-35 text-white">Số GD</th>
						<th class="align-center w-px-80 text-center h-px-35 text-white">Doanh số</th>
					</tr></thead>
					{foreach name=i from=$list_members item = _oM}
					<tr class="nohover">
						{if $deviceType ne 'phone'}
						<td class="align-center text-center text-white">{$smarty.foreach.i.iteration}</td>{/if}
						<td>
							<div class="d-flex gap-2 align-items-center">
								<img class="avatar avatar-xxs rounded-pill" src="{$clsProfile->getAvatar($_oM.staff_id, $_oM)}" />
								<div class="d-flex flex-column text-white gap-0">
									<h4 class="text-fs-13 mb-0">{$clsProfile->getFullName($_oM.staff_id, $_oM)}</h4>
									<div class="d-flex align-items-center gap-2 text-fs-10">{$_oM.department_name}</div>
								</div>
							</div>
						</td>
						<td class="align-center text-center text-white">{$_oM.total_billings}</td>
						<td class="align-center text-center text-white">{$clsISO->shortNumber($_oM.total_sales)}</td>
					</tr>
					{/foreach}
				</table>
			</div>
		</div>
		<div class="modal-footer justify-content-center">
			<button type="button" class="btn btn-block w-px-150 xs:w-100 btn-outline-default" data-bs-dismiss="modal">
				<i class="bx bx-x"></i> Đóng lại
			</button>
		</div>
	</form>
</div>
{if $clb_id eq $smarty.const._GROUP_NS_CLUB}
<style>
	.modal-content-clb .modal-body{
		color:var(--bs-white);
		background-color:rgb(159,124,3);
		background-image:url('/application/themes/images/icons/logo-future-starter.png');
		background-position:center center;
		background-repeat:no-repeat;
		background-size:105px;
	}
</style>
{else}
<style>
	.modal-content-clb .modal-body{
		color:var(--bs-white);
		background-color:rgb(2,65,52);
		background-image:url('/application/themes/images/icons/logo-future-diamond.png');
		background-position:center center;
		background-repeat:no-repeat;
		background-size:105px;
	}
</style>
{/if}