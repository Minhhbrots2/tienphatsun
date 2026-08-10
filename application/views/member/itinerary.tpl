<div class="container-xxl flex-grow-1 pt-3 container-p-y">
	<div class="row">
		<div class="col-12 col-xxxl-8 offset-xxxl-2 col-lg-10 offset-lg-1">
			<div class="wrapper">
				<div class="d-flex justify-content-{if $deviceType eq 'phone'}start{else}center{/if} mb-5">
					<div class="NhCXmrsQRv w-px-100 h-px-100 border border-5 bg-border-main rounded-pill overflow-hidden">
						<img src="{$clsProfile->getAvatar($profile_id,$oneProfile,100,100)}" 
							onerror="this.src='{$URL_IMAGES}/avatars/1.png'" class="w-100 h-100" />
					</div>
				</div>
				<div class="center-line"></div>
				{foreach from=$list_timelines item = _oMoc name = i}
				<div class="row row-{if $smarty.foreach.i.iteration%2 eq '0'}2{else}1{/if}">
					<section>
						<i class="icon {$_oMoc.icon}"></i>
						<div class="details">
							<span class="title">{$_oMoc.title}</span>
							<span class="text-muted">{$clsISO->convertTimeToText($_oMoc.reg_date)}</span>
						</div>
						<p>{$_oMoc.content}</p>
					</section>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	body{
		background: linear-gradient(300deg,#9f223a,#400007,#716201);
		background-size: 180% 180%;
		animation: gradient-animation 18s ease infinite;
	}
	@keyframes gradient-animation {
	  0% {
		background-position: 0% 50%;
	  }
	  50% {
		background-position: 100% 50%;
	  }
	  100% {
		background-position: 0% 50%;
	  }
	}
</style>
{/literal}