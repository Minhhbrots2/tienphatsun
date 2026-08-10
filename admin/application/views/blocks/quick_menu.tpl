{literal}

<script type="text/javascript">

	$(function(){

		$(document).on('click', 'a.item-header', function(ev){

			var $_this = $(this),

				$_sub = $_this.parent().find('.submenu');

			if($_sub.is(':visible')){

				$_sub.stop(false,true).slideUp();

				$_this.find('.arrow').removeClass('fa-angle-up').addClass('fa-angle-down');

			}else{

				$('.submenu:visible').stop(false,true).slideUp();

				$('.arrow').removeClass('fa-angle-up').addClass('fa-angle-down');

				$_sub.stop(false,true).slideDown();

				$_this.find('.arrow').removeClass('fa-angle-down').addClass('fa-angle-up');

			}

		});

	});

</script>

{/literal}

<div class="sidebar--nav">

	<ul class="nav nav-list">

		<li class="{if $mod eq 'home'}active{/if}">

			<a data-toggle="ripple" href="{$PCMS_URL}" title="{$PAGE_NAME}" style="color:#f58220">

				<i class="fa fa-home"></i>

				<span class="menu-text bold">{$core->get_Lang('home')}</span>

			</a>

		</li>

		{assign var=lstAdminButtonLeft value=$clsAdminButton->getAll('is_active=1 and is_group=1 and _type="_LEFT" order by order_no asc')}

		{section name=k loop=$lstAdminButtonLeft}

			{assign var=id value=$lstAdminButtonLeft[k].adminbutton_id}

			{assign var=lstAdminButtonLeftChild value=$clsAdminButton->getChild($id)}

			{if $clsAdminButton->checkConfiguration($lstAdminButtonLeft[k].CONFIGURATION_KEY)}

			<li class="{if $mod eq $lstAdminButtonLeft[k].mod_page}active{/if}">

				<a data-toggle="ripple" href="{$clsAdminButton->getRootURL($lstAdminButtonLeft[k].adminbutton_id)}" class="item-header {$lstAdminButtonLeft[k].class_page}">

					<i class="{$lstAdminButtonLeft[k].class_iconpage}"></i>

					<span class="menu-text"> {$core->get_Lang($lstAdminButtonLeft[k].title_page)}</span>

					{if $lstAdminButtonLeftChild[0].adminbutton_id ne ''}<b class="arrow fa fa-angle-down"></b>{/if}

				</a>

				{if !empty($lstAdminButtonLeftChild)}

				<div class="submenu" {if $smarty.section.k.index eq 0}style="display:block"{/if}>

					<ul class="nav-list sublist">

						{section name=i loop=$lstAdminButtonLeftChild}

						{if $clsAdminButton->checkConfiguration($lstAdminButtonLeftChild[i].CONFIGURATION_KEY)}

						<li{if $lstAdminButtonLeftChild[i].class_page ne ''} class="{$lstAdminButtonLeftChild[i].class_page}"{/if}>

							<a data-toggle="ripple" title="{$core->get_Lang($lstAdminButtonLeftChild[i].title_page)}" href="{$clsAdminButton->getURL($lstAdminButtonLeftChild[i].adminbutton_id)}"><span><i class="{$lstAdminButtonLeftChild[i].class_iconpage}"></i> {$core->get_Lang($lstAdminButtonLeftChild[i].title_page)}</span>

							</a>

						</li>

						{/if}

						{/section}

					</ul>

				</div>

				{/if}

			</li>

			{/if}

		{/section}

		<li class="hidden-sm hidden-xs d-none">

			<a data-toggle="ripple" href="{$PCMS_URL}/index.php?mod=feedback" title="{$PAGE_NAME}">

				<img src="//bizweb.dktcdn.net/assets/admin/images/feedback.png" width="20px">

				<span class="menu-text bold">{$core->get_Lang('Feedback')}</span>

			</a>

		</li>

	</ul>

	<div class="nav-user ">

		<div class="separate"></div>

		<div class="account-info{if $mod eq 'setting'} active{/if}">

			<a data-toggle="ripple" href="{$PCMS_URL}/index.php?mod=setting" id="submenu__link_settings" class="clearfix">

				{$core->makeIcon('cog fs-16')}

				<span class="menu-name">{$core->get_Lang('Config')}</span>

			</a>

		</div>

	</div>

</div>