<a data-toggle="ripple" href="{$PCMS_URL}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $mod eq 'home' && $act eq 'default'}text-main{else}text-dark{/if}">
	<div class="icon"><i class="menu-icon tf-icons bx bx-home me-0"></i></div>
	<span class="fs-12 {if $mod eq 'home' && $act eq 'default'}text-main{else}text-dark{/if} text-nowrap">Trang chủ</span>
</a>
{if $clsISO->checkPermissionGroup('DIRECTOR') eq '1'}
<a data-toggle="ripple" href="{$PCMS_URL}/bang-hang/" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $mod eq 'home' && $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if}">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 {if $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if} text-nowrap">Bảng hàng</span>
</a>
<a href="{$clsISO->getLink('exclusive')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="{$clsConfiguration->getValue('LogoWhite')}" width="23px" class="h-100 w-100" style="object-fit: contain"/>
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 {if $mod eq 'tool'}text-main{else}text-dark{/if} text-nowrap">Check-In</span>
</a>
<a data-toggle="ripple" href="{$clsISO->getLink('tool')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $mod eq 'crm'}text-main{else}text-dark{/if}" data-toggle="ripple">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 {if $mod eq 'crm'}text-main{else}text-dark{/if} text-nowrap">Tìm kiếm</span>
</a>
{elseif $clsISO->checkPermissionGroup('SALE_DIRECTOR') eq '1'}
<a data-toggle="ripple" href="{$PCMS_URL}/bang-hang/"class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if}">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 {if $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if} text-nowrap">Bảng hàng</span>
</a>
<a href="{$clsISO->getLink('exclusive')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="{$clsConfiguration->getValue('LogoWhite')}" width="23px" class="h-100 w-100" style="object-fit: contain" />
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 {if $mod eq 'tool'}text-main{else}text-dark{/if} text-nowrap">Check-In</span>
</a>
<a href="{$clsISO->getLink('tool')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $mod eq 'crm'}text-main{else}text-dark{/if}">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 {if $mod eq 'crm'}text-main{else}text-dark{/if} text-nowrap">Tìm kiếm</span>
</a>
{elseif $clsISO->checkPermissionGroup('ADMIN_PROJECT') eq '1'}
<a data-toggle="ripple" href="{$PCMS_URL}/bang-hang/" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $mod eq 'home' && $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if}">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 {if $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if} text-nowrap">Bảng hàng</span>
</a>
<a href="{$clsISO->getLink('exclusive')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="{$clsConfiguration->getValue('LogoWhite')}" width="23px" class="h-100 w-100" style="object-fit: contain" />
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 {if $mod eq 'tool'}text-main{else}text-dark{/if} text-nowrap">Check-In</span>
</a>
<a href="{$clsISO->getLink('tool')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $mod eq 'crm'}text-main{else}text-dark{/if}">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 {if $mod eq 'crm'}text-main{else}text-dark{/if} text-nowrap">Tìm kiếm</span>
</a>
{elseif $clsISO->checkPermissionGroup('ACCOUNTANT') eq '1'}
<a data-toggle="ripple" href="{$PCMS_URL}/bang-hang/" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if}">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 {if $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if} text-nowrap">Bảng hàng</span>
</a>
<a href="{$clsISO->getLink('exclusive')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="{$clsConfiguration->getValue('LogoWhite')}" width="23px" class="h-100 w-100" style="object-fit: contain" />
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 {if $mod eq 'tool'}text-main{else}text-dark{/if} text-nowrap">Check-In</span>
</a>
<a href="{$clsISO->getLink('tool')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $mod eq 'crm'}text-main{else}text-dark{/if}">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 {if $mod eq 'crm'}text-main{else}text-dark{/if} text-nowrap">Tìm kiếm</span>
</a>
{else}
<a data-toggle="ripple" href="{$PCMS_URL}/bang-hang/" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if}">
	<div class="icon"><i class="menu-icon tf-icons bx bx-table me-0"></i></div>
	<span class="fs-12 {if $sub eq 'project' && $act eq 'stock_list'}text-main{else}text-dark{/if} text-nowrap">Bảng hàng</span>
</a>
<a href="{$clsISO->getLink('exclusive')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center text-main">
	<div class="icon circle is-effect mb-1" style="background:#E6A400">
		<img src="{$clsConfiguration->getValue('LogoWhite')}" width="23px" class="h-100 w-100" style="object-fit: contain"/>
	</div>
	<span class="fs-12 text-nowrap">Độc quyền</span>
</a>
<a data-toggle="ripple" onClick="$Core.openChatBox(this, event)" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center">
	<div class="icon"><i class="menu-icon tf-icons bx bx-message-square-dots me-0"></i></div>
	<span class="fs-12 {if $mod eq 'tool'}text-main{else}text-dark{/if} text-nowrap">Check-In</span>
</a>
<a data-toggle="ripple" href="{$clsISO->getLink('tool')}" class="bottom-navbar-item flex-flow d-flex flex-column align-items-center justify-content-center {if $mod eq 'crm'} text-main{else}text-dark{/if}">
	<div class="icony"><i class="menu-icon tf-icons bx bx-search me-0"></i></div>
	<span class="fs-12 {if $mod eq 'crm'}text-main{else}text-dark{/if} text-nowrap">Tìm kiếm</span>
</a>
{/if}