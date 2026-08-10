{literal}
<style type="text/css">
	.sticky-button .sticky-menu{z-index:2;position:fixed;bottom:0px;right:15px;-webkit-transform:translateY(-50%);-moz-transform:translateY(-50%);-ms-transform:translateY(-50%);-o-transform:translateY(-50%);transform:translateY(-50%);list-style:none; z-index:9}
	.transparent{opacity:0;visibility:hidden}
	.sticky-button .sticky-menu li{-webkit-transition:.3s;-moz-transition:.3s;-o-transition:.3s;transition:.3s;position:relative;border:0!important}
	.sticky-button .sticky-menu li.transparent{opacity:0}
	.sticky-button .sticky-menu li+li{margin-top:10px}
	.sticky-button .sticky-menu a{width:40px;height:40px;background:#145a8d;color:#fff;display:block;text-align:center;line-height:36px;cursor:pointer;-webkit-border-radius:50%;-moz-border-radius:50%;-ms-border-radius:50%;-o-border-radius:50%;border-radius:50%;padding:12px; font-size:16px; text-decoration:none;}
	.sticky-button .sticky-menu a.zalo{ font-weight:bold; font-size:20px; line-height:16px;}
	.sticky-button .sticky-menu li:hover a{background:#f78222}
	.sticky-button .sticky-menu li .tooltip{position:absolute;right:54px;display:block;line-height:24px;background:#7e7e7e;top:12px;padding:0 15px;visibility:hidden;color:#fff;white-space:nowrap;font-size:12px;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;-o-border-radius:3px;border-radius:3px;opacity:0;-webkit-transition:.3s;-moz-transition:.3s;-o-transition:.3s;transition:.3s}
	.sticky-button .sticky-menu li:hover .tooltip{opacity:1;visibility:visible}
	.sticky-button .sticky-menu li .tooltip:after{content:'';width:8px;height:8px;background:#7e7e7e;-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:rotate(45deg);position:absolute;right:-4px;top:8px}
</style>
{/literal}
{if $lstButton}
<div class="sticky-button">
	<ul class="sticky-menu">
		{section name=i loop=$lstButton}
		{if $lstButton[i].status eq '1'}
		<li>
			{if $lstButton[i].type eq 'phone'}
            <a href="tel:{$lstButton[i].value}" rel="noopener noreferrer">{$core->makeIcon('phone')}</a>
			{elseif $lstButton[i].type eq 'email'}
			<a href="mailto:{$lstButton[i].value}"  target="_blank"rel="noopener noreferrer">{$core->makeIcon('envelope')}</a>
			{elseif $lstButton[i].type eq 'skype'}
			<a href="skype:{$lstButton[i].value}?call" target="_blank" rel="noopener noreferrer">{$core->makeIcon('skype')}</a>
			{elseif $lstButton[i].type eq 'messager'}
			<a href="//m.me/{$lstButton[i].value}" target="_blank" rel="noopener noreferrer">{$core->makeIcon('comments')}</a>
			{elseif $lstButton[i].type eq 'facebook'}
			<a href="//www.facebook.com/{$lstButton[i].value}" target="_blank" rel="noopener noreferrer"> {$core->makeIcon('facebook')}</a>
			{elseif $lstButton[i].type eq 'zalo'}
			<a class="zalo" href="https://zalo.me/{$lstButton[i].value}" target="_blank" rel="noopener noreferrer">Z</a>
			{/if}
            <span class="tooltip">{$lstButton[i].name}</span>
        </li>
		{/if}
		{/section}
	</ul>
</div>
{/if}