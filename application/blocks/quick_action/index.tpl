<div class="action" onclick="actionToggle();">
	<span>&#43;</span>
	<ul>
		{if $mod eq 'crm'}
		{if $clsCustomer->isFullPermiss()}
		<li><a class="cursor-pointer" onClick="$Core.crm.data_distribution(this, event)"> 
			<i class="bx bx-user-check"></i>
			Chia khách hàng
		</a></li>
		{/if}
		<li><a class="cursor-pointer" onClick="$Core.crm.open_search(this, event)" gId="{$gId}"> 
			<i class="bx bx-search"></i>
			Tìm kiếm khách hàng
		</a></li>
		{/if}
		<li><a class="cursor-pointer" title="Thêm Checkin" onClick="$Core.openChatBox(this, event)">
			<i class="bx bx-time-five"></i>
			Thêm Checkin
		</a></li>
		{if $clsConfiguration->getValue('gdrive_folder_setapp')}
		{assign var=gdrive_url value=$clsConfiguration->getValue('gdrive_folder_setapp')}

		{if $gdrive_url|strpos:'/view' !== false}
			{assign var=gdrive_url value=$gdrive_url|replace:'/view':'/preview'}
		{/if}
		<li><a class="cursor-pointer" title="Hướng dẫn cài app" data-fancybox="set_app" data-type="iframe" href="{$clsISO->getIframeUrl($gdrive_url)}" target="_blank">
			<i class="bx bx-cog"></i>
			Hướng dẫn cài app
		</a></li>
		{/if}
		<li><a class="cursor-pointer" title="Trợ giúp" href="/help/" target="_blank" 
			mod_page="{$mod}" sub_page="{$sub}" act_page="{$act}">
			<i class="bx bx-help-circle"></i>
			Hướng dẫn sử dụng
		</a></li>
	</ul>
</div>
{literal}
<script>
	function actionToggle() {
		var action = document.querySelector('.action');
		action.classList.toggle('active')
	}
</script>
<style>
	.action {
		position: fixed;
		bottom: 18px; 
		right: 22px;
		z-index:3;
		width: 52px;
		height: 52px;
		cursor: pointer;
		background: var(--main-color);
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-o-user-select: none;
		user-select: none;
		border-radius: 10px;
		z-index:9999999999;
		box-shadow: 0 10px 26px color-mix(in srgb, var(--main-color) 40%, transparent), 0 2px 6px color-mix(in srgb, var(--main-color) 30%, transparent);
	}
	@media screen and (max-width:575px){
		.action{
			bottom:80px !important;
		}
	}
	.action span {
		position: relative;
		width: 100%;
		height: 100%;
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-pack: center;
		-ms-flex-pack: center;
		justify-content: center;
		-webkit-box-align: center;
		-ms-flex-align: center;
		align-items: center;
		color: var(--bs-white);
		font-size: 2.5em;
		-webkit-transition: all .3s ease-in-out;
		transition: all .3s ease-in-out;
	}
	.action ul {
		position: absolute;
		bottom: 55px; right: 0px;
		background: var(--main-color);
		min-width: 220px;
		padding: 20px;
		border-radius: 20px;
		opacity: 0;
		visibility: hidden;
		-webkit-transition: all .3s;
		transition: all .3s;
	}
	.action ul li {
		list-style: none;
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-pack: start;
		-ms-flex-pack: start;
		justify-content: flex-start;
		-webkit-box-align: center;
		-ms-flex-align: center;
		align-items: center;
		padding: 8px 0;
		-webkit-transition: all .3s;
		transition: all .3s;
	}
	.action ul li img {
		height: 25px;
		width: 25px;
		margin-right: 10px;
		opacity: .8;
		-webkit-transition: all .5s;
		transition: all .5s;
		-webkit-transform: translateY(7px);
			transform: translateY(7px);
	}
	.action ul li img:hover {
		opacity: 1;
		-webkit-transform: scale(1.2);
			transform: scale(1.2);
	}
	.action ul li a {
		font-weight:500;
		text-decoration: none;
		color: var(--bs-white);
	}
	.action.active span {
		-webkit-transform: rotate(135deg);
			transform: rotate(135deg);
	}
	.action.active ul {
		right: 0px;
		opacity: 1;
		visibility: visible;
	}
	.action.active li {
		list-style: none;
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-pack: start;
		-ms-flex-pack: start;
		justify-content: flex-start;
		-webkit-box-align: center;
		-ms-flex-align: center;
		align-items: center;
		padding: 10px 0;
		-webkit-transition: all .5s;
		transition: all .5s;
	}
	.action.active li a:hover{
		color:#000;
	}
	.action.active li:not(:last-child) {
		border-bottom: 1px solid white;
	}
</style>
{/literal}