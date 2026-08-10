<div class="main-content container">
	<div class=" pd5  ">
        <ol class="breadcrumb breadcrumb-arrows">
            <li><a href="/" target="_self">Trang chủ</a></li>
            <li><a href="/profile.html">Trang cá nhân</a></li>
            <li class="active"><span>Danh sách yêu thích</span></li>
        </ol>
    </div>
    <div class="member-content">
        <div class="row">
        	<div class="col-lg-3 col-md-3 col-xs-12">
            	{$core->getBlock('member_left_profile')}
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12">
            	<div class="box-title-collection m-b-15 clearfix">
                	<div class="box-title-collection__title">
                    	<h1>Danh sách yêu thích (<span class="badge" id="totalWishlist">{$total}</span>)</h1>
                    </div>
                </div>
                <div class="clearfix"></div>
                {if $total eq '0'}
                    <div class="nodata-info">Chưa có sản phẩm nào !!</div>
                {else}
                <ul>
                	{section name=i loop=$lstWishlist}
                    {if $lstWishlist[i].type eq '_product'}
                	{assign var = _productName value = $clsProduct->getTitle($lstWishlist[i].for_id)}
                    {assign var = _productLink value = $clsProduct->getLink($lstWishlist[i].for_id)}
                    {assign var = _productPrice value = $clsProduct->getPrice($lstWishlist[i].for_id,true)}
                    {assign var = _productImage value = $clsProduct->getImage($lstWishlist[i].for_id,600,400)}
                    <li class="row form-group clearfix">
                    	<div class="col-md-3">
                        	<div class="thumbnail">
                            	<img src="{$_productImage}" class="img-responsive" />
                            </div>
                        </div>
                        <div class="col-md-9">
                      		<div class="entry">
                            	<h3 class="entry-title"><a href="{$_productLink}" title="{$_productName}">{$_productName}</a></h3>
                                <div class="db-info">
                                    <span class="price-info product-price">
                                    	<strong>{$_productPrice}</strong> {$clsISO->getRate()}
                                    </span>
                                    {if $clsProduct->checkIsOffer($lstWishlist[i].for_id)}
                                    <span class="discount-info">{$clsProduct->getRateOffer($lstWishlist[i].for_id)}</span>
                                    {/if}
                                    <span class="star-info">
                                    	{$clsProduct->getHTMLRateStar($lstWishlist[i].for_id, true)}
                                    </span>
                                    <div class="order-info__success">
                                    	<img src="{$URL_IMAGES}/cart.png?v={$upd_version}" />
                                    	{$clsProduct->getTotalBooking($lstWishlist[i].for_id)}
                                    </div>
                                    <span class="divisor">|</span>
                                    <span class="like-info">
                                    	<a class="like liked" data-type="_product" data-for_id="{$lstWishlist[i].for_id}" href="javascript:void(0);">Thích</a>
                                    </span>
                                </div>
                                <div class="clearfix m-t-10"></div>
                                <div class="text">{$clsProduct->getIntro($lstWishlist[i].for_id)}</div>
                            </div>
                        </div>
                    </li>
                    {else}
                    
                    {/if}
                    {/section}            
                </ul>
                {/if}
            </div>
       	</div>
    </div>
</div>
{literal}
<style type="text/css">
	.entry{}
	.entry > .entry-title{
		font-size:16px;
		color:#333;
	}
	.db-info {
		display:inline-block;
		width:100%;
	}
	.db-info:after,
	.db-info:before{
		display:table;
		clear:both;
		content:"";
	}
	.db-info > .price-info{
		float:left;
		display:inline-block;
		margin:0 10px 0 0;
	}
	.db-info > .price-info > strong{
		font-size:16px;
	}
	.db-info > .star-info{
		display:inline-block;
		margin-top:2px;
		float:left;
		margin-right:10px;
	}
	.db-info > .order-info__success{
		float:left;
	}
	.db-info > .divisor{
		display:inline-block;
		padding:0px 5px;
		float:left;
	}
	.nodata-info{
		color:#666;
		text-align:center;
		padding:20px 0px;
	}
	
</style>
{/literal}