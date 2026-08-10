{foreach from=$drill_rows item=_x}
<div class="crm-ld-urgent">
    <div class="avatar crm-ld-urgent-av">
        <span class="avatar-initial rounded-circle bg-label-primary">{$_x.initials|escape}</span>
    </div>
    <div class="min-w-0 flex-grow-1">
        <div class="crm-ld-urgent-nm">
            <a class="crm-ld-cuslink" customer_id="{$_x.customer_id}" onclick="$Core.crm.open_customer(this,event)" title="Xem chi tiết & hoạt động">{$_x.name|escape}</a>
        </div>
        <div class="crm-ld-urgent-sub">
            <i class="bx bx-user"></i>
            <span class="text-truncate">{$_x.sale|escape}</span>
        </div>
    </div>
    <a class="crm-ld-urgent-act" customer_id="{$_x.customer_id}" onclick="$Core.crm.view_activity(this,event)" title="Xem hoạt động">
        <i class="bx bx-bell"></i>
    </a>
    {if $_x.phone}
    <a href="tel:{$_x.phone|escape}" class="crm-ld-urgent-call" title="Gọi {$_x.name|escape}">
        <i class="bx bx-phone"></i>
    </a>
    {/if}
</div>
{/foreach}
