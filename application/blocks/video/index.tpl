{if $mod eq 'home'}
	<div class="video-player">
		<iframe width="560" height="370" src="{$clsConfiguration->getValue('video_homepage')}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</div>
{else}
	<div class="player">
		<iframe width="100%" height="300" src="{$clsConfiguration->getValue('video_homepage')}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe>
	</div>			
	{literal}
	<style>
		.section.video{ padding-bottom:0px !important;}
		.player{ width:100%; min-height:240px;}
	</style>
	{/literal}
{/if}
