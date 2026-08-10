<div class="modal-dialog modal-dialog-centered">
	<form method="post" class="frmIssueBank modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row mb-2">
				<div class="col-6 col-md-6">
					<label class="col-form-label">Số tài khoản</label>
					<input type="text" class="form-control required numberonly" name="account_number" 
					placeholder="Số tài khoản"{if $action eq '_edit'} value="{$oneBank.account_number}"{/if}>
				</div>
				<div class="col-6 col-md-6">
					<label class="col-form-label">Chủ tài khoản</label>
					<input type="text" class="form-control required"{if $action eq '_edit'} value="{$oneBank.account_person}"{/if} name="account_person" placeholder="Chủ tài khoản">
				</div>
			</div>
			<div class="form-row">
				<div class="col-xs-12 col-md-6 pr-0">
					<label class="col-form-label">Ngân hàng</label>
					<select name="bank_name" data-width="100%" class="iso-select2 required">
						{foreach name=i from=$list_banks item = _oBank}
						<option{if $action eq '_edit' && $oneBank.bank_name eq $_oBank.shortName} selected{/if} value="{$_oBank.shortName}">{$_oBank.shortName}</option>
						{/foreach}
					</select>
				</div>
				<div class="col-xs-12 col-md-6">
					<label class="col-form-label">Chi nhánh</label>
					<input type="text" class="form-control" name="location"{if $action eq '_edit'} value="{$oneBank.location}"{/if} placeholder="Tên chi nhánh (nếu có)">
				</div>
			</div>
		</div>
		<div class="modal-footer pt-3 border-top">
			<button type="button" data-toggle="ripple" class="btn btn-md flex-fill btn-warning" data-bs-dismiss="modal">Đóng</button>
			<button type="button" data-toggle="ripple" class="btn btn-md flex-fill btn-primary" onClick="$Core.member.pop_save_bank(this,event)" 
				profile_id="{$_profile_id}" bank_id="{$bank_id}">Lưu lại</button>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	.select2-container{
		z-index:1091 !important
	}
</style>
{/literal}