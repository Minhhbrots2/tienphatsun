{if $template_type eq '_modal'}
<div class="modal right fade show" id="chatbox_{$sender_id}" role="dialog">
	<div class="modal-dialog"><form class="modal-content" method="post" action="#" enctype="multipart/form-data">
		<div class="modal-header d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<img src="{$oneSender.avatar}" class="avatar rounded-pill" />
				<div class="d-flex flex-column gap-1">
					<h5 class="mb-0 fs-6">{$oneSender.zaloName}</h5>
					<small class="text-muted"><i class="bx bx-chat"></i> Chat với người này</small>
				</div>
			</div>
			<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div id="holder_chat_{$sender_id}" class="modal-body bg-lighter scroller">
			{section name=i loop=$list_preloaders}
			<div class="mb-2 d-flex gap-2{if $smarty.section.i.iteration%2 eq '0'} flex-row-reverse{/if} ng-star-inserted">
				<div class="cursor-pointer ng-star-inserted">
					<div class="avatar animate-bg rounded-pill w-px-40 h-px-40"></div>
				</div>
				<div class="message-bubble {if $smarty.section.i.iteration%2 eq '0'}me{else}thread{/if}">
					<div class="message-text mb-2">
						<div class="animate-bg w-px-150 mb-1 rounded-2 h-px-15"></div>
						<div class="animate-bg w-px-100 mb-1 rounded-2 h-px-15"></div>
						<div class="animate-bg w-px-50 rounded-2 h-px-15"></div>
					</div>
					<div class="animate-bg w-px-50 rounded-2 h-px-15"></div>
				</div>
			</div>
			{/section}
		</div>
		<div class="modal-footer p-0">
			<textarea class="form-control border-0 required no-focus" onkeydown="$Core.global.sop.send_chat(this, event)" 
				placeholder="Nhập nội dung tin nhắn..." name="message" rows="4" sender_id="{$sender_id}"></textarea>
		</div>
	</div></form>
</div>
{else}
	{if !empty($list_chats)}
		<!-- <div class="divider">
			<div class="divider-text">Text</div>
		</div> -->
		{foreach from=$list_chats item = _oChat}
			{if $_oChat.is_me eq '1'}
			<div class="mb-2 d-flex gap-2 flex-row-reverse ng-star-inserted">
				<div class="cursor-pointer ng-star-inserted">
					<img alt="{$oneOwner.zaloName}" class="avatar rounded-pill" src="{$oneOwner.avatar}" 
						onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'">
				</div>
				<div class="message-bubble me">
					<div class="message-text">{$_oChat.message|nl2br}</div>
					<small class="message-time text-muted">{$clsISO->getTimeAgo($_oChat.reg_date)}</small>
				</div>
			</div>
			{else}
			<div class="mb-2 d-flex gap-2 ng-star-inserted">
				<div class="cursor-pointer ng-star-inserted">
					<img alt="{$oneSender.zaloName}" class="avatar rounded-pill" src="{$oneSender.avatar}" 
						onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'">
				</div>
				<div class="message-bubble thread">
					<div class="message-text">{$_oChat.message|nl2br}</div>
					<small class="message-time text-muted">{$clsISO->getTimeAgo($_oChat.reg_date)}</small>
				</div>
			</div>
			{/if}
		{/foreach}
	{else}
		<div class="d-flex flex-column align-items-center justify-content-center h-100">
			<img src="{$URL_IMAGES}/DataEmpty.svg" class="w-px-150" />
			<p class="text-muted">Chưa có tin nhắn nào</p>
		</div>
	{/if}
{/if}
