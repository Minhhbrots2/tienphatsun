<div class="col-12">
	<div class="card no-shadow mb-sm-2 mb-4 pb-sm-2 pb-4" >
		{assign var=uid value=$clsISO->getUniqid()}
		<div class="card-body">		
			<div class="d-flex justify-content-center align-items-center gap-2 mb-2">
				<h3 class="card-title text-main d-flex align-items-center gap-2 justify-content-center mb-0">
					<i class='bx bxs-phone fs-20'></i> Đầu mối hỗ trợ
				</h3>
				{if $clsISO->checkPermission('edit_contact_footer')}
				<button class="btn btn-icon btn-sm top-0 right-0" type="button" onClick="$Core.contact.open(this,event)">
					<i class='bx bx-edit-alt'></i>
				</button>
				{/if}
			</div>
			{if !empty($ContactFooter)}
			<div class="form-row row-cols-xxl-3 row-cols-xxxl-4 row-cols-lg-3">
				{foreach name=i from=$ContactFooter item = _oContact}
				<div class="col{if $smarty.foreach.i.iteration gt $number_show} toggleItem d-none{/if}">
					<div class="contact-box position-relative border mb-2 p-2 rounded-1">
						<div class="d-flex flex-column">
							<h3 class="text-fs-16 xs:text-fs-14 mb-2">{$_oContact.title}</h3>
							<div class="d-flex align-items-center gap-1 gap-lg-2">
								<a class="d-flex align-items-center text-nowrap gap-1 contact-link text-link" target="_blank" 
									href="https://zalo.me/{$_oContact.phone}">
									<img src="{$URL_IMAGES}/zalo_chat.png" class="w-px-15" />
									<span class="text-fs-13">{$_oContact.name}</span>
								</a>
								<a class="d-flex align-items-center gap-1 contact-link text-link" href="tel:{$_oContact.phone}">
									<i class="bx bx-phone"></i>
									<span class="text-fs-13">{$_oContact.phone}</span>
								</a>
							</div>
						</div>
					</div>
				</div>
				{/foreach}
			</div>
			<div class="d-flex align-items-center justify-content-center">
				<button data-toggle="ripple" onClick="$Core.contact.collapsed(this, event)" 
					class="btn btn-sm px-3 btn-outline-default rounded-pill">
					<i class='bx bx-chevron-down'></i>
					<span>Xem thêm</span>
				</button>
			</div>
			{/if}
		</div>
	</div>
</div>
{literal}
<style>
	.contact-link,
	.contact-link:hover{
		text-decoration:none;
	}
	.contact-link{
		background: #f5f5f5;
		padding: 2px 8px;
		border-radius: 20px;
	}
	.contact-box:after{
		content:'';
		position:absolute;
		top:9px; right:9px;
		width:10px; height:10px;
		background:#EEE;
		border-radius:50%;
	}
</style>
{/literal}
