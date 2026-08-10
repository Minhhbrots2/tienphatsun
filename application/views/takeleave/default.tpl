<!-- Start JS -->
{$scriptJs}
<!-- End JS -->
<div class="container-xxl flex-grow-1 container-p-y pt-2">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
		<div class="zOGxLMosQm">
			<h4 class="fw-bold mb-1">Nghỉ phép</span></h4>
			<span class="text-muted">Có <strong class="text-danger total_record">{$total_record}</strong> nghỉ phép</span>
		</div>
		<div class="vCaBQODfmW d-flex align-items-center gap-1">
			<button type="button" class="btn btn-outline-primary create-new-takeleave" onClick="$Core.takeleave.open(this, event)" takeleave_id="0"><span>+ Thêm mới</span></button>
			<div class="dropdown">
				<button type="button" class="btn btn-icon btn-default hide-arrow dropdown-toggle" 
				data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
					<i class="bx bx-filter"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">
					<div class="p-3">
						<div data-field="date_type" class="input-group mb-2 ml-sm-2">
							<select class="form-control form-select search_field">
								<option value="reg_date"{if $date_type eq 'reg_date'} selected{/if}>Ngày tạo</option>
								<option value="start_date"{if $date_type eq 'start_date'} selected{/if}>Ngày nghỉ</option>
							</select>
						</div>
						<div class="input-group input-date-picker mb-2">
							<i class="ico ico-calendar"></i>
							<input type="text" value="{$start_date}" class="form-control from_date search_field w-px-100" 
							placeholder="Từ ngày" data-field="start_date">
							<input type="text" value="" class="form-control to_date search_field w-px-100" 
							placeholder="Đến ngày" data-field="end_date">
						</div>
						
					   <div class="form-group w-100 mb-2">
							<select onChange="select_profile_in_department(this, event)" toId="slb_Profile_Id" class="form-control w-100 form-select search_field" data-field="department_id">{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$oneItem.department_id,'Phòng ban')}
							</select>
						</div>
						<div class="form-group mb-2">
							<select id="slb_Profile_Id" class="iso-select2 search_field" data-field="user_id" data-width="100%" data-placeholder="Nhân viên" data-allow-clear="true">
								<option value="0">Nhân viên</option>
								{foreach from=$lstUser item=item name=item}
								<option value="{$item.profile_id}">
									{$clsProfile->getFullName($item.profile_id,$item)}
								</option>
								{/foreach}
							</select>
						</div>
						<div class="form-group mb-2">
							<select class="form-control form-select search_field" data-field="is_approval">
								<option value="" {if empty($smarty.get.is_approval)}selected{/if}> TT.Xét duyệt</option>
								<option value="0" {if $smarty.get.is_approval eq '0'}selected{/if}>Không duyệt</option>
								<option value="1" {if $smarty.get.is_approval eq '1'}selected{/if}>Đã duyệt</option>
								{if $has_unapproved}
								<option value="2" {if $smarty.get.is_approval eq '2'}selected{/if}>Chưa duyệt</option>
								{/if}
							</select>
						</div>
						<div class="form-group mb-2">
							<input type="hidden" class="search_field" data-field="is_staff" value="{$smarty.get.is_staff}" />
							<input type="hidden" class="search_field" data-field="is_curator" value="{$smarty.get.is_curator}" />
							<input type="hidden" class="search_field" data-field="is_head_of_dep" value="{$smarty.get.is_head_of_dep}" />
							<input type="hidden" class="search_field" data-field="is_director_of_dep" value="{$smarty.get.is_director_of_dep}" />
							<input type="hidden" class="search_field" data-field="is_director" value="{$smarty.get.is_director}" />
							<input type="hidden" class="search_field" data-field="is_hrad" value="{$smarty.get.is_hrad}" />
							<button type="button" class="btn btn-success filterTakeLeave">
								<i class="bx bx-search"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="box-body">
				<div class="holder_take_leave"></div>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.mr-0{ margin-right:0;}
	.ui-datepicker{z-index:9999 !important;}
	@media screen and (min-width:1360px){
		.table-ilooca th:nth-child(1){ width:5%;}
		.table-ilooca th:nth-child(2){ width:15%;}
		.table-ilooca th:nth-child(3){ width:15%;}
		.table-ilooca th:nth-child(4){ width:15%;}
		.table-ilooca th:nth-child(5){ width:10%;}
		.table-ilooca th:nth-child(6){ width:15%;}
	}
</style>
<script type="text/javascript">
	$(function(){
		$_document.on('click', '#mega-dropdown', function (e) {
			e.stopPropagation();
		});
	});
</script>
{/literal}