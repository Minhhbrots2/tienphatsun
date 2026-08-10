{if $lstPartner[0].partner_id ne ''}
<div class="container">
	<div class="row o-text__heading mb-half">
		<div class="col-xs-12 col-sm-12 col-md-8 offset-lg-2 text-center">
			<span class="text-muted">--- Thương hiệu bán chạy ---</span>
		</div>
	</div>
    <div class="list_brand">
        <div id="owl-brand" class="owl-carousel owl-theme">
            {foreach name=i from=$lstPartner item=partner}
			{assign var = partner_id value = $partner.partner_id}
            <div class="brand-item">
                <a class="brand-link img-shine radius-3" rel="nofollow" target="_blank" title="{$partner.title}">
                    <img class="img-responsive" src="{$partner.image}" alt="{$partner.title}" width="150px" height="150px" />
                </a>
            </div>
            {/foreach}
        </div>
    </div>
</div>
{/if}
{literal}
<style type="text/css">
	.brand-item{}
	.brand-item > .brand-link{
		display:block;
		padding:5px;
		outline:none;
	}
	.list_brand{
		margin-bottom:20px;
	}
	.list_brand .owl-controls{
		display:none;
	}
</style>
{/literal}